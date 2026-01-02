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
SET FOREIGN_KEY_CHECKS = 1;

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
