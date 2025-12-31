<?php
header("Content-Type: application/json");
require_once("../../config/config.php");
require_once("../../config/response.php");

$userId = $_GET['user_id'] ?? null;

if (!$userId) {
    sendResponse(false, "User ID required");
    exit;
}

$sql = "
SELECT c.id, c.title, c.description, c.image_path
FROM user_courses uc
JOIN courses c ON c.id = uc.course_id
WHERE uc.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

sendResponse(true, "User courses", $courses);
exit;
