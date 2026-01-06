<?php
// Database configuration

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "skillsharex"; // ⚠️ use your actual database name

// Create connection
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed",
        "error" => mysqli_connect_error()
    ]));
}

// Set charset (important for emojis & text)
mysqli_set_charset($conn, "utf8mb4");
