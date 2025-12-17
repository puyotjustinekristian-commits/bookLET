<?php
header('Content-Type: application/json');

$linkId = $_GET['link_id'] ?? '';
$ref = $_GET['ref'] ?? '';
$secretKey = "sk_test_uy9JVhPCQ7gAGEX9EaH8vR1N";

if (!$linkId) {
    echo json_encode(['status' => 'error', 'message' => 'No Link ID']);
    exit;
}

$ch = curl_init("https://api.paymongo.com/v1/links/" . $linkId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Basic " . base64_encode($secretKey . ":"),
  "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$status = $data['data']['attributes']['status'] ?? 'unpaid';

if ($status === 'paid') {
    
    $serverName = "JustinePuyot\SQLEXPRESS";
    $connectionOptions = [
        "Database" => "BOOKLET",
        "Uid" => "",
        "PWD" => ""
    ];
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn && $ref) {
        $type = $_GET['type'] ?? 'flight';

        if ($type === 'hotel') {
            $sql = "UPDATE HOTEL_BOOKING 
                    SET STATUS = 'PAID', PAYMENT_ID = ? 
                    WHERE PAYMENT_ID = ? OR PAYMENT_ID = ?";
        } else {
            $sql = "UPDATE FLIGHT_BOOKING 
                    SET STATUS = 'PAID', PAYMENT_ID = ? 
                    WHERE PAYMENT_ID = ? OR PAYMENT_ID = ?";
        }
        
        $params = [$linkId, $ref, $linkId];
        sqlsrv_query($conn, $sql, $params);
        sqlsrv_close($conn);
    }
}

echo json_encode(['status' => $status]);
?>
