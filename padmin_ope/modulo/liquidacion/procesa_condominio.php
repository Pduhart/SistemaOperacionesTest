<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_con = $_POST["valor"];

?>
<option value="">Elegir Torre</option>
<?php
$consulta = 
    "
    SELECT 
        * 
    FROM 
        torre_torre
    WHERE
        id_est_tor = 1 AND
        id_con = '".$id_con."'
    ORDER BY 
        nombre_tor
    ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_tor'];?>"><?php echo utf8_encode($fila['nombre_tor']);?></option>
        <?php
    }
}
?>

