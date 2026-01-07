<?php
$host = "localhost";
$username = "root"; // Default username for XAMPP/WAMP
$password = ""; // Default password is empty
$dbname = "student_info_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>