<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'alzamiento_contable'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<title>Ventas - Informe</title>
<!-- DataTables -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->

    <section class="content">
        <div class="col-sm-12">
            <!-- general form elements -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <!-- <div class="row">
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    <div class="col-sm-5"> -->

                                                        <?php  
                                                        $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_condominio_original)){
                                                            foreach ($fila_consulta_condominio_original as $fila) {
                                                             
                                                            }
                                                        }
                                                        ?>
                                                    <!-- </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row"> -->
                                            <!-- <div class="col-sm-12" id="contenedor_filtro"> -->
                                                <!-- <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px"> -->
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $filtro_consulta_cierre = '';
                                                    $elimina_filtro = 0;
                                                    
                                                    


                                                    if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                                                        $fila_consulta_condominio = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_condominio[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_condominio)){
                                                            foreach ($fila_consulta_condominio as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                                    $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                    $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span>   -->
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= " AND cie.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
      
                                                    }
                                                    ?>
                                                    
                                                <!-- </i> -->
                                              <!-- </h6> -->
                                            <!-- </div> -->
                                        <!-- </div> -->
                                        <div class="col-md-12">
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body" style="overflow-x: hidden;">
														<table class="table table-bordered tabla">
															<tr class="bg-verde">
																<td colspan="4"></td>
																<td colspan="6">Alzamiento</td>
															</tr>
															<tr class="bg-verdeclaro">
																<td></td>
																<td>Cliente</td>
																<td>Dpto.</td>
																<td>Valor Final Inmob.</td>
																<td>Fecha Cargo Cuenta 301</td>
																<td>Fecha Abono Cuenta 330</td>
																<td>Fecha Crédito</td>
																<td>N° días (solicitud/pago efect.)</td>
																<td>Alzado</td>
																<td>Pagado</td>
															</tr>
                                                            <?php
                                                            $acumulado_monto = 0;
                                                            $total_valor_liquidado_uf_credito = 0;
                                                            $contador = 0;
                                                            
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    ven.id_ven,
                                                                    ven.fecha_ven,
                                                                    ven.monto_ven,
                                                                    ven.id_for_pag,
                                                                    ven.id_ban,
                                                                    ven.id_tip_pag,
                                                                    ven.descuento_ven,
                                                                    ven.monto_credito_ven,
                                                                    ven.monto_credito_real_ven,
                                                                    viv.id_viv,
                                                                    viv.nombre_viv,
                                                                    mode.id_mod,
                                                                    mode.nombre_mod,
                                                                    con.id_con,
                                                                    con.nombre_con,
                                                                    pro.id_pro,
                                                                    pro.nombre_pro,
                                                                    pro.apellido_paterno_pro,
                                                                    pro.apellido_materno_pro,
                                                                    -- pie.valor_pie_ven,
                                                                    estado_venta.nombre_est_ven,
                                                                    ban.nombre_ban
                                                                FROM 
                                                                    venta_venta AS ven
                                                                    INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
                                                                    -- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
                                                                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                    INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                    INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                                    LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
                                                                WHERE 
                                                                    ven.id_ven > 0
                                                                    ".$filtro_consulta."
                                                                ORDER BY 
                                                                    ven.fecha_ven
                                                                "; 
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                	$contador++;
                                                                    if ($fila['fecha_ven'] == '0000-00-00') {
                                                                        $fecha_venta = "";
                                                                    }
                                                                    else{
                                                                        $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                                    }
                                                                    $acumulado_monto = $acumulado_monto + $fila['monto_ven'];


                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            IFNULL(SUM(pag.monto_pag / uf.valor_uf),0) AS pagado
                                                                        FROM
                                                                            pago_pago AS pag
                                                                            INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(pag.fecha_real_pag)
                                                                        WHERE
                                                                            pag.id_ven = ?
                                                                        ";
                                                                    $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                    $fila_pago = $conexion->extraer_registro_unico();
                                                                    $uf_pagado = $fila_pago["pagado"];
                                                                    

                                                                    //---- LIMPIAR VARIABLE
                                                                    $fecha_solicitud_pago_credito = '';
                                                                    $fecha_pago_credito_real = '';
                                                                    $valor_carta_resguardo_contado = 0;
                                                                    $valor_carta_resguardo_credito = 0;
                                                                    $fecha_solicitud_alzamiento_credito = '';
                                                                    $fecha_solicitud_alzamiento_contado = '';
                                                                    $uf_alzamiento = 0;
                                                                    $valor_uf_alzamiento = 0;
                                                                    $equivalente_alzamiento = 0;
                                                                    $fecha_cargo_alzamiento_credito = '';
                                                                    $fecha_abono_alzamiento_credito = '';
                                                                    $fecha_cargo_alzamiento_contado = '';
                                                                    $fecha_abono_alzamiento_contado = '';
                                                                    $uf_fecha_pago_credito_real = 0;
                                                                    $alzado = 0;
                                                                    $pagado = 0;
                                                                    $valor_liquidado_peso = 0;
                                                                    $valor_liquidado_uf = 0;

                                                                    
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            *
                                                                        FROM
                                                                            venta_etapa_venta
                                                                        WHERE
                                                                            id_ven = ? AND
                                                                            id_eta IN(14,35,45,47)
                                                                        ORDER BY 
                                                                            id_eta
                                                                        DESC
                                                                        ";
                                                                    $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                    $cantidad_etapa = $conexion->total();
                                                                    $fila_consulta_etapa = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_etapa)){
                                                                        foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                            switch ($fila_etapa["id_eta"]) {
                                                                                case 14:
                                                                                    //------- ALZADO CONTADO
                                                                                    if($fila['id_for_pag'] == 2 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                        $alzado = 1;
                                                                                    }
                                                                                    break;
                                                                                case 35:
                                                                                    //------- ALZADO CREDITO
                                                                                    if($fila['id_for_pag'] == 1 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                        $alzado = 1;
                                                                                    }
                                                                                    break;
                                                                                case 45:
                                                                                    //------- FECHA CREDITO 
                                                                                    if($fila_etapa["id_est_eta_ven"] == 1){
                                                                                        $fecha_solicitud_pago_credito = $fila_etapa["fecha_hasta_eta_ven"];
                                                                                    }
                                                                                    
                                                                                    break;
                                                                                case 47:
                                                                                    //------- FECHA PAGO CRDITO REAL
                                                                                    if($fila_etapa["id_est_eta_ven"] == 1){
                                                                                        $fecha_pago_credito_real = $fila_etapa["fecha_hasta_eta_ven"];
                                                                                    }
                                                                                    if($fila['id_for_pag'] == 1 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                        $pagado = 1;
                                                                                    }
                                                                                    break;
                                                                            }
                                                                        }
                                                                    }

                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            *
                                                                        FROM
                                                                            venta_etapa_campo_venta
                                                                        WHERE
                                                                            id_ven = ? AND
                                                                            id_cam_eta IN(14,25,35,42,43,44,45,46,47,48,49,50)
                                                                        ORDER BY 
                                                                            id_eta
                                                                        DESC
                                                                        ";
                                                                    $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                    $cantidad_etapa = $conexion->total();
                                                                    $fila_consulta_etapa = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_etapa)){
                                                                        foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                            switch ($fila_etapa["id_cam_eta"]) {
                                                                                case 14:
                                                                                    //------- FECHA SOLICITUD

                                                                                    break;
                                                                                case 25:
                                                                                    //------- VALOR CARTA RESGUARDO CONTADO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $valor_carta_resguardo_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                    }
                                                                                    break;
                                                                                case 35:
                                                                                    //------- VALOR CARTA RESGUARDO CREDITO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $valor_carta_resguardo_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                    }
                                                                                    
                                                                                    break;
                                                                                case 42:
                                                                                    //------- FECHA SOLICITUD ALZAMIENTO CREDITO
                                                                                    
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_solicitud_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        $fecha_solicitud_alzamiento_credito = date("Y-m-d",strtotime($fecha_solicitud_alzamiento_credito));
                                                                                    }
                                                                                    
                                                                                    break;
                                                                                case 43:
                                                                                    //------- FECHA CARGO ALZAMIENTO CREDITO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_cargo_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        $fecha_cargo_alzamiento_credito = date("Y-m-d",strtotime($fecha_cargo_alzamiento_credito));
                                                                                    }
                                                                                    break;
                                                                                case 44:
                                                                                    //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_abono_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        $fecha_abono_alzamiento_credito = date("Y-m-d",strtotime($fecha_abono_alzamiento_credito));
                                                                                    }
                                                                                    break;
                                                                                case 45:
                                                                                    //------- FECHA SOLICITUD
                                                                                    break;
                                                                                case 46:
                                                                                    //------- FECHA SOLICITUD ALZAMIENTO CONTADO
                                                                                    
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_solicitud_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        $fecha_solicitud_alzamiento_contado = date("Y-m-d",strtotime($fecha_solicitud_alzamiento_contado));
                                                                                    }
                                                                                    
                                                                                    break;
                                                                                case 47:
                                                                                    //------- FECHA CARGO ALZAMIENTO CONTADO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_cargo_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        if ($fecha_cargo_alzamiento_contado<>'' && $fecha_cargo_alzamiento_contado <> null) {
                                                                                        	$fecha_cargo_alzamiento_contado = date("Y-m-d",strtotime($fecha_cargo_alzamiento_contado));
                                                                                        }
                                                                                        
                                                                                    }
                                                                                    break;
                                                                                case 48:
                                                                                    //------- FECHA ABONO ALZAMIENTO CONTADO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $fecha_abono_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                        $fecha_abono_alzamiento_contado = date("Y-m-d",strtotime($fecha_abono_alzamiento_contado));
                                                                                    }
                                                                                    break;
                                                                                case 49:
                                                                                    //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $valor_liquidado_peso = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                    }
                                                                                    break;
                                                                                case 50:
                                                                                    //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                    if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                        $valor_liquidado_uf = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                    }
                                                                                    break;
                                                                                
                                                                            }
                                                                            
                                                                        }
                                                                    }
                                                                    //----- CREDITO
                                                                    if($fila['id_for_pag'] == 1){
                                                                        if(!empty($fecha_solicitud_alzamiento_credito)){
                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    valor_uf
                                                                                FROM
                                                                                    uf_uf
                                                                                WHERE
                                                                                    fecha_uf = ?
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fecha_solicitud_alzamiento_credito));
                                                                            $cantidad_uf = $conexion->total();
                                                                            if($cantidad_uf > 0){
                                                                                $fila_uf = $conexion->extraer_registro_unico();
                                                                                $uf_alzamiento = $fila_uf["valor_uf"];
                                                                            }
                                                                        }
                                                                        $valor_uf_alzamiento = ($fila['monto_ven'] * 0.9) - $valor_carta_resguardo_credito;

                                                                        $equivalente_alzamiento = $valor_uf_alzamiento * $uf_alzamiento;

                                                                        if(!empty($fecha_solicitud_pago_credito)){
                                                                            $fecha_solicitud_pago_credito = date("Y-m-d",strtotime($fecha_solicitud_pago_credito));
                                                                        }

                                                                        if(!empty($fecha_pago_credito_real)){
                                                                            $fecha_pago_credito_real = date("Y-m-d",strtotime($fecha_pago_credito_real));

                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    valor_uf
                                                                                FROM
                                                                                    uf_uf
                                                                                WHERE
                                                                                    fecha_uf = ?
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fecha_pago_credito_real));
                                                                            $cantidad_uf = $conexion->total();
                                                                            if($cantidad_uf > 0){
                                                                                $fila_uf = $conexion->extraer_registro_unico();
                                                                                $uf_fecha_pago_credito_real = $fila_uf["valor_uf"];
                                                                            }
                                                                        }

                                                                        $total_monto_ven_credito = $total_monto_ven_credito + $fila['monto_ven'];
                                                                        $total_carta_resguardo_credito = $total_carta_resguardo_credito + $valor_carta_resguardo_credito;
                                                                        $total_uf_alzamiento_credito = $total_uf_alzamiento_credito + $valor_uf_alzamiento;
																		$total_equivalente_alzamiento_credito = $total_equivalente_alzamiento_credito + $equivalente_alzamiento;

																		$total_valor_liquidado_peso_credito = $total_valor_liquidado_peso_credito + $valor_liquidado_peso;
																		$total_valor_liquidado_uf_credito = $total_valor_liquidado_uf_credito + $valor_liquidado_uf;

																		$total_uf_pagado_credito = $total_uf_pagado_credito + $uf_pagado;

																		// echo $total_uf_pagado_credito."-";
                                                                        ?>
                                                                        <tr>
                                                                        	<td><?php echo $contador; ?></td>
                                                                            <td style="text-align: left;"><?php echo ucwords(strtolower(utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']))); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <td><?php echo number_format($fila['monto_ven'], 2, ',', '.');?></td>

                                                                            <td><?php 
                                                                            	if ($fecha_cargo_alzamiento_credito<>'') {
                                                                            		echo date("d-m-Y",strtotime($fecha_cargo_alzamiento_credito));
                                                                            	} else {
                                                                            		echo utf8_encode($fecha_cargo_alzamiento_credito);
                                                                            	}
                                                                            	?></td>
                                                                            <td><?php 
                                                                            	if ($fecha_abono_alzamiento_credito<>'') {
                                                                            		echo date("d-m-Y",strtotime($fecha_abono_alzamiento_credito));
                                                                            	} else {
                                                                            		echo utf8_encode($fecha_abono_alzamiento_credito);
                                                                            	}
                                                                            	?></td>
                                                                            <td><?php 
                                                                            	if ($fecha_pago_credito_real<>'') {
                                                                            		echo date("d-m-Y",strtotime($fecha_pago_credito_real));
                                                                            	} else {
                                                                            		echo utf8_encode($fecha_pago_credito_real);
                                                                            	}
                                                                            	?></td>
                                                                            <td>
                                                                            <?php
                                                                            $dias = (strtotime($fecha_solicitud_pago_credito)-strtotime($fecha_pago_credito_real))/86400;
                                                                            $dias = abs($dias); 
                                                                            $dias = floor($dias);
                                                                            $total_diferencia_dias = $total_diferencia_dias + $dias;
                                                                            echo $dias;
                                                                             ?>
                                                                            </td>
                                                                            <?php 
																			$dif_monto = $valor_carta_resguardo_credito - $valor_liquidado_uf;
																			$total_dif_monto_credito = $total_dif_monto_credito + $dif_monto;
                                                                            $total_pagado_resumen = $valor_liquidado_uf + $uf_pagado;
                                                                            $saldo_uf = $fila['monto_ven'] - $total_pagado_resumen;

                                                                            $total_pagado_resumen_credito = $total_pagado_resumen_credito + $total_pagado_resumen;
                                                                            $total_saldo_uf_credito = $total_saldo_uf_credito + $saldo_uf;
                                                                            ?>
                                                                            <td><?php echo number_format($alzado, 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($pagado, 0, ',', '.');?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    else{
                                                                        //------ CONTADO
                                                                        if(!empty($fecha_solicitud_alzamiento_contado)){
                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    valor_uf
                                                                                FROM
                                                                                    uf_uf
                                                                                WHERE
                                                                                    fecha_uf = ?
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fecha_solicitud_alzamiento_contado));
                                                                            $cantidad_uf = $conexion->total();
                                                                            if($cantidad_uf > 0){
                                                                                $fila_uf = $conexion->extraer_registro_unico();
                                                                                $uf_alzamiento = $fila_uf["valor_uf"];
                                                                            }
                                                                        }
                                                                        $valor_uf_alzamiento = ($fila['monto_ven'] * 0.9) - $valor_carta_resguardo_contado;

                                                                        $equivalente_alzamiento = $valor_uf_alzamiento * $uf_alzamiento;

                                                                        $total_monto_ven_contado = $total_monto_ven_contado + $fila['monto_ven'];
                                                                        $total_carta_resguardo_contado = $total_carta_resguardo_contado + $valor_carta_resguardo_contado;
                                                                        $total_uf_alzamiento_contado = $total_uf_alzamiento_contado + $valor_uf_alzamiento;
																		$total_equivalente_alzamiento_contado = $total_equivalente_alzamiento_contado + $equivalente_alzamiento;
																		$total_uf_pagado_contado = $total_uf_pagado_contado + $uf_pagado;
                                                                        ?>
                                                                        <tr>
                                                                        	<td><?php echo $contador; ?></td>
                                                                            <td style="text-align: left;"><?php echo ucwords(strtolower(utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']))); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <td><?php echo number_format($fila['monto_ven'], 2, ',', '.');?></td>

                                                                            <td>
                                                                            	<?php 
                                                                            		if ($fecha_cargo_alzamiento_contado<>'' && $fecha_cargo_alzamiento_contado <> null) {
                                                                            			echo date("d-m-Y",strtotime($fecha_cargo_alzamiento_contado)); 
                                                                            		}
                                                                            		?></td>
                                                                            <td><?php echo utf8_encode($fecha_abono_alzamiento_contado); ?></td> 
                                                                            <td></td>
                                                                            <td></td>
                                                                            <?php 
                                                                            $total_pagado_resumen = $valor_liquidado_uf + $uf_pagado;
                                                                            $saldo_uf = $fila['monto_ven'] - $total_pagado_resumen;
                                                                            $total_pagado_resumen_contado = $total_pagado_resumen_contado + $total_pagado_resumen;
                                                                            $total_saldo_uf_contado = $total_saldo_uf_contado + $saldo_uf;
                                                                            ?>
                                                                            <td><?php echo number_format($alzado, 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($pagado, 0, ',', '.');?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    $total_alzado = $total_alzado + $alzado;
                                                                    $total_pagado = $total_pagado + $pagado;
                                                                }
                                                            }

                                                            $total_monto_inmob = $total_monto_ven_contado + $total_monto_ven_credito;
                                                            $total_carta_resguardo = $total_carta_resguardo_contado + $total_carta_resguardo_credito;
                                                            $total_uf_alzamiento = $total_uf_alzamiento_credito + $total_uf_alzamiento_contado;
                                                            $total_equivalente_alzamiento = $total_equivalente_alzamiento_credito + $total_equivalente_alzamiento_contado;
                                                            $total_pagado_resumen_completo = $total_pagado_resumen_credito + $total_pagado_resumen_contado;
                                                            $total_uf_pagado_completo = $total_uf_pagado_contado + $total_uf_pagado_credito;
                                                            $total_saldo_uf = $total_saldo_uf_credito + $total_saldo_uf_contado;
															

                                                            ?> 
															<!-- totales -->
															<tr class="bg-info font-weight-bold">
																<td colspan="3">Totales</td>
																<td><?php echo number_format($total_monto_inmob, 2, ',', '.');?></td>
																<td></td>
																<td></td>
																<td></td>
																<td><?php echo $total_diferencia_dias; ?></td>
																<td><?php echo $total_alzado; ?></td>
																<td><?php echo $total_pagado; ?></td>
															</tr>
														</table>	
														
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>                        
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
        </div>
    </section>

      <!-- Main content -->
   	
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
  <!-- /.content-wrapper -->
<!-- .wrapper cierra en el footer -->
</body>
</html>
