<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	
	$data = array();
	
	$search = '{ "where": { "code" : "'.$_POST['id'].'" } }';
	$curso = $rest -> sendGet($url."courses?filter=".urlencode($search), $data);
	$cursoD = json_decode($curso, true);
	
	$search = '{ "where": { "rol." : "Estudiante" } }';
	$alumnosDisponibles = $rest -> sendGet($url."people?filter=".urlencode($search), $data);
	$alumnosD = json_decode($alumnosDisponibles, true);
	
	$search = '{ "where": { "code" : "'.$_POST['id'].'" } }';
	$gruposDisponibles = $rest -> sendGet($url."groups?filter=".urlencode($search), $data);
	$grupos = json_decode($gruposDisponibles, true);
	
	for ($i=0; $i<count($cursoD[0]["students"]); $i++){
		$alumnoEncontrado=searchJson($alumnosD, $cursoD[0]["students"][$i]);
		$alumnosFinal[$i]= array ( 0 => $alumnoEncontrado["identification"],  1 => $alumnoEncontrado["name"]);
	}
	for ($i=0; $i<count($grupos); $i++){
		for ($j=0; $j<count($grupos[$i]["members"]); $j++){
			$alumnoGrupo=searchJson($alumnosD, $grupos[$i]["members"][$j]);
			$alumnoGrupoFinal[$i][$j] = $alumnoGrupo["name"];
		}
	}
	if (@isset($_POST['individual'])){
		$data = array (
			"code" => $_POST['id'],
			"name" => $_POST['nombreGrupo'],
			"members" => $_POST['estudiantes']
		);
		$rest -> sendPost($url."groups", $data);
	}
	if (@isset($_POST['grupoAleatorio'])){
		
		shuffle($alumnosFinal);
		$cantidadGrupos = count($alumnosFinal) / $_POST['cantidad'];
		$gruposT = array_chunk($alumnosFinal, $_POST['cantidad'] );
		if (isset($gruposT[intval($cantidadGrupos)])){
			$j=0;
			for ($i=0; $i < count($gruposT[intval($cantidadGrupos)]); $i++){
				if (!isset($gruposT[$j])){
					$j=0;
				}
				$gruposT[$j][count ($gruposT[$j])] = $gruposT[intval($cantidadGrupos)][$i];
				$j++;
			}
			unset($gruposT[intval($cantidadGrupos)]);
			shuffle($gruposT);
			
		} else {
			shuffle($gruposT);
		}
		$cant=count($grupos);
		for ($i=0; $i < count($gruposT);$i++ ){
			for ($j=0; $j < count( $gruposT[$i]);$j++ ){
				$array[$i][$j] = $gruposT[$i][$j][0];
			}
		}
		for ($i=0; $i < count($array);$i++ ){
			$data = array (
				"code" => $_POST['id'],
				"name" => "".$cant,
				"members" => $array[$i]
			);
			$cant++;
			$rest -> sendPost($url."groups", $data);
			//print "<pre>"; print_r($data); print "</pre>";
		}
	}

	
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
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent">
						<center>
							<h2>Crear grupo</h2>
							<br>
							<form action="#" method="POST">
								<div id="row">
									<div class="col-sm-5">
										Nombre:
									</div>
									<div class="col-sm-5">
										<input type="text" class="cajastexto" name="nombreGrupo" placeholder="Nombre del grupo" required>
									</div>
								</div>
								<div id="row">
									<div class="col-sm-5">
										Integrantes: 
									</div>
									<div class="col-sm-5">
										<input type="text" name="id" value="<?= $_POST['id']; ?>" style="display:none">
										<select name="estudiantes[]" id="estudiantes" class="selectpicker show-tick" multiple data-live-search="true" title="Seleccione una asignatura." required>
										<?php
										for ($i=0;$i < count($alumnosFinal); $i++){?>
											<option value="<?= $alumnosFinal [$i][0];?>"><?= $alumnosFinal [$i][1];?> </option>
										<?php }	
										?>
										</select>
									</div>
								</div>
								
								<input type="submit" value="Añadir grupo" name="individual" class="botones" >
							</form>
							
							<form action="#" method="post">
								<div id="row">
									<input type="text" name="id" value="<?= $_POST['id']; ?>" style="display:none">
									<div class="col-sm-5">
										Cantidad de integrantes por grupo
									</div>
									<div class="col-sm-5">
										<input type="number" min="1" step="1" name="cantidad" class="cajastexto" max="<?= count($cursoD[0]['students']);?>"  required>
									</div>
								</div>
								<input type="submit" name="grupoAleatorio" value="Generar grupos aleatoriamente" class="botones" >
							</form>
							<hr>
							<h2>Lista de grupos.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Nombre del grupo
											</th>
											<th scope="col"  class="text-center">
												Estudiantes
											</th>
											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td scope="row"  class="text-center">
												1
											</td>
											<td scope="row" class="text-center">
												Todos
											</td>
											<td scope="row" class="text-center">
												Todos
											</td>
										</tr>
										<?php 
										for ($i=1; $i < count($grupos); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row" class="text-center">
												<?= $grupos [$i] ["name"]; ?>
											</td>
											<td scope="row" class="text-justify">
												<table>
													<?php for ($j=0; $j< count( $grupos [$i] ["members"]); $j++){ ?>
														<tr><td><?= $alumnoGrupoFinal [$i] [$j]; ?></td></tr>
													<?php } ?>
												</table>
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