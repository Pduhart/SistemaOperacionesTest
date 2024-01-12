<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

$nombre = 'carta_fondo_'.$id.'-'.date('d-m-Y');
// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

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
    <title>Fondo puesta en marcha</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 0px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 13px;
    	}
        .sin-borde{
			width: 99%;
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
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
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
			<td style="text-align: left">
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
                if ($haylogo==0) {
                	$fila = $conexion->extraer_registro_unico();
                	$nombre_doc_con = $fila["nombre_doc_con"];
                	?>
					<!-- <img src="../../../archivo/condominio/documento/<?php //echo $id_con; ?>/<?php //echo $nombre_doc_con; ?>" width="220">	 -->
					<img src="http://00ppsav.cl/padmin_ope/assets/img/logo-icp.jpg" width="103" height="108">
                	<?php
                } else{
                	?>
                	<img src="http://00ppsav.cl/padmin_ope/assets/img/logo-icp.jpg" width="103" height="108">
                	<?php
                }
				 ?>
			</td>
	    </tr>
	    <tr>
	    	<td>
	    		<p style="text-align: justify;">La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?></p>
				<p>Estimado(a), <?php echo ucwords(mb_strtolower(utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro)));?><br>
				</p>
				<p style="text-align: justify;">Junto con saludar, le hago presente que al momento de recepcionar su nuevo departamento y según ley N° 19.537 de Copropiedad Inmobiliaria referida en Reglamento de Copropiedad Ud. debe cancelar por concepto de “Fondo de puesta en Marcha” un valor de <b>$ <?php echo $total_prorrateo_depto; ?></b></p>
				<p>Reglamento de Copropiedad indica:</p>
				<p style="text-align: justify;"><u><b>ARTÍCULO VIGÉSIMO PRIMERO:</b></u> Sin perjuicio de los pagos a que se refieren los artículos anteriores, los propietarios erogarán a prorrata de sus cuotas, los dineros necesarios para la formación de los siguientes fondos:</p> 
				<p style="text-align: justify;">A) "Fondo Común de Reserva", que se integrará según se establece en el artículo tercero transitorio del presente reglamento y servirá para atender las reparaciones de los bienes de dominio común o a gastos comunes urgentes o imprevistos, el que se formará e incrementará con el porcentaje de recargo sobre los gastos comunes que en sesión extraordinaria fije la asamblea de copropietarios, con el producto de las multas e intereses que deben pagar los copropietarios. Los recursos de este fondo se mantendrán en depósito en una cuenta corriente bancaria o en una cuenta de ahorro, o se invertirán en instrumentos financieros que operen en el mercado de capitales, previo acuerdo del Comité de Administración.</p>
				<p style="text-align: justify;">B) “Fondo de Puesta en Marcha”, que se integrará según se establece en el artículo tercero transitorio del presente reglamento y servirá para atender los gastos iniciales de habilitación y puesta en marcha de la Comunidad, tales como alhajamiento del hall de acceso, compra de activos básicos y capital de trabajo. Este aporte, corresponderá y deberá ser pagado por el primer adquirente de la Unidad respectiva.</p>
				<p style="text-align: justify;"><u><b>ARTÍCULO TERCERO TRANSITORIO:</b></u> Mientras la asamblea de copropietarios de la Comunidad, en sesión extraordinaria, no determine el porcentaje de recargo sobre los gastos comunes para el <b>fondo de reserva</b> establecido en el artículo vigésimo tercero del presente Reglamento, dicho porcentaje se fija en el equivalente al <b>10 % por ciento del total</b> de los gastos comunes que mensualmente corresponda.</p>
				<p></p>
				<p style="text-align: justify;">Respecto del Fondo de Puesta en Marcha, este se constituirá mediante el aporte por parte del adquirente de la unidad equivalente a 2 gastos comunes mensuales del edificio.</p>
				<p>Agradeceré tener contemplado este pago en el momento indicado.</p>
				<p>Atenta a sus consultas,</p>
				<br><br><br><br><br>
				<p><?php echo $nombre_gerente_operacion;?><br>
				Gerente de Ventas y Operaciones<br>
				<?php echo $nombre_empresa; ?></p>
	    	</td>
	    	
	    </tr>
	</table>
</body>
</html>