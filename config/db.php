<?php
// config/db.php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'skillsharex'; // Assuming db name, can be changed

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed: " . $conn->connect_error,
        "data" => null
    ]));
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>
