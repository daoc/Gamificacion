<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	if (isset($_POST['nombreAsignatura'])){
		
		$asignaturas = explode(",", str_replace( ", ",",", $_POST['nombreAsignatura']));
		if (count ($asignaturas) > 1){
			for ( $i =0; $i<count ($asignaturas); $i++ ){
				$datos = array( 
				"name" => $asignaturas[$i],
				"proffesors" => array ( )
			);
			$rest -> sendPost($url."subjects", $datos);
			}
		} else{
			$datos = array( 
				"name" => $_POST['nombreAsignatura'],
				"proffesors" => array ( )
			);
			$rest -> sendPost($url."subjects", $datos);
		}
			
	}
	
	$data = array();
	$asignaturasDisponibles = $rest -> sendGet($url."subjects", $data);
	$asignaturasDisponibles = json_decode($asignaturasDisponibles, true);
	$data = array("where" => array("role." => "Profesor") );
	$profesoresDisponibles = $rest -> sendGet($url."people", $data);
	$profesoresDisponibles = json_decode($profesoresDisponibles, true);

	//print_r($profesoresDisponibles);


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
							<h2>Añadir asignaturas</h2>
							<br>
							<form action="#" method="POST">
								<h8>En caso de agregar más de una asignatura separar con <h8 style="color:red">","</h8></h8>
								<br><br>
								<table>
									<tbody>
										<tr>
											<td>
												Nombre de la(s) asignatura(s)
											</td>
											<td>
												<input type="text" class="cajastexto" name="nombreAsignatura" placeholder="Nombre de la asignatura" required>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Añadir asignatura" class="botones" >
							</form>
							<hr>
							<h2>Lista de asignaturas.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Nombre Asignatura
											</th>
											<th scope="col"  class="text-center">
												Instructores Asignados
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($asignaturasDisponibles); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row" class="text-center">
												<?= $asignaturasDisponibles [$i] ["name"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<form action="#" name="asignatura" method="post">
												<?php 
												if ( count( $asignaturasDisponibles [$i]["proffesors"] ) == 0 ){
													print "No hay instructores asignados.";
												} else {
													if ( count( $asignaturasDisponibles [$i]["proffesors"] ) == 1 ){
														$results = searchJson( $profesoresDisponibles , $asignaturasDisponibles [$i]["proffesors"][0] );
														print $results["name"];
													} else {														
														print "<center><table>";
														for ($j=0; $j< count( $asignaturasDisponibles [$i]["proffesors"] ) ; $j++){
															$results = searchJson( $profesoresDisponibles , $asignaturasDisponibles [$i]["proffesors"][$j] );			
															print "<tr><td align='center'>".$results["name"]."</td></tr>";
														}	
														print "</table></center>";
													}
												}					  

												
												?>
												</form>
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