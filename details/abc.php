<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Annapurna Base Camp (ABC) Trek - Treksoul</title>
  <link rel="stylesheet" href="../style.css" />
  <style>
    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #007BFF;
      padding: 1rem 2rem;
      color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

    .trek-container {
      max-width: 850px;
      background: white;
      margin: 40px auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .trek-container h1 {
      color: #007BFF;
      margin-bottom: 15px;
    }

    .trek-container p {
      font-size: 1.1rem;
      line-height: 1.6;
      color: #333;
    }

    .trek-image {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 8px;
      margin: 20px 0;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .difficulty-rating {
      margin-top: 25px;
      font-weight: bold;
      color: #444;
    }

    .stars {
      margin-top: 8px;
      display: flex;
      gap: 5px;
    }

    .star {
      font-size: 24px;
      color: #ccc;
      transition: transform 0.3s ease;
      opacity: 0;
      animation: fadeInZoom 0.6s forwards;
    }

    .star.filled {
      color: gold;
    }

    .stars .star:nth-child(1) { animation-delay: 0.1s; }
    .stars .star:nth-child(2) { animation-delay: 0.2s; }
    .stars .star:nth-child(3) { animation-delay: 0.3s; }
    .stars .star:nth-child(4) { animation-delay: 0.4s; }
    .stars .star:nth-child(5) { animation-delay: 0.5s; }

    @keyframes fadeInZoom {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .booking-box {
      margin-top: 60px;
      padding: 30px;
      border-radius: 12px;
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .booking-box h2 {
      color: #007BFF;
      margin-bottom: 20px;
    }

    .booking-box form label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
    }

    .booking-box form input {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .booking-box form button {
      margin-top: 20px;
      padding: 12px 20px;
      background-color: #007BFF;
      color: white;
      border: none;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .booking-box form button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

 
  <nav class="navbar">
    <div class="nav-left">
      <span class="logo">Treksoul</span>
    </div>
    <div class="nav-right">
      <a href="../index.php" class="nav-link">Home</a>
      <?php if (isset($_SESSION["user_id"])): ?>
        <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</span>
        <a href="../logout.php" class="nav-link">Logout</a>
      <?php else: ?>
        <a href="../login.php" class="nav-link">Login</a>
        <a href="../register.php" class="nav-link">Register</a>
      <?php endif; ?>
      <a href="#booking-form" class="nav-link">Plan Your Trek</a>
    </div>
  </nav>

  <div class="trek-container">
    <h1>Annapurna Base Camp (ABC) Trek</h1>
    <img src="../image/abctrek.jpg" alt="ABC Trek Image" class="trek-image" />

    <p>
      The Annapurna Base Camp (ABC) trek is one of Nepal’s most famous and breathtaking trekking routes.
      It offers a unique blend of majestic Himalayan views, diverse landscapes, and rich cultural experiences.
      The trail passes through rhododendron forests, terraced fields, and traditional Gurung villages before reaching the base camp of Annapurna I.
    </p>

    <p>
      Along the way, trekkers enjoy stunning views of Annapurna South, Hiunchuli, Machapuchare (Fishtail), and other towering peaks.
      It is suitable for both novice and experienced trekkers due to its moderate difficulty level.
    </p>

    
    <div class="difficulty-rating">
      <span>Difficulty Level: Difficult</span>
      <div class="stars">
        <span class="star filled">★</span>
        <span class="star filled">★</span>
        <span class="star filled">★</span>
        <span class="star filled">★</span>
        <span class="star">★</span>
      </div>
    </div>

  
    <div id="booking-form" class="booking-box">
      <h2>Plan Your ABC Trek</h2>
      <form action="abc_plan.php" method="POST">
        <label for="trekkers">Number of Trekkers:</label>
        <input type="number" id="trekkers" name="trekkers" min="1" max="50" required />

        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" name="start_date" required />

        <label for="days">Number of Days:</label>
        <input type="number" id="days" name="days" min="1" max="30" required />

        <button type="submit">Submit Plan</button>
      </form>
    </div>
  </div>

</body>
</html>
