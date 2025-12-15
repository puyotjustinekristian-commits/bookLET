<?php
header("Content-Type: application/json; charset=UTF-8");

$apiKey = "922E638EDB404FB19963861AAA63226B";

if (empty($_GET["city"])) {
    echo json_encode([]);
    exit;
}

$city = urlencode("Cafe in " . $_GET["city"] . " Philippines");

/* =============================
   STEP 1: SEARCH CAFES
============================= */
$searchUrl = "https://api.content.tripadvisor.com/api/v1/location/search"
           . "?searchQuery=$city"
           . "&category=restaurants"
           . "&language=en"
           . "&key=$apiKey";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $searchUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["accept: application/json"]
]);

$searchResponse = curl_exec($ch);
curl_close($ch);

$searchData = json_decode($searchResponse, true);
$results = $searchData["data"] ?? [];

$cafes = [];

/* =============================
   STEP 2: DETAILS + PHOTO
============================= */
foreach ($results as $item) {

    if (!isset($item["location_id"])) continue;
    $id = $item["location_id"];

    /* DETAILS (rating, address) */
    $detailsUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/details"
                . "?language=en&key=$apiKey";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $detailsUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["accept: application/json"]
    ]);
    $detailsResponse = curl_exec($ch);
    curl_close($ch);

    $details = json_decode($detailsResponse, true);

    /* PHOTO */
    $photoUrl = "https://api.content.tripadvisor.com/api/v1/location/$id/photos"
              . "?language=en&key=$apiKey";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $photoUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["accept: application/json"]
    ]);
    $photoResponse = curl_exec($ch);
    curl_close($ch);

    $photos = json_decode($photoResponse, true);
    $image = $photos["data"][0]["images"]["large"]["url"] ?? null;

    $cafes[] = [
        "location_id" => $id,
        "name" => $details["name"] ?? $item["name"],
        "rating" => $details["rating"] ?? "N/A",
        "address" => $details["address_obj"]["address_string"] ?? "Address unavailable",
        "image" => $image
    ];

    if (count($cafes) >= 10) break;
}

echo json_encode($cafes);
