-- ============================================
-- ENHANCED CLEARANCE FEATURES - SCHEMA MIGRATION
-- Task 1: Database schema updates and migration
-- ============================================
-- This migration adds columns needed for:
-- - Email storage for student registration
-- - Registration status tracking
-- - Clearance generation and tracking
-- - Account creation timestamps
-- ============================================

USE dms;

-- Add new columns to account_studentprofile table
ALTER TABLE `account_studentprofile` 
ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `password`,
ADD COLUMN `registration_status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER `session_id`,
ADD COLUMN `clearance_generated` TINYINT(1) DEFAULT 0 AFTER `registration_status`,
ADD COLUMN `clearance_date` VARCHAR(30) DEFAULT NULL AFTER `clearance_generated`,
ADD COLUMN `clearance_reference` VARCHAR(20) DEFAULT NULL AFTER `clearance_date`,
ADD COLUMN `created_on` VARCHAR(30) DEFAULT NULL AFTER `clearance_reference`;

-- Add indexes for performance optimization
ALTER TABLE `account_studentprofile`
ADD UNIQUE KEY `unique_email` (`email`),
ADD INDEX `idx_clearance_generated` (`clearance_generated`),
ADD INDEX `idx_registration_status` (`registration_status`);

-- Display migration results
SELECT '✓ Migration completed successfully!' as 'Status';
SELECT 'New columns added to account_studentprofile:' as '';
SELECT 'email, registration_status, clearance_generated, clearance_date, clearance_reference, created_on' as 'Columns';
SELECT 'Indexes created:' as '';
SELECT 'unique_email (UNIQUE), idx_clearance_generated, idx_registration_status' as 'Indexes';

-- Verify the changes
DESCRIBE `account_studentprofile`;
