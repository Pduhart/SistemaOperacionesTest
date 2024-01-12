<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_con = $_POST["valor"];

?>
<option value="">Seleccione Torre</option>
<?php
if ($_SESSION["sesion_perfil_panel"] == 3) {
    //propietario
    $consulta = 
        "
        SELECT DISTINCT
            tor.id_tor,
            tor.nombre_tor
        FROM
            vivienda_vivienda AS viv
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
            INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
            INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
        WHERE
            tor.id_est_tor = 1 AND
            con.id_con = '".$id_con."' AND
            usu.id_usu = ".$_SESSION["sesion_id_panel"]."
        ";
}
else{
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
}

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

