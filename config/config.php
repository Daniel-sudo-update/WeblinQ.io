<?php
ob_start(); //Turns on output buffering 
session_start();

$timezone = date_default_timezone_set("Europe/Bucharest");

$con = mysqli_connect("eu-cdbr-west-03.cleardb.net", "b483c78de1e73d", "919e1f82", "heroku_a6ea7f8c2af936f"); //Connection variable

if(mysqli_connect_errno()) 
{
	echo "Problemă de conectare: " . mysqli_connect_errno();
}



// partea de resetare a parolei
$server = "eu-cdbr-west-03.cleardb.net";
$username = "b483c78de1e73d";
$password = "919e1f82";
$database = "heroku_a6ea7f8c2af936f";
$connection = mysqli_connect("$server","$username","$password");
$select_db = mysqli_select_db($connection, $database);
if(!$select_db)
{
	echo("connection terminated");
}

?>
<!-- 6Lc4GXomAAAAAM1xAASeLuiXHdIlgJpBvSWBVkeW site pt localhost-->
<!-- 6LckUBQmAAAAAHa8EQNZ2pN4VWy6znyy19t2kEM2 secret -->

<!-- 6Lc4GXomAAAAAM1xAASeLuiXHdIlgJpBvSWBVkeW site pt weblinq.org -->
<!-- 6Lc4GXomAAAAACQce16MtOBkfQUbh7EtxvpySyNB - secret -->