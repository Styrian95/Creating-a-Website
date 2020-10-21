<?php 
require_once 'connection.php';
require_once 'session.php';

//Kurse holen
$sql = 'SELECT ID, Kursname, Kursbeschreibung, TRAINER_ID FROM kurs';
$stmt = $database_connection->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$Kurse = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$documentupload_error = '';

$error = false;
if (isset($_POST['upload'])) {

	if ((!isset($_FILES['datei'])) OR (!isset($_POST['course']))) {
		$documentupload_error = 'Füllen Sie alle Felder aus!';
		$error = true;
	}

	if (!$error) {
		//Upload-Verzeichnis für Dokumente, Unterverzeichnis Dokumente im Ordner wo diese Datei liegt
		$upload_folder = dirname(__FILE__) . '/Dokumente/'; 

		//erstellen, wenn nicht vorhanden
		if (!file_exists($upload_folder)) {
			mkdir($upload_folder, 0777, true);
		}

		$filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
		//leerzeichen im dateinamen durch underline ersetzen
		$filename = str_replace(" ","_",$filename);
		$extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
		 
		//Überprüfung der Dateiendung
		$allowed_extensions = array('pdf', 'jpg', 'jpeg', 'xls', 'doc', 'docx', 'ppt', 'png');
		if(!in_array($extension, $allowed_extensions)) {
			$documentupload_error = "Ungültige Dateiendung. Nur pdf, jpg, jpeg, xls, doc, docx und ppt-Dateien sind erlaubt";
			$error = true;
		}
	}

	if (!$error) {
		//Überprüfung der Dateigröße
		$max_size = 5000*1024; //5000 KB
		if($_FILES['datei']['size'] > $max_size) {
			$documentupload_error = "Bitte keine Dateien größer als 5 Megabyte hochladen!";
			$error = true;
		}
	}

	if (!$error) {
		//Pfad zum Upload
		$new_path = $upload_folder.$filename.'.'.$extension;
		 
		//Neuer Dateiname falls die Datei bereits existiert
		if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
			 $id = 0;
			 while (file_exists($new_path)) {
			 	$id++;
			 	$new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
			 }
			 $filename = $filename . '_' . $id;
		}

		//Alles okay, verschiebe Datei an neuen Pfad
		move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
		$documentupload_error = 'Datei erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';

		// Insert in Datenbank
		$sql = 'INSERT INTO dokument (KURS_ID, pfad, dokumentenname, dokumententyp) VALUES (?,?,?,?)';
		$stmt = $this->conn->prepare($sql);

		$stmt->bind_param("isss",$_POST['course'],$new_path,$filename,$extension);
		$stmt->execute();
		$dokumentID = $this->conn->insert_id;
		
		if (!$dokumentID) {
			$documentupload_error = 'Fehler beim Verarbeiten der Daten!';
			$error = true;
		}
		$stmt->close();
	}

	if (!$error) {
		unset($_POST);
		unset($_FILES);
		$documentupload_error = 'Dokument erfolgreich hochgeladen!';
	}
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	</head>
<body>
<!-- Navigation-Bar -->
<?php require_once 'navbar.php'; ?>
<div class="container">
<!-- Course information --> 
<h3 style="text-align: center;">
	Herzlich Willkommen im Uploadportal<br>
	<small class="text-muted">Hier können Sie eine neue Datei zu einem Kurs uploaden!</small><hr>
</h3>

<form action="upload.php" method="post" enctype="multipart/form-data">
	<div class="form-row">
		<div class="form-group col-md-8">
			<input type="file" class="custom-file-input" id="customFile" name="datei">
				<label class="custom-file-label" for="customFile">Datei auswählen</label>
		</div>

		<div class="form-group col-md-4">
			<select name="course" id="course" class="form-control">
				<option <?php  if (!isset($_POST['course'])) {echo 'selected';}?> disabled>Kurs auswählen ...</option>

					<?php
						foreach ($Kurse as $Kurs) {
							$selected = '';
							if (isset($_POST['course'])) {
								if ($_POST['course'] == $Kurs['ID']) {
									$selected = 'selected';
								}
							}
							echo '<option ' . $selected . ' value="' . $Kurs['ID'] . '"> ' . $Kurs['Kursname'] . '</option>';
						}
					?>
			</select>
		</div>
	</div>

<button type="submit" name="upload" class="btn btn-success btn btn-block">Hochladen</button>
<p><?php echo $documentupload_error;?></p>
    </div>
    
  </form>

</div>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

</body>
</html>