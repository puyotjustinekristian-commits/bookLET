<?php
session_start();

$secretKey = "sk_test_uy9JVhPCQ7gAGEX9EaH8vR1N";

$serverName = "JustinePuyot\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$priceRaw = $_POST['price'] ?? 0;
$airline = $_POST['airline'] ?? ''; 
$duration = $_POST['duration'] ?? '';
$userId = $_POST['user_id'] ?? 0;

$fromCity = $_POST['from_city'] ?? '';
$toCity = $_POST['to_city'] ?? '';
$departDate = $_POST['departure_date'] ?? null;
$returnDate = !empty($_POST['return_date']) ? $_POST['return_date'] : null;
$adults = $_POST['adults'] ?? 1;

$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$contact = $_POST['contact_number'] ?? '';

$priceClean = str_replace(["₱", ",", " "], "", $priceRaw);
$price = floatval($priceClean);
$amount = intval($price * 100);

if ($amount <= 0) {
    die("Invalid payment amount");
}

$paymentRef = "REF-" . strtoupper(uniqid());

$sql = "INSERT INTO FLIGHT_BOOKING 
        (DEPARTURE_FROM, ARRIVAL_TO, DEPARTURE_DATE, RETURN_DATE, ADULT, FIRST_NAME, LAST_NAME, EMAIL, CONTACT_NUMBER, STATUS, USER_ID, PAYMENT_ID) 
        OUTPUT INSERTED.FLIGHT_ID 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', ?, ?)";

    $params = [
        $fromCity, 
        $toCity, 
        $departDate, 
        $returnDate, 
        $adults, 
        $firstName, 
        $lastName, 
        $email, 
        $contact, 
        $userId,
        $paymentRef 
    ];
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $flightId = $row['FLIGHT_ID'];
    
    $data = [
      "data" => [
        "attributes" => [
          "amount" => $amount,
          "currency" => "PHP",
          "description" => "Flight #$flightId ($fromCity to $toCity)",
          "remarks" => "bookLET flight payment",
          "reference_number" => $paymentRef,
          "redirect" => [
            "success" => "http://localhost/bookLET/payments/payment_success.php?ref=" . $paymentRef,
            "failed" => "http://localhost/bookLET/payments/payment_failed.php"
          ]
        ]
      ]
    ];
    
    $ch = curl_init("https://api.paymongo.com/v1/links");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "Authorization: Basic " . base64_encode($secretKey . ":")
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['data']['attributes']['checkout_url'])) {
        $checkoutUrl = $result['data']['attributes']['checkout_url'];
        $linkId = $result['data']['id'];
    
        $monitorUrl = "payment_monitor.php?link_id=" . urlencode($linkId) . 
                      "&ref=" . urlencode($paymentRef) . 
                      "&checkout_url=" . urlencode($checkoutUrl);
        
        header("Location: " . $monitorUrl);
        exit;
} else {
    echo "Payment creation failed";
}
?>
