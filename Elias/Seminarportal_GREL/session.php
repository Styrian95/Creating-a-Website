<?php
session_start();
	if(!$_SESSION['userid']) {
		header("Location: userlogin.php");
	} 
?>