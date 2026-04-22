-- ============================================
-- ADD CPSU VICTORIAS SESSIONS
-- ============================================
-- Adds academic sessions from 2000 to 2026
-- ============================================

USE dms;

-- Clear existing sessions (optional - remove if you want to keep existing data)
-- DELETE FROM `system_sessiondata`;

-- Insert all sessions from CPSU Victorias opening to current
INSERT INTO `system_sessiondata` (`session_name`, `created_on`) VALUES
('2000/2001', NOW()),
('2001/2002', NOW()),
('2002/2003', NOW()),
('2003/2004', NOW()),
('2004/2005', NOW()),
('2005/2006', NOW()),
('2006/2007', NOW()),
('2007/2008', NOW()),
('2008/2009', NOW()),
('2009/2010', NOW()),
('2010/2011', NOW()),
('2011/2012', NOW()),
('2012/2013', NOW()),
('2013/2014', NOW()),
('2014/2015', NOW()),
('2015/2016', NOW()),
('2016/2017', NOW()),
('2017/2018', NOW()),
('2018/2019', NOW()),
('2019/2020', NOW()),
('2020/2021', NOW()),
('2021/2022', NOW()),
('2022/2023', NOW()),
('2023/2024', NOW()),
('2024/2025', NOW()),
('2025/2026', NOW())
ON DUPLICATE KEY UPDATE session_name = session_name;

-- Display all sessions
SELECT 'Academic Sessions Added:' as '';
SELECT * FROM `system_sessiondata` ORDER BY `session_name`;

SELECT '✓ Sessions added successfully!' as 'Status';
SELECT CONCAT('Total Sessions: ', COUNT(*)) as 'Count' FROM `system_sessiondata`;
