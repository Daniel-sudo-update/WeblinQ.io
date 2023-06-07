<?php
ob_start(); //Turns on output buffering 
session_start();

$timezone = date_default_timezone_set("Europe/Bucharest");

$con = mysqli_connect("localhost", "root", "", "social"); //Connection variable

if(mysqli_connect_errno()) 
{
	echo "Problemă de conectare: " . mysqli_connect_errno();
}



// partea de resetare a parolei
$server = "localhost";
$username = "root";
$password = "";
$database = "social";
$connection = mysqli_connect("$server","$username","$password");
$select_db = mysqli_select_db($connection, $database);
if(!$select_db)
{
	echo("connection terminated");
}

?>
<!-- client=6LckUBQmAAAAACWDN-FMScRuAbTO07uAhbpe5gc_ -->
<!-- 6LckUBQmAAAAAHa8EQNZ2pN4VWy6znyy19t2kEM2 -->