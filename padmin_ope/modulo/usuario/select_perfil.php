<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$perfil_usu = $_POST["perfil_usu"];
$consulta = "SELECT * FROM usuario_usuario WHERE id_per = ? AND id_est_usu = 1 AND NOT id_usu = 1";
$conexion->consulta_form($consulta,array($perfil_usu));
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$nombre_usu = utf8_encode($fila['nombre_usu']);
		$apellido1_usu = utf8_encode($fila['apellido1_usu']);
		$apellido2_usu = utf8_encode($fila['apellido2_usu']);
		$id_usu = $fila['id_usu'];
		?>
		<option value="<?php echo $id_usu;?>"><?php echo $nombre_usu." ".$apellido1_usu." ".$apellido2_usu;?></option>
		<?php
	}
}
?>

    

