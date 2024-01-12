<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_tor = $_POST["valor"];

?>
<option value="">Seleccione Departamento</option>
<?php
// $consulta = 
//     "
//     SELECT
//         ven.id_ven,
//         viv.nombre_viv,
//         viv.id_viv
//     FROM
//         vivienda_vivienda AS viv,
//         venta_venta AS ven
//     WHERE
//         EXISTS (
//             SELECT
//                 venta.id_ven
//             FROM
//                 venta_etapa_venta AS venta
//             WHERE
//                 venta.id_ven = ven.id_ven
//         ) AND
//         viv.id_tor = ".$id_tor." AND
//         viv.id_est_viv = 1 AND
//         ven.id_viv = viv.id_viv
//     ORDER BY
//         viv.nombre_viv ASC
//     ";

// la vivienda ya puede estar no disponible

$consulta = 
    "
    SELECT
        ven.id_ven,
        viv.nombre_viv,
        viv.id_viv,
        CONCAT(pro.nombre_pro, ' ', pro.apellido_paterno_pro, ' ', pro.apellido_materno_pro) As cliente
    FROM
        vivienda_vivienda AS viv,
        venta_venta AS ven,
        propietario_propietario AS pro
    WHERE
        EXISTS (
            SELECT
                venta.id_ven
            FROM
                venta_etapa_venta AS venta
            WHERE
                venta.id_ven = ven.id_ven
        ) AND
        viv.id_tor = ".$id_tor." AND
        ven.id_est_ven <> 3 AND 
        ven.id_viv = viv.id_viv AND 
        ven.id_pro = pro.id_pro
    ORDER BY
        viv.nombre_viv ASC
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        ?>
        <option value="<?php echo $fila['id_ven'];?>"><?php echo utf8_encode($fila['nombre_viv']." ( Venta: ".$fila['id_ven']." - Cliente: ".$fila['cliente'].")");?></option>
        <?php
    }
}
?>

