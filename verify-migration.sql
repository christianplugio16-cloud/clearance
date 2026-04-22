-- ============================================
-- MIGRATION VERIFICATION SCRIPT
-- ============================================
-- Run this script after migration to verify all changes
-- were applied correctly
-- ============================================

USE dms;

-- Check if all new columns exist
SELECT 'Checking for new columns...' as 'Step 1';

SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'dms'
    AND TABLE_NAME = 'account_studentprofile'
    AND COLUMN_NAME IN ('email', 'registration_status', 'clearance_generated', 
                        'clearance_date', 'clearance_reference', 'created_on')
ORDER BY ORDINAL_POSITION;

-- Check if indexes were created
SELECT 'Checking for indexes...' as 'Step 2';

SELECT 
    INDEX_NAME,
    COLUMN_NAME,
    NON_UNIQUE,
    INDEX_TYPE
FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_SCHEMA = 'dms'
    AND TABLE_NAME = 'account_studentprofile'
    AND INDEX_NAME IN ('unique_email', 'idx_clearance_generated', 'idx_registration_status')
ORDER BY INDEX_NAME;

-- Count existing student records
SELECT 'Checking existing data integrity...' as 'Step 3';

SELECT 
    COUNT(*) as total_students,
    COUNT(email) as students_with_email,
    COUNT(clearance_generated) as students_with_clearance_flag,
    SUM(clearance_generated) as students_with_clearance
FROM account_studentprofile;

-- Check for any duplicate emails (should be 0 after UNIQUE constraint)
SELECT 'Checking for duplicate emails...' as 'Step 4';

SELECT 
    email,
    COUNT(*) as count
FROM account_studentprofile
WHERE email IS NOT NULL
GROUP BY email
HAVING COUNT(*) > 1;

-- Display full table structure
SELECT 'Full table structure:' as 'Step 5';
DESCRIBE account_studentprofile;

-- Summary
SELECT '============================================' as '';
SELECT 'MIGRATION VERIFICATION COMPLETE' as '';
SELECT '============================================' as '';
SELECT 'If you see all 6 new columns and 3 indexes above,' as 'Result';
SELECT 'the migration was successful!' as '';
