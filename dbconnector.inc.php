<?php
$host = 'localhost'; // host
$username = 'webapp151'; // username
$password = 'Z-PrzNE4f)e*br5r'; // password
$database = '151_projektarbeit'; // database

// mit der Datenbank verbinden
$mysqli = new mysqli($host, $username, $password, $database);

// Fehlermeldung, falls Verbindung fehl schlägt.
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
?>