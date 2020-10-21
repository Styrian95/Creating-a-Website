<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

$userlogin_error = '';
if (isset($_POST['userlogin'])) {
	$answer = $database_connection->LoginUser($_POST,false);
	if ($answer['success'] == true) {
		$user = $answer['user'];
		$_SESSION['USER'] = new User($user[0],$user[1],$user[2],$user[3],$user[4]);
	};
	$userlogin_error = $answer['message'];
}

$database_connection->CheckValidLogin(true,false,"index.php");


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>SeminarPortal</title>
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<!-- Fontawesome Icons-->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		
		<link href="css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="register">
			<h1>User - Login</h1>
			<form action="userlogin.php" method="post" autocomplete="off">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required value="<?php 
				if (isset($_POST['username'])) {
					if (trim($_POST['username']) != '') {
						echo $_POST['username'];
					}
				} 
				?>">
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Register" name="userlogin">
				<p><?php echo $userlogin_error;?></p>
			</form>
		</div>
	</body>
</html>

