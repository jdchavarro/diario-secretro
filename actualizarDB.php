<?php
session_start();
if(array_key_exists('content',$_POST)){
	include("conexion.php");
	$contenido = mysqli_real_escape_string($enlace, $_POST['content']);
	$id = mysqli_real_escape_string($enlace, $_SESSION['id']);
	$query = "UPDATE diario SET diario='".$contenido."' WHERE id=".$id." LIMIT 1";
	mysqli_query($enlace, $query);
}
?>