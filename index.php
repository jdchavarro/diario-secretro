<?php
session_start();
//verificamos si el usuario viene cerrando sesion de la otra pagina
if(array_key_exists("Logout",$_GET)){
	//viene de la pagina sesionIniciada
	//cerramos todas las variables de session
	session_unset();
	//eliminamos la cookie
	setcookie("id","",time()-60*60);
	$_COOKIE['id'] = "";
}
else if((array_key_exists("id",$_SESSION) AND $_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id'])){
	//verificamos que la session id existe y ademas tiene algo o si cookie id existe y tiene algo
	header("Location: sesionIniciada.php");
}

//creamos la variable que ira recogiendo todos los errores encontrados
$error = "";
//verificamos que el usuario halla enviado algo
if(array_key_exists("submit",$_POST)){
	
	include("conexion.php");
	
	//verificar si los campos estan vacios
	if(!$_POST['email']){
		$error .= "<br><strong>Email</strong> requerido.";
	}
	if(!$_POST['password']){
		$error .= "<br><strong>Contraseña</strong> requerido.";
	}
	//si todo ha ido bien
	else{
		//evitamos sql injection
		$email = mysqli_real_escape_string($enlace, $_POST['email']);
		$password = mysqli_real_escape_string($enlace, $_POST['password']);
		
		//verificamos cual formulario es
		//1 es registro, 0 si es inicio sesion
		if($_POST['registro'] == 1){
			//realizamos la consulta si el usuario esta ya registrado
			$query = "SELECT id FROM diario WHERE email='".$email."' LIMIT 1";
			$result = mysqli_query($enlace,$query);
			if(mysqli_num_rows($result)>0){
				$error = "<br><strong>Email</strong> ya registrado";
			}
			else{
				//si no esta registrado, procedemos a registrarlo
				//el cifrado de la contraseña lo haremos con el id que devuelve el registo del usuario
				$query = "INSERT INTO diario (email, password) VALUES ('".$email."','".$password."')";
				//verificamos que se registre correctamente
				if(!mysqli_query($enlace,$query)){
					$error = "<p>No hemos podido completar el registro, por favor intente más tarde.</p>";
				}
				else{
					//obtenemos la id
					$id = mysqli_insert_id($enlace);
					//Ciframos la contraseña
					$query = "UPDATE diario SET password='".md5(md5($id.$password))."' WHERE id=".$id." LIMIT 1";
					mysqli_query($enlace, $query);
					
					//asignamos el id de la session al del usuario
					$_SESSION['id'] = $id;
					
					//verificamos si el checkbox es 1 osea checked
					if($_POST['permanecerIniciada'] == '1'){
						//creamos un cookir para un año
						setcookie("id",$id,time()+60*60*24*365);
					}
					header("Location: sesionIniciada.php");
				}
			}
		}			
		else{
			//comprobamos el inicio de sesion
			//seleccionamos de la base de datos el email ingresado
			$query = "SELECT * FROM diario WHERE email='".$email."'";
			$result = mysqli_query($enlace,$query);
			$fila = mysqli_fetch_array($result);
			//comprobamos si ay algo en la fila
			if(isset($fila)){
				//recreamos la password creada
				$passwordHashed = md5(md5($fila['id'].$password));
				if($passwordHashed == $fila['password']){
					//usuario autenticado
					$_SESSION['id'] = $fila['id'];
					if($_POST['permanecerIniciada'] == '1'){
						setcookie("id",$fila['id'],time()+60*60*24*365);
					}
					header("Location: sesionIniciada.php");
				}
				else{
					$error = "<p>La <strong>contraseña</strong> no es correcta</p>";
				}
			}
			else{
				$error = "<p>El <strong>email</strong> no esta registrado</p>";
			}
		}
	}
	
	//mostramos los errores encontrados
	if($error != ""){
		$error = "<p>Hubo algun(os) error(es) en el formulario: ".$error."</p>";
	}
}
?>
<?php include("header.php");?>
<div class="container" id="contenedorPaginaPrincipal">
	<h1>Diario Secreto</h1>
	<p><strong>Guarda tus pensamientos para siempre</strong></p>
	
	<!-- Seccion Errores -->
    <div id="error">
    	<?php
    		if($error != ""){
    			echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
    		}
    	?>
	</div>

	<!-- Formulario registro -->
	<form method="POST" id="formularioRegistro">
		<p>¿Interesad@? Regístrate ahora!</p>
		<div class="form-group">
			<input type="email" class="form-control" name="email" placeholder="Tu email">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>
		<div class="form-check">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" name="permanecerIniciada" value=1>
				Permanecer iniciada
			</label>
		</div>
		<div class="form-group">
			<input type="hidden" name="registro" value=1>
			<input type="submit" class="btn btn-success" name="submit" value="Regístrate!">
		</div>
		<p><a class="alternarFormularios">Iniciar Sesión</a></p>
	</form>

	<!-- Formulario login -->
	<form method="POST" id="formularioLogin">
		<p>Inicia sesión con tu usuario/contraseña</p>
		<div class="form-group">
			<input type="email" class="form-control" name="email" placeholder="Tu email">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>
		<div class="form-check">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" name="permanecerIniciada" value=1>
				Permanecer iniciada
			</label>
		</div>
		<div class="form-group">
			<input type="hidden" name="registro" value=0>
			<input type="submit" class="btn btn-success" name="submit" value="Inicia sesión">
		</div>
		<p><a class="alternarFormularios">Regístrate</a></p>
	</form>
</div>
<?php include("footer.php");?>