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
    $merito = "NO";  
    $demerito = "NO";  
    $id = conexion::select("SELECT id FROM matriz_desarrollo WHERE id_vendedor=34");   
    $anotacion = conexion::select("SELECT anotacion FROM matriz_carta WHERE trabajador_id=34 AND estado = 1");   
    // $query_total_competencias = "SELECT (((`rpregunta1` * 100) DIV 4) + ((`rpregunta2` * 100) DIV 4) + ((`rpregunta3` * 100) DIV 4) + ((`rpregunta4` * 100) DIV 4)) DIV 4 as total FROM `matriz_desarrollo` WHERE `id_vendedor`=34";
    if(isset($anotacion[0])){
        if($anotacion[0]['anotacion'] == "Merito" ){
            $merito = "SI";
        }else if($anotacion[0]['anotacion'] == "Demerito"){
            $demerito = "SI";
        }
    }
    $query = "INSERT INTO matriz_informe VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $conexion->consulta_form($query,array(0,
                                          $id[0]['id'],
                                          34,
                                          7,
                                          utf8_decode("Sara Noemí Araya Bugueño"),
                                          "Encargada de Operaciones",
                                          $merito,
                                          $demerito,
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
                                          "NO",
                                          "NO",
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