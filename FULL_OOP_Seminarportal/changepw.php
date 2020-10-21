<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,false);

$pwchange = '';
if (isset($_POST['setpassword'])) {
	$answer = $database_connection->updatePassword($_POST,$_SESSION['USER']->getUserName());
	$pwchange = $answer['message'];
}

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

<!-- Navigation-Bar -->
<?php require_once 'navbar.php'; ?>
<div class="container">

	<!-- Course information --> 
	<h3 style="text-align: center;">
		Herzlich Willkommen im Passwort-Portal<br>
		<small class="text-muted">Hier können Sie Ihr Passwort ändern!</small><hr>
	</h3>

	<!-- update password -->
	<form action="changepw.php" method="post">
		<div class="form-row">
			<div class="form-group col-md-4">
				<input type="password" name="oldpassword" class="form-control" id="oldpassword" placeholder="Altes Passwort">
			</div>

			<div class="form-group col-md-4">
				<input type="password" name="newpassword" class="form-control" id="newpassword" placeholder="Neues Passwort">
			</div>

			<div class="form-group col-md-4">
				<input type="password" name="confirmnewpassword" class="form-control" id="confirmnewpassword" placeholder="Neues Passwort bestätigen">
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-md-12">
				<button type="submit" name="setpassword" id="setpassword" class="btn btn-success btn btn-block">Passwort ändern</button>
			</div>
		</div>
		
			<!-- Errorhandling -->
			<p id="pwchange-error"><?php echo $pwchange;?></p>
</div>
</body>
</html>