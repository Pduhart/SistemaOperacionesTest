<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_vend = $_POST["valor"];

?>
<option value="">Seleccione Departamento</option>
<?php
$consulta = 
    "
    SELECT DISTINCT
        venta.id_ven,
        viv.nombre_viv,
        viv.id_viv
    FROM 
        vivienda_vivienda AS viv 
        LEFT JOIN venta_venta AS venta ON venta.id_viv = viv.id_viv
    WHERE
        EXISTS (
            SELECT
                ven.id_ven
            FROM
                venta_etapa_venta AS ven
            WHERE
                ven.id_ven = venta.id_ven
        ) AND
        venta.id_vend = ".$id_vend." AND
        viv.id_est_viv = 1
    ORDER BY 
        viv.nombre_viv
    ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$cantidad = $conexion->total();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_ven'];?>"><?php echo utf8_encode($fila['nombre_viv']." ( Venta: ".$fila['id_ven'].")");?></option>
        <?php
    }
}
?>

