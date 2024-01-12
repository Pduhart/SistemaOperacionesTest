<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_viv = $_POST["valor"];


$consulta = 
    "
    SELECT
        mode.id_mod,
        mode.nombre_mod
    FROM
        modelo_modelo AS mode,
        vivienda_vivienda AS viv
    WHERE
        mode.id_mod = viv.id_mod AND
        viv.id_viv = ".$id_viv."
    ";

$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$id_mod = utf8_encode($fila['id_mod']);
$nombre_mod = utf8_encode($fila['nombre_mod']);

?>
<option value="<?php echo $id_mod;?>"><?php echo utf8_encode($nombre_mod);?></option>
<?php


