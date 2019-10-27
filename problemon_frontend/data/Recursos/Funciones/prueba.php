<?php
$usuario="andresb";

$username="root";
		$clave="";
		$servidor="localhost";
		$db="tesoro_busquedav1";
		$link=@mysqli_connect($servidor,$username,$clave)	or die ("Error al conectarse al servidor");
		mysqli_select_db($link,$db) or die ("Error al conectarse a la base de datos");
		mysqli_query ($link,"SET NAMES 'utf8'");

		$select="select max(TST_ID) maximo from test;";
		$r = mysqli_query( $link, $select );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$idprueba = $renglon->maximo;
		}
		$sql="SELECT * FROM `test_questions` tq inner join questions q on q.QUE_ID = tq.QUESTIONS_QUE_ID WHERE tq.USERS_USER_ID = '$usuario' and tq.GAMES_GAM_ID='1' and tq.TEST_TST_ID='$idprueba'";
		$r = mysqli_query( $link, $sql );
		while ( $renglon = mysqli_fetch_object( $r ) ) {
			$preguntas = $renglon->QUE_TITLE;
			$respuesta1 = $renglon->QUE_ANSWER1;
			$respuesta2 = $renglon->QUE_ANSWER2;
			$respuesta3 = $renglon->QUE_ANSWER3;
			$respuesta4 = $renglon->QUE_ANSWER4;
			
			$arrayprespuestas = array ("0" => $respuesta1, "1" => $respuesta2, "2" => $respuesta3, "3" => $respuesta4);
			$arrayprespuestas[] = shuffle($arrayprespuestas);
			
			$respuestascorrectas = $renglon->QUE_RIGHT_ANS;
			$descripcioubi = $renglon->QUE_DESCRIPTION;
			$posicion = $renglon->QUE_POSITION;
			$estado = $renglon->STATUS_STAT_ID;
			$nota = $renglon->TXQ_GRADE;
			$linkimg = $renglon->QUE_IMG;
			$idprueba = $renglon->TEST_TST_ID;
			$enviar[] = $preguntas.",,".$arrayprespuestas[0].",,".$arrayprespuestas[1].",,".$arrayprespuestas[2].",,".$arrayprespuestas[3].",,".$respuestascorrectas.",,".$descripcioubi.",,".$posicion.",,".$estado.",,".$nota.",,".$linkimg.",,".$idprueba;
		} 
		print "<pre>";print_r($enviar);print "</pre>";
		$enviar[] = shuffle($enviar);
		$reemplazar = array("\r", "\n", "/n", "/r", "<br>", "<br/>");
		for ($i = 0; $i < count($enviar) -1 ; $i++ ){
			if( $i > 0 )
				$resultado .= "///";
			$enviar[$i] = str_replace($reemplazar, "", $enviar[$i]);
			$resultado .= $enviar[$i];
		}
		print $resultado;
?>