
<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
  <div class="nav-left">
    <span class="logo">Treksoul</span>
  </div>

  <!-- Hamburger icon (visible on mobile only) -->
  <div class="menu-icon" onclick="toggleMenu()">☰</div>

  <div class="nav-right" id="navLinks">
    <a href="index.php" class="nav-link">Home</a>

    <?php if (isset($_SESSION["user_id"])): ?>
      <span style="color: white; margin-left: 20px;">
        Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!
      </span>
      <a href="logout.php" class="nav-link">Logout</a>
    <?php else: ?>
      <a href="login.php" class="nav-link">Login</a>
      <a href="register.php" class="nav-link">Register</a>
    <?php endif; ?>

    <!-- New Pages -->
    <a href="#destinations" class="nav-link">Trekking Destinations</a>
    <a href="#videos" class="nav-link">Trekking Videos</a>
    <a href="#about" class="nav-link">About Us</a>
  </div>
</nav>