<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<!DOCTYPE html>
<html>
<head>
	<title>Leer Archivo Excel</title>
	<link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
	<?php
	
	$nombreCeldas = array();
	for($i=65; $i<=90; $i++) {  
		$nombreCeldas[] = chr($i);    
	} 
	
	for($i=65; $i<=90; $i++) {  
		$nombreCeldas[] = $nombreCeldas[0].chr($i); 
		$nombreCeldas[] = $nombreCeldas[1].chr($i); 
		$nombreCeldas[] = $nombreCeldas[2].chr($i); 
		$nombreCeldas[] = $nombreCeldas[3].chr($i); 
		$nombreCeldas[] = $nombreCeldas[4].chr($i); 
		$nombreCeldas[] = $nombreCeldas[5].chr($i); 
	} 
	
	//print_r($_POST);
	
	include './CurlRequest.php';
	require_once './PHPExcel/Classes/PHPExcel.php';
	$url = $host_url; //"http://localhost:3000/api/";
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
		$enunciado[ $i ] = $sheet->getCell( $nombreCeldas[0] . $row )->getValue();
		$grado[ $i ] = $sheet->getCell( $nombreCeldas[3] . $row )->getValue();
		$pista[ $i ] = $sheet->getCell( $nombreCeldas[4] . $row )->getValue();
		$retroalimentacion[ $i ] = $sheet->getCell( $nombreCeldas[5] . $row )->getValue();
		$respuesta[ $i ] = $sheet->getCell( $nombreCeldas[6] . $row )->getValue();
		$imagenpregunta[ $i ] = $sheet->getCell( $nombreCeldas[7] . $row )->getValue();
		
$l=0;
		for ($k=8; $k < count($nombreCeldas); $k+=3){
			
			if ($sheet->getCell( $nombreCeldas[$k].$row ) == "" && 
				$sheet->getCell( $nombreCeldas[$k+1].$row ) == "" && $sheet->getCell( $nombreCeldas[$k+2].$row ) == "" ){
				break;
			}  
			$alternativa[ $i ][ $l ] =
				$sheet->getCell( $nombreCeldas[$k] . $row )->getValue(). "///" .
				$sheet->getCell( $nombreCeldas[$k+1] . $row )->getValue(). "///" .
				$sheet->getCell( $nombreCeldas[$k+2] . $row )->getValue();
			
			$l += 1;
		}
		
		
		
		$data[] = array(
			"subject" => $_POST['nombreMateria'], 
			"tema" => $_POST['tema'], 
			"tittle" => $enunciado[ $i ], 
			"description" => $pista[ $i ], 
			"retroalimentation" => $retroalimentacion[ $i ], 
			"image" => $imagenpregunta[ $i ], 
			"options" => $alternativa[ $i ], 
			"answer" => $respuesta[ $i ], 
			"degree" => $grado[ $i ]
		);
		$i++;
	}
	
	print "<pre>";print_r($data);print "</pre>";
	for ($i=0; $i < count($data); $i++){
		$rest -> sendPost($url."questions", $data[$i]);
	}
	
	//print "<pre>"; print_r($data);print "</pre>";
	
	
	?>
	<center>
			<div class="wrapper">
		<main>
			<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
		<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
			<div class="cent" style="width: 60%" align="center">
				<br>
			<center><legend>Las siguientes pregutas han sido añadidas en <?= $_POST['nombreMateria'];?> son:</legend>
				<form action="../../Templates/nuevaPregunta.php">
					<table>
						<tr>
						<!--	<th>Cédula</th> -->
							<th>Enunciado</th>
							<th>Dificultad</th>
						</tr>
						<?php 
						for($i=0;$i<count($data);$i++){?>
							<tr>
								<td><?= $enunciado[ $i ]; ?></td>
								<td><?= strtoupper($grado[ $i ]);  ?></td>
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
	<?php
		unlink($archivo);
	?>
</body>
</html>

