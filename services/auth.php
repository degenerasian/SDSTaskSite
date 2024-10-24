<?php
	require_once('db_config.php');
	session_start();
	
	//Check whether the session variable SESS_userid is present or not
	if(!isset($_SESSION['SESS_userid']) || (trim($_SESSION['SESS_userid']) == '')) {
		header("location: access-denied.php");
		exit();
	}
?>