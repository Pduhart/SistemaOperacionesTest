<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

// estructura base para devolver un mensaje de estatus de la petición
function status($title,$message,$icon){
        $jsondata['title'] = $title;
        $jsondata['message'] = $message;
        $jsondata['icon'] = $icon;
        echo json_encode($jsondata);
        die();
}
    // validación para obtener todos los valores del formulario
    if(!isset($_POST['trabajador']) || !isset($_POST['carta']) || !isset($_POST['descripcion']) || !isset($_POST['referencia']) || !isset($_POST['fundamentacion']) || !isset($_POST['resolucion'])){
        status("Incompleto!","Porfavor completar el formulario.","warning");
    }else{   
        
        try {    
            $descripcion = utf8_decode($_POST['descripcion']);        
            $referencia = utf8_decode($_POST['referencia']);        
            $fundamentacion = utf8_decode($_POST['fundamentacion']);        
            $resolucion = utf8_decode($_POST['resolucion']);        
            $registro = conexion::select("SELECT * FROM matriz_carta WHERE trabajador_id = ".$_POST['trabajador']." AND estado = 1");
            if(isset($registro[0])){
                if($_POST['carta'] == $registro[0]['anotacion']){
                    $conexion->consulta_form("UPDATE matriz_carta SET estado = 1 WHERE id = ?",array($registro[0]['id']));
                    $query = "INSERT INTO matriz_carta(trabajador_id,estado,anotacion,descripcion,referencia,fundamentacion,resolucion)VALUES(?,?,?,?,?,?,?)";
                    $conexion->consulta_form($query,array($_POST['trabajador'],1,$_POST['carta'],$descripcion,$referencia,$fundamentacion,$resolucion));
                    status("Registrado!","Evaluación de desempeño registrada con éxito.","success");                    
                   
                }else{                    
                    $conexion->consulta_form("UPDATE matriz_carta SET estado = 2 WHERE id = ?",array($registro[0]['id']));                                  
                    $query = "INSERT INTO matriz_carta(trabajador_id,estado,anotacion,descripcion,referencia,fundamentacion,resolucion)VALUES(?,?,?,?,?,?,?)";
                    $conexion->consulta_form($query,array($_POST['trabajador'],2,$_POST['carta'],$descripcion,$referencia,$fundamentacion,$resolucion));
                    status("Registrado!","Evaluación de desempeño registrada con éxito.","success");                
                   
                }
                
            }else{
                $query = "INSERT INTO matriz_carta(trabajador_id,estado,anotacion,descripcion,referencia,fundamentacion,resolucion)VALUES(?,?,?,?,?,?,?)";
                $conexion->consulta_form($query,array($_POST['trabajador'],1,$_POST['carta'],$descripcion,$referencia,$fundamentacion,$resolucion));
                status("Registrado!","Evaluación de desempeño registrada con éxito.","success");                
            }
        }catch(Exception $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","error");
        }catch(PDOException $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","error");           
        }  
       
        try {
            // $query = "INSERT INTO matriz_carta(trabajador_id,anotacion,descripcion,referencia,fundamentacion,resolucion)VALUES(?,?,?,?,?,?)";
            // $conexion->consulta_form($query,array($_POST['trabajador'],$_POST['carta'],$_POST['descripcion'],$_POST['referencia'],$_POST['fundamentacion'],$_POST['resolucion']));
            // status("Registrado!","Evaluación de desempeño registrada con éxito.","success");
        }catch(Exception $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","error");
        }catch(PDOException $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","error");           
        }        
    }   
?>