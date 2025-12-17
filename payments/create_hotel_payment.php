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


$hotelName = $_POST['hotel_name'] ?? 'Hotel';
$priceRaw = $_POST['price'] ?? 0;

$checkIn = $_POST['checkin'] ?? null;
$checkOut = $_POST['checkout'] ?? null;

$rooms = $_POST['rooms'] ?? 1;

$guests = (int)($_POST['adults'] ?? 1) + (int)($_POST['children'] ?? 0);

$userId = $_SESSION['USER_ID'] ?? 0;

$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$contact = $_POST['contact'] ?? '';


$priceClean = str_replace(["₱", ",", " "], "", $priceRaw);
$price = floatval($priceClean);
$amount = intval($price * 100);

if ($amount <= 0) {
    die("Invalid payment amount");
}

$paymentRef = "HOTEL-" . strtoupper(uniqid());



$sql = "INSERT INTO HOTEL_BOOKING 
        (CHECK_IN, CHECK_OUT, ROOMS, GUESTS, TOTAL_PRICE, PAYMENT_ID, STATUS, FIRST_NAME, LAST_NAME, EMAIL, CONTACT_NUMBER, USER_ID)
        OUTPUT INSERTED.HOTEL_ID
        VALUES (?, ?, ?, ?, ?, ?, 'PENDING', ?, ?, ?, ?, ?)";

$params = [
    $checkIn,
    $checkOut,
    $rooms,
    $guests,
    $price,
    $paymentRef,
    $firstName,
    $lastName,
    $email,
    $contact,
    $userId
];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$bookingId = $row['HOTEL_ID'];


$data = [
    "data" => [
        "attributes" => [
            "amount" => $amount,
            "currency" => "PHP",
            "description" => "$hotelName Booking #$bookingId",
            "remarks" => "bookLET hotel payment",
            "reference_number" => $paymentRef,
            "redirect" => [
                "success" => "http://localhost/bookLET/payments/payment_success_hotel.php?ref=" . $paymentRef,
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


    $monitorUrl = "hotel_payment_monitor.php?link_id=" . urlencode($linkId) . 
                  "&ref=" . urlencode($paymentRef) . 
                  "&checkout_url=" . urlencode($checkoutUrl);
    
    header("Location: " . $monitorUrl);
    exit;
} else {
    echo "Payment creation failed";
    print_r($result);
}
?>
