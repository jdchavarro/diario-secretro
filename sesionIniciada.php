<?php
session_start();
$contenidoDiario = "";
//comprobamos que exista el id en cookie y contenga algo
if(array_key_exists("id",$_COOKIE) && $_COOKIE['id']){
	//asignamos esa cookie a la sesion
	$_SESSION['id'] = $_COOKIE['id'];
}
//comprobamos que hay un id en la session y contenga algo
if(array_key_exists("id",$_SESSION) && $_SESSION['id']){
	//nos conectamos a la base de datos
	include("conexion.php");
	$id = mysqli_real_escape_string($enlace, $_SESSION['id']);
	$query = "SELECT diario FROM diario WHERE id=".$id." LIMIT 1";
	$result = mysqli_query($enlace,$query);
	$fila = mysqli_fetch_array($result);
	$contenidoDiario = $fila['diario'];
}
//en caso que no tenga una sesion abierta lo mandamos a registrarse
else{
	header("Location: index.php");
}
include("header.php");
?>
<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
	<a class="navbar-brand" href="#">Diario Secreto</a>
	<div class="my-2 my-lg-0">
		<a href="index.php?Logout=1"><button class="btn btn-outline-success" type="submit">Cerrar Sesión</button></a>
	</div>
</nav>

<div class="container-fluid" id="contenedorSesionIniciada">
	<textarea id="diario" class="form-control"><?php echo $contenidoDiario;?></textarea>
</div>
<?php include("footer.php");?>