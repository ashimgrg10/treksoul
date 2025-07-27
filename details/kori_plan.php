<?php
session_start();


$trekkers = $_POST['trekkers'] ?? 'N/A';
$start_date = $_POST['start_date'] ?? date('Y-m-d');
$days = $_POST['days'] ?? 'N/A';

// Weather forecast using Open-Meteo API
$latitude = 28.2086;
$longitude = 83.9977;
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
$days_int = (int)$days;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Kori Trek Plan - Treksoul</title>
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
    }
    body.sunny-bg {
      background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80');
    }
    body.rainy-bg {
      background-image: url('https://images.unsplash.com/photo-1527766833261-b09c3163a791?auto=format&fit=crop&w=1920&q=80');
      color: #d1e7f9;
    }
    body.cloudy-bg {
      background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1920&q=80');
      color: #444;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      background-color: rgba(0,123,255,0.85);
      padding: 1rem 2rem;
      color: white;
    }
    .nav-link {
      margin-left: 20px;
      color: white;
      text-decoration: none;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: rgba(255,255,255,0.95);
      padding: 20px;
      border-radius: 10px;
    }

    .trek-plan-box {
      background: linear-gradient(135deg, #007BFF, #00A8FF);
      color: white;
      padding: 25px;
      border-radius: 15px;
      text-align: center;
      margin-bottom: 30px;
    }

    .plan-details {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
      margin-top: 15px;
    }
    .plan-details div {
      background: rgba(255,255,255,0.2);
      padding: 15px;
      border-radius: 10px;
      min-width: 120px;
    }

    .weather-box {
      display: flex;
      align-items: center;
      gap: 20px;
      background: linear-gradient(135deg, #2196F3, #21CBF3);
      border-radius: 20px;
      padding: 25px;
      margin-bottom: 30px;
      color: white;
    }
    .weather-icon img {
      width: 100px;
      height: 100px;
    }
    .weather-details p {
      margin: 6px 0;
      font-weight: 600;
    }

    .hotels {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 40px;
    }
    .hotel-card {
      width: 280px;
      background: #f8f8f8;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .hotel-card img {
      width: 100%;
      border-radius: 8px;
      height: 160px;
      object-fit: cover;
      margin-bottom: 10px;
    }

    iframe {
      width: 100%;
      height: 300px;
      border: none;
      border-radius: 10px;
    }

    h3 {
      text-align: center;
      color: #007BFF;
      margin-top: 40px;
    }
   .route-box {
  background: linear-gradient(145deg, #4a90e2, #6fc1ff);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
  color: white;
  margin-bottom: 40px;
  animation: fadeIn 1s ease-in;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

.route-vertical {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  position: relative;
  gap: 30px;
}

.route-point {
  display: flex;
  align-items: center;
  gap: 15px;
  position: relative;
  z-index: 10;
}

.route-point.active .icon {
  background: #ffdf00;
  color: black;
  box-shadow: 0 0 8px #ffdf00;
  font-weight: bold;
}

.icon {
  width: 36px;
  height: 36px;
  background: white;
  color: #007bff;
  border-radius: 50%;
  text-align: center;
  line-height: 36px;
  font-size: 20px;
  box-shadow: 0 0 6px rgba(255,255,255,0.7);
  animation: pulse 1.5s infinite ease-in-out;
  flex-shrink: 0;
}

.label {
  font-weight: 600;
  font-size: 1.1rem;
  user-select: none;
}

/* Zigzag line styling */
.zigzag-line {
  width: 24px;
  height: 48px;
  background:
    linear-gradient(45deg, transparent 75%, white 75%),
    linear-gradient(-45deg, transparent 75%, white 75%);
  background-position: 0 0, 12px 12px;
  background-repeat: repeat-y;
  background-size: 24px 24px;
  animation: zigzag-move 1.5s linear infinite;
  margin-left: 18px;
}

/* Animate the zigzag background to slide */
@keyframes zigzag-move {
  0% {
    background-position: 0 0, 12px 12px;
  }
  100% {
    background-position: 0 24px, 12px 36px;
  }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.2); opacity: 0.7; }
}

@keyframes fadeIn {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.route-desc {
  text-align: center;
  font-size: 1rem;
  font-weight: 500;
  margin-top: 10px;
  color: white;
}
.day-label {
  font-weight: 700;
  background: #ffdf00;
  color: black;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.9rem;
  min-width: 60px;
  text-align: center;
  user-select: none;
  box-shadow: 0 0 6px #ffdf00;
  flex-shrink: 0;
}
.zigzag-container {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-left: 18px;
}

.zigzag-line {
  width: 24px;
  height: 48px;
  background:
    linear-gradient(45deg, transparent 75%, white 75%),
    linear-gradient(-45deg, transparent 75%, white 75%);
  background-position: 0 0, 12px 12px;
  background-repeat: repeat-y;
  background-size: 24px 24px;
  animation: zigzag-move 1.5s linear infinite;
}

.zigzag-text {
  font-size: 0.85rem;
  background: rgba(255, 255, 255, 0.8);
  padding: 2px 8px;
  border-radius: 12px;
  color: #007BFF;
  font-weight: 600;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.15);
}
.cost-box {
  background: #f0f8ff;
  padding: 25px;
  border-radius: 18px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 20px auto;
  font-size: 1rem;
  color: #333;
  border: 1px solid #cce5ff;
}

.cost-item,
.cost-total {
  display: flex;
  justify-content: space-between;
  margin: 10px 0;
  font-weight: 500;
}

.cost-total {
  font-size: 1.05rem;
  font-weight: 600;
  color: #007BFF;
}

.cost-total.group {
  color: #e74c3c;
}

.cost-divider {
  border: none;
  border-top: 1px dashed #ccc;
  margin: 15px 0;
}



  </style>
</head>
<body class="<?php echo $page_bg_class; ?>">

  <nav class="navbar">
    <div class="logo">Treksoul</div>
    <div>
      <a href="../index.php" class="nav-link">Home</a>
      <?php if (isset($_SESSION["user_id"])): ?>
        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</span>
        <a href="logout.php" class="nav-link">Logout</a>
      <?php else: ?>
        <a href="login.php" class="nav-link">Login</a>
        <a href="register.php" class="nav-link">Register</a>
      <?php endif; ?>
    </div>
  </nav>

  <div class="container">
    <div class="trek-plan-box">
      <h2>Your Kori Trek Plan</h2>
      <div class="plan-details">
        <div><strong>Trekkers:</strong><br><?php echo htmlspecialchars($trekkers); ?></div>
        <div><strong>Start Date:</strong><br><?php echo htmlspecialchars($start_date); ?></div>
        <div><strong>Days:</strong><br><?php echo htmlspecialchars($days); ?></div>
      </div>
    </div>

    <h3>🌦 Live Weather Forecast</h3>
    <div class="weather-box">
      <div class="weather-icon">
        <img src="icons/<?php echo $icon; ?>" alt="Weather Icon">
      </div>
      <div class="weather-details">
        <p><strong>Condition:</strong> <?php echo $weather_status; ?></p>
        <p><strong>Temperature:</strong> <?php echo $min_temp; ?>°C - <?php echo $max_temp; ?>°C</p>
        <p><strong>Precipitation:</strong> <?php echo $precip; ?> mm</p>
        <p><strong>Tip:</strong> <?php echo $tip; ?></p>
      </div>
    </div>

    <h3>🏨 AI-based Hotel Recommendations</h3>
<div class="hotels">
  <?php
    $price_per_person = 1200;
    $total_price = $price_per_person * (int)$trekkers;

  function hotelCard($img, $name, $desc) {
  global $price_per_person, $total_price;
  echo '
    <div class="hotel-card">
      <img src="' . $img . '" alt="' . $name . '">
      <p style="color: #e74c3c; font-weight: bold; font-size: 1.1rem;">' . $name . '</p>
      <p style="font-size: 0.9rem; color: #555;">' . $desc . '</p>
      <p style="color: #007BFF;"><strong>Rs ' . $price_per_person . ' / person / day</strong></p>
      <p style="color: #e74c3c; font-weight: bold; font-size: 1.1rem;"><strong>Total: Rs ' . number_format($total_price) . '</strong></p>
    </div>';
}


    if ($days_int <= 1) {
      hotelCard("grgcottage.jpg", "Gurung Cottage", "Simple and cozy lodge with mountain views.");
    } elseif ($days_int == 2) {
      hotelCard("tasa.jpg", "Hello brothers Tasa (Tasa)", "Eco-friendly stop midway to Kori with traditional meals.");
      hotelCard("nyano.jpg", "Hotel Nyano Kori (Kori)", "Comfortable resting place at the return point.");
    } else {
      hotelCard("grgcottage.jpg", "Gurung Cottage (siklesh)", "Base hotel at starting point in Sikles.");
      hotelCard("hugu.jpg", "Hugu Goath Hotel (Hugu)", "Eco-lodge with peaceful ambiance and local cuisine.");
      hotelCard("nyano.jpg", "Hotel Nyano Kori (Kori)", "Perfect rest spot before heading home.");
    }
  ?>
</div>


<h3>🧭 AI Trek Route Suggestion</h3>
<style>
  .zigzag-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: 18px;
  }

  .zigzag-line {
    width: 24px;
    height: 48px;
    background:
      linear-gradient(45deg, transparent 75%, white 75%),
      linear-gradient(-45deg, transparent 75%, white 75%);
    background-position: 0 0, 12px 12px;
    background-repeat: repeat-y;
    background-size: 24px 24px;
    animation: zigzag-move 1.5s linear infinite;
  }

  .zigzag-text {
    font-size: 0.85rem;
    background: rgba(255, 255, 255, 0.8);
    padding: 2px 8px;
    border-radius: 12px;
    color: #007BFF;
    font-weight: 600;
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.15);
  }
</style>

<div class="route-box">
  <?php if ($days_int <= 1): ?>
    <div class="route-vertical">
      <div class="route-point active">
        <div class="day-label">Day 1</div>
        <div class="icon">🚌</div>
        <div class="label">Pokhara → Siklesh (Bus)</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">4 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏁</div>
        <div class="label">Siklesh</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">9 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">📍</div>
        <div class="label">Kori Viewpoint</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">5 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏠</div>
        <div class="label">Return Base</div>
      </div>
    </div>
    <p class="route-desc">Start from Pokhara, bus to Siklesh, trek to Kori and return in a day.</p>

  <?php elseif ($days_int == 2): ?>
    <div class="route-vertical">
      <div class="route-point active">
        <div class="day-label">Day 1</div>
        <div class="icon">🚌</div>
        <div class="label">Pokhara → Siklesh (Bus)</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">4 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏁</div>
        <div class="label">Siklesh</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">3.5 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏨</div>
        <div class="label">Tasa</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">4 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 2</div>
        <div class="icon">⛰️</div>
        <div class="label">Kori Danda</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">7 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 2</div>
        <div class="icon">🏠</div>
        <div class="label">Return Base</div>
      </div>
    </div>
    <p class="route-desc">Bus ride from Pokhara to Siklesh, overnight at Tasa, trek to Kori and return next day.</p>

  <?php else: ?>
    <div class="route-vertical">
      <div class="route-point active">
        <div class="day-label">Day 1</div>
        <div class="icon">🚌</div>
        <div class="label">Pokhara → Siklesh (Bus)</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">4 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏁</div>
        <div class="label">Siklesh</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">3.5 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 1</div>
        <div class="icon">🏨</div>
        <div class="label">Hugu</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">4 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 2</div>
        <div class="icon">⛰️</div>
        <div class="label">Kori Danda</div>
      </div>
      <div class="zigzag-container">
        <div class="zigzag-line"></div>
        <div class="zigzag-text">5 hrs</div>
      </div>
      <div class="route-point">
        <div class="day-label">Day 3</div>
        <div class="icon">🏠</div>
        <div class="label">Return Base</div>
      </div>
    </div>
    <p class="route-desc">3-day journey: Bus from Pokhara to Siklesh, overnight at Hugu, trek to Kori and return.</p>
  <?php endif; ?>
</div>

<h3>💰 Trek Cost Breakdown</h3>
<div class="cost-box">
  <?php
    $bus_cost = 800;
   
    $hotel_cost_per_day = 1200;
    $food_cost_per_day = 600;

    $total_days = $days_int;
    $num_trekkers = (int)$trekkers;

   
    $hotel_total = $hotel_cost_per_day * $total_days;
    $food_total = $food_cost_per_day * $total_days;
    $per_person_total = $bus_cost  + $hotel_total + $food_total;
    $group_total = $per_person_total * $num_trekkers;
  ?>
  <div class="cost-item"><span>🚌 Bus Fare:</span><span>Rs <?php echo $bus_cost; ?></span></div>
 
  <div class="cost-item"><span>🏨 Hotel (<?php echo $total_days; ?> days):</span><span>Rs <?php echo $hotel_total; ?></span></div>
  <div class="cost-item"><span>🍛 Food (<?php echo $total_days; ?> days):</span><span>Rs <?php echo $food_total; ?></span></div>
  <hr class="cost-divider" />
  <div class="cost-total"><span>Total Per Person:</span><span>Rs <?php echo number_format($per_person_total); ?></span></div>
  <div class="cost-total group"><span>Total for <?php echo $num_trekkers; ?> trekkers:</span><span>Rs <?php echo number_format($group_total); ?></span></div>
</div>



    <h3>🗺 Kori Trek Route Map</h3>
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.285625179762!2d84.02480107539977!3d28.61222917568257!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3995939a39b83907%3A0x8f9de10b8b727b9e!2sKori%20Danda!5e0!3m2!1sen!2snp!4v1719398797395!5m2!1sen!2snp" 
      allowfullscreen>
    </iframe>
  </div>
</body>
</html>
