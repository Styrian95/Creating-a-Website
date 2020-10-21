<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$database_connection->CheckValidLogin(false,false);

$kurstext = '';
$nokurs = false;
if (!$_SESSION['USER']->getUserKurs()) {
	$kurstext = 'Sie sind in keinem Kurs angemeldet';
	$nokurs = true;
} else {
	$kurs = $database_connection->getSingleCourse($_SESSION['USER']->getUserKurs());
	$kurstext = 'Sie sind im Kurs >' . $kurs['Kursname'] . '< angemeldet!';
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

<!-- Course information --> 
<h3 style="text-align: center;">
	Herzlich Willkommen im Kursportal<br>
	<small class="text-muted"><?php echo $kurstext;?></small><br><br>
</h3>

<?php
if (!$nokurs) {
	$dokumente = $database_connection->getDocumentsFromCourse($_SESSION['USER']->getUserKurs());

	echo '
	<!-- table with course details -->
	<div class="container">
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Dokument</th>
					<th scope="col">Link zu Dokument</th>
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
				echo '</tr>';
				$a++;
			}

		echo '</tbody>
		</table>
	</div>';
}

?>

</body>
</html>