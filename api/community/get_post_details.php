<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

if ($post_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid post ID"]);
    exit;
}

// Fetch Post Details
$sqlPost = "
    SELECT 
        p.id AS post_id,
        p.topic AS post_type,
        u.full_name AS user_name,
        u.profile_image AS user_avatar_url,
        p.title AS post_title,
        p.content AS post_content,
        p.image_path,
        p.created_at AS timestamp,
        (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) AS comment_count,
        (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id AND user_id = ?) AS is_liked
    FROM community_posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
";

$stmtPost = $conn->prepare($sqlPost);
$stmtPost->bind_param("ii", $user_id, $post_id);
$stmtPost->execute();
$resultPost = $stmtPost->get_result();

if ($row = $resultPost->fetch_assoc()) {
    $postDetails = [
        'post_id' => (string) $row['post_id'],
        'post_type' => $row['post_type'] ? strtolower($row['post_type']) : 'discussion',
        'user_name' => $row['user_name'],
        'user_avatar_url' => $row['user_avatar_url'],
        'post_title' => $row['post_title'],
        'post_content' => $row['post_content'],
        'post_image' => $row['image_path'],
        'like_count' => (int) $row['like_count'],
        'comment_count' => (int) $row['comment_count'],
        'is_liked' => (bool) $row['is_liked'],
        'timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $row['timestamp'])->format(\DateTime::ATOM)
    ];

    // Fetch Comments
    $sqlComments = "
        SELECT 
            c.id AS comment_id,
            c.content,
            u.full_name AS user_name,
            u.profile_image AS user_avatar_url,
            c.created_at
        FROM post_comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at ASC
    ";

    $stmtComments = $conn->prepare($sqlComments);
    $stmtComments->bind_param("i", $post_id);
    $stmtComments->execute();
    $resultComments = $stmtComments->get_result();

    $comments = [];
    while ($cRow = $resultComments->fetch_assoc()) {
        $comments[] = [
            'comment_id' => (string) $cRow['comment_id'],
            'content' => $cRow['content'],
            'user_name' => $cRow['user_name'],
            'user_avatar_url' => $cRow['user_avatar_url'],
            'timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $cRow['created_at'])->format(\DateTime::ATOM)
        ];
    }

    echo json_encode([
        "success" => true,
        "post" => $postDetails,
        "comments" => $comments
    ]);

} else {
    echo json_encode(["success" => false, "message" => "Post not found"]);
}

$conn->close();
?>