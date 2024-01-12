<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_propietario_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["descripcion"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/propietario_clase.php");
$propietario = new propietario();
$ids = isset($_POST["ids"]) ? utf8_encode($_POST["ids"]) : 0;
$ids_cant = isset($_POST["ids_cant"]) ? utf8_encode($_POST["ids_cant"]) : 0;

$descripcion = isset($_POST["descripcion"]) ? utf8_decode($_POST["descripcion"]) : "";
$tipo_obs = isset($_POST["tipo_obs"]) ? utf8_decode($_POST["tipo_obs"]) : "";

if($tipo_obs<>'') {
	$describe_masiva = $tipo_obs;
} else {
	$describe_masiva = $descripcion;
}

$fecha = date("Y-m-d H:i:s");

$id_emp = explode(',',$ids);
$cantidad = $ids_cant - 1;
$contador = 0;
while($contador <= $cantidad ){
	$propietario->propietario_insert_observacion($id_emp[$contador],$_SESSION["sesion_id_panel"],$fecha,$describe_masiva);
	$contador++;
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>