<!DOCTYPE html>
<html lang="es">
<?php include "./Recursos/Funciones/AdministradorSesiones.php" ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./Recursos/css/bootstrap-select.css">
	<link rel="stylesheet" href="./Recursos/css/estilos.css">
	
	<script src="./Recursos/Javascript/bootstrap-select.js"></script>
	<script src="./Recursos/Javascript/comprobacionbase.js"></script>
	<script src="./Recursos/Javascript/valcedruc.js"></script>
</head>
<body>
	<div class="wrapper" >
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<h2>Seleccione el tipo de usuario con el que desea ingresar.</h2>
							<form action="Sistema" method="POST">
								<table>
									<tr>
										<td>
											<h4>Tipo de usuario</h4>
										</td>
										<td>
											<select name="tipousu" id="tipousu" class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" required>
												<?php for ($i=0; $i< count($roles);$i++ ) { ?>
													<option value="<?= $roles [$i]; ?>"><?= $roles [$i]; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
								</table>
								<input type="submit" value="Aceptar" class="botones">
							</form>
						</center>
					</div>
				</span>
			</div>
		</main>
		
		
	</div>
</body>

</html>
