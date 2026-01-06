<?php
require_once("../../config/db.php");
require_once("../../config/response.php");

$mentorId = $_GET["mentor_id"] ?? "";

if ($mentorId=="") sendResponse(false, "Mentor ID required");

$result = $conn->query(
  "SELECT id,name,phone,role FROM users WHERE id='$mentorId'"
);

if ($row = $result->fetch_assoc()) {
    sendResponse(true, "Mentor fetched", $row);
} else {
    sendResponse(false, "Mentor not found");
}
