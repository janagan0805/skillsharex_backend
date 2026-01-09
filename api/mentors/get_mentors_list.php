<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$sql = "
SELECT 
    u.id,
    u.full_name,
    u.status,
    u.profile_image,
    u.rating,
    u.rating_count,
    s.name AS skill_name
FROM users u
LEFT JOIN user_skills us 
    ON us.user_id = u.id AND us.type = 'mentor'
LEFT JOIN skills s 
    ON s.id = us.skill_id
WHERE u.role = 'mentor'
GROUP BY u.id
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$mentors = [];

while ($row = $result->fetch_assoc()) {
    $mentorId = (int)$row['id'];

    if (!isset($mentors[$mentorId])) {
        $mentors[$mentorId] = [
            "id" => $mentorId,
            "name" => $row['full_name'],
            "status" => $row['status'],
            "imageUrl" => $row['profile_image'],
            "rating" => (float)$row['rating'],
            "rating_count" => (int)$row['rating_count'],
            "skill" => $row['skill_name'] ?? ""
        ];
    }
}

echo json_encode([
    "success" => true,
    "message" => "Top mentors fetched successfully",
    "data" => array_values($mentors)
]);
