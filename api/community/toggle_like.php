<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = (int) ($data['user_id'] ?? 0);
$post_id = (int) ($data['post_id'] ?? 0);

if ($user_id <= 0 || $post_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

// Check if already liked
$checkStmt = $conn->prepare("SELECT id FROM post_likes WHERE user_id = ? AND post_id = ?");
$checkStmt->bind_param("ii", $user_id, $post_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Unlike
    $deleteStmt = $conn->prepare("DELETE FROM post_likes WHERE user_id = ? AND post_id = ?");
    $deleteStmt->bind_param("ii", $user_id, $post_id);
    $deleteStmt->execute();
    $is_liked = false;
} else {
    // Like
    $insertStmt = $conn->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)");
    $insertStmt->bind_param("ii", $user_id, $post_id);
    $insertStmt->execute();
    $is_liked = true;
}

// Get new like count
$countStmt = $conn->prepare("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?");
$countStmt->bind_param("i", $post_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$like_count = $countResult->fetch_assoc()['count'];

echo json_encode([
    "success" => true,
    "is_liked" => $is_liked,
    "like_count" => $like_count
]);
$conn->close();
?>