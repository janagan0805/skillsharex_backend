<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(0);
ini_set('display_errors', 0);

require_once(__DIR__ . "/../../config/config.php");
require_once(__DIR__ . "/../../config/response.php");

function fixImageOrientation($image, $filePath)
{
    if (!function_exists('exif_read_data')) {
        return $image;
    }

    $exif = @exif_read_data($filePath);
    if (!$exif || !isset($exif['Orientation'])) {
        return $image;
    }

    switch ($exif['Orientation']) {
        case 3:
            $image = imagerotate($image, 180, 0);
            break;
        case 6:
            $image = imagerotate($image, -90, 0);
            break;
        case 8:
            $image = imagerotate($image, 90, 0);
            break;
    }

    return $image;
}


if (!extension_loaded('gd')) {
    sendResponse(false, "Server Error: GD Extension is not enabled (required for image processing)");
}

$userId = $_POST['user_id'] ?? null;

if ($userId === null) {
    sendResponse(false, "User ID is required");
}


// Check for upload errors
if (!isset($_FILES['image'])) {
    sendResponse(false, "Image key 'image' not found in request");
}

if ($_FILES['image']['error'] !== 0) {
    $errorCode = $_FILES['image']['error'];
    $errorMessages = [
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
    ];
    $msg = $errorMessages[$errorCode] ?? "Unknown upload error code: $errorCode";
    sendResponse(false, $msg);
}

$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($_FILES['image']['type'], $allowed)) {
    sendResponse(false, "Invalid image type you f**k");

}

$targetDir = __DIR__ . "/../../uploads/profile/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Fetch user's full name and current profile image
$stmtUser = $conn->prepare("SELECT full_name, profile_image FROM users WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows === 0) {
    sendResponse(false, "User not found");
}

$userRow = $resultUser->fetch_assoc();
$fullName = $userRow['full_name'];
$oldImagePath = $userRow['profile_image']; // Get old image path
$stmtUser->close();

// Sanitize full name for filename
$sanitized_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $fullName);
$timestamp = time();
$fileName = $sanitized_name . "_" . $userId . "_" . $timestamp . ".png";
$targetFile = $targetDir . $fileName;

// Convert and save as PNG
$tempFile = $_FILES['image']['tmp_name'];
$imageInfo = getimagesize($tempFile);

if ($imageInfo === false) {
    sendResponse(false, "Invalid image file");
}

$mimeType = $imageInfo['mime'];
$sourceImage = null;

switch ($mimeType) {
    case 'image/jpeg':
        $sourceImage = imagecreatefromjpeg($tempFile);
        $sourceImage = fixImageOrientation($sourceImage, $tempFile);
        break;
    case 'image/png':
        $sourceImage = imagecreatefrompng($tempFile);
        break;
    case 'image/gif':
        $sourceImage = imagecreatefromgif($tempFile);
        break;
    case 'image/webp':
        $sourceImage = imagecreatefromwebp($tempFile);
        break;
    default:
        sendResponse(false, "Unsupported image format");
}

// Remove old image first as requested
if ($oldImagePath) {
    $oldFile = __DIR__ . "/../../" . $oldImagePath;
    if (file_exists($oldFile)) {
        unlink($oldFile);
    }
}

if ($sourceImage && imagepng($sourceImage, $targetFile)) {
    imagedestroy($sourceImage);

    $imagePath = "uploads/profile/" . $fileName;

    $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
    $stmt->bind_param("si", $imagePath, $userId);

    if ($stmt->execute()) {
        sendResponse(true, "Image uploaded", [
            "image_url" => $imagePath
        ]);
    } else {
        sendResponse(false, "Database update failed");
    }
} else {
    sendResponse(false, "Failed to save image");
}

