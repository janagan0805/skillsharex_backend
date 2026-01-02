<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

// Get 'skill' query param if available
$skillName = $_GET['skill'] ?? null;

// Build query
$sql = "
    SELECT u.id, u.full_name, u.email, u.status, s.name as skill_name 
    FROM users u
    JOIN user_skills us ON u.id = us.user_id
    JOIN skills s ON us.skill_id = s.id
    WHERE us.type = 'mentor'
";

if ($skillName) {
    $sql .= " AND s.name LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($skillName) {
    $param = "%" . $skillName . "%";
    $stmt->bind_param("s", $param);
}

$stmt->execute();
$result = $stmt->get_result();

$mentors = [];
while ($row = $result->fetch_assoc()) {
    // Group skills per mentor? Or just list valid mentors?
    // For simplicity, let's return row per skill match or aggregate.
    // Let's aggregate skills for the mentor.
    
    $mentorId = $row['id'];
    if (!isset($mentors[$mentorId])) {
        $mentors[$mentorId] = [
            'id' => $row['id'],
            'full_name' => $row['full_name'],
            'email' => $row['email'],
            'status' => $row['status'],
            'skills' => []
        ];
    }
    $mentors[$mentorId]['skills'][] = $row['skill_name'];
}

echo json_encode([
    "status" => "success",
    "data" => array_values($mentors)
]);
?>
