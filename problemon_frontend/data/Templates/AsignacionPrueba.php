<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/conexion.php"; include "../Recursos/Funciones/AdministradorSesiones.php"; 
	if($_POST){
		$sql="select * from person p inner join users u on p.PER_ID = u.PERSON_PER_ID inner join permissions per on u.USER_ID = per.USERS_USER_ID  where  u.USER_TYPE_UT_ID = '1' and per.INSTITUTE_INS_ID='$instituto' and per.SUBJECT_SUB_ID='$materia'";
		$r = mysqli_query( $link, $sql );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$estudiantes[] = $renglon->USER_ID;
		}  
		
		$preguntas = $_POST['preguntas'];
		$idprueba = $_POST['idprueba'];
		@$idjuego = $_POST['idjuego'];
		$idjuego=1; //cambiar depsues
		$cantpregmax = $_POST['cantpregmax'];
		for( $i = 0; $i< count($estudiantes) ; $i++ ){
			$borrar= " delete from test_questions where USERS_USER_ID='$estudiantes[$i]' and TEST_TST_ID='$idprueba' and GAMES_GAM_ID='$idjuego'";
			$link->query($borrar);
		}
		for( $i = 0; $i< count($estudiantes) ; $i++ ){
			shuffle($preguntas);
			for ( $j = 0; $j < $cantpregmax ; $j++){
				$sql=" INSERT INTO `test_questions`(`TEST_TST_ID`, `QUESTIONS_QUE_ID`, `GAMES_GAM_ID`, `TXQ_GRADE`, `STATUS_STAT_ID`, `USERS_USER_ID` ) VALUES ('$idprueba' ,'$preguntas[$j]' ,'$idjuego' ,0 ,1 ,'$estudiantes[$i]'  ) ";
				$link->query($sql);
			}
		}
			print "<script>alert('La prueba ha sido generada para cada estudiante satisfactoriamente')</script>";
	}
	
	if( $_GET ) {
		$idprueba = base64_decode( $_GET['id'] ) ;
		$sql="select * from test where `TST_ID`='$idprueba' ;"; 
		$r = mysqli_query( $link, $sql );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$nombreprueba = $renglon->TST_NAME;
			$materia = $renglon->SUBJECT_SUB_ID;
		}  
		
		$sql= "SELECT `QUE_ID`, `QUE_TITLE` FROM `questions` WHERE `SUBJECT_SUB_ID`='$materia'";
		$r = mysqli_query( $link, $sql );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$idpregun[] = $renglon->QUE_ID;
			$titulopregun[] = $renglon->QUE_TITLE;
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

</head>
<body>
	<div class="wrapper" >
		
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<form action="#" method="POST">
								<table>
									<tr>
										<td>
											<h4>Asignatura:</h4>
										</td>
										<td align="center">
											<input type="text" value="<?= $idprueba ?>" name="idprueba" style="display: none">
											<select name="nombreMateria" id="nombreMateria"class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" onChange="getLista(this.value)" required>
												<?php $sql="select * from subject where SUB_ID='$materia' ;"; 
												$r = mysqli_query( $link, $sql );
												while ( $renglon = mysqli_fetch_object( $r ) ) {?>
													<option value="<?= $renglon->SUB_ID; ?>" selected><?= $renglon->SUB_NAME; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Prueba:</h4>
										</td>
										<td align="center">
											<input type="text" value="<?= $nombreprueba ?>" readonly class="cajastexto">
										</td>
									</tr>
								</table>
								<h2>Lista de preguntas.</h2>
								<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda" />
								<table id="tablacontenido">
									<thead>
										<tr>
											<th style="text-align: center">Título</th>
											<th style="text-align: center">Seleccionar</th>
										</tr>
									</thead>
									<tbody>
								<?php for($i=0; $i< count( $idpregun ); $i++ )	{	?>
										<tr style="border-bottom: dashed 1px">
											<td><?= $titulopregun[$i]; ?></td>
											<td align="center"> <input name="preguntas[]" type="checkbox" checked="checked" value="<?= $idpregun[$i]; ?>"/> </td>
										</tr>
								<?php	}	?>
										<tr>
											<td>
												Cantidad Máxima de preguntas para los estudiantes:
											</td>
											<td>
												<input type="number" max="<?= $i ?>" min="1" value="<?= round($i/2,0) ?>" name="cantpregmax" </input>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="submit" value="Ok" class="botones">
							</form>
						</center>
						<br><br>
					</div>
				</span>
			</div>
		</main>
	</div>
</body>
	<script>
		document.querySelector("#buscar").onkeyup = function(){
			$TableFilter("#tablacontenido", this.value);
		}

		$TableFilter = function(id, value){
			var rows = document.querySelectorAll(id + ' tbody tr');

			for(var i = 0; i < rows.length; i++){
				var showRow = false;

				var row = rows[i];
				row.style.display = 'none';

				for(var x = 0; x < row.childElementCount; x++){
					if(row.children[x].textContent.toLowerCase().indexOf(value.toLowerCase().trim()) > -1){
						showRow = true;
						break;
					}
				}

				if(showRow){
					row.style.display = null;
				}
			}
		}	
	</script>
</html>