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
// Reads all courses from the database 
$course = $database->getCourse($user->courseId);
// Reads all documents, for the course, from the database 
$documents = $database->getDocuments($course->courseId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home User</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../src/css/navbar.css">
	<link rel="stylesheet" href="../src/css/styles.css">
</head>

<body>

	<!--navbar-->
	<div id="navbar">
		<ul>
		  <li><a class="active" href="userSite.php">Home User</a></li>
		  <li style="float:right"><a href="logout.php">Logout</a></li>
		</ul>
	</div>



	<!--content-->
	<div style=" display: block;margin-left: auto;margin-right: auto;width: max-content;">
		<h2>Guten Tag, <?php echo $user->lastname, ' ', $user->firstname ?> , Sie sind nun angemeldet.</h2>
		<h3>Sie sind für folgenden Kurs angemeldet: <?php echo $course->name ?> </h3>
		<table style="width:100%">
            <tr style="text-align: left;">
                <th>Dateiname</th>
                <th>Datei</th>
            </tr>
            <?php
            // creates one entry in the html for each object in the courses array
            foreach ($documents as &$document) {
            ?>
            <tr>
                <td><?php echo $document->displayName ?></td>
                <td><a href="../<?php echo $document->path ?>">download</a></td>
            </tr>
            <?php
            }
            ?>
        </table>
	</div>





	<!--footer-->
	<footer>
		<p>LAP (C) Zwatz</p>
	</footer>

</body>


</html>