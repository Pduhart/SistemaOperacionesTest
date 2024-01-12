<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
$consulta = 
	"
	SELECT 
		unidad_unidad.id_uni,
		unidad_unidad.nombre_uni	
	FROM 
		unidad_unidad,
		usuario_unidad_usuario
	WHERE 
		usuario_unidad_usuario.id_usu = ? AND
		usuario_unidad_usuario.id_uni = unidad_unidad.id_uni
	";
$conexion->consulta_form($consulta,array($usuario_usu));
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$nombre_uni = utf8_encode($fila['nombre_uni']);
		$id_uni = $fila['id_uni'];
		?>
		<option value="<?php echo $id_uni;?>"><?php echo $nombre_uni;?></option>
		<?php
	}
}
?>

    

