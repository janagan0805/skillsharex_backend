<?php
require "../config/config.php";

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode([
    "status" => "success",
    "courses" => $courses
]);
?>
