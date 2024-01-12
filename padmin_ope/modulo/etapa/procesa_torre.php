<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_tor = $_POST["valor"];

?>
<option value="">Seleccione Departamento</option>
<?php
$consulta = 
    "
    SELECT 
        * 
    FROM 
        vivienda_vivienda
    WHERE
        id_est_viv = 1 AND
        id_tor = '".$id_tor."'
    ORDER BY 
        nombre_viv
    ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?></option>
        <?php
    }
}
?>

