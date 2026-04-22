<?php
// Prevent any output before JSON
ob_start();

session_start();

// Clear any output that might have been generated
ob_end_clean();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(array('data' => array(), 'summary' => array()));
    exit();
}

require_once 'classes/store.php';
require_once 'classes/db.php';

// Get filter parameters from POST
$dateFrom = isset($_POST['dateFrom']) && !empty($_POST['dateFrom']) ? $_POST['dateFrom'] : null;
$dateTo = isset($_POST['dateTo']) && !empty($_POST['dateTo']) ? $_POST['dateTo'] : null;
$sessionId = isset($_POST['session']) && !empty($_POST['session']) ? $_POST['session'] : null;
$departmentId = isset($_POST['department']) && !empty($_POST['department']) ? $_POST['department'] : null;
$statusFilter = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : null;

// Build the SQL query with filters
$conn = Database::getInstance();

// Check if clearance columns exist
$checkColumns = $conn->db->query("SHOW COLUMNS FROM account_studentprofile LIKE 'clearance_generated'");
$hasClearanceColumns = $checkColumns->rowCount() > 0;

// Base query - conditionally include clearance columns
$sql = "SELECT 
    s.id,
    s.fullname,
    s.username as student_id,
    d.dept_name,
    sess.session_name,
    COALESCE(f.amount, 0) as total_fees,
    COALESCE(SUM(p.amount), 0) as amount_paid,
    (COALESCE(f.amount, 0) - COALESCE(SUM(p.amount), 0)) as balance,
    CASE 
        WHEN COALESCE(f.amount, 0) = 0 THEN 'No Fees Assigned'
        WHEN COALESCE(SUM(p.amount), 0) >= COALESCE(f.amount, 0) THEN 'Fully Paid'
        WHEN COALESCE(SUM(p.amount), 0) > 0 THEN 'Partial'
        ELSE 'Unpaid'
    END as payment_status,
    MAX(p.datePaid) as last_payment_date";

// Add clearance columns if they exist
if ($hasClearanceColumns) {
    $sql .= ",
    s.clearance_generated,
    s.clearance_date";
}

$sql .= "
FROM account_studentprofile s
LEFT JOIN system_departmentdata d ON s.dept_name_id = d.id
LEFT JOIN system_sessiondata sess ON s.session_id = sess.id
LEFT JOIN bursary_schoolfees f ON f.did_id = s.dept_name_id AND f.sid_id = s.session_id
LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = f.id";

// Build WHERE clause for filters
$whereConditions = array();
$params = array();

if ($sessionId !== null) {
    $whereConditions[] = "s.session_id = ?";
    $params[] = $sessionId;
}

if ($departmentId !== null) {
    $whereConditions[] = "s.dept_name_id = ?";
    $params[] = $departmentId;
}

// Add WHERE clause if there are conditions
if (!empty($whereConditions)) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

// Add GROUP BY
$groupByColumns = "s.id, s.fullname, s.username, d.dept_name, sess.session_name, f.amount";
if ($hasClearanceColumns) {
    $groupByColumns .= ", s.clearance_generated, s.clearance_date";
}
$sql .= " GROUP BY " . $groupByColumns;

// Add HAVING clause for status filter
if ($statusFilter !== null) {
    $sql .= " HAVING payment_status = ?";
    $params[] = $statusFilter;
}

// Execute query
try {
    $stmt = $conn->db->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Filter by date range if provided (filter on last_payment_date)
    // Note: Students with no payments will be excluded when date filter is active
    if ($dateFrom !== null || $dateTo !== null) {
        $results = array_filter($results, function($row) use ($dateFrom, $dateTo) {
            $lastPaymentDate = $row['last_payment_date'];
            
            // If no payment date and date filter is active, exclude
            if (empty($lastPaymentDate)) {
                return false;
            }
            
            // Check date range
            if ($dateFrom !== null && $lastPaymentDate < $dateFrom) {
                return false;
            }
            if ($dateTo !== null && $lastPaymentDate > $dateTo) {
                return false;
            }
            
            return true;
        });
        
        // Re-index array after filtering
        $results = array_values($results);
    }
    
    // Format data for DataTables
    $data = array();
    foreach ($results as $row) {
        $rowData = array(
            'id' => $row['id'],
            'fullname' => htmlspecialchars($row['fullname']),
            'student_id' => htmlspecialchars($row['student_id']),
            'dept' => htmlspecialchars($row['dept_name'] ?: 'N/A'),
            'session' => htmlspecialchars($row['session_name'] ?: 'N/A'),
            'total_fees' => number_format($row['total_fees'] ?: 0, 2, '.', ''),
            'amount_paid' => number_format($row['amount_paid'] ?: 0, 2, '.', ''),
            'balance' => number_format($row['balance'] ?: 0, 2, '.', ''),
            'payment_status' => $row['payment_status'],
            'last_payment_date' => $row['last_payment_date'] ?: null
        );
        
        // Add clearance data if columns exist
        if ($hasClearanceColumns) {
            $rowData['clearance_generated'] = $row['clearance_generated'];
            $rowData['clearance_date'] = $row['clearance_date'];
        } else {
            $rowData['clearance_generated'] = 0;
            $rowData['clearance_date'] = null;
        }
        
        $data[] = $rowData;
    }
    
    // Calculate summary statistics
    $totalCollected = 0;
    $totalOutstanding = 0;
    $fullyPaidCount = 0;
    
    foreach ($results as $row) {
        $totalCollected += floatval($row['amount_paid']);
        $totalOutstanding += floatval($row['balance']);
        if ($row['payment_status'] === 'Fully Paid') {
            $fullyPaidCount++;
        }
    }
    
    $summary = array(
        'total_collected' => number_format($totalCollected, 2, '.', ''),
        'total_outstanding' => number_format($totalOutstanding, 2, '.', ''),
        'fully_paid_count' => $fullyPaidCount
    );
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(array(
        'data' => $data,
        'summary' => $summary
    ));
    
} catch (PDOException $e) {
    // Log error and return empty response
    error_log("Payment List Error: " . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(array(
        'data' => array(),
        'summary' => array(
            'total_collected' => '0.00',
            'total_outstanding' => '0.00',
            'fully_paid_count' => 0
        ),
        'error' => 'Database error occurred'
    ));
}
?>
