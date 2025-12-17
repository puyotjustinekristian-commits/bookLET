<?php
$input = file_get_contents("php://input");
$event = json_decode($input, true);

if (
    isset($event['data']['attributes']['status']) &&
    $event['data']['attributes']['status'] === "paid"
) {

    $paymentId = $event['data']['id'];

    $serverName = "JustinePuyot\SQLEXPRESS";
    $connectionOptions = [
        "Database" => "BOOKLET",
        "Uid" => "",
        "PWD" => ""
    ];

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn) {

        $sql = "
            UPDATE FLIGHT_BOOKING
            SET STATUS = 'PAID', PAYMENT_ID = ?
            WHERE STATUS = 'PENDING'
              AND PAYMENT_ID IS NULL
        ";

        $params = [$paymentId];
        sqlsrv_query($conn, $sql, $params);
    }
}

http_response_code(200);
