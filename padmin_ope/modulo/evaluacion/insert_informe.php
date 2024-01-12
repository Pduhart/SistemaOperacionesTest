<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
function status($title,$message,$icon){
    $jsondata['title'] = $title;
    $jsondata['message'] = $message;
    $jsondata['icon'] = $icon;
    echo json_encode($jsondata);
    die();
}
try {
    $query_desarrollo = "SELECT id FROM matriz_desarrollo WHERE id_vendedor=".$_POST['persona'];
    $id = conexion::select($query_desarrollo);   
    $query = "INSERT INTO matriz_informe VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $conexion->consulta_form($query,array(0,
                                          $id[0]['id'],
                                          utf8_decode($_POST['persona']),
                                          utf8_decode($_POST['cargoEvaluador']),
                                          utf8_decode($_POST['evaluador']),
                                          'Asesor Inmobiliario',
                                          $_POST['merito'],
                                          $_POST['demerito'],
                                          $_POST['desarrollo'],
                                          utf8_decode($_POST['fundamentacion1']),
                                          utf8_decode($_POST['fundamentacion2']),
                                          utf8_decode($_POST['fundamentacion3']),
                                          utf8_decode($_POST['fundamentacion4']),
                                          utf8_decode($_POST['fundamentacion5']),
                                          utf8_decode($_POST['obsmejora']),
                                          utf8_decode($_POST['hecho']),
                                          utf8_decode($_POST['objetivo']),
                                          utf8_decode($_POST['mejora']),
                                          utf8_decode($_POST['kpi']),
                                          $_POST['ausentismo'],
                                          $_POST['compensa'],
                                          $_POST['ciclo_evaluacion'],
                                          $_POST['fecha_evaluacion']
                                        ));
    $last_id = $conexion->ultimo_id();
    status("data",$last_id,"success");
}catch(Exception $e) {
    status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","danger");
}catch(PDOException $e) {
    status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","danger");           
}   
?>