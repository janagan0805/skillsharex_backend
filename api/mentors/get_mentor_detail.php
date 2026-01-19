<?php
header("Content-Type: application/json");

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/response.php';

$mentorId = $_GET['mentor_id'] ?? null;

if (!$mentorId) {
    sendResponse(false, "Mentor ID is required");
    exit;
}

/*
 We will fetch:
 - mentor basic info (users)
 - mentor skill (skills via user_skills)
 - mentor phone (users table)
 - mentor rating & rating_count (users table)
*/

$sql = "
SELECT 
    u.id,
    u.full_name,
    u.status,
    u.phone,
    u.profile_image,
    u.rating,
    u.rating_count,
    u.created_at,
    s.name AS skill_name
FROM users u
JOIN user_skills us 
    ON us.user_id = u.id AND us.type = 'mentor'
JOIN skills s 
    ON s.id = us.skill_id
WHERE u.id = ?
AND u.role = 'mentor'
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $mentorId);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    sendResponse(false, "Mentor not found");
    exit;
}

/*
 Calculate experience years from created_at
 (simple & demo-safe)
*/
$createdYear = (int) date("Y", strtotime($row["created_at"]));
$currentYear = (int) date("Y");
$experienceYears = max(0, $currentYear - $createdYear);

/*
 Build expertise list:
 For now → single skill as list
 (Later you can expand to multiple skills easily)
*/
$expertiseList = [$row["skill_name"]];

$data = [
    "id" => (int) $row["id"],
    "name" => $row["full_name"],
    "skill" => $row["skill_name"],
    "phone" => $row["phone"],
    "rating" => (float) $row["rating"],
    "ratingCount" => (int) $row["rating_count"],
    "bio" => "Experienced mentor in " . $row["skill_name"] . " with real-world teaching experience.",
    "experienceYears" => $experienceYears,
    "expertiseList" => $expertiseList,
    "image" => $row["profile_image"],
    "status" => $row["status"],
];

sendResponse(true, "Mentor details fetched successfully", $data);
