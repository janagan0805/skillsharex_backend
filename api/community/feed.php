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

    // Get current user_id from query param to check 'is_liked' status
    $current_user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

    $sqlPosts = "
        SELECT 
            p.id AS post_id,
            p.topic AS post_type,
            u.full_name AS user_name,
            u.profile_image AS user_avatar_url,
            p.title AS post_title,
            p.image_path,
            p.content AS post_content,
            p.created_at AS timestamp,
            COUNT(DISTINCT l.id) AS like_count,
            COUNT(DISTINCT c.id) AS comment_count,
            MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) AS is_liked
        FROM community_posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN post_likes l ON p.id = l.post_id
        LEFT JOIN post_comments c ON p.id = c.post_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT 20
    ";

    $stmtPosts = $conn->prepare($sqlPosts);
    if ($stmtPosts) {
        $stmtPosts->bind_param("i", $current_user_id);
        $stmtPosts->execute();
        $resultPosts = $stmtPosts->get_result();

        while ($row = $resultPosts->fetch_assoc()) {

            // Format post_type (topic) if null default to 'discussion'
            $post_type = $row['post_type'] ? strtolower($row['post_type']) : 'discussion';

            // Map to response structure
            $response['feed_posts'][] = [
                'post_id' => (string) $row['post_id'],
                'post_type' => $post_type,
                'user_name' => $row['user_name'],
                'user_avatar_url' => $row['user_avatar_url'],
                'post_title' => $row['post_title'] ?? 'Untitled',
                'post_content' => $row['post_content'],
                'post_image' => $row['image_path'], // New field
                'like_count' => (int) $row['like_count'],
                'comment_count' => (int) $row['comment_count'],
                'is_liked' => (bool) $row['is_liked'],
                'timestamp' => \DateTime::createFromFormat('Y-m-d H:i:s', $row['timestamp'])->format(\DateTime::ATOM)
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
                'event_id' => (string) $row['event_id'],
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