<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$user_id = (int)($_GET['user_id'] ?? 0);
if ($user_id <= 0) {
    echo json_encode(["success" => false]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, full_name as name, role, profile_image as avatar_url FROM users WHERE id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode([
    "success" => true,
    "user" => $user
]);
