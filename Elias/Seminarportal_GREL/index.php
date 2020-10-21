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
	<?php
		require_once 'navbar.php';
	?>
	<h3 style="text-align: center;">
		Herzlich Willkommen im SeminarPortal<br>
		<small class="text-muted">Nütze die Navigation für weiter Schritte ...</small><br><br>
		<p>Username:<?php echo $_SESSION['username']?></p><br>
		<p>Kurs:<?php echo $_SESSION['userkurs']?></p><br>
		<p>Admin:<?php echo $_SESSION['admin']?></p><br>
	</h3>	
</body>
</html>