<?php
ob_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../../config/config.php';

function sendResponse($status, $message) {
    ob_clean();
    echo json_encode([
        "status" => $status,
        "message" => $message
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, "Invalid request method");
}

// Required fields
$courseId = $_POST['course_id'] ?? null;
$userId   = $_POST['user_id'] ?? null;
$title    = $_POST['title'] ?? null;
$status   = $_POST['status'] ?? null;
$description = $_POST['description'] ?? '';

if (!$courseId || !$userId || !$title || !$status) {
    sendResponse(false, "Missing required fields");
}

// Validate status
$allowedStatuses = ['active', 'inactive'];
if (!in_array($status, $allowedStatuses)) {
    sendResponse(false, "Invalid status value");
}

// 🔒 Rule: Only ONE active course per user
if ($status === 'active') {
    $checkSql = "
        SELECT id 
        FROM courses 
        WHERE user_id = ? 
          AND status = 'active' 
          AND id != ?
        LIMIT 1
    ";

    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $userId, $courseId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        sendResponse(
            false,
            "You already have another active course. Deactivate it first."
        );
    }

    $checkStmt->close();
}

// Update course
$sql = "
    UPDATE courses 
    SET title = ?, description = ?, status = ?
    WHERE id = ? AND user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssii",
    $title,
    $description,
    $status,
    $courseId,
    $userId
);

if ($stmt->execute()) {
    sendResponse(true, "Course updated successfully");
} else {
    sendResponse(false, "Failed to update course");
}

$stmt->close();
$conn->close();
