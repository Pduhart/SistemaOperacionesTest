<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_mod = $_POST["valor"];
$id_tor = $_POST["torre"];


?>
<option value="">Seleccione Departamento</option>
<?php
if ($id_tor > 0) {
    $consulta = 
        "
        SELECT 
            * 
        FROM
            vivienda_vivienda AS viv
            INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
        WHERE
            viv.id_mod = '".$id_mod."' AND
        viv.id_tor = '".$id_tor."' AND NOT
            EXISTS(
                SELECT 
                    ven.id_ven
                FROM 
                    venta_venta AS ven
                WHERE
                    ven.id_viv = viv.id_viv AND
                    ven.id_est_ven <> 3
            )
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
            <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?> (<?php echo number_format($fila["valor_viv"], 0, ',', '.'); ?> UF)</option>
            <?php
        }
    }
}
else{
    $consulta = 
        "
        SELECT 
            * 
        FROM
            vivienda_vivienda AS viv
            INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
        WHERE
            viv.id_mod = '".$id_mod."' AND NOT
            EXISTS(
                SELECT 
                    ven.id_ven
                FROM 
                    venta_venta AS ven
                WHERE
                    ven.id_viv = viv.id_viv AND
                    ven.id_est_ven <> 3
            )
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
            <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?> (<?php echo number_format($fila["valor_viv"], 0, ',', '.'); ?> UF)</option>
            <?php
        }
    }
}

?>

