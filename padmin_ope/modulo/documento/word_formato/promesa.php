<?php
session_start();
require "../../../config.php"; 
include '../../../class/conexion.php';
$conexion = new conexion();

include _INCLUDE."class/letra.php";
$EnLetras = new EnLetras();


$hoy = date("d-m-Y");
$id_pro = $_GET["id_pro"];
$id_mod_form = $_GET["id_mod"];
$id_ven = $_GET["id_ven"];
$id_personeria = $_GET["per"];

require_once dirname(__FILE__).'/PHPWord-master/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

use PhpOffice\PhpWord\TemplateProcessor;


// $nombre = "Sandra S.L.";
// $direccion = "Mi dirección";
// $municipio = "Mrd";
// $provincia = "Bdj";
// $cp = "02541";
// $telefono = "24536784";
$templateWord = new TemplateProcessor('promesa_'.$id_pro.'_'.$id_mod_form.'.docx');

$consulta = 
	"
	SELECT 
		con.id_con, 
		pro.nombre_pro, 
		pro.nombre2_pro, 
		pro.apellido_paterno_pro, 
		pro.apellido_materno_pro,
		pro.rut_pro,
		pro.direccion_pro,
		pro.direccion_trabajo_pro,
		pro.fono_pro,
		pro.correo_pro,
		pro.id_sex,
		prof.nombre_prof,
		civ.nombre_civ,
		com.nombre_com,
		reg.descripcion_reg,
		con.nombre_con, 
		viv.nombre_viv,
		viv.id_viv,
		viv.id_mod,
		viv.prorrateo_viv,
		viv.metro_total_viv,
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
		mode.nombre_mod,
		pro.profesion_promesa_pro
	FROM 
		venta_venta AS ven
		INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		INNER JOIN civil_civil AS civ ON civ.id_civ = pro.id_civ
		INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
		INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
		INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
		INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
	WHERE 
		ven.id_ven = ?
	";
$conexion->consulta_form($consulta,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$id_con = $fila["id_con"];
$nombre_pro = $fila["nombre_pro"];
$nombre2_pro = $fila["nombre2_pro"];
$apellido_paterno_pro = $fila["apellido_paterno_pro"];
$apellido_materno_pro = $fila["apellido_materno_pro"];
$rut_pro = $fila["rut_pro"];
$id_sex = $fila["id_sex"];
$nombre_civ = utf8_encode($fila["nombre_civ"]);
$nombre_prof = utf8_encode($fila["nombre_prof"]);
$nombre_com = utf8_encode($fila["nombre_com"]);
$descripcion_reg = utf8_encode($fila["descripcion_reg"]);
$direccion_pro = utf8_encode($fila["direccion_pro"]);
$direccion_trabajo_pro = utf8_encode($fila["direccion_trabajo_pro"]);
$profesion_promesa_pro = utf8_encode($fila["profesion_promesa_pro"]);

$nombre_mod = utf8_encode($fila["nombre_mod"]);

$nombre_con = $fila["nombre_con"];
$fecha_promesa_ven = $fila["fecha_ven"];
$nombre_viv = $fila["nombre_viv"];
$id_viv = $fila["id_viv"];
$id_mod = $fila["id_mod"];
$prorrateo_viv = $fila["prorrateo_viv"];
$metro_total_viv = $fila["metro_total_viv"];
$metro_total_viv_formato = number_format($metro_total_viv, 2, ',', '.');
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

if($id_sex==1) {
		$don_text = "Don(ña)";
	} else {
		$don_text = "Don(ña)";
	}

// $text_mod_1 = "el DEPARTAMENTO TIPO ".$nombre_mod;
// $text_mod_2 = "";

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

$nombre_doc_tit = utf8_encode($nombre_pro."_".$apellido_paterno_pro."_".$apellido_materno_pro);

$nombre_proyecto = strtoupper(utf8_encode($nombre_con));

$titular_promesa = strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));

$fecha_promesa = utf8_encode($dia)." de ".utf8_encode($nombre_mes)." de ".utf8_encode($anio);

$datos_titular = strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro)).", cédula nacional de identidad número ".$rut_pro.", estado civil ".$nombre_civ.", de profesión u oficio ".$profesion_promesa_pro.", con domicilio en ".$direccion_pro.", comuna de ".$nombre_com.", ".$descripcion_reg.", número de celular ".$fono_pro.", correo electrónico ".$correo_pro.", lugar de trabajo ".$direccion_trabajo_pro;

if($id_mod_form == 1) {
	$depto_detalle = $don_text." ".strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro)).", quien promete comprar, aceptar y adquirir para sí, el DEPARTAMENTO TIPO ".$nombre_mod." NÚMERO ".$nombre_viv.", BODEGA ".utf8_encode($nombre_bod).utf8_encode($nombre_bod_extra).", ESTACIONAMIENTO ".utf8_encode($nombre_esta).utf8_encode($nombre_esta_extra);
} else {
	$depto_detalle = $don_text." ".strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro)).", quien promete comprar, aceptar y adquirir para sí, la OFICINA NÚMERO ".$nombre_viv." , BODEGA ".utf8_encode($nombre_bod).utf8_encode($nombre_bod_extra).", ESTACIONAMIENTO ".utf8_encode($nombre_esta).utf8_encode($nombre_esta_extra);
}




$valor_depto = number_format($monto_ven, 2, ',', '.');

$forma_pagoA = "";
// pagos tipo cierre de negocio
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
        pag.id_cat_pag = 1
    ";
$conexion->consulta_form($consulta,array($id_ven));
$cantidad_pag_cie = $conexion->total();
$fila_consulta = $conexion->extraer_registro();
$cont_pag_cie = 0;
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

        $forma_pagoA .= utf8_encode($fila["nombre_for_pag"]." ");

        if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
			$forma_pagoA .= "";
		} else {
			$forma_pagoA .= utf8_encode($fila["nombre_ban"]);
		}
		$cont_pag_cie ++;
		$forma_pagoA .= " N° ".$fila["numero_documento_pag"];

		if ($fila["id_for_pag"]==6) {
		} else {
			$forma_pagoA .= " de fecha ".date("d/m/Y",strtotime($fila["fecha_pag"]));
		}

		$monto_pago_letra = $EnLetras->ValorEnLetras($pago_pesos,"Pesos");

		$forma_pagoA .= " por un monto de";
		if ($fila["id_for_pag"]==6) {
		} else {
			$forma_pagoA .= " $ ".number_format($pago_pesos, 0, ',', '.')."(".utf8_decode($monto_pago_letra).")";
		}

		if($cont_pag_cie < $cantidad_pag_cie) {
			$forma_pagoA .= ", ";
		}
	}
}

// suma y busca los detalle PIE
$forma_pagoB = "";
// $consulta_total_pie = "
// 	SELECT
//         SUM(pag.monto_pag) AS TotalPagoPie
//     FROM
//         pago_pago AS pag 
//     WHERE
//     	pag.id_ven = ? AND
//         pag.id_cat_pag = 2
//         ";
// $conexion->consulta_form($consulta_total_pie,array($id_ven));
// $filatot_pie = $conexion->extraer_registro_unico();
// $TotalPagoPie = $filatot_pie["TotalPagoPie"];
// $total_pie_uf = $TotalPagoPie / $valor_uf;

$total_pie_uf = $monto_pie_ven;

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
        pag.id_cat_pag = 2
    ";
$conexion->consulta_form($consulta,array($id_ven));
$cantidad_pag_pie = $conexion->total();
$fila_consulta = $conexion->extraer_registro();
$cont_pag_pie = 0;
$forma_pagoB .= number_format($total_pie_uf, 2, ',', '.')." Unidades de Fomento que se pagarán según detalle siguiente, ";
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

        $forma_pagoB .= $fila["nombre_for_pag"]." ";
        if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
			$forma_pagoB .= "";
		} else {
			$forma_pagoB .= $fila["nombre_ban"];
			$forma_pagoB .= " N° ".$fila["numero_documento_pag"];
		}
		$cont_pag_pie ++;
		

		if ($fila["id_for_pag"]==6) {
		} else {
			$forma_pagoB .= " de fecha ".date("d/m/Y",strtotime($fila["fecha_pag"]));
		}

		$monto_pago_letra = $EnLetras->ValorEnLetras($pago_pesos,"Pesos");

		
		if ($fila["id_for_pag"]==6) {
			$forma_pagoB .= " por un monto equivalente a ".$fila["monto_pag"]." Unidades de Fomento";
		} else {
			$forma_pagoB .= " por un monto de";
			$forma_pagoB .= " $ ".number_format($pago_pesos, 0, ',', '.')."(".utf8_decode($monto_pago_letra).")";
		}

		if($cont_pag_pie < $cantidad_pag_pie) {
			$forma_pagoB .= ", ";
		}
	}
}

$saldo_cred_cont = $monto_ven - 10 - $total_pie_uf;
$saldo_restante = number_format($saldo_cred_cont, 2, ',', '.');
// --- Asignamos valores a la plantilla

if ($id_personeria == 1) {
	$personeria = "don Sebastián Rodrigo Araya Varela";
	$firma_inmobiliaria = "SEBASTIAN ARAYA VARELA";
	$rut_firma_inmobiliaria = "11.610.180-7";
	$texto_cabecera_personeria = "don SEBASTIAN RODRIGO ARAYA VARELA, chileno, casado, arquitecto, cédula nacional de identidad número once millones seiscientos diez mil ciento ochenta guion siete";
} else {
	$personeria = "doña Cecilia Margarita Debia García";
	$firma_inmobiliaria = "CECILIA MARGARITA DEBIA GARCÍA";
	$rut_firma_inmobiliaria = "5.966.959-1";
	$texto_cabecera_personeria = "doña CECILIA MARGARITA DEBIA GARCÍA, chilena, soltera, técnico en construcción, cédula de identidad número cinco millones novecientos sesenta y seis mil novecientos cincuenta y nueve guion uno";
}

$rut_titular = $rut_pro;


$templateWord->setValue('nombre_proyecto',$nombre_proyecto);
$templateWord->setValue('texto_cabecera_personeria',$texto_cabecera_personeria);
$templateWord->setValue('titular_promesa',$titular_promesa);
$templateWord->setValue('fecha_promesa',$fecha_promesa);
$templateWord->setValue('datos_titular',$datos_titular);
$templateWord->setValue('depto_detalle',$depto_detalle);
$templateWord->setValue('valor_depto',$valor_depto);
$templateWord->setValue('forma_pagoA',$forma_pagoA);
$templateWord->setValue('forma_pagoB',$forma_pagoB);
$templateWord->setValue('saldo_restante',$saldo_restante);
$templateWord->setValue('personeria',$personeria);
$templateWord->setValue('rut_titular',$rut_titular);
$templateWord->setValue('firma_inmobiliaria',$firma_inmobiliaria);
$templateWord->setValue('rut_firma_inmobiliaria',$rut_firma_inmobiliaria);



// --- Guardamos el documento
$templateWord->saveAs('promesa/promesa_'.$nombre_doc_tit.'.docx');

header("Content-Disposition: attachment; filename=promesa.docx; charset=iso-8859-1");
echo file_get_contents('promesa/promesa_'.$nombre_doc_tit.'.docx');
        
?>