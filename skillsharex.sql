-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 09:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillsharex`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rating` decimal(2,1) DEFAULT 4.0,
  `rating_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `image_path`, `created_at`, `rating`, `rating_count`, `is_featured`) VALUES
(1, 'UI/UX Design', 'Learn user research, wireframing, prototyping, and usability testing to design intuitive digital experiences.', 'uploads/courses/uiux.png', '2025-12-31 10:30:34', 4.3, 120, 0),
(2, 'Android Development', 'Build modern Android apps using Kotlin, Jetpack Compose, and REST APIs with real-world projects.', 'uploads/courses/android.png', '2025-12-31 10:30:34', 4.6, 200, 0),
(3, 'Java Programming', 'Master core Java concepts including OOP, collections, multithreading, and backend fundamentals.', 'uploads/courses/java.png', '2025-12-31 10:30:34', 4.8, 300, 0),
(4, 'Graphic Design', 'Create visually appealing designs using color theory, typography, and layout principles.', 'uploads/courses/graphic_design.png', '2025-12-31 10:30:34', 4.0, 0, 0),
(5, 'Photoshop', 'Edit photos, design posters, and create social media creatives using Adobe Photoshop tools.', 'uploads/courses/photoshop.png', '2025-12-31 10:30:34', 4.0, 0, 0),
(6, 'Web Development', 'Learn HTML, CSS, JavaScript, and backend integration to build responsive websites.', 'uploads/courses/web.png', '2025-12-31 10:30:34', 4.0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) DEFAULT NULL,
  `post_type` varchar(50) DEFAULT 'Question',
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `skill_id`, `post_type`, `title`, `description`, `created_at`) VALUES
(1, 1, 9, 'Question', 'How do I start a career in UI/UX?', 'I am confused between UI design and UX research. Can someone guide me?', '2026-01-02 08:55:11'),
(2, 2, 7, 'Discussion', 'Best practices for Android Jetpack Compose', 'What are the must-follow architectural practices in modern Android apps?', '2026-01-02 08:55:11'),
(3, 4, 3, 'Question', 'Which tools are best for graphic designers?', 'Should beginners start with Photoshop or Illustrator?', '2026-01-02 08:55:11'),
(4, 5, 1, 'Discussion', 'Frontend vs Full Stack', 'Is it better to master frontend first or learn backend alongside?', '2026-01-02 08:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `mentor_id` int(11) NOT NULL,
  `learner_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `status` enum('pending','active','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(7, 'Android'),
(2, 'Data Science'),
(4, 'Digital Marketing'),
(3, 'Graphic Design'),
(8, 'IOS Development'),
(5, 'Photography'),
(6, 'Public Speaking'),
(9, 'UI/UX Design'),
(1, 'Web Development');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('learner','mentor') DEFAULT 'learner',
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'offline',
  `rating` decimal(2,1) DEFAULT 4.0,
  `rating_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role`, `profile_image`, `status`, `rating`, `rating_count`) VALUES
(1, 'Jana', 'janagan1808@gmail.com', '$2y$10$wBRdWG5t.3oi1dlh.CO.uuP7Ez71.mQgNZqf5RmKa81Cy/QDWuk1u', '2025-12-24 15:06:34', 'mentor', 'uploads/profile/jana.png', 'online', 4.8, 120),
(2, 'Gowtham', 'gowtham12@gmail.com', '$2y$10$zyvy15rm6dyDPFBnNGSR8uu2lS5cICyUEEBL5REmFMMoadcXXuQAO', '2025-12-24 15:14:04', 'mentor', 'uploads/profile/gowtham.png', 'offline', 4.5, 90),
(4, 'sibi', 'sibi@gmail.com', '$2y$10$tTF.SVGh20w957OEAdM/murxmO82i594/EFZnDiu2M13V4AOiB3rm', '2025-12-25 13:53:02', 'mentor', 'uploads/profile/sibi.png', 'online', 4.2, 60),
(5, 'Ashwin', 'ash@gmail.com', '$2y$10$36Lp6TREPXdO7TdC7CQJpuZF/VtcdQJuxY5h6PgCTubgR/1qoM8m2', '2025-12-27 04:09:50', 'mentor', 'uploads/profile/ashwin.png', 'offline', 4.0, 0),
(8, 'saranraj ', 'sarankarthick2011@gmail.com', '$2y$10$PDV2BlagOIjKXNKR9xv/OOIJk.6OaAcZYxekLbkHC2Cjr4gUpeWh6', '2025-12-31 06:28:52', 'mentor', 'uploads/profile/saranraj.png', 'online', 4.7, 150),
(9, 'kaviya', 'kaviya@gmail.com', '$2y$10$EI7kPaw9FqKw3xGpWH81Au.g7Oui7TG0ty2FmkTmnd6wNnWLEtLCC', '2025-12-31 06:34:29', 'mentor', 'uploads/profile/kaviya.png', 'offline', 4.1, 40);

-- --------------------------------------------------------

--
-- Table structure for table `user_courses`
--

CREATE TABLE `user_courses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_courses`
--

INSERT INTO `user_courses` (`id`, `user_id`, `course_id`) VALUES
(22, 1, 1),
(23, 1, 2),
(24, 5, 3),
(25, 5, 5),
(26, 8, 2),
(27, 8, 3),
(28, 8, 4),
(29, 9, 1),
(30, 9, 4),
(31, 2, 3),
(32, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `type` enum('learner','mentor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`, `type`) VALUES
(11, 1, 9, 'mentor'),
(12, 2, 7, 'mentor'),
(13, 4, 3, 'mentor'),
(14, 5, 1, 'mentor'),
(15, 8, 7, 'mentor'),
(16, 9, 1, 'mentor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mentor_id` (`mentor_id`),
  ADD KEY `learner_id` (`learner_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_courses`
--
ALTER TABLE `user_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_courses`
--
ALTER TABLE `user_courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`learner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_courses`
--
ALTER TABLE `user_courses`
  ADD CONSTRAINT `user_courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
