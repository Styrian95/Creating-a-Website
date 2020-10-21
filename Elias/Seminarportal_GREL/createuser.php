<?php
require_once 'connection.php';
require_once 'session.php';
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
	Herzlich Willkommen im Userportal<br>
	<small class="text-muted">Hier können User und Trainer angelegt werden!</small><hr>
</h3>

<!-- create new user -->
<form name="createuser.php" action="createuser.php" method="post">
	<div class="form-row">
		<div class="form-group col-md-6">
			<input type="text" name="firstname" class="form-control" id="firstname" placeholder="Vorname">
		</div>

		<div class="form-group col-md-6">
			<input type="text" name="lastname" class="form-control" id="lastname" placeholder="Nachname">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-6">
			<input type="text" name="username" class="form-control" id="username" placeholder="Username">
		</div>

		<div class="form-group col-md-3">
			<input type="password" name="userpassword1" class="form-control" id="userpassword1" placeholder="Passwort">
		</div>

		<div class="form-group col-md-3">
			<input type="password" name="userpassword2" class="form-control" id="userpassword2" placeholder="Passwort">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-3">
			<input type="text" name="street" class="form-control" id="street" placeholder="Straße">
		</div>

		<div class="form-group col-md-3">
			<input type="text" name="housenumber" class="form-control" id="housenumber" placeholder="Hausnummer">
		</div>

		<div class="form-group col-md-2">
			<input type="number" name="zipcode" class="form-control" id="zipcode" placeholder="Postleitzahl">
		</div>

		<div class="form-group col-md-2">
			<input type="text" name="city" class="form-control" id="city" placeholder="Ort">
		</div>

		<div class="form-group col-md-2">
			<input type="number" name="userkurs" class="form-control" id="userkurs" placeholder="Seminar-ID:">
		</div>
	</div>

<b>Admin</b>
<div class="radio">
  <label><input type="radio" value="0" name="admin" checked> NEIN</label>
</div>
<div class="radio">
  <label><input type="radio" value ="1" name="admin"> JA</label>
</div>

<b>Trainer</b>
<div class="radio">
  <label><input type="radio" value="0" name="trainer" checked> NEIN</label>
</div>
<div class="radio">
  <label><input type="radio" value ="1" name="trainer"> JA</label>
</div>

	<div class="form-row">
		<div class="form-group col-md-6">
			<button type="submit" name="createuser" id="createuser" class="btn btn-success btn btn-block">User erstellen</button>
		</div>
	</div>
</div>
</form>
</div>
</body>

<?php
	if(isset($_POST['createuser']))
	{
        $db = new dbConnection();
		$db->adduser($_POST['userkurs'],$_POST['username'],$_POST['userpassword1'],$_POST['admin'],$_POST['trainer'],$_POST['firstname'],$_POST['lastname'],$_POST['street'],$_POST['housenumber'],$_POST['zipcode'],$_POST['city']);
	}
?>
</html>