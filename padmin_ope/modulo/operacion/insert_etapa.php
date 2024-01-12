<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["id_ven"])){
	header("Location: ../../panel.php");
	exit();
}

require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();

$id_ven = isset($_POST["id_ven"]) ? utf8_decode($_POST["id_ven"]) : 0;
$id_etapa = isset($_POST["id_etapa"]) ? utf8_decode($_POST["id_etapa"]) : 0;
$id_etapa_venta = isset($_POST["id_etapa_venta"]) ? utf8_decode($_POST["id_etapa_venta"]) : 0;


$consulta = "SELECT * FROM etapa_campo_etapa WHERE id_eta = ?";
$conexion->consulta_form($consulta,array($id_etapa));
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
        $conexion->consulta_form($consulta,array($id_etapa_venta,$id_ven,$id_etapa,$id_cam_eta));
        $cantidad_registro = $conexion->total();
        if($cantidad_registro == 0){
            $consulta = "INSERT INTO venta_etapa_campo_venta VALUES(?,?,?,?,?,?,?,?)";
            $conexion->consulta_form($consulta,array(0,$id_etapa_venta,$id_ven,$id_etapa,$fila["id_tip_cam_eta"],$id_cam_eta,$fila["nombre_cam_eta"],$valor_campo));
        }
        else{
            $consulta = "UPDATE venta_etapa_campo_venta SET nombre_eta_cam_ven = ?, valor_campo_eta_cam_ven = ? WHERE id_eta_ven = ? AND id_ven = ? AND id_eta = ? AND id_cam_eta = ?";
            $conexion->consulta_form($consulta,array($fila["nombre_cam_eta"],$valor_campo,$id_etapa_venta,$id_ven,$id_etapa,$id_cam_eta));
        }

		// etapa liquidacion pagos
		if ($id_etapa==38) {
			$consulta_liq = "SELECT * FROM venta_liquidado_venta WHERE id_ven = ?";
	        $conexion->consulta_form($consulta_liq,array($id_ven));
	        $cantidad_registro_liq = $conexion->total();
	        $hoy_reg = date("Y-m-d");
	        if($cantidad_registro_liq == 0){
	        	if ($id_cam_eta==51) {
	        		$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,0,$valor_campo));
	        	} else if($id_cam_eta==52){
					$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,$valor_campo,0));
	        	}
	        }
	        else{
	        	if ($id_cam_eta==51) {
	            	$consulta = "UPDATE venta_liquidado_venta SET monto_liq_pesos_ven = ? WHERE id_ven = ?";
	           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
	           	} else if($id_cam_eta==52){
					$consulta = "UPDATE venta_liquidado_venta SET monto_liq_uf_ven = ? WHERE id_ven = ?";
	           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
	        	}
	        }
		}

		// etapa liquidacion pagos
		if ($id_etapa==47) {
			$consulta_liq = "SELECT * FROM venta_liquidado_venta WHERE id_ven = ?";
	        $conexion->consulta_form($consulta_liq,array($id_ven));
	        $cantidad_registro_liq = $conexion->total();
	        $hoy_reg = date("Y-m-d");
	        if($cantidad_registro_liq == 0){
	        	if ($id_cam_eta==49) {
	        		$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,0,$valor_campo));
	        	} else if($id_cam_eta==50){
					$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,$valor_campo,0));
	        	}
	        }
	        else{
	        	if ($id_cam_eta==49) {
	            	$consulta = "UPDATE venta_liquidado_venta SET monto_liq_pesos_ven = ? WHERE id_ven = ?";
	           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
	           	} else if($id_cam_eta==50){
					$consulta = "UPDATE venta_liquidado_venta SET monto_liq_uf_ven = ? WHERE id_ven = ?";
	           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
	        	}
	        }
		}

		// etapa ciudad notaria
		// crédito
		if ($id_etapa==27) {
			if ($id_cam_eta==68) {
            	$consulta = "UPDATE venta_campo_venta SET ciudad_notaria_ven = ? WHERE id_ven = ?";
           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
           	}
		}

		// etapa ciudad notaria
		// contado
		if ($id_etapa==5) {
			if ($id_cam_eta==67) {
            	$consulta = "UPDATE venta_campo_venta SET ciudad_notaria_ven = ? WHERE id_ven = ?";
           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
           	}
		}
    }
}


$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>