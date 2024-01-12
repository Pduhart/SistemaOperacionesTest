<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_con = $_POST["valor"];

?>
<option value="">Seleccione Bodega</option>
<?php
$consulta = 
    "
    SELECT 
        * 
    FROM 
        bodega_bodega
    WHERE
        id_est_bod = 1 AND
        id_con = '".$id_con."' AND
        id_viv = 0
    ORDER BY 
        nombre_bod
    ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_bod'];?>"><?php echo utf8_encode($fila['nombre_bod']);?></option>
        <?php
    }
}
?>

