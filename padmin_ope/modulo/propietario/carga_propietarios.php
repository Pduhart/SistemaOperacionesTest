<?php 
require "../../config.php"; 

include _INCLUDE."class/conexion.php";
$conexion = new conexion();
?>
<table>
	<?php 
	$consulta = "SELECT nombre_pro, apellido_paterno_pro, correo_pro FROM propietario_propietario ORDER BY id_pro DESC LIMIT 0,1000";
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
		 ?>
		<tr>
			<td><?php echo utf8_encode(strtolower($fila['correo_pro']));?></td>
			<td><?php echo utf8_encode(strtolower($fila['nombre_pro']));?></td>
			<td><?php echo utf8_encode(strtolower($fila['apellido_paterno_pro']));?></td>
		</tr>
		<?php
        }
    }
    ?>
</table>

