<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$ven_id = (isset($_POST['vendedor']))?$_POST['vendedor']:0;
$resp1 = (isset($_POST['optionsRadios1']))?$_POST['optionsRadios1']:0;
$resp2 = (isset($_POST['optionsRadios2']))?$_POST['optionsRadios2']:0;
$resp3 = (isset($_POST['optionsRadios3']))?$_POST['optionsRadios3']:0;
$resp4 = (isset($_POST['optionsRadios4']))?$_POST['optionsRadios4']:0;

// funcion para devolver el valor de porcentaje
function percents($val){
    $var = 0;
    if($val != 0){
        $var = ($val * 100) / 16;
    }
    return $var;
}
// funcion para separar el dato y convertirlo en tipo numero ej: str_split("12") -> ["1"]["2"] -> intval(["2"]) = 2
function formato($val){
    $var = 0;
    $var = str_split($val);
    // se le resta uno para quedar desde el rango 0 a 4
    return intval($var[1] - 1);
}
// estructura base para devolver un mensaje de estatus de la petición
function status($title,$message,$icon){
        $jsondata['title'] = $title;
        $jsondata['message'] = $message;
        $jsondata['icon'] = $icon;
        echo json_encode($jsondata);
        die();
}
    // validación para obtener todos los valores del formulario
    if($ven_id == 0 || $resp1 == 0 || $resp2 == 0 || $resp3 == 0 || $resp4 == 0){
        status("Incompleto!","Porfavor completar el formulario.","warning");
    }else{       
        $pts = (formato($resp1) + formato($resp2) + formato($resp3) + formato($resp4));        
        $percents = percents($pts);
        try {
            $query = "INSERT INTO matriz_desarrollo(puntos,porcentaje,id_vendedor,rpregunta1,rpregunta2,rpregunta3,rpregunta4)VALUES(?,?,?,?,?,?,?)";
            $conexion->consulta_form($query,array($pts,$percents,$ven_id,formato($resp1),formato($resp2),formato($resp3),formato($resp4)));
            status("Registrado!","Evaluación de desempeño registrada con éxito.","success");
        }catch(Exception $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","danger");
        }catch(PDOException $e) {
            status("Error!","A ocurrido un error grave, contactar al administrador. codigo de error : ".$e->message()." ","danger");           
        }        
    }   
?>