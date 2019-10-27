<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/conexion.php"; include "../Recursos/Funciones/AdministradorSesiones.php"; ?>
	
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script src="../Recursos/Javascript/bootstrap-select.js"></script>
	<link rel="stylesheet" href="../Recursos/css/bootstrap-select.css">
	<link rel="stylesheet" href="../Recursos/css/estilos.css">
	
	
	<script src="../Recursos/Javascript/comprobacionbase.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Recursos/Javascript/jquery.datetimepicker.full.js"></script>
	<link rel="stylesheet" href="../Recursos/css/jquery.datetimepicker.min.css">
	<style>
		tr:hover td {
			cursor: pointer; 
			color: firebrick;
		}
	</style>
	<script src="../Recursos/Javascript/jquery.popupwindow.js"></script>

</head>
<body>
	<div class="wrapper" >
		
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<table id="tablacontenido">
								<thead>
									<tr>
										<th align="center">
											<h3>Pregunta</h3>
										</th>
										<th align="center">
											<h3>Nota</h3>
										</th>	
										<th align="center">
											<h3>Nota Final</h3>
										</th>
									</tr>
								</thead>
								<tbody>
							<?php 	
									$idprueba = $_GET['prueba'];
									$usuarioprueba = $_GET['id'];
									$sql= "SELECT * FROM `test_questions` tq inner join questions q on tq.QUESTIONS_QUE_ID = q.QUE_ID where tq.`TEST_TST_ID`='$idprueba' and tq.USERS_USER_ID='$usuarioprueba'";
									$r = mysqli_query( $link, $sql ); 
									while ( $renglon = mysqli_fetch_object( $r ) ) {
									?>
									<tr >
										<td align="center">
											<?= $renglon->QUE_TITLE; ?>
										</td>
										<td align="center">
											<?= $renglon->TXQ_GRADE; ?>
										</td>
										<td align="center">

										</td>

									</tr>
							<?php 	} 	
									function consultaNota ($usuarioprueba, $idprueba ){
										include("../Recursos/Funciones/conexion.php");
										$sql = "select TXQ_GRADE from test_questions where USERS_USER_ID='$usuarioprueba' and TEST_TST_ID='$idprueba'";
										
										$r = mysqli_query( $link, $sql );
										$notatotal=0;
										$i=0;
										while ( $renglon = mysqli_fetch_object( $r ) ) {
											$notatotal+=$renglon->TXQ_GRADE;
											$i++;
										}
										return number_format(round( $notatotal/$i , 2) , 2 ) ;
									}

									?>
									<tr >
										<td align="center">
											Nota final Promediada.
										</td>
										<td align="center">

										</td>
										<td align="center">
											<?= consultaNota ($usuarioprueba, $idprueba); ?>
										</td>

									</tr>
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

</html>