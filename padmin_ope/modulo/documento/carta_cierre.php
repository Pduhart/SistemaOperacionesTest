<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];
$pie = $_GET["pie"];

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Carta Cierre Negocio</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 13px;
    	}
        .sin-borde{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
        }
		.sin-borde h2{
			font-size: 1.4rem;
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
		    .no-print, .no-print *
		    {
		        display: none !important;
		    }
		}
    </style>
</head>
<body>
	<?php 
	if ($pie > 0) {
	 ?>
	 <a class="btn no-print" href="carta_cierre_pdf.php?id=<?php echo $id; ?>" target="_blank">PDF</a>
	<?php 
	} else {
	 ?>
	 <a class="btn no-print" href="carta_cierre_spie_pdf.php?id=<?php echo $id; ?>" target="_blank">PDF</a>
	<?php 
	}
	?>
	
	<a class="btn no-print" href="carta_cierre_word.php?id=<?php echo $id; ?>" target="_blank">Word</a>
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
			ven.pie_cancelado_ven
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
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$rut_pro = $fila["rut_pro"];
	$nombre_con = $fila["nombre_con"];
	$fecha_promesa_ven = $fila["fecha_ven"];
	$nombre_viv = $fila["nombre_viv"];
	$id_viv = $fila["id_viv"];
	$prorrateo_viv = $fila["prorrateo_viv"];
	$id_for_pag = $fila["id_for_pag"];
	$pie_cancelado_ven = $fila["pie_cancelado_ven"];
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

	if ($id_con==1) {
    	$logo = "logo-empresa.jpg";
    	$nombre_empresa = "Inmobiliaria Cordillera SPA";
    } else {
    	$logo = "logo-icp.jpg";
    	$nombre_empresa = "Inmobiliaria Costanera Pacífico";
    }

	?>
	<table class="sin-borde">
	    <!-- <tr> -->
			<!-- <td style="text-align: left"><img src="<?php //echo _ASSETS."img/".$logo."";?>" style="margin-right: 24%">  -->
			<?php 
			// $consulta = 
   //              "
   //              SELECT
   //                  nombre_doc_con
   //              FROM 
   //                  condominio_documento_condominio
   //              WHERE 
   //                  id_con = ? AND
   //                  (nombre_doc_con = 'logo.jpg' OR nombre_doc_con = 'logo.png' OR nombre_doc_con = 'logo2.jpg' OR nombre_doc_con = 'logo2.png')
   //              ";
   //          $contador = 1;
   //          $conexion->consulta_form($consulta,array($id_con));
   //          $haylogo = $conexion->total();
   //          if ($haylogo>0) {
   //          	$fila = $conexion->extraer_registro_unico();
   //          	$nombre_doc_con = $fila["nombre_doc_con"];
            	?>
				<!-- <img src="<?php // echo _RUTA."archivo/condominio/documento/";?><?php //echo $id_con; ?>/<?php //echo $nombre_doc_con; ?>" width="200">	 -->

            	<?php
            // }
			 ?>
			<!-- </td> -->
	    <!-- </tr> -->
	    <tr>
	    	<td>
	    		<div style="display: flex; justify-content: space-between; align-items: center;">
	    			<img src="<?php echo _ASSETS."img/".$logo."";?>" width="103" height="108">
	    			<?php 
					$consulta = 
		                "
		                SELECT
		                    nombre_doc_con
		                FROM 
		                    condominio_documento_condominio
		                WHERE 
		                    id_con = ? AND
		                    (nombre_doc_con = 'logo.jpg' OR nombre_doc_con = 'logo.png' OR nombre_doc_con = 'logo2.jpg' OR nombre_doc_con = 'logo2.png')
		                ";
		            $contador = 1;
		            $conexion->consulta_form($consulta,array($id_con));
		            $haylogo = $conexion->total();
		            if ($haylogo>0) {
		            	$fila = $conexion->extraer_registro_unico();
		            	$nombre_doc_con = $fila["nombre_doc_con"];
		            	?>
						<img src="<?php echo _RUTA."archivo/condominio/documento/";?><?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="170">	

		            	<?php
		            }
					 ?>
	    		</div>
	    	</td>
	    </tr>
	    <tr>
	    	<td>
	    		<h3 style="text-align: center">DETALLE CIERRE NEGOCIO<br><?php echo utf8_encode($nombre_con);?></h3>
	    		<br><br>
	    		<p style="text-align: right">La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?></p><br>
	    		<table style="width: 80%">
	    			<tr>
	    				<td style="width: 20%">Señor(a)</td>
	    				<td>: <?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></td>
	    			</tr>
	    			<tr>
	    				<td>RUT</td>
	    				<td>: <?php echo $rut_pro; ?></td>
	    			</tr>
	    		</table>
				<p>De nuestra consideración:</p>
				<p>Por la presente nos es grato saludarlo(a) y a la vez informarle las condiciones del cierre de negocio:</p>
				<p>DEPARTAMENTO N° <?php echo utf8_encode($nombre_viv);?>, BODEGA <?php echo utf8_encode($nombre_bod).utf8_encode($nombre_bod_extra);?>, ESTACIONAMIENTO <?php echo utf8_encode($nombre_esta).utf8_encode($nombre_esta_extra);?>, por un valor de <?php echo number_format($monto_ven, 2, ',', '.');?> U.F., <?php echo strtoupper(utf8_encode($nombre_con));?></p>
				<p><b>Forma de Pago UF:</b></p>
				<table style="width: 100%; border-collapse: collapse;" class="conborde">
					<tr>
						<td style="width: 50%">Reserva</td>
						<td class="derecha"><?php echo number_format($monto_reserva_ven, 2, ',', '.');?></td>
					</tr>
					<tr>
						<td>Pie Contado</td>
						<td class="derecha"><?php echo number_format($monto_pie_ven, 2, ',', '.');?></td>
					</tr>
					<?php 
						if ($id_for_pag==1) {
					?>
					<tr>
						<td>Crédito Hipotecario</td>
						<td class="derecha"><?php echo number_format($monto_credito, 2, ',', '.');?></td>
					</tr>
					<?php
					} else {
					?>
					<tr>
						<td>Saldo a Pagar Contado</td>
						<td class="derecha"><?php echo number_format($monto_credito, 2, ',', '.');?></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td><b>Total</b></td>
						<td class="derecha"><b><?php echo number_format($monto_ven, 2, ',', '.');?></b></td>
					</tr>
				</table>
				<p><b>Cierre de Negocio:</b></p>
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
                            pag.id_cat_pag = 1 ORDER BY
                            pag.fecha_pag ASC, pag.numero_documento_pag ASC
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
								<td><?php
									if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
										echo "--";
									} else {
										echo utf8_encode($fila["nombre_ban"]);
									}
									?></td>
								<td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
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
                    ?>
				</table>
				<p><b>Detalle Pie:</b></p>
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
                            pag.id_cat_pag = 2 ORDER BY
                            pag.fecha_pag ASC, pag.numero_documento_pag ASC
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
							<td><?php
									if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
										echo "--";
									} else {
										echo utf8_encode($fila["nombre_ban"]);
									}
									?></td>
							<td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
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
				<?php 
				if ($id_con==3) {
				 ?>
				<p>En concordancia con lo anterior, se le informa que será de su exclusiva responsabilidad el cumplimiento y mantención de los requisitos necesarios para materializar la compra de su Departamento, poseer capacidad financiera suficiente para su adquisición y/o cumplir de modo adecuado las exigencias para acceder a créditos comerciales, de cualquier naturaleza, que permitan financiar la compra ofrecida.</p>
				<p>Una vez iniciado el proceso de Escrituración deberá pagar los Gastos Operacionales al banco o institución que financiará la adquisición de su departamento.</p>
				<p>En señal de aceptación y conocimiento de la información contenida en la presente, agradeceremos a usted firmar este documento en dos ejemplares, quedando uno en su poder y otro en su carpeta.</p>
				
				<?php 
				} else {
				?>
				<p style="text-align: justify;">Los valores señalados en recuadro son <u>referenciales y calculados a valor UF de hoy.</u></p>
				<p style="text-align: justify;">Los pagos se imputan contablemente según se indica en clausula número <u>CUARTA</u> de la <u>Promesa de Compraventa</u>: </p>
				<p style="text-align: justify;">“ …. Las sumas entregadas por el Promitente Comprador, referidas en los literales a) referente a “reserva”, b) referente a “Pie” y c) referente a “saldo restante”, de la presente cláusula, se imputarán al precio total, tomándose como criterio de la mencionada imputación, las sumas convertidas a Unidades de Fomento según valor de ésta al momento del COBRO EFECTIVO de los documentos entregados por el Promitente Comprador.</p>
				<p style="text-align: justify;">En concordancia con lo anterior, se le informa que será de su exclusiva responsabilidad el cumplimiento y mantención de los requisitos necesarios para materializar la compra de su Departamento, poseer capacidad financiera suficiente para su adquisición y/o cumplir de modo adecuado las exigencias para acceder a créditos comerciales, de cualquier naturaleza, que permitan financiar la compra ofrecida.</p>
				<p style="text-align: justify;">Una vez iniciado el proceso de Escrituración deberá pagar los Gastos Operacionales al banco o institución que financiará la adquisición de su departamento.</p>
				<p style="text-align: justify;">Además, deberá cancelar el Fondo de Puesta en Marcha (o de Explotación) que corresponde a los gastos de operación que se requieren para dar inicio al funcionamiento del Condominio y sus instalaciones, por una sola vez, específicamente cuando el propietario compra su departamento, monto equivalente a dos meses de gastos comunes aproximadamente, el cual, se cancela a la Inmobiliaria Costanera Pacifico SpA previa a la entrega de su departamento, estos recursos serán entregados a la Administración del Condominio.</p>				
				<p style="text-align: justify;">En señal de aceptación y conocimiento de la información contenida en la presente, agradeceremos a usted firmar este documento en dos ejemplares, quedando uno en su poder y otro en su carpeta.</p>
				<?php
				}

				if ($pie > 0) {
				?>
				<br><br><br><br><br><br>

				<div style="display: flex; justify-content: space-between; align-items: start;">
					<table style="width: 30%; text-align: center;">
						<tr>
							<td class="borde-top">
								<?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?><br>RUT: <?php echo $rut_pro; ?>
							</td>
						</tr>
					</table>
					<table style="width: 30%; text-align: center;">
						<tr>
							<td class="borde-top">
								<?php echo $nombre_gerente_operacion;?><br>Gerente de Ventas y Operaciones<br><?php echo $nombre_empresa; ?>
							</td>
						</tr>
					</table>
				</div>
				<?php } ?>
	    	</td>
	    	
	    </tr>
	</table>
</body>
</html>