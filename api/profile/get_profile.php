<?php
header("Content-Type: application/json");

// ✅ DB CONNECTION
require_once("../../config/db.php");

// ✅ GET user_id from query
$userId = $_GET['user_id'] ?? '';

if ($userId === '') {
    echo json_encode([
        "success" => false,
        "message" => "User ID required",
        "data" => null
    ]);
    exit;
}

// ✅ FETCH USER PROFILE
$sql = "
    SELECT 
        full_name AS name,
        role,
        profile_image
    FROM users
    WHERE id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "User not found",
        "data" => null
    ]);
    exit;
}

$row = $result->fetch_assoc();

// ✅ FETCH USER SKILLS
$skillsSql = "
    SELECT s.name
    FROM user_skills us
    JOIN skills s ON us.skill_id = s.id
    WHERE us.user_id = ?
";
$skillsStmt = $conn->prepare($skillsSql);
$skillsStmt->bind_param("i", $userId);
$skillsStmt->execute();
$skillsResult = $skillsStmt->get_result();

$skillsArray = [];
while ($skillRow = $skillsResult->fetch_assoc()) {
    $skillsArray[] = $skillRow['name'];
}

// ✅ FINAL RESPONSE
echo json_encode([
    "success" => true,
    "data" => [
        "name" => $row['name'],
        "role" => $row['role'],
        "profile_image" => $row['profile_image'],
        "skills" => $skillsArray
    ]
]);
