<?php
$input = file_get_contents("php://input");
$event = json_decode($input, true);

if ($event['data']['attributes']['status'] === "paid") {

  $paymentId = $event['data']['id'];

  $serverName="JustinePuyot\SQLEXPRESS";
  $connectionOptions=[
    "Database"=>"BOOKLET",
    "Uid"=>"",
    "PWD"=>""
  ];

  $conn = sqlsrv_connect($serverName, $connectionOptions);

  if ($conn) {
    $sql = "
      INSERT INTO FLIGHT_BOOKING
      (DEPARTURE_FROM, ARRIVAL_TO, ADULT, STATUS, PAYMENT_ID, USER_ID)
      VALUES (?, ?, ?, ?, ?, ?)
    ";

    $params = [
      "MNL",
      "CEB",
      1,
      "PAID",
      $paymentId,
      1
    ];

    sqlsrv_query($conn, $sql, $params);
  }
}

http_response_code(200);
