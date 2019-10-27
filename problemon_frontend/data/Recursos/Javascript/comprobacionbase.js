// JavaScript Document
var xmlhttp;
var respuesta;

function comprobar(cosa, valor) {

	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			respuesta = xmlhttp.responseText;
		}
	}
	switch (cosa) {
		case "usuarioexist":
			xmlhttp.open("POST", "./Recursos/Funciones/usuarioexist.php", true);
			break;
		case "cedusuoexist":
			xmlhttp.open("POST", "./Recursos/Funciones/cedusuoexist.php", true);
			break;
		default:
			break;
	}
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("valor=" + valor);
	return respuesta;
}
