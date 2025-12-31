<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "skillsharex";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed"
    ]));
    exit;
}
?>
