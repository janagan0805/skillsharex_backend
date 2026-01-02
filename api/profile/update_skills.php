<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(["status" => "error", "message" => "POST required"]));
}

// Get Input
$data = json_decode(file_get_contents("php://input"), true);

// Assume user is authenticated and we have user_id. 
// IN REAL APP: Get user_id from Session or Token.
// FOR DEMO: Expect 'user_id' in body.
$userId = $data['user_id'] ?? null;
$skillName = $data['skill_name'] ?? null;
$type = $data['type'] ?? 'learner'; // 'learner' or 'mentor'

if (!$userId || !$skillName) {
    die(json_encode(["status" => "error", "message" => "user_id and skill_name required"]));
}

// 1. Check if skill exists, if not create it (or error out? Let's find existing first)
$stmt = $conn->prepare("SELECT id FROM skills WHERE name = ?");
$stmt->bind_param("s", $skillName);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // Option: Auto-create skill or fail. Let's fail for strictness unless we want dynamic skills.
    // Let's Auto-create for better UX.
    $insertSkill = $conn->prepare("INSERT INTO skills (name) VALUES (?)");
    $insertSkill->bind_param("s", $skillName);
    if (!$insertSkill->execute()) {
        die(json_encode(["status" => "error", "message" => "Failed to create skill"]));
    }
    $skillId = $insertSkill->insert_id;
} else {
    $skillId = $res->fetch_assoc()['id'];
}

// 2. Link user to skill
// Check if already linked
$checkLink = $conn->prepare("SELECT id FROM user_skills WHERE user_id = ? AND skill_id = ? AND type = ?");
$checkLink->bind_param("iis", $userId, $skillId, $type);
$checkLink->execute();

if ($checkLink->get_result()->num_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Skill already verified"]);
    exit;
}

$link = $conn->prepare("INSERT INTO user_skills (user_id, skill_id, type) VALUES (?, ?, ?)");
$link->bind_param("iis", $userId, $skillId, $type);

if ($link->execute()) {
    echo json_encode(["status" => "success", "message" => "Skill added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Db Error"]);
}
?>
