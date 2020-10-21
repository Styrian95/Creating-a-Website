<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,true);

$kurscreation_error = '';
if (isset($_POST['createcourse'])) {
	$answer = $database_connection->AddCourse($_POST,true);
	$kurscreation_error = $answer['message'];
}

//kurse holen
$kurse = $database_connection->getAllCourses();

//trainer holen
$Trainers = $database_connection->getAllTrainers();

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
	</head>

<body>

<!-- Navigation-Bar -->
<?php require_once 'navbar.php'; ?>
<div class="container">
<!-- Course information --> 
<h3 style="text-align: center;">
	Herzlich Willkommen im Admin-Kursportal<br>
	<small class="text-muted">Hier können neue Kurse angelegt werden!</small><hr>
</h3>

<!-- create new course -->
<form action="admincourse.php" method="post">
	<div class="form-row">
		<div class="form-group col-md-4">
			<input type="text"  class="form-control" id="course" name="coursename" placeholder="Kursname" value="<?php GetTextPostValue('coursename');?>">
		</div>

		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="coursedesc" name="coursedescription" placeholder="Kursbeschreibung" value="<?php GetTextPostValue('coursedescription');?>">
		</div>

		<div class="form-group col-md-4">
			<select name="coursetrainer" id="trainer" class="form-control">
				<option <?php  if (!isset($_POST['coursetrainer'])) {echo 'selected';}?> disabled>Trainer auswählen ...</option>

					<?php
						foreach ($Trainers as $Trainer) {
							$selected = '';
							if (isset($_POST['coursetrainer'])) {
								if ($_POST['coursetrainer'] == $Trainer['ID']) {
									$selected = 'selected';
								}
							}

							echo '<option ' . $selected . ' value="' . $Trainer['ID'] . '">' . $Trainer['nachname'] . ' ' . $Trainer['vorname'] . '</option>';
						}
					?>
			</select>
		</div>
	</div>

	<div class="form-row">
		<button type="submit" name="createcourse" class="btn btn-success btn btn-block">Kurs erstellen</button>
	</div>
	<p><?php echo $kurscreation_error;?></p>
<hr>
</form>
<!-- table with course details -->
<div class="container">
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">#</th>
				<th scope="col">Kurs</th>
				<th scope="col">Kursbeschreibung</th>
				<th scope="col">Trainer</th>
			</tr>
		</thead>
		<tbody>
	<?php

	$a = 1;
	foreach ($kurse as $kurs) {
		echo '<tr>';

			echo '<th scope="row">' . $a . '</th>';
			echo '<td>' . $kurs['Kursname'] . '</td>';
			echo '<td>' . $kurs['Kursbeschreibung'] . '</td>';

			$trainer = trim($kurs['nachname'] . ' ' . $kurs['vorname']);

			if (strlen($trainer) == 0) {
				$trainer = 'KEIN TRAINER';
			}

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
</html>