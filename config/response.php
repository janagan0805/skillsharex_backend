<?php
// Standardized JSON Response Functions

function sendResponse($success, $message, $data = null, $code = 200) {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function successResponse($message, $data = null) {
    sendResponse(true, $message, $data, 200);
}

function errorResponse($message, $code = 400) {
    sendResponse(false, $message, null, $code);
}
?>
