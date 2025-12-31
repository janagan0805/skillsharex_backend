<?php
header("Content-Type: application/json");
require_once("../../config/config.php");
require_once("../../config/response.php");

$userId = $_POST['user_id'] ?? null;

if (!$userId) {
    sendResponse(false, "User ID required");
    exit;
}

$update = $conn->prepare("UPDATE users SET status='offline' WHERE id=?");
$update->bind_param("i", $userId);
$update->execute();

sendResponse(true, "Logged out");
exit;
