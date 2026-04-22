-- ============================================
-- CPSU VICTORIAS - COURSE UPDATE SCRIPT
-- ============================================
-- Run this script to update existing database
-- ============================================

USE dms;

-- First, expand the column size to fit longer names
ALTER TABLE `system_facultydata` MODIFY `faculty_name` VARCHAR(50) NOT NULL;
ALTER TABLE `system_departmentdata` MODIFY `dept_name` VARCHAR(100) NOT NULL;

-- Update Faculty/College Names
UPDATE `system_facultydata` SET `faculty_name` = 'College of Education' WHERE `id` = 1;
UPDATE `system_facultydata` SET `faculty_name` = 'College of Agriculture' WHERE `id` = 2;
UPDATE `system_facultydata` SET `faculty_name` = 'College of Technology' WHERE `id` = 3;

-- Add new College if not exists
INSERT INTO `system_facultydata` (`id`, `faculty_name`, `created_on`) 
VALUES (4, 'College of Hospitality Management', NOW())
ON DUPLICATE KEY UPDATE `faculty_name` = 'College of Hospitality Management';

-- Update Department/Program Names
UPDATE `system_departmentdata` SET `dept_name` = 'Bachelor in Elementary Education', `fid_id` = 1 WHERE `id` = 1;
UPDATE `system_departmentdata` SET `dept_name` = 'BS in Agriculture', `fid_id` = 2 WHERE `id` = 2;
UPDATE `system_departmentdata` SET `dept_name` = 'BS in Information Technology', `fid_id` = 3 WHERE `id` = 3;
UPDATE `system_departmentdata` SET `dept_name` = 'BS in Hospitality Management', `fid_id` = 4 WHERE `id` = 4;

-- Display updated data
SELECT 'Updated Faculties/Colleges:' as '';
SELECT * FROM `system_facultydata`;

SELECT 'Updated Departments/Programs:' as '';
SELECT d.id, d.dept_name as 'Program', f.faculty_name as 'College' 
FROM `system_departmentdata` d 
LEFT JOIN `system_facultydata` f ON d.fid_id = f.id;

SELECT '✓ Course update completed successfully!' as 'Status';
