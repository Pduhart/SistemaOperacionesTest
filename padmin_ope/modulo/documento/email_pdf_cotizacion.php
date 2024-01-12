<?php 
ob_start();//Enables Output Buffering
session_start(); 
date_default_timezone_set('Chile/Continental');
include 'mpdf/mpdf.php';
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

require '../../class/phpmailer_new/PHPMailerAutoload.php';

$id_cot = $_GET["id_cot"];

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

$html .= '
<!DOCTYPE html>
<html>
<head>
    <title>Formato Cotización</title>
    <meta charset="utf-8">
    <style type="text/css">

    	@page {
    		margin-top: 5mm;
    	}

    	html,body{
    		padding: 0px;
    		margin: 0;
    		font-family: "Verdana", arial;
    		font-size: 11px;
    		line-height: 12px;
    	}

    	table td{
    		padding: 2px 5px;
    	}

    	table.min-padding td{
    		padding: 2px 5px;
    	}

        .sin-borde{
			width: 100%;
			margin-left: auto;
			margin-right: auto;
			box-sizing: border-box;
        }

        .con-borde{
        	/*border:  .1px solid #9c9c9c;*/
        	border-collapse: collapse;
        	box-sizing: border-box;
        }

        .con-borde td{
        	border:  .1px solid #9c9c9c;
        }

        .ancho-completo{
        	width:  100%;
        }

        .titulos{
        	font-weight: bold;
        	text-decoration: underline;
        }

        .td-borde{
        	border:  .1px solid #9c9c9c;
        }

        .caja-borde{
        	border:  .1px solid #9c9c9c;
        	border-collapse: collapse;
        	box-sizing: border-box;
        }

        .bold{
        	font-weight: bold;
        }

        .td-borde-right{
        	border-right: .1px solid #9c9c9c;
        }

        .borde-bottom{
        	border-bottom: .1px solid #9c9c9c;
        }

        .text-right{
        	text-align:  right;
        }

        .text-center{
        	text-align:  center;
        }

        .td-min-padding{
        	padding: 2px 5px;
        }
		
    </style>
</head>
<body>';

	$consulta = 
		"
		SELECT 
			con.id_con, 
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			pro.rut_pro,
			pro.fono_pro,
			pro.fono2_pro,
			pro.correo_pro,
			pro.id_sex,
			pro.direccion_pro,
			con.nombre_con, 
			viv.nombre_viv,
			viv.id_viv,
			viv.id_pis,
			viv.metro_terraza_viv,
			viv.metro_total_viv,
			viv.metro_viv,
			viv.valor_viv,
			cot.fecha_cot,
			cot.numero_cot,
			cot.porcentaje_credito_cot,
			vend.id_vend,
			vend.nombre_vend,
			vend.apellido_paterno_vend,
			vend.apellido_materno_vend,
			vend.correo_vend,
			vend.fono_vend,
			com.nombre_com,
			viv_ori.nombre_ori_viv,
			mod_viv.nombre_mod,
			mod_viv.numero_cama_mod,
			mod_viv.numero_banio_mod
		FROM 
			cotizacion_cotizacion AS cot
			INNER JOIN propietario_propietario AS pro ON pro.id_pro = cot.id_pro
			INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
			INNER JOIN modelo_modelo AS mod_viv ON mod_viv.id_mod = viv.id_mod
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN vivienda_orientacion_vivienda AS viv_ori ON viv_ori.id_ori_viv = viv.id_ori_viv
            INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
            INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
		WHERE 
			cot.id_cot = ?
		";
	$conexion->consulta_form($consulta,array($id_cot));
	$fila = $conexion->extraer_registro_unico();
	$id_con = $fila["id_con"];
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$direccion_pro = $fila["direccion_pro"];

	$correo_vend = $fila["correo_vend"];
	$fono_vend = $fila["fono_vend"];
	$nombre_vend = $fila["nombre_vend"];
	$apellido_paterno_vend = $fila["apellido_paterno_vend"];
	$apellido_materno_vend = $fila["apellido_materno_vend"];

	$rut_pro = $fila["rut_pro"];
	$id_sex = $fila["id_sex"];
	$fono_pro = $fila["fono_pro"];
	$fono2_pro = $fila["fono2_pro"];
	$correo_pro = $fila["correo_pro"];
	$nombre_con = $fila["nombre_con"];
	$nombre_viv = $fila["nombre_viv"];
	$numero_cot = $fila["numero_cot"];
	$numero_cama_mod = utf8_encode($fila["numero_cama_mod"]);
	$nombre_mod = utf8_encode($fila["nombre_mod"]);
	$numero_banio_mod = utf8_encode($fila["numero_banio_mod"]);
	$nombre_com = $fila["nombre_com"];
	$id_viv = $fila["id_viv"];
	$id_pis = $fila["id_pis"];
	$metro_terraza_viv = $fila["metro_terraza_viv"];
	$metro_terraza_viv_for = number_format($metro_terraza_viv, 2, ',', '.');
	$metro_total_viv = $fila["metro_total_viv"];
	$metro_total_viv_for = number_format($metro_total_viv, 2, ',', '.');
	$metro_viv = $fila["metro_viv"];
	$metro_viv_for = number_format($metro_viv, 2, ',', '.');
	$nombre_ori_viv = $fila["nombre_ori_viv"];
	$fecha_cot = date("Y-m-d",strtotime($fila["fecha_cot"]));
	$fecha_cotizacion = date("d-m-Y",strtotime($fila["fecha_cot"]));

	$valor_viv = $fila["valor_viv"];
	$valor_viv_for = number_format($valor_viv, 2, ',', '.');

	$porcentaje_credito_cot = $fila["porcentaje_credito_cot"];
	$porcentaje_credito_cot_for = number_format($porcentaje_credito_cot, 2, ',', '.');
	// si hay extras

	$consulta = 
	    "
	    SELECT
	        valor_uf
	    FROM
	        uf_uf
	    WHERE
	        fecha_uf = ?
	    ";
	$conexion->consulta_form($consulta,array($fecha_cot));
	$fila = $conexion->extraer_registro_unico();
	$uf_cotizacion = utf8_encode($fila['valor_uf']);

	$valor_viv_pesos = round($valor_viv * $uf_cotizacion);
	
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

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
	$conexion->consulta_form($consulta,array(167));
	$fila = $conexion->extraer_registro_unico();
	$interes_cotizacion = $fila["valor_par"];

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
	$conexion->consulta_form($consulta,array(27,$id_con));
	$fila = $conexion->extraer_registro_unico();
	$direccion_condominio = $fila["valor_par"];

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
	$conexion->consulta_form($consulta,array(12,$id_con));
	$fila = $conexion->extraer_registro_unico();
	$uf_reserva = $fila["valor_par"];

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
	$conexion->consulta_form($consulta,array(4,$id_con));
	$fila = $conexion->extraer_registro_unico();
	$porcentaje_descuento = $fila["valor_par"];

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
	$conexion->consulta_form($consulta,array(18,$id_con));
	$fila = $conexion->extraer_registro_unico();
	$valor_bodega = $fila["valor_par"];
	$valor_bodega_pesos = round($fila["valor_par"] * $uf_cotizacion);
	$valor_bodega_for = number_format($valor_bodega, 2, ',', '.');

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
	$conexion->consulta_form($consulta,array(19,$id_con));
	$fila = $conexion->extraer_registro_unico();
	$valor_esta = $fila["valor_par"];
	$valor_esta_pesos = round($fila["valor_par"] * $uf_cotizacion);
	$valor_esta_for = number_format($valor_esta, 2, ',', '.');

	$precio_lista_uf_total = $valor_viv + $valor_bodega + $valor_esta;
	$precio_lista_pesos_total = round($precio_lista_uf_total * $uf_cotizacion);

	$valor_descuento = $valor_bodega + $valor_esta;
	$valor_descuento_pesos = round($valor_descuento * $uf_cotizacion);
	$precio_con_descuento = $valor_viv;
	$precio_con_descuento_pesos = round($valor_viv * $uf_cotizacion);

	$porcentaje_reserva = ($uf_reserva * 100) / $precio_con_descuento;
	$reserva_pesos = round($uf_reserva * $uf_cotizacion);

	$porcentaje_contado = 100 - $porcentaje_reserva - $porcentaje_credito_cot;

	$monto_credito_uf = $precio_con_descuento * $porcentaje_credito_cot / 100;
	$monto_credito_pesos = round($monto_credito_uf * $uf_cotizacion);

	$monto_contado_uf = $precio_con_descuento - $monto_credito_uf - $uf_reserva;
	$monto_contado_pesos = round($monto_contado_uf * $uf_cotizacion);

	$nombre_cliente = utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);

	$nombre_vendedor = utf8_encode($nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend);
	

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

	$html .= '
	<table class="sin-borde">
	    <tr>
			<td colspan="3" style="text-align: left"><img src="../../assets/img/logo-icp.jpg"> 
			</td>
	    </tr>
	    <tr>
	    	<td style="vertical-align: bottom;"><b>Proyecto</b></td>
	    	<td style="vertical-align: bottom;"><b>: '.utf8_encode($nombre_con).'</b></td>
	    	<td rowspan="2" style="width: 35%; padding: 0">
	    		<table class="con-borde ancho-completo min-padding">
	    			<tr>
	    				<td>Cotización N°</td>
	    				<td class="text-right">'.$numero_cot.'</td>
	    			</tr>
	    			<tr>
	    				<td>Fecha</td>
	    				<td class="text-right">'.$fecha_cotizacion.'</td>
	    			</tr>
	    			<tr>
	    				<td>Valor U.F. $</td>
	    				<td class="text-right">'.number_format($uf_cotizacion, 2, ',', '.').'</td>
	    			</tr>
	    		</table>
	    	</td>
	    </tr>
	    <tr>
	    	<td style="vertical-align: top;"><b>Dirección</b></td>
	    	<td style="vertical-align: top;"><b>: '.utf8_encode($direccion_condominio).'</b></td>
	    </tr>
	</table>

	<table class="caja-borde ancho-completo" style="margin-top: 10px;">
		<tr>
			<td colspan="4" class="titulos td-borde">Datos del Cliente</td>
		</tr>
		<tr>
			<td style="width: 13%">Nombre</td>
			<td>'.$nombre_cliente.'</td>
			<td style="width: 13%">RUT</td>
			<td>'.utf8_encode($rut_pro).'</td>
		</tr>
		<tr>
			<td>Dirección</td>
			<td>'.utf8_encode($direccion_pro).'</td>
			<td>Teléfono 1</td>
			<td>'.utf8_encode($fono_pro).'</td>
		</tr>
		<tr>
			<td>Comuna</td>
			<td>'.utf8_encode($nombre_com).'</td>
			<td>Teléfono 2</td>
			<td>'.utf8_encode($fono2_pro).'</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>'.utf8_encode($correo_pro).'</td>
			<td></td>
			<td></td>
		</tr>
	</table>

	<table class="caja-borde ancho-completo" style="margin-top: 20px">
		<tr>
			<td colspan="10" class="titulos td-borde">Datos del Bien</td>
		</tr>
		<tr>
			<td colspan="2" rowspan="2" class="td-borde">Unidad</td>
			<td rowspan="2" class="td-borde">Tipo</td>
			<td rowspan="2" class="td-borde">Piso</td>
			<td rowspan="2" class="td-borde">Orient.</td>
			<td colspan="3" class="td-borde text-center">Superficies</td>
			<td colspan="2" class="td-borde text-center">Precio</td>
		</tr>
		<tr>
			<td class="td-borde text-center">Útil</td>
			<td class="td-borde text-center">Terraza</td>
			<td class="td-borde text-center">Total</td>
			<td class="td-borde text-center">UF</td>
			<td class="td-borde text-center">$</td>
		</tr>
		<tr>
			<td class="td-borde-right">Departamento</td>
			<td class="td-borde-right">'.utf8_encode($nombre_viv).'</td>
			<td class="td-borde-right">DEPT/'.$nombre_mod.' '.$numero_cama_mod.'D+'.$numero_banio_mod.'B</td>
			<td class="td-borde-right">'.utf8_encode($id_pis).'</td>
			<td class="td-borde-right">'.utf8_encode($nombre_ori_viv).'</td>
			<td class="td-borde-right text-right">'.utf8_encode($metro_viv_for).'</td>
			<td class="td-borde-right text-right">'.utf8_encode($metro_terraza_viv_for).'</td>
			<td class="td-borde-right text-right">'.utf8_encode($metro_total_viv_for).'</td>
			<td class="td-borde-right text-right">'.$valor_viv_for.'</td>
			<td class=" text-right">'.number_format($valor_viv_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td class="td-borde-right">Bodega</td>
			<td class="td-borde-right">'.utf8_encode($nombre_bod).'</td>
			<td class="td-borde-right">BOD.</td>
			<td class="td-borde-right">1</td>
			<td class="td-borde-right">X</td>
			<td class="td-borde-right text-right">3,9</td>
			<td class="td-borde-right text-right">0</td>
			<td class="td-borde-right text-right">3,9</td>
			<td class="td-borde-right text-right">'.$valor_bodega_for.'</td>
			<td class=" text-right">'.number_format($valor_bodega_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td class="td-borde-right borde-bottom">Estacionamiento</td>
			<td class="td-borde-right borde-bottom">'.utf8_encode($nombre_esta).'</td>
			<td class="td-borde-right borde-bottom">EST.</td>
			<td class="td-borde-right borde-bottom">0</td>
			<td class="td-borde-right borde-bottom">X</td>
			<td class="td-borde-right borde-bottom text-right">0,00</td>
			<td class="td-borde-right borde-bottom text-right">0,00</td>
			<td class="td-borde-right borde-bottom text-right">0,00</td>
			<td class="td-borde-right borde-bottom text-right">'.$valor_esta_for.'</td>
			<td class="borde-bottom text-right">'.number_format($valor_esta_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td colspan="10" style="border: none;"></td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td class="td-borde bold text-right" colspan="3">Total Precio Lista</td>
			<td class="td-borde bold text-right">'.number_format($precio_lista_uf_total, 2, ',', '.').'</td>
			<td class="td-borde bold text-right">'.number_format($precio_lista_pesos_total, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td class="td-borde bold text-right" colspan="3">Descuento</td>
			<td class="td-borde text-right">'.number_format($valor_descuento, 2, ',', '.').'</td>
			<td class="td-borde text-right">'.number_format($valor_descuento_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td class="td-borde bold text-right" colspan="3">Total Precio Venta</td>
			<td class="td-borde bold text-right">'.number_format($precio_con_descuento, 2, ',', '.').'</td>
			<td class="td-borde bold text-right">'.number_format($precio_con_descuento_pesos, 0, ',', '.').'</td>
		</tr>
	</table>

	<table class="caja-borde ancho-completo" style="margin-top: 10px">
		<tr>
			<td colspan="4" class="titulos">Forma de Pago</td>
		</tr>
		<tr>
			<td class="td-borde">Concepto</td>
			<td class="td-borde text-center">%</td>
			<td class="td-borde text-right">Valor UF</td>
			<td class="td-borde text-right">Valor $</td>
		</tr>
		<tr>
			<td class="td-borde-right">RESERVA</td>
			<td class="td-borde-right text-center">'.number_format($porcentaje_reserva, 2, ',', '.').'%</td>
			<td class="td-borde-right text-right">'.number_format($uf_reserva, 2, ',', '.').'</td>
			<td class=" text-right">'.number_format($reserva_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td class="td-borde-right">PIE CONTADO</td>
			<td class="td-borde-right text-center">'.number_format($porcentaje_contado, 2, ',', '.').'%</td>
			<td class="td-borde-right text-right">'.number_format($monto_contado_uf, 2, ',', '.').'</td>
			<td class=" text-right">'.number_format($monto_contado_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td class="td-borde-right borde-bottom">CREDITO HIPOTECARIO</td>
			<td class="td-borde-right borde-bottom text-center">'.$porcentaje_credito_cot_for.'%</td>
			<td class="td-borde-right borde-bottom text-right">'.number_format($monto_credito_uf, 2, ',', '.').'</td>
			<td class=" borde-bottom text-right">'.number_format($monto_credito_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td class="td-borde-right borde-bottom bold" style="text-align: right;">TOTAL</td>
			<td class="td-borde-right borde-bottom text-center">100%</td>
			<td class="td-borde-right borde-bottom text-right">'.number_format($precio_con_descuento, 2, ',', '.').'</td>
			<td class=" borde-bottom text-right">'.number_format($precio_con_descuento_pesos, 0, ',', '.').'</td>
		</tr>
		<tr>
			<td colspan="4" class=" td-min-padding"></td>
		</tr>
	</table>';
	$credito_cot = $monto_credito_uf;

	function PMT($i, $n, $p){
		$valor = $i * $p * pow((1 + $i), $n) / (1 - pow((1 + $i), $n));
		return $valor;
	}
	//------------------------------- CALCULO DIVIDENDO A 15 AÑOS
	$credito = -$credito_cot;
	$numero_pago = 12 * 15;
	$interes = $interes_cotizacion;
	$uf_cot = $uf_cotizacion;
	
	$valor_uf_15 = PMT($interes / 1200, $numero_pago, $credito);
	$valor_uf_15_round = round($valor_uf_15, 2);
	$valor_uf_15_formato = number_format($valor_uf_15, 2, ',', '.');
	$valor_peso_15 = $valor_uf_15_formato * $uf_cot;
	$valor_peso_15_formato = number_format($valor_peso_15, 0, ',', '.');
	
	$valor_uf_15_formato_renta = $valor_uf_15_round * 4;
	$valor_peso_15_renta = $valor_uf_15_formato_renta * $uf_cot;
	$valor_peso_15_formato_renta = "$ ".number_format($valor_peso_15_renta, 0, ',', '.');
	$valor_uf_15_formato_renta = $valor_uf_15_formato_renta." UF";
	$valor_uf_15_formato = $valor_uf_15_formato;
	
	//------------------------------- CALCULO DIVIDENDO A 20 AÑOS
	$credito = -$credito_cot;
	$numero_pago = 12 * 20;
	$interes = 4;
	
	$valor_uf_20 = PMT($interes / 1200, $numero_pago, $credito);
	$valor_uf_20_round = round($valor_uf_20, 2);
	$valor_uf_20_formato = number_format($valor_uf_20, 2, ',', '.');
	$valor_peso_20 = $valor_uf_20_formato * $uf_cot;
	$valor_peso_20_formato = number_format($valor_peso_20, 0, ',', '.');
	
	$valor_uf_20_formato_renta = $valor_uf_20_round * 4;
	$valor_peso_20_renta = $valor_uf_20_formato_renta * $uf_cot;
	$valor_peso_20_formato_renta = "$ ".number_format($valor_peso_20_renta, 0, ',', '.');
	$valor_uf_20_formato_renta = $valor_uf_20_formato_renta." UF";
	$valor_uf_20_formato = $valor_uf_20_formato;

	//------------------------------- CALCULO DIVIDENDO A 25 AÑOS
	$credito = -$credito_cot;
	$numero_pago = 12 * 25;
	$interes = 4;
	
	$valor_uf_25 = PMT($interes / 1200, $numero_pago, $credito);
	$valor_uf_25_round = round($valor_uf_25, 2);
	$valor_uf_25_formato = number_format($valor_uf_25, 2, ',', '.');
	$valor_peso_25 = $valor_uf_25_formato * $uf_cot;
	$valor_peso_25_formato = number_format($valor_peso_25, 0, ',', '.');
	
	$valor_uf_25_formato_renta = $valor_uf_25_round * 4;
	$valor_peso_25_renta = $valor_uf_25_formato_renta * $uf_cot;
	$valor_peso_25_formato_renta = "$ ".number_format($valor_peso_25_renta, 0, ',', '.');
	$valor_uf_25_formato_renta = $valor_uf_25_formato_renta." UF";
	$valor_uf_25_formato = $valor_uf_25_formato;
	
	//------------------------------- CALCULO DIVIDENDO A 30 AÑOS
	$credito = -$credito_cot;
	$numero_pago = 12 * 30;
	$interes = 4;
	
	$valor_uf_30 = PMT($interes / 1200, $numero_pago, $credito);
	$valor_uf_30_round = round($valor_uf_30, 2);
	$valor_uf_30_formato = number_format($valor_uf_30, 2, ',', '.');
	$valor_peso_30 = $valor_uf_30_formato * $uf_cot;
	$valor_peso_30_formato = number_format($valor_peso_30, 0, ',', '.');
	
	$valor_uf_30_formato_renta = $valor_uf_30_round * 4;
	$valor_peso_30_renta = $valor_uf_30_formato_renta * $uf_cot;
	$valor_peso_30_formato_renta = "$ ".number_format($valor_peso_30_renta, 0, ',', '.');
	$valor_uf_30_formato_renta = $valor_uf_30_formato_renta." UF";
	$valor_uf_30_formato = $valor_uf_30_formato;

	$html .= '
	<table class="caja-borde ancho-completo" style="margin-top: 10px">
		<tr>
			<td class="titulos td-borde-right" colspan="5">Simulación de Crédito Hipotecario</td>
			<td rowspan="7" style="width: 40%"></td>
		</tr>
		<tr>
			<td rowspan="2" class="td-borde td-min-padding" style="text-align: center">Plazo (años)</td>
			<td rowspan="2" class="td-borde td-min-padding" style="text-align: center">Tasa Anual (%)</td>
			<td colspan="2" class="td-borde td-min-padding" style="text-align: center">Dividendo Mensual</td>
			<td rowspan="2" class="td-borde td-min-padding" style="text-align: center">Renta Requerida</td>
		</tr>
		<tr>
			<td class="td-borde td-min-padding" style="text-align: center">(UF)</td>
			<td class="td-borde td-min-padding" style="text-align: center">($)</td>
		</tr>
		<tr>
			<td class="td-borde-right td-min-padding" style="text-align: center">15</td>
			<td class="td-borde-right td-min-padding" style="text-align: center">'.number_format($interes, 2, ',', '.').'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_uf_15_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_15_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_15_formato_renta.'</td>
		</tr>
		<tr>
			<td class="td-borde-right td-min-padding" style="text-align: center">20</td>
			<td class="td-borde-right td-min-padding" style="text-align: center">'.number_format($interes, 2, ',', '.').'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_uf_20_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_20_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_20_formato_renta.'</td>
		</tr>
		<tr>
			<td class="td-borde-right td-min-padding" style="text-align: center">25</td>
			<td class="td-borde-right td-min-padding" style="text-align: center">'.number_format($interes, 2, ',', '.').'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_uf_25_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_25_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_25_formato_renta.'</td>
		</tr>
		<tr>
			<td class="td-borde-right td-min-padding" style="text-align: center">30</td>
			<td class="td-borde-right td-min-padding" style="text-align: center">'.number_format($interes, 2, ',', '.').'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_uf_30_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_30_formato.'</td>
			<td class="td-borde-right td-min-padding text-right">'.$valor_peso_30_formato_renta.'</td>
		</tr>
	</table>

	<table class="caja-borde ancho-completo" style="margin-top: 22px">
		<tr>
			<td class="bold td-borde-right" style="width: 50%; line-height: 1.6rem">
				Atendido por: '.strtoupper($nombre_vendedor).'<br>
				Celular: '.utf8_encode($fono_vend).'<br>
				Email: '.utf8_encode($correo_vend).'
			</td>
			<td style="text-align: justify; padding: 5px 12px; font-size: 9px">
				* Validez de la cotización 5 días corridos a contar de esta fecha y no constituye reserva de compra.<br> 
				* El valor cancelado por concepto Reserva, será abonado a Pie.<br> 
				* Serán de cargo exclusivo del comprador, los gastos operacionales que genere la compraventa definitiva: Tasación; Estudio de Títulos; Confección de Escritura, Impuestos, Notaría y Conservador de Bienes Raíces.<br> 
				* La tasa de interés es sólo referencial. Las instituciones financieras cursan los créditos con las tasas vigentes a la fecha de escrituración. 
			</td>
		</tr>
	</table>
</body>
</html>';

if($id_sex==1){
	$estimado_text = "Estimado";
} else {
	$estimado_text = "Estimada";
}

$automatico="
    <table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
    <tr>
      <td align='left'><img src='https://www.icpacifico.cl/images/logo-top.jpg'></td>
    </tr>
    <tr>
      <td style='padding:10px; line-height:20px; font-size:13px;'>
      Estimado/a ".ucwords(mb_strtolower(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro))).",<br>Se adjunta Cotización Proyecto <b>".utf8_encode($nombre_con).".</b><br><br>
      <b>Vendedor: ".mb_strtoupper($nombre_vendedor)."</b>
      <br><br>
      </td>
    </tr>
    <tr height='28'>
      <td style='font-size:11px; background-color:#88c440; color:#EEE; text-align:center; border-radius: 10px'>Inmobiliaria Costanera Pacífico <a href='https://www.icpacifico.cl' target='_blank' style='color: #FFF'>www.icpacifico.cl</a></td>
    </tr>
    </table>
    ";

$mpdf = new mPDF('c','Letter'); 
// $mpdf->charset_in='UTF-8';
// $mpdf->allow_charset_conversion=true;
$mpdf->writeHTML($html);

ob_end_clean();//End Output Buffering

$emailAttachment = $mpdf->Output('cotizacion_ICP.pdf', 'S');

$mail_automatico = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail_automatico->CharSet = 'UTF-8';
$mail_automatico->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail_automatico->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail_automatico->Debugoutput = 'html';
//Set the hostname of the mail server
$mail_automatico->Host = 'smtp.gmail.com';
$mail_automatico->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail_automatico->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail_automatico->SMTPAuth = true;
//Username to use for SMTP authentication - use full email_automatico address for gmail_automatico
$mail_automatico->Username = "sociales@icpacifico.cl";
//Password to use for SMTP authentication
$mail_automatico->Password = "xjxenhjbncxefdmh";
//Set who the message is to be sent from
$mail_automatico->AddReplyTo(utf8_encode($correo_vend));
$mail_automatico->setFrom('sociales@icpacifico.cl', 'Envíos Inmob. Costanera Pacífico');

// $correo_pro = "brunomailcasa@gmail.com";
// $correo_empresa = "brunomailcasa@gmail.com";
// $correo_vend;

if ($correo_pro<>'') {
	$mail_automatico->AddAddress($correo_pro);
	$mail_automatico->AddCC($correo_vend);
	$mail_automatico->Subject = "Inmobiliaria Costanera Pacífico - Cotización ".utf8_encode($nombre_con);
	$mail_automatico->Body = $automatico;
	$mail_automatico->AddStringAttachment($emailAttachment, 'cotizacion_ICP.pdf', 'base64', 'application/pdf');
	$mail_automatico->Send();
	echo "correo enviado con éxito a ".$correo_pro."<br><br><b>Cerrar esta pestaña</b>";
} else {
	echo "No se pudo enviar el correo porque el cliente no registra email.";
}
// $mpdf->AddPage();
// $mpdf->WriteHTML($html2);
// $nombre = 'documentos/cotizacion_'.date('dmYHi').'.pdf';

// $fecha = date('Y-m-d H:i:s');
// $pdf = $mpdf->output($nombre ,'I');

?>