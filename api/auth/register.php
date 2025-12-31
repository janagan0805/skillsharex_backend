<?php
header("Content-Type: application/json");

require_once("../../config/config.php");
require_once("../../config/response.php");

$fullName = $_POST['full_name'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (!$fullName || !$email || !$password) {
    sendResponse(false, "All fields are required");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// check email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    sendResponse(false, "Email already registered");
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $fullName, $email, $hashedPassword);

if ($stmt->execute()) {
    sendResponse(true, "Registration successful");
    exit;
} else {
    sendResponse(false, "Registration failed");
    exit;
}
