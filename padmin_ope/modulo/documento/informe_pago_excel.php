<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

$id = $_GET["id"];

$nombre = 'cierre_negocio_'.$id.'-'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Pago</title>
    <meta charset="utf-8">
    <style type="text/css">
    	*{
    		margin:0;
    		padding: 0;
    	}
    	html,body{
    		padding: 0px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 12px;
    	}
    	@media print {
		  /* Contenido del fichero print.css */
			  html,body{
	    		padding: 0px;
	    		margin: 0;
	    		font-family: Arial;
	    		font-size: 12px;
	    	}
		}
    	.bordef{
    		border:2px solid #000000;
    	}
        .sin-borde{
			width: 100%;
			margin-left: auto;
			margin-right: auto;
        }
		.liquida{
			width: 100%;
			margin-left: auto;
			margin-right: auto;
			border-collapse: collapse;
		}
		.liquida td{
		}
		.color{
			background-color: #CCCCCC;
			/*border:1px solid #000000;*/
		}
		.detalle td{
			border:1px solid #000000;
		}
		.borde{
			border:1px solid #000000;
		}
		.centrado{
			text-align: center;
		}
		.derecha{
			text-align: right;
		}

		.tabla{
			width: 100%;
			border-collapse: collapse;
		}

		.borde td{
			border: 1px solid;
		}
    </style>
</head>
<body>
<?php
	include _INCLUDE."class/conexion.php";
	$conexion = new conexion();

	$consulta = 
		"
		SELECT 
			con.id_con, 
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			con.nombre_con, 
			viv.nombre_viv,
			viv.prorrateo_viv,
			ven.id_for_pag
		FROM 
			venta_venta AS ven
			INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
			INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		WHERE 
			ven.id_ven = ?
		";
	$conexion->consulta_form($consulta,array($id));
	$fila = $conexion->extraer_registro_unico();
	$id_con = $fila["id_con"];
	$id_for_pag = $fila["id_for_pag"];
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$nombre_con = $fila["nombre_con"];
	$nombre_viv = $fila["nombre_viv"];
	$prorrateo_viv = $fila["prorrateo_viv"];

	$consulta = 
        "
        SELECT
            valor_par
        FROM
            parametro_parametro
        WHERE
            valor2_par = ? AND
            id_con = ?
        ";
    $conexion->consulta_form($consulta,array(14,$id_con));
    $fila = $conexion->extraer_registro_unico();
    $porcentaje_prorrateo = utf8_encode($fila['valor_par']);
    $total_prorrateo = ($prorrateo_viv * $porcentaje_prorrateo) / 100;
    $total_prorrateo = $total_prorrateo*2;
    //$total_vivienda = $prorrateo_viv - $total_prorrateo;

	// fecha pago puesta marcha
	// $consultafecha = "SELECT 
	// 		fecha_hasta_eta_ven
	// 	FROM
	// 		venta_etapa_venta
	// 	WHERE
	// 		id_ven = ".$id." AND
	// 		id_est_eta_ven = 1";
	// if ($id_for_pag==1) {
	// 	$consultafecha .= " AND id_eta = 30";
	// } else {
	// 	$consultafecha .= " AND id_eta = 12";
	// }
 //    $conexion->consulta($consultafecha);
 //    $filafecha = $conexion->extraer_registro_unico();
 //    $fecha_cierre = $filafecha['fecha_hasta_eta_ven'];
 //    if ($fecha_cierre<>'') {
 //    	$fecha_cierre = date("d-m-Y",strtotime($fecha_cierre));
 //    } else {
 //    	$fecha_cierre = "--";
 //    }

    // 
    $consultafecha = "SELECT 
			fecha_pago_cliente_fondo_expotacion
		FROM
			venta_campo_venta
		WHERE
			id_ven = ".$id."";
    $conexion->consulta($consultafecha);
    $filafecha = $conexion->extraer_registro_unico();
    $fecha_cierre = $filafecha['fecha_pago_cliente_fondo_expotacion'];
    if ($fecha_cierre<>'') {
    	$fecha_cierre = date("d-m-Y",strtotime($fecha_cierre));
    } else {
    	$fecha_cierre = "--";
    }

	// Fondo GOP
	if ($id_for_pag==2) {
		$consultahay = 
		    "
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = ".$n_etaco_fondos_gastos." AND 
		        id_cam_eta = ".$n_cam_etaco_valor_gop."
		    ";
		$conexion->consulta_form($consultahay,array($id));
		$insert = $conexion->total();
		if ($insert>0) {
			$fila = $conexion->extraer_registro_unico();
			$monto_gas_ven = utf8_encode($fila['valor_campo_eta_cam_ven']);
		}

		$consultahay = 
		    "
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = ".$n_etaco_fondos_gastos." AND 
		        id_cam_eta = ".$n_cam_etaco_fecha_gop."
		    ";
		$conexion->consulta_form($consultahay,array($id));
		$insert = $conexion->total();
		if ($insert>0) {
			$fila = $conexion->extraer_registro_unico();
			$fecha_gas_ven = utf8_encode($fila['valor_campo_eta_cam_ven']);
			$fecha_gas = date("d-m-Y",strtotime($fecha_gas_ven));
		}
	}
	
	if ($id_con==1) {
    	$logo = "logo-empresa.jpg";
    	$nombre_empresa = "Inmobiliaria Cordillera SPA";
    } else {
    	$logo = "logo-icp.jpg";
    	$nombre_empresa = "Inmobiliaria Costanera Pacífico";
    }

	?>
	<table class="sin-borde">
	    <tr>
			<td style="width: 20%; text-align: left">
				<?php 
				if ($_SESSION["sesion_perfil_panel"]<>6) {
				 ?>
					<img src="<?php echo _ASSETS."img/".$logo."";?>">
				<?php 
				}
				 ?>
			</td>
			<td colspan="6" style="text-align: center;"><h2><?php echo $nombre_empresa; ?></h2></td>
			<td style="width: 20%;"></td>
	    </tr>
	</table>

	<table class="liquida">
		<tr>
			<td colspan="8" style="text-align: center; border: 1px solid">Arqueo Contable</td>
		</tr>
		<tr>
			<td colspan="8"></td>
		</tr>
	</table>

	<table class="liquida">
		<tr>
			<!-- detalle -->
			<td style="vertical-align: top">
				<table class="tabla">
					<tr class="borde">
						<td class="color">Cliente</td>
						<td colspan="2"><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></td>
						<td class="color">Dpto.</td>
						<td><?php echo utf8_encode($nombre_viv);?></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
					<tr class="color borde">
						<td>Abono $</td>
						<td>Abono UF</td>
						<td>Fecha Cobro</td>
						<td>Estado de Cobro</td>
						<td>Valor UF pago efectivo</td>
					</tr>
					<?php
					$total_abono = 0;
					$total_uf = 0;
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
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
							$valor_uf_efectivo = 0;
							$pie_agado_efectivo = 0;
                        	
                            if ($fila["fecha_real_pag"]=="0000-00-00" || $fila["fecha_real_pag"]==null) { //abonos no cancelados aún
                                $fecha_real_mostrar = "";

                                $consulta = 
    							"
								    SELECT
								        valor_uf
								    FROM
								        uf_uf
								    WHERE
								        fecha_uf = '".date("Y-m-d",strtotime($fila["fecha_ven"]))."'
								    ";
								$conexion->consulta($consulta);
								$cantidaduf = $conexion->total();
								if($cantidaduf > 0){
	                    			$filauf = $conexion->extraer_registro_unico();
									$valor_uf = $filauf["valor_uf"];
									if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
										$monto_pag = $fila["monto_pag"] * $valor_uf;
										$abono_uf = $fila["monto_pag"];
										// $abono_uf = 0;
										$monto_pag = 0;
									} else {
										$monto_pag = $fila["monto_pag"];
										$abono_uf = $fila["monto_pag"] / $valor_uf;
										$abono_uf = 0;
									}
									
								} else {
									$valor_uf = 0;
								}

								$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;

                            }
                            else{
                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila["fecha_real_pag"]));
                                
                                $consulta = 
    							"
								    SELECT
								        valor_uf
								    FROM
								        uf_uf
								    WHERE
								        fecha_uf = ?
								    ";
								$conexion->consulta_form($consulta,array($fila["fecha_real_pag"]));
								$cantidad_uf = $conexion->total();
								if($cantidad_uf > 0){
									$filauf = $conexion->extraer_registro_unico();
									$valor_uf_efectivo = $filauf['valor_uf'];
									if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
										$monto_pag = $fila["monto_pag"] * $valor_uf;
										$abono_uf = $fila["monto_pag"] * $valor_uf_efectivo;
										// para que no sume
									} else {
										$monto_pag = $fila["monto_pag"];
										$abono_uf = $fila["monto_pag"] / $valor_uf_efectivo;
									}
								} else {
									$valor_uf_efectivo = 0;
								} 

								$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;          
                            }
                            $total_abono = $total_abono + $monto_pag;
							$total_uf = $total_uf + $abono_uf;
                            ?>
                            <!-- lista de pagos -->
							<tr class="borde">
								<td>$ <?php echo number_format($monto_pag, 0, ',', '.');?></td>
								<td><?php echo number_format($abono_uf, 2, ',', '.');?></td>
								<td><?php echo $fecha_real_mostrar;?></td>
								<td><?php echo utf8_encode($fila["nombre_est_pag"]);?></td>
								<td><?php echo number_format($valor_uf_efectivo, 2, ',', '.');?></td>
							</tr>

                            <?php
                            $contador++;
                        }
                    }
                    ?>
					
					<!-- total -->
					<tr>
						<td style="border: 2px solid #000;">$ <?php echo number_format($total_abono, 0, ',', '.');?></td>
						<td style="border: 2px solid #000;"><?php echo number_format($total_uf, 2, ',', '.');?></td>
						<td colspan="3"></td>
					</tr>
					<tr>
						<td colspan="5"></td>
					</tr>
				</table>

				<table class="tabla">
					<tr class="borde">
						<td colspan="2" style="text-align: center">Fondo Puesta en Marcha</td>
						<td></td>
						<td colspan="2" style="text-align: center">Fondo gastos OOPP Contado</td>
					</tr>
					<tr class="borde">
						<td>Monto</td>
						<td>Fecha Pago</td>
						<td></td>
						<td>Monto</td>
						<td>Fecha Pago</td>
					</tr>
					<tr class="borde">
						<td>$ <?php echo number_format($total_prorrateo, 0, ',', '.');?></td>
						<td><?php echo $fecha_cierre; ?></td>
						<td></td>
						<td><?php echo number_format($monto_gas_ven, 0, ',', '.');?></td>
						<td><?php echo $fecha_gas; ?></td>
					</tr>
					<tr class="borde">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
			<td></td>
			<!-- arqueo -->
			<td style="text-align: center">
				<?php
				 $consulta = 
                    "
                    SELECT
                        ven.monto_vivienda_ven,
                        ven.pie_cancelado_ven,
                        ven.pie_cobrar_ven,
                        ven.monto_credito_ven,
                        ven.descuento_ven,
                        ven.monto_credito_real_ven,
                        ven.monto_reserva_ven,
                        ven.id_pie_abo_ven,
                        ven.monto_estacionamiento_ven,
                        ven.monto_bodega_ven
                    FROM 
                        venta_venta AS ven
                    WHERE 
                        ven.id_ven = ?
                    ";
                $contador = 1;
                $conexion->consulta_form($consulta,array($id));
                $fila = $conexion->extraer_registro_unico();
                $total_general = $fila["monto_vivienda_ven"] - $fila["descuento_ven"];
                $total_general_total = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];
                $pie_cancelado = $fila["pie_cancelado_ven"] + $fila["monto_reserva_ven"];
                $id_pie_abo_ven = $fila["id_pie_abo_ven"];
                ?>
				<table class="tabla">
					<tr>
						<td colspan="2" class="color" style="text-align: center; border: 1px solid">ARQUEO</td>
					</tr>
					<tr>
						<td class="borde centrado">Valor Depto.</td>
						<td class="borde centrado">
							<?php 
							if ($id_pie_abo_ven==1) {
								echo number_format($fila["monto_vivienda_ven"], 2, ',', '.');
							} else {
								echo number_format($total_general, 2, ',', '.');
							}
							?>
						</td>
					</tr>
					<?php 
					if ($fila["monto_estacionamiento_ven"]>0 || $fila["monto_bodega_ven"]>0) {
					$adicional = $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"];
					 ?>
					<tr>
						<td class="borde centrado">Adicionales</td>
						<td class="borde centrado"><?php echo number_format($adicional, 2, ',', '.');?></td>
					</tr>
					 <?php 
						}
					  ?>
					  <!-- muestra el pie cancelado real en base a los pagos -->
					<tr>
						<td class="borde centrado">Pie Cancelado</td>
						<td class="borde centrado"><?php echo number_format($pie_pagado_efectivo, 2, ',', '.');?><?php // echo number_format($pie_cancelado, 2, ',', '.');?></td>
					</tr>
					<?php 
					if ($id_pie_abo_ven==1) {
					 ?>
					<tr>
						<td class="borde centrado">Abono Inmobiliaria</td>
						<td class="borde centrado"><?php echo number_format($fila["descuento_ven"], 2, ',', '.');?></td>
					</tr>
					<?php 
					}

					// pie por cobrar real
					$pie_por_cobrar = $pie_cancelado + $fila["pie_cobrar_ven"] - $total_uf;

					if ($id_for_pag==2) {
						$pie_pagado_porcobrar = $pie_cancelado - $pie_pagado_efectivo;
					}
					if ($pie_pagado_porcobrar > 0 && $id_for_pag==1) {
					 ?>
					<tr>
						<td class="borde centrado">Pie por Cobrar</td>
						<td class="borde centrado"><?php echo number_format($pie_pagado_porcobrar, 2, ',', '.');?></td>
						<!-- <td class="borde centrado"><?php //echo number_format($fila["pie_cobrar_ven"], 2, ',', '.');?></td> -->
					</tr>
					<?php 
					}
					if ($fila["monto_credito_real_ven"]<>0) {
						$credito_hipo = number_format($fila["monto_credito_real_ven"], 2, ',', '.');
						$credito_suma = $fila["monto_credito_real_ven"];
					} else {
						$credito_hipo = number_format($fila["monto_credito_ven"], 2, ',', '.');
						$credito_suma = $fila["monto_credito_ven"];
					}
	

					$total = $pie_cancelado + $fila["pie_cobrar_ven"] + $credito_suma;
					$saldo_pie = $total - ($credito_suma + $pie_pagado_porcobrar + $pie_pagado_efectivo);

					if ($id_for_pag==1) {
						?>
						<tr>
							<td class="borde centrado">Saldo Pie</td>
							<td class="borde centrado"><?php echo number_format($saldo_pie, 2, ',', '.');?></td>
						</tr>
						<tr>
							<td class="borde centrado">Crédito Hipotecario</td>
							<td class="borde centrado"><?php echo $credito_hipo; ?></td>
						</tr>
					<?php
					} else {
						$total = $pie_cancelado + $fila["pie_cobrar_ven"] ;
						$saldo_total = $saldo_pie + $credito_suma;

						$saldo_total = $total_general - $pie_pagado_efectivo;
						?>
						<tr>
							<td class="borde centrado">Saldo a Pagar</td>
							<td class="borde centrado"><?php echo number_format($saldo_total, 2, ',', '.'); ?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td class="borde centrado">Total</td>
						<td class="borde centrado"><?php echo number_format($total, 2, ',', '.');?></td>
					</tr>
					<tr>
						<td class="borde centrado">Valor Final Inmobiliaria</td>
						<td class="borde centrado"><?php echo number_format($total_general_total, 2, ',', '.');?></td>
					</tr>
				</table>
				<?php 
				$consulta = 
                    "
                    SELECT
                        nombre_doc_con
                    FROM 
                        condominio_documento_condominio
                    WHERE 
                        id_con = ? AND
                        (nombre_doc_con = 'logo.jpg' OR nombre_doc_con = 'logo.png')
                    ";
                $contador = 1;
                $conexion->consulta_form($consulta,array($id_con));
                $haylogo = $conexion->total();
                if ($haylogo>0) {
                	$fila = $conexion->extraer_registro_unico();
                	$nombre_doc_con = $fila["nombre_doc_con"];
                	?>
					<img src="<?php echo _RUTA."archivo/condominio/documento/";?><?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="200">	

                	<?php
                }
				 ?>
			</td>
		</tr>
	</table>
</body>
</html>