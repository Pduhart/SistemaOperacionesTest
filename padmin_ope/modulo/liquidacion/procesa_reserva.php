<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$filtro = "";

$id_con = $_POST["condominio"];
$id_tor = $_POST["torre"];
$id_viv = $_POST["departamento"];

if (!empty($id_con) && $id_con > 0) {
    $filtro .= " AND con.id_con = ".$id_con;
}
if (!empty($id_tor) && $id_tor > 0) {
    $filtro .= " AND tor.id_tor = ".$id_tor;
}
if (!empty($id_viv) && $id_viv > 0) {
    $filtro .= " AND viv.id_viv = ".$id_viv;
}
?>
<option value="">Elegir Reserva</option> 
<?php   
$consulta =  
    " 
    SELECT
        res.id_res
    FROM
        reserva_reserva AS res
        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = res.id_viv
        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
    WHERE
        res.id_est_pag_res = 1 AND
        res.id_est_res = 2  ".$filtro."
    ORDER BY
        res.id_res 
    "; 
$conexion->consulta($consulta); 
$fila_consulta = $conexion->extraer_registro(); 

if(is_array($fila_consulta)){ 
    foreach ($fila_consulta as $fila) { 
        ?> 
        <option value="<?php echo $fila['id_res'];?>"><?php echo utf8_encode($fila['id_res']);?></option> 
        <?php 
    } 
} 
?> 
