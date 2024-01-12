<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_POST["id_con"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
require "../../config.php";
require '../../class/conexion.php';
$conexion = new conexion();


$id_con = isset($_POST["id_con"]) ? $_POST["id_con"] : 0;
$conrecup = isset($_POST["conrecup"]) ? $_POST["conrecup"] : 0;
$piescr = isset($_POST["piescr"]) ? $_POST["piescr"] : 0;
$chpr = isset($_POST["chpr"]) ? $_POST["chpr"] : 0;
$chrecup = isset($_POST["chrecup"]) ? $_POST["chrecup"] : 0;
$piefal = isset($_POST["piefal"]) ? $_POST["piefal"] : 0;
$cantchprec = isset($_POST["cantchprec"]) ? $_POST["cantchprec"] : 0;
$canconrecu = isset($_POST["canconrecu"]) ? $_POST["canconrecu"] : 0;
$canchrecup = isset($_POST["canchrecup"]) ? $_POST["canchrecup"] : 0;
$fecha = date("Y-m-d");

$consulta = "INSERT INTO historico_recuperacion_historico VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
$conexion->consulta_form($consulta,array(0,$id_con,$_SESSION["sesion_id_panel"],$fecha,$conrecup,$piescr,$chpr,$chrecup,$piefal,$cantchprec,$canconrecu,$canchrecup));



$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>