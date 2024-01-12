<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_modelo_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/modelo_clase.php");
$modelo = new modelo();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;

$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$numero_banio = isset($_POST["numero_banio"]) ? utf8_decode($_POST["numero_banio"]) : 0;
$numero_cama = isset($_POST["numero_cama"]) ? utf8_decode($_POST["numero_cama"]) : 0;

$modelo->modelo_crea($nombre,$numero_cama,$numero_banio,$descripcion);
$modelo->modelo_update($id);


// $modelo->modelo_delete_servicio_modelo($id); 
// if (isset($_POST['servicio_vivienda'])) {
//     if (is_array($_POST['servicio_vivienda'])) {
//         foreach ($_POST['servicio_vivienda'] as $key => $id_ser) {
//         	$modelo->modelo_insert_servicio_modelo($id, $id_ser); 
//         }
//     }
// }

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>