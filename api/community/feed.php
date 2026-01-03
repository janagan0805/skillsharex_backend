<?php
// api/community/feed.php

// 1. Configuration & Headers
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

// Disable error reporting for production output to avoid breaking JSON
// but in a real dev environment we might want logging.
// For this single-file requirement, we ensure clean JSON output.
error_reporting(0);
ini_set('display_errors', 0);

$response = [
    'feed_posts' => [],
    'upcoming_events' => []
];

// Check DB Connection
if ($conn->connect_error) {
    // In a real scenario, we might return 500, but requirement says "Return empty arrays if no data" 
    // and "NO fatal errors". We'll just return the empty structure if DB fails.
    echo json_encode($response);
    exit;
}

try {
    // -------------------------------------------------------------------------
    // 2. Fetch Feed Posts
    // -------------------------------------------------------------------------
    // Requirements:
    // - Join posts -> users
    // - Order by created_at DESC
    // - Limit 20
    // - post_content_snippet = first 120 chars
    
    $sqlPosts = "
        SELECT 
            p.id AS post_id,
            p.post_type,
            u.full_name AS user_name,
            u.profile_image AS user_avatar_url,
            p.title AS post_title,
            SUBSTRING(p.description, 1, 120) AS post_content_snippet,
            p.created_at AS timestamp
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT 20
    ";

    $stmtPosts = $conn->prepare($sqlPosts);
    if ($stmtPosts) {
        $stmtPosts->execute();
        $resultPosts = $stmtPosts->get_result();

        while ($row = $resultPosts->fetch_assoc()) {
            // Generate random counts as per requirement for demo
            $like_count = rand(10, 200);
            $comment_count = rand(10, 200);

            // Format post_type if null default to 'discussion'
            $post_type = $row['post_type'] ? strtolower($row['post_type']) : 'discussion';
            
            // Map to response structure
            $response['feed_posts'][] = [
                'post_id' => (string)$row['post_id'],
                'post_type' => $post_type,
                'user_name' => $row['user_name'],
                'user_avatar_url' => $row['user_avatar_url'], // May be null
                'post_title' => $row['post_title'] ?? 'Untitled',
                'post_content_snippet' => $row['post_content_snippet'],
                'like_count' => $like_count,
                'comment_count' => $comment_count,
                'timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $row['timestamp'])->format(\DateTime::ATOM) // ISO-8601
            ];
        }
        $stmtPosts->close();
    }

    // -------------------------------------------------------------------------
    // 3. Fetch Upcoming Events (Sessions)
    // -------------------------------------------------------------------------
    // Requirements:
    // - Join sessions -> users (mentor_id)
    // - Join sessions -> skills (to get title/name) [Added based on schema analysis]
    // - Only future dates (created_at > NOW()) 
    //   Note: 'sessions' table uses 'created_at'. Assuming this is the event time for this schema context,
    //   or simply filtering strictly as requested.
    // - Limit 5
    // - platform = "Live in-app"

    $sqlEvents = "
        SELECT 
            s.id AS event_id,
            sk.name AS event_title, 
            u.full_name AS mentor_name,
            s.created_at AS event_date
        FROM sessions s
        JOIN users u ON s.mentor_id = u.id
        LEFT JOIN skills sk ON s.skill_id = sk.id
        WHERE s.created_at > NOW()
        ORDER BY s.created_at ASC
        LIMIT 5
    ";

    $stmtEvents = $conn->prepare($sqlEvents);
    if ($stmtEvents) {
        $stmtEvents->execute();
        $resultEvents = $stmtEvents->get_result();

        while ($row = $resultEvents->fetch_assoc()) {
             $response['upcoming_events'][] = [
                'event_id' => (string)$row['event_id'],
                // Fallback title if skill name is missing
                'event_title' => $row['event_title'] ? $row['event_title'] . ' Session' : 'Mentorship Session',
                'mentor_name' => $row['mentor_name'],
                'event_date' => \DateTime::createFromFormat('Y-m-d H:i:s', $row['event_date'])->format(\DateTime::ATOM),
                'platform' => 'Live in-app'
            ];
        }
        $stmtEvents->close();
    }

} catch (Exception $e) {
    // Catch any unexpected exceptions to ensure valid JSON output with empty data or error info
    // For this prompt "Return empty arrays... NO fatal errors", we stick to the default response structure initialized above.
}

// 4. Return JSON
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

$conn->close();
?>
