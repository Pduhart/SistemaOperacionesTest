<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

$cat_pago = $_GET["cat"];

$consulta_cat_pag = "SELECT nombre_cat_pag FROM pago_categoria_pago WHERE id_cat_pag = ".$cat_pago."";
$conexion->consulta($consulta_cat_pag);
$fila = $conexion->extraer_registro_unico();
$nombre_cat_pag = utf8_encode($fila["nombre_cat_pag"]);

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Comprobante Recepción de Valores</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 12px;
    	}
        .sin-borde{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
        }
		.sin-borde h3{
			font-size: 1.5rem;
			margin-bottom: 10px;
		}
		.sin-borde h6{
			font-size: 1rem;
			margin-top: 10px;
		}
		.sin-borde .hoy{
			width: 100%;
			border: 1px solid #000000;
			padding: 6px;
		}
		.sin-borde .periodo{
			width: 100%;
			padding: 6px;
		}
		.liquida{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
			border-collapse: collapse;
		}
		.liquida td{
			padding-bottom: .2rem;
			padding-top: .2rem;
		}
		.liquida .cabecera td{
			vertical-align: top;
			text-align: center;
		}
		.liquida .separa td{
			font-weight: bold;
			border-bottom: 1px solid #000000;
			border-top: 1px solid #000000;
		}
		.liquida .separa.total td{
			border-top: 2px solid #000000;
		}
		.derecha{
			text-align: right;
		}
		.centro{
			text-align: center;
		}
		.liquida .bl-1{
			border-left: 1px solid #000000;
		}

		.conborde td{
			border: 1px solid #000000;
			padding: 2px 3px;
		}
		.bold td{
			font-weight: bold;
		}

		.borde-top{
			border-top: 1px solid #000000;
		}

		.btn{
			background-color: #DBDBDB;
			padding: 3px 6px;
			border-radius: 4px;
			text-decoration: none;
		}


		@media print 
			{
			    @page {
			      margin:0;
			    }
			    html, body {
			        overflow:visible;
			        font-family: "Verdana", arial;
			    }
			    body {
			    	padding:  0.3cm 1cm;
			    }
			    .no-print, .no-print *
			    {
			        display: none !important;
			    }
			}

		.table_cliente{
			width:  100%;
			border-collapse:  collapse;
		}

		.table_cliente td{
			border:  .1px solid #9c9c9c;
			padding:  2px 4px;
		}

		.table_cuadro {
			width:  100%;
			border-collapse:  collapse;
		}

		.table_cuadro td {
			border:  .1px solid #9c9c9c;
			padding:  2px 4px;
		}

		.table_compra{
			width:  100%;
			border-collapse: collapse;
			border:  1px solid #000000;
		}

		.table_compra thead{
			border:  1px solid #9c9c9c;
			text-align: left;
			
		}

		.table_compra thead th{
			padding:  3px;
		}

		.table_compra .resumen td{
			border-top:  1px solid #9c9c9c;
			padding:  3px;
		}

		.table_firmas{
			width:  100%;
			margin-top: 20px;
			border-collapse:  collapse;
			font-weight: bold;
		}

		.table_firmas td{
			text-align:  center;
			border:  .1px solid #9c9c9c;
			padding: 4px;
		}

		.table_firmas .firma td{
			height: 90px;
		}

		.nota p{
			font-weight: bold;
			text-align:  center;
		}
    </style>
</head>
<body>

	 <a class="btn no-print" href="comprobante_valores_pdf.php?id=<?php echo $id; ?>&cat=<?php echo $cat_pago; ?>" target="_blank">PDF</a>

	<?php  

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
	$conexion->consulta_form($consulta,array(14));
	$fila = $conexion->extraer_registro_unico();
	$nombre_gerente_operacion = $fila["valor_par"];

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
	$conexion->consulta_form($consulta,array(15));
	$fila = $conexion->extraer_registro_unico();
	$nombre_notario = $fila["valor_par"];

	$consulta = 
		"
		SELECT 
			con.id_con, 
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			pro.rut_pro,
			con.nombre_con, 
			viv.nombre_viv,
			viv.id_viv,
			viv.prorrateo_viv,
			viv.id_mod,
			viv.metro_total_viv,
			mode.nombre_mod,
			ven.monto_estacionamiento_ven,
			ven.monto_bodega_ven,
			ven.monto_ven,
			ven.fecha_ven,
			ven.monto_reserva_ven,
			ven.monto_credito_ven,
			ven.monto_credito_real_ven,
			ven.id_pie_abo_ven,
			ven.descuento_ven,
			ven.id_for_pag,
			ven.pie_cancelado_ven,
			ven.id_vend,
			ven.id_pro
		FROM 
			venta_venta AS ven
			INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
			INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod 
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		WHERE 
			ven.id_ven = ?
		";
	$conexion->consulta_form($consulta,array($id));
	$fila = $conexion->extraer_registro_unico();
	$id_con = $fila["id_con"];
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$rut_pro = $fila["rut_pro"];
	$nombre_con = $fila["nombre_con"];
	$fecha_ven = $fila["fecha_ven"];
	$nombre_viv = $fila["nombre_viv"];
	$id_viv = $fila["id_viv"];
	$id_mod = $fila["id_mod"];
	$nombre_mod = $fila["nombre_mod"];
	$prorrateo_viv = $fila["prorrateo_viv"];
	$metro_total_viv = $fila["metro_total_viv"];
	$id_for_pag = $fila["id_for_pag"];
	$pie_cancelado_ven = $fila["pie_cancelado_ven"];
	$id_vend = $fila["id_vend"];
	$id_pro = $fila["id_pro"];
	// si hay extras
	$monto_estacionamiento_ven = $fila["monto_estacionamiento_ven"];
	$monto_bodega_ven = $fila["monto_bodega_ven"];
	if ($monto_estacionamiento_ven<>0) {
		$consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE id_viv = ".$id_viv." AND valor_esta <> 0";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $filaest) {
				$nombre_esta_extra .= ", ".$filaest["nombre_esta"];
			}
		}
	}

	if ($monto_bodega_ven<>0) {
		$consulta = "SELECT nombre_bod FROM bodega_bodega WHERE id_viv = ".$id_viv." AND valor_bod <> 0";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $filabod) {
				$nombre_bod_extra .= ", ".$filabod["nombre_bod"];
			}
		}
	}

	$monto_ven = $fila["monto_ven"];
	$monto_reserva_ven = $fila["monto_reserva_ven"];
	$monto_credito_ven = $fila["monto_credito_ven"];
	$monto_credito_real_ven = $fila["monto_credito_real_ven"];

	// echo $monto_credito_ven." - ".$monto_credito_real_ven;

	if ($monto_credito_real_ven<>'' && $monto_credito_real_ven<> 0) {
		$monto_credito = $monto_credito_real_ven;
	} else {
		$monto_credito = $monto_credito_ven;
	}
	$monto_pie_ven = $monto_ven - $monto_reserva_ven - $monto_credito;
	$id_pie_abo_ven = $fila["id_pie_abo_ven"];
	$descuento_ven = $fila["descuento_ven"];

	$id_ven = $id;

	// VENDEDOR
	$consulta_vend = "SELECT nombre_vend, apellido_paterno_vend, apellido_materno_vend FROM vendedor_vendedor WHERE id_vend = ".$id_vend."";
	$conexion->consulta($consulta_vend);
	$fila = $conexion->extraer_registro_unico();
	$nombre_vend = $fila["nombre_vend"];
	$apellido_paterno_vend = $fila["apellido_paterno_vend"];
	$apellido_materno_vend = $fila["apellido_materno_vend"];
	$nombre_vendedor = utf8_encode($nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend);
	// en caso contado
	// if ($id_for_pag==2) {
	// 	$monto_pie_ven = $pie_cancelado_ven;
	// }

	// estacionamiento inicial
	$consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE id_viv = ".$id_viv." AND valor_esta = 0";
	$conexion->consulta($consulta);
	$fila = $conexion->extraer_registro_unico();
	$nombre_esta = $fila["nombre_esta"];
	// bodega inicial
	$consulta = "SELECT nombre_bod FROM bodega_bodega WHERE id_viv = ".$id_viv." AND valor_bod = 0";
	$conexion->consulta($consulta);
	$fila = $conexion->extraer_registro_unico();
	$nombre_bod = $fila["nombre_bod"];


	// cantidad de ventas del cliente
	$consulta = "SELECT id_ven FROM venta_venta WHERE id_pro = ".$id_pro." AND id_est_ven > 3 AND id_ven < ".$id_ven."";
	$conexion->consulta($consulta);
	$cantidad_compras = $conexion->total();
	$cantidad_compras = $cantidad_compras + 1;
	
	$mes = date("n",strtotime($fecha_promesa_ven));
	$dia = date("d",strtotime($fecha_promesa_ven));
	$anio = date("Y",strtotime($fecha_promesa_ven));

	switch ($mes) {
		case 1:
			$nombre_mes = "Enero";
			break;
		
		case 2:
			$nombre_mes = "Febrero";
			break;
		case 3:
			$nombre_mes = "Marzo";
			break;
		case 4:
			$nombre_mes = "Abril";
			break;
		case 5:
			$nombre_mes = "Mayo";
			break;
		case 6:
			$nombre_mes = "Junio";
			break;
		case 7:
			$nombre_mes = "Julio";
			break;
		case 8:
			$nombre_mes = "Agosto";
			break;
		case 9:
			$nombre_mes = "Septiembre";
			break;
		case 10:
			$nombre_mes = "Octubre";
			break;
		case 11:
			$nombre_mes = "Noviembre";
			break;
		case 12:
			$nombre_mes = "Diciembre";
			break;
	}

	$logo = "logo-icp.jpg";
    $nombre_empresa = "INMOBILIARIA COSTANERA PACÍFICO SPA";

    $consultapar = 
        "
        SELECT
            valor_par
        FROM
            parametro_parametro
        WHERE
            valor2_par = ? AND
            id_con = ?
        ";
    $conexion->consulta_form($consultapar,array(27,$id_con));
    $filapar = $conexion->extraer_registro_unico();
    $sala_ventas = utf8_encode($filapar['valor_par']);

    $fecha_emision = date("d/m/Y");

    $consulta_uf = 
		"
		    SELECT
		        valor_uf
		    FROM
		        uf_uf
		    WHERE
		        fecha_uf = '".date("Y-m-d",strtotime($fecha_ven))."'
		    ";
	$conexion->consulta($consulta_uf);
	$filauf = $conexion->extraer_registro_unico();
	$valor_uf = $filauf["valor_uf"];

	?>
	<table class="sin-borde">
	    <tr>
	    	<td>
	    		<table style="width: 100%">
	    			<tr>
	    				<td style="line-height: 20px">
	    					<?php echo $nombre_empresa; ?><br>
	    					AVDA. PACÍFICO 2800, LA SERENA<br>
	    					FONO 595490788
	    				</td>
	    				<td style="width: 40%">
	    					<table class="table_cuadro">
	    						<tr>
	    							<td>FOLIO ÚNICO</td>
	    							<td><?php echo $id_ven; ?></td>
	    						</tr>
	    						<tr>
	    							<td>SALA DE VENTAS</td>
	    							<td><?php echo $sala_ventas; ?></td>
	    						</tr>
	    						<tr>
	    							<td>FECHA EMISIÓN</td>
	    							<td><?php echo $fecha_emision; ?></td>
	    						</tr>
	    					</table>
	    				</td>
	    			</tr>
	    		</table>
	    	</td>
	    </tr>
	    <tr>
	    	<td>
	    		<h3 style="text-align: center">Comprobante Recepción de Valores</h3>


	    		<table class="table_cliente">
	    			<tr>
	    				<td><b>RUT</b></td>
	    				<td><?php echo $rut_pro; ?></td>
	    				<td><b>NOMBRE</b></td>
	    				<td><?php echo mb_strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?></td>
	    			</tr>
	    			<tr>
	    				<td><b>ACUERDO</b></td>
	    				<td><?php echo $cantidad_compras; ?></td>
	    				<td><b>CONCEPT. COBRO</b></td>
	    				<td><?php echo mb_strtoupper($nombre_cat_pag); ?></td>
	    			</tr>
	    			<tr>
	    				<td><b>FECHA MONEDA</b></td>
	    				<td><?php echo date("d/m/Y",strtotime($fecha_ven)); ?></td>
	    				<td><b>VALOR MONEDA</b></td>
	    				<td><?php echo number_format($valor_uf, 2, ',', '.'); ?></td>
	    			</tr>
	    			<tr>
	    				<td>
	    					<b>PROYECTO</b>
	    				</td>
	    				<td><?php echo strtoupper(utf8_encode($nombre_con));?></td>
	    				<td></td>
	    				<td></td>
	    			</tr>
	    		</table>
			</td>
		</tr>

		<tr>
			<td>
				<h3 style="text-align: center">Documentos Recibidos</h3>
				<table style="width: 100%; border-collapse: collapse;" class="conborde centro">
					<tr class="bold">
						<td>Banco</td>
						<td>Forma de Pago</td>
						<td>N° Doc</td>
						<td>Fecha</td>
						<td>UF</td>
						<td>$</td>
					</tr>
					<?php
					$total_abono = 0;
					$total_uf = 0;
                    $consulta = 
                        "
                        SELECT 
                            pag.id_pag,
                            cat_pag.nombre_cat_pag,
                            ban.nombre_ban,
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
                            LEFT JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
                        WHERE 
                            pag.id_ven = ? AND
                            pag.id_for_pag <> 6 AND
                            pag.id_cat_pag = ".$cat_pago." ORDER BY
                            pag.fecha_pag ASC, pag.id_for_pag ASC, pag.numero_documento_pag ASC
                        ";
                    $contador = 1;
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
							$valor_uf_efectivo = 0;
							$abono_uf = 0;
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
									$abono_uf = $fila["monto_pag"];
								} else {
									$abono_uf = $fila["monto_pag"] / $valor_uf;
								}
							} else {
								$valor_uf = 0;
							}

							if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
								$pago_pesos = $fila["monto_pag"] * $valor_uf;
							} else {
								$pago_pesos = $fila["monto_pag"];
							}

                            $total_abono = $total_abono + $fila["monto_pag"];
							$total_uf = $total_uf + $abono_uf;
                            ?>
						<tr>
							<td style="width: 20%"><?php
									if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
										echo "--";
									} else {
										echo utf8_encode($fila["nombre_ban"]);
									}
									?></td>
							<td style="width: 20%"><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
							<td><?php echo $fila["numero_documento_pag"];?></td>
							<td>
								<?php 
								if ($fila["id_for_pag"]==6) {
									
								} else {
									echo date("d-m-Y",strtotime($fila["fecha_pag"]));
								}
								?>
							</td>
							<td><?php echo number_format($abono_uf, 2, ',', '.');?></td>
							<td>
								<?php 
								if ($fila["id_for_pag"]==6) {
									
								} else {
									?>
									$ <?php echo number_format($pago_pesos, 0, ',', '.');
								}
								?>
							</td>
						</tr>
						<?php
                            $contador++;
                        }
                    }
                    if ($id_pie_abo_ven==1) {
                    	?>
						<tr>
							<td></td>
							<td>Abono Promocional Inmobiliaria</td>
							<td>2</td>
							<td>--</td>
							<td><?php echo number_format($descuento_ven, 2, ',', '.');?></td>
							<td>--</td>
						</tr>
                    	<?php
                    }
                    ?>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<h3 style="text-align: center">Identificación de la Compra</h3>
				<table class="table_compra">
					<thead>
						<tr>
							<th>ID</th>
							<th>Descripción</th>
							<th>Tipo</th>
							<th>M2</th>
						</tr>
					</thead>
					<tr>
						<td><?php echo $id_viv; ?></td>
						<td>DEPARTAMENTO N° <?php echo utf8_encode($nombre_viv);?></td>
						<td>
							<?php 
							if($id_mod <> 3){
							 echo "DEPT / ".$nombre_mod;
							} else {
								echo $nombre_mod;
							}
							?>
						</td>
						<td><?php echo $metro_total_viv; ?></td>
					</tr>
					<?php 
					$consulta = "SELECT nombre_esta, id_esta FROM estacionamiento_estacionamiento WHERE id_viv = ".$id_viv." ";
					$conexion->consulta($consulta);
					$fila_consulta = $conexion->extraer_registro();
			        if(is_array($fila_consulta)){
			            foreach ($fila_consulta as $filaest) {
			            ?>
			            <tr>
			            	<td><?php echo $filaest['id_esta']; ?>E</td>
			            	<td>ESTACIONAMIENTO <?php echo $filaest['nombre_esta']; ?></td>
			            	<td>EST.</td>
			            	<td></td>
			            </tr>
			            <?php
						}
					}
					 ?>
					 <?php 
					$consulta = "SELECT nombre_bod, id_bod FROM bodega_bodega WHERE id_viv = ".$id_viv." ";
					$conexion->consulta($consulta);
					$fila_consulta = $conexion->extraer_registro();
			        if(is_array($fila_consulta)){
			            foreach ($fila_consulta as $filaest) {
			            ?>
			            <tr>
			            	<td><?php echo $filaest['id_bod']; ?>B</td>
			            	<td>BODEGA <?php echo $filaest['nombre_bod']; ?></td>
			            	<td>BOD.</td>
			            	<td></td>
			            </tr>
			            <?php
						}
					}
					 ?>
					 <tr class="resumen">
					 	<td></td>
					 	<td colspan="2"><b>Valor Final Venta</b></td>
					 	<td><b><?php echo number_format($monto_ven, 2, ',', '.');?> UF</b></td>
					 </tr>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<table class="table_firmas">
					<tr>
						<td colspan="4">Firmar</td>
					</tr>
					<tr>
						<td>Firma Cliente</td>
						<td>Firma Vendedor</td>
						<td>V°B° Gerencia</td>
						<td>Registro Operacional</td>
					</tr>
					<tr class="firma">
						<td></td>
						<td><?php echo mb_strtoupper($nombre_vendedor); ?></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td class="nota">
				<p>NOTA: ESTE COMPROBANTE SOLO ES VALIDO CON TIMBRE DE CAJA CANCELADO.<br>
				LOS PAGOS SERAN CONTABILIZADOS UNA VEZ VERIFICADOS DISPONIBILIDAD D FONDOS</p>
			</td>
		</tr>
	</table>
</body>
</html>