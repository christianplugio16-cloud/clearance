# Student Login Troubleshooting Guide

## Quick Diagnosis

**Run this diagnostic script first:**
```
http://your-domain/diagnose-login-issue.php
```

This will check:
- Database connection
- Table structure
- Student accounts
- PHP session configuration
- Common issues

---

## Common Issues and Solutions

### Issue 1: "No user is registered using this credentials"

**Possible Causes:**
1. Wrong username or password
2. No student accounts in database
3. Password doesn't match (MD5 hash issue)

**Solutions:**

#### Solution A: Check if student accounts exist
```sql
SELECT id, username, fullname FROM account_studentprofile LIMIT 10;
```

If no results, you need to create a student account.

#### Solution B: Reset password for existing account
```sql
-- This sets password to "password123"
UPDATE account_studentprofile 
SET password = '482c811da5d5b4bc6d497ffa98491e38' 
WHERE username = 'your_username';
```

#### Solution C: Create a test student account
```sql
INSERT INTO account_studentprofile 
(fullname, username, password, dept_name_id, session_id) 
VALUES 
('Test Student', 'teststudent', '482c811da5d5b4bc6d497ffa98491e38', 1, 1);
```

Login with:
- **Username:** teststudent
- **Password:** password123

---

### Issue 2: Login button does nothing / No response

**Possible Causes:**
1. JavaScript error
2. jQuery not loaded
3. reducer.php not accessible
4. AJAX request failing

**Solutions:**

#### Solution A: Check browser console
1. Press F12 to open Developer Tools
2. Go to Console tab
3. Look for JavaScript errors
4. Common errors:
   - "jQuery is not defined" → jQuery not loaded
   - "404 Not Found" → reducer.php path wrong
   - "500 Internal Server Error" → PHP error in reducer.php

#### Solution B: Verify jQuery is loaded
Open browser console and type:
```javascript
jQuery.fn.jquery
```
If it returns a version number (e.g., "3.2.1"), jQuery is loaded.

#### Solution C: Check reducer.php path
Make sure `student/reducer.php` exists and is accessible.

#### Solution D: Test reducer.php directly
Create a test file `test-login.php`:
```php
<?php
$_POST['action'] = 'login';
$_POST['username'] = 'teststudent';
$_POST['password'] = 'password123';

include 'student/reducer.php';
?>
```

---

### Issue 3: Database connection error

**Possible Causes:**
1. MySQL not running
2. Wrong database credentials
3. Database doesn't exist
4. Port mismatch

**Solutions:**

#### Solution A: Check MySQL is running
**Windows:**
```cmd
net start MySQL
```

**Linux/Mac:**
```bash
sudo service mysql start
```

#### Solution B: Verify database exists
```sql
SHOW DATABASES LIKE 'dms';
```

If not found, create it:
```sql
CREATE DATABASE dms;
```

#### Solution C: Check database credentials
Edit `student/classes/db.php`:
```php
private static $dsn = 'mysql:host=localhost:3306;dbname=dms';
private static $user= 'root';
private static $pass= '';  // Your MySQL password
```

#### Solution D: Test database connection
Create `test-db-connection.php`:
```php
<?php
try {
    $conn = new PDO('mysql:host=localhost:3306;dbname=dms', 'root', '');
    echo "✓ Database connection successful!";
} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage();
}
?>
```

---

### Issue 4: Session not persisting / Redirects to login

**Possible Causes:**
1. Session not starting
2. Session timeout too short
3. Session path not writable

**Solutions:**

#### Solution A: Check session configuration
Add to top of `student/reducer.php`:
```php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

#### Solution B: Check session directory permissions
**Windows:** Usually `C:\Windows\Temp`
**Linux:** Usually `/tmp` or `/var/lib/php/sessions`

Make sure PHP can write to this directory.

#### Solution C: Increase session timeout
Edit `php.ini`:
```ini
session.gc_maxlifetime = 1800  ; 30 minutes
```

---

### Issue 5: Migration-related issues

**Possible Causes:**
1. Database migration not run
2. New columns missing
3. Registration status blocking login

**Solutions:**

#### Solution A: Check if migration was run
```sql
DESCRIBE account_studentprofile;
```

Look for these new columns:
- email
- registration_status
- clearance_generated
- clearance_date
- clearance_reference
- created_on

#### Solution B: Run the migration
```sql
SOURCE clearance-schema-migration.sql;
```

Or manually:
```sql
ALTER TABLE account_studentprofile 
ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER password,
ADD COLUMN registration_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER session_id,
ADD COLUMN clearance_generated TINYINT(1) DEFAULT 0 AFTER registration_status,
ADD COLUMN clearance_date VARCHAR(30) DEFAULT NULL AFTER clearance_generated,
ADD COLUMN clearance_reference VARCHAR(20) DEFAULT NULL AFTER clearance_date,
ADD COLUMN created_on VARCHAR(30) DEFAULT NULL AFTER clearance_reference;
```

#### Solution C: Update existing students to 'approved'
```sql
UPDATE account_studentprofile 
SET registration_status = 'approved' 
WHERE registration_status IS NULL;
```

**IMPORTANT:** The login system does NOT check registration_status, so this shouldn't block login. But it's good to set it anyway.

---

## Step-by-Step Login Test

### Step 1: Verify Database
```sql
-- Check database exists
SHOW DATABASES LIKE 'dms';

-- Check table exists
SHOW TABLES LIKE 'account_studentprofile';

-- Check students exist
SELECT COUNT(*) FROM account_studentprofile;
```

### Step 2: Create Test Account
```sql
INSERT INTO account_studentprofile 
(fullname, username, password, dept_name_id, session_id, registration_status) 
VALUES 
('Test Student', 'teststudent', '482c811da5d5b4bc6d497ffa98491e38', 1, 1, 'approved');
```

### Step 3: Test Login
1. Go to `http://your-domain/student/login.php`
2. Enter:
   - Username: `teststudent`
   - Password: `password123`
3. Click Login

### Step 4: Check Browser Console
1. Press F12
2. Go to Console tab
3. Look for errors

### Step 5: Check Network Tab
1. Press F12
2. Go to Network tab
3. Submit login form
4. Look for `reducer.php` request
5. Check response

---

## Password Hash Reference

The system uses MD5 hashing. Here are some common passwords and their hashes:

| Password | MD5 Hash |
|----------|----------|
| password123 | 482c811da5d5b4bc6d497ffa98491e38 |
| admin123 | 0192023a7bbd73250516f069df18b500 |
| student123 | 5f4dcc3b5aa765d61d8327deb882cf99 |
| test123 | cc03e747a6afbbcbf8be7668acfebee5 |

To generate MD5 hash:
```php
<?php echo md5('your_password'); ?>
```

Or online: https://www.md5hashgenerator.com/

---

## Quick Fix Commands

### Reset all student passwords to "password123"
```sql
UPDATE account_studentprofile 
SET password = '482c811da5d5b4bc6d497ffa98491e38';
```

### Create multiple test accounts
```sql
INSERT INTO account_studentprofile 
(fullname, username, password, dept_name_id, session_id, registration_status) 
VALUES 
('John Doe', 'john', '482c811da5d5b4bc6d497ffa98491e38', 1, 1, 'approved'),
('Jane Smith', 'jane', '482c811da5d5b4bc6d497ffa98491e38', 1, 1, 'approved'),
('Bob Wilson', 'bob', '482c811da5d5b4bc6d497ffa98491e38', 1, 1, 'approved');
```

All with password: `password123`

### Check login attempts in real-time
Add this to `student/reducer.php` after line 10:
```php
// Log all login attempts
file_put_contents('login-attempts.log', 
    date('Y-m-d H:i:s') . " - Username: " . $_POST['username'] . "\n", 
    FILE_APPEND
);
```

---

## Still Not Working?

### Enable PHP Error Reporting

Add to top of `student/login.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

Add to top of `student/reducer.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### Check PHP Error Log

**Windows:** `C:\xampp\php\logs\php_error_log`
**Linux:** `/var/log/php/error.log` or `/var/log/apache2/error.log`

### Enable MySQL Query Log

```sql
SET GLOBAL general_log = 'ON';
SET GLOBAL general_log_file = '/tmp/mysql.log';
```

Then check `/tmp/mysql.log` for queries.

---

## Contact Information

If you're still having issues after trying all solutions:

1. Run `diagnose-login-issue.php` and save the output
2. Check browser console for JavaScript errors
3. Check PHP error log for server errors
4. Check MySQL error log for database errors
5. Provide all error messages when asking for help

---

## Summary Checklist

- [ ] MySQL is running
- [ ] Database 'dms' exists
- [ ] Table 'account_studentprofile' exists
- [ ] At least one student account exists
- [ ] Password is correct (MD5 hash matches)
- [ ] jQuery is loaded on login page
- [ ] reducer.php is accessible
- [ ] No JavaScript errors in console
- [ ] No PHP errors in error log
- [ ] Session is starting correctly
- [ ] Database credentials are correct

---

**Last Updated:** April 10, 2026
