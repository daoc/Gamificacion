<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	
	if(isset($_POST['juego'])){
		$disponibilidad = explode(" - ", $_POST['disponibilidad']);
		$data= array(
			"game" => $_POST['juego'],
			"course" => $_POST['id'],
			"profesour" => $cedulaUser,
			"groups" => $_POST['grupos'],
			"start" => $disponibilidad[0],
			"end" => $disponibilidad[1],
			"tries" => $_POST['intentos'],
			"time_per_question" => $_POST['segundos'],
			"ponderation_per_question" => $_POST['dificultadPregunta'],
			"ponderation" => $_POST['ponderacionNota'],
			"question_list" => $_POST['preguntas']
		);
		//print_r ($data);
		$rest -> sendPost($url."activities", $data);
	}
	
	$data = array();
	$search = '{ "where": { "code":"'. $_POST['id'] .'" }}';
	$gruposDisponibles = $rest -> sendGet($url."groups?filter=".urlencode($search), $data);
	$gruposDisponibles = json_decode($gruposDisponibles, true);
	//print_r($gruposDisponibles);
	$juegosDisponibles = $rest -> sendGet($url."games", $data);
	$juegosDisponibles = json_decode($juegosDisponibles, true);
	$materia = explode("-", $_POST['id']);
	$search = '{ "where": { "subject":"'. $materia[0] .'" }}';
	$preguntasDisponibles = $rest -> sendGet($url."questions", $data);
	$preguntasDisponibles = json_decode($preguntasDisponibles, true);
	
	$search = '{ "where": { "course":"'. $_POST['id'] .'" }}';
	$actividadesDisponibles = $rest -> sendGet($url."activities?filter=".urlencode($search), $data);
	$actividadesDisponibles = json_decode($actividadesDisponibles, true);
	//print_r($actividadesDisponibles);
	// no se si ocupare
	
	
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

</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center; width: 85%">
					<div class="cent"  style="width: 60%">
						<center>
							<h2>Crear Actividad</h2>
							<br>
							<form action="#" method="POST">
								<div id="row">
									<div class="col-sm-1">
										Juego:
										<input type="text" value="<?= $_POST['id']; ?>" name="id" style="display: none">
									</div>
									<div class="col-sm-5">
										<select name="juego" id="juego" class="selectpicker show-tick" data-live-search="true" title="Seleccione un juego." required>
										<?php
										for ($i=0;$i < count($juegosDisponibles); $i++){?>
											<option value="<?= $juegosDisponibles [$i]["name"];?>"><?= $juegosDisponibles [$i]["name"];?> </option>
										<?php }	?>
										</select>
									</div>
									<div class="col-sm-2">
										Preguntas:
									</div>
									<div class="col-sm-4">
										<select name="preguntas[]" id="preguntas[]" class="selectpicker show-tick" data-live-search="true" multiple  data-actions-box="true" title="Seleccione las preguntas de la actividad." required>
										<?php
										for ($i=0;$i < count($preguntasDisponibles); $i++){?>
											<option value="<?= $preguntasDisponibles [$i]["id"];?>"><?= $preguntasDisponibles [$i]["tittle"];?> </option>
										<?php }	?>
										</select>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<lengend>Tiempo de disponibilidad</lengend>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<input type="text" name="disponibilidad" class="cajastexto"  width="300px" required />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<lengend>Parámetros de evaluación</lengend>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-2">
										Intentos:
									</div>
									<div class="col-sm-3">
										<input type="number" step="1" value="1" min="1" name="intentos" class="cajastexto" placeholder="Cantidad de intentos por pregunta"  width="300px" required />
									</div>
									<div class="col-sm-2">
										Tiempo por pregunta (segundos):
									</div>
									<div class="col-sm-2">
										<input type="number" step="1"  value="1" min="1" name="segundos" class="cajastexto" placeholder="Cantidad de segundos por pregunta"  width="300px" required />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<lengend>Parámetros de ponderación</lengend>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-2">
										Preguntas:
									</div>
									<div class="col-sm-3">
										Puntaje de preguntas:
									</div>
									<div class="col-sm-4">
										<table>
											<tr>
												<td>Fácil</td>
												<td><input type="number" name="dificultadPregunta[]" class="cajastexto" value="1" step="1" min="1" ></td>
											</tr>
											<tr>
												<td>Moderada</td>
												<td><input type="number" name="dificultadPregunta[]" class="cajastexto" value="3" step="1" min="1" ></td>
											</tr>
											<tr>
												<td>Difícil</td>
												<td><input type="number" name="dificultadPregunta[]" class="cajastexto" value="5" step="1" min="1" ></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-sm-4">
										Ponderación nota final 
									</div>
									<div class="col-sm-4">
										<input type="number" name="ponderacionNota" class="cajastexto" value="10" step="1" min="1" max="10" >
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-sm-12">
										<lengend>Asignación de grupos</lengend>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-5">
										Grupos:
									</div>
									<div class="col-sm-5">
										<select name="grupos[]" id="grupos[]" class="selectpicker show-tick" multiple data-live-search="true" title="Seleccione el/los grupos." required>
										<?php
										for ($i=0;$i < count($gruposDisponibles); $i++){?>
											<option value="<?= $gruposDisponibles [$i]["name"];?>"><?= $gruposDisponibles [$i]["name"];?> </option>
										<?php }	?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-sm-4"></div>
									<div class="col-sm-4">
										<div class="checkbox-inline">
											<label><input type="checkbox" name="notificar" value="si" >Notificar a estudiantes</label>
										</div>
									</div>
								</div>
								<input type="submit" value="Añadir actividad" class="botones" >
							</form>
							
							
							<hr>
							<h2>Lista de Actividades.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Juego
											</th>
											<th scope="col"  class="text-center">
												Grupos
											</th>
											<th scope="col"  class="text-center">
												Tiempo de disponibilidad
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($actividadesDisponibles); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row" class="text-center">
												<?= $actividadesDisponibles [$i] ["game"]; ?>
											</td>
											<td scope="row" class="text-justify">
												<table>
													<?php for ($j=0; $j< count( $actividadesDisponibles [$i] ["groups"]); $j++){ ?>
														<tr><td><?= $actividadesDisponibles [$i] ["groups"] [$j]; ?></td></tr>
													<?php } ?>
												</table>
											</td>
											<td scope="row" class="text-center">
												<?= $actividadesDisponibles [$i] ["start"]; ?> - <?= $actividadesDisponibles [$i] ["end"]; ?>
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
$(function() {
  $('input[name="disponibilidad"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'M/DD hh:mm A'
    }
  });
});

</script>

</html>