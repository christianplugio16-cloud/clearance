<?php
/**
 * Test Pending Registrations JSON Endpoint
 * This helps diagnose JSON response issues
 */

// Start session as admin
session_start();

// Simulate admin login (REMOVE THIS IN PRODUCTION!)
$_SESSION['username'] = 'admin';
$_SESSION['uid'] = 1;

echo "<h1>Testing Pending Registrations JSON Endpoint</h1>";
echo "<hr>";

echo "<h2>1. Direct PHP Test</h2>";

try {
    require_once "admin/classes/db.php";
    require_once "admin/classes/store.php";
    
    $conn = Database::getInstance();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test the query
    $query = $conn->db->prepare("
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
    
    $query->execute();
    $registrations = $query->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Query returned: <strong>" . count($registrations) . " rows</strong></p>";
    
    if (count($registrations) > 0) {
        echo "<h3>Sample Data:</h3>";
        echo "<pre>";
        print_r($registrations[0]);
        echo "</pre>";
        
        // Format data for DataTables
        $data = array();
        foreach ($registrations as $reg) {
            $data[] = array(
                'id' => $reg['id'],
                'fullname' => htmlspecialchars($reg['fullname']),
                'username' => htmlspecialchars($reg['username']),
                'email' => htmlspecialchars($reg['email'] ?? ''),
                'dept_name' => htmlspecialchars($reg['dept_name'] ?? 'N/A'),
                'session_name' => htmlspecialchars($reg['session_name'] ?? 'N/A'),
                'created_on' => $reg['created_on'] ? date('M d, Y', strtotime($reg['created_on'])) : 'N/A',
                'registration_status' => $reg['registration_status'] ?? 'approved'
            );
        }
        
        $jsonResponse = json_encode(array('data' => $data));
        
        echo "<h3>JSON Response:</h3>";
        echo "<textarea style='width: 100%; height: 200px;'>";
        echo $jsonResponse;
        echo "</textarea>";
        
        // Validate JSON
        json_decode($jsonResponse);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "<p style='color: green;'>✓ JSON is valid!</p>";
        } else {
            echo "<p style='color: red;'>✗ JSON is invalid: " . json_last_error_msg() . "</p>";
        }
        
    } else {
        echo "<p style='color: orange;'>⚠ No students found</p>";
        echo "<p>The JSON response will be:</p>";
        echo "<pre>" . json_encode(array('data' => array())) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<h2>2. Test Actual Endpoint</h2>";
echo "<p>Open this URL in a new tab to see the actual JSON response:</p>";
echo "<p><a href='admin/pendingRegistrationsList.php' target='_blank'>admin/pendingRegistrationsList.php</a></p>";

echo "<hr>";
echo "<h2>3. Common Issues and Solutions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";

echo "<h3>Issue: Invalid JSON Response</h3>";
echo "<p><strong>Possible Causes:</strong></p>";
echo "<ul>";
echo "<li>PHP errors or warnings being output before JSON</li>";
echo "<li>Whitespace before &lt;?php tag</li>";
echo "<li>Missing columns in database (email, registration_status, created_on)</li>";
echo "<li>Database connection error</li>";
echo "<li>Session not set (not logged in as admin)</li>";
echo "</ul>";

echo "<p><strong>Solutions:</strong></p>";
echo "<ol>";
echo "<li><strong>Check for PHP errors:</strong> Look at the actual endpoint URL above</li>";
echo "<li><strong>Run database migration:</strong> Make sure all columns exist</li>";
echo "<li><strong>Check browser console:</strong> Press F12 → Console tab</li>";
echo "<li><strong>Check network tab:</strong> Press F12 → Network tab → Look for pendingRegistrationsList.php</li>";
echo "</ol>";

echo "</div>";

echo "<hr>";
echo "<h2>4. Quick Fixes</h2>";

echo "<h3>Fix 1: Update existing students</h3>";
echo "<p>If students don't have email, registration_status, or created_on:</p>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo "UPDATE account_studentprofile \n";
echo "SET registration_status = 'approved', \n";
echo "    created_on = NOW() \n";
echo "WHERE registration_status IS NULL OR created_on IS NULL;\n";
echo "</pre>";

echo "<h3>Fix 2: Check if columns exist</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo "DESCRIBE account_studentprofile;\n";
echo "</pre>";

echo "<h3>Fix 3: Run migration</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px;'>";
echo "SOURCE clearance-schema-migration.sql;\n";
echo "</pre>";

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
</style>
