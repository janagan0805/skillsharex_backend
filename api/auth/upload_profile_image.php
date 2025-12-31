<?php
require_once("../config/config.php");
require_once("../config/response.php");

$userId = $_POST['user_id'] ?? null;

if ($userId === null) {
    sendResponse(false, "User ID is required");
}


if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    sendResponse(false, "Image not found");
}


$targetDir = "../uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = "profile_" . $userId . "_" . time() . ".jpg";
$targetFile = $targetDir . $fileName;

if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {

    $imagePath = "uploads/" . $fileName;

    $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
    $stmt->bind_param("si", $imagePath, $userId);
    $stmt->execute();

    sendResponse(true, "Image uploaded", [
        "image_url" => $imagePath
    ]);
} else {
    sendResponse(false, "Upload failed");
}
