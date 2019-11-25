<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/New_York');
		
define('HOST','localhost');
define('USER','chris_groopzilla');
define('PASS','@%groopzilla&Chris!#');
define('DBNAME','chris_groopzilla');


$dbHost = HOST;	
$dbUser = USER;
$dbName = DBNAME;
$dbPass = PASS;

$conn = mysqli_connect($dbHost,$dbUser,$dbPass) or die("Could not Connect The Server:  Errors:".mysqli_error());
$db = mysqli_select_db($conn,$dbName) or die("database Connection Error:".mysqli_error());


?>