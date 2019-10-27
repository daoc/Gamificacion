<?php
include("conexion.php");
include("AdministradorSesiones.php");
$idmateria = $_POST['str'];
$sql= "SELECT * FROM `test` WHERE `USERS_USER_ID`='$username' AND `SUBJECT_SUB_ID`='$idmateria'";
$r = mysqli_query( $link, $sql ); ?>
<table id="tablacontenido">
	<thead>
		<tr>
			<th align="center">
				<h3>Nombre</h3>
			</th>	
			<th align="center">
				<h3>Fecha de Inicio</h3>
			</th>	
			<th align="center">
				<h3>Fecha de Finalización</h3>
			</th>	
			<th align="center">
				<h3>Opciones</h3>
			</th>	
		</tr>
	</thead>
	<tbody>
<?php 	while ( $renglon = mysqli_fetch_object( $r ) ) {	?>
		<tr onClick="window.location.href='./AsignacionPrueba?id=<?= base64_encode( $renglon->TST_ID ); ?>'" >
			<td align="center">
				<?= $renglon->TST_NAME; ?>
			</td>
			<td align="center">
				<?= $renglon->TST_DATE_START; ?>
			</td>
			<td align="center">
				<?= $renglon->TST_DATE_END; ?>
			</td>
			<td align="center">
				<span class="glyphicon glyphicon-edit" onClick="modificarPrueba('<?= base64_encode( $renglon->TST_ID ); ?>')">Modificar</span>
			</td>
		</tr>
<?php 	} 	?>
	</tbody>
</table>
