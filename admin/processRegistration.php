<?php
session_start();
require_once "classes/store.php";

// Set JSON header
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(array('status' => 0, 'message' => 'Unauthorized access'));
    exit();
}

// Check if required parameters are present
if (!isset($_POST['action']) || !isset($_POST['student_id'])) {
    echo json_encode(array('status' => 0, 'message' => 'Missing required parameters'));
    exit();
}

try {
    $action = $_POST['action'];
    $studentId = $_POST['student_id'];
    
    // Process the action
    if ($action === 'approve') {
        $result = Store::approveRegistration($studentId);
        if ($result === 1) {
            echo json_encode(array('status' => 1, 'message' => 'Registration approved successfully'));
        } else {
            echo json_encode(array('status' => 0, 'message' => 'Failed to approve registration'));
        }
    } elseif ($action === 'reject') {
        $result = Store::rejectRegistration($studentId);
        if ($result === 1) {
            echo json_encode(array('status' => 1, 'message' => 'Registration rejected successfully'));
        } else {
            echo json_encode(array('status' => 0, 'message' => 'Failed to reject registration'));
        }
    } else {
        echo json_encode(array('status' => 0, 'message' => 'Invalid action'));
    }
} catch (Exception $e) {
    // Log error
    Store::logError('REGISTRATION_APPROVAL', $e->getMessage(), $_SESSION['id']);
    
    // Return error response
    echo json_encode(array(
        'status' => 0,
        'message' => 'An error occurred while processing the registration. Please try again.'
    ));
}
?>
