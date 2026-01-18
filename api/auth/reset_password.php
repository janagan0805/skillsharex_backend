<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$token = $input['token'] ?? '';
$newPassword = $input['new_password'] ?? '';

if (empty($token) || empty($newPassword)) {
    echo json_encode(["status" => "error", "message" => "Token and new password are required"]);
    exit;
}

// Verify token
$now = date("Y-m-d H:i:s");
$stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expiry > ?");
$stmt->bind_param("ss", $token, $now);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid or expired token"]);
    exit;
}

$row = $result->fetch_assoc();
$email = $row['email'];
$stmt->close();

// Update password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$updStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$updStmt->bind_param("ss", $hashedPassword, $email);

if ($updStmt->execute()) {
    // Delete used token
    $delStmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $delStmt->bind_param("s", $token);
    $delStmt->execute();

    echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update password"]);
}
?>