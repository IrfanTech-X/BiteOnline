<?php
$servername = "localhost";
$username = "root"; // Default username for phpMyAdmin
$password = ""; // Leave empty for default setup
$dbname = "online_food_ordering_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
