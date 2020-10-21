<?php
/////////////////////////////////////////////////////////////////////////////////////////
/////								 DATABASE SETTINGS 								/////
/////////////////////////////////////////////////////////////////////////////////////////
    class dbConnection
    {
        private $servername = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbname = 'Seminarportal';
		private $conn;

	public function __construct() {
		$this->dbConnection();
	}
        public function dbConnection()
        {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            } 
        }
/////////////////////////////////////////////////////////////////////////////////////////
/////								 USER VALIDIEREN 								/////
/////////////////////////////////////////////////////////////////////////////////////////
public function validateUser($username, $password, $isAdmin = false) {
		$sql = "SELECT ID, KURS_ID, username, userpassword, trainer FROM user WHERE (username = ?) AND (admin = ?)";
		$stmt = $this->conn->prepare($sql);
		$admin = 0;
		$stmt->bind_param("si", $username,$admin);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		$stmt->close();
		$error = false;

// Check ob User vorhanden
		if($result->num_rows === 0) {
			echo '<center>Keine Übereinstimmung von Usernamen und Passwort!<center>';
			$error = true;
		}

// Check ob eingegebenes Passwort mit Passwort in Datenbank übereinstimmt
		if (!$error) {
			$password_hash = $user['userpassword'];
			if (!password_verify($password, $password_hash)) {
			echo '<center>Keine Übereinstimmung von Usernamen und Passwort!<center>';
				$error = true;
			}
		}
// Session Details speichern
		if (!$error) {
			$_SESSION['userid'] = password_hash($user['ID'],PASSWORD_DEFAULT);
			$_SESSION['username'] = $user['username'];
			$_SESSION['admin'] = false;
			$_SESSION['userkurs'] = $user['KURS_ID'];
			$_SESSION['trainer'] = $user['trainer'];
			header("Location: index.php");
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////
/////								 ADMIN VALIDIEREN 								/////
/////////////////////////////////////////////////////////////////////////////////////////
public function validateAdmin($username, $password, $isAdmin = true) {
		$sql = "SELECT ID, KURS_ID, username, userpassword, trainer FROM user WHERE (username = ?) AND (admin = ?)";
		$stmt = $this->conn->prepare($sql);
		$admin = 1;
		$stmt->bind_param("si", $username,$admin);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		$stmt->close();
		$error = false;

// Check ob User vorhanden
		if($result->num_rows === 0) {
			echo '<center>Keine Übereinstimmung von Usernamen und Passwort!<center>';
			$error = true;
		}

// Check ob eingegebenes Passwort mit Passwort in Datenbank übereinstimmt
		if (!$error) {
			$password_hash = $user['userpassword'];
			if (!password_verify($password, $password_hash)) {
				echo '<center>Keine Übereinstimmung von Usernamen und Passwort!<center>';
				$error = true;
			}
		}
// Session Details speichern
		if (!$error) {
			$_SESSION['userid'] = password_hash($user['ID'],PASSWORD_DEFAULT);
			$_SESSION['username'] = $user['username'];
			$_SESSION['admin'] = true;
			$_SESSION['userkurs'] = $user['KURS_ID'];
			$_SESSION['trainer'] = $user['trainer'];
			header("Location: index.php");
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////
/////								 USER ANLEGEN 									/////
/////////////////////////////////////////////////////////////////////////////////////////
public function adduser($userkurs,$username,$userpassword1,$admin,$trainer,$firstname,$lastname,$street,$housenumber,$zipcode,$city){
//Kurse holen
$sql = 'SELECT ID, Kursname, TRAINER_ID FROM kurs';
$stmt = $this->conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$Kurse = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$MIN_PASSWORD_LENGTH = 10;
$error = false;

// Passwortlänge überprüfen 
	if (!$error) {
		if (strlen($userpassword1) < $MIN_PASSWORD_LENGTH) {
			$usercreation_error = 'Mindestlänge des Passworts sind ' . $MIN_PASSWORD_LENGTH . ' Zeichen!';
			$error = true;
		}
	}

	$userpassword1 = password_hash($userpassword1,PASSWORD_DEFAULT);

	if (!$error) {
		$sql = 'SELECT ID FROM user WHERE (username = ?)';
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		$stmt->close();

// Check ob Username bereits vergeben 
		if ($user) {
			echo '<center>Ihr Username ist bereits vergeben!<center>';
			$error = true;
		}
	}

	if (!$error) {
 
		$sql = 'INSERT INTO `user` (`KURS_ID`, `username`, `userpassword`, `admin`, `trainer`, `vorname`, `nachname`, `strasse`, `strassennummer`, `PLZ`, `Ort`) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
		$stmt = $this->conn->prepare($sql);

		$stmt->bind_param("issiissssss", $userkurs,$username,$userpassword1,$admin,$trainer,$firstname,$lastname,$street,$housenumber,$zipcode,$city);
		$stmt->execute();

		$userid = $this->conn->insert_id;
		
		if (!$userid) {
			echo '<center>Fehler beim Verarbeiten der Daten!<center>';
			$error = true;
		}
		$stmt->close();
	}

// Wenn Trainer ausgewählt, Update in Kurs-Tabelle
	if (!$error) {
		if ($trainer) {
			$sql = 'UPDATE kurs SET TRAINER_ID = ? WHERE ID = ?';
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("ii", $userid,$userkurs);
			$stmt->execute();
		};
	}

	if (!$error) {
		unset($_POST);
		echo '<center>User erfolgreich angelegt!<center>';
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
/////							USER AUSGEBEN   									/////
/////////////////////////////////////////////////////////////////////////////////////////
public function getallusers(){
// Alle User aus Datenbank holen & die Kursdetails 
$sql = 'SELECT user.ID, user.admin, user.trainer, user.vorname, user.nachname, kurs.kursname FROM user LEFT OUTER JOIN kurs ON user.KURS_ID = kurs.ID';
$stmt = $this->conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $users;
}
/////////////////////////////////////////////////////////////////////////////////////////
/////							KURSE AUSGEBEN   									/////
/////////////////////////////////////////////////////////////////////////////////////////
public function getallcourses(){
// Alle Kurse aus Datenbank holen & Trainer
$sql = 'SELECT kurs.ID, kurs.Kursname, kurs.Kursbeschreibung, user.nachname, user.vorname FROM kurs LEFT OUTER JOIN user ON kurs.trainer_id = user.id';
$stmt = $this->conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$kurse = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $kurse;
}
/////////////////////////////////////////////////////////////////////////////////////////
/////							DOKUMENTE AUSGEBEN   								/////
/////////////////////////////////////////////////////////////////////////////////////////
public function getalldocuments() {
// Alle Dokumente aus Datenbank holen
$sql = 'SELECT dokument.ID, dokument.pfad, dokument.dokumentenname, dokument.dokumententyp, kurs.Kursname FROM dokument JOIN kurs ON dokument.KURS_ID = kurs.ID';
$stmt = $this->conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();
$dokumente = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $dokumente;
}
/////////////////////////////////////////////////////////////////////////////////////////
/////								 USER KURS 										/////
/////////////////////////////////////////////////////////////////////////////////////////
public function getuserdocument() {
// Alle Kurse vom User eingeschriebenen Kurs
$sql = 'SELECT ID, pfad, dokumentenname, dokumententyp FROM dokument WHERE (KURS_ID = ?)';
$stmt = $this->conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['userkurs']);
$stmt->execute();

$result = $stmt->get_result();
$dokumente = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
return $dokumente;
}
}
$database_connection = new dbConnection();