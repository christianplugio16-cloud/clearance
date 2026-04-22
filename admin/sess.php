<?php
	session_start();
	require_once __DIR__ . '/classes/store.php';
	
	$date = date("Y/m/d");
	//if($date === "2019/06/06"):
	//header("location:login.php");
	//endif;
	
	// Check if admin is logged in
	if(!isset($_SESSION['username'])):
		header("location:login.php");
		exit();
	endif;
	
	// Validate session timeout (30 minutes)
	if (!Store::validateSession(1800)) {
		session_unset();
		session_destroy();
		header("location:login.php?timeout=1");
		exit();
	}
?>
