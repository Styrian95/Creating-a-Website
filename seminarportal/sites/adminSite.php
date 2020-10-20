<?php
session_start();
require_once('../modules/config.php');
require_once('../classes/user.php');

// creates a new empty user Object
$user = new User();
/*
unsearialies the previous serialised user 
and saves it as the newly created user
*/
$user = unserialize($_SESSION['user']);
// Checks if the user is set respectively if the email is set
if (!$user || !$user->email) {
    // Asks the user to login if the userSite.php got accesed via the searchbar 
    die('Bitte zuerst einloggen: <a href="userLogin.php">User</a>  <a href="userLogin.php">Interal User</a>');
}
if ($user->isAdministrator != '1') {
    // Asks the user to login if the userSite.php got accesed via the searchbar 
    die('Kein Admin, Bitte zuerst einloggen als Admin einloggen: <a href="userLogin.php">Interal User</a>');
}
// Reads all courses from the database 
//$courses = $database->getAllCourses();
// Reads all documents, for the course, from the database 
//$documents = $database->getAllDocuments();
$allUsers = $database->getAllUsers();
$allInternalUsers = $database->getAllInternalUsers();

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Admin Area</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../src/css/navbar.css">
	<link rel="stylesheet" href="../src/css/styles.css">
</head>

<body>

	<!--navbar-->
	<div id="navbar">
		<ul>
		  <li><a class="active" href="adminSite.php">Admin Area</a></li>
		  <li><a href="createUser.php">User Erstellen</a></li>
		  <li><a href="createInternalUser.php">Internal User Erstellen</a></li>
		  <li style="float:right"><a href="logout.php">Logout</a></li>
		</ul>
	</div>



	<!--content-->
	<div style=" display: block;margin-left: auto;margin-right: auto;width: max-content;">
		<h2>Guten Tag, <?php echo $user->lastname, ' ', $user->firstname ?> , Sie sind nun angemeldet.</h2>
		<h3>Admin Area</h3>

		<h4>Alle User</h4>
		<table>
		  <tr>
		    <th>Vorname</th>
		    <th>Nachname</th>
		    <th>Kurs</th>
		  </tr>
		  	<?php foreach($allUsers as $user) { ?>
		  	<tr>
			    <td><?php echo $user->firstname ?></td>
			    <td><?php echo $user->lastname ?></td>
			    <td><?php echo $user->courseName ?></td>
		    </tr>
			<?php } ?>
		</table>

		<h4>Alle Internen User</h4>
		<table>
		  <tr>
		    <th>Vorname</th>
		    <th>Nachname</th>
		    <th>Ist Admin?</th>
		  </tr>
		  	<?php foreach($allInternalUsers as $internalUser) { ?>
		  	<tr>
			    <td><?php echo $internalUser->firstname ?></td>
			    <td><?php echo $internalUser->lastname ?></td>
			    <td><?php if($internalUser->isAdministrator == '1') {echo "JA"; } else { echo "NEIN"; } ?></td>
		    </tr>
			<?php } ?>
		</table>


	</div>





	<!--footer-->
	<footer>
		<p>LAP (C) Zwatz</p>
	</footer>

</body>


</html>