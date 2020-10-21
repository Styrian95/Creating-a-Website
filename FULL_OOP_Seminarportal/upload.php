<?php 
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,true);

//Kurse holen
$Kurse = $database_connection->getAllCourses();
$documentupload_error = '';

if (isset($_POST['upload'])) {
	
	$answer = $database_connection->uploadFile($_POST);
	$documentupload_error = $answer['message'];
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<!-- Database settings-->
	</head>
<body>
<!-- Navigation-Bar -->
<?php require_once 'navbar.php'; ?>
<div class="container">
<!-- Course information --> 
<h3 style="text-align: center;">
	Herzlich Willkommen im Uploadportal<br>
	<small class="text-muted">Hier können Sie eine neue Datei zu einem Kurs uploaden!</small><hr>
</h3>

<form action="upload.php" method="post" enctype="multipart/form-data">
	<div class="form-row">
		<div class="form-group col-md-8">
			<input type="file" class="custom-file-input" id="customFile" name="datei">
				<label class="custom-file-label" for="customFile">Datei auswählen</label>
		</div>

		<div class="form-group col-md-4">
			<select name="course" id="course" class="form-control">
				<option <?php  if (!isset($_POST['course'])) {echo 'selected';}?> disabled>Kurs auswählen ...</option>

					<?php
						foreach ($Kurse as $Kurs) {
							$selected = '';
							if (isset($_POST['course'])) {
								if ($_POST['course'] == $Kurs['ID']) {
									$selected = 'selected';
								}
							}
							echo '<option ' . $selected . ' value="' . $Kurs['ID'] . '"> ' . $Kurs['Kursname'] . '</option>';
						}
					?>
			</select>
		</div>
	</div>

<button type="submit" name="upload" class="btn btn-success btn btn-block">Hochladen</button>
<p><?php echo $documentupload_error;?></p>
    </div>
    
  </form>

</div>

<?php

	$dokumente = $database_connection->getAllDocuments();

	echo '
	<div class="container">
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Dokument<
					<th scope="col">Link zu Dokument</th>
					<th scope="col">Kurs</th>
				</tr>
			</thead>

			<tbody>';

			$a = 1;
			foreach ($dokumente as $dokument) {
				echo '<tr>';
				echo '<th scope="row">'. $a . '</th>';
				echo '<td>' . $dokument['dokumentenname'] . ' (.' . $dokument['dokumententyp'] . ')</td>';
				$dokumentenlink = $dokument['pfad'] . '/' . $dokument['dokumentenname'] . '.' . $dokument['dokumententyp'];
				echo '<td><a target="_blank" href="' . $dokumentenlink . '">&Ouml;FFNEN</td>';
				echo '<td>' . $dokument['Kursname'] . '</td>';
				echo '</tr>';
				$a++;
			}

		echo '</tbody>
		</table>
	</div>';

?>

<script src="js/functions.js"></script> 
</body>
</html>