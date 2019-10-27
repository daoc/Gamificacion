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
	
	
	<link rel="stylesheet" href="../Recursos/css/bootstrap-select.css">
	<link rel="stylesheet" href="../Recursos/css/estilos.css">
	
	<script src="../Recursos/Javascript/bootstrap-select.js"></script>
	<script src="../Recursos/Javascript/comprobacionbase.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../Recursos/Javascript/jquery.datetimepicker.full.js"></script>
	<link rel="stylesheet" href="../Recursos/css/jquery.datetimepicker.min.css">
	

</head>
<body>
	<div class="wrapper" >
		
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<h2>Ingreso de evaluaciones.</h2>
							<form action="#" method="POST">
								<table>
									<tr>
										<td>
											<h4>Asignatura:</h4>
										</td>
										<td align="center">
											<select name="nombreMateria" id="nombreMateria"class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" required>
												<?php $sql="select * from subject s inner join permissions p on p.SUBJECT_SUB_ID=s.SUB_ID where p.USERS_USER_ID='$username' ;"; //cambiar instituto despues
												$r = mysqli_query( $link, $sql );
												while ( $renglon = mysqli_fetch_object( $r ) ) {?>
													<option value="<?= $renglon->SUB_ID; ?>"><?= $renglon->SUB_NAME; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Nombre de la evaluación:</h4>
										</td>
										<td align="center">
											<input type="text" name="nombrePrueba" id="nombrePrueba" class="cajastexto" maxlength="100" placeholder="Nombre de la evaluación" required>
										</td>
									</tr>
									<tr>
										<td>
											<h4>Fecha y hora comienzo:</h4>
										</td>
										<td align="center">
											<input type='text' class="cajastexto" id="fechaInicio" name="fechaInicio" placeholder="Fecha inicialización" required/>
									</tr>
									<tr>
										<td>
											<h4>Fecha y hora finalización:</h4>
										</td>
										<td align="center">
											<input type="text" name="fechaFin" id="fechaFin" class="cajastexto" placeholder="Fecha finalización" required>
										</td>
									</tr>
									
								</table>
								<input type="submit" id="ingresopr" name="ingresopr" value="Registrar" class="botonesdisabled">
							</form>
						</center>
					</div>
					
				</span>
			</div>
		</main>
	</div>
</body>
<script>
	jQuery.datetimepicker.setLocale('es');
	jQuery(document).ready(function () {
		'use strict';
		jQuery('#fechaInicio, #fechaFin').datetimepicker();;
	});
	
	
	var bandera=0;
	var materia=document.getElementById('nombreMateria');
	var fstart=document.getElementById('fechaInicio');
	var fend=document.getElementById('fechaFin');
	var nprueba=document.getElementById('nombrePrueba');
	
	
	function comprobarbandera(){
		if ( materia.value != "" && fstart.value != "" && fend.value != "" && nprueba.value != "" ){
			bandera=1;	
		} else {
			bandera=0;
		}
		if ( bandera == 1 ){
			document.getElementById('ingresopr').className="botones";
			document.getElementById('ingresopr').removeAttribute("disabled");  
		} else {
			document.getElementById('ingresopr').disabled=false;
			document.getElementById('ingresopr').className="botonesdisabled";
		}
	}
	
	$('form').on('keyup change keydown keypress', 'input, select, textarea', function(){
		console.log('Se ha cambiado un valor!');
		comprobarbandera();
	});
</script>
<?php
	if( $_POST ){
		$nombreMateria = $_POST['nombreMateria'];
		$fechainicio = $_POST['fechaInicio'];
		$fechafin = $_POST['fechaFin'];
		$nombreprueba = $_POST['nombrePrueba'];
		$sql="INSERT INTO `test`(`TST_NAME`, `TST_DATE_START`, `TST_DATE_END`, SUBJECT_SUB_ID, USERS_USER_ID) VALUES ('$nombreprueba','$fechainicio','$fechafin','$nombreMateria', '$username')";
		$link->query($sql) ;
		print "<script> alert('Prueba Ingresada correctamente.')</script>";
	}
?>
</html>