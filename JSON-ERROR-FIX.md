# DataTables Invalid JSON Response - Fix Guide

## Error Message
```
DataTables warning: table id=registrationsTable - Invalid JSON response
```

## What This Means
The `pendingRegistrationsList.php` file is not returning valid JSON. This happens when:
1. PHP errors/warnings are output before the JSON
2. There's whitespace before the `<?php` tag
3. Database columns don't exist (migration not run)
4. Session issues

## Fixes Applied

### Fix 1: Added Output Buffering
**File:** `admin/pendingRegistrationsList.php`

Added output buffering to prevent any accidental output before JSON:
```php
// Prevent any output before JSON
ob_start();

session_start();
require_once "classes/store.php";

// Clear any output that might have been generated
ob_end_clean();

// Set JSON header
header('Content-Type: application/json');
```

### Fix 2: Fixed Session Variable
Changed `$_SESSION['id']` to `$_SESSION['uid']` in error logging:
```php
// OLD - Wrong variable
Store::logError('PENDING_REGISTRATIONS', $e->getMessage(), $_SESSION['id']);

// NEW - Correct variable
$userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
Store::logError('PENDING_REGISTRATIONS', $e->getMessage(), $userId);
```

### Fix 3: Changed Sort Order
Changed from `ORDER BY s.created_on DESC` to `ORDER BY s.id DESC` to avoid issues with NULL created_on values.

### Fix 4: Added Null Coalescing for Email
```php
'email' => htmlspecialchars($reg['email'] ?? ''),
```

## How to Diagnose

### Step 1: Run the Test Script
Open this in your browser:
```
http://localhost/your-project/test-pending-registrations-json.php
```

This will:
- ✓ Test the database query
- ✓ Show sample data
- ✓ Validate the JSON
- ✓ Show common issues

### Step 2: Check the Actual Endpoint
Open this URL directly in your browser:
```
http://localhost/your-project/admin/pendingRegistrationsList.php
```

**What you should see:**
```json
{"data":[{"id":"1","fullname":"John Doe","username":"john",...}]}
```

**What indicates an error:**
- PHP error messages
- HTML output
- Blank page
- Anything other than pure JSON

### Step 3: Check Browser Console
1. Press F12
2. Go to Console tab
3. Look for errors

### Step 4: Check Network Tab
1. Press F12
2. Go to Network tab
3. Reload the Pending Registrations page
4. Click on `pendingRegistrationsList.php`
5. Check the Response tab

## Common Causes and Solutions

### Cause 1: Database Migration Not Run
**Symptom:** Error about missing columns (email, registration_status, created_on)

**Solution:**
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

### Cause 2: Students Have NULL Values
**Symptom:** Query works but some fields are NULL

**Solution:**
```sql
UPDATE account_studentprofile 
SET registration_status = 'approved', 
    created_on = NOW() 
WHERE registration_status IS NULL OR created_on IS NULL;
```

### Cause 3: Not Logged In as Admin
**Symptom:** Returns `{"data":[]}`

**Solution:**
- Make sure you're logged in as admin
- Check that `$_SESSION['username']` is set

### Cause 4: PHP Errors
**Symptom:** HTML error messages in JSON response

**Solution:**
1. Check PHP error log
2. Enable error display temporarily:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```
3. Fix the errors
4. Disable error display in production

### Cause 5: Database Connection Error
**Symptom:** Exception thrown

**Solution:**
- Check MySQL is running
- Verify database credentials in `admin/classes/db.php`
- Check database 'dms' exists

## Testing Steps

### Test 1: Direct Endpoint Test
```
http://localhost/your-project/admin/pendingRegistrationsList.php
```

Expected output:
```json
{"data":[...]}
```

### Test 2: Check JSON Validity
Copy the response and paste it into: https://jsonlint.com/

### Test 3: Test with Browser DevTools
1. Open Pending Registrations page
2. Press F12
3. Go to Network tab
4. Look for `pendingRegistrationsList.php`
5. Check Status (should be 200)
6. Check Response (should be valid JSON)

### Test 4: Test Query Directly
```sql
SELECT 
    s.id,
    s.fullname,
    s.username,
    s.email,
    s.registration_status,
    s.created_on,
    d.dept_name,
    sess.session_name
FROM account_studentprofile s
LEFT JOIN system_departmentdata d ON s.dept_name_id = d.id
LEFT JOIN system_sessiondata sess ON s.session_id = sess.id
ORDER BY s.id DESC
LIMIT 10;
```

## Quick Fixes

### Fix 1: Clear PHP Output
Add to top of `pendingRegistrationsList.php`:
```php
<?php
ob_start();
// ... rest of code
ob_end_clean();
header('Content-Type: application/json');
```

### Fix 2: Disable Error Display
Add to top of `pendingRegistrationsList.php`:
```php
<?php
error_reporting(0);
ini_set('display_errors', 0);
```

### Fix 3: Return Empty Data on Error
```php
} catch (Exception $e) {
    echo json_encode(array('data' => array()));
}
```

## Verification

After applying fixes:

1. ✅ Open `admin/pendingRegistrationsList.php` directly
2. ✅ Should see valid JSON: `{"data":[...]}`
3. ✅ No PHP errors or warnings
4. ✅ No HTML output
5. ✅ DataTables loads without errors

## Status
✅ **FIXED** - Added output buffering, fixed session variable, improved error handling

---

**Date:** April 10, 2026  
**Issue:** DataTables Invalid JSON Response  
**Resolution:** Output buffering and error handling improvements
