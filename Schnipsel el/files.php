<?php
if (isset($_POST['BUTTONNAME'])) {
	print_r($_FILES['dateiname']);

	$upload_folder = dirname(__FILE__) . '/Dokumente/'; 

	//erstellen, wenn nicht vorhanden

	if (!file_exists($upload_folder)) {
		mkdir($upload_folder, 0777, true);
	}

	$filename = pathinfo($_FILES['dateiname']['name'], PATHINFO_FILENAME);

	//leerzeichen im dateinamen durch underline ersetzen

	$filename = str_replace(" ","_",$filename);
	$extension = strtolower(pathinfo($_FILES['dateiname']['name'], PATHINFO_EXTENSION));
	 
	//Überprüfung der Dateiendung

	$allowed_extensions = array('pdf', 'jpg', 'jpeg', 'xls', 'doc', 'docx', 'ppt', 'png');
	if(!in_array($extension, $allowed_extensions)) {
		$documentupload_error = "Ungültige Dateiendung. Nur pdf, jpg, jpeg, xls, doc, docx und ppt-Dateien sind erlaubt";
	}


	//Überprüfung der Dateigröße

	$max_size = 5000*1024; //5000 KB
	if($_FILES['dateiname']['size'] > $max_size) {
		$documentupload_error = "Bitte keine Dateien größer als 5 Megabyte hochladen!";
	}


if (!$error) {
	//Pfad zum Upload
	$new_path = $upload_folder.$filename.'.'.$extension;
	 
	//Neuer Dateiname falls die Datei bereits existiert
	//Falls Datei existiert, hänge eine Zahl an den Dateinamen
	if(file_exists($new_path)) { 
		 $id = 0;
		 while (file_exists($new_path)) {
		 	$id++;
		 	$new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
		 }
		 $filename = $filename . '_' . $id;
	}

	//Alles okay, verschiebe Datei an neuen Pfad
	
	move_uploaded_file($_FILES['dateiname']['tmp_name'], $new_path);
	$documentupload_error = 'Datei erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';
}

?>


<form action="files.php" method="post" enctype="multipart/form-data">
	<div class="form-row">
		<div class="form-group col-md-8">
			<input type="file" class="custom-file-input" id="customFile" name="dateiname">
				<label class="custom-file-label" for="customFile">Datei auswählen</label>
		</div>
	</div>

	<div class="form-row">
		<button type="submit" name="BUTTONNAME" class="btn btn-success btn-block">BUTTON</button>
	</div>

</form>