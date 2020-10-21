<?php 
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$admin_required = false;
if ($_SESSION['USER']->getUserAdminStatus() !== NULL) {
	$admin_required = $_SESSION['USER']->getUserAdminStatus();
}

session_destroy();
$database_connection->RedirectToLogin($admin_required);
die;
?>