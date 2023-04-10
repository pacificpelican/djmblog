<?php
// Start the session
session_start();

$servername = "localhost";
$username = "bigjerk";
$password = "bigjerk";
$dbname = "dminfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
echo "<section id='dbInfo'>";
if ($conn->connect_error) {
  echo("Database connection failed: " . $conn->connect_error);
}
else {
//  echo ("Database connected successfully -- 📼 " . $conn->connection_status);
}
echo "</section>";
?> 


<style>
  #dbInfo {
    font-family: Futura, display;
    color: yellow;
    background: gray;
  }
</style>
