<?php
// Database configuration
$servername = "localhost"; // The hostname of your database server
$username = "root"; // The username to access the database
$password = ""; // The password to access the database
$dbname = "tour_travel_db"; // The name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
