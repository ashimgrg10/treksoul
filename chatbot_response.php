<?php
// Predefined questions and answers (you can expand this!)
$responses = [
    "best trek for beginners" => "The Mardi Himal trek is perfect for beginners with stunning views and moderate difficulty.",
    "abc trek days" => "The Annapurna Base Camp (ABC) trek typically takes 7 to 10 days.",
    "best season for mardi" => "Spring (March-May) and Autumn (September-November) are the best seasons for Mardi Himal trek.",
    "kori trek duration" => "The Kori Trek usually takes about 5 to 7 days depending on your pace.",
    "trekking permit" => "Yes, most treks in Nepal require permits like TIMS and ACAP. We can help you get them.",
];

// Get user input
$input = strtolower(trim($_POST['message'] ?? ''));

// Match with known questions
$answer = "Sorry, I don't have info on that yet. Please ask about specific treks.";
foreach ($responses as $key => $response) {
    if (strpos($input, $key) !== false) {
        $answer = $response;
        break;
    }
}

echo json_encode(['reply' => $answer]);
?>
