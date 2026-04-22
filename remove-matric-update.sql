-- ============================================
-- REMOVE MATRIC NUMBER - UPDATE SCRIPT
-- ============================================
-- This script changes matric to username
-- ============================================

USE dms;

-- Rename the matric column to username and expand size
ALTER TABLE `account_studentprofile` 
CHANGE COLUMN `matric` `username` VARCHAR(50) NOT NULL;

-- Update existing matric numbers to usernames (lowercase first names)
-- You may want to customize these based on your actual data
UPDATE `account_studentprofile` SET `username` = 'ayinde' WHERE `id` = 1;
UPDATE `account_studentprofile` SET `username` = 'odewale' WHERE `id` = 2;
UPDATE `account_studentprofile` SET `username` = 'ojewole' WHERE `id` = 3;

-- Display updated data
SELECT 'Updated Student Profiles:' as '';
SELECT id, fullname, username, dept_name_id, session_id FROM `account_studentprofile`;

SELECT '✓ Matric number removed successfully! Students now use username.' as 'Status';
