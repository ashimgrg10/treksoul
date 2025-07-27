<?php
session_start();

$trekkers = $_POST['trekkers'] ?? 'N/A';
$start_date = $_POST['start_date'] ?? date('Y-m-d');
$days = $_POST['days'] ?? 'N/A';

// Weather forecast using Open-Meteo API
$latitude = 28.5308;
$longitude = 83.8807;
$api_url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,weathercode&timezone=auto&start_date=$start_date&end_date=$start_date";

$weather_json = file_get_contents($api_url);
$weather_data = json_decode($weather_json, true);

$max_temp = $weather_data['daily']['temperature_2m_max'][0] ?? 'N/A';
$min_temp = $weather_data['daily']['temperature_2m_min'][0] ?? 'N/A';
$precip = $weather_data['daily']['precipitation_sum'][0] ?? 0;
$weathercode = $weather_data['daily']['weathercode'][0] ?? 0;

// Determine weather status & icon & page background class
if ($weathercode < 3) {
    $weather_status = "Clear or Mostly Sunny";
    $page_bg_class = "sunny-bg";
    $icon = "sun.gif";
} elseif ($weathercode < 45) {
    $weather_status = "Cloudy";
    $page_bg_class = "cloudy-bg";
    $icon = "clouds.gif";
} elseif ($weathercode < 61) {
    $weather_status = "Light Rain";
    $page_bg_class = "rainy-bg";
    $icon = "rain.gif";
} else {
    $weather_status = "Rainy or Stormy";
    $page_bg_class = "rainy-bg";
    $icon = "storm.gif";
}

$tip = $precip > 5 ? "Pack a raincoat!" : "Conditions look good for trekking!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Plan Result - Treksoul</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transition: background-image 0.8s ease-in-out;
      color: #222;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      background-color: #f4f4f4;
    }
    body.sunny-bg {
      background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80');
      color: #222;
    }
    body.rainy-bg {
      background-image: url('https://images.unsplash.com/photo-1527766833261-b09c3163a791?auto=format&fit=crop&w=1920&q=80');
      color: #d1e7f9;
      text-shadow: 0 0 5px rgba(255,255,255,0.9);
    }
    body.cloudy-bg {
      background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1920&q=80');
      color: #444;
      text-shadow: 0 0 3px rgba(255,255,255,0.7);
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: rgba(0,123,255,0.85);
      padding: 1rem 2rem;
      color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      backdrop-filter: saturate(180%) blur(10px);
    }
    .logo {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .nav-link {
      margin-left: 20px;
      color: white;
      text-decoration: none;
      font-weight: 500;
    }
    .nav-link:hover {
      text-decoration: underline;
    }
    .container {
      max-width: 900px;
      margin: 40px auto 60px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Trek Plan Box */
    .trek-plan-box {
      background: linear-gradient(135deg, #007BFF, #00A8FF);
      color: white;
      padding: 25px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,123,255,0.3);
      margin-bottom: 30px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      text-align: center;
    }
    .trek-plan-box h2 {
      font-weight: 700;
      font-size: 1.9rem;
      margin-bottom: 20px;
      letter-spacing: 1.2px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .plan-details {
      display: flex;
      justify-content: space-around;
      font-size: 1.2rem;
      font-weight: 600;
      gap: 20px;
      flex-wrap: wrap;
    }
    .plan-details div {
      background: rgba(255, 255, 255, 0.15);
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      flex: 1 1 30%;
      transition: background-color 0.3s ease;
      color: #fff;
    }
    .plan-details div:hover {
      background: rgba(255, 255, 255, 0.3);
      cursor: default;
    }

    /* New live weather box design */
    .weather-box {
      max-width: 600px;
      margin: 0 auto 40px auto;
      background: linear-gradient(135deg, #2196F3, #21CBF3);
      border-radius: 30px;
      box-shadow: 0 8px 20px rgba(33, 203, 243, 0.6);
      display: flex;
      align-items: center;
      gap: 25px;
      padding: 25px 35px;
      color: white;
      font-weight: 700;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
 .weather-icon {
  background: rgba(255, 255, 255, 0.25);
  border-radius: 50%;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(255,255,255,0.3);
  flex-shrink: 0;
  width: 140px;
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.weather-icon img {
  width: 100px;
  height: 100px;
  filter: drop-shadow(0 2px 3px rgba(0,0,0,0.4));
  border-radius: 50%;
  object-fit: cover;
}


    .weather-details {
      flex-grow: 1;
    }
    .weather-details p {
      margin: 6px 0;
      font-size: 1.15rem;
      line-height: 1.3;
    }
    .weather-details p.tip {
      font-style: italic;
      color: #D0F0FD;
      margin-top: 12px;
      font-weight: 600;
    }

    /* Hotels styling */
    .hotels {
      display: flex;
      gap: 20px;
      margin-top: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .hotel-card {
      width: 280px;
      background: #f8f8f8;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .hotel-card:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      cursor: pointer;
    }
    .hotel-card img {
      width: 100%;
      border-radius: 8px;
      height: 160px;
      object-fit: cover;
      margin-bottom: 10px;
    }

    h3 {
      color: #007BFF;
      text-align: center;
      margin-top: 30px;
      margin-bottom: 20px;
    }
    iframe {
      width: 100%;
      height: 300px;
      border: none;
      border-radius: 10px;
      margin-top: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body class="<?php echo $page_bg_class; ?>">

<nav class="navbar">
  <div class="nav-left">
    <span class="logo">Treksoul</span>
  </div>
  <div class="nav-right">
    <a href="../index.php" class="nav-link">Home</a>
    <?php if (isset($_SESSION["user_id"])): ?>
      <span style="color: white; margin-left: 20px;">Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</span>
      <a href="logout.php" class="nav-link">Logout</a>
    <?php else: ?>
      <a href="login.php" class="nav-link">Login</a>
      <a href="register.php" class="nav-link">Register</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
  <div class="trek-plan-box">
    <h2>Your Trek Plan</h2>
    <div class="plan-details">
      <div><strong>Trekkers:</strong><br><?php echo htmlspecialchars($trekkers); ?></div>
      <div><strong>Start Date:</strong><br><?php echo htmlspecialchars($start_date); ?></div>
      <div><strong>Days Planned:</strong><br><?php echo htmlspecialchars($days); ?></div>
    </div>
  </div>

  <h3>🌦 Live Weather Forecast for <?php echo htmlspecialchars($start_date); ?></h3>
  <div class="weather-box">
    <div class="weather-icon">
      <img src="icons/<?php echo $icon; ?>" alt="Weather Icon" />
    </div>
    <div class="weather-details">
      <p><strong>Condition:</strong> <?php echo $weather_status; ?></p>
      <p><strong>Temperature:</strong> <?php echo $min_temp; ?>°C - <?php echo $max_temp; ?>°C</p>
      <p><strong>Precipitation:</strong> <?php echo $precip; ?> mm</p>
      <p class="tip"><strong>Trek Tip:</strong> <?php echo $tip; ?></p>
    </div>
  </div>

  <h3>🏨 Nearby Hotels</h3>
  <div class="hotels">
    <div class="hotel-card">
      <img src="sanctuary.jpg" alt="Hotel Trekkers Sanctuary" />
      <p>Hotel Trekkers Sanctuary</p>
    </div>
    <div class="hotel-card">
      <img src="ecolodge.jpg" alt="Ghandruk Village Eco Lodge" />
      <p>Ghandruk Village Eco Lodge</p>
    </div>
    <div class="hotel-card">
      <img src="lodge.jpg" alt="Mountain View Lodge" />
      <p>Mountain View Lodge</p>
    </div>
  </div>

  <h3>🗺 ABC Trek Route Map</h3>
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.3608854106244!2d83.82072897539788!3d28.61024927568295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399593c329e693f3%3A0xa759f9c9f4fdb301!2sAnnapurna%20Base%20Camp!5e0!3m2!1sen!2snp!4v1629459630575!5m2!1sen!2snp" allowfullscreen></iframe>
</div>

</body>
</html>
