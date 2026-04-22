-- ============================================
-- ENHANCED CLEARANCE FEATURES - DATABASE UPDATES
-- ============================================

USE dms;

-- Add email and registration fields to student profile
ALTER TABLE `account_studentprofile` 
ADD COLUMN `email` VARCHAR(100) NULL AFTER `username`,
ADD COLUMN `student_id` VARCHAR(50) NULL AFTER `email`,
ADD COLUMN `registration_status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER `session_id`,
ADD COLUMN `registered_date` DATETIME NULL AFTER `registration_status`;

-- Add clearance tracking fields
ALTER TABLE `account_studentprofile`
ADD COLUMN `clearance_status` ENUM('pending', 'cleared') DEFAULT 'pending' AFTER `registered_date`,
ADD COLUMN `clearance_date` DATETIME NULL AFTER `clearance_status`,
ADD COLUMN `clearance_reference` VARCHAR(50) NULL AFTER `clearance_date`;

-- Add payment status tracking
ALTER TABLE `payment`
ADD COLUMN `payment_method` VARCHAR(50) DEFAULT 'Cash' AFTER `datePaid`,
ADD COLUMN `receipt_number` VARCHAR(50) NULL AFTER `payment_method`,
ADD COLUMN `verified_by` INT(11) NULL AFTER `receipt_number`,
ADD COLUMN `verified_date` DATETIME NULL AFTER `verified_by`;

-- Create payment summary view
CREATE OR REPLACE VIEW `payment_summary` AS
SELECT 
    s.id as student_id,
    s.fullname,
    s.username,
    s.email,
    d.dept_name as program,
    f.faculty_name as college,
    sess.session_name,
    sf.amount as total_fees,
    COALESCE(SUM(p.amount), 0) as total_paid,
    (sf.amount - COALESCE(SUM(p.amount), 0)) as balance,
    CASE 
        WHEN COALESCE(SUM(p.amount), 0) >= sf.amount THEN 'Fully Paid'
        WHEN COALESCE(SUM(p.amount), 0) > 0 THEN 'Partial'
        ELSE 'Not Paid'
    END as payment_status,
    s.clearance_status,
    s.clearance_reference
FROM account_studentprofile s
LEFT JOIN system_departmentdata d ON s.dept_name_id = d.id
LEFT JOIN system_facultydata f ON d.fid_id = f.id
LEFT JOIN system_sessiondata sess ON s.session_id = sess.id
LEFT JOIN bursary_schoolfees sf ON sf.did_id = s.dept_name_id AND sf.sid_id = s.session_id
LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = sf.id
GROUP BY s.id, s.fullname, s.username, s.email, d.dept_name, f.faculty_name, sess.session_name, sf.amount, s.clearance_status, s.clearance_reference;

-- Display results
SELECT '✓ Database updated successfully!' as 'Status';
SELECT 'New columns added to account_studentprofile:' as '';
SELECT 'email, student_id, registration_status, registered_date, clearance_status, clearance_date, clearance_reference' as 'Columns';

SELECT 'New columns added to payment:' as '';
SELECT 'payment_method, receipt_number, verified_by, verified_date' as 'Columns';

SELECT 'Payment summary view created for easy reporting' as '';
