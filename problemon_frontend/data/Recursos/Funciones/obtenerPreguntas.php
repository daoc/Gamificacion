<?php
session_start();
include("conexion.php");
$usuari = $_POST['str'];
$select="select max(TST_ID) maximo from test;";
$r = mysqli_query( $link, $select );
while ( $renglon = mysqli_fetch_object( $r ) ) {
	$idprueba = $renglon->maximo;
}
$sql="SELECT * FROM `test_questions` tq inner join questions q on q.QUE_ID = tq.QUESTIONS_QUE_ID WHERE tq.USERS_USER_ID = '$usuari' and tq.GAMES_GAM_ID='1' and tq.TEST_TST_ID='$idprueba'";
$r = mysqli_query( $link, $sql );
while ( $renglon = mysqli_fetch_object( $r ) ) {
	$preguntas[] = $renglon->QUE_TITLE;
	$respuesta1[] = $renglon->QUE_ANSWER1;
	$respuesta2[] = $renglon->QUE_ANSWER2;
	$respuesta3[] = $renglon->QUE_ANSWER3;
	$respuesta4[] = $renglon->QUE_ANSWER4;
	$respuestascorrectas[] = $renglon->QUE_RIGHT_ANS;
	$descripcioubi[] = $renglon->QUE_DESCRIPTION;
	$imagen[] = $renglon->QUE_IMG;
	$posicion[] = $renglon->QUE_POSITION;
	$estado[] = $renglon->STATUS_STAT_ID;
} 
$reemplazar = array("\r", "\n", " ", "/n", "/r", "<br>", "<br/>");
for ($i = 0; $i < count($preguntas) ; $i++ ){
	if( $i>0 )
		print "///";
	$posicion[$i] = str_replace($reemplazar, "", $posicion[$i]);
	print $posicion[$i].",".$imagen[$i].",".$estado[$i];
}
?>