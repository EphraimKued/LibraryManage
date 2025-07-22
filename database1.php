<?php
// Database configuration
$host = 'localhost';        // Database host (usually localhost)
$dbname = 'WebCa';  // Database name
$username = 'root';// Database username
$password = '';// Database password

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>