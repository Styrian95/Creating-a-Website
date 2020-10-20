>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>PHP

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

>>>>>>>>>>>>>>>>>>>>>>HTML
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

>>>>>>>>>>>>>>>>>>>>SQL
    // Determines if a user login attempt succeeds or fails
    public function login($email, $password) {
        // Checks if the user is in the database
        $user = $this->getUser($email);
        // Cecks if a user got returned
        if ($user) {
            // Checks if the provides password matches
            if (password_verify($password, $user->password)) {
                // Returns the user
                return $user;
            } else {
                // Password didn't match
                return false;
            }
        } else {
            // email is not in the database
            return false;
        }
    }

    public function getUser($email) {
        // Prepare db-statements
        $sql = "SELECT * FROM t_user WHERE email =?";
        $stmt= $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $sqlResult = $stmt->get_result()->fetch_assoc(); 
        if (is_countable($sqlResult) && count($sqlResult) > 0) {
            // Creates an new user
            $user = new User();
            $user->userId = $sqlResult['user_id'];
            $user->courseId = $sqlResult['course_id'];
            $user->email = $sqlResult['email'];
            $user->password = $sqlResult['password'];
            $user->firstname = $sqlResult['firstname'];
            $user->lastname = $sqlResult['lastname'];
            $user->postalCode = $sqlResult['postal_code'];
            $user->city = $sqlResult['city'];
            $user->street = $sqlResult['street'];
        } else{
            return false;
        }
        // Returns the created user
        return $user;
    }
