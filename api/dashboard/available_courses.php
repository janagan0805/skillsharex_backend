<?php
header("Content-Type: application/json");
require_once("../../config/config.php");
require_once("../../config/response.php");

// Query: Get courses linked to users (mentors) via user_courses
// We filter by role='mentor' to ensure we only get courses taught by mentors
$sql = "
SELECT 
    c.id AS id, 
    c.title AS course_name, 
    u.full_name AS mentor_name, 
    c.image_path AS cover_image, 
    u.status AS mentor_online_status
FROM user_courses uc
JOIN courses c ON uc.course_id = c.id
JOIN users u ON uc.user_id = u.id
WHERE u.role = 'mentor'
";

$result = $conn->query($sql);

$courses = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $courses[] = $row;
    }
}

sendResponse(true, "Available courses", $courses);
?>
