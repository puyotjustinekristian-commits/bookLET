<?php
session_start();

$paymongoKey = "sk_test_QA4VBydYStXnnthnBDBGDk3f";

$airline  = $_SESSION['BOOKING']['airline'];
$duration = $_SESSION['BOOKING']['duration'];
$price    = $_SESSION['BOOKING']['price'];

$amount = intval(str_replace(',', '', $price)) * 100;

$data = [
  "data" => [
    "attributes" => [
      "amount" => $amount,
      "currency" => "PHP",
      "payment_method_allowed" => ["card"],
      "description" => "Flight Booking"
    ]
  ]
];

$ch = curl_init("https://api.paymongo.com/v1/payment_intents");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($data),
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode($paymongoKey . ":")
  ]
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

$clientKey = $result["data"]["attributes"]["client_key"];

header("Location: paymongo_pay.php?key=$clientKey");
exit;
