<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);

$apiKey    = "PSPK6OQ5I4WqyePHBbppmlD5jYVh2NAS";
$apiSecret = "UGW6Nq1I11bI7eop";

$from   = $_GET["from"]   ?? "";
$to     = $_GET["to"]     ?? "";
$depart = $_GET["depart"] ?? "";
$return = $_GET["return"] ?? "";
$adults = intval($_GET["adults"] ?? 1);

if (!$from || !$to || !$depart) {
    echo json_encode([]);
    exit;
}

$tokenCh = curl_init("https://test.api.amadeus.com/v1/security/oauth2/token");

curl_setopt_array($tokenCh, [
    CURLOPT_POST           => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        "Content-Type: application/x-www-form-urlencoded"
    ],
    CURLOPT_POSTFIELDS     => http_build_query([
        "grant_type"    => "client_credentials",
        "client_id"     => $apiKey,
        "client_secret" => $apiSecret
    ])
]);

$tokenResponse = curl_exec($tokenCh);
curl_close($tokenCh);

$tokenData   = json_decode($tokenResponse, true);
$accessToken = $tokenData["access_token"] ?? "";

if (!$accessToken) {
    echo json_encode([]);
    exit;
}

$url = "https://test.api.amadeus.com/v2/shopping/flight-offers"
     . "?originLocationCode=$from"
     . "&destinationLocationCode=$to"
     . "&departureDate=$depart"
     . "&adults=$adults"
     . "&currencyCode=PHP"
     . "&max=20";

if (!empty($return)) {
    $url .= "&returnDate=$return";
}

$flightCh = curl_init($url);

curl_setopt_array($flightCh, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        "Authorization: Bearer $accessToken"
    ]
]);

$response = curl_exec($flightCh);
curl_close($flightCh);

$data        = json_decode($response, true);
$offers      = $data["data"] ?? [];
$carrierDict = $data["dictionaries"]["carriers"] ?? [];

$results = [];
$seen    = [];

foreach ($offers as $offer) {

    $airlineCode = $offer["validatingAirlineCodes"][0] ?? "";

    if (!$airlineCode || !isset($carrierDict[$airlineCode])) {
        continue;
    }

    $airlineName = $carrierDict[$airlineCode];
    $price       = $offer["price"]["total"] ?? "0";
    $duration    = $offer["itineraries"][0]["duration"] ?? "PT0M";

    $uniqueKey = $airlineName . "|" . $price;
    if (isset($seen[$uniqueKey])) {
        continue;
    }
    $seen[$uniqueKey] = true;

    $results[] = [
        "airline"  => $airlineName,
        "duration" => $duration,
        "price"    => $price,
        "link"     => "#"
    ];

    if (count($results) >= 5) {
        break;
    }
}

echo json_encode($results);
exit;
