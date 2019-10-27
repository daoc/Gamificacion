<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	$data = array();
	$search = '{ "where": { "proffesors." : "'.$cedulaUser.'" } }';
	$asignaturasDisponibles = $rest -> sendGet($url."subjects?filter=".urlencode($search), $data);
	$asignaturasDisponibles = json_decode($asignaturasDisponibles, true);
	// print_r($asignaturasDisponibles);
	?>

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
		var coordenadas = {
			valor: '',
			get valor() {
				return this._prop1;
			},
			set valor( value ) {
				this._prop1 = value;
				document.getElementById( 'coordenadas' ).value = coordenadas.valor;
				$( '#preguntasn' ).trigger( 'change' );
			}
		}

		function getCoor( valor ) {
			var xmlhttp;
			if ( window.XMLHttpRequest ) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject( 'Microsoft.XMLHTTP' );
			}
			xmlhttp.onreadystatechange = function () {
				if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
					coordenadas.valor = xmlhttp.responseText;
				}
			}
			xmlhttp.open( 'POST', '../Recursos/Funciones/obtenercoord.php', true );
			xmlhttp.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
			xmlhttp.send( 'str=' + valor );
		}

		function obtenerCoordenadas() {
			$.popupWindow( '../mapa', {
				height: 800,
				width: 600,
				onUnload: function () {
					getCoor( 'coordenadas' );
				}
			} );
		}
	</script>

</head>

<body>
	<div class="wrapper">

		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent"  style="width: 60%">
						<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-6">
									<center>
										<h2>Ingreso de preguntas.</h2>
										<form action="#" id="preguntasn" name="preguntasn" method="POST"  enctype="multipart/form-data">
											<div class="row">
												<div class="col-sm-5">
													<h4>Asignatura:</h4>
												</div>
												<div class="col-sm-5">
													<select name="nombreMateria" id="nombreMateria" class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" required>
														<?php for ($i=0; $i < count ($asignaturasDisponibles); $i++) {?>
														<option value="<?= $asignaturasDisponibles[$i]['name']; ?>">
															<?= $asignaturasDisponibles[$i]['name']; ?>
														</option>
														<?php } ?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-5">
													<h4>Tema:</h4>
												</div>
												<div class="col-sm-5">
													<input type="text"  name="tema" id="tema" class="cajastexto" maxlength="40" placeholder="Tema" required>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-5">
													<h4>Enunciado de pregunta:</h4>
												</div>
												<div class="col-sm-5">
													<td align="center">
														<textarea type="text" name="nombrePreg" id="nombrePreg" class="cajastexto" maxlength="500" placeholder="Enunciado de la pregunta" required></textarea>
													</td>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-8">
													<legend>Alternativas y/o respuesta correcta.</legend>
												</div>
												<div class="col-sm-1">
													<input type="button" style="color:gray;background-color:rgba(24, 124, 105, 0.71); color:white" value="Agregar alternativa" name="agregarAlternativa"/>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<table id="dataTable_alternativas" style=" border-collapse: collapse;border-spacing: 0;width: 100%;border: 1px solid #ddd;">

													</table>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-5">
													<h4>Pista y/o descripción de la pregunta:</h4>
												</div>
												<div class="col-sm-5">
													<td align="center">
														<textarea type="text" name="descripcionPreg" id="descripcionPreg" class="cajastexto" maxlength="500" placeholder="Descripcion de la pregunta" required></textarea>
													</td>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-8">
													<h4>Grado de dificutad.</h4>
												</div>
												<div class="col-sm-4" align="justify">
													<div class="radio">
														<label><input type="radio" name="grado" value="facil" checked required>Fácil</label>
													</div>
													<div class="radio">
														<label><input type="radio" name="grado" value="moderado">Moderado</label>
													</div>
													<div class="radio">
														<label><input type="radio" name="grado" value="dificil">Difícil</label>
													</div>

												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<h4>Imagen (opcional).</h4>
													<input id="fileInput" name="file" type="file" onchange="cargarImagen(this.files)" style="display: none">
													<canvas id="salida" width="300" height="300" style="border:2px solid #d3d3d3; position: relative;" onclick="document.getElementById('fileInput').click();"></canvas>
												</div>
											</div>


											<table>

												<!--
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
								
								<div class="col-sm-1">
									<div id="separacion" style="border-left:1px solid hsla(100, 10%, 50%,100)"></div>
								</div>
								<div class="col-sm-5" align="center">
									<center>
										<h2>Ingreso xlsx.</h2>
										<div class="row">
											<form action="../Recursos/Funciones/ExcelPreguntas" id="preguntasG" name="preguntasG" method="POST"  enctype="multipart/form-data">
												<div class="row">
													<div class="col-sm-5">
														<h4>Asignatura:</h4>
													</div>
													<div class="col-sm-5">
														<select name="nombreMateria" id="nombreMateria" class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" required>
															<?php for ($i=0; $i < count ($asignaturasDisponibles); $i++) {?>
															<option value="<?= $asignaturasDisponibles[$i]['name']; ?>">
																<?= $asignaturasDisponibles[$i]['name']; ?>
															</option>
															<?php } ?>
														</select>
													</div>
													<div class="row">
												<div class="col-sm-5">
													</h4><input type="text"  name="tema" id="tema" class="cajastexto" maxlength="40" placeholder="Tema" required>
												</div>
											</div>
													<label for="archivo">Archivo (xls o xlsx): </label>
													<input type="file" name="archivo" id="archivo" required> Suba un archivo en formato xlsx (Archivo de excel).
													<input type="submit" id="ingresoprG" name="ingresoprG" value="Registrar" class="botones">
												</div>
											</form>
										</div>
										<div class="row"> <div class="col-lg-1"></div></div>
										<div class="row">
											<form action="../Recursos/Funciones/Descomprimir" method="POST"  enctype="multipart/form-data">
												<div class="row">
													
													<label for="archivo">Archivo (zip): </label>
													<input type="file" name="archivo" id="archivo" required> Suba un archivo en formato zip con los archivos requeridos.
													<input type="submit" name="ingresoComprimido" value="Registrar" class="botones">
												</div>
											</form>
										</div>
									</center>
								</div>
							</div>
						</div>
									
						
					</div>

				</span>
			</div>
		</main>
	</div>
</body>
<script>
	$( "input[name=agregarAlternativa]" ).click( function () {
		var cant = $( '#dataTable_alternativas tr' ).length;
		$( "#dataTable_alternativas" ).append(
			'<tr id="row"><td style="height: 40px; background-color:#ffffff; color:gray"><h4>Alternativa respuesta ' + ( cant + 1 ) + ':</h4>  </td>' +
			'<td style="height: 40px; background-color:#ffffff; color:gray"><input type="text" class="cajastexto" id="alternativa3" name="alternativa[]" placeholder="alternativa ' + ( cant + 1 ) + '" required/> <input type="radio" id="respuestacorrecta' + ( cant + 1 ) + '" name="respuestacorrecta" value="' + ( cant ) + '" required/> <label for="respuestacorrecta3">Respuesta Correcta?</label> </td>' +
			'<td style="height: 40px; background-color:#ffffff; width:40px" ><input style="color:gray;background-color:rgba(24, 124, 105, 0.71); color:white;width:100%;height:100%" "  type="button" name="eliminarfila" id="eliminarfila-' + cant + '" onClick="borrarfila(this.id)" value="Eliminar"></td></tr>'
		);
		filas += 1;
	} );

	function borrarfila( x ) {
		var aux = x.split( "-" );
		x = aux[ 1 ];
		var table = document.getElementById( "dataTable_alternativas" );
		var cant = table.rows.length - 1;
		table.deleteRow( x );
		for ( var i = x; i < cant; i++ ) {
			var dat = "eliminarfila-" + ( parseInt( i ) + 1 );
			var dat2 = "eliminarfila-" + i;
			document.getElementById( dat ).id = dat2;
		}
		$( '#dataTable_alternativas' ).trigger( 'change' );
	}
	jQuery.datetimepicker.setLocale( 'es' );
	jQuery( document ).ready( function () {
		'use strict';
		jQuery( '#fechaInicio, #fechaFin' ).datetimepicker();;
	} );

	$( 'form' ).on( 'keyup change keydown keypress', 'input, select, textarea', function () {
		console.log( 'Se ha cambiado un valor!' );
	} );
</script>
<?php
if ( $_POST ) {
	$filename ='';
	if ($_FILES){
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$fileContents = file_get_contents($_FILES['file']['tmp_name']);
		$mimeType = $finfo->buffer($fileContents);
		if (($mimeType === "image/png" && strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], ".") + 1)) === "png") || ($mimeType === "image/jpeg" && strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], ".") + 1)) === "jpg")) {
			$nombre=date("YmdGis").".".(strtolower(substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], ".") + 1)));
			$filename='./Recursos/Preguntas/'.$nombre;
			switch(strtolower($_FILES['file']['type']))
			{
				case 'image/jpeg':
					$image = imagecreatefromjpeg($_FILES['file']['tmp_name']);
				break;
				case 'image/png':
					$image = imagecreatefrompng($_FILES['file']['tmp_name']);
				break;
				case 'image/gif':
					$image = imagecreatefromgif($_FILES['file']['tmp_name']);
				break;
				default:
				exit('Unsupported type: '.$_FILES['file']['type']);
			}	
			$max_width = 450;
			$max_height = 450;
							// Get current dimensions
			$old_width  = imagesx($image);
			$old_height = imagesy($image);
			// Calculate the scaling we need to do to fit the image inside our frame
			$scale      = min($max_width/$old_width, $max_height/$old_height);
			// Get the new dimensions
			$new_width  = ceil($scale*$old_width);
			$new_height = ceil($scale*$old_height);
			// Create new empty image
			$new = imagecreatetruecolor($new_width, $new_height);
			// Resize old image into new
			imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
			ob_start();
			imagejpeg($new, NULL, 90);
			$data = ob_get_clean();
		}
		file_put_contents( ".".$filename, $data);
	}
	$data = array(
		"subject" => $_POST['nombreMateria'],
		"tittle" => $_POST['nombrePreg'],
		"tema" => $_POST['tema'],
		"description" => $_POST['descripcionPreg'],
		"image" => $filename, 
		"options" => $_POST['alternativa'],
		"answer" => $_POST['alternativa'][ $_POST['respuestacorrecta']],
		"additional_info" => array(),
		"degree"=> $_POST['grado']
	);
	$rest -> sendPost($url."questions", $data);
	print "<script> alert('Pregunta agregada exitosamente.');</script>";
}

?>

<style>
	.archivo {
		width: 80px;
		height: 80px;
		overflow: hidden;
	}
	
	#fileInput {
		background-image: url("src/fotografia.png");
		height: 100px;
		background-size: 100px 100px;
		padding-left: 100px;
	}
</style>

<script>
	var image;

	function cargarImagen( files ) {
		var file = files[ 0 ];

		var reader = new FileReader();

		reader.onload = function ( e ) {

			var output = document.getElementById( "salida" );
			var context = output.getContext( "2d" );
			context.clearRect( 0, 0, 300, 300 );
			//salida.style.backgroundImage = "url('" + e.target.result + "')";
			var image = new Image()
			image.src = e.target.result

			image.onload = function () {
				var relacion = image.width / image.height;
				var alto = 300;
				var ancho = alto * relacion;

				var tamano = 300 - ancho;
				var mitadx = tamano / 2; // ( ancho / 2 );
				var mitady = 0; //125 - ( alto / 2 );

				context.drawImage( image, mitadx, mitady, ancho, alto );
			}
		};
		reader.readAsDataURL( file );
	}


	var imgs;


	window.onload = function () {
		var img = new Image();
		img.src = "./src/fotografia.png";
		var canvas = document.getElementById( "salida" );
		var ctx = canvas.getContext( "2d" );
		//var img = document.getElementById("scream");
		ctx.drawImage( img, 0, 0, 200, 200 );

		imgs = document.getElementById( "salida" );
		imgs.ondragenter = msImg;
		imgs.ondragover = msImg;
		imgs.ondrop = limpiar;
	}

	function msImg( e ) {
		e.stopPropagation();
		e.preventDefault();
	}

	function limpiar( e ) {
		e.stopPropagation();
		e.preventDefault();

		var data = e.dataTransfer;
		var files = data.files;


		cargarImagen( files );
	}
	
	$("#separacion").height($("#contenido").height());
</script>

</html>