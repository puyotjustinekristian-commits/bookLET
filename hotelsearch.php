<?php
header("Content-Type: application/json");

$apiKey = "c75088e098msh7e4d5f33e362c85p1654c9jsnda43866fb283";
$apiHost = "booking-com15.p.rapidapi.com";

$city = $_GET['city'] ?? '';
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';

if ($city === '' || $checkin === '' || $checkout === '') {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

/* 1️⃣ Destination */
$destUrl = "https://booking-com15.p.rapidapi.com/api/v1/hotels/searchDestination?query=" . urlencode($city);

$ch = curl_init($destUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-key: $apiKey",
        "x-rapidapi-host: $apiHost"
    ]
]);

$destResponse = curl_exec($ch);
curl_close($ch);

$destData = json_decode($destResponse, true);

if (!isset($destData['data'][0]['dest_id'])) {
    echo json_encode(["error" => "Destination not found"]);
    exit;
}

$destId = $destData['data'][0]['dest_id'];

/* 2️⃣ Hotels */
$hotelUrl = "https://booking-com15.p.rapidapi.com/api/v1/hotels/searchHotels?" . http_build_query([
    "dest_id" => $destId,
    "search_type" => "CITY",
    "arrival_date" => $checkin,
    "departure_date" => $checkout,
    "adults" => 2,
    "room_qty" => 1,
    "page_number" => 1,
    "currency" => "PHP"
]);

$ch = curl_init($hotelUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-key: $apiKey",
        "x-rapidapi-host: $apiHost"
    ]
]);

$hotelResponse = curl_exec($ch);
curl_close($ch);

echo $hotelResponse;
