<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

include _INCLUDE."class/letra.php";
$EnLetras = new EnLetras();

function d64($dato)
	{
		$decode = base64_decode(base64_decode(base64_decode($dato)));
		 return $decode;
	}

$id = d64($_GET["id"]);
$opc = $_GET["opc"];
$pie = $_GET["pie"];
// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

if($opc==1) {
	$personeria = "don SEBASTIÁN RODRIGO ARAYA VARELA";
	$texto_cabecera_personeria = "don <b>SEBASTIAN RODRIGO ARAYA VARELA</b>, chileno, casado, arquitecto, cédula nacional de identidad número once millones seiscientos diez mil ciento ochenta guion siete";
} else {
	$personeria = "doña CECILIA MARGARITA DEBIA GARCÍA ";
	$texto_cabecera_personeria = "doña <b>CECILIA MARGARITA DEBIA GARCÍA</b>, chilena, soltera, técnico en construcción, cédula de identidad número cinco millones novecientos sesenta y seis mil novecientos cincuenta y nueve guion uno";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Promesa Compra Venta</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 13px;

    	}
        .sin-borde{
			width: 92%;
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
	<a class="btn no-print" href="promesa_venta_depto_8_pdf.php?id=<?php echo $_GET["id"]; ?>&opc=<?php echo $opc; ?>&pie=<?php echo $pie; ?>" target="_blank">PDF</a>
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
	$conexion->consulta_form($consulta,array($id));

	$hay_registro = $conexion->total();

	if ($hay_registro == 0) {
		echo "FALTAN DATOS PARA LA PROMESA";
		exit;
	}

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
	$fono_pro = utf8_encode($fila["fono_pro"]);
	$correo_pro = utf8_encode($fila["correo_pro"]);
	$profesion_promesa_pro = utf8_encode($fila["profesion_promesa_pro"]);

	$nombre_mod = utf8_encode($fila["nombre_mod"]);

	$nombre_con = $fila["nombre_con"];
	$fecha_promesa_ven = $fila["fecha_ven"];
	$nombre_viv = $fila["nombre_viv"];
	$id_viv = $fila["id_viv"];

	$id_mod = $fila["id_mod"];
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

	if($id_sex==1) {
		$don_text = "Don(ña)";
	} else {
		$don_text = "Don(ña)";
	}

	$text_mod_1 = "el <b>DEPARTAMENTO TIPO ".$nombre_mod;
	$text_mod_2 = "";

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
	    		<h3 style="text-align: center">PROMESA DE COMPRAVENTA<br><?php echo strtoupper(utf8_encode($nombre_con));?></h3>
	    		<br>
	    		<h3 style="text-align: center">INMOBILIARIA COSTANERA PACIFICO S.P.A<br>Y<br><?php echo strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?></h3>

	    		<div style="text-align: justify; line-height: 24px">
	    			<p>En La Serena, a <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> de <?php echo utf8_encode($anio);?>, entre <b>"INMOBILIARIA COSTANERA PACÍFICO SpA."</b>, persona jurídica de derecho privado, del giro de su denominación, rol único tributario número setenta y seis millones ochocientos sesenta y seis mil setenta y cinco guión uno, representada legalmente, según se acreditará, por <?php echo $texto_cabecera_personeria; ?>, con domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la <b>"Promitente Vendedora" y/o "La Inmobiliaria",</b> y por la otra parte don (ña) <?php echo strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?>, cédula nacional de identidad número <?php echo $rut_pro;?>, estado civil <?php echo $nombre_civ; ?>, de profesión u oficio <?php echo $profesion_promesa_pro; ?>, con domicilio en <?php echo $direccion_pro; ?>, comuna de <?php echo $nombre_com; ?>, <?php echo $descripcion_reg; ?>, número de celular <?php echo $fono_pro; ?>, correo electrónico <?php echo $correo_pro; ?>, lugar de trabajo <?php echo $direccion_trabajo_pro; ?>. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente:
	    			<b><u>PRIMERO: Declaración de dominio.</u> INMOBILIARIA COSTANERA PACÍFICO SpA,</b> declara ser dueña plena, exclusiva y excluyente del siguiente bien inmueble: <b>LOTE UNO D UNO A</b> resultante de la subdivisión del Lote Uno D Uno, resultante a su vez de la subdivisión del Lote Uno D, resultante de la subdivisión del denominado Lote Número Uno, que resultó de la fusión entre el inmueble que se singulariza como Resto No Enajenado del Lote A, resultante de la subdivisión del Lote A, que a su vez resultó de la subdivisión del predio denominado Chacra Club Hípico, comuna de La Serena y del inmueble ubicado en Avenida Francisco de Aguirre número cero doscientos cuarenta y uno antigua numeración cero ciento cincuenta y nueve, singularizado en el plano agregado bajo el número mil quinientos sesenta al final del Registro de propiedad del Conservador de Bienes Raíces de La Serena, del año dos mil veintiuno. Según el citado plano, dicho lote se encuentra encerrado en el polígono Z-AB-AC-AD-AE-AG-AH-Z y tiene una superficie aproximada de veinte mil uno coma treinta metros cuadrados y los siguientes deslindes especiales: <b>AL NORTE</b>, , en tramo Z-AB en ciento veinte coma treinta metros con Otros Propietarios, en línea sinuosa en tramo AB-AC en treinta y dos coma treinta metros con Otros Propietarios; al <b>ESTE</b>, tramo Z-AH en ochenta y nueve coma sesenta metros con Otros Propietarios; al <b>SUR</b>, en tramo AH-AG en doscientos veintiuno coma ochenta metros con Lote Uno D Uno B; al <b>OESTE</b>, en tramo AG-AE en once coma treinta metros con Cesión B.N.U.P. número Uno, tramo AE-AD en cincuenta y siete coma cuarenta metros con calle Emilio Apey - Calle Treinta y nueve; y al <b>NOROESTE</b>, en tramo AC-AD en ciento ocho coma cuarenta metros con Otros Propietarios. <b>INMOBILIARIA COSTANERA PACÍFICO SpA</b>, adquirió el inmueble por compra que hiciera a INMOBILIARIA OVCO LIMITADA, según consta en la escritura pública de fecha primero de Septiembre del año dos mil veintiuno, suscrita en la Notaría de La Serena de don Carlos Galleguillos Carvajal. <b>El título de dominio a su nombre se encuentra inscrito a fojas nueve mil cuatrocientos cuarenta y cinco, número seis mil doscientos veinticinco, en el Registro de Propiedad del Conservador de Bienes Raíces de La Serena, del año dos mil veintiuno.</b> Declara, finalmente, que el rol de avalúo para efectos del pago del impuesto territorial que lo grava conforme a la ley diecisiete mil doscientos treinta y cinco, es el número <b>trece guion quinientos quince</b> de la comuna de La Serena.
	    			<b><u>SEGUNDO: De la singularización del proyecto.</u></b> En el inmueble singularizado precedentemente, <b>INMOBILIARIA COSTANERA PACIFICO SpA</b>, ha proyectado la construcción denominada <b>“Condominio Distrito Verde”</b>, el que constará de ocho edificios, sumando un total de doscientos cuarenta departamentos, compuesto por CUATRO etapas. La Primera Etapa consistente en Dos edificios o torres, encomendando la construcción de los mismos a <b>CONSTRUCTORA DEL MAR II S.p.A</b>, siendo sus especificaciones técnicas las que siguen: 
					Un Edificio de seis pisos con treinta departamentos cada uno y Un Edificio de seis pisos más subterráneo con treinta departamentos cada uno, sumando un total de sesenta unidades, de los cuales veinticuatro son tipo “A” (dos dormitorios, dos baños), veinticuatro  son tipo “B” (dos dormitorios, un baño) y doce son de  tipo “C” (un dormitorio, un baño), además cuentan con una bodega por unidad, ubicadas en el subterráneo del  edificio Tres. El Condominio tendrá espacios comunes compuestos de dos salas de coworking y salón gourmet, quinchos al aire libre, lavandería, sala de basura y portería. Todas las unidades poseerán un estacionamiento, los cuales estarán a nivel de suelo. Los departamentos del referido edificio se acogerán al Decreto con Fuerza de Ley número Dos, del año mil novecientos cincuenta y nueve, su Reglamento y posteriores modificaciones. La referida construcción se hará de acuerdo con los planos y especificaciones técnicas del arquitecto Sebastián Araya Varela, los que el Promitente Comprador declara conocer y aceptar, quien además autoriza al arquitecto antes señalado, para realizar las adecuaciones y mejoras al proyecto, así como las especificaciones que considere necesarias. El aludido Proyecto Habitacional se denomina <b>“CONDOMINIO DISTRITO VERDE”</b> y estará acogido a los beneficios de la Ley diecinueve mil quinientos treinta y siete sobre Copropiedad Inmobiliaria y al Decreto con Fuerza de Ley número dos del año mil novecientos cincuenta y nueve, su reglamento y posteriores modificaciones. El permiso de edificación rola con el número Ciento noventa y tres y fue otorgado por la Dirección de Obras Municipales de la Ilustre Municipalidad de La Serena con fecha veintitrés de Noviembre del año dos mil veintiuno, el cual se redujo a escritura pública en la Tercera Notaría de La Serena de don Carlos Galleguillos Carvajal, con fecha dieciséis de diciembre de dos mil uno. 
	    			<b><u>TERCERO: Del consentimiento.</u></b> Por este acto e instrumento, <b>INMOBILIARIA COSTANERA PACIFICO S.p.A.</b>, representada en la forma señalada en la comparecencia, promete vender, ceder y transferir a <b><?php echo $don_text; ?> <?php echo strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?></b>, quien promete comprar, aceptar y adquirir para sí, 
	    			<?php echo $text_mod_1; ?> NÚMERO <?php echo $nombre_viv; ?>, BODEGA <?php echo utf8_encode($nombre_bod).utf8_encode($nombre_bod_extra);?>, ESTACIONAMIENTO <?php echo utf8_encode($nombre_esta).utf8_encode($nombre_esta_extra);?></b>, del proyecto <b><u><?php echo strtoupper(utf8_encode($nombre_con));?></u></b>, más la cuota correspondiente en los bienes comunes, tales como ascensores, pasillos, etc., y, especialmente, en el terreno en que el edificio se construye; singularizado en los planos del edificio precedentemente señalado. A su turno, el Promitente Comprador declara, expresamente, conocer el proyecto, los planos y las especificaciones técnicas del edificio, con sus modificaciones existentes a la fecha, expresando que lo que se obligará a comprar, es el departamento señalado precedentemente, en la forma y condiciones que tales antecedentes lo configuran. 
	    			<b><u>CUARTO:</u></b> El precio de la compraventa será la suma total de <b><?php echo number_format($monto_ven, 2, ',', '.');?></b> Unidades de Fomento, Iva incluido, que se pagarán de la siguiente forma: <b>A)</b> en este acto, con la  suma de <b>
	    			<?php 
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
                            pag.id_cat_pag = 1 ORDER BY
                            pag.fecha_pag ASC, pag.numero_documento_pag ASC
                        ";
                    $conexion->consulta_form($consulta,array($id));
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
                            ?>
                            <?php echo utf8_encode($fila["nombre_for_pag"]." ");

                            if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
								echo "";
							} else {
								echo utf8_encode($fila["nombre_ban"]);
							}
							$cont_pag_cie ++;
							echo " N° ".$fila["numero_documento_pag"];

							if ($fila["id_for_pag"]==6) {
							} else {
								echo " de fecha ".date("d/m/Y",strtotime($fila["fecha_pag"]));
							}

							$monto_pago_letra = $EnLetras->ValorEnLetras($pago_pesos,"Pesos");

							echo " por un monto de";
							if ($fila["id_for_pag"]==6) {
							} else {
								?>
								$<?php echo number_format($pago_pesos, 0, ',', '.');
								echo "(".$monto_pago_letra.")";
							}

							if($cont_pag_cie < $cantidad_pag_cie) {
								echo ", ";
							}
						}
					}
					?>
					</b> equivalente al día de hoy a  <b>10,00</b> Unidades de Fomento, que la promitente vendedora declara recibir plenamente conforme.<b>B)

					<?php 
					// suma y busca los detalle PIE
					// $consulta_total_pie = "
					// 	SELECT
     //                        SUM(pag.monto_pag) AS TotalPagoPie
     //                    FROM
     //                        pago_pago AS pag 
     //                    WHERE
     //                    	pag.id_ven = ? AND
     //                        pag.id_cat_pag = 2
     //                        ";
     //                $conexion->consulta_form($consulta_total_pie,array($id));
     //                $filatot_pie = $conexion->extraer_registro_unico();
     //                $TotalPagoPie = $filatot_pie["TotalPagoPie"];
     //                $total_pie_uf = $TotalPagoPie / $valor_uf;

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
                            pag.id_cat_pag = 2 ORDER BY
                            pag.fecha_pag ASC, pag.numero_documento_pag ASC
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $cantidad_pag_pie = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $cont_pag_pie = 0;
                    echo number_format($total_pie_uf, 2, ',', '.')." Unidades de Fomento que se pagarán según detalle siguiente, ";
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
                            <?php echo utf8_encode($fila["nombre_for_pag"]." ");
                            if ($fila["id_for_pag"]==6 || $fila["id_for_pag"]==2) {
								echo "";
							} else {
								echo utf8_encode($fila["nombre_ban"]);
								echo " N° ".$fila["numero_documento_pag"];
							}
							$cont_pag_pie ++;
							

							if ($fila["id_for_pag"]==6) {
							} else {
								echo " de fecha ".date("d/m/Y",strtotime($fila["fecha_pag"]));
							}

							$monto_pago_letra = $EnLetras->ValorEnLetras($pago_pesos,"Pesos");

							
							if ($fila["id_for_pag"]==6) {
								echo "por un monto equivalente a ".$fila["monto_pag"]." Unidades de Fomento";
							} else {
								echo " por un monto de";
								?>
								$<?php echo number_format($pago_pesos, 0, ',', '.');
								echo "(".$monto_pago_letra.")";
							}

							if($cont_pag_pie < $cantidad_pag_pie) {
								echo ", ";
							}
						}
					}
					echo ".";
					$saldo_cred_cont = $monto_ven - 10 - $total_pie_uf;
					?>
					</b><b>C)</b> El saldo restante, equivalente a <b><?php echo number_format($saldo_cred_cont, 2, ',', '.'); ?></b> Unidades de Fomento, se pagará al momento de celebrarse el contrato de Compraventa Prometido, suma que será pagada mediante Crédito Hipotecario o de Contado. Las sumas entregadas por el Promitente Comprador, referidas en los literales a), b) y c) de la presente cláusula, se imputarán al precio total, tomándose como criterio de la mencionada imputación, las sumas convertidas a Unidades de Fomento según valor de ésta al momento del <b>COBRO EFECTIVO</b> de los documentos entregados por el Promitente Comprador. Todos los pagos anticipados serán garantizados con póliza de Avla Seguros de Crédito Y Garantía S.A.
					<b><u>QUINTO: Reconocimiento.</u></b> Los comparecientes reconocen estar en pleno conocimiento de que la Venta Prometida en el presente instrumento se encuentra regulada por la Ley número veinte mil setecientos ochenta que modifica al Decreto ley número ochocientos veinticinco, Ley del Impuesto al Valor Agregado, en virtud de la cual, a partir del primero de enero del año dos mil dieciséis, todas las ventas de inmuebles, nuevos o usados, que sean efectuadas por un vendedor habitual se encuentran gravadas con el Impuesto al Valor Agregado. 
					<b><u>SEXTO: Declaración.</u></b> Los contratantes convienen y declaran en que en caso de modificarse la base actual de cálculo de la Unidad de Fomento o de suspenderse su aplicación, se aplicará en su lugar, el Índice de Precio al Consumidor, vigente entre la fecha de este contrato y la fecha del pago efectivo.
					<b><u>SÉPTIMO: Financiamiento bancario.</u></b> En el caso de mediar un crédito hipotecario u otro tipo de financiamiento bancario, el Promitente Comprador será quien gestione su crédito hipotecario ante el Banco de su elección, obligándose a proporcionar con la antelación de dos meses a la fecha de la entrega del proyecto <b><?php echo strtoupper(utf8_encode($nombre_con));?></b>, todos los antecedentes requeridos para tal operación. Con todo, en el evento que el Promitente Comprador no realice el pago convenido en la cláusula cuarta de este contrato, o sin motivo alguno no contribuya al acceso de la información requerida por la Promitente Vendedora, o no sea sujeto de crédito de acuerdo a las exigencias de las entidades de financiamiento, dentro del plazo de treinta días a la fecha de la Recepción Municipal, la Promitente Vendedora podrá declarar ipso facto resuelto este contrato, pudiendo en tal caso disponer inmediatamente de la propiedad objeto de esta Promesa en la forma que estime conveniente. En tal evento, la Promitente Vendedora, notificará su decisión de resolver este contrato al Promitente Comprador por los medios que estime conveniente, tales como correo electrónico, carta certificada, notificación personal, etc., manifestando la determinación de la sociedad vendedora, <b>INMOBILIARIA COSTANERA PACÍFICO SpA</b>, de declarar terminado el contrato, notificación que por sí sola producirá todos los efectos legales y contractuales, aun cuando el Promitente Comprador no se encuentre en el domicilio señalado en la comparecencia, o estuviere ausente del país. Declarada la resolución del contrato de Promesa de Compraventa, según se expresó precedentemente, el Promitente Comprador, a vía de pena, incurrirá en una multa en las mismas condiciones que se señala en la cláusula undécima de este contrato. 
					<b><u>OCTAVO: De la tramitación del Financiamiento Bancario:</u></b> Queda expresamente estipulado en este acto que toda tramitación del crédito con garantía hipotecaria o de obtención de subsidio habitacional otorgado por el Estado de Chile, es de única y exclusiva responsabilidad del Promitente Comprador. Por consiguiente, si el Promitente Comprador no obtuviere dicho crédito y/o subsidio, o si se le otorgare con demora en relación a los plazos establecidos en esta Promesa, tales circunstancias serán de su absoluta y exclusiva responsabilidad, no habiendo en consecuencia responsabilidad alguna de la inmobiliaria en este sentido. 
					<b><u>NOVENO:</u></b> En el caso de que la forma de pago sea a través de pies u adelantos de dinero a plazos, o sea en Subsidio u Ahorro, o en el caso de que sea tramitado el Subsidio y lo emplee en otro proyecto, queda establecido que cualquier incumplimiento de los vencimientos de los documentos en custodia o adelantos comprometidos según el presente instrumento, quedará sujeto a las sanciones establecidas en la cláusula Séptimo. 
					<b><u>DÉCIMO: Suscripción de Compraventa Definitiva.</u></b> El contrato definitivo de Compraventa, se otorgará en la Notaría que señale la Promitente Vendedora dentro de los sesenta días siguientes a la fecha de la Recepción Municipal definitiva del departamento que se ha prometido vender. Este plazo será prorrogable en los días que correspondan para la firma de la Compraventa Prometida, según lo estime la parte vendedora, de lo contrario se resuelve el contrato según lo estipulado en la cláusula siguiente.
					<b><u>UNDÉCIMO: Cláusula penal.</u></b> Si el Promitente Comprador se desistiera de la Promesa de Compraventa por causas que le son imputables, o  en el caso que no se pudiera llevar a efecto en la forma estipulada o si por cualquier circunstancia  incurriera  en  simple  retardo de suscribir la escritura de Compraventa Definitiva, deberá pagar <b>cincuenta Unidades de Fomento</b>, todo ello a modo de pena o multa, y como avaluación anticipada de los perjuicios causados por el incumplimiento, quedando automáticamente resuelta esta Promesa de Compraventa, sin responsabilidad alguna para la Promitente Vendedora. A su turno, la Promitente Vendedora se obliga a devolver el dinero entregado a cuenta del precio sin interés alguno, deducida la multa señalada precedentemente; ello, dentro del plazo de noventa días, contados desde la resolución del contrato y el correspondiente finiquito. Asimismo, si la Promitente vendedora no concretara el proyecto inmobiliario en los términos señalados en la cláusula precedente deberá pagar <b>cincuenta Unidades de Fomento</b>, todo ello a modo de pena o multa, y como avaluación anticipada de los perjuicios causados por el incumplimiento, quedando automáticamente resuelta esta Promesa de Compraventa, sin responsabilidad alguna para la Promitente Compradora. 
					<b><u>DOUDÉCIMO: Póliza de seguro.</u></b> Con el objeto de garantizar el cumplimiento del presente contrato por parte del Promitente Vendedor y de acuerdo con lo establecido en el artículo ciento treinta y ocho bis de la Ley General de Urbanismo y Construcciones y en la cláusula cuarta de este instrumento, la Promitente Vendedora ha contratado en favor de don (ña)
					<b><?php echo strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?></b>, ya individualizado en la comparecencia, en calidad de beneficiario, un seguro equivalente a las sumas entregadas. El presente seguro, se mantendrá vigente hasta el día de la inscripción del inmueble individualizado en la cláusula segunda precedente a nombre del Promitente Comprador en el Registro de Propiedad del Conservador de Bienes Raíces de La Serena. 
					<b><u>DÉCIMO TERCERO: De la entrega material de la cosa que se promete vender.</u></b> La entrega material de la cosa prometida se hará a la Promitente Compradora al momento de otorgarse la escritura de Compraventa Definitiva, en forma simbólica, en la forma prevista en el artículo seiscientos ochenta y cuatro número uno del Código Civil, permitiéndole la promitente vendedora a la promitente compradora, la aprehensión material de la cosa vendida. Asimismo, la Promitente Vendedora, comunica en este acto y mediante este instrumento a la Promitente Compradora que el departamento que se vende, se estima que estará terminado el <b>primer trimestre del año dos mil veintitrés</b>, debiendo estar a esa fecha los saldos de precio debidamente cancelados.
					<b><u>DÉCIMO CUARTO: De los Gastos.</u></b> Los gastos, impuestos e inscripciones originados por el otorgamiento de esta Promesa de Compraventa, serán de cargo del vendedor, así como los que deriven del contrato de Compraventa Prometido, serán de cargo del Promitente Comprador.
					<b><u>DÉCIMO QUINTO: De la forma en que se hará la venta y otras declaraciones de las partes.</u></b> La venta se hará considerando la cosa prometida como especie o cuerpo cierto, en el estado que indican sus especificaciones técnicas ya referidas, que son conocidas y aprobadas por el Promitente Comprador; y con similares terminaciones al departamento piloto; con todos sus usos y derechos, costumbres, servidumbres activas y pasivas, con todas sus instalaciones, edificaciones y plantaciones, respondiendo el Promitente Vendedor del saneamiento de la evicción en conformidad a la ley, libre de todo gravamen, litigio, prohibición, embargo o expropiación que la pudiere afectar, excepto en lo que dice relación con el Reglamento de Copropiedad del edificio, el que será dictado por el Promitente Vendedor. Se deja expresa constancia que si la Ilustre Municipalidad de La Serena, al extender el Certificado de Autorización de Venta por Pisos y Departamentos, permitiere sólo la asignación del uso y goce de él o de los estacionamientos para automóviles materia del presente contrato, se entenderá que la Promitente Vendedora ha cumplido a cabalidad las obligaciones que le impone el presente contrato y el prometido, en la medida en que se ceda, al Promitente Comprador, el uso y goce de tal o tales estacionamientos, aun cuando el terreno en el que se encuentren situados fuere declarado por la Municipalidad como bien común del Edificio. 
					<b><u>DÉCIMO SEXTO: Prórroga de competencia.</u></b> Para todos los efectos legales, las partes fijan y fijarán domicilio en la ciudad y comuna de La Serena y se someterán a la jurisdicción y competencia de sus tribunales ordinarios de justicia. 
					<b><u>DÉCIMO SÉPTIMO: Mandato.</u></b> La Promitente Compradora ya singularizada en este acto, viene en otorgar al representante de la sociedad <b>INMOBILIARIA COSTANERA PACÍFICO SpA</b>, ya individualizado en la comparecencia de este acto, un mandato irrevocable, a fin de que en su nombre y representación proceda a suscribir la pertinente escritura pública de resciliación de esta Promesa de Compraventa en el caso de contravención de las obligaciones emanadas de este instrumento, o del incumplimiento del vendedor de los actos u hechos determinados por la parte vendedora, sin perjuicios de las sanciones establecidas en la cláusula  Undécima.
					<b><u>DÉCIMO OCTAVO: Facultades.</u></b> El (la) promitente comprador (ra) otorga mandato especial irrevocable, en los términos expuestos en el artículo mil quinientos veintiocho del Código Civil, a don Sebastián Rodrigo Araya Varela y a doña Cecilia Margarita Debia García, para que en forma separada e indistintamente, en su nombre y representación, aclaren, complementen, rectifiquen o enmienden el presente contrato de promesa de compraventa sin alterar sus elementos esenciales, respecto de cualquier error u omisión de que adoleciere a objeto de obtener la total legalización de la propiedad que mediante este instrumento se promete vender. Para tal efecto los mandatarios quedan facultados para firmar todos los instrumentos privados o escrituras públicas que se requieran con el fin antes señalado. 
					<b><u>DÉCIMO NOVENO. Del cierre de negocios.</u></b>  Se considera parte integrante del presente contrato, el cierre de negocios celebrado entre las partes promitentes de la presente escritura, dejando expresa constancia que en el evento de que existiese la modalidad de pago en cuotas, el no pago oportuno de éstas, darán la facultad a la sociedad para dejar sin efecto el referido cierre de negocios, y, por ende, de manera ipso facto, la presente Promesa de Compraventa, con las sanciones establecidas en la cláusula Undécima de este instrumento. <b><u>LA PERSONERÍA</u></b> que habilita a <?php echo $personeria; ?> para representar a <b>INMOBILIARIA COSTANERA PACÍFICO SpA</b> consta en escritura pública de fecha siete de Septiembre del año dos mil dieciocho, otorgada ante la Notario Público de La Serena doña Elena Leyton Carvajal, documento que no se inserta por ser conocido del Notario autorizante, de las partes y a expresa solicitud de ellas. En comprobante, y previa lectura, la otorgan 
					<?php 
					if ($pie==1) {
					 ?>
					y firman ante mí
				<?php } ?>
					. Se da copia. - DOY FE.-
	    		</div>

				
				<br><br><br><br><br><br>
				<?php 
					if ($pie==1) {
					 ?>
				<div style="display: flex; justify-content: space-between; align-items: start;">
					<table style="width: 30%; text-align: center;">
						<tr>
							<td class="borde-top">
								<?php echo strtoupper(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro));?><br>RUT: <?php echo $rut_pro; ?>
							</td>
						</tr>
					</table>
					<table style="width: 30%; text-align: center;">
						<tr>
							<td class="borde-top">
								<?php 
								if ($opc==1) {
								?>
								SEBASTIÁN ARAYA VARELA<br>RUT: 11.610.180-7<br>pp. <?php echo $nombre_empresa; ?> SpA
								<?php
								} else {
								 ?>
								CECILIA MARGARITA DEBIA GARCÍA<br>RUT: 5.966.959-1<br>pp. <?php echo $nombre_empresa; ?> SpA
								 <?php
								}
								?>
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