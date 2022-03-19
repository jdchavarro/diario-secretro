<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "diario-secreto";
//nos conectamos a la base de datos
$enlace = mysqli_connect($server,$user,$password,$db);

//comprobamos que no hubo error de conexión
if(mysqli_connect_error()){
	die("Error de conexión en la base de datos");
}
?>