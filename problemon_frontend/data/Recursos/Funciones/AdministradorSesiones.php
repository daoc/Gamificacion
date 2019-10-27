<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<?php
session_start();
date_default_timezone_set('America/Bogota');
include "CurlRequest.php";
$url = $host_url; //"localhost:3000/api/";

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
//usuario
if ( isset( $_SESSION['usuario'] ) ){
	$cedulaUser=$_SESSION['usuario']['identification'];
	$nombreUser=$_SESSION['usuario']['name'];
	$username=$_SESSION['usuario']['user'];
	if ( count($_SESSION['usuario']['rol']) == 1 ){
		$rolUsuario = $_SESSION['usuario']['rol'][0];
		$_SESSION["rol"] = $rolUsuario;
	} else {
		$roles = $_SESSION['usuario']['rol'];
		$rolUsuario = @$_SESSION["rol"];
	}
	if (!isset($_SESSION['pagina']))
		$_SESSION['pagina']="index";

	if ($_SESSION['usuario']['status'] != "ACTIVO"){
		header('location:./?mensaje=Tiene acceso restringido al sistema.');
		unset($_SESSION); //para destruir todas las 				variables: $_SESSION = array();
		setcookie(session_name(), '', 1);
		session_destroy();
		session_write_close(); 
		session_destroy();
	}
	
} else { 
	header('location:./?mensaje=Debe iniciar sesión para acceder al sistema.');
}

?>
