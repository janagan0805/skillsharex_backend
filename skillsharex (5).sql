-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 10:00 AM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `user_id`, `title`, `content`, `topic`, `created_at`, `image_path`) VALUES
(6, 1, 'Building Scalable Web Apps with Laravel and React in 2026', 'This talk walks through designing a modern, scalable web application using Laravel for the backend and React on the frontend. We will cover API design, authentication, state management, and deployment patterns that work well for small teams. Developers will leave with a practical blueprint they can adapt to their own projects, including common pitfalls and performance tips for production-ready apps.', 'Web Development', '2026-03-29 16:52:12', NULL),
(7, 8, 'From Code to Content: Practical Generative AI for Everyday Developers', 'Generative AI is no longer just a research topic; it’s a daily productivity tool for developers. This session shows how to use large language models to speed up coding, documentation, testing, and even product copy, with live examples and clear do’s and don’ts. We will also touch on privacy, copyright concerns, and how to safely integrate AI into existing workflows.', 'Career Guidance', '2026-03-29 17:03:39', NULL),
(8, 1, 'Microservices, Containers, and Cloud: When You Actually Need Them', 'Everyone talks about microservices, but not every project needs them. This talk explains when a monolith is enough, when to break into services, and how containers and cloud platforms fit into the picture. Attendees will see reference architectures, deployment pipelines, and practical guidance on avoiding over-engineering while still preparing for growth.', 'General Discussion', '2026-03-29 17:08:56', NULL),
(9, 1, 'it will work to guide your self', 'this was more useful for the people make the guidance to work', 'Career Guidance', '2026-04-01 08:02:30', NULL);

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
(1, 'UI/UX Design', 'Learn user research, wireframing, prototyping, and usability testing to design intuitive digital experiences.', 'uploads/courses/uiux.png', '2025-12-31 10:30:34', 4.3, 120, 0, 1, 'active'),
(2, 'Android Development', 'Build modern Android apps using Kotlin, Jetpack Compose, and REST APIs with real-world projects.', 'uploads/courses/android.png', '2025-12-31 10:30:34', 4.6, 200, 0, 1, 'inactive'),
(4, 'Graphic Design', 'Create visually appealing designs using color theory, typography, and layout principles.', 'uploads/courses/graphic_design.png', '2025-12-31 10:30:34', 4.0, 0, 0, 8, 'active'),
(7, 'Android app development ', 'Use to develop the android apps ', 'uploads/courses/course_696b9e86a797e9.11915114.jpg', '2026-01-17 14:36:54', 4.0, 0, 0, 1, 'inactive'),
(8, 'dummy 2', 'testing', 'uploads/courses/course_696ba0284a9b06.77478456.jpg', '2026-01-17 14:43:52', 4.0, 0, 0, 8, 'inactive'),
(9, 'dummy 3', 'testing', 'uploads/courses/course_696ba060804f49.60601661.jpg', '2026-01-17 14:44:48', 4.0, 0, 0, 8, 'inactive'),
(10, 'skills sharing', 'this is a skill sharing description', 'uploads/courses/course_696ba402cd2591.26783183.jpg', '2026-01-17 15:00:18', 4.0, 0, 0, 8, 'inactive');

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
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(2, 6, 1, 'anyone and share your opnion', '2026-03-29 16:52:53'),
(3, 6, 8, 'yeah I have a question, how could you think like that?', '2026-03-29 17:02:32'),
(4, 6, 8, 'and tell me how many technologies you known?', '2026-03-29 17:02:55'),
(5, 7, 8, 'Try to ask about it!', '2026-03-29 17:04:01'),
(6, 7, 1, 'can you tell me about what will be the future technologies in 2050?', '2026-03-29 17:06:43'),
(7, 6, 1, 'Yeah, I know php, python, react, node, aws, etc.,', '2026-03-29 17:07:21'),
(8, 8, 1, 'good', '2026-04-01 08:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(4, 6, 1, '2026-03-29 16:52:23'),
(5, 6, 8, '2026-03-29 17:01:57'),
(6, 7, 8, '2026-03-29 17:03:42'),
(8, 7, 1, '2026-03-29 17:06:12'),
(9, 8, 1, '2026-03-29 17:08:58'),
(10, 9, 1, '2026-04-01 08:02:35');

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
(1, 'Android Basics with Compose', 'Learn Android fundamentals and Jetpack Compose basics', 'Android Development', 1, '2026-01-15', '10:00:00', '11:30:00', 'LIVE'),
(2, 'Full Stack Web Roadmap', 'Guidance on becoming a full stack web developer', 'Web Development', 2, '2026-01-16', '14:00:00', '15:00:00', 'UPCOMING'),
(3, 'UI/UX Design Fundamentals', 'Introduction to UI/UX principles and tools', 'UI/UX', 4, '2026-01-17', '16:00:00', '17:30:00', 'UPCOMING'),
(4, 'Career Guidance for Freshers', 'Career planning and interview preparation tips', 'Career Guidance', 5, '2026-01-18', '11:00:00', '12:00:00', 'UPCOMING'),
(5, 'Advanced Android Architecture', 'MVVM, Clean Architecture, and best practices', 'Android Development', 8, '2026-01-19', '18:00:00', '19:30:00', 'COMPLETED'),
(6, 'Resume Building & Mock Interview', 'Improve resume quality and attend mock interviews', 'Career Guidance', 11, '2026-01-20', '09:30:00', '10:30:00', 'UPCOMING');

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
(16, 'bhjmn'),
(2, 'Data Science'),
(4, 'Digital Marketing'),
(15, 'ghjj'),
(3, 'Graphic Design'),
(21, 'Graphic designer'),
(17, 'hkigci'),
(8, 'IOS Development'),
(18, 'jgxgk'),
(12, 'json'),
(20, 'kycco'),
(19, 'kyxkgx'),
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
(1, 'Jana', 'janagan1808@gmail.com', '$2y$10$wBRdWG5t.3oi1dlh.CO.uuP7Ez71.mQgNZqf5RmKa81Cy/QDWuk1u', '2025-12-24 15:06:34', 'mentor', 'uploads/profile/Jana_1_1774849203.png', 'online', 4.5, 120, '9043045940'),
(2, 'Gowtham', 'gowtham12@gmail.com', '$2y$10$zyvy15rm6dyDPFBnNGSR8uu2lS5cICyUEEBL5REmFMMoadcXXuQAO', '2025-12-24 15:14:04', 'mentor', 'uploads/profile/Gowtham_2.png', 'online', 4.6, 90, ''),
(4, 'Dilip', 'dilip@gmail.com', '$2y$10$tTF.SVGh20w957OEAdM/murxmO82i594/EFZnDiu2M13V4AOiB3rm', '2025-12-25 13:53:02', 'mentor', 'uploads/profile/sibi.png', 'online', 4.2, 60, ''),
(8, 'Saran raj B', 'sarankarthick2011@gmail.com', '$2y$10$jMmLwczn2QpnBQN2z7XlVuwzKvwIxcCMA9Lj.6VOevNCXdbwCQg2a', '2025-12-31 06:28:52', 'mentor', 'uploads/profile/saran_8_1768896779.png', 'offline', 4.7, 150, '7904780297'),
(14, 'karthik s', 'karthik@gmail.com', '$2y$10$A/A4LZJEnTVj0/.Tgel.GeRKTBEu7At7IRSwGv25i7NpRoskcjw42', '2026-01-09 02:37:22', 'mentor', 'uploads/profile/karthiks_14.png', 'offline', 4.0, 85, '8794652398');

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
(12, 2, 7, 'mentor'),
(13, 4, 3, 'mentor'),
(57, 14, 10, 'mentor'),
(58, 14, 11, 'mentor'),
(59, 14, 13, 'mentor'),
(76, 8, 7, 'mentor'),
(77, 8, 10, 'mentor'),
(78, 8, 12, 'mentor'),
(94, 1, 9, 'mentor'),
(95, 1, 21, 'mentor'),
(96, 1, 7, 'mentor');

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

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
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
