<?php

require_once "db.php";


class Store extends Database{
public function __construct(){
parent::__construct();
if(!isset($_SESSION)){
	session_start();
}
}


public static function existOne($tbl, $col, $value){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $col LIKE ? Limit 1");
$select->execute(array($value));
return $select->rowCount();
}
public static function existTwo($tbl, $col, $col2, $value, $value2){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $col LIKE ? AND $col2 LIKE ? Limit 1");
$select->execute(array($value, $value2));
return $select->rowCount();
}


public static function loadDistincts($col, $tbl){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT DISTINCT $col FROM $tbl ");
$select->execute();
return $select;
}


public static function loadDistinctWhere($col, $tbl, $where, $value){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT DISTINCT $col FROM $tbl WHERE $where like ? ");
$select->execute(array($value));
return $select;
}


public static function loadDistinct($col, $tbl){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT DISTINCT $col FROM $tbl ");
$select->execute();
return $select->fetchAll();
}
public static function loadDistinctCond1($col, $tbl, $cond, $value){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT DISTINCT $col FROM $tbl WHERE $cond = ? ");
$select->execute(array($value));
return $select->fetchAll();
}

public static function getColById($tbl, $col, $id, $return){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $col = ? Limit 1");
$select->execute(array($id));
return $select->fetchColumn($return);
}
public static function getColById2($tbl, $col, $col2, $id,  $id2, $return){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $col = ?  AND $col2 = ? Limit 1");
$select->execute(array($id,$id2));
return $select->fetchColumn($return);
}
public static function getName($tbl, $col, $id, $return){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $col LIKE ? ");
$select->execute(array($id));
return $select->fetchColumn($return);
}

public static function loadTable($tbl){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl");
$select->execute();
return $select;
}
public static function loadTbl($tbl){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl");
$select->execute();
return $select->fetchAll();
}
public static function loadTblCond($tbl, $cond, $value){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $cond LIKE ?  ");
$select->execute(array($value));
return $select;
}


public static function loadTblCond2($tbl, $cond, $value){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM $tbl WHERE $cond LIKE ?  ");
$select->execute(array($value));
return $select->fetchAll();
}


public static function getDeptList($faculty){
$conn = Database::getInstance();
$select = $conn->db->prepare("SELECT * FROM system_departmentdata WHERE fid_id = ? ");
$select->execute(array($faculty));
return $select;
}

public static function CreatedOn(){
return   date('Y-m-d H:i:sa');
}

#############################insert functions###########################################



public static function saveFaculty(){
$conn = Database::getInstance();
$faculty_name = $_POST['faculty_name'];

if($existCheck = self::existOne('system_facultydata', 'faculty_name', $faculty_name)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO system_facultydata (faculty_name, created_on)
																											VALUES (:faculty_name, :created_on )");
$stmt->bindParam(':faculty_name', $faculty_name, PDO::PARAM_STR);
$stmt->bindParam(':created_on', $now, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}

public static function saveDepartment(){
$conn = Database::getInstance();
$dept_name = $_POST['dept_name'];
$faculty = $_POST['faculty'];

if($existCheck = self::existTwo('system_departmentdata', 'dept_name', 'fid_id', $dept_name, $faculty)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO system_departmentdata (dept_name, fid_id, created_on)
																											VALUES (:dept_name, :fid_id, :created_on )");
$stmt->bindParam(':dept_name', $dept_name, PDO::PARAM_STR);
$stmt->bindParam(':fid_id', $faculty, PDO::PARAM_STR);
$stmt->bindParam(':created_on', $now, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}






public static function saveSession(){
$conn = Database::getInstance();
$session = $_POST['session_name'];

if($existCheck = self::existOne('system_sessiondata', 'session_name', $session)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO system_sessiondata (session_name, created_on)
																											VALUES (:session_name, :created_on )");
$stmt->bindParam(':session_name', $session, PDO::PARAM_STR);
$stmt->bindParam(':created_on', $now, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}


public static function saveFee(){
$conn = Database::getInstance();
$session = $_POST['session'];
$department = $_POST['department'];
$amount = $_POST['amount'];

if($existCheck = self::existTwo('bursary_schoolfees', 'did_id', 'sid_id', $department, $session)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO bursary_schoolfees (did_id, sid_id, amount)
																											VALUES (:dept, :session, :amount )");
$stmt->bindParam(':dept', $department, PDO::PARAM_STR);
$stmt->bindParam(':session', $session, PDO::PARAM_STR);
$stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}

public static function saveStudent(){
$conn = Database::getInstance();
$fullname = $_POST['fullname'];
$department = $_POST['department'];
$session = $_POST['session'];
$username = $_POST['username'];
$password = md5($_POST['password']);

if($existCheck = self::existOne('account_studentprofile', 'username', $username)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO account_studentprofile (fullname, username, password, dept_name_id, session_id)
																											VALUES (:fullname, :username, :password, :dept, :session )");
$stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);
$stmt->bindParam(':dept', $department, PDO::PARAM_STR);
$stmt->bindParam(':session', $session, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}



public static function saveUser(){
$conn = Database::getInstance();
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$password = md5($_POST['password']);

if($existCheck = self::existOne('users', 'username', $username)==0)
{
	$now = self::CreatedOn();

$stmt = $conn->db->prepare("INSERT INTO users (fullname, username, password, created_on)
																											VALUES (:fullname, :username, :password, :created_on )");
$stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':password', $password, PDO::PARAM_STR);
$stmt->bindParam(':created_on', $now, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;
 }
 else {
	return 2;
	}


}





public static function saveCandidate($POST,$FILES){
$conn = Database::getInstance();
	session_start();
$barcode = substr(number_format(time() * rand(),0,'',''),0,13);
$image = '';

 $File_Name  = strtolower($FILES['photo']['name']);

if($File_Name!="")
{
 $image = self::uploadImage($FILES,$barcode);
}
else{
	$image = $File_Name;
}

$stmt = $conn->db->prepare("INSERT INTO candidates ( stdId, position,session,level, image)
 	VALUES (:stdId,:position,:session,:level,:img)");

$stmt->bindParam(':stdId', $_POST['student'], PDO::PARAM_INT);
$stmt->bindParam(':position', $_POST['position'], PDO::PARAM_STR);
$stmt->bindParam(':session', $_POST['session'], PDO::PARAM_STR);
$stmt->bindParam(':level', $_POST['level'], PDO::PARAM_STR);
$stmt->bindParam(':img', $image, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;

}






#############################insert functions###########################################

#############################update functions###########################################


	public static function activations($GET){
$conn = Database::getInstance();

	$status = "Pending";
	if($_GET['etype'] == "activate"):
			$status = "Active";
		elseif($_GET['etype'] == "finished"):
			$status = "Finished";
	endif;
	$session = $_GET['session'];

	$position = $_GET['position'];


$stmt = $conn->db->prepare("UPDATE candidates SET status=:status WHERE  session=:session AND  position=:position ");

$stmt->bindParam(':status', $status, PDO::PARAM_STR);
$stmt->bindParam(':session', $session, PDO::PARAM_STR);
$stmt->bindParam(':position', $position, PDO::PARAM_STR);
if ($stmt->execute()): return 1; else: return 0;	endif;

}

public static function updateAnimal($POST){
$conn = Database::getInstance();
	session_start();
	$userId = $_SESSION['id'];


$stmt = $conn->db->prepare("UPDATE animals SET animalno=:animalno, weight=:weight,
																											arrived=:arrived,breed_id=:breed_id,remark=:remark,
																											health_status=:health_status,  updatedby=:by WHERE id=:id ");

$stmt->bindParam(':animalno', $_POST['animalno'], PDO::PARAM_STR);
$stmt->bindParam(':weight', $_POST['weight'], PDO::PARAM_STR);
$stmt->bindParam(':arrived', $_POST['arrived'], PDO::PARAM_STR);
$stmt->bindParam(':breed_id', $_POST['breed'], PDO::PARAM_INT);
$stmt->bindParam(':remark', $_POST['remark'], PDO::PARAM_STR);
$stmt->bindParam(':health_status', $_POST['status'], PDO::PARAM_STR);
//$stmt->bindParam(':gender', $_POST['gender'], PDO::PARAM_STR);
$stmt->bindParam(':by', $userId, PDO::PARAM_INT);
$stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
if ($stmt->execute()): return 1; else: return 0;	endif;

}


#############################update functions###########################################

#############################delete functions###########################################
public static function delAnimal($id){

$conn = Database::getInstance();
$result =$conn->db->prepare("DELETE FROM animals WHERE id= :memid");
	$result->bindParam(':memid', $id);
	if($result->execute()): return 1; else: return 0; endif;
}
#############################delete functions###########################################

public static function uploadImage($FILES,$imagename){
$UploadDirectory	= 'img/'; //specify upload directory ends with / (slash)
//Is file size is less than allowed size.
if ($FILES["photo"]["size"] > 5242880) {
die("File size is too big!");
}
//allowed file type Server side check
switch(strtolower($FILES['photo']['type']))
{
//allowed file types
case 'image/png':
case 'image/gif':
case 'image/jpeg':
case 'image/pjpeg':
case 'image/jpg':
break;
default:
	var_dump(strtolower($FILES['photo']['type']));
die('Unsupported File!'); //output error
}
$File_Name          = strtolower($FILES['photo']['name']);
$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
$NewFileName 		= $imagename.$File_Ext; //new file name
$location='';
if($File_Name!="")
{$location			= $UploadDirectory.$NewFileName;
if(move_uploaded_file($FILES['photo']['tmp_name'], $UploadDirectory.$NewFileName )):
 return $location;
else: return $location; endif;
}
else{ return $location;}
}

// Payment calculation methods for enhanced clearance features
public static function checkPaymentComplete($studentId) {
    $conn = Database::getInstance();
    
    $query = $conn->db->prepare("
        SELECT 
            f.amount as total_fees,
            COALESCE(SUM(p.amount), 0) as amount_paid
        FROM account_studentprofile s
        LEFT JOIN bursary_schoolfees f ON f.did_id = s.dept_name_id AND f.sid_id = s.session_id
        LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = f.id
        WHERE s.id = ?
        GROUP BY s.id, f.amount
    ");
    $query->execute(array($studentId));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['amount_paid'] >= $result['total_fees']) {
        return true;
    }
    return false;
}

public static function getPaymentSummary($studentId) {
    $conn = Database::getInstance();
    
    $query = $conn->db->prepare("
        SELECT 
            f.amount as total_fees,
            COALESCE(SUM(p.amount), 0) as amount_paid,
            (f.amount - COALESCE(SUM(p.amount), 0)) as balance
        FROM account_studentprofile s
        LEFT JOIN bursary_schoolfees f ON f.did_id = s.dept_name_id AND f.sid_id = s.session_id
        LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = f.id
        WHERE s.id = ?
        GROUP BY s.id, f.amount
    ");
    $query->execute(array($studentId));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return array(
            'total_fees' => $result['total_fees'],
            'amount_paid' => $result['amount_paid'],
            'balance' => $result['balance']
        );
    }
    
    return array(
        'total_fees' => 0,
        'amount_paid' => 0,
        'balance' => 0
    );
}

public static function getPaymentStatus($studentId) {
    $conn = Database::getInstance();
    
    $query = $conn->db->prepare("
        SELECT 
            f.amount as total_fees,
            COALESCE(SUM(p.amount), 0) as amount_paid
        FROM account_studentprofile s
        LEFT JOIN bursary_schoolfees f ON f.did_id = s.dept_name_id AND f.sid_id = s.session_id
        LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = f.id
        WHERE s.id = ?
        GROUP BY s.id, f.amount
    ");
    $query->execute(array($studentId));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$result || $result['total_fees'] == 0) {
        return 'No Fees Assigned';
    }
    
    $amountPaid = $result['amount_paid'];
    $totalFees = $result['total_fees'];
    
    if ($amountPaid >= $totalFees) {
        return 'Fully Paid';
    } elseif ($amountPaid > 0) {
        return 'Partial';
    } else {
        return 'Unpaid';
    }
}

public static function generateClearance($studentId) {
    try {
        $conn = Database::getInstance();
        
        // Check if already generated
        $check = $conn->db->prepare("SELECT clearance_generated FROM account_studentprofile WHERE id = ?");
        $check->execute(array($studentId));
        if ($check->fetchColumn(0) == 1) {
            return 2; // Already generated
        }
        
        // Check payment status
        $paymentCheck = self::checkPaymentComplete($studentId);
        if (!$paymentCheck) {
            return 0; // Payment not complete
        }
        
        // Generate unique reference
        $year = date('Y');
        $random = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $reference = "CLR-{$year}-{$random}";
        
        // Update student record
        $now = self::CreatedOn();
        $stmt = $conn->db->prepare("UPDATE account_studentprofile 
                                    SET clearance_generated = 1, 
                                        clearance_date = :date, 
                                        clearance_reference = :ref 
                                    WHERE id = :id");
        $stmt->bindParam(':date', $now, PDO::PARAM_STR);
        $stmt->bindParam(':ref', $reference, PDO::PARAM_STR);
        $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return 1; // Success
        } else {
            return 0; // Failed
        }
    } catch (Exception $e) {
        self::logError('CLEARANCE_GENERATION', $e->getMessage(), $studentId);
        return 0; // Failed
    }
}

public static function getClearanceData($studentId) {
    $conn = Database::getInstance();
    
    $query = $conn->db->prepare("
        SELECT 
            s.id,
            s.fullname,
            s.username as student_id,
            s.clearance_generated,
            s.clearance_date,
            s.clearance_reference,
            d.dept_name,
            f.faculty_name,
            sess.session_name,
            fees.amount as total_fees,
            COALESCE(SUM(p.amount), 0) as amount_paid
        FROM account_studentprofile s
        LEFT JOIN system_departmentdata d ON s.dept_name_id = d.id
        LEFT JOIN system_facultydata f ON d.faculty_id = f.id
        LEFT JOIN system_sessiondata sess ON s.session_id = sess.id
        LEFT JOIN bursary_schoolfees fees ON fees.did_id = s.dept_name_id AND fees.sid_id = s.session_id
        LEFT JOIN payment p ON p.studentId = s.id AND p.feesId = fees.id
        WHERE s.id = ?
        GROUP BY s.id
    ");
    $query->execute(array($studentId));
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return array(
            'id' => $result['id'],
            'fullname' => $result['fullname'],
            'student_id' => $result['student_id'],
            'clearance_generated' => $result['clearance_generated'],
            'clearance_date' => $result['clearance_date'],
            'clearance_reference' => $result['clearance_reference'],
            'dept_name' => $result['dept_name'],
            'faculty_name' => $result['faculty_name'],
            'session_name' => $result['session_name'],
            'total_fees' => $result['total_fees'],
            'amount_paid' => $result['amount_paid'],
            'payment_status' => self::getPaymentStatus($studentId)
        );
    }
    
    return null;
}

public static function registerStudent() {
    $conn = Database::getInstance();
    
    // Validate inputs
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $session = isset($_POST['session']) ? $_POST['session'] : '';
    
    // Validation checks
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        return array('status' => 0, 'message' => 'All fields are required');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('status' => 0, 'message' => 'Invalid email format');
    }
    
    if (strlen($password) < 8) {
        return array('status' => 0, 'message' => 'Password must be at least 8 characters');
    }
    
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return array('status' => 0, 'message' => 'Password must contain letters and numbers');
    }
    
    if ($password !== $confirm_password) {
        return array('status' => 0, 'message' => 'Passwords do not match');
    }
    
    // Check if username exists
    if (self::existOne('account_studentprofile', 'username', $username) > 0) {
        return array('status' => 2, 'message' => 'Username already exists');
    }
    
    // Check if email exists
    if (self::existOne('account_studentprofile', 'email', $email) > 0) {
        return array('status' => 2, 'message' => 'Email already registered');
    }
    
    // Hash password
    $hashed_password = md5($password);
    
    // Insert student record
    $stmt = $conn->db->prepare("INSERT INTO account_studentprofile 
                                (fullname, username, password, email, dept_name_id, session_id, registration_status) 
                                VALUES (:fullname, :username, :password, :email, :dept, :session, 'pending')");
    $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':dept', $department, PDO::PARAM_INT);
    $stmt->bindParam(':session', $session, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        return array('status' => 1, 'message' => 'Registration successful! Please wait for admin approval.');
    } else {
        return array('status' => 0, 'message' => 'Registration failed. Please try again.');
    }
}

public static function approveRegistration($studentId) {
    $conn = Database::getInstance();
    
    $stmt = $conn->db->prepare("UPDATE account_studentprofile 
                                SET registration_status = 'approved' 
                                WHERE id = :id");
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        return 1; // Success
    } else {
        return 0; // Failed
    }
}

public static function rejectRegistration($studentId) {
    $conn = Database::getInstance();
    
    $stmt = $conn->db->prepare("UPDATE account_studentprofile 
                                SET registration_status = 'rejected' 
                                WHERE id = :id");
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        return 1; // Success
    } else {
        return 0; // Failed
    }
}

/**
 * Logging Functions
 */

public static function logClearanceGeneration($studentId, $username) {
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/clearance.log';
    $timestamp = date('Y-m-d H:i:s');
    $message = "[{$timestamp}] [INFO] [CLEARANCE] [Student ID: {$studentId}, Username: {$username}] Clearance generated successfully\n";
    
    file_put_contents($logFile, $message, FILE_APPEND);
}

public static function logRegistrationAttempt($username, $email, $success, $message = '') {
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/registration.log';
    $timestamp = date('Y-m-d H:i:s');
    $status = $success ? 'SUCCESS' : 'FAILED';
    $logMessage = "[{$timestamp}] [{$status}] [REGISTRATION] [Username: {$username}, Email: {$email}] {$message}\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

public static function logPaymentQuery($adminId, $filters = '') {
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/payments.log';
    $timestamp = date('Y-m-d H:i:s');
    $filterInfo = $filters ? "Filters: {$filters}" : 'No filters';
    $message = "[{$timestamp}] [INFO] [PAYMENT_QUERY] [Admin ID: {$adminId}] Payment list accessed. {$filterInfo}\n";
    
    file_put_contents($logFile, $message, FILE_APPEND);
}

public static function logError($component, $errorMessage, $userId = null) {
    $logDir = __DIR__ . '/../../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $userInfo = $userId ? "User ID: {$userId}" : 'No user';
    $message = "[{$timestamp}] [ERROR] [{$component}] [{$userInfo}] {$errorMessage}\n";
    
    file_put_contents($logFile, $message, FILE_APPEND);
}

/**
 * Security Functions
 */

public static function generateCSRFToken() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

public static function validateCSRFToken($token) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token']) || !isset($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

public static function regenerateCSRFToken() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

public static function validateSession($timeout = 1800) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    // Check if session has timed out (default 30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        session_unset();
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}


}
