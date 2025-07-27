<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$message = strtolower(trim($input["message"] ?? ""));

// Basic hotel intent detection
$hotelKeywords = ["hotel", "stay", "lodge", "accommodation", "resort", "guest house"];
$isHotelQuery = false;

foreach ($hotelKeywords as $keyword) {
    if (strpos($message, $keyword) !== false) {
        $isHotelQuery = true;
        break;
    }
}

// Extract location (basic) – default to Pokhara if not found
preg_match("/in ([a-zA-Z\s]+)/", $message, $matches);
$location = isset($matches[1]) ? trim($matches[1]) : "Pokhara";

if ($isHotelQuery) {
    // Call Google Places via fetch_hotels.php
    $hotelResponse = file_get_contents("http://localhost/fetch_hotels.php");
    $hotels = json_decode($hotelResponse, true)['hotels'] ?? [];

    if (count($hotels) > 0) {
        $reply = "Here are some hotel options in $location:\n\n";
        foreach ($hotels as $hotel) {
            $reply .= "🏨 *{$hotel['name']}*\n📍 {$hotel['address']}\n⭐ Rating: {$hotel['rating']}\n🔗 [View on Map]({$hotel['link']})\n\n";
        }
    } else {
        $reply = "Sorry, I couldn't find any hotels in $location right now.";
    }

    echo json_encode(["reply" => $reply]);
    exit();
}

// Use OpenAI for general trekking queries
$openaiApiKey = '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';

$payload = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a trekking assistant for Nepal. Answer questions about routes, gear, seasons, and safety."],
        ["role" => "user", "content" => $message]
    ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $openaiApiKey"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$reply = $data["choices"][0]["message"]["content"] ?? "Sorry, I couldn't understand that.";

echo json_encode(["reply" => $reply]);
