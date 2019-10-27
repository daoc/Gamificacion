<?php
	include "../Recursos/Funciones/AdministradorSesiones.php";
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../Recursos/css/estilos.css">
</head>

<body>
	<div class="wrapper" >
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<?php
							if( $rolUsuario == "Estudiante" ) { ?>
								Como estudiante, podrá ver sus puntuaciones en cada prueba con su puntuación. 
						<?php	}	?>
						<?php
							if( $rolUsuario == "Profesor" ) { ?>
								Como instructor, usted tendrá acceso a modificar/crear/eliminar pruebas del sistema <br/>
								a su vez, podrá crear nuevas preguntas para cada prueba. <br/>
								También podrá ver reportes de sus alumnos.
						<?php	}	?>
						<?php
							if( $rolUsuario == "Administrador" ) { ?>
								Como administrador, usted tendrá acceso a brindar acceso tanto como bloquear acceso a diferentes instituciones<br/>
						<?php	}	?>
					</div>
				</span>
			</div>
		</main>
	</div>
</body>

</html>