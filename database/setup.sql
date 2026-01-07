-- database/setup.sql (or run in phpMyAdmin)

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `profile_image` VARCHAR(255) DEFAULT 'default.png',
  `role` ENUM('mentor', 'learner') NOT NULL DEFAULT 'learner',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `skill` VARCHAR(100) NOT NULL,
  `mentor_id` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `status` ENUM('LIVE', 'UPCOMING', 'COMPLETED') NOT NULL DEFAULT 'UPCOMING',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`mentor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Optional: Insert dummy data for testing
-- INSERT INTO `users` (`full_name`, `phone`, `profile_image`, `role`) VALUES
-- ('Jana', '919043045940', 'uploads/profile/jana.png', 'mentor');

-- INSERT INTO `sessions` (`title`, `description`, `skill`, `mentor_id`, `date`, `start_time`, `end_time`, `status`) VALUES
-- ('Android Development', 'Learn Android basics', 'Android', 1, '2026-01-10', '18:00:00', '19:00:00', 'LIVE');
