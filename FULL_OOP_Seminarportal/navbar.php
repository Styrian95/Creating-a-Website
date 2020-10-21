<?php
require_once 'phpinclude/user.php';
require_once 'phpinclude/session.php';
require_once 'phpinclude/connection.php';

?>


<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	<a class="navbar-brand" href="index.php">SeminarPortal</a>
		<ul class="navbar-nav mr-auto">

			<?php 

				if ($_SESSION['USER']->getUserAdminStatus()) {
					echo '<li class="nav-item">
						<a class="nav-link" href="users.php">Userportal</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="admincourse.php">Kursportal</a>
					</li>';

					echo '<li class="nav-item">
						<a class="nav-link" href="course.php">Mein Kurs</a>
					</li>';

				} else {
					echo '<li class="nav-item">
						<a class="nav-link" href="course.php">Mein Kurs</a>
					</li>';
				}

				echo '<li class="nav-item">
						<a class="nav-link" href="changepw.php">Passwort ändern</a>
					</li>';

			?>
		</ul>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<span class="navbar-text">Hallo, <?php echo $_SESSION['USER']->getUserName()?></span>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="logout.php"></span>Logout</a>
			</li>
		</ul>
</nav>
