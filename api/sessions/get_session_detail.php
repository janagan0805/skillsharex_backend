<?php
// api/sessions/get_session_detail.php

header("Content-Type: application/json");

// 1. Include Database Connection
require_once '../../config/db.php';

// 2. Check for session_id
if (!isset($_GET['session_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Missing session_id parameter",
        "data" => null
    ]);
    exit;
}

$session_id = (int)$_GET['session_id'];

// 3. Prepare SQL Query
// Use prepared statement for security
$sql = "SELECT 
            s.id, 
            s.title, 
            s.description, 
            s.skill, 
            s.date, 
            s.start_time, 
            s.end_time, 
            s.status,
            u.full_name AS mentor_name,
            u.phone AS mentor_phone,
            u.profile_image AS mentor_image
        FROM sessions s
        JOIN users u ON s.mentor_id = u.id
        WHERE s.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $session_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $sessionDetail = [
            "id" => (int)$row['id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "skill" => $row['skill'],
            "date" => $row['date'],
            "start_time" => substr($row['start_time'], 0, 5), // HH:MM
            "end_time" => substr($row['end_time'], 0, 5),     // HH:MM
            "status" => $row['status'],
            "mentor" => [
                "name" => $row['mentor_name'],
                "phone" => $row['mentor_phone'],
                "image" => $row['mentor_image']
            ]
        ];

        echo json_encode([
            "status" => true,
            "message" => "Session detail fetched",
            "data" => $sessionDetail
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Session not found",
            "data" => null
        ]);
    }
} else {
    echo json_encode([
        "status" => false,
        "message" => "Database error: " . $stmt->error,
        "data" => null
    ]);
}

$stmt->close();
$conn->close();
?>
