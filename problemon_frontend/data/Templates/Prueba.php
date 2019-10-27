<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/conexion.php"; include "../Recursos/Funciones/AdministradorSesiones.php"; ?>
	
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
	<script src="../Recursos/Javascript/comprobacionbase.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Recursos/Javascript/jquery.datetimepicker.full.js"></script>
	<link rel="stylesheet" href="../Recursos/css/jquery.datetimepicker.min.css">
	
	<script src="../Recursos/Javascript/jquery.popupwindow.js"></script>
	<script>
	
	var coordenadas = { valor : '', 
					   get valor() { return this._prop1; },
					   set valor(value) { this._prop1 = value;
										 document.getElementById('coordenadas').value=coordenadas.valor;
										 $('#preguntasn').trigger('change');
										}
					  }
		
	function getCoor(valor){
		var xmlhttp;
		if (window.XMLHttpRequest) {
		    xmlhttp = new XMLHttpRequest();
		} else {
		    xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		xmlhttp.onreadystatechange = function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        coordenadas.valor = xmlhttp.responseText;
		    }
		}
		xmlhttp.open('POST', '../Recursos/Funciones/obtenercoord.php', true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlhttp.send('str='+valor);
	}
		
	function obtenerCoordenadas(){
		$.popupWindow('../mapa', {
            height: 800,
            width : 600,
			onUnload: function() {
				getCoor('coordenadas');
        	}
    	});
	} 
	
	
	</script>

</head>
<body>
	<div class="wrapper" >
		
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<h2>Ingreso de preguntas.</h2>
							<form action="#" id="preguntasn" name="preguntasn" method="POST">
								<table>
									<tr>
										<td>
											<h4>Enunciado de pregunta:</h4>
										</td>
										<td align="center">
											<textarea  type="text" name="nombrePreg" id="nombrePreg" class="cajastexto" maxlength="500" placeholder="Enunciado de la pregunta" required></textarea> 
										</td>
									</tr>
									<tr>
										<td>
											<h4>Alternativa respuesta 1:</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="alternativa1" name="alternativa1" placeholder="alternativa 1" required/>	
											<input type="radio" id="respuestacorrecta1" name="respuestacorrecta" value="1" checked /> <label for="respuestacorrecta1">Respuesta Correcta?</label>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Alternativa respuesta 2:</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="alternativa2" name="alternativa2" placeholder="alternativa 2" required/>
											<input type="radio" id="respuestacorrecta2" name="respuestacorrecta" value="2" /> <label for="respuestacorrecta2">Respuesta Correcta?</label>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Alternativa respuesta 3:</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="alternativa3" name="alternativa3" placeholder="alternativa 3" required/>
											<input type="radio" id="respuestacorrecta3" name="respuestacorrecta" value="3" /> <label for="respuestacorrecta3">Respuesta Correcta?</label>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Alternativa respuesta 4:</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="alternativa4" name="alternativa4" placeholder="alternativa 4" required/>
											<input type="radio" id="respuestacorrecta4" name="respuestacorrecta" value="4" /> <label for="respuestacorrecta4">Respuesta Correcta?</label>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Ubicación</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="coordenadas" name="coordenadas" placeholder="Coordenadas" onClick="obtenerCoordenadas()" readonly />
										</td>
									</tr>
									<tr>
										<td>
											<h4>Descripción de la ubicación</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="descubi" name="descubi" placeholder="Descripción de la ubicación" />
										</td>
									</tr>
								<!--	<tr>
										<td>
											<h4>Adjunte el codigo qr</h4>
										</td>
										<td align="center">
											<label for="fileQr">Archivo (png o jpg): </label>
											<input type="hidden" name="MAX_FILE_SIZE" value="3145728"><input type="file" name="fileQr" id="fileQr" >
										</td>
									</tr>
									-->
								</table>
								<input type="submit" id="ingresopr" name="ingresopr" value="Registrar" class="botones">
							</form>
						</center>
					</div>
					
				</span>
			</div>
		</main>
	</div>
</body>
<script>
	
	jQuery.datetimepicker.setLocale('es');
	jQuery(document).ready(function () {
		'use strict';
		jQuery('#fechaInicio, #fechaFin').datetimepicker();;
	});
	
	$('form').on('keyup change keydown keypress', 'input, select, textarea', function(){
		console.log('Se ha cambiado un valor!');
	});
</script>
<?php
	if( $_POST ){
		$pregunta=$_POST['nombrePreg'];
		$alternativa1=$_POST['alternativa1'];
		$alternativa2=$_POST['alternativa2'];
		$alternativa3=$_POST['alternativa3'];
		$alternativa4=$_POST['alternativa4'];
		switch( $_POST['respuestacorrecta'] ){
			case "1":
				$respuestacorrecta=$alternativa1;
				break;
			case "2":
				$respuestacorrecta=$alternativa2;
				break;
			case "3":
				$respuestacorrecta=$alternativa3;
				break;
			case "4":
				$respuestacorrecta=$alternativa4;
				break;
			case "default":
				break;
				
		}
		$coordenadas=$_POST['coordenadas'];
		$descubi=$_POST['descubi'];
		$idpregunta=1;
		$select="select max(`QUE_ID`) maximo from questions;";
		$r = mysqli_query( $link, $select );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$idpregunta += $renglon->maximo;
		}
		$valor= "pregIdentificacion-".$idpregunta; 
		include "../Recursos/Funciones/qr/qrlib.php";    
		$filename = '../Recursos/Qrs/'.$valor.'.png';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 4;  
		QRcode::png($valor, $filename, $errorCorrectionLevel, $matrixPointSize, 2);  
		$sql="INSERT INTO `questions`( `QUE_TITLE`, `QUE_ANSWER1`, `QUE_ANSWER2`, `QUE_ANSWER3`, `QUE_ANSWER4`, `QUE_RIGHT_ANS`, `QUE_DESCRIPTION`, `QUE_IMG`, `QUE_POSITION`) VALUES ( '$pregunta' , '$alternativa1' , '$alternativa2' , '$alternativa3' , '$alternativa4' , '$respuestacorrecta' , '$descubi' , '$filename' , '$coordenadas' )";
		if ( $link->query($sql) ){
			print "<script>alert('Pregunta agregada exitósamente.');</script>";
		} 
	}
	
?>
	
</html>