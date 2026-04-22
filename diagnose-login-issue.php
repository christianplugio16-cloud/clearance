<?php
/**
 * STUDENT LOGIN DIAGNOSTIC SCRIPT
 * This script helps diagnose why students cannot log in
 */

require_once "student/classes/db.php";

echo "<h1>Student Login Diagnostic Report</h1>";
echo "<hr>";

try {
    $conn = Database::getInstance();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if account_studentprofile table exists
    echo "<h2>1. Checking account_studentprofile table</h2>";
    $stmt = $conn->db->query("SHOW TABLES LIKE 'account_studentprofile'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Table 'account_studentprofile' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Table 'account_studentprofile' does NOT exist!</p>";
        exit;
    }
    
    // Check table structure
    echo "<h2>2. Checking table structure</h2>";
    $stmt = $conn->db->query("DESCRIBE account_studentprofile");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $requiredColumns = ['id', 'username', 'password', 'fullname', 'dept_name_id', 'session_id'];
    $newColumns = ['email', 'registration_status', 'clearance_generated', 'clearance_date', 'clearance_reference', 'created_on'];
    
    echo "<h3>Required columns for login:</h3>";
    echo "<ul>";
    foreach ($requiredColumns as $col) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $col) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo "<li style='color: green;'>✓ $col</li>";
        } else {
            echo "<li style='color: red;'>✗ $col (MISSING!)</li>";
        }
    }
    echo "</ul>";
    
    echo "<h3>New columns from migration:</h3>";
    echo "<ul>";
    foreach ($newColumns as $col) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $col) {
                $found = true;
                break;
            }
        }
        if ($found) {
            echo "<li style='color: green;'>✓ $col</li>";
        } else {
            echo "<li style='color: orange;'>⚠ $col (Not migrated yet - this is OK if you haven't run the migration)</li>";
        }
    }
    echo "</ul>";
    
    // Check if there are any students
    echo "<h2>3. Checking student accounts</h2>";
    $stmt = $conn->db->query("SELECT COUNT(*) as count FROM account_studentprofile");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $studentCount = $result['count'];
    
    if ($studentCount > 0) {
        echo "<p style='color: green;'>✓ Found $studentCount student account(s)</p>";
        
        // Show sample student data (without password)
        echo "<h3>Sample student accounts:</h3>";
        $stmt = $conn->db->query("SELECT id, username, fullname, dept_name_id, session_id FROM account_studentprofile LIMIT 5");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Full Name</th><th>Dept ID</th><th>Session ID</th></tr>";
        foreach ($students as $student) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($student['id']) . "</td>";
            echo "<td>" . htmlspecialchars($student['username']) . "</td>";
            echo "<td>" . htmlspecialchars($student['fullname']) . "</td>";
            echo "<td>" . htmlspecialchars($student['dept_name_id']) . "</td>";
            echo "<td>" . htmlspecialchars($student['session_id']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p style='color: red;'>✗ No student accounts found in database!</p>";
        echo "<p>You need to create at least one student account to test login.</p>";
    }
    
    // Test login with a sample account
    echo "<h2>4. Testing login functionality</h2>";
    if ($studentCount > 0) {
        $stmt = $conn->db->query("SELECT username FROM account_studentprofile LIMIT 1");
        $testStudent = $stmt->fetch(PDO::FETCH_ASSOC);
        $testUsername = $testStudent['username'];
        
        echo "<p>To test login, try these credentials:</p>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> " . htmlspecialchars($testUsername) . "</li>";
        echo "<li><strong>Password:</strong> (You need to know the password for this account)</li>";
        echo "</ul>";
        
        echo "<p><strong>Note:</strong> Passwords are hashed with MD5. If you don't know the password, you can reset it:</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px;'>";
        echo "UPDATE account_studentprofile \n";
        echo "SET password = '" . md5('password123') . "' \n";
        echo "WHERE username = '" . htmlspecialchars($testUsername) . "';\n";
        echo "</pre>";
        echo "<p>This will set the password to: <strong>password123</strong></p>";
    }
    
    // Check PHP session configuration
    echo "<h2>5. Checking PHP session configuration</h2>";
    echo "<ul>";
    echo "<li>Session save path: " . session_save_path() . "</li>";
    echo "<li>Session name: " . session_name() . "</li>";
    echo "<li>Session module: " . ini_get('session.save_handler') . "</li>";
    echo "</ul>";
    
    // Check if reducer.php is accessible
    echo "<h2>6. Checking reducer.php</h2>";
    if (file_exists('student/reducer.php')) {
        echo "<p style='color: green;'>✓ student/reducer.php exists</p>";
    } else {
        echo "<p style='color: red;'>✗ student/reducer.php NOT found!</p>";
    }
    
    // Check database connection settings
    echo "<h2>7. Database connection settings</h2>";
    echo "<ul>";
    echo "<li>Host: localhost:3306</li>";
    echo "<li>Database: dms</li>";
    echo "<li>User: root</li>";
    echo "<li>Password: (empty)</li>";
    echo "</ul>";
    echo "<p><strong>Note:</strong> Make sure these settings match your MySQL configuration.</p>";
    
    // Common issues and solutions
    echo "<h2>8. Common Issues and Solutions</h2>";
    echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";
    echo "<h3>Issue: 'No user is registered using this credentials'</h3>";
    echo "<ul>";
    echo "<li><strong>Wrong username or password:</strong> Double-check your credentials</li>";
    echo "<li><strong>Password not matching:</strong> Passwords are hashed with MD5. Use the SQL above to reset</li>";
    echo "<li><strong>No students in database:</strong> Create a student account first</li>";
    echo "</ul>";
    
    echo "<h3>Issue: Login button does nothing</h3>";
    echo "<ul>";
    echo "<li><strong>JavaScript error:</strong> Check browser console (F12) for errors</li>";
    echo "<li><strong>jQuery not loaded:</strong> Check if jQuery is loading properly</li>";
    echo "<li><strong>reducer.php not accessible:</strong> Check file permissions</li>";
    echo "</ul>";
    
    echo "<h3>Issue: Database connection error</h3>";
    echo "<ul>";
    echo "<li><strong>MySQL not running:</strong> Start MySQL service</li>";
    echo "<li><strong>Wrong credentials:</strong> Check student/classes/db.php settings</li>";
    echo "<li><strong>Database doesn't exist:</strong> Create 'dms' database</li>";
    echo "</ul>";
    echo "</div>";
    
    // Quick fix SQL
    echo "<h2>9. Quick Fix SQL Commands</h2>";
    echo "<p>If you need to create a test student account, run this SQL:</p>";
    echo "<pre style='background: #f5f5f5; padding: 10px;'>";
    echo "INSERT INTO account_studentprofile \n";
    echo "(fullname, username, password, dept_name_id, session_id) \n";
    echo "VALUES \n";
    echo "('Test Student', 'teststudent', '" . md5('password123') . "', 1, 1);\n";
    echo "</pre>";
    echo "<p>Login credentials will be:</p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> teststudent</li>";
    echo "<li><strong>Password:</strong> password123</li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>Diagnostic complete!</strong></p>";
    echo "<p>If you're still having issues, check the specific error messages above.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>Possible solutions:</h3>";
    echo "<ul>";
    echo "<li>Make sure MySQL is running</li>";
    echo "<li>Check database credentials in student/classes/db.php</li>";
    echo "<li>Make sure database 'dms' exists</li>";
    echo "<li>Check MySQL port (default: 3306)</li>";
    echo "</ul>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background: #f5f5f5;
}
h1 {
    color: #003366;
}
h2 {
    color: #228B22;
    margin-top: 30px;
}
h3 {
    color: #333;
}
pre {
    overflow-x: auto;
}
table {
    background: white;
    margin: 10px 0;
}
</style>
