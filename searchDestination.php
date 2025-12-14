<?php
header("Content-Type: application/json");

// 🔐 YOUR RAPIDAPI KEY (REPLACE THIS)
$apiKey = "c75088e098msh7e4d5f33e362c85p1654c9jsnda43866fb283";

// Get query
$query = $_GET['query'] ?? '';

if ($query === '') {
    echo json_encode(["error" => "Query is required"]);
    exit;
}

// Booking.com RapidAPI endpoint
$url = "https://booking-com15.p.rapidapi.com/api/v1/hotels/searchDestination?query=" . urlencode($query);

// cURL request
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Key: $apiKey",
        "X-RapidAPI-Host: booking-com15.p.rapidapi.com"
    ]
]);

$response = curl_exec($ch);
curl_close($ch);

// Return API response
echo $response;
