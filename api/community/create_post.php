<?php
header("Content-Type: application/json");
error_reporting(0);

require_once "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data["user_id"] ?? 0;
$content = $data["content"] ?? "";

if ($content === "") {
    echo json_encode(["status" => false]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO community_posts (user_id, content) VALUES (?, ?)"
);
$stmt->bind_param("is", $user_id, $content);
$stmt->execute();

echo json_encode(["status" => true]);
exit;
