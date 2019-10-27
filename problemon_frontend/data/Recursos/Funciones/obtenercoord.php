<?php
session_start();
$peticion = $_POST['str'];
$variable = $_SESSION['coordenadas'];
$reemplazar = array("(", ")", " ");
$variable = str_replace($reemplazar, "", $variable);
print $variable;
?>