<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");
$nombre = 'certificado_premio_'.$id.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename= $nombre.doc");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Certificado Premio</title>
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
<body>
	<?php  

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
	$conexion->consulta_form($consulta,array(16));
	$fila = $conexion->extraer_registro_unico();
	$nombre_gerente_general = $fila["valor_par"];


	$consulta = 
		"
		SELECT 
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			pro.rut_pro,
			con.nombre_con,
			con.id_con,
			ven.fecha_ven,
			pre.nombre_pre,
			viv.nombre_viv
		FROM 
			venta_venta AS ven
			INNER JOIN premio_premio AS pre ON pre.id_pre = ven.id_pre
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
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$nombre_con = $fila["nombre_con"];
	$rut_pro = $fila["rut_pro"];
	$nombre_pre = utf8_encode($fila["nombre_pre"]);
	$nombre_viv = utf8_encode($fila["nombre_viv"]);
	$id_con = $fila["id_con"];

	if ($id_con==1) {
    	$logo = "logo-empresa.jpg";
    	$nombre_empresa = "Inmobiliaria Cordillera SPA";
    } else {
    	$logo = "logo-icp.jpg";
    	$nombre_empresa = "Inmobiliaria Costanera Pacífico SPA";
    }
	
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

	?>
	<table class="sin-borde">
	    <tr>
	    	<td style="text-align: center">
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
					<img src="http://00ppsav.cl/archivo/condominio/documento/<?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="220" height="113">	

                	<?php
                } else{
                	?>
                	<img src="http://00ppsav.cl/padmin_ope/assets/img/logo-empresa.jpg";?>">
                	<?php
                }
				 ?>
				 <h3>CERTIFICADO</h3>
			</td>
	    </tr>
	    <tr>
	    	<td>
	    		<p style="text-align: right">La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?></p><br>
	    		<table style="width: 100%">
	    			<tr>
	    				<td style="width: 25%">Nombre de Beneficiario:</td>
	    				<td><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></td>
	    			</tr>
	    			<tr>
	    				<td>R.U.T.:</td>
	    				<td><?php echo utf8_encode($rut_pro);?></td>
	    			</tr>
	    			<tr>
	    				<td>Fecha Promesa:</td>
	    				<td><?php echo  date("d-m-Y",strtotime($fecha_promesa_ven));?></td>
	    			</tr>
	    			<tr>
	    				<td>N° Departamento:</td>
	    				<td><?php echo $nombre_viv;?></td>
	    			</tr>
	    			<tr style="height: 10px">
	    				<td colspan="2"></td>
	    			</tr>
	    			<tr>
	    				<td colspan="2" style="padding-bottom: 8px"><b>PROMOCIÓN</b></td>
	    			</tr>
	    			<tr>
	    				<td colspan="2" style="border: 2px solid #000000; padding: 3px"><?php echo $nombre_pre; ?></td>
	    			</tr>
	    		</table>
				<p><b>Condiciones:</b> Requisito esencial para la obtención del premio haberse realizado la tradición del inmueble singularizado en la promesa de compraventa.</p>
				<p><b>Plazos de entrega:</b> A la firma de escritura y según programa de entrega de inmuebles por parte de la Inmobiliaria.</p>

				<br><br><br><br><br><br><br>
				<p style="text-align: center;">
				<table style="width: 28%; border-top: 1px solid #000000; margin-left: auto; margin-right: auto; text-align: center">
					<tr>
						<td><?php echo $nombre_gerente_general;?><br>Gerente General<br><?php echo $nombre_empresa; ?></td>
					</tr>
				</table>
				</p>
	    	</td>
	    	
	    </tr>
	</table>
</body>
</html>