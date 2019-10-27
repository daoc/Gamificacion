<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<?php
session_start();
$data = array();
include "./CurlRequest.php";
$rest = new CurlRequest();
$url = $host_url."Users/logout?access_token=".$_SESSION['respLogin']['id']; //"http://localhost:3000/api/Users/logout?access_token=".$_SESSION['respLogin']['id'];
$rest -> sendPost($url, $data);
unset($_SESSION); //para destruir todas las 				variables: $_SESSION = array();

setcookie(session_name(), '', 1);
session_destroy();
session_write_close(); 
header("Location: ../../?mensaje=Sesión cerrada éxitosamente.");

/*
efectúa el logout del usario detruyendo COMPLETAMENTE la información de la sesión
Luego de haber ingresado a esta página, el usuario no podrá volver a ingresar
a la página protegida, a no ser que vuelva a logearse
*/


?>
