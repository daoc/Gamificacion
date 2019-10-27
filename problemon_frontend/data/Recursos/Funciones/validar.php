<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<?php
include "./CurlRequest.php";
$url = $host_url; //"http://loopback:3000/api/";
$rest = new CurlRequest();
	header( "Content-Type: text/html;charset=utf-8" );
	//print "<meta charset='utf-8'>";
	if ( isset( $_POST[ "usuario" ] ) && isset( $_POST[ "contrasena" ] ) ) {
		//print "<pre>"; print_r($_POST[ "usuario" ]); print "</pre>"; 
		//print "<pre>"; print_r($_POST[ "contrasena" ]); print "</pre>"; 
		$data = array(
			"username" => $_POST[ "usuario" ],
			"password" => $_POST[ "contrasena" ] );
		//print "<pre>"; print_r($data); print "</pre>"; 
		$resultado = $rest -> sendPost( $url."users/login" , $data );
		//print "<pre>Resultado: "; print_r($resultado); print "</pre>"; 
		$respose = json_decode($resultado);
		if ( @$respose -> { 'error' } -> { 'statusCode' } == 401 ){
			//print "<pre>"; print_r($respose); print "</pre>"; 
			header( "location: ../../?mensaje=Usuario o contraseña erroneos, verifique los datos ingresados." );
		} else {
			//print "<pre>"; print_r($respose); print "</pre>"; 
			$json='{ "where" : { "user":"'. $_POST[ "usuario" ] .'"  }  }';
			$data = array( "" );
			$res = $rest -> sendGet( $url."People?filter=". urlencode($json) , $data );
			$persona = json_decode($res, true);
			//print "<pre>"; print_r($persona); print "</pre>";

			if ($persona[0]['status'] == "ACTIVO")
			{
				session_start();
				$_SESSION['respLogin'] = json_decode($resultado, true);
				$_SESSION['usuario'] = $persona[0];
				if ( count($_SESSION['usuario']['rol']) == 1 ) 
					header( "location: ../../Sistema" );
				else
					header( "location: ../../seleccionTipoUsuario" );
				//print "<pre>"; print_r($_SESSION['usuario']); print "</pre>"; 
			} else { //en caso de desactivado
				header( "location: ../../?mensaje=Cuenta inhabilitada en el sistema, contacte al administrador." );
			}
			//print "<pre>"; print_r($_SESSION['person']); print "</pre>"; 
			//print_r ($_SESSION['person']['rol']);
		}
		
	}	else 	{
		header( "location: ../../?mensaje=No ha ingresado los datos para ingresar al sistema." );
	}

	?>
