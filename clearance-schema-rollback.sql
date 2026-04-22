-- ============================================
-- ENHANCED CLEARANCE FEATURES - SCHEMA ROLLBACK
-- Task 1: Database schema rollback script
-- ============================================
-- This script safely reverts the clearance schema migration
-- Use this if you need to undo the migration changes
-- ============================================
-- WARNING: This will remove the new columns and their data!
-- Make sure to backup your database before running this script.
-- ============================================

USE dms;

-- Remove indexes first
ALTER TABLE `account_studentprofile`
DROP INDEX `unique_email`,
DROP INDEX `idx_clearance_generated`,
DROP INDEX `idx_registration_status`;

-- Remove columns from account_studentprofile table
ALTER TABLE `account_studentprofile`
DROP COLUMN `created_on`,
DROP COLUMN `clearance_reference`,
DROP COLUMN `clearance_date`,
DROP COLUMN `clearance_generated`,
DROP COLUMN `registration_status`,
DROP COLUMN `email`;

-- Display rollback results
SELECT '✓ Rollback completed successfully!' as 'Status';
SELECT 'Removed columns from account_studentprofile:' as '';
SELECT 'email, registration_status, clearance_generated, clearance_date, clearance_reference, created_on' as 'Columns';
SELECT 'Removed indexes:' as '';
SELECT 'unique_email, idx_clearance_generated, idx_registration_status' as 'Indexes';

-- Verify the changes
DESCRIBE `account_studentprofile`;
