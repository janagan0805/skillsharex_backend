<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$user_id = (int) ($data['user_id'] ?? 0);
$post_id = (int) ($data['post_id'] ?? 0);
$content = trim($data['content'] ?? "");

if ($user_id <= 0 || $post_id <= 0 || $content === "") {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO post_comments (user_id, post_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $post_id, $content);

if ($stmt->execute()) {
    $comment_id = $stmt->insert_id;

    // Get new comment count
    $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM post_comments WHERE post_id = ?");
    $countStmt->bind_param("i", $post_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $comment_count = $countResult->fetch_assoc()['count'];

    echo json_encode([
        "success" => true,
        "comment_id" => $comment_id,
        "comment_count" => $comment_count
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$conn->close();
?>