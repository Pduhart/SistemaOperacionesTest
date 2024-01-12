<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_tor = $_POST["valor"];

?>
<option value="">Seleccione Modelo</option>
<?php
$consulta = 
    "
    SELECT 
        * 
    FROM 
        modelo_modelo
    WHERE
       id_tor = ".$id_tor."
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
        <?php
    }
}
?>

