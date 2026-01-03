<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Create Post
    $input = json_decode(file_get_contents("php://input"), true);
    
    $userId = $input['user_id'] ?? null;
    $postType = $input['post_type'] ?? 'Question';
    $skillId = $input['skill_id'] ?? null; // Category
    $title = $input['title'] ?? null;
    $description = $input['description'] ?? null;
    
    if (!$userId || !$description) {
        die(json_encode(["status" => "error", "message" => "user_id and description are required"]));
    }
    
    // Validate Skill ID if provided (or find by name if passed as name? UI shows dropdown, likely Name or ID. Let's assume ID or Name. 
    // UI shows "Android". If frontend sends "Android" (string), we need to look it up.
    // Let's handle both: if numeric -> ID, if string -> find/create.
    // Actually simplicity: Let's assume frontend sends matching Skill ID or handle string lookup.
    
    if ($skillId && !is_numeric($skillId)) {
        // Look up by name
        $sName = $skillId;
        $check = $conn->prepare("SELECT id FROM skills WHERE name = ?");
        $check->bind_param("s", $sName);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            $skillId = $res->fetch_assoc()['id'];
        } else {
             // Create ??
             $skillId = null; // Or create. Let's keep null if invalid category.
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO posts (user_id, post_type, skill_id, title, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isis", $userId, $postType, $skillId, $title, $description);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Post created"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create post"]);
    }

} elseif ($method === 'GET') {
    // List Posts
    $skillFilter = $_GET['skill'] ?? null;
    
    $sql = "SELECT 
            p.id,
            p.post_type,
            p.title,
            p.description,
            p.created_at,
            u.full_name AS author_name,
            u.profile_image AS author_avatar,
            u.role AS author_role,
            s.name AS skill_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN skills s ON p.skill_id = s.id
        WHERE 1=1";

            
    if ($skillFilter) {
        $sql .= " AND s.name LIKE ?";
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    
    if ($skillFilter) {
        $param = "%$skillFilter%";
        $stmt->bind_param("s", $param);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    echo json_encode(["status" => "success", "data" => $posts]);
}
?>
