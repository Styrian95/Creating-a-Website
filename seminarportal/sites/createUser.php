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
    die('Kein Zutritt, Bitte zuerst einloggen als Admin einloggen: <a href="userLogin.php">Interal User</a>');
} else {
	if(isset($_GET['register'])) {
		$newUser = new User;

		$error = false;
		$newUser->email = $_POST['email'];
		$newUser->password = $_POST['password'];
		$newUser->firstname = $_POST['firstname'];
		$newUser->lastname = $_POST['lastname'];
		$newUser->postalCode = $_POST['postalCode'];
		$newUser->city = $_POST['city'];
		$newUser->street = $_POST['street'];
		$newUser->courseId = $_POST['courseId'];
		$passwordConfirm = $_POST['passwordConfirm'];

        // Checks if the given email is a valide email (formwise)
        if (!filter_var($newUser->email, FILTER_VALIDATE_EMAIL)) {
            // Sets the errorMessage variable 
            $errorMessage = 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
            $error = true;
        }

        // Checks if the given email has at least one char
        if (strlen($newUser->password) == 0) {
            // Sets the errorMessage variable 
            $errorMessage = 'Bitte ein password angeben<br>';
            $error = true;
        }

		if($newUser->password != $passwordConfirm) {

			$error = true;
			$errorMessage = 'Die Passwörter müssen übereinstimmen<br>';
		}

		if(!$error){

			$databaseUser = $database->getUser($newUser->email);

			if(!$databaseUser) {
				$result = $database->createUser($newUser);

				if($result) {
					 $message = 'User wurde erfolgreich registriert.</a>';
					} else{
						$errorMessage = 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
					}
			}
		}


	}
}
// Reads all courses from the database 
$courses = $database->getAllCourses();
// Reads all documents, for the course, from the database 
//$documents = $database->getAllDocuments();



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>User Erstellen</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../src/css/navbar.css">
	<link rel="stylesheet" href="../src/css/styles.css">
</head>

<body>

	<!--navbar-->
	<div id="navbar">
		<ul>
		  <li><a href="adminSite.php">Admin Area</a></li>
		  <li><a class="active" href="createUser.php">User Erstellen</a></li>
		  <li><a href="createInternalUser.php">Internal User Erstellen</a></li>
		  <li style="float:right"><a href="logout.php">Logout</a></li>
		</ul>
	</div>



	<!-- Div that centers the displayed register form -->
    <div style=" display: block;margin-left: auto;margin-right: auto;width: max-content;">
        <form action="?register=1" method="post">
            E-Mail:<br>
            <input type="email" size="40" maxlength="250" name="email"><br><br>
            Dein Password:<br>
            <input type="password" size="40" maxlength="250" name="password"><br>
            Password wiederholen:<br>
            <input type="password" size="40" maxlength="250" name="passwordConfirm"><br><br>
            Vorname:<br>
            <input type="text" size="40" maxlength="250" name="firstname"><br><br>
            Nachname:<br>
            <input type="text" size="40" maxlength="250" name="lastname"><br><br>
            Postleitzahl:<br>
            <input type="text" size="40" maxlength="250" name="postalCode"><br><br>
            Stadt:<br>
            <input type="text" size="40" maxlength="250" name="city"><br><br>
            Strasse:<br>
            <input type="text" size="40" maxlength="250" name="street"><br><br>
            Kurs<br>
            <select name="courseId">
            	<?php foreach($courses as $course){ ?> 
            		<option value="<?php echo $course->courseId ?>"><?php echo $course->name ?></option>"  <?php } ?>
            </select><br><br>
            <input type="submit" value="Abschicken">
        </form>

	<?php
    // Displays an error message on the site if one is set 
    if (isset($errorMessage)) {
        echo $errorMessage;
    }

    // Displays a message on the site if one is set 
    if (isset($message)) {
        echo $message;
    }
    ?>

    </div>





	<!--footer-->
	<footer>
		<p>LAP (C) Zwatz</p>
	</footer>

</body>


</html>