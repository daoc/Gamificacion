<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="Recursos/css/estilos.css">
	<style>
		#dialogoverlay{
			display: none;
			opacity: .8;
			position: fixed;
			top: 0px;
			left: 0px;
			background: #FFF;
			width: 100%;
			z-index: 10;
		}
		#dialogbox{
			display: none;
			position: fixed;
			background: #000;
			border-radius:7px; 
			width:550px;
			z-index: 10;
		}
		#dialogbox > div{ background:#FFF; margin:8px; }
		#dialogbox > div > #dialogboxhead{ background: #666; font-size:19px; padding:10px; color:#CCC; }
		#dialogbox > div > #dialogboxbody{ background:#333; padding:20px; color:#FFF; }
		#dialogbox > div > #dialogboxfoot{ background: #666; padding:10px; text-align:right; }
	</style>
	<script>
		function CustomAlert(){
			this.render = function(dialog){
				var winW = window.innerWidth;
				var winH = window.innerHeight;
				var dialogoverlay = document.getElementById('dialogoverlay');
				var dialogbox = document.getElementById('dialogbox');
				dialogoverlay.style.display = "block";
				dialogoverlay.style.height = winH+"px";
				dialogbox.style.left = (winW/2) - (550 * .5)+"px";
				dialogbox.style.top = "100px";
				dialogbox.style.display = "block";
				document.getElementById('dialogboxhead').innerHTML = "NOTIFICACIÓN";
				document.getElementById('dialogboxbody').innerHTML = dialog;
				document.getElementById('dialogboxfoot').innerHTML = '<button class="botones" onclick="Alert.ok()">OK</button>';
			}
			this.ok = function(){
				document.getElementById('dialogbox').style.display = "none";
				document.getElementById('dialogoverlay').style.display = "none";
			}
		}
		var Alert = new CustomAlert();
	</script>
</head>
<body>
	<div id="dialogoverlay"></div>
	<div id="dialogbox">
		<div>
			<div id="dialogboxhead"></div>
			<div id="dialogboxbody"></div>
			<div id="dialogboxfoot"></div>
		</div>
	</div>
	
	<?php 
	if ($_GET){
		$mensaje = $_GET['mensaje'];?>
	<script>
			Alert.render("<?= $mensaje ?>");
	</script>
	<?php } ?>
	<div class="wrapper" >
		<header class="header">
			<div class="contenedor">
				<img src="./Recursos/Imagenes/logo1.png" width=auto height=50  onClick="location.href='./'">
				<h1 class="Titulo">Gamificación UTE (GrIInf)</h1>
			</div>
		</header>
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<h2>Ingreso al sistema.</h2>
							<form name="login" method="POST" action="Recursos/Funciones/validar">
								<table style="border-collapse: separate;border-spacing: 25px;">
									<tr>
										<td><strong>Usuario:</strong></td>
										<td><input class="cajastexto" type="text" name="usuario" placeholder="Usuario" required/></td>
									</tr>
									<tr>
										<td><strong>Contraseña:</strong></td>
										<td><input class="cajastexto" type="password" name="contrasena" placeholder="Contraseña" required/></td>
									</tr>
								</table>
								<input type="submit" value="Ingresar" class="botones">
								<input type="submit" name="invitado" value="Ingresar como invitado" class="botones">
							</form>
						</center>
					</div>
				</span>
			</div>
		</main>
		
		<footer class="footer2">
			<center>
				<p style="font-size: 1em">GrIInf (Grupo de Investigación en Informática)</p>
			</center>
		</footer>
	</div>
</body>

</html>
