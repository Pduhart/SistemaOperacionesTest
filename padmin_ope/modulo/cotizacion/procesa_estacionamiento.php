<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_con = $_POST["valor"];

?>
<option value="">Seleccione Estacionamiento</option>
<?php
$consulta = 
    "
    SELECT 
        * 
    FROM 
        estacionamiento_estacionamiento
    WHERE
        id_est_esta = 1 AND
        id_con = '".$id_con."'  AND
        id_viv = 0
    ORDER BY 
        nombre_esta
    ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_esta'];?>"><?php echo utf8_encode($fila['nombre_esta']);?></option>
        <?php
    }
}
?>

