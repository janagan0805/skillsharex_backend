-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2026 at 05:00 PM
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
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `content` text NOT NULL,
  `topic` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `title`, `content`, `topic`, `created_at`) VALUES
(1, 1, 'Test Post Title', 'This is a test post content created via PowerShell script.', 'General', '2026-01-07 17:25:35'),
(2, 1, 'hello', 'hi bro', 'Android Development', '2026-01-07 18:21:28'),
(3, 8, 'hi', 'ffghhj', 'Android Development', '2026-01-08 06:52:22');

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
  `is_featured` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `image_path`, `created_at`, `rating`, `rating_count`, `is_featured`, `user_id`, `status`) VALUES
(1, 'UI/UX Design', 'Learn user research, wireframing, prototyping, and usability testing to design intuitive digital experiences.', 'uploads/courses/uiux.png', '2025-12-31 10:30:34', 4.3, 120, 0, 1, 'inactive'),
(2, 'Android Development', 'Build modern Android apps using Kotlin, Jetpack Compose, and REST APIs with real-world projects.', 'uploads/courses/android.png', '2025-12-31 10:30:34', 4.6, 200, 0, 1, 'inactive'),
(3, 'Java Programming', 'Master core Java concepts including OOP, collections, multithreading, and backend fundamentals.', 'uploads/courses/java.png', '2025-12-31 10:30:34', 4.8, 300, 0, 5, 'inactive'),
(4, 'Graphic Design', 'Create visually appealing designs using color theory, typography, and layout principles.', 'uploads/courses/graphic_design.png', '2025-12-31 10:30:34', 4.0, 0, 0, 8, 'inactive'),
(5, 'Photoshop', 'Edit photos, design posters, and create social media creatives using Adobe Photoshop tools.', 'uploads/courses/photoshop.png', '2025-12-31 10:30:34', 4.0, 0, 0, 5, 'inactive'),
(7, 'dummy', 'this is a dummy des', 'uploads/courses/course_696b9e86a797e9.11915114.jpg', '2026-01-17 14:36:54', 4.0, 0, 0, 1, 'active'),
(8, 'dummy 2', 'testing', 'uploads/courses/course_696ba0284a9b06.77478456.jpg', '2026-01-17 14:43:52', 4.0, 0, 0, 8, 'inactive'),
(9, 'dummy 3', 'testing', 'uploads/courses/course_696ba060804f49.60601661.jpg', '2026-01-17 14:44:48', 4.0, 0, 0, 8, 'inactive'),
(10, 'sharing', 'this is a skill sharing description', 'uploads/courses/course_696ba402cd2591.26783183.jpg', '2026-01-17 15:00:18', 4.0, 0, 0, 8, 'active');

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
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `skill` varchar(100) DEFAULT NULL,
  `mentor_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('LIVE','UPCOMING','COMPLETED') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `title`, `description`, `skill`, `mentor_id`, `date`, `start_time`, `end_time`, `status`) VALUES
(1, 'Android Basics with Compose', 'Learn Android fundamentals and Jetpack Compose basics', 'Android Development', 1, '2026-01-15', '10:00:00', '11:30:00', ''),
(2, 'Full Stack Web Roadmap', 'Guidance on becoming a full stack web developer', 'Web Development', 2, '2026-01-16', '14:00:00', '15:00:00', ''),
(3, 'UI/UX Design Fundamentals', 'Introduction to UI/UX principles and tools', 'UI/UX', 4, '2026-01-17', '16:00:00', '17:30:00', ''),
(4, 'Career Guidance for Freshers', 'Career planning and interview preparation tips', 'Career Guidance', 5, '2026-01-18', '11:00:00', '12:00:00', ''),
(5, 'Advanced Android Architecture', 'MVVM, Clean Architecture, and best practices', 'Android Development', 8, '2026-01-19', '18:00:00', '19:30:00', ''),
(6, 'Resume Building & Mock Interview', 'Improve resume quality and attend mock interviews', 'Career Guidance', 11, '2026-01-20', '09:30:00', '10:30:00', '');

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
(12, 'json'),
(13, 'mongodb'),
(11, 'node'),
(14, 'node js'),
(5, 'Photography'),
(6, 'Public Speaking'),
(10, 'react'),
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
  `role` enum('learner','mentor') DEFAULT 'mentor',
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'offline',
  `rating` decimal(2,1) DEFAULT 4.0,
  `rating_count` int(11) DEFAULT 100,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role`, `profile_image`, `status`, `rating`, `rating_count`, `phone`) VALUES
(1, 'Jana', 'janagan1808@gmail.com', '$2y$10$wBRdWG5t.3oi1dlh.CO.uuP7Ez71.mQgNZqf5RmKa81Cy/QDWuk1u', '2025-12-24 15:06:34', 'mentor', 'uploads/profile/Jana_1.png', 'online', 4.5, 120, '9043045940'),
(2, 'Gowtham', 'gowtham12@gmail.com', '$2y$10$zyvy15rm6dyDPFBnNGSR8uu2lS5cICyUEEBL5REmFMMoadcXXuQAO', '2025-12-24 15:14:04', 'mentor', 'uploads/profile/Gowtham_2.png', 'online', 4.6, 90, ''),
(4, 'sibi', 'sibi@gmail.com', '$2y$10$tTF.SVGh20w957OEAdM/murxmO82i594/EFZnDiu2M13V4AOiB3rm', '2025-12-25 13:53:02', 'mentor', 'uploads/profile/sibi.png', 'online', 4.2, 60, ''),
(5, 'Ashwin', 'ash@gmail.com', '$2y$10$36Lp6TREPXdO7TdC7CQJpuZF/VtcdQJuxY5h6PgCTubgR/1qoM8m2', '2025-12-27 04:09:50', 'mentor', 'uploads/profile/ashwin.png', 'offline', 4.0, 90, '6380067133'),
(8, 'saran', 'sarankarthick2011@gmail.com', '$2y$10$PDV2BlagOIjKXNKR9xv/OOIJk.6OaAcZYxekLbkHC2Cjr4gUpeWh6', '2025-12-31 06:28:52', 'mentor', 'uploads/profile/saran_8.png', 'online', 4.7, 150, '7904780297'),
(11, 'Dhana', 'asskrvsdhana@gmail.com', '$2y$10$Bd1chlJRFYG9YvMOwMWiu.qX0H2F6up9rWIRKccw/Zl3d.GFCb3Zi', '2026-01-07 04:32:05', 'mentor', NULL, 'online', 4.0, 100, '9342244899'),
(13, 'Ashok B', 'ashok@gmail.com', '$2y$10$TbUQr1atOoceSFw3gZNEGuPGmtLsKvgROEteMwhGiSb0cfEfSQr4q', '2026-01-09 01:57:03', 'mentor', 'uploads/profile/AshokB_13.png', 'offline', 4.8, 80, '8778910594'),
(14, 'karthik s', 'karthik@gmail.com', '$2y$10$A/A4LZJEnTVj0/.Tgel.GeRKTBEu7At7IRSwGv25i7NpRoskcjw42', '2026-01-09 02:37:22', 'mentor', 'uploads/profile/karthiks_14.png', 'offline', 4.0, 85, '8794652398'),
(15, 'dilip', 'dilip@gmail.com', '$2y$10$20.cGkPX9J3EoFlnpMa57.pjlruwQJWN3erLEJBfcXpyXE5pChOKC', '2026-01-09 03:06:11', 'mentor', 'uploads/profile/dilip_15.png', 'online', 4.8, 100, '7985469785');

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
(49, 8, 7, 'mentor'),
(50, 8, 10, 'mentor'),
(51, 8, 12, 'mentor'),
(57, 14, 10, 'mentor'),
(58, 14, 11, 'mentor'),
(59, 14, 13, 'mentor'),
(60, 15, 14, 'mentor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_courses_user` (`user_id`);

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
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
