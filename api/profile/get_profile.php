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
        bio,
        profile_image,
        skills
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

// ✅ CONVERT SKILLS TO ARRAY
$skillsArray = [];
if (!empty($row['skills'])) {
    // If stored as JSON
    $decoded = json_decode($row['skills'], true);
    if (is_array($decoded)) {
        $skillsArray = $decoded;
    } else {
        // fallback: comma-separated
        $skillsArray = explode(",", $row['skills']);
    }
}

// ✅ FINAL RESPONSE
echo json_encode([
    "success" => true,
    "data" => [
        "name" => $row['name'],
        "role" => $row['role'],
        "bio" => $row['bio'],
        "profile_image" => $row['profile_image'],
        "skills" => $skillsArray
    ]
]);
