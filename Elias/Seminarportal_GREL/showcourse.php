<?php
require_once 'connection.php';
require_once 'session.php';
//user holen
$kurse = $database_connection->getallcourses();
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