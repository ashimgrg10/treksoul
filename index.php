<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
// Assuming username is stored in session as 'username'
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Treksoul</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    html {
      scroll-behavior: smooth;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
    }
    /* Navbar */
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
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .logo {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .welcome-msg {
      margin-right: 20px;
      font-weight: 600;
      font-size: 1rem;
      color: white;
    }
    .nav-right {
      display: flex;
      align-items: center;
    }
    .nav-right .nav-link {
      margin-left: 20px;
      color: white;
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
    }
    .nav-right .nav-link:hover {
      text-decoration: underline;
    }
    .menu-icon {
      display: none;
      font-size: 28px;
      color: white;
      cursor: pointer;
    }
    @media (max-width: 768px) {
      .menu-icon { display: block; }
      .nav-right {
        display: none;
        flex-direction: column;
        background-color: #007BFF;
        position: absolute;
        top: 60px;
        right: 20px;
        padding: 10px;
        border-radius: 8px;
      }
      .nav-right.show { display: flex; }
      .nav-right .nav-link { margin: 8px 0; }
      .welcome-msg {
        margin: 0 0 10px 0;
      }
    }
    /* Sections */
    .about-section, .trek-section, .video-section {
      width: 80%;
      margin: 40px auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .about-content {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      align-items: center;
    }
    .about-text {
      flex: 1;
      min-width: 250px;
    }
    .about-text h2 { color: #007BFF; }
    .about-text p {
      font-size: 1.05rem;
      color: #444;
      line-height: 1.6;
    }
    .about-images {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .about-images img {
      width: 100%;
      border-radius: 8px;
      height: 300px;
      object-fit: cover;
    }
    .trek-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .trek-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.2s;
      background: white;
    }
    .trek-card:hover { transform: scale(1.03); }
    .trek-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .trek-card h3 { padding: 10px; color: #333; }
    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
    }
    .video-box {
      padding: 25px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .video-box video {
      width: 100%;
      height: 220px;
      border-radius: 8px;
      background: black;
    }
    .video-title {
      font-weight: bold;
      margin-bottom: 10px;
    }
    /* Chatbot styles */
    #chatbot-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
      font-family: 'Segoe UI', sans-serif;
    }
    #chatbot-popup {
      display: none;
      flex-direction: column;
      width: 340px;
      height: 460px;
      background: linear-gradient(135deg, #ffffff, #e6f0ff);
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .chat-header {
      background: #007BFF;
      color: white;
      padding: 14px;
      font-weight: bold;
      text-align: center;
    }
    .chat-body {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .chat-message {
      max-width: 80%;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 10px;
      word-wrap: break-word;
      white-space: pre-wrap;
    }
    .chat-message.bot {
      background: #f1f1f1;
      align-self: flex-start;
    }
    .chat-message.user {
      background: #007BFF;
      color: white;
      align-self: flex-end;
    }
    .chat-input-area {
      display: flex;
      border-top: 1px solid #ccc;
    }
    #chat-input {
      flex: 1;
      border: none;
      padding: 12px;
      font-size: 1rem;
      outline: none;
    }
    #send-btn {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 12px 16px;
      cursor: pointer;
    }
    #chatbot-bubble {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #007BFF, #00c6ff);
      color: white;
      font-size: 28px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
      user-select: none;
      transition: transform 0.2s;
    }
    #chatbot-bubble:hover {
      transform: scale(1.1);
    }
    /* Footer */
    .footer {
      background-color: #0d6efd;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 14px;
      margin-top: 30px;
    }
    .footer a {
      color: #ffffff;
      text-decoration: none;
      font-weight: bold;
    }
    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
  <div class="logo">Treksoul</div>
  <div class="nav-right" id="navLinks">
    <div class="welcome-msg">Welcome, <?php echo $username; ?></div>
    <a href="#trekking" class="nav-link">Destinations</a>
    <a href="#videos" class="nav-link">Trekking Videos</a>
    <a href="#about" class="nav-link">About</a>
    <a href="logout.php" class="nav-link">Logout</a>
  </div>
  <div class="menu-icon" onclick="toggleMenu()">☰</div>
</div>

<!-- About Section -->
<section class="about-section" id="about">
  <div class="about-content">
    <div class="about-text">
      <h2>Welcome to Treksoul</h2>
      <p>Treksoul is your ultimate AI-powered trekking companion, designed to make your trekking adventures in Nepal truly unforgettable.</p>
      <p>Your adventure begins here. Let Treksoul be your trusted guide to the majestic Himalayas and beyond.</p>
    </div>
    <div class="about-images">
      <img src="image/mardi.jpg" alt="Mountain View">
    </div>
  </div>
</section>

<!-- Trekking Destinations -->
<section class="trek-section" id="trekking">
  <h2>Trekking Destinations</h2>
  <div class="trek-grid">
    <a href="details/kori.php" class="trek-card">
      <img src="image/kori.jpg" alt="Kori Trek" />
      <h3>Kapuchhe and Kori Trek</h3>
    </a>
    <a href="details/abc.php" class="trek-card">
      <img src="image/abctrek.jpg" alt="ABC Trek" />
      <h3>Annapurna Base Camp (ABC) Trek</h3>
    </a>
    <a href="details/mardi.php" class="trek-card">
      <img src="image/mardi.jpg" alt="Mardi Himal" />
      <h3>Mardi Himal Trek</h3>
    </a>
    <a href="details/khumai.php" class="trek-card">
      <img src="image/khumai.jpeg" alt="Khumai Danda" />
      <h3>Khumai Danda</h3>
    </a>
  </div>
</section>

<!-- Trekking Videos -->
<section class="video-section" id="videos">
  <h2>Featured Trekking Videos</h2>
  <div class="video-grid">
    <div class="video-box">
      <div class="video-title">Hidden Danda</div>
      <video muted preload="metadata" controls>
        <source src="videos/hidden.mp4" type="video/mp4" />
      </video>
    </div>
    <div class="video-box">
      <div class="video-title">Khumai Trek</div>
      <video muted preload="metadata" controls>
        <source src="videos/khumai.mp4" type="video/mp4" />
      </video>
    </div>
    <div class="video-box">
      <div class="video-title">Ride to Siklesh</div>
      <video muted preload="metadata" controls>
        <source src="videos/siklesh.mp4" type="video/mp4" />
      </video>
    </div>
  </div>
</section>

<!-- Chatbot -->
<div id="chatbot-container">
  <div id="chatbot-popup">
    <div class="chat-header">🗻 Trekking Assistant</div>
    <div class="chat-body" id="chat-body">
      <div class="chat-message bot">Hi! How can I help you plan your trek?</div>
    </div>
    <div class="chat-input-area">
      <input type="text" id="chat-input" placeholder="Type a message..." autocomplete="off" />
      <button id="send-btn">Send</button>
    </div>
  </div>
  <div id="chatbot-bubble" title="Toggle Chat">💬</div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="footer-container">
    <p>© 2025 Treksoul. All rights reserved.</p>
    <p>📞 061570030 | ✉️ <a href="mailto:treksoul@hotmail.com">treksoul@hotmail.com</a> | 📍 Kathmandu, Nepal | 🏢 Pokhara Branch</p>
  </div>
</footer>

<script>
  const chatbotPopup = document.getElementById("chatbot-popup");
  const chatbotBubble = document.getElementById("chatbot-bubble");
  const chatInput = document.getElementById("chat-input");
  const chatBody = document.getElementById("chat-body");
  const sendBtn = document.getElementById("send-btn");

  chatbotBubble.addEventListener("click", () => {
    chatbotPopup.style.display = chatbotPopup.style.display === "flex" ? "none" : "flex";
  });

  sendBtn.addEventListener("click", async () => {
    const msg = chatInput.value.trim();
    if (!msg) return;

    const userDiv = document.createElement("div");
    userDiv.className = "chat-message user";
    userDiv.textContent = msg;
    chatBody.appendChild(userDiv);

    chatInput.value = "";

    const botDiv = document.createElement("div");
    botDiv.className = "chat-message bot";
    botDiv.textContent = "...";
    chatBody.appendChild(botDiv);

    chatBody.scrollTop = chatBody.scrollHeight;

    const res = await fetch("ai_chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message: msg })
    });

    const data = await res.json();
    botDiv.textContent = data.reply || "Sorry, I couldn't understand that.";

    chatBody.scrollTop = chatBody.scrollHeight;
  });
</script>

</body>
</html>
