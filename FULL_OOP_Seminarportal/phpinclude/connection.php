<?php

class Database{
	private $DATABASE_HOST;
	private $DATABASE_USER;
	private $DATABASE_PASS;
	private $DATABASE_NAME;
	public $mysqli;

	public function __construct() {
		$this->db_connect();
	}

	private function db_connect(){
		$this->host = 'localhost';
		$this->user = 'root';
		$this->pass = '';
		$this->db = 'Seminarportal';

		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
		return $this->mysqli;
	}

////////////////////////////////////////////////////////////////////////
//PRIVATE
////////////////////////////////////////////////////////////////////////

	private function SendErrorMessage($message) {
		return array('success' => false, 'message' => $message);
	}

////////////////////////////////////////////////////////////////////////

//!!!!!!!!!!!!!!!!!!!!!
	//binden von einem ganzen array geht NUR AB PHP 5.6

	//eine zeile
	private function select($sql,$bind_string,$bind_array){
		$stmt = $this->mysqli->prepare($sql);

		if ($bind_array) {
			$stmt->bind_param($bind_string, ...$bind_array);
		}
		$stmt->execute();
		$result = $stmt->get_result();

		$assoc_array = $result->fetch_assoc();
		return $assoc_array;
	}

////////////////////////////////////////////////////////////////////////

	//mehrere zeilen -> immer zweidimensionales array
	private function selectMultiple($sql,$bind_string,$bind_array){
		$stmt = $this->mysqli->prepare($sql);

		if ($bind_array) {
			$stmt->bind_param($bind_string, ...$bind_array);
		}
		
		$stmt->execute();
		$result = $stmt->get_result();

		$assoc_array = $result->fetch_all(MYSQLI_ASSOC);
		return $assoc_array;
	}

////////////////////////////////////////////////////////////////////////

	//Für Update, Delete, ... -> kein return value
	private function sendsql($sql,$bind_string,$bind_array){
		$stmt = $this->mysqli->prepare($sql);

		$stmt->bind_param($bind_string, ...$bind_array);
		$stmt->execute();
		return true;
	}

////////////////////////////////////////////////////////////////////////

	private function getInsertId() {
		return $this->mysqli->insert_id;
	}

////////////////////////////////////////////////////////////////////////
//PUBLIC
////////////////////////////////////////////////////////////////////////

	public function RedirectToLogin($admin_required = false) {
		if ($admin_required) {
			header("Location: adminlogin.php");
		} else {
			header("Location: userlogin.php");
		}
	}

////////////////////////////////////////////////////////////////////////

	public function CheckValidLogin($check_only = false,$admin_required = false, $redirect_success = false) {
		if (!isset($_SESSION['USER'])) {
			if (!$check_only) {
				session_destroy();
				RedirectToLogin($admin_required);
				die;
			}
		} else {
			$sql = 'SELECT ID, admin FROM user WHERE (username = ?)';

			$bind_array = array($_SESSION['USER']->getUserName());
			$user = $this->select($sql,"s",$bind_array);

			if(count($user) === 0) {
				session_destroy();
				RedirectToLogin($admin_required);
				die;
			}

			if (!password_verify($user['ID'], $_SESSION['USER']->getUserID())) {
				session_destroy();
				RedirectToLogin($admin_required);
				die;
			}

			if ($admin_required) {
				if ($user['admin'] != $admin_required) {
					session_destroy();
					RedirectToLogin($admin_required);
					die;
				}
			}

			if ($redirect_success) {
				header("Location: " . $redirect_success);
			}

		}
	}

////////////////////////////////////////////////////////////////////////

	public function LoginUser($array,$admin_required) {

		$error = false;
		if ((!isset($array['username'])) OR (!isset($array['password']))) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$array['username'] = trim($array['username']);
		$array['password'] = trim($array['password']);

		if ((strlen($array['username']) == 0) OR (strlen($array['password']) == 0)) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$sql = 'SELECT ID, KURS_ID, username, userpassword, trainer FROM user WHERE (username = ?) AND (admin = ?)';

		$bind_array = array($array['username'],$admin_required);
		$user = $this->select($sql,"si",$bind_array);

		if(!$user === 0) {
			$adminlogin_error = 'Keine Übereinstimmung von Usernamen und Passwort!';
			return $this->SendErrorMessage('Keine Übereinstimmung von Usernamen und Passwort!');
		}

		$password_hash = $user['userpassword'];
		if (!password_verify($array['password'], $password_hash)) {
			return $this->SendErrorMessage('Keine Übereinstimmung von Usernamen und Passwort!');
		}

		$user = array(password_hash($user['ID'],PASSWORD_DEFAULT),$user['username'],$user['KURS_ID'],$admin_required,$user['trainer']);
		return array('success' => true, 'message' => '','user' => $user);
	}

////////////////////////////////////////////////////////////////////////

	public function AddUser($array) {

		$MIN_PASSWORD_LENGTH = 10;

		if ((!isset($array['firstname'])) OR (!isset($array['lastname'])) OR (!isset($array['username'])) OR (!isset($array['userpassword1'])) OR (!isset($array['userpassword2'])) OR (!isset($array['street'])) OR (!isset($array['housenumber'])) OR (!isset($array['zipcode'])) OR (!isset($array['city']))) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$array['firstname'] = trim($array['firstname']);
		$array['lastname'] = trim($array['lastname']);
		$array['username'] = trim($array['username']);
		$array['userpassword1'] = trim($array['userpassword1']);
		$array['userpassword2'] = trim($array['userpassword2']);
		$array['street'] = trim($array['street']);
		$array['housenumber'] = trim($array['housenumber']);
		$array['zipcode'] = trim($array['zipcode']);
		$array['city'] = trim($array['city']);

		if ((strlen($array['firstname']) == 0) OR (strlen($array['lastname']) == 0) OR (strlen($array['username']) == 0) OR (strlen($array['userpassword1']) == 0) OR (strlen($array['userpassword2']) == 0) OR (strlen($array['street']) == 0) OR (strlen($array['housenumber']) == 0) OR (strlen($array['zipcode']) == 0) OR (strlen($array['city']) == 0)) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		if ($array['userpassword1'] != $array['userpassword2']) {
			$usercreation_error = '';
			return $this->SendErrorMessage('Die Passworteingaben stimmen nicht überein!');
		}

		if (strlen($array['userpassword1']) < $MIN_PASSWORD_LENGTH) {
			$usercreation_error = '';
			return $this->SendErrorMessage('Mindestlänge des Passworts sind ' . $MIN_PASSWORD_LENGTH . ' Zeichen!');
		}

		$array['userpassword1'] = password_hash($array['userpassword1'],PASSWORD_DEFAULT);
		unset($array['userpassword2']);

		$sql = 'SELECT ID FROM user WHERE (username = ?)';

		$bind_array = array($array['username']);
		$user = $this->select($sql,"s",$bind_array);

		if ($user) {
			return $this->SendErrorMessage('Ihr Username ist bereits vergeben!');
		}

		if(!preg_match('/^[0-9]{4}$/',$array['zipcode'])) {
			return $this->SendErrorMessage('Geben Sie eine valide Postleitzahl in Österreich an!');
		}

		$admin = false;
		if (isset($array['admin'])) {
			$admin = true;
		}

		$trainer = false;
		if (isset($array['trainer'])) {
			$trainer = true;
		}

		$userkurs = NULL;
		if (isset($array['kurs'])) {
			$userkurs = $array['kurs'];
		}

		if (($trainer) && (!$userkurs)) {
			return $this->SendErrorMessage('Für einen Trainer müssen Sie einen Kurs angeben, den er trainiert!');
		}

		if ($trainer) {
			$sql = 'SELECT ID FROM kurs WHERE (id = ?) AND (trainer_id IS NULL)';

			$bind_array = array($userkurs);
			$kurstemp = $this->select($sql,"i",$bind_array);

			if (!$kurstemp) {
				return $this->SendErrorMessage('Der gewählte Kurs hat bereits einen Trainer!');
			}
		}
 
		$sql = 'INSERT INTO `user` (`KURS_ID`, `username`, `userpassword`, `admin`, `trainer`, `vorname`, `nachname`, `strasse`, `strassennummer`, `PLZ`, `Ort`) VALUES (?,?,?,?,?,?,?,?,?,?,?)';

		$bind_array = array($userkurs,$array['username'],$array['userpassword1'],$admin,$trainer,$array['firstname'],$array['lastname'],$array['street'],$array['housenumber'],$array['zipcode'],$array['city']);
		$this->sendsql($sql,"issiissssss",$bind_array);

		$userid = $this->getInsertId();
		
		if (!$userid) {
			$usercreation_error = 'Fehler beim Verarbeiten der Daten!';
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		if ($trainer) {
			$sql = 'UPDATE kurs SET TRAINER_ID = ? WHERE ID = ?';

			$bind_array = array($userid,$userkurs);
			$this->sendsql($sql,"ii",$bind_array);
		};

		unset($_POST);
		return array('success' => true, 'message' => 'User erfolreich angelegt.');
	}

////////////////////////////////////////////////////////////////////////

	public function AddCourse($array) {

		if ((!isset($array['coursename'])) OR (!isset($array['coursedescription']))) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$array['coursename'] = trim($array['coursename']);
		$array['coursedescription'] = trim($array['coursedescription']);

		if ((strlen($array['coursename']) == 0) OR (strlen($array['coursedescription']) == 0)) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		if (!isset($array['coursetrainer'])) {
			$array['coursetrainer'] = null;
		}
 
		$sql = 'INSERT INTO kurs (trainer_id, Kursname, Kursbeschreibung) VALUES (?,?,?)';

		$bind_array = array($array['coursetrainer'],$array['coursename'],$array['coursedescription']);
		$this->sendsql($sql,"iss",$bind_array);

		$kursid = $this->getInsertId();
		
		if (!$kursid) {
			return $this->SendErrorMessage('Fehler beim Verarbeiten der Daten!');
		}

		if ($array['coursetrainer']) {
			$sql = 'UPDATE user SET kurs_id = ? WHERE ID = ?';
			$bind_array = array($kursid,$array['coursetrainer']);
			$this->sendsql($sql,"ii",$bind_array);
		}

		unset($_POST);
		return array('success' => true, 'message' => 'Kurs erfolreich angelegt.');
	}

////////////////////////////////////////////////////////////////////////

	public function getSingleCourse($id) {
		$sql = 'SELECT ID, Kursname FROM kurs WHERE (id = ?)';

		$bind_array = array($id);
		return $this->select($sql,"i",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function getDocumentsFromCourse($id) {
		$sql = 'SELECT ID, pfad, dokumentenname, dokumententyp FROM dokument WHERE (KURS_ID = ?)';

		$bind_array = array($id);
		return $this->selectMultiple($sql,"i",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function updatePassword($array,$UserName) {
		$MIN_PASSWORD_LENGTH = 10;

		if ((!isset($array['oldpassword'])) OR (!isset($array['newpassword'])) OR (!isset($array['confirmnewpassword']))) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$array['oldpassword'] = trim($array['oldpassword']);
		$array['newpassword'] = trim($array['newpassword']);
		$array['confirmnewpassword'] = trim($array['confirmnewpassword']);

		if ((strlen($array['oldpassword']) == 0) OR (strlen($array['newpassword']) == 0) OR (strlen($array['confirmnewpassword']) == 0)) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		$sql = 'SELECT ID, userpassword FROM user WHERE (username = ?)';

		$bind_array = array($UserName);
		$user = $this->select($sql,"s",$bind_array);

		if (!password_verify($array['oldpassword'], $user['userpassword'])) {
			return $this->SendErrorMessage('Ihr altes Password stimmt nicht überein!');
		}

		if ($array['newpassword'] != $array['confirmnewpassword']) {
			$usercreation_error = '';
			return $this->SendErrorMessage('Die neuen Passworteingaben stimmen nicht überein!');
		}

		if (strlen($array['newpassword']) < $MIN_PASSWORD_LENGTH) {
			$usercreation_error = '';
			return $this->SendErrorMessage('Mindestlänge des Passworts sind ' . $MIN_PASSWORD_LENGTH . ' Zeichen!');
		}

		$array['newpassword'] = password_hash($array['newpassword'],PASSWORD_DEFAULT);
		unset($array['confirmnewpassword']);

		$sql = 'UPDATE user SET userpassword = ? WHERE username = ?';
		$bind_array = array($array['newpassword'],$UserName);
		$this->sendsql($sql,"ss",$bind_array);

		unset($_POST);
		return array('success' => true, 'message' => 'Passwort erfolgreich geändert!');
	}

////////////////////////////////////////////////////////////////////////

	public function updateUser($array) {

	}

////////////////////////////////////////////////////////////////////////

	public function getAllUsers() {
		$sql = 'SELECT user.ID, user.admin, user.trainer, user.vorname, user.nachname, kurs.kursname FROM user LEFT OUTER JOIN kurs ON user.KURS_ID = kurs.ID';

		$bind_array = array();
		return $this->selectMultiple($sql,"",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function getAllDocuments() {
		$sql = 'SELECT dokument.ID, dokument.pfad, dokument.dokumentenname, dokument.dokumententyp, kurs.Kursname FROM dokument JOIN kurs ON dokument.KURS_ID = kurs.ID';
	
		$bind_array = array();
		return $this->selectMultiple($sql,"",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function getAllTrainers() {
		$sql = 'SELECT ID, nachname, vorname FROM user WHERE trainer = 1 AND kurs_id IS NULL';

		$bind_array = array();
		return $this->selectMultiple($sql,"",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function getAllCourses() {
		$sql = 'SELECT kurs.ID, kurs.Kursname, kurs.Kursbeschreibung, user.nachname, user.vorname FROM kurs LEFT OUTER JOIN user ON kurs.trainer_id = user.id';

		$bind_array = array();
		return $this->selectMultiple($sql,"",$bind_array);
	}

////////////////////////////////////////////////////////////////////////

	public function uploadFile($array) {
		if ((!isset($_FILES['datei'])) OR (!isset($array['course']))) {
			return $this->SendErrorMessage('Füllen Sie alle Felder aus!');
		}

		//Upload-Verzeichnis für Dokumente, Unterverzeichnis Dokumente im Ordner wo diese Datei liegt
		$upload_folder = dirname(__FILE__) . '/dokumente/'; 

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
			return $this->SendErrorMessage('Ungültige Dateiendung. Nur pdf, jpg, jpeg, xls, doc, docx und ppt-Dateien sind erlaubt');
		}


		//Überprüfung der Dateigröße
		$max_size = 5000*1024; //5000 KB
		if($_FILES['datei']['size'] > $max_size) {
			return $this->SendErrorMessage('Bitte keine Dateien größer als 5 Megabyte hochladen!');
		}

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

		$bind_array = array($array['course'],$new_path,$filename,$extension);
		$this->sendsql($sql,"isss",$bind_array);

		$dokumentID = $this->getInsertId();
		
		if (!$dokumentID) {
			return $this->SendErrorMessage('Fehler beim Verarbeiten der Daten!');
		}

		unset($_POST);
		unset($_FILES);
		return array('success' => true, 'message' => 'Dokument erfolreich angelegt.');
	}

}

$database_connection = new Database();