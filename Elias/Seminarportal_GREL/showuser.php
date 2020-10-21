<?php
require_once 'connection.php';
require_once 'session.php';
//user holen
$users = $database_connection->getallusers();
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

<form name="showuser.php" action="showuser.php" method="post">
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

			$rownumber = 1;
			foreach ($users as $user) {
				echo '<tr>';

					echo '<th scope="row">' . $rownumber . '</th>';
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

					$rownumber++;
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
</div>
</div>
</body>
</html>