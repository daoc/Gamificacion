<!DOCTYPE html>
<html>
<head>
	<title>Leer Archivo Excel</title>
	<link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
	<?php

	if ( $_FILES[ "archivo" ][ "name" ] ) {
		$filename = $_FILES[ "archivo" ][ "name" ];
		$source = $_FILES[ "archivo" ][ "tmp_name" ];
		$type = $_FILES[ "archivo" ][ "type" ];

		$name = explode( ".", $filename );
		$accepted_types = array( 'application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed' );
		foreach ( $accepted_types as $mime_type ) {
			if ( $mime_type == $type ) {
				$okay = true;
				break;
			}
		}

		$continue = strtolower( $name[ 1 ] ) == 'zip' ? true : false;
		if ( !$continue ) {
			$message = "El archivo subido no es de formato .zip. Por favor intentelo de nuevo.";
		}

		$target_path = "" . $filename; // change this to the correct site path
		if ( move_uploaded_file( $source, $target_path ) ) {
			$zip = new ZipArchive();
			$x = $zip->open( $target_path );
			if ( $x === true ) {
				$zip->extractTo( "../Preguntas/" ); // change this to the correct site path
				$zip->close();

				unlink( $target_path );
			}
			
			
			$message = "Los archivos han sido subidos y descomprimidos.";
		} else {
			$message = "Hubo un error, por favor intentelo de nuevo.";
		}
	}
	?>
	<center>
		<div class="wrapper">
			<main>
				<div id="main" style="width: 100%; height: 100%; text-align: center;  vertical-align: middle; display: table;">
					<span id='contenido' style="display: table-cell; vertical-align: middle;text-align: center;">
						<div class="cent" style="width: 60%" align="center">
							<br>
							<center>
								<legend>
									<?= $message;?>
								</legend>
								<form action="../../Templates/nuevaPregunta.php">

									<input type="submit" value="Regresar" class="botones"> </center>
							</form>

						</div>
					</span>
				</div>
			</main>
		</div>
	</center>
</body>
</html>