<?php
session_start();
require_once('../modules/config.php');

// Checks if the login form is submited
if (isset($_GET['login'])) {
    // Sets up the needed variables 
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validates the users provided and stores the user 
    $user = $database->login($email, $password);

    // Checks if the user is set and not false and redirects to the userSite.php 
    if ($user && $user !== false) {
        // Saves the user class as an serealized value
        $_SESSION['user'] = serialize($user);
        header("Location: ./userSite.php", true, 301);
    } else {
        // Dispays an error if the user is invalide 
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>User Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../src/css/navbar.css">
	<link rel="stylesheet" href="../src/css/styles.css">
</head>

<body>

	<!--navbar-->
	<div id="navbar">
		<ul>
		  <li><a  href="../index.html">Startseite</a></li>
		  <li><a class="active" href="userlogin.php">Login</a></li>
		  <li style="float:right"><a href="internallogin.php">Login Internal</a></li>
		</ul>
	</div>



	<!--content-->
	<div id="content">
		<h2>User Login</h2>
		    <!-- Div that centers the displayed login form -->
	    <div style=" display: block;margin-left: auto;margin-right: auto;width: max-content;">
	        <form action="?login=1" method="post">
	            E-Mail:<br>
	            <input type="email" size="40" maxlength="250" name="email"><br><br>
	            Dein Passwort:<br>
	            <input type="password" size="40" maxlength="250" name="password"><br><br>
	            <input type="submit" value="Abschicken">
	        </form>

	    
	        <br>
		<?php
	    // Displays an error message on the top of the site if one is set 
	    if (isset($errorMessage)) {
	        echo $errorMessage;
	    }
	    ?>
	    </div>

	</div>




	<!--footer-->
	<footer>
		<p>LAP (C) Zwatz</p>
	</footer>

</body>


</html>