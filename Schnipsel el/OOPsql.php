<?php

//Database connection OOP

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
		$this->host = 'hostip';
		$this->user = 'username';
		$this->pass = 'userpassword';
		$this->db = 'databasename';

		$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
		return $this->mysqli;
	}

	//!!!!!!!!!!!!!!!!!!!!!
	//binden von einem ganzen array geht NUR AB PHP 5.6

	public function select($sql,$bind_string,$bind_array){
		$stmt = $this->mysqli->prepare($sql);

		$stmt->bind_param($bind_string, ...$bind_array);
		$stmt->execute();

		$assoc_array = $result->fetch_all(MYSQLI_ASSOC);
		return $assoc_array;
	}

	//Für Update, Delete, ... -> kein return value
	public function sendsql($sql,$bind_string,$bind_array){
		$stmt = $this->mysqli->prepare($sql);

		$stmt->bind_param($bind_string, ...$bind_array);
		$stmt->execute();
	}
}

///////////////////

$select = "SELECT * FROM table1 WHERE value1 = ?";

$udpate = "UPDATE table1 SET value2 = ? WHERE value1 = ?";

$delete = "DELETE FROM table1 WHERE value1 = ?";

/////////////////////

$db = new Database();

//entweder die sql befehle auch als methoden in der klasse aufrufen

$bind_string = "i";
$bind_array = array(5);

$result = $db->select($select,$bind_string,$bind_array);

////////////////////////////

//oder das aus new database als $database_connection nehmen (wenn nur die verbindung OOP sein muss)

$stmt = $db->prepare($select);

?>