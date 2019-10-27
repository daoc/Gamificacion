<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	$data = array();
	
 	$search = '{ "where": { "course" : "'.$_GET['id'].'" } }';
	$actividadesDisponibles = $rest -> sendGet($url."activities?filter=".urlencode($search) , $data);
	$actividadesDisponibles = json_decode($actividadesDisponibles, true);

	$search = '{ "where": { "code" : "'.$_GET['id'].'" } }';
	$grupos = $rest -> sendGet($url."groups?filter=".urlencode($search) , $data);
	$grupos = json_decode($grupos, true);

	
	
	for ($i = 0; $i < count($actividadesDisponibles); $i++){

		for ($k = 0; $k < count ($grupos); $k++){
			if ($grupos[$k]["name"] == $actividadesDisponibles[$i]["groups"][$i] )
				$grupoCalificar = $grupos[$k]["members"];
			
		}
		for ($k = 0; $k < count ($grupoCalificar); $k++){
			$search1 = '{ "where": { "identification" : "'.$grupoCalificar[$k].'" } }';
			$persona = $rest -> sendGet($url."people?filter=". urlencode($search1) , $data);
			$persona = json_decode($persona, true);
			$person[] = array ( "email" => $grupoCalificar[$k], "name" => $persona[0]["name"]);
		}	
		
		
	}
	//print "<pre>";print_r($notasD);print "</pre>";
		//print_r($notasD);

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
		tr:hover td {
			cursor: pointer; 
			color: firebrick;
		}
	</style>
</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
							<div class="cent" style="width: 80%">
						<center>
							<h2>Reporte.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda"/>
								<table class="table" id="tablacontenido" style="border: thin; border-color: #B53739" width="75%">
									<thead>
										<tr>
											<th scope="col"  class="text-center">
												#
											</th>
											<th scope="col"  class="text-center">
												Estudiante
											</th>
											<?php for ($i=0; $i < count($actividadesDisponibles); $i++) {	?>
												<th scope="col" class="text-center">
													Actividad <?= $i+1; ?><br/> <?= $actividadesDisponibles[$i]["game"]; ?>
												</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php 
										for ($i=0; $i < count($person); $i++) {	?>
										<tr>
											<td scope="row"  class="text-center">
												<?= $i+1;?>
											</td>
											<td scope="row" class="text-center">
												<?= $person [$i] ["name"]; ?>
											</td>
											<td scope="row" class="text-center">
												<?php
													$search1 = '{ "where": { "student" : "'.$person [$i] ["email"].'" } }';
													$nota = $rest -> sendGet($url."grades?filter=". urlencode($search1) , $data);
													$notasD = json_decode($nota, true);
																		//	  print "<pre>"; print_r($nota);print "</pre>";
													if ( count($notasD)==0)
														print "0";
													else {
														for ($k =0; $k < $actividadesDisponibles[0]["tries"]; $k++){
															@$search = '{ "where": { "tittle" : "'.$notasD [$k] ["question"].'" } }';
															$pregunta = $rest -> sendGet($url."questions?filter=". urlencode($search) , $data);
															$pregunta = json_decode($pregunta, true);
															switch (@$pregunta[0]["degree"] ){
																case "facil":
																	$max [$k] = $actividadesDisponibles[0]["ponderation_per_question"][0];
																	$notasXpromedio [$k]= $notasD [$k] ["grade"] * $max [$k];
																	print $notasD[$k] ["grade"] * $actividadesDisponibles[0]["ponderation_per_question"][0]. "/".$actividadesDisponibles[0]["ponderation_per_question"][0]. "<br/><br/>";
																	break;
																case "moderado":
																	$max [$k] = $actividadesDisponibles[0]["ponderation_per_question"][1];
																	$notasXpromedio [$k]= $notasD [$k] ["grade"] * $max [$k];
																	print $notasD [$k] ["grade"] * $actividadesDisponibles[0]["ponderation_per_question"][1]. "/".$actividadesDisponibles[0]["ponderation_per_question"][1]. "<br/><br/>";
																	break;
																case "dificil":
																	$max [$k] = $actividadesDisponibles[0]["ponderation_per_question"][2];
																	$notasXpromedio [$k]= $notasD[$k] ["grade"] * $max [$k];
																	print $notasD [$k] ["grade"] * $actividadesDisponibles[0]["ponderation_per_question"][2]. "/".$actividadesDisponibles[0]["ponderation_per_question"][2]. "<br/><br/>";
																	break;
																default:
																	break;
															}
														}
													}				   
												?>
											</td>
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