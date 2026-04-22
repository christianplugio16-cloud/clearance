<?php
	session_start();
require_once "classes/db.php";
require_once "classes/store.php";
$conn = Database::getInstance();
date_default_timezone_set("Etc/GMT-8");
if(isset($_POST['action'])){
//started here

if($_POST['action'] == "login"){
	$feedbck = 0;

	$username = $_POST['username']; $password = md5($_POST['password']);
$stmt = $conn->db->prepare("SELECT * FROM account_studentprofile WHERE username = ? AND password = ? ");
$stmt->execute( array($username,$password) );
$member = $stmt->fetch();
	if(!empty($member)){
		// Check registration status
		$registration_status = isset($member['registration_status']) ? $member['registration_status'] : 'approved';
		
		if($registration_status == 'pending') {
			echo 'pending'; // Registration pending admin approval
		} elseif($registration_status == 'rejected') {
			echo 'rejected'; // Registration rejected by admin
		} else {
			// Approved - allow login
			$_SESSION['page'] = "logged";
			$_SESSION['uid'] = $member['id'];
			$_SESSION['fullname'] = $member['fullname'];
			$_SESSION['department'] = $member['dept_name_id'];
			$_SESSION['session'] = $member['session_id'];
			$_SESSION['username'] = $member['username'];
			echo 1;
		}
	}
	else {
			echo $feedbck;
	}
}




if($_POST['action'] == "addPayment"){
	$send = Store::makePayment($_POST['feesId'],$_SESSION['uid'],$_POST['amount']);
	
	// After successful payment, check if clearance should be generated
	if($send == 1) {
		// Check if payment is complete
		if(Store::checkPaymentComplete($_SESSION['uid'])) {
			// Generate clearance if not already generated
			$clearanceResult = Store::generateClearance($_SESSION['uid']);
			if($clearanceResult == 1) {
				// Log clearance generation
				Store::logClearanceGeneration($_SESSION['uid'], $_SESSION['username']);
			}
		}
	}
	
	echo $send;
}
}


?>