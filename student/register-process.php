<?php
session_start();
require_once('classes/db.php');
require_once('classes/store.php');

// Set JSON header
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => 0, 'message' => 'Invalid request method'));
    exit;
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !Store::validateCSRFToken($_POST['csrf_token'])) {
    echo json_encode(array('status' => 0, 'message' => 'Invalid security token. Please refresh the page and try again.'));
    exit;
}

try {
    // Process registration
    $result = Store::registerStudent();
    
    // Log registration attempt
    $username = isset($_POST['username']) ? $_POST['username'] : 'unknown';
    $email = isset($_POST['email']) ? $_POST['email'] : 'unknown';
    Store::logRegistrationAttempt($username, $email, $result['status'] == 1, $result['message']);
    
    // Regenerate CSRF token after successful submission
    if ($result['status'] == 1) {
        Store::regenerateCSRFToken();
    }
    
    // Return JSON response
    echo json_encode($result);
} catch (Exception $e) {
    // Log error
    Store::logError('REGISTRATION', $e->getMessage());
    
    // Return error response
    echo json_encode(array(
        'status' => 0,
        'message' => 'An error occurred during registration. Please try again later.'
    ));
}
?>
