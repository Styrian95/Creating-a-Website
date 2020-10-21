<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,true);

//kurse holen
$Kurse = $database_connection->getAllCourses();

$MIN_PASSWORD_LENGTH = 10;
$usercreation_error = '';
//user anlegen
if (isset($_POST['createuser'])) {
	$answer = $database_connection->AddUser($_POST);
	$usercreation_error = $answer['message'];
}

//user holen
$users = $database_connection->getAllUsers();

function GetTextPostValue($Postname) {
	if (isset($_POST[$Postname])) {
		if (trim($_POST[$Postname]) != '') {
			echo $_POST[$Postname];
		}
	} 
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
		<!-- Database settings-->
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
<form name="users.php" action="users.php" method="post">
	<div class="form-row">
		<div class="form-group col-md-6">
			<input type="text" name="firstname" class="form-control" id="firstname" placeholder="Vorname" value="<?php GetTextPostValue('firstname');?>">
		</div>

		<div class="form-group col-md-6">
			<input type="text" name="lastname" class="form-control" id="lastname" placeholder="Nachname" value="<?php GetTextPostValue('lastname');?>">
		</div>
	</div>

	<div class="form-row">
		<div class="form-group col-md-6">
			<input type="text" name="username" class="form-control" id="username" placeholder="Username" value="<?php GetTextPostValue('username');?>">
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
			<input type="text" name="street" class="form-control" id="street" placeholder="Straße" value="<?php GetTextPostValue('street');?>">
		</div>

		<div class="form-group col-md-3">
			<input type="text" name="housenumber" class="form-control" id="housenumber" placeholder="Hausnummer" value="<?php GetTextPostValue('housenumber');?>">
		</div>

		<div class="form-group col-md-3">
			<input type="number" name="zipcode" class="form-control" id="zipcode" placeholder="Postleitzahl" onblur="checkzipcode()" value="<?php GetTextPostValue('zipcode');?>">
		</div>

		<div class="form-group col-md-3">
			<input type="text" name="city" class="form-control" id="city" placeholder="Ort" value="<?php GetTextPostValue('city');?>">
		</div>
	</div>

	<div class="form-row align-items-center">
		<div class="form-group col-md-4">

				<select name="kurs" id="inputState" class="form-control">
					<option <?php  if (!isset($_POST['kurs'])) {echo 'selected';}?> disabled>Kurs auswählen ...</option>

					<?php
						foreach ($Kurse as $Kurs) {
							$selected = '';
							if (isset($_POST['kurs'])) {
								if ($_POST['kurs'] == $Kurs['ID']) {
									$selected = 'selected';
								}
							}

							echo '<option ' . $selected . ' value="' . $Kurs['ID'] . '">' . $Kurs['Kursname'] . '</option>';
						}
					?>

				</select>
		</div>

		<div class="form-group col-md-1">
			<div class="form-check">
					<?php
						$checked = '';
						if (isset($_POST['trainer'])) {
							$checked = 'checked';
						}
					?>

				<input class="form-check-input" name="trainer" type="checkbox" id="trainer" <?php echo $checked;?>>
				<label class="form-check-label" for="trainer">Trainer</label>
			</div>
		</div>

		<div class="form-group col-md-1">
			<div class="form-check">
					<?php
						$checked = '';
						if (isset($_POST['admin'])) {
							$checked = 'checked';
						}
					?>
				<input class="form-check-input" name="admin" type="checkbox" id="admin" <?php echo $checked;?>>
				<label class="form-check-label" name="admin" for="admin">Admin</label>
			</div>
		</div>

		<div class="form-group col-md-6">
			<button type="submit" name="createuser" id="createuser" class="btn btn-success btn btn-block">User erstellen</button>
		</div>
		<p id="usercreation-error"><?php echo $usercreation_error;?></p>
</div>


<hr>
</form>
<!-- table with course details -->
<div class="container">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Vorname</th>
				<th scope="col">Nachname</th>
				<th scope="col">Im Kurs</th>
				<th scope="col">Admin</th>
				<th scope="col">Trainer</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$a = 1;
			foreach ($users as $user) {
				echo '<tr>';

					echo '<th scope="row">' . $a . '</th>';
					echo '<td>' . $user['vorname'] . '</td>';
					echo '<td>' . $user['nachname'] . '</td>';
					echo '<td>' . $user['kursname'] . '</td>';

					$admin = '';
					$trainer = '';

					if ($user['admin'] == 1) {
						$admin = 'x';
					}

					if ($user['trainer'] == 1) {
						$trainer = 'x';
					}

					echo '<td>' . $admin . '</td>';
					echo '<td>' . $trainer . '</td>';

					$a++;
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
</div>
</div>
</body>

 <script src="js/functions.js"></script> 

</html>