<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Start Live Session (or Request)
    $data = json_decode(file_get_contents("php://input"), true);
    
    $mentorId = $data['mentor_id'] ?? null;
    $learnerId = $data['learner_id'] ?? null;
    $skillId = $data['skill_id'] ?? null;
    
    if (!$mentorId || !$learnerId || !$skillId) {
         die(json_encode(["status" => "error", "message" => "Missing fields"]));
    }
    
    $stmt = $conn->prepare("INSERT INTO sessions (mentor_id, learner_id, skill_id, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iii", $mentorId, $learnerId, $skillId);
    
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Session requested",
            "session_id" => $stmt->insert_id
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create session"]);
    }

} elseif ($method === 'GET') {
    // Get Session Status / List
    $userId = $_GET['user_id'] ?? null;
    
    if (!$userId) {
        die(json_encode(["status" => "error", "message" => "user_id required"]));
    }
    
    $sql = "SELECT s.*, sk.name as skill_name, u.full_name as other_party_name
            FROM sessions s
            JOIN skills sk ON s.skill_id = sk.id
            JOIN users u ON (CASE WHEN s.mentor_id = ? THEN s.learner_id ELSE s.mentor_id END) = u.id
            WHERE s.mentor_id = ? OR s.learner_id = ?
            ORDER BY s.created_at DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }
    
    echo json_encode(["status" => "success", "data" => $sessions]);
}
?>
