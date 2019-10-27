<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>Leer Archivo Excel</title>
	<link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
	<?php
	include './CurlRequest.php';
	require_once './PHPExcel/Classes/PHPExcel.php';
	$url = $host_url; //"localhost:3000/api/";
	$rest= new CurlRequest();
	function searchJson( $obj, $value ) {
		foreach( $obj as $key => $item ) {
			if( !is_nan( intval( $key ) ) && is_array( $item ) ){
				if( in_array( $value, $item ) ) return $item;
			} else {
				foreach( $item as $child ) {
					if(isset($child) && $child == $value) {
						return $child;
					}
				}
			}
		}
		return null;
	}
	
	$personas = $rest -> sendGet($url."people", array());
	$personas = json_decode($personas, true);
	$search = '{ "where": { "code" : "'.$_POST['curso'].'" } }';
	$curso = $rest -> sendGet($url."courses?filter=".urlencode($search), array());
	$curso = json_decode($curso, true);
	//print_r($curso[0]["students"]);
	//print count($curso[0]["students"]);
	if ( isset( $_FILES[ 'archivo' ] ) ) {
		$errors = array();
		$file_name = $_FILES[ 'archivo' ][ 'name' ];
		$file_size = $_FILES[ 'archivo' ][ 'size' ];
		$file_tmp = $_FILES[ 'archivo' ][ 'tmp_name' ];
		$file_type = $_FILES[ 'archivo' ][ 'type' ];
		move_uploaded_file( $file_tmp, "./" . $file_name );

	}
	$archivo = $file_name;
	$inputFileType = PHPExcel_IOFactory::identify( $archivo );
	$objReader = PHPExcel_IOFactory::createReader( $inputFileType );
	$objPHPExcel = $objReader->load( $archivo );
	$sheet = $objPHPExcel->getSheet( 0 );
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	$i=0;
	for ( $row = 2; $row <= $highestRow; $row++ ) {
		$usuario[ $i ] = $sheet->getCell( "A" . $row )->getValue();
		$contrasena[ $i ] = $sheet->getCell( "B" . $row )->getValue();
		$nombre[ $i ] = $sheet->getCell( "C" . $row )->getValue().$sheet->getCell( "D" . $row )->getValue();
		$correo[ $i ] = $sheet->getCell( "E" . $row )->getValue();
		
		if ( @count( searchJson($personas, $correo[$i])) == 0){
			$data = array(
				"identification" => $correo[ $i ],
				"name" => $nombre[ $i ],
				"rol" => array ("Estudiante"), 
				"user" => $usuario[ $i ],
				"status" => "ACTIVO"
			); 
			$rest -> sendPost($url."people", $data);
			$data = array( 
				"username" => $usuario[ $i ],
				"email" => $correo[ $i ],
				"password" => $contrasena[ $i ]
			); 
			$rest -> sendPost($url."Users", $data);
		}
		$i++;
	}
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
	?>
	<center>
			<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
		<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
			<div class="cent" style="width: 60%" align="center">
				<br>
			<center><legend>Los estudiantes que se han matricuado en el sistema a la materia <?= $_POST['curso'];?> son:</legend>
				<form action="../../Templates/matricularEstudiantes">
					<table>
						<tr>
						<!--	<th>Cédula</th> -->
							<th>Nombre</th>
							<th>Correo</th>
						<!--	<th>Teléfono</th> -->
							<th>Usuario</th>
							<th>Contraseña</th>
						</tr>
						<?php 
						for($i=0;$i<count($contrasena);$i++){?>
							<tr>
								<td><?= $nombre[ $i ]; ?></td>
								<td><?= $correo[ $i ]; ?></td>
								<td><?= $usuario[ $i ]; ?></td>
								<td><?= $contrasena[ $i ]; ?></td>
							</tr>
						<?php  } ?>

					</table>
				 <input type="submit" value="Regresar" class="botones"> </center>
				</form>
					
			</div>
		</span>
				</div>
				</main></div>
	</center>
		
</body>
</html>

