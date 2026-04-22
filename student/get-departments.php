<?php
/**
 * Get Departments by Faculty ID
 * AJAX endpoint for registration form
 */

require_once '../admin/classes/store.php';

header('Content-Type: application/json');

if(isset($_POST['faculty_id']) && !empty($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];
    
    try {
        $departments = Store::getDeptList($faculty_id);
        $dept_array = array();
        
        while($dept = $departments->fetch(PDO::FETCH_ASSOC)) {
            $dept_array[] = array(
                'id' => $dept['id'],
                'dept_name' => $dept['dept_name']
            );
        }
        
        echo json_encode(array(
            'status' => 1,
            'departments' => $dept_array
        ));
    } catch(Exception $e) {
        echo json_encode(array(
            'status' => 0,
            'message' => 'Error loading departments',
            'departments' => array()
        ));
    }
} else {
    echo json_encode(array(
        'status' => 0,
        'message' => 'Faculty ID is required',
        'departments' => array()
    ));
}
?>
