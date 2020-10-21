<?php
require_once 'connection.php';
require_once 'session.php';
//dokumente holen
$dokumente = $database_connection->getuserdocument();

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
	<h3 style="text-align: center;">
		Herzlich Willkommen im Kursportal<br>
	</h3>

<?php
	echo '
	<!-- table with course details -->
	<div class="container">
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Dokument</th>
					<th scope="col">Link zu Dokument</th>
					<th scope="col">Download</th>
				</tr>
			</thead>

			<tbody>';

			$num = 1;
			foreach ($dokumente as $dokument) {
				echo '<tr>';
				echo '<th scope="row">'. $num . '</th>';
				echo '<td>' . $dokument['dokumentenname'] . ' (.' . $dokument['dokumententyp'] . ')</td>';
				$dokumentenlink = $dokument['pfad'];
				echo '<td><a target="_blank" href="' . $dokumentenlink . '">&Ouml;FFNEN</td>';
				echo '<td><a target="_blank" download href="' . $dokumentenlink . '">DOWNLOAD</td>';
				echo '</tr>';
				$num++;
			}

		echo '</tbody>
		</table>
	</div>';
?>

</body>
</html>