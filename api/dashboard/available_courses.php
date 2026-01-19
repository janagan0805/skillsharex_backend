<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$sql = "
SELECT 
    c.id AS course_id,
    c.title,
    c.image_path,
    c.rating,
    c.rating_count,
    c.status,
    u.full_name AS mentor_name,
    u.status AS mentor_status
FROM courses c
JOIN users u 
    ON u.id = c.user_id AND u.role = 'mentor'
ORDER BY c.created_at DESC
LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = [
        "id" => (int) $row["course_id"],
        "course_name" => $row["title"],
        "cover_image" => $row["image_path"],
        "rating" => (float) $row["rating"],
        "rating_count" => (int) $row["rating_count"],
        "mentor_name" => $row["mentor_name"],
        "mentor_online_status" => $row["mentor_status"],
        "status" => $row["status"]
    ];
}

echo json_encode([
    "success" => true,
    "message" => "Courses fetched successfully",
    "data" => $courses
]);
