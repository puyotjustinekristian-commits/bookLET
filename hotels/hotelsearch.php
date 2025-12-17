<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");

$apiKey = "922E638EDB404FB19963861AAA63226B";

$city   = $_GET["city"] ?? "";
$budget = $_GET["budget"] ?? ""; // $, $$, $$$, $$$$

if (trim($city) === "") {
    echo json_encode([]);
    exit;
}

$searchQuery = urlencode($city . " Philippines");

$searchUrl = "https://api.content.tripadvisor.com/api/v1/location/search"
           . "?searchQuery=$searchQuery"
           . "&category=hotels"
           . "&language=en"
           . "&key=$apiKey";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $searchUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["accept: application/json"],
    CURLOPT_TIMEOUT => 15
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$results = $data["data"] ?? [];

$hotels = [];

foreach ($results as $item) {

    if (!isset($item["location_id"])) continue;

    $id = $item["location_id"];

    $detailsUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/details"
                . "?language=en&key=$apiKey";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $detailsUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["accept: application/json"],
        CURLOPT_TIMEOUT => 10
    ]);
    $detailsResponse = curl_exec($ch);
    curl_close($ch);

    if (!$detailsResponse) continue;

    $details = json_decode($detailsResponse, true);

    $priceLevel = $details["price_level"] ?? "";

    if ($budget !== "" && $priceLevel !== $budget) continue;

    $image = "";

    $photoUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/photos"
              . "?language=en&key=$apiKey";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $photoUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["accept: application/json"],
        CURLOPT_TIMEOUT => 10
    ]);
    $photoResponse = curl_exec($ch);
    curl_close($ch);

    $photoData = json_decode($photoResponse, true);

    if (!empty($photoData["data"][0]["images"]["large"]["url"])) {
        $image = $photoData["data"][0]["images"]["large"]["url"];
    }

    $hotels[] = [
        "location_id" => $id,
        "name" => $details["name"] ?? $item["name"],
        "rating" => $details["rating"] ?? "N/A",
        "address" => $details["address_obj"]["address_string"] ?? "Address unavailable",
        "price_level" => $priceLevel,
        "image" => $image
    ];

    if (count($hotels) >= 10) break;
}

echo json_encode($hotels);
