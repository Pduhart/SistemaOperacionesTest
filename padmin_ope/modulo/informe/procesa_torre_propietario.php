<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_tor = $_POST["valor"];

?>
<option value="">Seleccione Departamento</option>
<?php
if ($_SESSION["sesion_perfil_panel"] == 3) {
    //propietario
    $consulta = 
        "
        SELECT DISTINCT
            viv.id_viv,
            viv.nombre_viv
        FROM
            vivienda_vivienda AS viv
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
            INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
            INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
        WHERE
            viv.id_tor = ".$id_tor." AND
            viv.id_est_viv = 1 AND
            usu.id_usu = ".$_SESSION["sesion_id_panel"]."
        ";
}
else{
    $consulta = 
    "
    SELECT 
        viv.nombre_viv,
        viv.id_viv
    FROM 
        vivienda_vivienda AS viv
    WHERE
        viv.id_tor = ".$id_tor." AND
        viv.id_est_viv = 1
    ORDER BY 
        viv.nombre_viv
    ASC
    ";
}

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

