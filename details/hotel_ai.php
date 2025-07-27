<?php
$budget = $_POST['budget'];
$type = $_POST['type'];

$recommendations = [];

if ($budget == 'budget') {
    if ($type == 'solo') {
        $recommendations = [
            "Backpackers Hostel Pokhara",
            "Budget Lodge Lakeside",
            "Phewa Corner Stay"
        ];
    } elseif ($type == 'family') {
        $recommendations = [
            "Peaceful Budget Guesthouse",
            "Family Tree Lodge"
        ];
    } elseif ($type == 'couple') {
        $recommendations = [
            "Romantic Nest Inn",
            "Cozy Sunrise Hotel"
        ];
    } else {
        $recommendations = ["Green Earth Eco Stay"];
    }
} elseif ($budget == 'mid') {
    if ($type == 'solo') {
        $recommendations = [
            "Mount View Mid Hotel",
            "Solo Trekker’s Rest"
        ];
    } elseif ($type == 'family') {
        $recommendations = [
            "Hotel Family Delight",
            "Mountain Inn Resort"
        ];
    } elseif ($type == 'couple') {
        $recommendations = [
            "Lovers Retreat Hotel",
            "Serene Valley Rooms"
        ];
    } else {
        $recommendations = ["Eco Vista Retreat"];
    }
} else { // luxury
    if ($type == 'solo') {
        $recommendations = [
            "Highview Solo Suites",
            "Pokhara Heights"
        ];
    } elseif ($type == 'family') {
        $recommendations = [
            "Luxury Family Resort",
            "Heaven's Nest Hotel"
        ];
    } elseif ($type == 'couple') {
        $recommendations = [
            "Romantic Panorama Resort",
            "Sunset Valley Romance Hotel"
        ];
    } else {
        $recommendations = ["Eco Palace Deluxe Stay"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Hotel Results</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6faff;
      padding: 40px;
    }

    .result-box {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #007BFF;
      margin-bottom: 20px;
    }

    ul {
      padding-left: 20px;
    }

    li {
      margin-bottom: 10px;
      font-size: 17px;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      background-color: #007BFF;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
    }

    a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="result-box">
    <h2>Recommended Hotels Based on Your Preference</h2>
    <ul>
      <?php foreach ($recommendations as $hotel): ?>
        <li><?php echo $hotel; ?></li>
      <?php endforeach; ?>
    </ul>
    <a href="hotel.html">🔙 Choose Again</a>
  </div>
</body>
</html>
