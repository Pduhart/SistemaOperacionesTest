<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_bono_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/bono_clase.php");
$bono = new bono();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;
$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$tipo_bono = isset($_POST["tipo_bono"]) ? utf8_decode($_POST["tipo_bono"]) : "";
$cat_bono = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : "";
$modelo = isset($_POST["modelo"]) ? utf8_decode($_POST["modelo"]) : "";
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$valor = isset($_POST["valor"]) ? utf8_decode($_POST["valor"]) : "";
$desde = isset($_POST["desde"]) ? utf8_decode($_POST["desde"]) : 0;
$hasta = isset($_POST["hasta"]) ? utf8_decode($_POST["hasta"]) : 0;

$fecha_desde = isset($_POST["fecha_desde"]) ? utf8_decode($_POST["fecha_desde"]) : null;
$fecha_hasta = isset($_POST["fecha_hasta"]) ? utf8_decode($_POST["fecha_hasta"]) : null;

if(empty($modelo)){
	$modelo = 0;
}

if(!empty($fecha_desde)){
	$fecha_desde = date("Y-m-d",strtotime($fecha_desde));
}
else{
	$fecha_desde = null;
}

if(!empty($fecha_hasta)){
	$fecha_hasta = date("Y-m-d",strtotime($fecha_hasta));
}
else{
	$fecha_hasta = null;
}



$bono->bono_crea($condominio,$tipo_bono,$modelo,$cat_bono,1,$nombre,$desde,$hasta,$valor,$fecha_desde,$fecha_hasta);
$bono->bono_update($id);


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>