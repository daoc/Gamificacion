<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	if (isset($_POST['nombreCurso'])){
		$search = '{ "where": { "name" : "'.$_POST['asignatura'].'", "proffesors." : "'.$_POST['instructor'].'" } }';
		$existe = $rest -> sendGet($url."subjects?filter=".urlencode($search), array());
		$existe = json_decode($existe, true);
		if (count($existe) == 0){
			$search = '{ "where": { "name" : "'.$_POST['asignatura'].'" } }';
			$existe = $rest -> sendGet($url."subjects?filter=".urlencode($search), array());
			$existe = json_decode($existe, true);
			$existe  [0] ['proffesors'][count($existe  [0] ['proffesors'])] = $_POST['instructor'];
			$rest -> sendPost($url."subjects", $datos);
		}
		
		$search = '{ "where": { "code" : "'.$_POST['asignatura'].'-'.strtoupper( $_POST['nombreCurso']).'", "period" : "'.$_POST['periodo'].'" } }';
		$existe = $rest -> sendGet($url."courses?filter=".urlencode($search), array());
		$existe = json_decode($existe, true);
		if (count($existe) == 0 ){
			$fecha= explode( "/",  $_POST['periodo']);
			$anio = explode( " - ", $fecha[2]);
			$periodocode=$anio[0]."-".$fecha[4];
			$datos = array( 
				"code" => $_POST['asignatura']."-".strtoupper( $_POST['nombreCurso']."-".$periodocode ),
				"name" => strtoupper( $_POST['nombreCurso'] ),
				"subject" => $_POST['asignatura'],
				"profesour" => $_POST['instructor'],
				"students" => array(
					""
				),
				"type" => $_POST['tipo'],
				"period" => $_POST['periodo']
			);
			$rest -> sendPost($url."courses", $datos);
		} else {
			print "<script>alert('El curso con ese periodo ya existe');</script>";
		}

	}
	$data = array();
	$cursosDisponibles = $rest -> sendGet($url."courses", $data);
	$cursosDisponibles = json_decode($cursosDisponibles, true);
	
	$institutosDisponibles = $rest -> sendGet($url."institutes", $data);
	$institutosDisponibles = json_decode($institutosDisponibles, true);
	$search= '{"where": {"proffesors.": "'.$cedulaUser.'"}}';
	$materiasDisponibles = $rest -> sendGet($url."subjects?filter=".urlencode($search), $data);
	$materiasDisponibles = json_decode($materiasDisponibles, true);
	$search= '{"where": {"rol.": "Profesor"}}';
	$profesoresDisponibles = $rest -> sendGet($url."people?filter=".urlencode($search), $data);
	$profesoresDisponibles = json_decode($profesoresDisponibles, true);
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
							<h2>Añadir un curso</h2>
							<br>
							<form action="#" method="POST">
								<table>
									<tbody>
										<tr>
											<td>
												Nombre Curso
											</td>
											<td>
												<input type="text" class="cajastexto" name="nombreCurso" placeholder="Nombre del curso" required>
											</td>
										</tr>
										<tr>
											<td>
												Asignatura
											</td>
											<td>
												<select name="asignatura" id="asignatura" class="selectpicker show-tick" data-live-search="true" title="Seleccione una asignatura." required>
												<?php
													for ($i=0;$i < count($materiasDisponibles); $i++){?>
														<option value="<?= $materiasDisponibles [$i]['name'];?>"><?= $materiasDisponibles [$i]['name'];?> </option>
													<?php }	
												?>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												Instructor
											</td>
											<td>
												<select name="instructor" id="instructor" class="selectpicker show-tick" data-live-search="true" title="Seleccione un instructor." required>
												<?php
													for ($i=0;$i < count($profesoresDisponibles); $i++){?>
														<option value="<?= $profesoresDisponibles [$i]['identification'];?>"><?= $profesoresDisponibles [$i]['name'];?> </option>
													<?php }	
												?>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												Período de curso
											</td>
											<td>
												<?php 
												if ( date("m")+6 >12 )
													$fecha = ( date("m")+6 )-12 . "-" .date("d")."-". ( date("Y") + 1); 
												else
													$fecha = date("m")+6 ."-".date("d")."-".date("Y");   ?>
												<input type="text" id="daterange" value="<?= date("m-d-Y"); ?> - <?= $fecha; ?>"  class="cajastexto" name="periodo" placeholder="Período de curso" required/>
											</td>
										</tr>
										<tr>
										
											<td>
												Tipo de curso
											</td>
											<td>
												<div class="custom-control custom-radio">
												  <input type="radio" class="custom-control-input" id="defaultChecked" name="tipo" value="Privado" checked>Requiere mátricula
												</div>
												<div class="custom-control custom-radio">
												  <input type="radio" class="custom-control-input" id="defaultUnchecked" name="tipo" value="Público" >Público
												</div>
											</td>
										</tr>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Añadir curso" class="botones" onClick="valthis()">
							</form>
							<hr>
							<h2>Lista de cursos disponibles.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Nombre del Curso
											</th>
											<th scope="col"  class="text-center">
												Asignatura
											</th>
											<th scope="col"  class="text-center">
												Período
											</th>
											<th scope="col"  class="text-center">
												Tipo de curso
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($cursosDisponibles); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row">
												<?= $cursosDisponibles [$i] ["name"]; ?>
											</td>
											<td scope="row">
												<?= $cursosDisponibles [$i] ["subject"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<?= $cursosDisponibles [$i] ["period"]; ?>
											</td>
											<td scope="row"  class="text-center">
												<?= $cursosDisponibles [$i] ["type"]; ?>
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