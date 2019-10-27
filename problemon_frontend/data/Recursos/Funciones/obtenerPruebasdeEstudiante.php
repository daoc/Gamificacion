<?php
include("conexion.php");
include("AdministradorSesiones.php");
$idprueba = $_POST['str'];
$sql= "SELECT * FROM `test` WHERE `SUBJECT_SUB_ID`='$idprueba'";
$r = mysqli_query( $link, $sql ); ?>
<select  style="border-bottom-color: red;" name="prueba" id="prueba" onChange="getLista(this.value)">
	<option value="" disabled selected>Seleccione una opcion</option>
<?php 	while ( $renglon = mysqli_fetch_object( $r ) ) {	?>
	<option value="<?= $renglon ->TST_ID; ?>"><?= $renglon ->TST_NAME; ?></option>
<?php 	}	?>
</select>