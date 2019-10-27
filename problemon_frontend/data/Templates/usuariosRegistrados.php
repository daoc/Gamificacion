<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	$data = array();
	if ($_POST){
		$persona = $rest -> sendGet($url."people/".$_POST['id'], $data);
		$persona = json_decode($persona, true);
		if ($_POST["accion"] == "habilitar"){
			$persona ["status"] = "ACTIVO";
		} else {
			$persona ["status"] = "DESHABILITADO";
		}
		$rest -> sendPut($url."people", $persona);
	}

	$personasDisponibles = $rest -> sendGet($url."people", $data);
	$personasDisponibles = json_decode($personasDisponibles, true);
	
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

	<style>
		table.table1 tr:hover td {
			cursor: pointer; 
			color: firebrick;
		}
	</style>
	
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="../Recursos/Javascript/moment.min.js"></script>
<script type="text/javascript" src="../Recursos/Javascript/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../Recursos/css/daterangepicker.css" />
<script>
$(function() {
  $('input[id="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent">
						<center>
							<h2>Lista de usuarios.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Nombre
											</th>
											<th scope="col"  class="text-center">
												Rol
											</th>
											<th scope="col"  class="text-center">
												Nombre de usuario
											</th>
											<th scope="col"  class="text-center">
												Estado
											</th>
											<th scope="col"  class="text-center">
												Acción
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($personasDisponibles); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row" class="text-center">
												<?= $personasDisponibles [$i] ["name"]; ?>
											</td>
											
											<td scope="row"  class="text-center">
												<?php for ($j=0; $j< count($personasDisponibles [$i] ["rol"]); $j++){
													if ($j > 0 && $j < count($personasDisponibles [$i] ["rol"]))
														print " , ";
													print $personasDisponibles [$i] ["rol"] [$j];
												}  ?>
											</td>
											<td scope="row"  class="text-center">
												<?= $personasDisponibles [$i] ["user"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<?= $personasDisponibles [$i] ["status"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<?php if( $personasDisponibles [$i] ["status"] == "ACTIVO")
													print "<form action='#' method='POST'>
													<input type='text' name='accion' style='display:none' value='deshabilitar'> 
													<input type='text' name='id' style='display:none' value='".$personasDisponibles [$i] ["id"]."'> 
													<input type='submit' value='Deshabilitar'>
													</form>";
												else 
													print "<form action='#' method='POST'>
													<input type='text' name='accion' style='display:none' value='habilitar'> 
													<input type='text' name='id' style='display:none' value='".$personasDisponibles [$i] ["id"]."'> 
													<input type='submit' value='Activar'>
													</form>";?>
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