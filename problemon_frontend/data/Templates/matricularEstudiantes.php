<!DOCTYPE html>
<html lang="es">
<?php include "../Recursos/Funciones/AdministradorSesiones.php"; 
	$pagina = explode( "/", $_SERVER['REQUEST_URI']);
	$_SESSION['pagina'] = $pagina [3] ;
	$rest = new CurlRequest();
	
	$data = array();
	$cursosDisponibles = $rest -> sendGet($url."courses", $data);
	$search = '{ "where": { "rol." : "Estudiante" } }';
	$alumnosDisponibles = $rest -> sendGet($url."people?filter=".urlencode($search), $data);
	$materiasDisponibles = $rest -> sendGet($url."subjects", $data);
	$materiasDisponibles = json_decode($materiasDisponibles, true);

	//print_r($materiasDisponibles);
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
		$search = '{ "where": { "code" : "'.$_POST['curso'].'" } }';
		$curso = $rest -> sendGet($url."courses?filter=".urlencode($search), array());
		$curso = json_decode($curso, true);
		$correo= array( $_POST['mailus'] );
		$data = array(
			"identification" => $_POST['mailus'],
			"name" => strtoupper($_POST['nombre']),
			"rol" => array ("Estudiante"), 
			"user" => $_POST['nomus'],
			"status" => "ACTIVO"
		); 
		$rest -> sendPost($url."people", $data);
		$data = array( 
			"username" => $_POST['nomus'],
			"email" => $_POST['mailus'],
			"password" => $_POST['contraus']
		); 
		$rest -> sendPost($url."Users", $data);
		
		if ( $curso[0]["students"][0] == ""  )
			unset($curso[0]["students"][0]);
		$curso[0]["students"]= array_merge ($curso[0]["students"] ,$correo);
		$curso[0]["students"] = array_unique($curso[0]["students"]);
		$rest -> sendPut($url."courses", $curso [0]);
		$data = array(
			"code" => $curso [0]['code'],
			"name" => "Todos",
			"members" => $curso[0]["students"]
		);
		$rest -> sendPut($url."groups", $data);
	}
	?>

</head>

<body>
	<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" style="width: 70%">
						<center>
							<div class="row">
								<div class="col-sm-6">
									<legend>Individual</legend>
									<br><br>
									<form action="#" method="POST" enctype="multipart/form-data">
										<select name="asignaturas" id="asignaturas" class="selectpicker" data-live-search='true' title="Seleccione una asignatura" onchange="cursosDisponibles(this.value, 'I')">
											<?php for($i=0;$i<count($materiasDisponibles);$i++){ ?>
												<option value="<?= $materiasDisponibles[$i]['name'];?>"><?= $materiasDisponibles[$i]['name'];?></option>
											<?php } ?>
										</select> <select name="curso" id="cursoI" class="selectpicker" data-live-search='true' title="Seleccione un curso" >
										</select>
										<br>
										<br>
										<div class="row">
											<div class="col-sm-6"><h4>Correo Electrónico:</h4></div>
											<div class="col-sm-4">
												<input type="email" name="mailus" maxlength="100" class="cajastexto" placeholder="Correo electrónico" title="Ingrese el correo electrónico" onchange="comprobar(this.value)"  onkeydown="comprobar(this.value)" onkeypress="comprobar(this.value)" onkeyup="comprobar(this.value)" required>
												<p id="respuestacorreo"></p>
											</div>
											
										</div>
										<div class="row">
											<div class="col-sm-6"><h4>Nombre de usuario:</h4></div>
											<div class="col-sm-4">
												<input type="text" name="nomus" id="nomus" class="cajastexto" placeholder="Nombre de usuario" required  
													   onKeyUp="comprobarusuario(this.value);" 
													   onChange="comprobarusuario(this.value);" 
													onBlur="comprobarusuario(this.value);"> 
												<p id="respuestausu"></p>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6"><h4>Nombre completo del estudiante:</h4></div>
											<div class="col-sm-4">
												<input type="text" name="nombre" id="nombre" class="cajastexto" maxlength="50" placeholder="Apellidos y Nombres" required>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6"><h4>Contraseña:</h4></div>
											<div class="col-sm-4">
												<input type="password" name="contraus" id="contraus" pattern=".{5,}" title="La contraseña debe contener mínimo 5 carácteres" class="cajastexto" placeholder="Contraseña" required>
											</div>
										</div>
										<input type="submit" id="ingresous" disabled value="Agregar Estudiante" class="botonesdisabled">
									</form>
								</div>
								<div class="col-sm-1">
									<hr style=" border:none; border-left:1px solid hsla(100, 10%, 50%,100);height:50vh;width:1px;   ">
								</div>
								<div class="col-sm-4" align="center">
									<legend>Archivo excel</legend>
									<br><br>
									<form action="../Recursos/Funciones/Excel" method="POST" enctype="multipart/form-data">
										<select name="asignaturas" id="asignaturas" class="selectpicker" data-live-search='true' title="Seleccione una asignatura" onchange="cursosDisponibles(this.value, 'G')">
											<?php for($i=0;$i<count($materiasDisponibles);$i++){ ?>
												<option value="<?= $materiasDisponibles[$i]['name'];?>"><?= $materiasDisponibles[$i]['name'];?></option>
											<?php } ?>
										</select>
										<br><br>
										<select name="curso" id="curso" class="selectpicker" data-live-search='true' title="Seleccione un curso" >
											
										</select>
										<br>
										<br>
										<label for="archivo">Archivo (xls o xlsx): </label>
										<input type="file" name="archivo" id="archivo" required> Suba un archivo en formato xlsx (Archivo de excel).
										<input type="submit" value="Agregar Estudiantes" class="botones">
									</form>
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
	var cursos = JSON.parse('<?= $cursosDisponibles; ?>');
	var array = Object.keys(cursos).map(function(k) { return cursos[k] });
	var estudiantes = JSON.parse('<?= $alumnosDisponibles; ?>');
	var alumnos = Object.keys(estudiantes).map(function(k) { return estudiantes[k] });
	var cursosDiv = document.getElementById('curso');
	var cursosDivI = document.getElementById('cursoI');
	var ingresous = document.getElementById('ingresous');
	var banderacorreo = 0;
	var banderauser = 0;
	
	function cursosDisponibles(str, id){
		var select = '';
		for (var i = 0; i < array.length; i++){
			if ( array[i] ["subject"] ==  str ){
				select += "<option value='"+ array[i] ["code"]+"' >" + array[i] ["name"] +" ("+ array[i] ["period"] +") </option>";
			}
		}
		if (id=="I"){
			cursosDivI.innerHTML = select;
		} else {
			cursosDiv.innerHTML = select;
		}
		$('.selectpicker').selectpicker('refresh');
	}
	
	function comprobar(str){
		if (str.length>6)
		for (var i = 0; i < alumnos.length; i++){
			if ( alumnos[i] ["identification"] ==  str ){
				document.getElementById('respuestacorreo').innerHTML="No disponible";
				document.getElementById('respuestacorreo').style="color:firebrick";
				banderacorreo=0;
				
				break;
			} else {
				document.getElementById('respuestacorreo').innerHTML="Disponible";
				document.getElementById('respuestacorreo').style.color="#a1ec98";
				banderacorreo=1;
			}
		} 
		else {
			document.getElementById('respuestacorreo').innerHTML="";
			banderacorreo=0;
		}
		comprobarBandera();
	}
	function comprobarusuario(str){
		if (str.length>4)
		for (var i = 0; i < alumnos.length; i++){
			if ( alumnos[i] ["user"] ==  str ){
				document.getElementById('respuestausu').innerHTML="No disponible";
				document.getElementById('respuestausu').style="color:firebrick";
				banderauser=0;
				
				break;
			} else {
				document.getElementById('respuestausu').innerHTML="Disponible";
				document.getElementById('respuestausu').style.color="#a1ec98";
				banderauser=1;
			}
		} 
		else {
			document.getElementById('respuestausu').innerHTML="";
			banderauser=0;
		}
		comprobarBandera();
	}
	
	function comprobarBandera(){
		if( banderacorreo== 1 && banderauser==1){
			ingresous.removeAttribute("disabled");  
			ingresous.className="botones";
		} else {
			ingresous.disabled=false;
			ingresous.className="botonesdisabled";
		}
	}
</script>

</html>