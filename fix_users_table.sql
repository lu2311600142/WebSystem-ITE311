-- SQL script to fix the users table structure
-- Run this in phpMyAdmin SQL tab if you have both 'user' and 'users' tables

-- Step 1: If 'users' table doesn't exist, rename 'user' to 'users'
-- RENAME TABLE `user` TO `users`;

-- Step 2: Add missing columns to users table (if they don't exist)
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `username` VARCHAR(100) NULL AFTER `id`,
ADD COLUMN IF NOT EXISTS `name` VARCHAR(100) NULL AFTER `username`,
ADD COLUMN IF NOT EXISTS `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`;

-- Step 3: Update role enum to include student and teacher
-- Note: This might fail if you have existing 'user' role values
-- You may need to update those first: UPDATE users SET role = 'student' WHERE role = 'user';
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('admin', 'student', 'teacher') DEFAULT 'student';

-- Step 4: If you have a 'user' table that's empty or duplicate, you can drop it
-- DROP TABLE IF EXISTS `user`;

