<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_POST["valor"];

?>
<option value="">Seleccione Modelo</option>
<?php
$consulta = 
    "
    SELECT 
        mode.id_mod, 
        mode.nombre_mod
    FROM
        torre_torre AS tor 
        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
        INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
    WHERE
        tor.id_con = '".$id."' AND
        mode.id_est_mod = 1
    GROUP BY
        mode.id_mod, 
        mode.nombre_mod
    ORDER BY 
        mode.nombre_mod
    ASC
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

