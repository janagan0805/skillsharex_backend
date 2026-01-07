<?php
header("Content-Type: application/json");

require_once("../../config/db.php");
require_once("../../config/response.php");

/*
 API: get_profile.php
 INPUT: ?mentor_id=1
 TABLE: users
*/

$mentorId = $_GET["mentor_id"] ?? "";

if ($mentorId === "") {
    sendResponse(false, "Mentor ID required");
    exit;
}

$stmt = $conn->prepare(
    "SELECT 
        id,
        full_name,
        phone,
        profile_image,
        rating,
        status
     FROM users
     WHERE id = ? AND role = 'mentor'"
);

$stmt->bind_param("i", $mentorId);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    $data = [
        "id"     => (int)$row["id"],
        "name"   => $row["full_name"],
        "phone"  => $row["phone"],
        "image"  => $row["profile_image"],
        "rating" => (float)$row["rating"],
        "status" => $row["status"]
    ];

    sendResponse(true, "Mentor fetched", $data);
    exit;

} else {
    sendResponse(false, "Mentor not found");
    exit;
}
