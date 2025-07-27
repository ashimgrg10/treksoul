<?php
// Simulated weather data — replace this with data from your Python weather API
$forecast_condition = "rain";  // Options: sunny, rain, cloud, snow
$temperature = "13°C";

// Image selection based on condition
$iconMap = [
    "sunny" => "sun.png",
    "rain" => "rain.png",
    "cloud" => "cloud.png",
    "snow" => "snow.png"
];

$iconFile = isset($iconMap[$forecast_condition]) ? $iconMap[$forecast_condition] : "sun.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Live Weather Forecast - Treksoul</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .weather-box {
      max-width: 300px;
      background-color: #e8f4ff;
      border: 1px solid #b6dcff;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .weather-box h3 {
      color: #007BFF;
      margin-bottom: 15px;
    }

    .weather-icon {
      width: 80px;
      height: 80px;
      margin-bottom: 10px;
    }

    .temp {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
    }

    .condition {
      font-size: 1rem;
      color: #555;
      text-transform: capitalize;
    }
  </style>
</head>
<body>

<div class="weather-box">
  <h3>🌦 Live Weather Forecast</h3>
  <img src="icons/<?php echo $iconFile; ?>" alt="<?php echo $forecast_condition; ?>" class="weather-icon">
  <p class="temp"><?php echo $temperature; ?></p>
  <p class="condition"><?php echo $forecast_condition; ?></p>
</div>

</body>
</html>
