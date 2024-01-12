<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["fecha_editar"])){
	header("Location: ../../panel.php");
	exit();
}
require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();

$id_ven_editar = isset($_POST["id_ven_editar"]) ? utf8_decode($_POST["id_ven_editar"]) : 0;
$id_etapa_editar = isset($_POST["id_etapa_editar"]) ? utf8_decode($_POST["id_etapa_editar"]) : 0;
$id_etapa_venta_editar = isset($_POST["id_etapa_venta_editar"]) ? utf8_decode($_POST["id_etapa_venta_editar"]) : 0;

// nuevo campos
$nuevos_campos = isset($_POST["nuevos_campos"]) ? $_POST["nuevos_campos"] : 0;

$fecha_editar = isset($_POST["fecha_editar"]) ? utf8_decode($_POST["fecha_editar"]) : "0000-00-00 00:00:00";

$fecha_formato = date("Y-m-d",strtotime($fecha_editar));
$fecha_editar = $fecha_formato." ".date("H:i:s");

$fecha_inicio_editar = isset($_POST["fecha_inicio_editar"]) ? utf8_decode($_POST["fecha_inicio_editar"]) : "0000-00-00 00:00:00";

$fecha_formato_inicio = date("Y-m-d",strtotime($fecha_inicio_editar));
$fecha_inicio_editar = $fecha_formato_inicio." ".date("H:i:s");


$consulta = "UPDATE venta_etapa_venta SET fecha_hasta_eta_ven = ?, fecha_desde_eta_ven = ? WHERE id_eta_ven = ?";
$conexion->consulta_form($consulta,array($fecha_editar,$fecha_inicio_editar,$id_etapa_venta_editar));



$consulta = "SELECT * FROM venta_etapa_campo_venta WHERE id_eta = ? AND id_ven = ?";
$conexion->consulta_form($consulta,array($id_etapa_editar,$id_ven_editar));
//echo $id_etapa_editar."id_etapa_editar<br>";
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	$id_cam_eta = $fila["id_eta_cam_ven"];

        if($fila["id_tip_cam_eta"] == 2){
            $valor_campo = isset($_POST["campo_extra_editar_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_editar_".$id_cam_eta]) : 0;
        }
        else if($fila["id_tip_cam_eta"] == 3){
            $valor_campo = isset($_POST["campo_extra_editar_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_editar_".$id_cam_eta]) : "0000-00-00 00:00:00";
            $valor_campo = date("Y-m-d",strtotime($valor_campo));
        }
        else{
            $valor_campo = isset($_POST["campo_extra_editar_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_editar_".$id_cam_eta]) : "";
        }

        $consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_eta_cam_ven = ?";
		$conexion->consulta_form($consulta,array($valor_campo,$id_cam_eta));

		
    }
}


// insertar campos nuevos
if ($nuevos_campos>0) {
	$consulta = "SELECT * FROM etapa_campo_etapa WHERE id_eta = ?";
	$conexion->consulta_form($consulta,array($id_etapa_editar));
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
	    foreach ($fila_consulta as $fila) {
	    	$id_cam_eta = $fila["id_cam_eta"];
	        if($fila["id_tip_cam_eta"] == 2){
	            $valor_campo = isset($_POST["campo_extra_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_".$id_cam_eta]) : 0;
	        }
	        else if($fila["id_tip_cam_eta"] == 3){
	            $valor_campo = isset($_POST["campo_extra_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_".$id_cam_eta]) : null;
	            if ($valor_campo<>null || $valor_campo <> '') {
	            	$valor_campo = date("Y-m-d",strtotime($valor_campo));
	            } else {
	            	$valor_campo = null;
	            }
	            
	        }
	        else{
	            $valor_campo = isset($_POST["campo_extra_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_".$id_cam_eta]) : "";
	        }
	        $consulta = "SELECT * FROM venta_etapa_campo_venta WHERE id_eta_ven = ? AND id_ven = ? AND id_eta = ? AND id_cam_eta = ?";
	        $conexion->consulta_form($consulta,array($id_etapa_venta_editar,$id_ven_editar,$id_etapa_editar,$id_cam_eta));
	        $cantidad_registro = $conexion->total();
	        if($cantidad_registro == 0){
	            $consulta = "INSERT INTO venta_etapa_campo_venta VALUES(?,?,?,?,?,?,?,?)";
	            $conexion->consulta_form($consulta,array(0,$id_etapa_venta_editar,$id_ven_editar,$id_etapa_editar,$fila["id_tip_cam_eta"],$id_cam_eta,$fila["nombre_cam_eta"],$valor_campo));
	        }
	    }
	}
}

// cambios específicos
$consulta = "SELECT id_for_pag FROM venta_venta WHERE id_ven = ?";
$conexion->consulta_form($consulta,array($id_ven_editar));
$fila_up = $conexion->extraer_registro_unico();
$id_for_pag = $fila_up["id_for_pag"];

if($id_for_pag == 1){
	if($id_etapa_editar == 27){
		$consulta = "SELECT valor_campo_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 27 AND id_cam_eta = 32";
		$conexion->consulta_form($consulta,array($id_ven_editar));
		$fila = $conexion->extraer_registro_unico();
		$fecha_firma_cliente = $fila["valor_campo_eta_cam_ven"];
		$fecha_firma_cliente = date("Y-m-d",strtotime($fecha_firma_cliente));

        $consulta = "UPDATE venta_venta SET fecha_escritura_ven = ? WHERE id_ven = ?";    
        $conexion->consulta_form($consulta,array($fecha_firma_cliente,$id_ven_editar));
	}
} else {
	if($id_etapa_editar == 6){
		$consulta = "SELECT valor_campo_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 6 AND id_cam_eta = 5";
		$conexion->consulta_form($consulta,array($id_ven));
		$fila = $conexion->extraer_registro_unico();
		$fecha_firma_cliente = $fila["valor_campo_eta_cam_ven"];
		$fecha_firma_cliente = date("Y-m-d",strtotime($fecha_firma_cliente));

		$consulta = "UPDATE venta_venta SET fecha_escritura_ven = ? WHERE id_ven = ?";    
        $conexion->consulta_form($consulta,array($fecha_firma_cliente,$id_ven_editar));
	}
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>