<?php
// api/sessions/get_sessions.php

header("Content-Type: application/json");

// 1. Include Database Connection
require_once '../../config/db.php';

// 2. Prepare SQL Query
// JOIN sessions with users to get mentor details
$sql = "SELECT 
            s.id, 
            s.title, 
            s.skill, 
            s.date, 
            s.start_time, 
            s.end_time, 
            s.status,
            u.id AS mentor_id,
            u.full_name AS mentor_name,
            u.profile_image AS mentor_image
        FROM sessions s
        JOIN users u ON s.mentor_id = u.id
        ORDER BY s.date ASC, s.start_time ASC";

// 3. Execute Query
$result = $conn->query($sql);

if ($result) {
    $sessions = [];
    
    while ($row = $result->fetch_assoc()) {
        // format the data structure as requested
        $sessionItem = [
            "id" => (int)$row['id'],
            "title" => $row['title'],
            "skill" => $row['skill'],
            "date" => $row['date'],
            "start_time" => substr($row['start_time'], 0, 5), // HH:MM
            "end_time" => substr($row['end_time'], 0, 5),     // HH:MM
            "status" => $row['status'],
            "mentor" => [
                "id" => (int)$row['mentor_id'],
                "name" => $row['mentor_name'],
                "image" => $row['mentor_image']
            ]
        ];
        $sessions[] = $sessionItem;
    }

    echo json_encode([
        "status" => true,
        "message" => "Sessions fetched",
        "data" => $sessions
    ]);

} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to fetch sessions: " . $conn->error,
        "data" => null
    ]);
}

$conn->close();
?>
