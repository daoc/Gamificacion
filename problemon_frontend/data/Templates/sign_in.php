<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	$data = array();
	$search='{ "where": { "or": [{"rol." :"Administrador"},{"rol." :"Profesor"} ]} }';
	$usuariosDisponibles = $rest -> sendGet($url."people?filter=".urlencode($search), $data);?>
<head>
	
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../Recursos/css/bootstrap-select.css">
	<link rel="stylesheet" href="../Recursos/css/estilos.css">
	
	<script src="../Recursos/Javascript/bootstrap-select.js"></script>
</head>
<body>
	<div class="wrapper" >
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<h2>Registro en el sistema.</h2>
							<form name="#" method="POST">
								<div class="row">
									<div class="col-sm-6"><h4>Correo Electrónico:</h4></div>
									<div class="col-sm-4">
										<input type="email" name="mailus" maxlength="100" class="cajastexto" placeholder="Correo electrónico" title="Ingrese el correo electrónico" onchange="comprobar(this.value)"  onkeydown="comprobar(this.value)" onkeypress="comprobar(this.value)" onkeyup="comprobar(this.value)" required>
										<p id="respuestacorreo"></p>
									</div>			
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Nombre de usuario:</h4></div>
									<div class="col-sm-4">
									<input type="text" name="nomus" id="nomus" class="cajastexto" placeholder="Nombre de usuario" required  
											   onKeyUp="comprobarusuario(this.value);" 
											   onChange="comprobarusuario(this.value);" 
											onBlur="comprobarusuario(this.value);"> 
									<p id="respuestausu"></p>
								</div>
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Nombre completo:</h4></div>
									<div class="col-sm-4">
										<input type="text" name="nombre" id="nombre" class="cajastexto" maxlength="50" placeholder="Apellidos y Nombres" required>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Tipo de usuario:</h4></div>
									<div class="col-sm-4">
										<select name="tipo" class="selectpicker" id="tipo" data-live-search='true' title="Seleccione un tipo de usuario" required>
											<option value="Administrador"> Administrador</option>
											<option value="Profesor" selected>Instructor</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Contraseña:</h4></div>
									<div class="col-sm-4">
										<input type="password" name="contraus" id="contraus" pattern=".{5,}" title="La contraseña debe contener mínimo 5 carácteres" class="cajastexto" placeholder="Contraseña" onBlur="comprobarcontra();" onClick="contrar.value='';" required>
										<p id="respuestacontra"></p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Repetir la Contraseña:</h4></div>
									<div class="col-sm-4">
										<input type="password" name="contrausr" id="contrausr" pattern=".{5,}" title="La contraseña debe contener mínimo 5 carácteres" class="cajastexto" placeholder="Repetir Contraseña" onBlur="comprobarcontra();" required>
									</div>
		
								</div>
							
								<input type="submit" id="ingresous" name="ingresous" value="Registrar" class="botonesdisabled">
							</form>
						</center>
					</div>
				</span>
			</div>
		</main>
	</div>
</body>
<script>
	var usuarios = JSON.parse('<?= $usuariosDisponibles; ?>');
	var users = Object.keys(usuarios).map(function(k) { return usuarios[k] });
	var contra=document.getElementById('contraus');
	var contrar=document.getElementById('contrausr');
	var ingresous=document.getElementById('ingresous');
	var respuestausu=document.getElementById('respuestausu');
	var respuestacorreo=document.getElementById('respuestacorreo');
	var respuestacontra=document.getElementById('respuestacontra');
	var bandera=0;
	var banderacorreo=0;
	var banderaus=0;
	var banderauser;
	var respuced;
	
	function comprobar(str){
		if (str.length>6)
		for (var i = 0; i < usuarios.length; i++){
			if ( usuarios[i] ["identification"] ==  str ){
				respuestacorreo.innerHTML="No disponible";
				respuestacorreo.style="color:firebrick";
				banderacorreo=0;
				
				break;
			} else {
				respuestacorreo.innerHTML="Disponible";
				respuestacorreo.style.color="#a1ec98";
				banderacorreo=1;
			}
		} 
		else {
			respuestacorreo.innerHTML="";
			banderacorreo=0;
		}
	}
	
	function comprobarcontra(){
		if(contra.value == contrar.value && contra.value != ""){
			bandera=1;
			contra.style.borderColor="#a1ec98";
			contrar.style.borderColor="#a1ec98";
		} else {
			contra.style.borderColor="firebrick";
			contrar.style.borderColor="firebrick";
			bandera=0;
		}
	}
	
	function comprobarusuario(str){
		if (str.length>4)
		for (var i = 0; i < users.length; i++){
			if ( users[i] ["user"] ==  str ){
				respuestausu.innerHTML="No disponible";
				respuestausu.style="color:firebrick";
				banderauser=0;
				break;
			} else {
				respuestausu.innerHTML="Disponible";
				respuestausu.style.color="#a1ec98";
				banderauser=1;
			}
		} 
		else {
			respuestausu.innerHTML="";
			banderauser=0;
		}
	}
	
	function comprobarbandera(){
		if (bandera==1 && banderacorreo==1 && banderauser==1){
			ingresous.removeAttribute("disabled");  
			ingresous.className="botones";
		} else {
			ingresous.disabled=false;
			ingresous.className="botonesdisabled";
		}
	}
	
	$('form').on('change keydown keypress keyup', 'input, select, textarea', function(){
		console.log('Se ha cambiado un valor!');
		comprobarbandera();
		console.log(bandera + " " + banderacorreo + " "+ banderauser)
	});
</script>
<?php
	if(@$_POST['ingresous']){
		$url = "http://localhost:3000/api/";
		$rest = new CurlRequest();
		$datosPersona = array(
			"identification" => $_POST['mailus'],
			"name" => strtoupper($_POST['nombre']) ,
			"rol" => array($_POST['tipo']),
			"user" => $_POST['nomus'],
			"status" => "ACTIVO"
		);
		//print 
		$rest -> sendPost($url."people",$datosPersona);
		
		
		$datosUsuario = array(
			"username" => $_POST[ "nomus" ],
			"password" => $_POST[ "contraus" ],
			"email" => $_POST['mailus']
		);
		//print 
		$rest -> sendPost($url."users",$datosUsuario);
		print "<script>alert('Usuario creado exitosamente');</script>";
		
	}
?>
</html>
