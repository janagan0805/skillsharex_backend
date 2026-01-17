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

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, "Invalid request method. Only POST is allowed.");
}

// 1. Validate required fields
// Note: Android Retrofit @Multipart sends data in $_POST
$userId = $_POST['user_id'] ?? null;
$title = $_POST['title'] ?? null;
$status = $_POST['status'] ?? null;
$description = $_POST['description'] ?? ''; // Optional or default empty

if (!$userId || !$title || !$status) {
    sendResponse(false, "Missing required fields: user_id, title, or status.");
}

// Validate Status Enum
$allowedStatuses = ['active', 'inactive'];
if (!in_array($status, $allowedStatuses)) {
    sendResponse(false, "Invalid status. Allowed values: 'active', 'inactive'.");
}

// 🔒 Business Rule: Only one active course per user
if ($status === 'active') {

    $checkSql = "SELECT id FROM courses WHERE user_id = ? AND status = 'active' LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);

    if (!$checkStmt) {
        sendResponse(false, "Server error while validating active course.");
    }

    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        sendResponse(
            false,
            "You already have an active course. Please deactivate it before creating a new active course."
        );
    }

    $checkStmt->close();
}


// 2. Validate Image
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    sendResponse(false, "Image file is required and must be valid.");
}

$fileTmpPath = $_FILES['image']['tmp_name'];
$originalFileName = $_FILES['image']['name'];
$fileSize = $_FILES['image']['size'];
$fileType = $_FILES['image']['type'];

// Allow only specific image extensions
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
$fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    sendResponse(false, "Invalid image format. Allowed: jpg, jpeg, png, webp.");
}

// 3. Generate Unique Filename & Save
// Define upload directory relative to this script
$uploadDir = __DIR__ . '/../../uploads/courses/';
if (!is_dir($uploadDir)) {
    // Create directory if it doesn't exist (recursive)
    if (!mkdir($uploadDir, 0777, true)) {
        sendResponse(false, "Server error: Unable to create upload directory.");
    }
}

// Generate unique filename to prevent overwrites
$newFileName = uniqid('course_', true) . '.' . $fileExtension;
$destinationPath = $uploadDir . $newFileName;

// Path to store in database (relative to project root usually)
$dbPath = 'uploads/courses/' . $newFileName;

if (!move_uploaded_file($fileTmpPath, $destinationPath)) {
    sendResponse(false, "Failed to upload image.");
}

// 4. Insert into Database
// Use prepared statements for security
$sql = "INSERT INTO courses (user_id, title, description, status, image_path) VALUES (?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters: i = integer, s = string
    $stmt->bind_param("issss", $userId, $title, $description, $status, $dbPath);

    if ($stmt->execute()) {
        sendResponse(true, "Course created successfully.");
    } else {
        // Clean up the uploaded file if DB insert fails to avoid orphan files
        if (file_exists($destinationPath)) {
            unlink($destinationPath);
        }
        sendResponse(false, "Database error: Failed to insert course data.");
    }
    $stmt->close();
} else {
    // Clean up file if prepare fails
    if (file_exists($destinationPath)) {
        unlink($destinationPath);
    }
    sendResponse(false, "Database error: Unable to prepare statement.");
}

$conn->close();
?>
