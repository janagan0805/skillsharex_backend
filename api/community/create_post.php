<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = (int)($data['user_id'] ?? 0);
$title   = trim($data['title'] ?? "");
$content = trim($data['content'] ?? "");
$topic   = trim($data['topic'] ?? "");

if (
    $user_id <= 0 ||
    $title === "" || strlen($title) > 80 ||
    $content === "" || strlen($content) > 500 ||
    $topic === ""
) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO community_posts (user_id, title, content, topic)
     VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("isss", $user_id, $title, $content, $topic);

if (!$stmt->execute()) {
    echo json_encode(["success" => false]);
    exit;
}

echo json_encode([
    "success" => true,
    "post_id" => $stmt->insert_id
]);
