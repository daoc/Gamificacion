<?php
include("conexion.php");
include("AdministradorSesiones.php");
$idprueba = $_POST['str'];
$sql= "SELECT * FROM `permissions` p inner join users u on p.USERS_USER_ID = u.USER_ID inner join person per on u.PERSON_PER_ID = per.PER_ID where u.USER_TYPE_UT_ID=1 and p.`SUBJECT_SUB_ID`='$idprueba'";
$r = mysqli_query( $link, $sql ); ?>
<table id="tablacontenido">
	<thead>
		<tr>
			<th align="center">
				<h3>Cédula del estudiante</h3>
			</th>
			<th align="center">
				<h3>Nombre del estudiante</h3>
			</th>	
			<th align="center">
				<h3>Nota Final</h3>
			</th>
			<th align="center">
				<h3>Hora Inicio</h3>
			</th>
			<th align="center">
				<h3>Hora Finalización</h3>
			</th>
		</tr>
	</thead>
	<tbody>
<?php 	while ( $renglon = mysqli_fetch_object( $r ) ) {?>
		<tr onClick="window.location.href='./verReporteEstudiante?id=<?=  $renglon->USERS_USER_ID ; ?>'" >
			<td align="center">
				<?= $renglon->PER_ID; ?>
			</td>
			<td align="center">
				<?= $renglon->PER_NAME; ?>
			</td>
			<td align="center">
				<?= consultaNota ($renglon->USERS_USER_ID, $idprueba); ?>
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
	</tbody>
</table>
