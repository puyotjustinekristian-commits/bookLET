<?php
header("Content-Type: application/json");

$apiKey = "YOUR_API_KEY_HERE"; // <-- replace with your TripAdvisor API key

// List of featured attractions
$featured = [
    "Binondo Chinatown",
    "Manila Zoo",
    "Fort Santiago",
    "Rizal Park",
    "National Museum of Fine Arts",
    "Quiapo Church",
    "Casa Manila Museum",
    "Manila Cathedral",
    "Ayala Museum",
    "Bonifacio Global City"
];

// Custom images for each attraction
$images = [
    "Binondo Chinatown" => "binondo.jpg",
    "Manila Zoo" => "zoo.jpg",
    "Fort Santiago" => "fort-santiago.jpg",
    "Rizal Park" => "rizal-park.jpg",
    "National Museum of Fine Arts" => "national-museum.png",
    "Quiapo Church" => "quiapo-church.jpg",
    "Casa Manila Museum" => "casa-manila.jpg",
    "Manila Cathedral" => "manila-cathedral.jpg",
    "Ayala Museum" => "ayala-museum.jpg",
    "Bonifacio Global City" => "bgc.jpg"
];

$final = [];

foreach ($featured as $place) {

    $query = urlencode($place);

    // STEP 1 — Search attraction by name
    $urlSearch = "https://api.content.tripadvisor.com/api/v1/location/search?searchQuery=$query&category=attractions&language=en&key=$apiKey";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlSearch);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["accept: application/json"]);
    $searchResponse = curl_exec($curl);
    curl_close($curl);

    if (!$searchResponse) continue;
    $searchData = json_decode($searchResponse, true);

    if (empty($searchData["data"])) continue;

    $locationId = $searchData["data"][0]["location_id"];

    // STEP 2 — Get details
    $urlDetails = "https://api.content.tripadvisor.com/api/v1/location/$locationId/details?language=en&key=$apiKey";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlDetails);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["accept: application/json"]);
    $detailsResponse = curl_exec($curl);
    curl_close($curl);

    if (!$detailsResponse) continue;

    $details = json_decode($detailsResponse, true);

    // Extract TYPE (category)
    $type = "Unknown";
    if (isset($details["category"]["name"])) {
        $type = $details["category"]["name"];
    }
    if (isset($details["categories"][0]["name"])) {
        $type = $details["categories"][0]["name"];
    }

    // STEP 3 — Get reviews
    $urlReviews = "https://api.content.tripadvisor.com/api/v1/location/$locationId/reviews?language=en&key=$apiKey";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urlReviews);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["accept: application/json"]);
    $reviewsResponse = curl_exec($curl);
    curl_close($curl);

    $reviewsData = json_decode($reviewsResponse, true);
    $reviews = $reviewsData["data"] ?? [];

    // Final formatted output
    $final[] = [
        "name"        => $details["name"] ?? $place,
        "type"        => $type,
        "description" => $details["description"] ?? "No description available.",
        "image"       => $images[$place] ?? "images/default.jpg",
        "reviews"     => $reviews
    ];
}

echo json_encode($final);
