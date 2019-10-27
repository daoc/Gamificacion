<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$rest = new CurlRequest();
	$data = array();
	//print "<pre>";print_r($_SESSION);?>
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
							<form name="#" method="POST" onKeypress="if(event.keyCode == 13) event.returnValue = false;">
								<div class="row">
									<div class="col-sm-6"><p>Correo Electrónico:</p></div>
									<div class="col-sm-4">
										<?= $_SESSION ['usuario'] ['identification'];?>
									</div>			
								</div>
								<div class="row">
									<div class="col-sm-6"><p>Nombre de usuario:</p></div>
									<div class="col-sm-4">
										<?= $_SESSION ['usuario'] ['user'];?>
									</div>			
								</div>
								<div class="row">
									<div class="col-sm-6"><h4>Nombre completo:</h4></div>
									<div class="col-sm-4">
										<input type="text" name="nombre" value="<?= $_SESSION ['usuario'] ['name'];?>" id="nombre" class="cajastexto" maxlength="50" placeholder="Apellidos y Nombres" required>
									</div>
								</div>
								
								
								<div class="row">
									<div class="col-sm-6"><h4>Contraseña vieja:</h4></div>
									<div class="col-sm-4">
										<input type="password" name="contrausvieja" id="contrausvieja" pattern=".{5,}" title="La contraseña debe contener mínimo 5 carácteres" class="cajastexto" placeholder="Contraseña vieja" required>
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-6"><h4>Contraseña nueva:</h4></div>
									<div class="col-sm-4">
										<input type="password" name="contraus" id="contraus" pattern=".{5,}" title="La contraseña debe contener mínimo 5 carácteres" class="cajastexto" placeholder="Contraseña nueva" onBlur="comprobarcontra();" onClick="contrar.value='';" required>
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
	var contra=document.getElementById('contraus');
	var contrar=document.getElementById('contrausr');
	var ingresous=document.getElementById('ingresous');
	var respuestacontra=document.getElementById('respuestacontra');
	var bandera=0;
	var banderacorreo=0;
	var banderaus=0;
	var banderauser;
	var respuced;
	
	
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
	
	
	function comprobarbandera(){
		if (bandera==1){
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
		console.log(bandera )
	});
</script>
<?php
	if(@$_POST['ingresous']){
		$url = "http://ec2-3-16-37-243.us-east-2.compute.amazonaws.com:3000/api/";
		$rest = new CurlRequest();
		$_SESSION ['usuario']['name'] = strtoupper($_POST['nombre']);
		$rest -> sendPut($url."people",$_SESSION ['usuario']);
		$datosUsuario = array(
			"oldPassword" => $_POST[ "contrausvieja" ],
			"newPassword" => $_POST[ "contraus" ],
		);
		
		$ch = curl_init($url."Users/change-password?access_token=". $_SESSION ['respLogin']['id']);
            //a true, obtendremos una respuesta de la url, en otro caso, 
            //true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//establecemos el verbo http que queremos utilizar para la petición
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($datosUsuario));
		$response = curl_exec($ch);
		curl_close($ch);
		$respuesta = json_decode($response, true);
		if(isset ($respuesta['error']['message'])) {
			print "<script>alert('Contraseña antigua incorrecta') ; </script>)";
		}else{
			print "<script>alert('Datos actualizados');</script>";
		}
	}
?>
</html>