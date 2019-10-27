<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	
	if (isset($_POST['nombreJuego'])){
		$datos = array( 
			"name" => strtoupper( $_POST['nombreJuego'] ),
			"platform" => $_POST['plataforma'],
			"url" => $_POST['urlJuego']
		);
		$rest -> sendPost($url."games", $datos);
	}
	$data = array();
	$juegosDisponibles = $rest -> sendGet($url."games", $data);
	$juegosDisponibles = json_decode($juegosDisponibles, true);
	//print_r($juegosDisponibles);
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

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://glyphsearch.com/bower_components/octicons/octicons/octicons.css">

</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent">
						<center>
							<h2>Añadir un juego</h2>
							<br>
							<form action="#" method="POST">
								<table>
									<tbody>
										<tr>
											<td>
												Nombre Juego
											</td>
											<td>
												<input type="text" class="cajastexto" name="nombreJuego" placeholder="Nombre del juego" required>
											</td>
										</tr>
										<tr>
											<td>
												Plataforma Juego
											</td>
											<td>
												<table>
													<tr>
														<td align="right" width="40%">
															<input class="form-check-input" type="checkbox" name="plataforma[]" value="movil">
														</td>
														<td>&nbsp;&nbsp;&nbsp;Móvil</td>
													</tr>
													<tr>
														<td align="right" width="40%"><input class="form-check-input" type="checkbox" name="plataforma[]" value="web">
														</td>
														<td>&nbsp;&nbsp;&nbsp;Web</td>
													</tr>
													<tr>
														<td align="right" width="40%"><input class="form-check-input" type="checkbox" name="plataforma[]" value="desktop">
														</td>
														<td>&nbsp;&nbsp;&nbsp;Escritorio</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												Url de acceso:
											</td>
											<td>
												<input type="text" class="cajastexto" name="urlJuego" placeholder="URL del juego" required>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Añadir juego" class="botones" >
							</form>
							<hr>
							<h2>Lista de juegos disponibles.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Nombre Juego
											</th>
											<th scope="col"  class="text-center">
												Plataforma
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($juegosDisponibles); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row">
												<?= $juegosDisponibles [$i] ["name"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<?php 
												for ($j=0; $j < count($juegosDisponibles [$i] ["platform"]); $j++) {
													switch ( $juegosDisponibles [$i] ["platform"] [$j] ){
														case "movil":
															print '<span tittle="Juego móvil"><i class="mega-octicon octicon-device-mobile fa-1x"><span></i>&nbsp;&nbsp;&nbsp;';
														break;
														case "desktop" :
															print '<i class="fa fa-desktop fa-1x" tittle="Juego de escritorio"></i>&nbsp;&nbsp;&nbsp;';
														break;
														case "web" :
															print '<i class="mega-octicon octicon-browser fa-1x" tittle="Juego web"></i>&nbsp;&nbsp;&nbsp;';
														break;
													default:
													break;	
													}
														
												}
												?>
											</td>
										</tr>
										<?php	}	?>
									</tbody>
								</table>
						</center>
						<br><br>
					</div>
				</span>
			</div>
		</main>
	</div>
</body>
<script>
	document.querySelector( "#buscar" ).onkeyup = function () {
		$TableFilter( "#tablacontenido", this.value );
	}

	$TableFilter = function ( id, value ) {
		var rows = document.querySelectorAll( id + ' tbody tr' );

		for ( var i = 0; i < rows.length; i++ ) {
			var showRow = false;

			var row = rows[ i ];
			row.style.display = 'none';

			for ( var x = 0; x < row.childElementCount; x++ ) {
				if ( row.children[ x ].textContent.toLowerCase().indexOf( value.toLowerCase().trim() ) > -1 ) {
					showRow = true;
					break;
				}
			}

			if ( showRow ) {
				row.style.display = null;
			}
		}
	}
</script>

</html>