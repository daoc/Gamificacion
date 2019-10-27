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
	<style>
		tr:hover td {
			cursor: pointer; 
			color: firebrick;
		}
	</style>
	<script src="../Recursos/Javascript/jquery.popupwindow.js"></script>
	<script>

	var pruebas = { lista : '', 
					   get lista() { return this._prop1; },
					   set lista(value) { this._prop1 = value;
										 $("#listaEvaluaciones").html(value=pruebas.lista);
										}
					  }
	
	function getLista(valor){
		var xmlhttp;
		if (window.XMLHttpRequest) {
		    xmlhttp = new XMLHttpRequest();
		} else {
		    xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		xmlhttp.onreadystatechange = function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        pruebas.lista = xmlhttp.responseText;
		    }
		}
		xmlhttp.open('POST', '../Recursos/Funciones/obtenerlista.php', true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlhttp.send('str='+valor);
	}
	
	function modificarPrueba(str){
		$.popupWindow('../modificarPrueba?id='+str, {
            height: 800,
            width : 600,
			onUnload: function() {
				getLista(document.getElementById('nombreMateria').value);
        	}
    	});
	} 
	</script>

</head>
<body>
	<div class="wrapper" >
		
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
				<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
					<div class="cent" >
						<center>
							<table>
								<tr>
									<td>
										<h4>Asignatura:</h4>
									</td>
									<td align="center">
										<select name="nombreMateria" id="nombreMateria"class="selectpicker show-tick" data-live-search="true" title="Seleccione una opción." style="border-bottom-color: red;" onChange="getLista(this.value)" required>
											<?php $sql="select * from subject s inner join permissions p on p.SUBJECT_SUB_ID=s.SUB_ID where p.USERS_USER_ID='$username' ;"; //cambiar instituto despues
											$r = mysqli_query( $link, $sql );
											while ( $renglon = mysqli_fetch_object( $r ) ) {?>
												<option value="<?= $renglon->SUB_ID; ?>"><?= $renglon->SUB_NAME; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
							</table>
							<h2>Lista de evaluaciones.</h2>
							<td colspan="10"><input id="buscar" name="buscar" type="text" class="form-control" placeholder="Busqueda" />
							<div id="listaEvaluaciones"></div>
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