<?php
require_once 'connection.php';
CheckValidLogin(false,true);

$kurscreation_error = '';
if (isset($_POST['createcourse'])) {
	$error = false;
	if ((!isset($_POST['coursename'])) OR (!isset($_POST['coursedescription']))) {
		$kurscreation_error = 'Füllen Sie alle Felder aus!';
		$error = true;
	}

	if (!$error) {
		$_POST['coursename'] = trim($_POST['coursename']);
		$_POST['coursedescription'] = trim($_POST['coursedescription']);

		if ((strlen($_POST['coursename']) == 0) OR (strlen($_POST['coursedescription']) == 0)) {
			$kurscreation_error = 'Füllen Sie alle Felder aus!';
			$error = true;
		}
	}

	if (!$error) {
 
		$sql = 'INSERT INTO kurs (trainer_id, Kursname, Kursbeschreibung) VALUES (?,?,?)';
		$stmt = $database_connection->prepare($sql);

		$stmt->bind_param("iss",$_POST['coursetrainer'],$_POST['coursename'],$_POST['coursedescription']);
		$stmt->execute();

		$kursid = $database_connection->insert_id;
		
		if (!$kursid) {
			$kurscreation_error = 'Fehler beim Verarbeiten der Daten!';
			$error = true;
		}
		$stmt->close();
	}

	if (!$error) {
		if ($_POST['coursetrainer']) {
			$sql = 'UPDATE user SET kurs_id = ? WHERE ID = ?';
			$stmt = $database_connection->prepare($sql);
			$stmt->bind_param("ii", $kursid,$_POST['coursetrainer']);
			$stmt->execute();
		}
	}

	if (!$error) {
		unset($_POST);
		$kurscreation_error = 'Kurs erfolgreich angelegt!';
	}
}

//kurse holen
$sql = 'SELECT kurs.Kursname, kurs.Kursbeschreibung, user.nachname, user.vorname FROM kurs LEFT OUTER JOIN user ON kurs.trainer_id = user.id';
$stmt = $database_connection->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$kurse = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

function GetTextPostValue($Postname) {
	if (isset($_POST[$Postname])) {
		if (trim($_POST[$Postname]) != '') {
			echo $_POST[$Postname];
		}
	} 
}

$sql = 'SELECT ID, nachname, vorname FROM user WHERE trainer = 1 AND kurs_id IS NULL';
$stmt = $database_connection->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$Trainers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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