<?php
/**
 * Check Registrations Diagnostic Script
 * This checks if students are being registered and why they might not show up
 */

require_once "admin/classes/db.php";

echo "<h1>Registration Diagnostic Report</h1>";
echo "<hr>";

try {
    $conn = Database::getInstance();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check if registration_status column exists
    echo "<h2>1. Checking database schema</h2>";
    $stmt = $conn->db->query("DESCRIBE account_studentprofile");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $hasEmail = false;
    $hasRegistrationStatus = false;
    $hasCreatedOn = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'email') $hasEmail = true;
        if ($column['Field'] === 'registration_status') $hasRegistrationStatus = true;
        if ($column['Field'] === 'created_on') $hasCreatedOn = true;
    }
    
    echo "<ul>";
    echo "<li>" . ($hasEmail ? "✓" : "✗") . " email column</li>";
    echo "<li>" . ($hasRegistrationStatus ? "✓" : "✗") . " registration_status column</li>";
    echo "<li>" . ($hasCreatedOn ? "✓" : "✗") . " created_on column</li>";
    echo "</ul>";
    
    if (!$hasEmail || !$hasRegistrationStatus || !$hasCreatedOn) {
        echo "<p style='color: red;'><strong>⚠ Database migration not run!</strong></p>";
        echo "<p>Run this SQL file: <code>clearance-schema-migration.sql</code></p>";
        echo "<p>Or run this SQL manually:</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px;'>";
        echo "ALTER TABLE account_studentprofile \n";
        echo "ADD COLUMN email VARCHAR(100) DEFAULT NULL AFTER password,\n";
        echo "ADD COLUMN registration_status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' AFTER session_id,\n";
        echo "ADD COLUMN clearance_generated TINYINT(1) DEFAULT 0 AFTER registration_status,\n";
        echo "ADD COLUMN clearance_date VARCHAR(30) DEFAULT NULL AFTER clearance_generated,\n";
        echo "ADD COLUMN clearance_reference VARCHAR(20) DEFAULT NULL AFTER clearance_date,\n";
        echo "ADD COLUMN created_on VARCHAR(30) DEFAULT NULL AFTER clearance_reference;\n";
        echo "</pre>";
    }
    
    // Check all students
    echo "<h2>2. Checking all students in database</h2>";
    $stmt = $conn->db->query("SELECT COUNT(*) as count FROM account_studentprofile");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalStudents = $result['count'];
    
    echo "<p>Total students: <strong>$totalStudents</strong></p>";
    
    if ($totalStudents > 0) {
        // Show all students with their registration status
        echo "<h3>All Students:</h3>";
        
        if ($hasRegistrationStatus) {
            $stmt = $conn->db->query("
                SELECT 
                    id, 
                    fullname, 
                    username, 
                    email, 
                    registration_status, 
                    created_on,
                    dept_name_id,
                    session_id
                FROM account_studentprofile 
                ORDER BY id DESC 
                LIMIT 20
            ");
        } else {
            $stmt = $conn->db->query("
                SELECT 
                    id, 
                    fullname, 
                    username, 
                    dept_name_id,
                    session_id
                FROM account_studentprofile 
                ORDER BY id DESC 
                LIMIT 20
            ");
        }
        
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Full Name</th>";
        echo "<th>Username</th>";
        if ($hasEmail) echo "<th>Email</th>";
        if ($hasRegistrationStatus) echo "<th>Status</th>";
        if ($hasCreatedOn) echo "<th>Created On</th>";
        echo "<th>Dept ID</th>";
        echo "<th>Session ID</th>";
        echo "</tr>";
        
        foreach ($students as $student) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($student['id']) . "</td>";
            echo "<td>" . htmlspecialchars($student['fullname']) . "</td>";
            echo "<td>" . htmlspecialchars($student['username']) . "</td>";
            if ($hasEmail) echo "<td>" . htmlspecialchars($student['email'] ?? 'NULL') . "</td>";
            if ($hasRegistrationStatus) {
                $status = $student['registration_status'] ?? 'NULL';
                $color = $status === 'approved' ? 'green' : ($status === 'pending' ? 'orange' : 'red');
                echo "<td style='color: $color;'><strong>" . htmlspecialchars($status) . "</strong></td>";
            }
            if ($hasCreatedOn) echo "<td>" . htmlspecialchars($student['created_on'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($student['dept_name_id']) . "</td>";
            echo "<td>" . htmlspecialchars($student['session_id']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count by status
        if ($hasRegistrationStatus) {
            echo "<h3>Students by Status:</h3>";
            $stmt = $conn->db->query("
                SELECT 
                    registration_status, 
                    COUNT(*) as count 
                FROM account_studentprofile 
                GROUP BY registration_status
            ");
            $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<ul>";
            foreach ($statusCounts as $statusCount) {
                $status = $statusCount['registration_status'] ?? 'NULL';
                $count = $statusCount['count'];
                echo "<li><strong>$status:</strong> $count students</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ No students found in database</p>";
        echo "<p>Try registering a student through the registration form.</p>";
    }
    
    // Check departments and sessions
    echo "<h2>3. Checking departments and sessions</h2>";
    
    $stmt = $conn->db->query("SELECT COUNT(*) as count FROM system_departmentdata");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $deptCount = $result['count'];
    echo "<p>Departments: <strong>$deptCount</strong></p>";
    
    if ($deptCount > 0) {
        $stmt = $conn->db->query("SELECT id, dept_name FROM system_departmentdata LIMIT 5");
        $depts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<ul>";
        foreach ($depts as $dept) {
            echo "<li>ID: " . $dept['id'] . " - " . htmlspecialchars($dept['dept_name']) . "</li>";
        }
        echo "</ul>";
    }
    
    $stmt = $conn->db->query("SELECT COUNT(*) as count FROM system_sessiondata");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $sessionCount = $result['count'];
    echo "<p>Sessions: <strong>$sessionCount</strong></p>";
    
    if ($sessionCount > 0) {
        $stmt = $conn->db->query("SELECT id, session_name FROM system_sessiondata LIMIT 5");
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<ul>";
        foreach ($sessions as $session) {
            echo "<li>ID: " . $session['id'] . " - " . htmlspecialchars($session['session_name']) . "</li>";
        }
        echo "</ul>";
    }
    
    // Test the pending registrations query
    echo "<h2>4. Testing Pending Registrations Query</h2>";
    $stmt = $conn->db->prepare("
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
        LIMIT 10
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Query returned: <strong>" . count($results) . " rows</strong></p>";
    
    if (count($results) > 0) {
        echo "<p style='color: green;'>✓ Query works! Students should appear in Pending Registrations page.</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Query returned no results. No students to display.</p>";
    }
    
    // Recommendations
    echo "<h2>5. Recommendations</h2>";
    echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";
    
    if (!$hasEmail || !$hasRegistrationStatus || !$hasCreatedOn) {
        echo "<h3>⚠ Action Required: Run Database Migration</h3>";
        echo "<p>The database is missing required columns. Run the migration:</p>";
        echo "<pre>SOURCE clearance-schema-migration.sql;</pre>";
    } else {
        echo "<h3>✓ Database Schema is Correct</h3>";
    }
    
    if ($totalStudents === 0) {
        echo "<h3>⚠ No Students in Database</h3>";
        echo "<p>Register a test student:</p>";
        echo "<ol>";
        echo "<li>Go to: <a href='student/register.php'>student/register.php</a></li>";
        echo "<li>Fill out the registration form</li>";
        echo "<li>Submit the form</li>";
        echo "<li>Check Pending Registrations in admin panel</li>";
        echo "</ol>";
    } else {
        echo "<h3>✓ Students Found in Database</h3>";
        echo "<p>Students should appear in the Pending Registrations page.</p>";
    }
    
    if ($deptCount === 0 || $sessionCount === 0) {
        echo "<h3>⚠ Missing Departments or Sessions</h3>";
        echo "<p>You need to create departments and sessions first:</p>";
        echo "<ul>";
        echo "<li>Admin → Programs (to create departments)</li>";
        echo "<li>Admin → Academic Sessions (to create sessions)</li>";
        echo "</ul>";
    }
    
    echo "</div>";
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>Diagnostic complete!</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
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
