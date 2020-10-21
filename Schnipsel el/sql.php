<?php

//Database connection prozedural

$DATABASE_HOST = 'hostip';
$DATABASE_USER = 'username';
$DATABASE_PASS = 'userpassword';
$DATABASE_NAME = 'databasename';

$database_connection = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//////////////////////////////////////////////////////////

$select = "SELECT * FROM table1 WHERE value1 = ?";

$udpate = "UPDATE table1 SET value2 = ? WHERE value1 = ?";

$delete = "DELETE FROM table1 WHERE value1 = ?";

///////////////////////////////////////////////////////////

$stmt = $database_connection->prepare($select);
$wert1 = 1;

// i -> integer, d -> double, s -> string, datum

$stmt->bind_param("i", $wert1);
$stmt->execute();

$result = $stmt->get_result();

//assoc -> array hat als index die spaltennamen
//numeric -> array hat als index 0,1,2,...

// eine zeile holen -> eindimensionales array

$einezeile = $result->fetch_assoc();

//aalles auf einmal holen, 2 dimensionales array

$num_array = $result->fetch_all(MYSQLI_NUM);

$assoc_array = $result->fetch_all(MYSQLI_ASSOC);


$stmt->close();

?>