<!DOCTYPE html>
<html lang="es">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<?php include "./Recursos/Funciones/AdministradorSesiones.php";
if (@$_POST ){
	$rolUsuario = $_POST['tipousu'];
	$_SESSION["rol"] = $rolUsuario;
	if ( @$rolUsuario=="Estudiante" ) 
		$_SESSION['pagina'] = "cursosAlumnos" ;
	else if ( @$rolUsuario=="Profesor" ) 
		$_SESSION['pagina'] = "gestionCursos" ;
	else if ( @$rolUsuario=="Administrador" ) 
	$_SESSION['pagina'] = "matricularEstudiantes" ;
				
}	
?>

	
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0,
maximum-scale=1.0, minimun-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./Recursos/css/bootstrap-select.css">
	<script type="text/javascript" src="./Recursos/Javascript/redimencionar.js"></script>
	<link rel="stylesheet" href="./Recursos/css/estilos.css">

</head>
<body>
	<div class="wrapper" >
		<header2 class="header">
			<div class="contenedor">
				<form action="./Templates/editarDatos" target="contenidopagina">
					<input type="submit" class="botones" value="Mi usuario"/>
				</form>
				<h1 class="Titulo">Tesis</h1>
				<form action="./Recursos/Funciones/Cerrar">
					<input type="submit" class="botones" value="Cerrar Sesiòn"/>
				</form>
			</div>
		</header2>
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;   display: table;">
				<span id='contenido' style="width: 100%; height: 100%; ">
					<div class="menu">
						<div style="width: 100%; height: 80%; text-align: center;">
							<nav class="navbar navbar-inverse" style="border-radius: 0px 0px 94px 94px;-moz-border-radius: 0px 0px 94px 94px;-webkit-border-radius: 0px 0px 94px 94px;">
								<div class="container-fluid">
									<div class="navbar-header"
									>
										Menú  &nbsp;
 									</div>
									<ul class="nav navbar-nav">
										<?php if( @count ($roles) > 1){ ?>
										<li>
											<a class="dropdown-toggle" href="seleccionTipoUsuario">Cambiar tipo de usuario</a>
										</li>
										<?php } ?>
										<li class="active">
											<a href="./Sistema" >Inicio</a>
										</li>

										<?php if( @$rolUsuario=="Estudiante" ){ ?>
										<!-- <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Ver<span class="caret"></span></a>
											<ul class="dropdown-menu">
												<li><a href="./Templates/NotasEstudiante" target="contenidopagina">Pruebas Rendidas</a>
												</li>
												<li><a href="./Templates/JuegosDisponibles" target="contenidopagina">Juegos Disponibles</a>
												</li>
											</ul>
										</li> -->
										<li>
											<a href="./Templates/cursosAlumnos" target="contenidopagina">Mis cursos</a>
										</li>
										<li >
											<a href="./Templates/reportesAlumnos" target="contenidopagina">Reportes</a>
										</li>
										<?php } ?>
										<?php if( @$rolUsuario=="Profesor" ){ ?>
										<li>
											<a href="./Templates/gestionCursos" target="contenidopagina">Gestionar cursos</a>
										</li>
										<li>
											<a href="./Templates/nuevaPregunta" target="contenidopagina">Crear Preguntas</a>
										</li>
										<li>
											<a href="./Templates/Notas" target="contenidopagina">Reportes</a>
										</li>
										<li><a href="./Templates/JuegosNuevos" target="contenidopagina">Añadir juego nuevo</a></li>
										<?php } ?>
										<?php if( @$rolUsuario=="Administrador" ){ ?>
										<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Crear<span class="caret"></span></a>
											<ul class="dropdown-menu">
												<li><a href="./Templates/cursosNuevos" target="contenidopagina">Curso</a>
												</li>
												<li><a href="./Templates/sign_in" target="contenidopagina">Usuarios</a>
												</li>
												
											</ul>
										</li>
										<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Añadir<span class="caret"></span></a>
											<ul class="dropdown-menu">
												<li><a href="./Templates/JuegosNuevos" target="contenidopagina">Juegos Nuevos</a>
												</li>
												<li><a href="./Templates/Asignaturas" target="contenidopagina">Asignaturas Nuevas</a>
												</li>
												
											</ul>
										</li>
										<li>
											<a target="contenidopagina" href="./Templates/matricularEstudiantes">Matricular Estudiantes</a>
										</li>
										<li>
											<a target="contenidopagina" href="./Templates/usuariosRegistrados">Lista de usuarios</a>
										</li>
										<?php } ?>
									</ul>
									<h4 align="right">Bievenido: <?= $nombreUser; ?></h4>
						
								</div>
								
							</nav>
						</div>

					</div>
					<?php if ( @$rolUsuario=="Estudiante" ) {
							if ( $_SESSION['pagina'] == "index"){
								$_SESSION['pagina'] = "cursosAlumnos" ;
							}
						} else if ( @$rolUsuario=="Profesor" ) {
							if ( $_SESSION['pagina'] == "index"){
								$_SESSION['pagina'] = "gestionCursos" ;
							}
						} else if ( @$rolUsuario=="Administrador" ) {
							if ( $_SESSION['pagina'] == "index"){
								$_SESSION['pagina'] = "matricularEstudiantes" ;
							}
						}
					?>
					
					<iframe src="./Templates/<?= $_SESSION['pagina'];?>" name="contenidopagina" id="contenidopagina" frameborder="0" width="100%" height="100%"></iframe>
				</span>
			</div>
		</main>
		
		<footer class="footer2">
			<center>
				<p style="font-size: 1em">GrIInf (Grupo de Investigación en Informática)</p>
			</center>
		</footer>
	</div>
</body>

</html>
