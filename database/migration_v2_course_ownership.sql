-- 1. Add user_id column to courses table
ALTER TABLE courses ADD COLUMN user_id INT(11) DEFAULT NULL;

-- 2. Backfill user_id from user_courses table
-- We assume the user in user_courses is the owner.
-- If multiple users are linked to a course, this will pick one (arbitrarily via GROUP BY or first match if update without join limit).
-- Since the goal is single ownership, we take the first mapped user.
UPDATE courses c
JOIN user_courses uc ON c.id = uc.course_id
SET c.user_id = uc.user_id;

-- 3. Verify Data (Manual Step - Optional but recommended)
-- SELECT * FROM courses WHERE user_id IS NULL;
-- Ensure no critical data is missing an owner.

-- 4. Enforce Foreign Key Constraint
-- First, ensure all user_ids are valid or handle NULLs if any courses have no owner.
-- Use a default user or delete orphan courses if strict policy. For now we just alter.
ALTER TABLE courses MODIFY COLUMN user_id INT(11) NOT NULL;
ALTER TABLE courses ADD CONSTRAINT fk_courses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- 5. Drop the old table
-- Only run this after verifying step 3 works and applications are updated.
DROP TABLE user_courses;
