<?php
include("conexion.php");
include("AdministradorSesiones.php");
$idprueba = $_POST['str'];
$sql= "SELECT * FROM `test_questions` tq inner join questions q on tq.QUESTIONS_QUE_ID = q.QUE_ID where tq.`TEST_TST_ID`='$idprueba' and tq.USERS_USER_ID='$username'";
$r = mysqli_query( $link, $sql ); ?>
<table id="tablacontenido">
	<thead>
		<tr>
			<th align="center">
				<h3>Pregunta</h3>
			</th>
			<th align="center">
				<h3>Nota</h3>
			</th>	
			<th align="center">
				<h3>Nota Final</h3>
			</th>
		</tr>
	</thead>
	<tbody>
<?php 	while ( $renglon = mysqli_fetch_object( $r ) ) {
		?>
		<tr >
			<td align="center">
				<?= $renglon->QUE_TITLE; ?>
			</td>
			<td align="center">
				<?= $renglon->TXQ_GRADE; ?>
			</td>
			<td align="center">
				
			</td>
			
		</tr>
<?php 	} 	
		function consultaNota ($username, $idprueba ){
			include("conexion.php");
			$sql = "select TXQ_GRADE from test_questions where USERS_USER_ID='$username' and TEST_TST_ID='$idprueba'";
			$r = mysqli_query( $link, $sql );
			$notatotal=0;
			$i=0;
			while ( $renglon = mysqli_fetch_object( $r ) ) {
				$notatotal+=$renglon->TXQ_GRADE;
				$i++;
			}
			return number_format(round( $notatotal/$i , 2) , 2 ) ;
		}
		
		?>
		<tr >
			<td align="center">
				Nota final Promediada.
			</td>
			<td align="center">
				
			</td>
			<td align="center">
				<?= consultaNota ($username, $idprueba); ?>
			</td>
			
		</tr>
	</tbody>
</table>
