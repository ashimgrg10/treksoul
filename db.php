<?php
$host = "localhost";
$dbname = "treksoul";
$username = "root";
$password = ""; // Use your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
