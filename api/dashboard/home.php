<?php
require_once("../../config/config.php");
require_once("../../config/response.php");

/*
 INPUT:
 ?user_id=1
*/

$userId = $_GET["user_id"] ?? "";

if ($userId === "") {
    sendResponse(false, "User ID is required");
}

/* ---------------- USER INFO ---------------- */

$userQuery = $conn->prepare(
    "SELECT id, full_name, role FROM users WHERE id = ?"
);
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

if (!$user) {
    sendResponse(false, "User not found");
}

/* ---------------- HERO SECTION ---------------- */
/*
 Priority:
 1. LIVE session
 2. UPCOMING session
 3. DISCOVER mode
*/

$hero = null;

/* LIVE SESSION */
$liveQuery = $conn->query(
    "SELECT s.id, s.title, u.full_name AS mentor
     FROM sessions s
     JOIN users u ON u.id = s.mentor_id
     WHERE s.status = 'LIVE'
     ORDER BY s.start_time ASC
     LIMIT 1"
);

if ($liveQuery->num_rows > 0) {
    $live = $liveQuery->fetch_assoc();
    $hero = [
        "type" => "LIVE_SESSION",
        "session_id" => $live["id"],
        "title" => $live["title"],
        "mentor" => $live["mentor"],
        "cta" => "Join Now"
    ];
} else {

    /* UPCOMING SESSION */
    $upcomingQuery = $conn->query(
        "SELECT s.id, s.title, u.full_name AS mentor, s.start_time
         FROM sessions s
         JOIN users u ON u.id = s.mentor_id
         WHERE s.status = 'UPCOMING'
         ORDER BY s.start_time ASC
         LIMIT 1"
    );

    if ($upcomingQuery->num_rows > 0) {
        $upcoming = $upcomingQuery->fetch_assoc();
        $hero = [
            "type" => "UPCOMING_SESSION",
            "session_id" => $upcoming["id"],
            "title" => $upcoming["title"],
            "mentor" => $upcoming["mentor"],
            "time" => $upcoming["start_time"],
            "cta" => "Set Reminder"
        ];
    } else {

        /* DISCOVER MODE */
        $hero = [
            "type" => "DISCOVER",
            "message" => "Start learning by exploring mentors and sessions",
            "cta" => "Explore Now"
        ];
    }
}

/* ---------------- AVAILABLE SESSIONS ---------------- */

$availableSessions = [];
$sessionQuery = $conn->query(
    "SELECT s.id, s.title, u.full_name AS mentor, s.status
     FROM sessions s
     JOIN users u ON u.id = s.mentor_id
     WHERE s.status = 'LIVE'
     LIMIT 5"
);

while ($row = $sessionQuery->fetch_assoc()) {
    $availableSessions[] = [
        "session_id" => $row["id"],
        "title" => $row["title"],
        "mentor" => $row["mentor"],
        "status" => $row["status"]
    ];
}



/* ---------------- QUICK ACTIONS ---------------- */

$quickActions = [
    "MENTORS",
    "SESSIONS",
    "COMMUNITY"
];

/* ---------------- TOP MENTORS ---------------- */

$mentors = [];
$mentorQuery = $conn->query(
    "SELECT id, full_name
     FROM users
     WHERE role = 'mentor'
     ORDER BY id DESC
     LIMIT 5"
);

while ($row = $mentorQuery->fetch_assoc()) {
    $mentors[] = [
        "mentor_id" => $row["id"],
        "name" => $row["full_name"]
    ];
}

/* ---------------- COMMUNITY HIGHLIGHT ---------------- */

$communityPost = null;
$postQuery = $conn->query(
    "SELECT p.id, p.title, u.full_name
     FROM community_posts p
     JOIN users u ON u.id = p.user_id
     ORDER BY p.created_at DESC
     LIMIT 1"
);

if ($postQuery->num_rows > 0) {
    $post = $postQuery->fetch_assoc();
    $communityPost = [
        "post_id" => $post["id"],
        "title" => $post["title"],
        "author" => $post["full_name"]
    ];
}

/* ---------------- FINAL RESPONSE ---------------- */

sendResponse(true, "Home dashboard loaded", [
    "user" => [
        "id" => $user["id"],
        "name" => $user["full_name"],
        "role" => $user["role"]
    ],
    "hero" => $hero,
    "available_sessions" => $availableSessions,   // ✅ ADD THIS
    "quick_actions" => $quickActions,
    "top_mentors" => $mentors,
    "community_highlight" => $communityPost
]);

