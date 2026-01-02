-- WARNING: This will RESET your database data!
CREATE DATABASE IF NOT EXISTS skillsharex;
USE skillsharex;

-- Disable Foreign Key Checks to allow dropping tables
SET FOREIGN_KEY_CHECKS = 0;
-- DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS skills;
DROP TABLE IF EXISTS user_skills;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS posts;
-- DROP TABLE IF EXISTS courses;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `image_path`, `created_at`) VALUES
(1, 'UI/UX Design', 'Learn user research, wireframing, prototyping, and usability testing to design intuitive digital experiences.', 'uploads/courses/uiux.png', '2025-12-31 10:30:34'),
(2, 'Android Development', 'Build modern Android apps using Kotlin, Jetpack Compose, and REST APIs with real-world projects.', 'uploads/courses/android.png', '2025-12-31 10:30:34'),
(3, 'Java Programming', 'Master core Java concepts including OOP, collections, multithreading, and backend fundamentals.', 'uploads/courses/java.png', '2025-12-31 10:30:34'),
(4, 'Graphic Design', 'Create visually appealing designs using color theory, typography, and layout principles.', 'uploads/courses/graphic_design.png', '2025-12-31 10:30:34'),
(5, 'Photoshop', 'Edit photos, design posters, and create social media creatives using Adobe Photoshop tools.', 'uploads/courses/photoshop.png', '2025-12-31 10:30:34'),
(6, 'Web Development', 'Learn HTML, CSS, JavaScript, and backend integration to build responsive websites.', 'uploads/courses/web.png', '2025-12-31 10:30:34');


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('learner','mentor') DEFAULT 'learner',
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'offline'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role`, `profile_image`, `status`) VALUES
(1, 'Jana', 'janagan1808@gmail.com', '$2y$10$wBRdWG5t.3oi1dlh.CO.uuP7Ez71.mQgNZqf5RmKa81Cy/QDWuk1u', '2025-12-24 15:06:34', 'mentor', NULL, 'online'),
(2, 'Gowtham', 'gowtham12@gmail.com', '$2y$10$zyvy15rm6dyDPFBnNGSR8uu2lS5cICyUEEBL5REmFMMoadcXXuQAO', '2025-12-24 15:14:04', 'mentor', NULL, 'offline'),
(4, 'sibi', 'sibi@gmail.com', '$2y$10$tTF.SVGh20w957OEAdM/murxmO82i594/EFZnDiu2M13V4AOiB3rm', '2025-12-25 13:53:02', 'mentor', NULL, 'online'),
(5, 'Ashwin', 'ash@gmail.com', '$2y$10$36Lp6TREPXdO7TdC7CQJpuZF/VtcdQJuxY5h6PgCTubgR/1qoM8m2', '2025-12-27 04:09:50', 'mentor', NULL, 'offline'),
(8, 'saranraj ', 'sarankarthick2011@gmail.com', '$2y$10$PDV2BlagOIjKXNKR9xv/OOIJk.6OaAcZYxekLbkHC2Cjr4gUpeWh6', '2025-12-31 06:28:52', 'mentor', NULL, 'online'),
(9, 'kaviya', 'kaviya@gmail.com', '$2y$10$EI7kPaw9FqKw3xGpWH81Au.g7Oui7TG0ty2FmkTmnd6wNnWLEtLCC', '2025-12-31 06:34:29', 'mentor', NULL, 'offline');



-- Users table
-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     full_name VARCHAR(100) NOT NULL,
--     email VARCHAR(100) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     role VARCHAR(100) DEFAULT NULL, -- E.g. "Mentor . UI/UX"
--     bio TEXT DEFAULT NULL,
--     avatar VARCHAR(255) DEFAULT 'default_avatar.png',
--     status ENUM('online', 'offline') DEFAULT 'offline',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- Skills table
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- User Skills (Many-to-Many)
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    type ENUM('learner', 'mentor') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Community Posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NULL, -- Optional Category (e.g. "Android")
    post_type VARCHAR(50) DEFAULT 'Question', -- 'Question', 'Discussion', etc.
    title VARCHAR(255) DEFAULT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE SET NULL
);

-- Messages (Chat)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sessions (Live Meetings)
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mentor_id INT NOT NULL,
    learner_id INT NOT NULL,
    skill_id INT NOT NULL,
    status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mentor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (learner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Initial Skills SEED
INSERT IGNORE INTO skills (name) VALUES 
('Web Development'), 
('Data Science'), 
('Graphic Design'), 
('Digital Marketing'), 
('Photography'),
('Public Speaking'),
('Android'),
('IOS Development'),
('UI/UX Design');
