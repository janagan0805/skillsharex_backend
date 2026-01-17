<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/response.php';

$courseId = $_GET['course_id'] ?? null;

if (!$courseId) {
    sendResponse(false, "Course ID is required");
    exit;
}

$sql = "
SELECT 
    c.id,
    c.title,
    c.description,
    c.image_path,
    c.rating,
    c.rating_count,
    c.status,
    u.id AS mentor_id,
    u.full_name AS mentor_name,
    u.status AS mentor_status,
    u.profile_image AS mentor_image
FROM courses c
JOIN users u 
    ON u.id = c.user_id AND u.role = 'mentor'
WHERE c.id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    sendResponse(true, "Course details fetched successfully", [
        "id" => (int)$row["id"],
        "course_name" => $row["title"],
        "description" => $row["description"],
        "cover_image" => $row["image_path"],
        "rating" => (float)$row["rating"],
        "rating_count" => (int)$row["rating_count"],
        "status" => $row["status"],
        "mentor" => [
            "id" => (int)$row["mentor_id"],
            "name" => $row["mentor_name"],
            "status" => $row["mentor_status"],
            "image" => $row["mentor_image"]
        ]
    ]);
} else {
    sendResponse(false, "Course not found");
}
