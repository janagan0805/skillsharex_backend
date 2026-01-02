<?php
// Phase 0: Demo Mode Configuration

// 1. Database Credentials (keeping for future connection, but not strictly forced for dummy data logic)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'skillsharex');

// 2. Demo Constants
define('DEMO_USER_ID', 1);

// 3. Establish Connection (Optional for Phase 0 purely hardcoded, but good practice to have ready)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    // For Phase 0 Demo, we might want to suppress DB errors if we are just returning hardcoded data,
    // but the prompt asked for "Database connection" to be present.
    // We will output a JSON error if it fails even in Phase 0 to avoid silent failures.
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Set charset
$conn->set_charset("utf8mb4");
?>
