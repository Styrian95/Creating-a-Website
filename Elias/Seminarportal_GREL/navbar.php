<?php
require_once 'connection.php';
?>


<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
	<a class="navbar-brand" href="index.php">SeminarPortal</a>
		<ul class="navbar-nav mr-auto">

			<?php 

				if ($_SESSION['admin']) {
					echo '<li class="nav-item">
						<a class="nav-link" href="createuser.php">User erstellen</a>
					</li>';

					echo '<li class="nav-item">
						<a class="nav-link" href="showuser.php">User anzeigen</a>
					</li>';

					echo '<li class="nav-item">
						<a class="nav-link" href="showcourse.php">Kurse anzeigen</a>
					</li>';

					echo '<li class="nav-item">
						<a class="nav-link" href="showdocuments.php">Dokumente anzeigen</a>
					</li>';

				} else {
					echo '<li class="nav-item">
						<a class="nav-link" href="course.php">Mein Kurs</a>
					</li>';
				}

			?>
		</ul>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<span class="navbar-text">Hallo, <?php echo $_SESSION['username']?></span>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="logout.php"></span>Logout</a>
			</li>
		</ul>
</nav>
