<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(["status" => "error", "message" => "POST required"]));
}

$input = json_decode(file_get_contents("php://input"), true);

// Fallback to POST if not JSON (for form-data if used without file)
if (!$input) {
    $input = $_POST;
}

$userId = $input['user_id'] ?? null;
if (!$userId) {
    die(json_encode(["status" => "error", "message" => "User ID required"]));
}

// Fields to update
$fullName = $input['full_name'] ?? null;
$role = $input['role'] ?? null;
$bio = $input['bio'] ?? null;
$avatar = $input['avatar'] ?? null; // Optional URL string

// Build Dynamic Update Query
$fields = [];
$params = [];
$types = "";

if ($fullName !== null) {
    $fields[] = "full_name=?";
    $params[] = $fullName;
    $types .= "s";
}
if ($role !== null) {
    $fields[] = "role=?";
    $params[] = $role;
    $types .= "s";
}
if ($bio !== null) {
    $fields[] = "bio=?";
    $params[] = $bio;
    $types .= "s";
}
if ($avatar !== null) {
    $fields[] = "avatar=?";
    $params[] = $avatar;
    $types .= "s";
}

if (!empty($fields)) {
    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id=?";
    $params[] = $userId;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if (!$stmt->execute()) {
        die(json_encode(["status" => "error", "message" => "Failed to update profile info"]));
    }
}

// Handle Skills Sync (If provided)
// Format: "skills": ["React.js", "JavaScript"]
// This will REPLACE existing skills for this user to match the list (Sync).
// Note: We need to decide if these are 'learner' or 'mentor' skills. 
// For simplicity, let's assume this Edit Profile sets 'mentor' skills (since UI shows Mentorship context) 
// OR we can make it generic. The UI shows 'Skills' under 'Edit Profile'.
// Let's default to 'mentor' or try to conserve type if possible. 
// Actually, simple tag list usually implies what THEY offer or have.
if (isset($input['skills']) && is_array($input['skills'])) {
    // 1. Clear existing skills? Or just add new? 
    // "Sync" implies clearing others. Let's Clear all for this user first.
    // WARNING: This clears both learner/mentor if we don't differentiate.
    // Let's clear ALL for simplicity as per "Edit Profile" usually resetting state.
    
    $conn->query("DELETE FROM user_skills WHERE user_id = $userId");
    
    $insert = $conn->prepare("INSERT INTO user_skills (user_id, skill_id, type) VALUES (?, ?, 'mentor')");
    
    foreach ($input['skills'] as $skillName) {
        $skillName = trim($skillName);
        if (!$skillName) continue;
        
        // Find or Create Skill
        $check = $conn->prepare("SELECT id FROM skills WHERE name = ?");
        $check->bind_param("s", $skillName);
        $check->execute();
        $res = $check->get_result();
        
        if ($res->num_rows > 0) {
            $skillId = $res->fetch_assoc()['id'];
        } else {
            $create = $conn->prepare("INSERT INTO skills (name) VALUES (?)");
            $create->bind_param("s", $skillName);
            $create->execute();
            $skillId = $create->insert_id;
        }
        
        // Link
        $insert->bind_param("ii", $userId, $skillId);
        $insert->execute();
    }
}

echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
?>
