<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,false);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>SeminarPortal</title>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

<body>
<?php require_once 'navbar.php'; ?>

<!-- Mainpage information --> 
<h3 style="text-align: center;">
	Herzlich Willkommen im SeminarPortal<br>
	<small class="text-muted">Nütze die Navigation für weiter Schritte ...</small><br><br>
	<small class="text-muted">Deine Session-ID ist: <?php echo session_id();?></small>
</h3>	
</body>