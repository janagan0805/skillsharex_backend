<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Send Message
    $data = json_decode(file_get_contents("php://input"), true);
    
    $senderId = $data['sender_id'] ?? null;
    $receiverId = $data['receiver_id'] ?? null;
    $message = $data['message'] ?? null;
    
    if (!$senderId || !$receiverId || !$message) {
        die(json_encode(["status" => "error", "message" => "Missing fields"]));
    }
    
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $senderId, $receiverId, $message);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message sent"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }
    
} elseif ($method === 'GET') {
    // Get Messages
    $u1 = $_GET['user1'] ?? null;
    $u2 = $_GET['user2'] ?? null;
    
    if (!$u1 || !$u2) {
        die(json_encode(["status" => "error", "message" => "user1 and user2 required"]));
    }
    
    // Fetch conversation between two users
    $sql = "SELECT m.*, u.full_name as sender_name 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id
            WHERE (sender_id = ? AND receiver_id = ?) 
               OR (sender_id = ? AND receiver_id = ?) 
            ORDER BY created_at ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $u1, $u2, $u2, $u1);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    echo json_encode(["status" => "success", "data" => $messages]);
}
?>
