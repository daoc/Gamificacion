<?php include $_SERVER['DOCUMENT_ROOT'] . "/global.php" ?>
<?php
include "./CurlRequest.php";
//usuario
$url = $host_url; //"localhost:3000/api/";
$rest = new CurlRequest();
$json='{ "where" : { "user":"'. $_POST[ "valor" ] .'"  }  }';
$data = array( "" );
$resultado = $rest -> sendGet( $url."People?filter=". urlencode($json) , $data );
$perResultado = json_decode($resultado, true);

if (!isset($perResultado[0])){
	print "Disponible.";
}else {
	print "No está Disponible.";
}
?>
