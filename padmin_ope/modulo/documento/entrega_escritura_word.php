<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

$nombre = 'entrega_escritura_'.$id.'-'.date('d-m-Y');

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename= $nombre.doc");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Entrega Escritura</title>
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
    		font-size: 13px;
    	}
        .sin-borde{
			width: 100%;
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
		.conborde td{
			border: 1px solid #000000;
			padding: 2px 3px;
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
	<!-- <a class="btn no-print" href="carta_fondo_marcha_word.php?id=<?php// echo $id; ?>" target="_blank">Word</a> -->
	<!-- <a class="btn no-print" href="carta_fondo_marcha_pdf.php?id=<?php //echo $id; ?>" target="_blank">PDF</a> -->
	<!-- <a class="btn no-print" href="carta_fondo_marcha_email.php?id=<?php // echo $id; ?>" target="_blank">Enviar por mail</a> -->
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
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			con.nombre_con,
			con.id_con,
			ven.fecha_ven,
			viv.prorrateo_viv
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
	$fecha_promesa_ven = $fila["fecha_ven"];
	$fecha_actual = date("Y-m-d");
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = utf8_encode($fila["apellido_paterno_pro"]);
	$apellido_materno_pro = utf8_encode($fila["apellido_materno_pro"]);
	$nombre_con = $fila["nombre_con"];
	$id_con = $fila["id_con"];
	$prorrateo_viv = $fila["prorrateo_viv"];

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
    $conexion->consulta_form($consultapar,array(14,$id_con));
    $filapar = $conexion->extraer_registro_unico();
    $porcentaje_prorrateo = utf8_encode($filapar['valor_par']);

    $total_prorrateo_depto = ($prorrateo_viv * $porcentaje_prorrateo) / 100;
	$total_prorrateo_depto = $total_prorrateo_depto*2;
	$total_prorrateo_depto = number_format($total_prorrateo_depto, 0, ',', '.');

	
	$mes = date("n",strtotime($fecha_actual));
	$dia = date("d",strtotime($fecha_actual));
	$anio = date("Y",strtotime($fecha_actual));

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
			<td style="text-align: left"><img src="https://00ppsav.cl/padmin_ope/assets/img/<?php echo $logo;?>" style="margin-right: 24%"> 
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
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img style="margin-left: 200px" src="http://00ppsav.cl/archivo/condominio/documento/<?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="200" height="80">	

            	<?php
            }
			 ?>
			</td>
	    </tr>
	    <?php 

	    $consulta_honorarios_abogado = 
		"
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE
		        id_ven = ".$id." AND 
		        id_eta = 3 AND
		        id_cam_eta = 69
		    ";
		$conexion->consulta($consulta_honorarios_abogado);
		$filahonorabo = $conexion->extraer_registro_unico();
		$honorario_abo = $filahonorabo['valor_campo_eta_cam_ven'];
		$honorario_abo_formato = number_format($honorario_abo, 0, ',', '.');

		$consulta_boleta_abogado = 
		"
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE
		        id_ven = ".$id." AND 
		        id_eta = 3 AND
		        id_cam_eta = 72
		    ";
		$conexion->consulta($consulta_boleta_abogado);
		$filaboletaabo = $conexion->extraer_registro_unico();
		$boleta_abo = $filaboletaabo['valor_campo_eta_cam_ven'];

	    $consulta_honorarios = 
		"
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE
		        id_ven = ".$id." AND 
		        id_eta = 55 AND
		        id_cam_eta = 71
		    ";
		$conexion->consulta($consulta_honorarios);
		$filahonor = $conexion->extraer_registro_unico();
		$honorario_cbr = $filahonor['valor_campo_eta_cam_ven'];
		$honorario_cbr_formato = number_format($honorario_cbr, 0, ',', '.');

		$consulta_boleta_cbr = 
		"
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE
		        id_ven = ".$id." AND 
		        id_eta = 55 AND
		        id_cam_eta = 73
		    ";
		$conexion->consulta($consulta_boleta_cbr);
		$filaboletacbr = $conexion->extraer_registro_unico();
		$boleta_cbr = $filaboletacbr['valor_campo_eta_cam_ven'];

		$total_montos = $honorario_cbr + $honorario_abo;
		$total_montos_formato = number_format($total_montos, 0, ',', '.');

		$consulta_monto_GGOO = 
		"
		    SELECT
		        valor_campo_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE
		        id_ven = ".$id." AND 
		        id_eta = 2 AND
		        id_cam_eta = 41
		    ";
		$conexion->consulta($consulta_monto_GGOO);
		$filamontoggoo = $conexion->extraer_registro_unico();
		$monto_ggoo = $filamontoggoo['valor_campo_eta_cam_ven'];
		$monto_ggoo_formato = number_format($monto_ggoo, 0, ',', '.');

		$total_montos = $honorario_cbr + $honorario_abo;
		$total_montos_formato = number_format($total_montos, 0, ',', '.');


		$fondo_rendir = $monto_ggoo;

		$saldo = $fondo_rendir - $total_montos;
		$saldo_formato = number_format($saldo, 0, ',', '.');
	     ?>
	    <tr>
	    	<td>
	    		<p style="text-align: left">La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?></p>
				<p style="text-align: justify;">Estimado(a),<br><?php echo $nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?><br>
				</p>
				<p style="text-align: left;"><u>Presente</u></p>
				<p style="text-align: justify;">Junto con saludar, <b>Inmobiliaria Costanera Pacifico S.P.A</b>, tiene el agrado de saludarle y hacer entrega de Escritura de Compra Venta, debidamente inscrita en Conservador de Bienes Raíces La Serena Sr. Jaime Morande Miranda, certificado de Gravámenes y Prohibiciones, y Derecho de Uso y Goce de Estacionamiento y factura de venta del departamento.</p>
				<p>Arqueo Fondo a rendir</p>
				<table style="width: 100%; border-collapse: collapse;" class="conborde">
					<tr>
						<td></td>
						<td>Ingreso fondo a rendir</td>
						<td>Egresos</td>
						<td>N° de Boleta</td>
						<td>Monto</td>
					</tr>
					<tr>
						<td></td>
						<td style="text-align: center">$ <?php echo $monto_ggoo_formato; ?></td>
						<td>Estudio de Título y Confección Escritura</td>
						<td style="text-align: right"><?php echo $boleta_abo; ?></td>
						<td style="text-align: right">$<?php echo $honorario_abo_formato; ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>Inscripción CBR</td>
						<td style="text-align: right"><?php echo $boleta_cbr; ?></td>
						<td style="text-align: right">$<?php echo $honorario_cbr_formato; ?></td>
					</tr>
					<tr>
						<td>Total</td>
						<td></td>
						<td></td>
						<td></td>
						<td style="text-align: right">$<?php echo $total_montos_formato; ?></td>
					</tr>
					<tr>
						<td>Saldo a reembolsar</td>
						<td colspan="4" style="text-align: center; background-color: #dadada">$<?php echo $saldo_formato; ?></td>
					</tr>
				</table>
				<p style="text-align: justify;">Este saldo, será depositado en su cuenta corriente.</p> 
				<p style="text-align: justify;">Agradeciendo su elección se despide cordialmente,</p>
				
				<br><br><br><br><br>
				<p><?php echo $nombre_gerente_operacion;?><br>
				Gerente de Ventas y Operaciones<br>
				<?php echo $nombre_empresa; ?></p>
	    	</td>
	    	
	    </tr>
	</table>
</body>
</html>