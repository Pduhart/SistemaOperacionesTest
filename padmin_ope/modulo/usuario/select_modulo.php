<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
$consulta = 
	"
	SELECT 
		usuario_modulo.id_mod,
		usuario_modulo.nombre_mod		
	FROM 
		usuario_modulo,
		usuario_usuario_modulo
	WHERE 
		usuario_usuario_modulo.id_usu = ? AND
		usuario_usuario_modulo.id_mod = usuario_modulo.id_mod
	";
$conexion->consulta_form($consulta,array($usuario_usu));
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$nombre_mod = utf8_encode($fila['nombre_mod']);
		$id_mod = $fila['id_mod'];
		?>
		<option value="<?php echo $id_mod;?>"><?php echo $nombre_mod;?></option>
		<?php
	}
}
?>

    

