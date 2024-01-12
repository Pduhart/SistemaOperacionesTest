<?php 
ob_start();//Enables Output Buffering
session_start(); 
date_default_timezone_set('Chile/Continental');
include 'mpdf/mpdf.php';
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_cierre = $_GET["id"];
$id_vendedor = $_GET["id_vend"];

// cambio de textos

	$texto_tabla = "Comisión";
	$texto_titulo = "Comisión";


function get_uf_disistimiento($id_ven){

	$conexion = new conexion();

	// echo $id_ven;

	$consulta = 
	    "
	    SELECT
	        id_cie
	    FROM
	        cierre_venta_cierre
	    WHERE
	        id_ven = ? AND id_est_ven <> 3 
	    ";
	$conexion->consulta_form($consulta,array($id_ven));
	$fila = $conexion->extraer_registro_unico();
	$id_cie = $fila["id_cie"];

	$consulta = 
	    "
	    SELECT
	        fecha_hasta_cie
	    FROM
	        cierre_cierre
	    WHERE
	        id_cie = ?
	    ";
	$conexion->consulta_form($consulta,array($id_cie));
	$fila = $conexion->extraer_registro_unico();
	$fecha_hasta_cie = $fila["fecha_hasta_cie"];

	$consulta = 
	    "
	    SELECT
	        valor_uf
	    FROM
	        uf_uf
	    WHERE
	        fecha_uf = ?
	    ";
	$conexion->consulta_form($consulta,array($fecha_hasta_cie));
	$fila = $conexion->extraer_registro_unico();
	$valor_uf_desistimiento = utf8_encode($fila['valor_uf']);

	return $valor_uf_desistimiento;

}

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

$html = '<!DOCTYPE html>
<html>
<head>
    <title>LIQUIDACIÓN</title>
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
		.liquida .lista td{
			text-align: right;
		}
		.liquida .lista td.nombre{
			text-align: left;
		}
		.liquida .bl-1{
			border-left: 1px solid #000000;
		}
    </style>
</head>
<body>';

$consulta = 
    "
    SELECT
        nombre_vend,
        apellido_paterno_vend,
        apellido_materno_vend,
        id_usu
    FROM
        vendedor_vendedor
    WHERE
        id_vend = ?
    ";
$conexion->consulta_form($consulta,array($id_vendedor));
$fila = $conexion->extraer_registro_unico();
$nombre_vend = $fila["nombre_vend"];
$apellido_paterno_vend = $fila["apellido_paterno_vend"];
$apellido_materno_vend = $fila["apellido_materno_vend"];
$id_usu = $fila["id_usu"];

$total_liquidacion_bonos = 0;
$total_liquidacion_comisiones = 0;
$total_liquidacion_a_pagar = 0;

$consulta = 
    "
    SELECT
        cie.fecha_desde_cie,
        cie.fecha_hasta_cie,
        cie.anio_cie,
        uf.valor_uf,
        con.id_con,
        con.nombre_con,
        con.alias_con,
        mes.id_mes,
        mes.nombre_mes
    FROM
        cierre_cierre AS cie
        INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
        INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
        INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
        INNER JOIN uf_uf AS uf ON uf.fecha_uf = cie.fecha_hasta_cie
    WHERE
        cie.id_cie = ? AND
        ven.id_vend = ?
    GROUP BY
        cie.id_cie,
        cie.anio_cie,
        con.nombre_con,
        uf.valor_uf,
        mes.id_mes,
        con.alias_con,
        cie.fecha_desde_cie,
        cie.fecha_hasta_cie,
        con.id_con
    ";
$conexion->consulta_form($consulta,array($id_cierre,$id_vendedor));
$cantidad_condominio = $conexion->total();
$fila_consulta_cierre = $conexion->extraer_registro();
$contador_pagina = 1;
if(is_array($fila_consulta_cierre)){
    foreach ($fila_consulta_cierre as $fila_cierre) {
        $fecha_desde_cie = $fila_cierre["fecha_desde_cie"];
        $fecha_hasta_cie = $fila_cierre["fecha_hasta_cie"];
        $anio_cie = $fila_cierre["anio_cie"];
        $valor_uf = $fila_cierre["valor_uf"];
        $nombre_con = $fila_cierre["nombre_con"];
        $id_mes = $fila_cierre["id_mes"];
        $nombre_mes = $fila_cierre["nombre_mes"];
        $alias_con = $fila_cierre["alias_con"];
        $id_con = $fila_cierre["id_con"];

        $UF_LIQUIDACION = $valor_uf;
        $html .= '<table class="sin-borde">
        <tr>';
            	$consulta = 
        		    "
        		    SELECT
        		        valor_par,
        		        valor2_par
        		    FROM
        		        parametro_parametro
        		    WHERE
        		        id_con = ? AND
        		        valor2_par IN (6,7,8)
        		    ";
        		$conexion->consulta_form($consulta,array($id_con));
        		$fila_consulta = $conexion->extraer_registro();

                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
                    	if($fila["valor2_par"] == 6){
                    		$porcentaje_promesa = $fila["valor_par"];
                    	}
                    	else if($fila["valor2_par"] == 7){
                    		$porcentaje_escritura = $fila["valor_par"];
                    	}
                    	else{
                    		$comision = $fila["valor_par"];
                    	}
                    }
                }
                if ($id_con==1) {
                	$logo = "logo-empresa.jpg";
                } else {
                	$logo = "logo-icp.jpg";
                }
            	$html .= '<td style="width: 20%; text-align: left"><img src="../../assets/img/logo-icp.jpg"></td>
        		<td style="text-align: center;"><h2>Detalle '.$texto_titulo.' Ejecutivo: '.utf8_encode($nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend).'</h2><h6>Período: '.$id_mes.'/'.utf8_encode($anio_cie).'</h6></td>
        		<td style="width: 20%;">
        			<table class="hoy">
        				<tr>
        					<td style="width: 50%"><b>Fecha:</b></td>
        					<td>'.date("d-m-Y").'</td>
        				</tr>
        				<tr>
        					<td style="width: 50%"><b>Hora:</b></td>
        					<td>'.date("H:i").'</td>
        				</tr>
        				<tr>
                            <td style="width: 50%"><b>Página:</b></td>
        					<td>'.$contador_pagina.'/'.$cantidad_condominio.'</td>
        				</tr>
        			</table>
        			<table class="periodo">
        				<tr>
        					<td style="width: 50%"><b>Desde:</b></td>
        					<td>'.date("d-m-Y",strtotime($fecha_desde_cie)).'</td>
        				</tr>
        				<tr>
        					<td style="width: 50%"><b>Hasta:</b></td>
        					<td>'.date("d-m-Y",strtotime($fecha_hasta_cie)).'</td>
        				</tr>
        				<tr>
        					<td style="width: 50%"><b>Valor UF:</b></td>
        					<td>'.number_format($valor_uf, 2, ',', '.').'</td>
        				</tr>
        			</table>
        		</td>
            </tr>
        </table>';

        $html .= '<table class="liquida">
        	<thead>
        		<tr>
        			<th colspan="4" style="border:1px solid #000000;">CONDOMINIO '.utf8_encode($nombre_con).'</th>
        			<th colspan="4" style="text-align: center; border:1px solid #000000;">'.$texto_tabla.' UF</th>
        			<th colspan="2" style="text-align: center; border:1px solid #000000;">'.$texto_tabla.' $</th>
        			<th colspan="2" style="text-align: center; border:1px solid #000000;">Desistimiento</th>
        		</tr>
        	</thead>
        	<tbody>
        		<tr class="cabecera">
        			<td style="width: 7%"></td>
        			<td style="width: 7%">Valor</td>
        			<td>Nombre Cliente</td>
        			<td style="width: 9%">Fecha Cierre</td>
        			<td style="width: 5%" class="bl-1">%</td>
        			<td style="width: 7%">Total</td>
        			<td style="width: 7%">Promesa<br>'.$porcentaje_promesa.'</td>
        			<td style="width: 7%">Escritura<br>'.$porcentaje_escritura.'</td>
        			<td style="width: 7%" class="bl-1">Promesa<br>'.$porcentaje_promesa.'</td>
        			<td style="width: 7%">Escritura<br>'.$porcentaje_escritura.'</td>
        			<td style="width: 8%" class="bl-1">Fecha</td>
        			<td style="width: 9%">Descuento $</td>
        		</tr>';
        		//Desistimientos del mes -->
        		$html .= '<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Desistimientos</td>
        		</tr>';
        		 
                $total_desistimiento_acumulado = 0;
                $monto_uf_acumulado_promesa = 0;
                $monto_uf_acumulado_escritura = 0;
                $monto_acumulado_promesa = 0;
                $monto_acumulado_escritura = 0;
                $monto_uf_acumulado_escritura_desi = 0;
                $monto_uf_acumulado_promesa_desi = 0;
                $monto_acumulado_bonos = 0;
                $consulta = 
                    "
                    SELECT
                        vend.id_vend,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.fecha_ven,
                        ven.promesa_monto_comision_ven,
                        ven.escritura_monto_comision_ven,
                        ven.total_comision_ven,
                        ven.promesa_bono_precio_ven,
                        ven.escritura_bono_precio_ven,
                        ven.total_bono_precio_ven,
                        ven.factor_categoria_ven,
                        ven.porcentaje_comision_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        des_ven.fecha_des_ven,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        vendedor_vendedor AS vend
                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                        INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                    WHERE
                        tor.id_con = ? AND
                        vend.id_vend = ? AND
                        EXISTS(
                            SELECT 
                                ven_cie.id_ven_cie
                            FROM 
                                cierre_venta_cierre AS ven_cie
                            WHERE
                                ven_cie.id_cie = ? AND
                                ven_cie.id_ven = ven.id_ven AND
                                ven_cie.id_est_ven = 3
                        )
                    ";
                $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {

                    	$UF_DESISTIMIENTO_VENTA = 0;

                    	$UF_DESISTIMIENTO_VENTA = get_uf_disistimiento($fila_det['id_ven']);

                        $monto_comision_promesa_desi = round(round($fila_det['promesa_monto_comision_ven'],2) * $UF_DESISTIMIENTO_VENTA);
                        $monto_comision_escritura_desi = round(round($fila_det['escritura_monto_comision_ven'],2) * $UF_DESISTIMIENTO_VENTA);

                        // $monto_comision_promesa_desi = round($fila_det['promesa_monto_comision_ven'] * $UF_LIQUIDACION);
                        // $monto_comision_escritura_desi = round($fila_det['escritura_monto_comision_ven'] * $UF_LIQUIDACION);

                        // if ($fila_det['id_ven'] == 484) {

                        // 	$promesa_monto_comision_ven_desistimiento = round($fila_det['promesa_monto_comision_ven'],1);
                        	
                        // 	$monto_comision_promesa_desi = $promesa_monto_comision_ven_desistimiento * 29706.87;

                        // }

                        // if ($fila_det['id_ven'] == 509) {

                        // 	$promesa_monto_comision_ven_desistimiento = round($fila_det['promesa_monto_comision_ven'],2);
                        	
                        // 	$monto_comision_promesa_desi = $promesa_monto_comision_ven_desistimiento * 29753.8;

                        // }
                        $total_desistimiento = 0;

                        $porcentaje_comision_ven = $fila_det['porcentaje_comision_ven'];
                        $factor_categoria_ven = $fila_det['factor_categoria_ven'];

	                    $consulta = 
			                "
			                SELECT
			                    valor_par
			                FROM
			                    parametro_parametro
			                WHERE
			                    id_con = ? AND
			                    valor2_par IN (".$factor_categoria_ven.")
			                ";
			            $conexion->consulta_form($consulta,array($id_con));
			            $filacategoria = $conexion->extraer_registro_unico();
			            $porcentaje_multiplo_categoria = $filacategoria['valor_par'];

                        $porcentaje_comision_venta = $porcentaje_comision_ven * $porcentaje_multiplo_categoria;
                        
                        $consulta = 
                            "
                            SELECT
                                id_est_ven
                            FROM
                                cierre_venta_cierre
                            WHERE
                                id_ven = ? AND
                                id_est_ven = ?
                            ";
                        $conexion->consulta_form($consulta,array($fila_det['id_ven'],4));
                        $cantidad_estado_promesa = $conexion->total();

                        $consulta = 
                            "
                            SELECT
                                id_est_ven
                            FROM
                                cierre_venta_cierre
                            WHERE
                                id_ven = ? AND
                                id_est_ven = ?
                            ";
                        $conexion->consulta_form($consulta,array($fila_det['id_ven'],6));
                        $cantidad_estado_escritura = $conexion->total();

						if ($fila_det['id_pie_abo_ven']==1) {
                        	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                        } else {
                        	$valor_venta_comision = $fila_det['monto_ven'];
                        }

                        $html .= '<tr class="lista">
                        	<td>'.utf8_encode($alias_con).'</td>
                        	<td>'.number_format($valor_venta_comision, 2, ',', '.').'</td>
                            <td class="nombre">'.utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']).'</td>
                            <td>'.date("d/m/Y",strtotime($fila_det['fecha_ven'])).'</td>
                            <td>'.$porcentaje_comision_venta.'</td>';
                            
                            if($cantidad_estado_promesa > 0){
                            	$monto_uf_acumulado_promesa_desi = $monto_uf_acumulado_promesa_desi - $fila_det['promesa_monto_comision_ven'];
                            	$monto_acumulado_promesa_desi = $monto_acumulado_promesa_desi - $monto_comision_promesa_desi;
                                $total_desistimiento = $total_desistimiento + $monto_comision_promesa_desi;
                                $html .= '<td>'.number_format($fila_det['promesa_monto_comision_ven'], 2, ',', '.').'</td>';
                                
                            }
                            else{
                                $html .= '<td></td>';
                            }
                            if($cantidad_estado_escritura > 0){
                            	$monto_uf_acumulado_escritura_desi = $monto_uf_acumulado_escritura_desi - $fila_det['escritura_monto_comision_ven'];
                            	$monto_acumulado_escritura_desi = $monto_acumulado_escritura_desi - $monto_comision_escritura_desi;
                                $total_desistimiento = $total_desistimiento + $monto_comision_escritura_desi;
                                $html .= '<td>'.number_format($fila_det['escritura_monto_comision_ven'], 2, ',', '.').'</td>';
                                
                            }
                            else{
                                $html .= '<td></td>';
                            }
                            $html .= '<td></td>
                            <td></td>
                            <td></td>
                            <td>'.date("d/m/Y",strtotime($fila_det['fecha_des_ven'])).'</td>
                            <td>'.number_format($total_desistimiento, 0, ',', '.').'.-</td>
                        </tr>';
                        $total_desistimiento_acumulado = $total_desistimiento_acumulado + $total_desistimiento;
                    }
                }
                //Ventas del Mes -->
        		$html .= '<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Cierres y Escrituras</td>
        		</tr>';
        		$monto_acumulado_a_pagar = 0;
                
                $consulta = 
                    "
                    SELECT
                        vend.id_vend,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.fecha_ven,
                        ven.promesa_monto_comision_ven,
                        ven.escritura_monto_comision_ven,
                        ven.total_comision_ven,
                        ven.promesa_bono_precio_ven,
                        ven.escritura_bono_precio_ven,
                        ven.total_bono_precio_ven,
                        ven.factor_categoria_ven,
                        ven.porcentaje_comision_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        vendedor_vendedor AS vend
                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    WHERE
                        tor.id_con = ? AND
                        vend.id_vend = ? AND
                        EXISTS(
                            SELECT 
                                ven_cie.id_ven_cie
                            FROM 
                                cierre_venta_cierre AS ven_cie
                            WHERE
                                ven_cie.id_cie = ? AND
                                ven_cie.id_ven = ven.id_ven AND
                                ven_cie.id_est_ven IN (4,6)
                        )
                        
                    ";
                $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {
                        // $monto_comision_promesa = round($fila_det['promesa_monto_comision_ven'] * $fila_det['valor_uf']);
                        // $monto_comision_escritura = round($fila_det['escritura_monto_comision_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $fila_det['valor_uf']);

                        $porcentaje_comision_ven = $fila_det['porcentaje_comision_ven'];
                        $factor_categoria_ven = $fila_det['factor_categoria_ven'];

	                    $consulta = 
			                "
			                SELECT
			                    valor_par
			                FROM
			                    parametro_parametro
			                WHERE
			                    id_con = ? AND
			                    valor2_par IN (".$factor_categoria_ven.")
			                ";
			            $conexion->consulta_form($consulta,array($id_con));
			            $filacategoria = $conexion->extraer_registro_unico();
			            $porcentaje_multiplo_categoria = $filacategoria['valor_par'];

                        $porcentaje_comision_venta = $porcentaje_comision_ven * $porcentaje_multiplo_categoria;

						if ($fila_det['id_pie_abo_ven']==1) {
                        	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                        } else {
                        	$valor_venta_comision = $fila_det['monto_ven'];
                        }

                        // PROMESAS
                        $comision_promesa_red = floor($fila_det['promesa_monto_comision_ven'] * 1000) / 1000;
				        $comision_promesa_red = round($comision_promesa_red, 2);

				        $monto_comision_promesa = round($comision_promesa_red * $UF_LIQUIDACION);
                        $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $UF_LIQUIDACION);

                        // ESCRITURAS
                        $comision_escritura_red = floor($fila_det['escritura_monto_comision_ven'] * 1000) / 1000;
				        $comision_escritura_red = round($comision_escritura_red, 2);

                        $monto_comision_escritura = round($comision_escritura_red * $UF_LIQUIDACION);
                        $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $UF_LIQUIDACION);

                        $html .= '<tr class="lista">
                        	<td>'.utf8_encode($alias_con).'</td>
                        	<td>'.number_format($valor_venta_comision, 2, ',', '.').'</td>
                            <td class="nombre">'.utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']).'</td>
                            <td>'.date("d/m/Y",strtotime($fila_det['fecha_ven'])).'</td>
                            <td>'.$porcentaje_comision_venta.'</td>
                            <td>'.number_format($fila_det['total_comision_ven'], 3, ',', '.').'</td>
                            <td>'.$comision_promesa_red.'</td>
                            <td>'.$comision_escritura_red.'</td>';

                            
                            $consulta = 
        	                    "
        	                    SELECT
        	                        id_est_ven
        	                    FROM
        	                        cierre_venta_cierre
        	                    WHERE
        	                        id_ven = ? AND
        	                        id_est_ven = ? AND
        	                        id_cie = ?
        	                    ";
                            
        	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],4,$id_cierre));
        	                $cantidad_estado_promesa = $conexion->total();

        	                $consulta = 
        	                    "
        	                    SELECT
        	                        id_est_ven
        	                    FROM
        	                        cierre_venta_cierre
        	                    WHERE
        	                        id_ven = ? AND
        	                        id_est_ven = ? AND
        	                        id_cie = ?
        	                    ";
        	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],6,$id_cierre));
        	                $cantidad_estado_escritura = $conexion->total();

                            if($cantidad_estado_promesa > 0){

                            	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $fila_det['promesa_monto_comision_ven'];
                            	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_comision_promesa;
                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_promesa;
                                $html .= '<td>'.number_format($monto_comision_promesa, 0, ',', '.').'</td>';
                            }
                            else{
                                $html .= '<td>0</td>';
                            }
                            
                        	if($cantidad_estado_escritura > 0){
                        		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $fila_det['escritura_monto_comision_ven'];
                        		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_comision_escritura;
                        		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_escritura;
                                $html .= '<td>'.number_format($monto_comision_escritura, 0, ',', '.').'</td>';
                            }
                        	else{
                        		$html .= '<td>0</td>';
                            }
                                
                            
                            $html .= '<td></td>
                            <td></td>
                        </tr>';
                    }
                }
                //bonos por rango -->
                
                $consulta = 
                    "
                    SELECT
                        *
                    FROM
                        cierre_bono_cierre
                    WHERE
                        id_usu = ? AND
                        id_cie = ? AND
                        id_con = ?
                    ";
                $conexion->consulta_form($consulta,array($id_usu,$id_cierre,$id_con)); 
                $cantidad_bono = $conexion->total();
                if($cantidad_bono > 0){
                    $fila_consulta_bono = $conexion->extraer_registro(); 
                    if(is_array($fila_consulta_bono)){
                        foreach ($fila_consulta_bono as $fila_bono) {
                            
                            $monto_bono = $valor_uf * $fila_bono['monto_bon_cie'];
                            $monto_acumulado_promesa = $monto_acumulado_promesa + $monto_bono;
                            $monto_acumulado_bonos = $monto_acumulado_bonos + $monto_bono;
                            $html .= '<tr class="separa">
                                <td colspan="2"></td>
                                <td colspan="3">Bono '.utf8_encode($fila_bono['nombre_bon_cie']).'</td>
                                <td style="text-align: right">'.number_format($fila_bono['monto_bon_cie'], 2, ',', '.').'</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">'.number_format($monto_bono, 0, ',', '.').'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                }
                else{
                	$html .= '<tr class="separa">
        				<td colspan="2"></td>
        				<td colspan="3"></td>
        				<td style="text-align: right"></td>
        				<td></td>
        				<td></td>
        				<td style="text-align: right"></td>
        				<td></td>
        				<td></td>
        				<td></td>
        			</tr>';
                }
                 
                
        		
        		
        		
        		//bonos al precio -->
        		$html .= '<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Bono al Precio</td>
        		</tr>';
        		
                $monto_acumulado_a_pagar = 0;
                $consulta = 
                    "
                    SELECT
                        vend.id_vend,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.promesa_monto_comision_ven,
                        ven.escritura_monto_comision_ven,
                        ven.total_comision_ven,
                        ven.promesa_bono_precio_ven,
                        ven.escritura_bono_precio_ven,
                        ven.total_bono_precio_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        vendedor_vendedor AS vend
                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    WHERE
                        tor.id_con = ? AND
                        vend.id_vend = ? AND
                        EXISTS(
                            SELECT 
                                ven_cie.id_ven_cie
                            FROM 
                                cierre_venta_cierre AS ven_cie
                            WHERE
                                ven_cie.id_cie = ? AND
                                ven_cie.id_ven = ven.id_ven AND
                                ven_cie.id_est_ven IN (4,6)
                        )
                    ";
                $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {
                        // $monto_comision_promesa = round($fila_det['promesa_monto_comision_ven'] * $fila_det['valor_uf']);
                        // $monto_comision_escritura = round($fila_det['escritura_monto_comision_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $fila_det['valor_uf']);
                        
                        if ($fila_det['id_pie_abo_ven']==1) {
                        	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                        } else {
                        	$valor_venta_comision = $fila_det['monto_ven'];
                        }

                        $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $UF_LIQUIDACION);
                        $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $UF_LIQUIDACION);


                        $html .= '<tr class="lista">
                        	<td>'.utf8_encode($alias_con).'</td>
                        	<td>'.number_format($valor_venta_comision, 2, ',', '.').'</td>
                            <td class="nombre">'.utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']).'</td>
                            <td></td>
                            <td></td>
                            <td>'.number_format($fila_det['total_bono_precio_ven'], 2, ',', '.').'</td>
                            <td>'.number_format($fila_det['promesa_bono_precio_ven'], 2, ',', '.').'</td>
                            <td>'.number_format($fila_det['escritura_bono_precio_ven'], 2, ',', '.').'</td>';

                            
                            $consulta = 
        	                    "
        	                    SELECT
        	                        id_est_ven
        	                    FROM
        	                        cierre_venta_cierre
        	                    WHERE
        	                        id_ven = ? AND
        	                        id_est_ven = ? AND
        	                        id_cie = ?
        	                    ";
        	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],4,$id_cierre));
        	                $cantidad_estado_promesa = $conexion->total();

        	                $consulta = 
        	                    "
        	                    SELECT
        	                        id_est_ven
        	                    FROM
        	                        cierre_venta_cierre
        	                    WHERE
        	                        id_ven = ? AND
        	                        id_est_ven = ? AND
        	                        id_cie = ?
        	                    ";
        	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],6,$id_cierre));
        	                $cantidad_estado_escritura = $conexion->total();

                            if($cantidad_estado_promesa > 0){
                            	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $fila_det['promesa_bono_precio_ven'];
                            	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_bono_promesa;
                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_promesa;
                                $html .= '<td>'.number_format($monto_bono_promesa, 0, ',', '.').'</td>';
                            }
                            else{
                                $html .= '<td>0</td>';
                            }
                            
                        	if($cantidad_estado_escritura > 0){
                        		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $fila_det['escritura_bono_precio_ven'];
                        		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_bono_escritura;
                        		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_escritura;
                                $html .= '<td>'.number_format($monto_bono_escritura, 0, ',', '.').'</td>';
                            }
                        	else{
                        		$html .= '<td>0</td>';
                            }
                                
                            
                            $html .= '<td></td>
                            <td></td>
                        </tr>';
                    }
                }
                
        		//Totales -->
        		$html .= '<tr class="separa total">
        			<td colspan="2"></td>
        			<td colspan="3">TOTALES</td>
        			<td></td>
        			<td style="text-align: right">'.number_format($monto_uf_acumulado_promesa, 2, ',', '.').'</td>
        			<td style="text-align: right">'.number_format($monto_uf_acumulado_escritura, 2, ',', '.').'</td>
        			<td style="text-align: right">'.number_format($monto_acumulado_promesa, 0, ',', '.').'</td>
        			<td style="text-align: right">'.number_format($monto_acumulado_escritura, 0, ',', '.').'</td>
        			<td></td>
        			<td style="text-align: right">'.number_format($total_desistimiento_acumulado, 0, ',', '.').'</td>
        		</tr>';
        		//a PAgar -->
        		$total_bonos = 0;
        		$total_comisiones = 0;
        		$total_pago = ($monto_acumulado_promesa + $monto_acumulado_escritura) - $total_desistimiento_acumulado;
                
                $total_bonos = $monto_acumulado_bonos + $monto_acumulado_a_pagar;
                $total_comisiones = $total_pago - $total_bonos;

                $total_liquidacion_bonos = $total_liquidacion_bonos + $total_bonos;
                $total_liquidacion_comisiones = $total_liquidacion_comisiones + $total_comisiones;


        		

        		

        		$html .= '<tr>
        			<td colspan="2"></td>
        			<td>COMISIONES</td>
        			<td></td>
        			<td></td>
        			<td>'.number_format($total_comisiones, 0, ',', '.').'</td>
        			<td colspan="6"></td>
        		</tr>
        		<tr>
        			<td colspan="2"></td>
        			<td>BONOS</td>
        			<td></td>
        			<td></td>
        			<td>'.number_format($total_bonos, 0, ',', '.').'</td>
        			<td colspan="6"></td>
        		</tr>';

        		


        		$html .= '<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="3">TOTAL A PAGAR</td>
        			<td colspan="3">'.number_format($total_pago, 0, ',', '.').'</td>
                    <td colspan="4" style="text-align: center"></td>
                    <td></td>
        		</tr>
        	</tbody>
        </table>
        <br/>
        <br/>';
        $contador_pagina++; 
    }
}

    $bonos ="
    SELECT 
        *
    FROM 
        bonos
    WHERE
        id_vendedor = ? and
        id_cierre = ? 
    ";
    $conexion->consulta_form($bonos,array($id_vendedor,$id_cierre));
    $bonos_detalle = $conexion->extraer_registro();
    


    if(is_array($bonos_detalle)){
        for ($i=0; $i < count($bonos_detalle); $i++) {
            $total_liquidacion_bonos += $bonos_detalle[$i]['monto'];
            
        $rango  = "";
        if($bonos_detalle[$i]['porcentaje'] == 100){ $rango = "Igual a 100%";}else if($bonos_detalle[$i]['porcentaje'] >100){ $rango = "desde 101% a 150%";}else if($bonos_detalle[$i]['porcentaje'] >150){ $rango = " de 150% en adelante";}else{ $rango ="por desempeño";}
       $html .= '      
        <table class="liquida" style=" margin-bottom:50px;">
            <thead>
                <tr>
                    <th colspan="11" style="text-align:center;border:1px solid #000000; font-weight: 700;">'.$bonos_detalle[$i]['nombre'].'</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" style="text-align:right;border:1px solid #000000;">% Cumplimiento : '.$bonos_detalle[$i]['porcentaje'].'%</td>                
                    <td colspan="2" style="text-align:center;border:1px solid #000000;">Mes de '.$bonos_detalle[$i]['mes'].'</td>               
                    <td colspan="2" style="text-align:center;border:1px solid #000000;">Rango de cumplimiento '.$rango.'</td>
                    <td colspan="3" style="text-align:left;border:1px solid #000000;">Total $'.$bonos_detalle[$i]['monto'].'</td>               
                </tr>
            </tbody>
        </table>';
        }
    }
    $total_liquidacion_a_pagar = $total_liquidacion_comisiones + $total_liquidacion_bonos; 
$html .= '<table class="liquida">
	<thead>
		<tr>
			<th colspan="4" style="border:1px solid #000000;">RESUMEN TOTAL</th>
			<th colspan="3" style="text-align: center; border:1px solid #000000;">COMISIONES</th>
			<th colspan="2" style="text-align: center; border:1px solid #000000;">BONOS</th>
			<th colspan="2" style="text-align: center; border:1px solid #000000;">TOTAL</th>
		</tr>
	</thead>
	<tbody>
		<tr class="cabecera">
			<td colspan="4"></td>
			<td colspan="3" class="bl-1">$ '.number_format($total_liquidacion_comisiones, 0, ',', '.').'</td>
			<td colspan="2" class="bl-1">$ '.number_format($total_liquidacion_bonos, 0, ',', '.').'</td>
			<td colspan="2" class="bl-1">$ '.number_format($total_liquidacion_a_pagar, 0, ',', '.').'</td>
		</tr>
	</tbody>
</table>';
$html .= '
</body>
</html>';
// echo $html;
$mpdf = new mPDF('c','A4-L');
// $mpdf->charset_in='UTF-8';
// $mpdf->allow_charset_conversion=true;
$mpdf->writeHTML($html);
$nombre = 'documentos/liquidacion-vendedor-'.date('dmYHi').'.pdf';
ob_end_clean();//End Output Buffering
$pdf = $mpdf->output($nombre ,'I');
// $mpdf->charset_in='UTF-8';
// $mpdf->allow_charset_conversion=true;
// $mpdf->writeHTML($html);
// $mpdf->AddPage();
// $mpdf->WriteHTML($html2);
// $nombre = 'documentos/liquidacion-vendedor-'.date('dmYHi').'.pdf';
// $fecha = date('Y-m-d H:i:s');
// $pdf = $mpdf->output($nombre ,'I');

?>