<?php
// Prevent any output before JSON
ob_start();

session_start();
require_once "classes/store.php";

// Clear any output that might have been generated
ob_end_clean();

// Set JSON header
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(array('data' => array()));
    exit();
}

try {
    $conn = Database::getInstance();
    
    // Query to get all registrations with student details
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
    ");
    
    $query->execute();
    $registrations = $query->fetchAll(PDO::FETCH_ASSOC);
    
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
    
    // Return JSON response
    echo json_encode(array('data' => $data));
    
} catch (Exception $e) {
    // Log error (use uid instead of id)
    $userId = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
    Store::logError('PENDING_REGISTRATIONS', $e->getMessage(), $userId);
    
    // Return empty data
    echo json_encode(array('data' => array()));
}
?>
