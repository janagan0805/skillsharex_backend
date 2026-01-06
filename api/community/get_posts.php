<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

require_once "../../config/db.php";

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$posts = [];

$sql = "
    SELECT 
        p.id AS post_id,
        p.content,
        COUNT(l.like_id) AS like_count,
        SUM(CASE WHEN l.user_id = $user_id THEN 1 ELSE 0 END) AS is_liked
    FROM community_posts p
    LEFT JOIN post_likes l ON p.id = l.post_id
    GROUP BY p.id
    ORDER BY p.id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = [
        "post_id" => (int)$row["post_id"],
        "content" => $row["content"],
        "like_count" => (int)$row["like_count"],
        "is_liked" => ((int)$row["is_liked"]) > 0
    ];
}

echo json_encode($posts);
exit;
