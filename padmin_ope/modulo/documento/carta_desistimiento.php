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

?>
<!DOCTYPE html>
<html>
<head>
    <title>Carta Desistimiento</title>
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

	// $consulta = 
	// 	"
	// 	SELECT 
	// 		pro.nombre_pro, 
	// 		pro.nombre2_pro, 
	// 		pro.apellido_paterno_pro, 
	// 		pro.apellido_materno_pro 
	// 	FROM 
	// 		venta_venta AS ven
	// 		INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	// 		INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	// 		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	// 	WHERE 
	// 		id_ven = ?
	// 	";

	$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
	$conexion->consulta_form($consulta,array(14));
	$fila = $conexion->extraer_registro_unico();
	$nombre_gerente_operacion = $fila["valor_par"];

	$consulta = 
		"
		SELECT 
			pro.nombre_pro, 
			pro.nombre2_pro, 
			pro.apellido_paterno_pro, 
			pro.apellido_materno_pro,
			tor.id_con
		FROM 
			venta_venta AS ven
			INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
			INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		WHERE 
			ven.id_ven = ?
		";
	$conexion->consulta_form($consulta,array($id));
	$fila = $conexion->extraer_registro_unico();
	$nombre_pro = $fila["nombre_pro"];
	$nombre2_pro = $fila["nombre2_pro"];
	$apellido_paterno_pro = $fila["apellido_paterno_pro"];
	$apellido_materno_pro = $fila["apellido_materno_pro"];
	$id_con = $fila["id_con"];


	$consulta = "SELECT fecha_des_ven FROM venta_desestimiento_venta WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($id));
	$fila = $conexion->extraer_registro_unico();
	$fecha_desistimiento = $fila["fecha_des_ven"];
	$mes = date("n",strtotime($fecha_desistimiento));
	$dia = date("d",strtotime($fecha_desistimiento));
	$anio = date("Y",strtotime($fecha_desistimiento));

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
                        (nombre_doc_con = 'logo.jpg' OR nombre_doc_con = 'logo.png')
                    ";
                $contador = 1;
                $conexion->consulta_form($consulta,array($id_con));
                $haylogo = $conexion->total();
                if ($haylogo>0) {
                	$fila = $conexion->extraer_registro_unico();
                	$nombre_doc_con = $fila["nombre_doc_con"];
                	?>
					<img src="<?php echo _RUTA."archivo/condominio/documento/";?><?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="220">	

                	<?php
                } else{
                	?>
                	<img src="<?php echo _ASSETS."img/logo-empresa.jpg";?>">
                	<?php
                }
				 ?>
			</td>
	    </tr>
	    <tr>
	    	<td>
	    		<p>La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?></p><br>
				<p>Estimado <?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?>:<br>
				Junto con saludar, le envío carta de desistimiento firmado por ambas partes.<br><br>
				Le informo que la multa, Ud. la podrá ocupar como reserva para nuestros próximos proyectos en un plazo máximo de 12 meses.
				<br><br>
				Saluda atentamente,</p>
				<br><br><br><br><br>
				<p><?php echo $nombre_gerente_operacion;?><br>
				Gerente de Ventas y Operaciones<br>
				Inmobiliaria Costanera Pacífico SpA</p>
	    	</td>
	    </tr>
	</table>
</body>
</html>