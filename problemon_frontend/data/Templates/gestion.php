<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	
	$data = array();
	$search = '{ "where": { "id" : "'.$_GET['id'].'" } }';
	$curso = $rest -> sendGet($url."courses?filter=".urlencode($search), $data);
	$cursoD = json_decode($curso, true);
	$search = '{ "where": { "rol." : "Estudiante" } }';
	$alumnosDisponibles = $rest -> sendGet($url."people?filter=".urlencode($search), $data);
	$alumnosD = json_decode($alumnosDisponibles, true);
	
	$search = '{ "where": { "code" : "'.$cursoD[0]['code'].'" } }';
	$gruposDisponibles = $rest -> sendGet($url."groups?filter=".urlencode($search), $data);
	$grupos = json_decode($gruposDisponibles, true);
	//print_r($grupos);
	for ($i=0; $i<count($cursoD[0]["students"]); $i++){
		$alumnoEncontrado=searchJson($alumnosD, $cursoD[0]["students"][$i]);
		$alumnosFinal[$i]= $alumnoEncontrado["name"];
	}
	
	$search = '{ "where": { "course":"'. $cursoD[0]['code'] .'" }}';
	$actividadesDisponibles = $rest -> sendGet($url."activities?filter=".urlencode($search), $data);
	$actividadesDisponibles = json_decode($actividadesDisponibles, true);
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Recursos/Javascript/jquery.datetimepicker.full.js"></script>
	<link rel="stylesheet" href="../Recursos/css/jquery.datetimepicker.min.css">
	<script src="../Recursos/Javascript/jquery.popupwindow.js"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<link rel="stylesheet" href="https://glyphsearch.com/bower_components/octicons/octicons/octicons.css">

	<script src="../Recursos/Javascript/bootstrap-select.js"></script>
	
	<style>
		table.table tr:hover td {
			cursor: pointer;
			color: firebrick;
		}
	</style>
	
	<?php
	if ($_POST){
		
	}
	?>

</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div id="cent" class="cent" style="width: 90%">
						<center>
							<div class="row">
								<div class="col-lg-3" align="center" >
									<legend>Grupo</legend>
									<form action="crearGrupo" method="POST">
										<input type="text" name="id" value="<?= $cursoD[0]['code']; ?>" style="display:none">
										<input type="submit" class="botones" value="Crear Grupo">
									</form>
									<legend>Lista de grupos</legend>
									<table class="table table-striped table-dark">
										<thead class="thead-dark">
											<tr>
												<th scope="col">#</th>
												<th scope="col">Grupo</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Todos</td>
											</tr>
											<?php  for ($i=1; $i<count($grupos);$i++ ){?>
											<tr>
												<td><?= $i+1; ?></td>
												<td><?= $grupos[$i]["name"]; ?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="col-sm-1">
									<div id="separacion" style="border-left:1px solid hsla(100, 10%, 50%,100)"></div>
								</div>
								
								<div class="col-sm-3">
									<legend>Lista de alumnos</legend>
									<table class="table table-striped table-dark">
										<thead class="thead-dark">
											<tr>
												<th scope="col">#</th>
												<th scope="col">Nombre</th>
											</tr>
										</thead>
										<tbody>
											<?php  for ($i=0; $i<count($alumnosFinal);$i++ ){?>
											<tr>
												<td><?= $i+1; ?></td>
												<td><?= $alumnosFinal[$i]; ?></td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="col-sm-1">
									<div id="separacion2" style="border-left:1px solid hsla(100, 10%, 50%,100)"></div>
								</div>
								<div class="col-sm-3"  align="center">
									<legend>Actividad</legend>
									<form action="crearActividad" method="post">
										<input type="text" name="id" value="<?= $cursoD[0]['code']; ?>" style="display:none">
										<input type="submit" class="botones" value="Crear Actividad">
									</form>
									<legend>Lista de Actividades</legend>
									<table class="table table-striped table-dark">
										<thead class="thead-dark">
											<tr>
												<th scope="col" class="text-center">#</th>
												<th scope="col" class="text-center">Juego</th>
												<th scope="col"  class="text-center">Grupos</th>
												<th scope="col"  class="text-center">Fecha</th>
											</tr>
										</thead>
										<tbody>
											<?php  for ($i=0; $i< count($actividadesDisponibles);$i++ ){?>
											<tr onClick="window.location.href='modificarActividad?actividad=<?=$actividadesDisponibles[$i]["id"];?>'">
												<td	 class="text-center"><?= $i+1; ?></td>
												<td class="text-center"><?= $actividadesDisponibles[$i]["game"]; ?></td>
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
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</center>
					</div>

					<br><br>
				</span>
			</div>
		</main>
	</div>
</body>
<script>
	$("#separacion").height($("#contenido").height());
	$("#separacion2").height($("#separacion").height());
	$("#cent").height($("#separacion").height());
</script>

</html>


