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
    SELECT DISTINCT
        viv.nombre_viv,
        viv.id_viv,
        ven.id_ven,
        CONCAT(pro.nombre_pro, ' ', pro.apellido_paterno_pro, ' ', pro.apellido_materno_pro) As cliente
    FROM 
        venta_venta AS ven
        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
        INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
        INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
    WHERE
        viv.id_tor = ".$id_tor." AND 
        ven.id_est_ven <> 3
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
        <option value="<?php echo $fila['id_viv'];?>-<?php echo $fila['id_ven'];?>"><?php echo utf8_encode($fila['nombre_viv'])." ( Venta: ".$fila['id_ven']." - Cliente: ".utf8_encode($fila['cliente']).")";?></option>
        <?php
    }
}
?>

