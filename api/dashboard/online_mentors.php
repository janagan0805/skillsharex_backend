<?php
header("Content-Type: application/json");
require_once("../../config/config.php");
require_once("../../config/response.php");

$sql = "
SELECT id, full_name, profile_image
FROM users
WHERE role='mentor' AND status='online'
";

$result = $conn->query($sql);

$mentors = [];

while ($row = $result->fetch_assoc()) {
    $mentors[] = $row;
}

sendResponse(true, "Online mentors", $mentors);
exit;
