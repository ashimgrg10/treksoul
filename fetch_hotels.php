<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

$location = isset($input['location']) ? $input['location'] : 'Pokhara';
$keyword = isset($input['keyword']) ? $input['keyword'] : 'hotel';

$apiKey = 'AIzaSyCaxJw9o_dT_HTFWhJsey3OqEWjBno8kjA';
$apiUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode("$keyword hotels in $location") . "&type=lodging&key=$apiKey";

$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

$results = [];

if (isset($data['results'])) {
    foreach (array_slice($data['results'], 0, 3) as $place) {
        $results[] = [
            'name' => $place['name'],
            'address' => $place['formatted_address'],
            'rating' => $place['rating'] ?? 'N/A',
            'link' => "https://www.google.com/maps/search/?api=1&query=" . urlencode($place['name'])
        ];
    }
}

echo json_encode(['hotels' => $results]);
?>
