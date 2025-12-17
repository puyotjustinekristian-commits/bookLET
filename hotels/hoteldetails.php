<?php
header("Content-Type: application/json; charset=UTF-8");

$apiKey = "922E638EDB404FB19963861AAA63226B";

if (!isset($_GET["id"]) || trim($_GET["id"]) === "") {
    echo json_encode(["error" => "Missing hotel ID"]);
    exit;
}

$id = $_GET["id"];

$detailsUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/details"
            . "?language=en&key=$apiKey";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $detailsUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["accept: application/json"],
    CURLOPT_TIMEOUT => 15
]);
$detailsResponse = curl_exec($ch);
curl_close($ch);

$details = json_decode($detailsResponse, true);

$reviewsUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/reviews"
            . "?language=en&key=$apiKey";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $reviewsUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["accept: application/json"],
    CURLOPT_TIMEOUT => 15
]);
$reviewsResponse = curl_exec($ch);
curl_close($ch);

$reviewsData = json_decode($reviewsResponse, true);

$photosUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/photos"
           . "?language=en&key=$apiKey";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $photosUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["accept: application/json"],
    CURLOPT_TIMEOUT => 15
]);
$photosResponse = curl_exec($ch);
curl_close($ch);

$photosData = json_decode($photosResponse, true);

$images = [];
if (!empty($photosData["data"])) {
    foreach ($photosData["data"] as $photo) {
        if (isset($photo["images"]["large"]["url"])) {
            $images[] = $photo["images"]["large"]["url"];
        }
        if (count($images) >= 3) break;
    }
}

echo json_encode([
    "name" => $details["name"] ?? "",
    "rating" => $details["rating"] ?? "N/A",
    "address" => $details["address_obj"]["address_string"] ?? "Address unavailable",
    "description" => $details["description"] ?? "No description available.",
    "price_level" => $details["price_level"] ?? "$",
    "images" => $images,
    "reviews" => $reviewsData["data"] ?? []
]);
