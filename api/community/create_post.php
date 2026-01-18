<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

// Check if request is multipart/form-data (for file upload)
// If it is, $_POST and $_FILES will be populated properly.
// If it's pure JSON, we read php://input. 
// However, the frontend for file upload MUST use multipart/form-data.

$user_id = (int) ($_POST['user_id'] ?? 0);
$title = trim($_POST['title'] ?? "");
$content = trim($_POST['content'] ?? "");
$topic = trim($_POST['topic'] ?? "");

// If $_POST is empty, it might be a JSON request (backward compatibility or no image)
if (empty($_POST) && empty($_FILES)) {
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data) {
        $user_id = (int) ($data['user_id'] ?? 0);
        $title = trim($data['title'] ?? "");
        $content = trim($data['content'] ?? "");
        $topic = trim($data['topic'] ?? "");
    }
}

if (
    $user_id <= 0 ||
    $title === "" || strlen($title) > 80 ||
    $content === "" || strlen($content) > 500 ||
    $topic === ""
) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../uploads/community/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($fileExt, $allowed)) {
        $newFileName = uniqid('post_', true) . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $destPath)) {
            // Store relative path for frontend access
            // Assuming uploads/ is accessible via web root
            $image_path = 'uploads/community/' . $newFileName;
        }
    }
}

$stmt = $conn->prepare(
    "INSERT INTO community_posts (user_id, title, content, topic, image_path)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("issss", $user_id, $title, $content, $topic, $image_path);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Database error"]);
    exit;
}

echo json_encode([
    "success" => true,
    "post_id" => $stmt->insert_id,
    "image_path" => $image_path
]);
