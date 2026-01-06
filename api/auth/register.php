<?php
header("Content-Type: application/json");

require_once("../../config/config.php");
require_once("../../config/response.php");

$fullName = $_POST['full_name'] ?? null;
$email    = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$phone    = $_POST['phone'] ?? null;   // ✅ NEW

// 🔐 Validation
if (!$fullName || !$email || !$password || !$phone) {
    sendResponse(false, "All fields are required");
    exit;
}

// ✅ Phone validation (basic but important)
if (!preg_match("/^[0-9]{10,15}$/", $phone)) {
    sendResponse(false, "Invalid phone number");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// 🔍 Check email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    sendResponse(false, "Email already registered");
    exit;
}

// ✅ Insert user with phone
$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("ssss", $fullName, $email, $phone, $hashedPassword);

if ($stmt->execute()) {
    sendResponse(true, "Registration successful");
    exit;
} else {
    sendResponse(false, "Registration failed");
    exit;
}
