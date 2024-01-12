<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");

// $nombre = 'ICP_SPA_LISTADO_CIERRE_NEGOCIO_'.date('d-m-YH:m:s').'.xls';
header("Pragma: public");
header("Expires: 0");
$nombre = 'ICP_LISTADO_CIERRE_NEGOCIO'.date('dmYHms').'.xls';
header("Content-Type: application/xls"); 
header("Content-Disposition: attachment; filename=$nombre");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
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
function conversion($val){
	$bb = explode(",",$val)[0];
	$bbresto = !empty(explode(",",$val)[1]) ? explode(",",$val)[1] : '01';
	$newbb = $bb.'.'.$bbresto;
	$bbb = floatval($newbb);	
	return $bbb;
}
function diferencia($a,$b){
	 return (string)abs(round((doubleval($a) * 0.9) - conversion($b),2));
}
function calculoSaldoUf($total,$pieCancelado,$saldoPie,$cartaResguardo){
	$carta = conversion($cartaResguardo);
	$resp = abs(round($total - round($pieCancelado,2) - round($saldoPie,2) - $carta,2));	
	return (string)$resp;
}
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
									<?php                                                       
										$conexion->consulta("SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con");
										$fila_consulta_condominio_original = $conexion->extraer_registro();																									
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
											$filtro_consulta .= " AND viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];                                                        
										}else{

										}
										?>                                                   
                                        <div class="col-md-12">
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body" style="overflow-x: hidden;">
														<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Condominio</th>
                                                                    <!-- <th>Modelo</th> -->
                                                                    <th align="center" height="50" bgcolor="#6fd513">Depto</th>
                                                                    <!-- <th>Orientación</th> -->
                                                                    <!-- <th>Vendedor</th> -->
                                                                    <!-- <th>Categoría</th> -->
                                                                    <th align="center" height="50" bgcolor="#6fd513">Cliente</th>
                                                                    <!-- <th>Fecha Venta</th> -->
                                                                    <!-- <th>Est. Adic.</th> -->
                                                                    <!-- <th>Forma Pago</th> -->
                                                                    <!-- <th>Banco/Tipo Pago</th> -->
                                                                    <th align="center" height="50" bgcolor="#6fd513">Valor Depto.</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Pie Cancelado</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Abono Inmobiliaria</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Pie por Cobrar</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Saldo Pie</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Crédito Hipotecario</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Total</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Valor Final Inmob.</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Carta de resguardo.</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Banco.</th>
                                                                    <th align="center" height="50" bgcolor="#6fd513">Diferencia UF Cobertura Banco.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Saldo UF.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Monto Liquidación UF.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Fecha Liquidación.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Fecha Solicitud Alzamiento.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Fecha Solicitud Carta de Resguardo.</th>                                                              
                                                                    <th align="center" height="50" bgcolor="#6fd513">Fecha Emisión Carta de Resguardo.</th>                                                              
                                                                    <!-- <th>Premio</th> -->                                                                                                                                                                                                        
                                                                    <!-- <th>Estado Venta</th> -->
                                                                    <!-- <th>Motivo Desistimiento</th> -->
                                                                    <!-- <th>Etapa Actual</th> -->
                                                                    <!-- <th>Estado Etapa</th> -->
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
																$nombre_banco = "";
                                                                $acumulado_monto = 0;                                                                
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
																		ven.id_ven,
																		ven.fecha_ven,
																		ven.monto_ven,
																		viv.id_viv,
																		viv.nombre_viv,
																		ori_viv.nombre_ori_viv,
																		mode.id_mod,
																		mode.nombre_mod,
																		con.id_con,
																		con.nombre_con,
																		tor.id_tor,
																		tor.nombre_tor,
																		vend.id_vend,
																		vend.nombre_vend,
																		vend.apellido_paterno_vend,
																		vend.apellido_materno_vend,
																		pro.id_pro,
																		pro.nombre_pro,
																		pro.apellido_paterno_pro,
																		pro.apellido_materno_pro,
																		esta.id_esta,
																		esta.nombre_esta,
																		for_pag.id_for_pag,
																		for_pag.nombre_for_pag,
																		-- pie.valor_pie_ven,
																		cat.nombre_cat_vend,
																		ven.descuento_ven,
																		pre.nombre_pre,
																		ven.monto_credito_ven,
																		ven.monto_credito_real_ven,
																		estado_venta.nombre_est_ven,
																		ven.id_ban,
																		ven.id_tip_pag,
																		ban.nombre_ban,
																		tip_pag.nombre_tip_pag,
																		ven.id_est_ven,
																		viv.id_est_viv,
																		viv.valor_viv,
																		viv.id_pis,
																		ven.monto_vivienda_ven,
																		ven.id_pie_abo_ven,
																		ven.monto_reserva_ven,
																		ven.pie_cancelado_ven,
																		ven.pie_cobrar_ven,
																		ven.monto_estacionamiento_ven,
                        												ven.monto_bodega_ven															
																	FROM 
																		vivienda_vivienda AS viv
																		LEFT JOIN venta_venta AS ven ON viv.id_viv = ven.id_viv
																		LEFT JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
																		LEFT JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
																		-- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
																		INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
																		INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
																		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
																		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
																		LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
																		LEFT JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
																		LEFT JOIN venta_estacionamiento_venta AS est_ven ON est_ven.id_ven = ven.id_ven
																		LEFT JOIN estacionamiento_estacionamiento AS esta ON esta.id_esta = est_ven.id_esta
																		LEFT JOIN vendedor_categoria_vendedor AS cat ON cat.id_cat_vend = ven.factor_categoria_ven
																		LEFT JOIN premio_premio AS pre ON pre.id_pre = ven.id_pre
																		LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
																		LEFT JOIN pago_tipo_pago AS tip_pag ON tip_pag.id_tip_pag = ven.id_tip_pag
																	WHERE 
																		viv.id_viv > 0
																		".$filtro_consulta."
																	ORDER BY 
																		viv.id_tor, viv.id_pis, viv.nombre_viv ASC";                                                                 
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
																		$nombre_banco = $fila['nombre_ban'];
                                                                    	$estado_viv = $fila['id_est_viv'];
                                                                    	$fecha_venta = '';
                                                                    	if ($estado_viv==2) {
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
	                                                                                eta_ven.fecha_desde_eta_ven,
	                                                                                eta.duracion_eta,
	                                                                                eta.nombre_eta
	                                                                            FROM
	                                                                                venta_venta AS ven
	                                                                                INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
	                                                                                INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
	                                                                            WHERE
	                                                                                ven.id_ven = ?
	                                                                            ORDER BY 
	                                                                                eta_ven.id_eta_ven
	                                                                            DESC
	                                                                            LIMIT 0,1
	                                                                            ";
                                                                        	$conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                        	$cantidad_etapa = $conexion->total();
	                                                                        if($cantidad_etapa > 0){
	                                                                            $fila_venta = $conexion->extraer_registro_unico();
	                                                                            $hoy = date("Y-m-d");
	                                                                            $fecha_inicio = $fila_venta["fecha_desde_eta_ven"];
	                                                                            $duracion = $fila_venta["duracion_eta"];
	                                                                            $etapa_nombre = $fila_venta["nombre_eta"];
	                                                                            $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
	                                                                            $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
	                                                                            if($fecha_final > $hoy){
	                                                                                $etapa_estado = "Atrasada";
	                                                                                $etapa = 1;
	                                                                            }
	                                                                            else{
	                                                                                $etapa_estado = "En fecha";
	                                                                                $etapa = 2;
	                                                                            }
	                                                                        }
	                                                                        else{
	                                                                            $etapa_nombre = "--";
	                                                                            $etapa_estado = "No iniciada";
	                                                                            $etapa = 3;
	                                                                        }
	                                                                    } else {
	                                                                    	$etapa_nombre = "";
																			$etapa_estado = "";
	                                                                    }	                                                                    
                                                                        if ($fila['id_est_ven']!=3){
                                                                        ?>
                                                                        <tr>
																			<!-- Nombre del condominio  -->
                                                                            <td align="center"><?php echo utf8_encode($fila['nombre_con']); ?></td>
																			<!-- Nombre del departamento -->
                                                                            <td align="center"><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <!-- Nombre del cliente -->
                                                                            <td align="center"><?php echo strtoupper(utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro'])); ?></td>
                                                                                                                                                      
                                                                            <?php 
                                                                                $id_pie_abo_ven = $fila["id_pie_abo_ven"];																			
                                                                            	$monto_ven = $fila['monto_ven'];
                                                                            	$total_general = $fila["monto_vivienda_ven"] - $fila["descuento_ven"];
																				if ($id_pie_abo_ven==1) {
																					$monto_ven = $fila["monto_vivienda_ven"];
																				} else {
																					$monto_ven = $total_general;
																				}																			
																			?>
																			<!-- Valor del departamento -->
                                                                            <td align="center">
                                                                            	<?php 
                                                                            	if ($estado_viv==2) {
                                                                            		echo number_format($monto_ven, 2, ',', '.');
                                                                            	} else {
                                                                            		echo number_format($fila['valor_viv'], 2, ',', '.');
                                                                            	}
                                                                            	?>
                                                                            </td>
                                                                            <?php 
																			$total_abono = 0;
																			$total_uf = 0;
																			$pie_pagado_efectivo = 0;
																			$pie_cancelado = 0;
																			$pie_por_cobrar = 0;
																			$pie_pagado_porcobrar = 0;
																			
														                    $consulta = 
														                        "
														                        SELECT 
														                            pag.id_pag,
														                            cat_pag.nombre_cat_pag,
														                            -- ban.nombre_ban,
														                            for_pag.nombre_for_pag,
														                            pag.fecha_pag,
														                            pag.fecha_real_pag,
														                            pag.numero_documento_pag,
														                            pag.monto_pag,
														                            est_pag.nombre_est_pag,
														                            pag.id_est_pag,
														                            pag.id_ven,
														                            ven.fecha_ven,
														                            pag.id_for_pag
														                        FROM
														                            pago_pago AS pag 
														                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
														                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
														                            -- INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
														                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
														                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
														                        WHERE 
														                            pag.id_ven = ?
														                        ";
														                    $contador = 1;
														                    $conexion->consulta_form($consulta,array($fila["id_ven"]));
														                    $fila_consulta = $conexion->extraer_registro();
														                    if(is_array($fila_consulta)){
														                        foreach ($fila_consulta as $filapago) {
																					$valor_uf_efectivo = 0;
																					$pie_agado_efectivo = 0;														                        	
														                            if ($filapago["fecha_real_pag"]=="0000-00-00" || $filapago["fecha_real_pag"]==null) { //abonos no cancelados aún
														                                $fecha_real_mostrar = "";

														                                $consulta = 
														    							"
																						    SELECT
																						        valor_uf
																						    FROM
																						        uf_uf
																						    WHERE
																						        fecha_uf = '".date("Y-m-d",strtotime($filapago["fecha_ven"]))."'
																						    ";
																						$conexion->consulta($consulta);
																						$cantidaduf = $conexion->total();
																						if($cantidaduf > 0){
															                    			$filauf = $conexion->extraer_registro_unico();
																							$valor_uf = $filauf["valor_uf"];
																							if ($filapago["id_for_pag"]==6) { // si es pago contra escritura UF
																								$monto_pag = $filapago["monto_pag"] * $valor_uf;
																								$abono_uf = $filapago["monto_pag"];
																								// $abono_uf = 0;
																								$monto_pag = 0;
																							} else {
																								$monto_pag = $filapago["monto_pag"];
																								$abono_uf = $filapago["monto_pag"] / $valor_uf;
																								$abono_uf = 0;
																							}
																							
																						} else {
																							$valor_uf = 0;
																						}

																						$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;
																						

														                            }else{
														                                $fecha_real_mostrar = date("d/m/Y",strtotime($filapago["fecha_real_pag"]));
																						$conexion->consulta_form("SELECT valor_uf FROM uf_uf WHERE fecha_uf = ?",array($filapago["fecha_real_pag"]));
																						$cantidad_uf = $conexion->total();
																						if($cantidad_uf > 0){
																							$filauf = $conexion->extraer_registro_unico();
																							$valor_uf_efectivo = $filauf['valor_uf'];
																							if ($filapago["id_for_pag"]==6) { // si es pago contra escritura UF
																								$monto_pag = $filapago["monto_pag"] * $valor_uf;
																								$abono_uf = $filapago["monto_pag"] * $valor_uf_efectivo;
																								// para que no sume
																							} else {
																								$monto_pag = $filapago["monto_pag"];
																								$abono_uf = $filapago["monto_pag"] / $valor_uf_efectivo;
																							}
																						} else {
																							$valor_uf_efectivo = 0;
																						} 

																						$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;  
																						
														                            }
														                            $total_abono = $total_abono + $monto_pag;
																					$total_uf = $total_uf + $abono_uf;
														                            $contador++;
														                        }
														                    }
                                                                             ?>
																			 <!-- Pie Cancelado -->
                                                                            <td align="center"><?php echo number_format($pie_pagado_efectivo, 2, ',', '.');?></td>
																			<!-- Abono Inmobiliario -->
                                                                            <td align="center"><?php 
                                                                            	if ($estado_viv==2) {
                                                                            		if ($id_pie_abo_ven==1) {
                                                                            			echo number_format($fila['descuento_ven'], 2, ',', '.');
                                                                            		}
                                                                            	}
                                                                            	?>
                                                                            </td>
																			<?php 
																			$pie_cancelado = $fila["pie_cancelado_ven"] + $fila["monto_reserva_ven"];
																			$pie_por_cobrar = $pie_cancelado + $fila["pie_cobrar_ven"] - $total_uf;
																			 ?>
																			 <!-- Pie por cobrar -->
                                                                            <td align="center"><?php echo number_format($pie_pagado_porcobrar, 2, ',', '.');?></td>

                                                                            <?php 
																			$saldo_total = 0;
																			if ($fila["monto_credito_real_ven"]<>0) {
																				
																				$credito_hipo = $fila["monto_credito_real_ven"];
																				$credito_suma = $fila["monto_credito_real_ven"];
																			} else {
																				
																				$credito_hipo = $fila["monto_credito_ven"];
																				$credito_suma = $fila["monto_credito_ven"];
																			}
															

																			$total = $pie_cancelado + $fila["pie_cobrar_ven"] + $credito_suma;
																			$saldo_pie = $total - ($credito_suma + $pie_pagado_porcobrar + $pie_pagado_efectivo);
																			if ($fila['id_est_ven']<>3) {
																				if ($fila['id_for_pag']==1) {
																				?>																				    
																					<td align="center"><?php echo number_format($saldo_pie, 2, ',', '.');?></td>
																					<td align="center"><?php echo number_format($credito_hipo, 2, ',', '.');?></td>
																				<?php
																				} else {
																					$saldo_total = $saldo_pie + $credito_suma;
																					?>
																					<td align="center" class="borde centrado"><?php echo number_format($saldo_total, 2, ',', '.'); ?></td>
																					<td></td>
																				<?php
																				}
																			}

																			?>

																			<td><?php echo number_format($total, 2, ',', '.');?></td>
																			
																			<?php
																			$montoLiqu = 0;
																			$fechaLiqu = "";
																			$fechaAlzamiento="";
																			$solicitudCartaResguardo="";
																			$emisionCartaResguardo="";

																			// monto y fecha liquidos
																			if(isset($fila["id_ven"])){
                                                                              $conexion->consulta("SELECT * FROM venta_liquidado_venta WHERE id_ven =".$fila["id_ven"]);
																			  $fila_consulta = $conexion->extraer_registro();
																			  if(is_array($fila_consulta)){																				
																				$montoLiqu = $fila_consulta[0]["monto_liq_uf_ven"];
																				$fechaLiqu = $fila_consulta[0]["fecha_liq_ven"];
																			  }
																			  
																			//  fecha de alzamiento 
																			  $conexion->consulta("SELECT * FROM venta_etapa_venta WHERE id_ven = ".$fila["id_ven"]." AND id_eta = 35");
																			  $fecha_alzamiento = $conexion->extraer_registro();
																			  if(is_array($fecha_alzamiento)) $fechaAlzamiento = explode(' ',$fecha_alzamiento[0]['fecha_desde_eta_ven'])[0];
																	
																			  //  fecha de solicitud CARTA Resguardo (ECR11)
																			  $conexion->consulta("SELECT * FROM venta_etapa_venta WHERE id_ven = ".$fila["id_ven"]." AND id_eta = 32");
																			  $solicitud_fecha = $conexion->extraer_registro();
																			  if(is_array($solicitud_fecha)) $solicitudCartaResguardo = explode(' ',$solicitud_fecha[0]['fecha_desde_eta_ven'])[0];
																			 
																			  //  fecha de Emisión CARTA Resguardo (ECR12)
																			  $conexion->consulta("SELECT * FROM venta_etapa_venta WHERE id_ven = ".$fila["id_ven"]." AND id_eta = 33");
																			  $emision_carta = $conexion->extraer_registro();
																			  if(is_array($emision_carta)) $emisionCartaResguardo = explode(' ',$emision_carta[0]['fecha_desde_eta_ven'])[0];
																		
																			}
																			$diferenciaUf = 0;
																			$total = 0.0;
																			
																			// Valor final inmob.																			
																			if ($fila['id_est_ven']<>3) {
																			 	$total_general_total = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];
																				$total = (double) $total_general_total;
																				?>
																				<td align="center"><?php echo number_format($total_general_total, 2, ',', '.');?></td>
																				<?php
																			} else {
																				?>
																				<td>0,00</td>
																				<?php
																			}																			          
																																						
																			?>  
																			<!-- carta de resguardo -->
																			<td align="center"><?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>    
																			<!-- Banco -->
																			<td align="center"><?php echo utf8_encode($nombre_banco); ?></td>                                                                    																			
																			<?php $val = ($fila["monto_credito_real_ven"]<>0) ? $fila["monto_credito_real_ven"] : $fila["monto_credito_ven"];?>
																			<!-- diferencia uf cobertura banco -->
																			<td align="center"><?php echo diferencia($total,$val); ?></td>
																			<!-- Saldo UF    total - pie cancelado - saldo pie - carta de resguardo -->																																						
																			<td align="center"><?php echo (calculoSaldoUf($total,$pie_pagado_efectivo,$saldo_pie,$val) == '0.01')? '0':calculoSaldoUf($total,$pie_pagado_efectivo,$saldo_pie,$val);?></td>
																			<!-- Monto Liquidación -->
																			<td align="center"><?php echo ($montoLiqu)?$montoLiqu:'';?></td>
																			<!-- Fecha Liquidación -->
																			<td align="center"><?php echo ($fechaLiqu)?$fechaLiqu:'';?></td>
																			<!-- Fecha Alzamiento -->
																			<td align="center"><?php echo ($fechaAlzamiento)?$fechaAlzamiento:'';?></td>
																			<!-- Fecha solicitud carta de resguardo -->
																			<td align="center"><?php echo ($solicitudCartaResguardo)?$solicitudCartaResguardo:'';?></td>
																			<!-- Fecha emisión carta de resguardo -->
																			<td align="center"><?php echo ($emisionCartaResguardo)?$emisionCartaResguardo:'';?></td>
                                                                        </tr>
                                                                        <?php
																		}
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <!-- <tfoot>
                                                                <tr>
                                                                    <td colspan="20"></td>
                                                                    
                                                                    <td>$<?php //echo number_format($acumulado_monto, 2, ',', '.'); ?></td>
                                                                </tr> 
                                                            </tfoot> -->                                                                                                                        
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