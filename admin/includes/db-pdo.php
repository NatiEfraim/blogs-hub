<?php
// Database configuration
$host = 'localhost'; // Replace with your database host
$dbname = 'blogs'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password (if any)

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception (optional but recommended)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Output a message if the connection is successful (optional)
    // echo "Connected successfully";
} catch (PDOException $e) {
    // Handle database connection errors
    die("Connection failed: " . $e->getMessage());
}
