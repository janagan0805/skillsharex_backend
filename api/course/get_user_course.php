<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    echo json_encode([
        "success" => false,
        "message" => "User ID is required"
    ]);
    exit;
}

$sql = "
SELECT 
    id,
    title AS course_name,
    description,
    image_path AS cover_image,
    rating,
    rating_count
FROM courses
WHERE user_id = ?
ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $courses
]);
