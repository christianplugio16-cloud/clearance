<?php
/**
 * Test script for student registration functionality
 * Tests the registerStudent() method and register-process.php endpoint
 */

require_once('admin/classes/db.php');
require_once('admin/classes/store.php');

echo "<h2>Testing Student Registration Functionality</h2>\n";
echo "<pre>\n";

// Test 1: Missing required fields
echo "Test 1: Missing required fields\n";
$_POST = array(
    'fullname' => 'Test Student',
    'username' => '',
    'email' => 'test@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=0, message about required fields\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 0) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 2: Invalid email format
echo "Test 2: Invalid email format\n";
$_POST = array(
    'fullname' => 'Test Student',
    'username' => 'testuser123',
    'email' => 'invalid-email',
    'password' => 'password123',
    'confirm_password' => 'password123',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=0, message about invalid email\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 0 && strpos($result['message'], 'email') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 3: Password too short
echo "Test 3: Password too short\n";
$_POST = array(
    'fullname' => 'Test Student',
    'username' => 'testuser123',
    'email' => 'test@example.com',
    'password' => 'pass1',
    'confirm_password' => 'pass1',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=0, message about password length\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 0 && strpos($result['message'], '8 characters') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 4: Password without letters or numbers
echo "Test 4: Password without letters or numbers\n";
$_POST = array(
    'fullname' => 'Test Student',
    'username' => 'testuser123',
    'email' => 'test@example.com',
    'password' => 'password',
    'confirm_password' => 'password',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=0, message about password requirements\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 0 && strpos($result['message'], 'letters and numbers') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 5: Password mismatch
echo "Test 5: Password mismatch\n";
$_POST = array(
    'fullname' => 'Test Student',
    'username' => 'testuser123',
    'email' => 'test@example.com',
    'password' => 'password123',
    'confirm_password' => 'password456',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=0, message about password mismatch\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 0 && strpos($result['message'], 'do not match') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 6: Valid registration (will create a test user)
echo "Test 6: Valid registration\n";
$testUsername = 'testuser_' . time();
$testEmail = 'test_' . time() . '@example.com';
$_POST = array(
    'fullname' => 'Test Student',
    'username' => $testUsername,
    'email' => $testEmail,
    'password' => 'password123',
    'confirm_password' => 'password123',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=1, success message\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 1) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 7: Duplicate username
echo "Test 7: Duplicate username\n";
$_POST = array(
    'fullname' => 'Another Student',
    'username' => $testUsername,
    'email' => 'another@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=2, message about duplicate username\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 2 && strpos($result['message'], 'Username') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Test 8: Duplicate email
echo "Test 8: Duplicate email\n";
$_POST = array(
    'fullname' => 'Another Student',
    'username' => 'anotheruser123',
    'email' => $testEmail,
    'password' => 'password123',
    'confirm_password' => 'password123',
    'department' => '1',
    'session' => '1'
);
$result = Store::registerStudent();
echo "Expected: status=2, message about duplicate email\n";
echo "Result: status={$result['status']}, message={$result['message']}\n";
echo ($result['status'] === 2 && strpos($result['message'], 'Email') !== false) ? "✓ PASS\n\n" : "✗ FAIL\n\n";

// Cleanup: Delete test user
echo "Cleanup: Removing test user...\n";
$conn = Database::getInstance();
$stmt = $conn->db->prepare("DELETE FROM account_studentprofile WHERE username = ?");
$stmt->execute(array($testUsername));
echo "Test user removed.\n\n";

echo "</pre>\n";
echo "<h3>All tests completed!</h3>\n";
?>
