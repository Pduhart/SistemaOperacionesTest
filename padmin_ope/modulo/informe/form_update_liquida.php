<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$monto_liq_uf_ven=0;
$monto_liq_pesos_ven=0;
$fecha_liq='';
$id_ven = $_POST["valor"];


$consulta = "SELECT ven.id_for_pag,viv.nombre_viv,ven.fecha_promesa_ven FROM venta_venta as ven,vivienda_vivienda as viv WHERE ven.id_ven = ? AND ven.id_viv = viv.id_viv";
$conexion->consulta_form($consulta,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$id_for_pag = utf8_encode($fila['id_for_pag']);
$nombre_viv = utf8_encode($fila['nombre_viv']);
$fecha_promesa_ven = $fila['fecha_promesa_ven'];
if ($fecha_promesa_ven<>null) $fecha_liq_com = date("d-m-Y",strtotime($fecha_promesa_ven));
$consultahay = "SELECT monto_liq_uf_ven,monto_liq_pesos_ven,fecha_liq_ven FROM venta_liquidado_venta WHERE id_ven = ?";
$conexion->consulta_form($consultahay,array($id_ven));
$insert = $conexion->total();
if ($insert>0) {
	$fila = $conexion->extraer_registro_unico();
	$fecha_liq_ven = utf8_encode($fila['fecha_liq_ven']);
	$monto_liq_uf_ven = utf8_encode($fila['monto_liq_uf_ven']);
	$monto_liq_pesos_ven = utf8_encode($fila['monto_liq_pesos_ven']);
	if ($fecha_liq_ven<>null)$fecha_liq = date("d-m-Y",strtotime($fecha_liq_ven));
}

$consultacampos = 
    "
    SELECT
        numero_factura_ven,
        monto_factura_ven,
        monto_nc_ven,
        numero_nc_ven,
        valor_cre_ven,
        fecha_alzamiento_ven,
        fecha_cargo_301_ven,
        fecha_abono_330_ven,
        ciudad_notaria_ven
    FROM
        venta_campo_venta
    WHERE 
        id_ven = ?
    ";
$conexion->consulta_form($consultacampos,array($id_ven));
$filacam = $conexion->extraer_registro_unico();
$numero_factura_ven = utf8_encode($filacam['numero_factura_ven']);
$monto_factura_ven = utf8_encode($filacam['monto_factura_ven']);
$numero_ncredito_ven = utf8_encode($filacam['numero_nc_ven']);
$monto_ncredito_ven = utf8_encode($filacam['monto_nc_ven']);
$valor_cre_ven = utf8_encode($filacam['valor_cre_ven']);
$ciudad_notaria_ven = $filacam['ciudad_notaria_ven'];
$fecha_alzamiento_ven = utf8_encode($filacam['fecha_alzamiento_ven']);
if ($fecha_alzamiento_ven<>null) $fecha_alzamiento_ven = date("d-m-Y",strtotime($fecha_alzamiento_ven));

$fecha_cargo_301_ven = utf8_encode($filacam['fecha_cargo_301_ven']);
if ($fecha_cargo_301_ven<>null) $fecha_cargo_301_ven = date("d-m-Y",strtotime($fecha_cargo_301_ven));

$fecha_abono_330_ven = utf8_encode($filacam['fecha_abono_330_ven']);
if ($fecha_abono_330_ven<>null)$fecha_abono_330_ven = date("d-m-Y",strtotime($fecha_abono_330_ven));

    $liquida = new stdClass();

 
    $liquida->venta = $id_ven;
    $liquida->unidad = $nombre_viv;
    $liquida->id = $id_ven;
    $liquida->insert = $insert;
 
	            if ($_SESSION["sesion_perfil_panel"] == 1):
	            	$consulta_esta_liquidado = "SELECT id_cie FROM cierre_venta_cierre WHERE id_ven = ? AND id_est_ven = 4";
					$conexion->consulta_form($consulta_esta_liquidado,array($id_ven));
					$hay_liquidacion = $conexion->total();
                        if($hay_liquidacion>0){
                            $readonly = "disabled";
                            $text_aclara = "- venta ya liquidada";
                        } else {
                            $readonly = "";
                            $text_aclara = "";
                        }
                    endif;
    $liquida->readonly = $readonly;
    $liquida->text_aclara = $text_aclara;
    $liquida->fecha_liq_com = $fecha_liq_com;
    $liquida->valor_cre_ven = $valor_cre_ven;
    $liquida->ciudad_notaria_ven = $ciudad_notaria_ven;
    $liquida->fecha_alzamiento_ven = $fecha_alzamiento_ven;


    $liquida->fecha_cargo_301_ven = $fecha_cargo_301_ven;
    $liquida->fecha_abono_330_ven = $fecha_abono_330_ven;
    $liquida->monto_factura_ven = $monto_factura_ven;
    $liquida->numero_factura_ven = $numero_factura_ven;
    $liquida->monto_ncredito_ven = $monto_ncredito_ven;
    $liquida->numero_ncredito_ven = $numero_ncredito_ven;
    $liquida->monto_liq_uf_ven = $monto_liq_uf_ven;
    $liquida->monto_liq_pesos_ven = $monto_liq_pesos_ven;
    $liquida->fecha_liq = $fecha_liq;

    echo json_encode($liquida, JSON_FORCE_OBJECT);
	            ?>

	           
                            
                            
                            
                                                                              
                    
           
        	
       

        
     
  
