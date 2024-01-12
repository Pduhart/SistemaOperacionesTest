<?php
session_start();
date_default_timezone_set('America/Santiago');
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_POST["fecha_cierre"])){
	header("Location: ../../panel.php");
	exit();
}

require "../../config.php";
include("../../class/conexion.php");
$conexion = new conexion();

$id_ven = isset($_POST["id_ven"]) ? utf8_decode($_POST["id_ven"]) : 0;
$id_etapa = isset($_POST["id_etapa"]) ? utf8_decode($_POST["id_etapa"]) : 0;
$id_etapa_venta = isset($_POST["id_etapa_venta"]) ? utf8_decode($_POST["id_etapa_venta"]) : 0;
$fecha = isset($_POST["fecha_cierre"]) ? utf8_decode($_POST["fecha_cierre"]) : null;

if ($fecha<>'' || $fecha<> null) {
	$fecha_formato = date("Y-m-d",strtotime($fecha));
	// H:i:s
	$fecha = $fecha_formato." ".date("H:i:s");
}


$consulta = "SELECT id_est_ven, id_viv, cotizacion_ven, id_pro FROM venta_venta WHERE id_ven = ?";
$conexion->consulta_form($consulta,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$id_est_ven = $fila["id_est_ven"];
$id_viv = $fila["id_viv"];
$cotizacion_ven = $fila["cotizacion_ven"];
$propietario = $fila["id_pro"];

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
            if ($valor_campo<>null) {
            	$valor_campo = date("Y-m-d",strtotime($valor_campo));
            }
        }
        else{
            $valor_campo = isset($_POST["campo_extra_".$id_cam_eta]) ? utf8_decode($_POST["campo_extra_".$id_cam_eta]) : "";
        }

        // ahora cuando cierra igual pregunta si el campo ya existe sino lo repetía

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
	        		if ($valor_campo=='') {
	        			$valor_campo = NULL;
	        		}
	        		$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,NULL,$valor_campo));
	        	} else if($id_cam_eta==52){
	        		if ($valor_campo=='') {
	        			$valor_campo = NULL;
	        		}
					$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	            	$conexion->consulta_form($consulta,array(0,$id_ven,$hoy_reg,$valor_campo,NULL));
	        	}
	        }
	        else{
	        	if ($id_cam_eta==51) {
	        		if ($valor_campo=='') {
	        			$valor_campo = NULL;
	        		}
	            	$consulta = "UPDATE venta_liquidado_venta SET monto_liq_pesos_ven = ? WHERE id_ven = ?";
	           		$conexion->consulta_form($consulta,array($valor_campo,$id_ven));
	           	} else if($id_cam_eta==52){
	           		if ($valor_campo=='') {
	        			$valor_campo = NULL;
	        		}
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

        // $consulta = "INSERT INTO venta_etapa_campo_venta VALUES(?,?,?,?,?,?,?,?)";
		// $conexion->consulta_form($consulta,array(0,$id_etapa_venta,$id_ven,$id_etapa,$fila["id_tip_cam_eta"],$fila["id_cam_eta"],$fila["nombre_cam_eta"],$valor_campo));
    }
}
// validar que fecha cierre sea mayor que la apertura
$consulta = "SELECT fecha_desde_eta_ven FROM venta_etapa_venta WHERE id_eta_ven = ?";
$conexion->consulta_form($consulta,array($id_etapa_venta));
$fila_consulta = $conexion->extraer_registro_unico();
$fecha_desde_eta_ven = $fila_consulta["fecha_desde_eta_ven"];
$fecha_desde_eta_ven_for = date("Y-m-d",strtotime($fecha_desde_eta_ven));

if ($fecha_desde_eta_ven_for <= $fecha_formato) {
	// 
	$consulta = "UPDATE venta_etapa_venta SET fecha_hasta_eta_ven = ?, id_est_eta_ven = ? WHERE id_eta_ven = ?";	
	$conexion->consulta_form($consulta,array($fecha,1,$id_etapa_venta));


	$consulta = "SELECT id_for_pag, numero_real_eta FROM etapa_etapa WHERE id_eta = ?";
	$conexion->consulta_form($consulta,array($id_etapa));
	$fila = $conexion->extraer_registro_unico();
	$id_for_pag = $fila["id_for_pag"];
	$numero_real_eta = $fila["numero_real_eta"];

	if($id_for_pag == 1){
	    if($id_etapa == 27){ //etapa notaría firma cliente
	    	// va a buscar fecha firma cliente para guardarlo n la venta
			$consulta = "SELECT valor_campo_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 27 AND id_cam_eta = 32";
			$conexion->consulta_form($consulta,array($id_ven));
			$fila = $conexion->extraer_registro_unico();
			$fecha_firma_cliente = $fila["valor_campo_eta_cam_ven"];
			$fecha_firma_cliente = date("Y-m-d",strtotime($fecha_firma_cliente));

	        $consulta = "UPDATE venta_venta SET id_est_ven = ?, id_operacion_ven = ?, fecha_escritura_ven = ? WHERE id_ven = ?";    
	        $conexion->consulta_form($consulta,array(6,$_SESSION["sesion_id_panel"],$fecha_firma_cliente,$id_ven));

	        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
            $conexion->consulta_form($consulta,array(0,$id_ven,6));

	        // cotizacion
	        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
	        $conexion->consulta_form($consulta,array(6,$cotizacion_ven));
	        
	        // $consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "SELECT id_esta FROM venta_estacionamiento_venta WHERE id_ven = ?";
	        // $conexion->consulta_form($consulta,array($id_ven));
	        // $fila_consulta = $conexion->extraer_registro();
	        // if(is_array($fila_consulta)){
	        //     foreach ($fila_consulta as $fila) {
	                
	        //         $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ?, id_viv = ? WHERE id_esta = ?";    
	        //         $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_esta"]));
	        //     }
	        // }

	        // $consulta = "SELECT id_bod FROM venta_bodega_venta WHERE id_ven = ?";
	        // $conexion->consulta_form($consulta,array($id_ven));
	        // $fila_consulta = $conexion->extraer_registro();
	        // if(is_array($fila_consulta)){
	        //     foreach ($fila_consulta as $fila) {
	                
	        //         $consulta = "UPDATE bodega_bodega SET id_est_bod = ?, id_viv = ? WHERE id_bod = ?";    
	        //         $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_bod"]));
	        //     }
	        // }
	  //       $consulta = "INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$propietario,$id_viv));

	    }
	    else if($id_etapa > 27 && $id_etapa<> 51){
	        $consulta = "UPDATE venta_venta SET id_est_ven = ? WHERE id_ven = ?";    
	        $conexion->consulta_form($consulta,array(7,$id_ven));

	        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
	        $conexion->consulta_form($consulta,array(7,$cotizacion_ven));

	        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
            $conexion->consulta_form($consulta,array(0,$id_ven,7));
	    }
	}
	else{
	    if($id_etapa == 6){ //firma cliente contado
			// va a buscar fecha firma cliente para guardarlo n la venta
			$consulta = "SELECT valor_campo_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 6 AND id_cam_eta = 5";
			$conexion->consulta_form($consulta,array($id_ven));
			$fila = $conexion->extraer_registro_unico();
			$fecha_firma_cliente = $fila["valor_campo_eta_cam_ven"];
			$fecha_firma_cliente = date("Y-m-d",strtotime($fecha_firma_cliente));

	        $consulta = "UPDATE venta_venta SET id_est_ven = ?, id_operacion_ven = ?, fecha_escritura_ven = ? WHERE id_ven = ?";    
	        $conexion->consulta_form($consulta,array(6,$_SESSION["sesion_id_panel"],$fecha_firma_cliente,$id_ven));

	        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
            $conexion->consulta_form($consulta,array(0,$id_ven,6));

	        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
	        $conexion->consulta_form($consulta,array(6,$cotizacion_ven));

	        // $consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
	        // $conexion->consulta_form($consulta,array(2,$id_viv));

	        // $consulta = "SELECT id_esta FROM venta_estacionamiento_venta WHERE id_ven = ?";
	        // $conexion->consulta_form($consulta,array($id_ven));
	        // $fila_consulta = $conexion->extraer_registro();
	        // if(is_array($fila_consulta)){
	        //     foreach ($fila_consulta as $fila) {
	                
	        //         $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ?, id_viv = ? WHERE id_esta = ?";    
	        //         $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_esta"]));
	        //     }
	        // }

	        // $consulta = "SELECT id_bod FROM venta_bodega_venta WHERE id_ven = ?";
	        // $conexion->consulta_form($consulta,array($id_ven));
	        // $fila_consulta = $conexion->extraer_registro();
	        // if(is_array($fila_consulta)){
	        //     foreach ($fila_consulta as $fila) {
	                
	        //         $consulta = "UPDATE bodega_bodega SET id_est_bod = ?, id_viv = ? WHERE id_bod = ?";    
	        //         $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_bod"]));
	        //     }
	        // }

	        // $consulta = "INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$propietario,$id_viv));
	    }
	    else if($id_etapa > 6){
	        $consulta = "UPDATE venta_venta SET id_est_ven = ? WHERE id_ven = ?";    
	        $conexion->consulta_form($consulta,array(7,$id_ven));

	        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
            $conexion->consulta_form($consulta,array(0,$id_ven,7));

	        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
	        $conexion->consulta_form($consulta,array(7,$cotizacion_ven));
	    }
	}

	$consulta = "SELECT id_eta FROM etapa_etapa WHERE id_for_pag = ? AND numero_real_eta > ? ORDER BY numero_real_eta ASC LIMIT 0,1";
	$conexion->consulta_form($consulta,array($id_for_pag,$numero_real_eta));
	$hayproximo = $conexion->total();
	if ($hayproximo>0) {
		$fila = $conexion->extraer_registro_unico();
		$id_etapa_siguiente = $fila["id_eta"];


		$fecha = null;
		$consulta = "INSERT INTO venta_etapa_venta VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_ven,$id_etapa_siguiente,3,$fecha,$fecha,""));
	}
	$jsondata['envio'] = 1;
	echo json_encode($jsondata);
	exit();
} else {
	$jsondata['envio'] = 4;
	echo json_encode($jsondata);
	exit();
}
?>