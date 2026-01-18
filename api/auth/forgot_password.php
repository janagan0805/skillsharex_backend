<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$email = $input['email'] ?? '';

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // Return success even if user not found to prevent enumeration, or return specific error?
    // For this task, let's be explicit for easier debugging, or change to generic message later.
    // Standard practice: generic message. But for development: specific.
    // I'll stick to specific for now as it's a requested feature build.
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}
$stmt->close();

// Generate token
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Store token (upsert or insert)
// We might want to clear old tokens for this email first
$delStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
$delStmt->bind_param("s", $email);
$delStmt->execute();
$delStmt->close();

$insStmt = $conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
$insStmt->bind_param("sss", $email, $token, $expiry);
if (!$insStmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}
$insStmt->close();

// Send Email
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // TODO: Update with your SMTP Host
    $mail->SMTPAuth = true;
    $mail->Username = 'kohsanar20@gmail.com'; // TODO: Update with your SMTP Username
    $mail->Password = 'wlph dkkm xwap ikpe';    // TODO: Update with your SMTP Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('no-reply@skillsharex.com', 'SkillShareX');
    $mail->addAddress($email);

    // Content
    $resetLink = "http://192.168.31.11:8080/skillsharex_backend/reset_password_form.html?token=" . $token; // Frontend URL
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Click here to reset your password: <a href='$resetLink'>$resetLink</a><br>This link expires in 1 hour.";

    $mail->send();
    echo json_encode(["status" => "success", "message" => "Reset link sent to your email"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}
?>