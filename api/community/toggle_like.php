<?php
require_once "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$post_id = $data["post_id"];
$user_id = $data["user_id"];

$check = $conn->query(
    "SELECT * FROM post_likes WHERE post_id=$post_id AND user_id=$user_id"
);

if ($check->num_rows > 0) {
    $conn->query(
        "DELETE FROM post_likes WHERE post_id=$post_id AND user_id=$user_id"
    );
} else {
    $conn->query(
        "INSERT INTO post_likes (post_id, user_id) VALUES ($post_id, $user_id)"
    );
}

echo json_encode(["status" => true]);
