<?php
header("Content-Type: application/json");
require_once("../../config/config.php");
require_once("../../config/response.php");

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
    c.created_at,
    u.id AS mentor_id,
    u.full_name AS mentor_name,
    u.status AS mentor_status,
    u.profile_image AS mentor_image
FROM courses c
LEFT JOIN user_courses uc ON c.id = uc.course_id
LEFT JOIN users u ON uc.user_id = u.id AND u.role = 'mentor'
WHERE c.id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Cast numeric types
    $row['id'] = (int)$row['id'];
    if ($row['mentor_id']) {
        $row['mentor_id'] = (int)$row['mentor_id'];
    }
    
    sendResponse(true, "Course details fetched successfully", $row);
} else {
    sendResponse(false, "Course not found");
}

$stmt->close();
$conn->close();
?>
