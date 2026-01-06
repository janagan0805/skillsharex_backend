<?php
header("Content-Type: application/json");

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Only POST method allowed"
    ]);
    exit;
}

// DB connection
require_once __DIR__ . '/../../config/config.php';


// -------------------- INPUT HANDLING (FIX) --------------------

// Try JSON first
$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);

// If JSON is invalid, fall back to FORM DATA
if (!is_array($data)) {
    $data = $_POST;
}

// Get values safely
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

// Validate fields
if ($email === '' || $password === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password are required"
    ]);
    exit;
}

// -------------------- USER FETCH --------------------

$sql = "SELECT id, full_name, email, profile_image, password FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error"
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
    exit;
}

// -------------------- PASSWORD VERIFY --------------------

if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid password"
    ]);
    exit;
}

// -------------------- SUCCESS --------------------

$update = $conn->prepare("UPDATE users SET status='online' WHERE id=?");
$update->bind_param("i", $user['id']);
$update->execute();

echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "user" => [
        "id" => $user['id'],
        "full_name" => $user['full_name'],
        "email" => $user['email'],
        "profile_image" => $user['profile_image']
    ]
]);
exit;
