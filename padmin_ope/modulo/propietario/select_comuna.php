<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$region = $_POST["region"];
?>
<option value="">Seleccione Comuna</option>
<?php
$consulta = "SELECT * FROM comuna_comuna WHERE id_reg = ?";
$conexion->consulta_form($consulta,array($region));
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$nombre_com = utf8_encode($fila['nombre_com']);
		$id_com = $fila['id_com'];
		?>
		<option value="<?php echo $id_com;?>"><?php echo $nombre_com;?></option>
		<?php
	}
}
?>

    

