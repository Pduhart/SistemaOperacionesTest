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
    <title>Detalle de Pago</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 13px;
    	}
    	.bordef{
    		border:2px solid #000000;
    	}
        .sin-borde{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
        }
		.liquida{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
			border-collapse: collapse;
		}
		.liquida td{
		}
		.color{
			background-color: #CCCCCC;
			border:1px solid #000000;
		}
		.detalle td{
			border:1px solid #000000;
		}
		.borde{
			border:1px solid #000000;
		}
		.centrado{
			text-align: center;
		}
		.derecha{
			text-align: right;
		}
    </style>
</head>
<body>
	<table class="sin-borde">
	    <tr>
			<td style="width: 20%; text-align: left"><img src="<?php echo _ASSETS."img/logo-empresa.jpg";?>"></td>
			<td style="text-align: center;"><h2>Inmobiliaria Cordillera SPA</h6></td>
			<td style="width: 20%;">
			</td>
	    </tr>
	</table>

	<table class="liquida">
		<thead>
			<tr>
				<th colspan="8" class="bordef" style="border:1px solid #000000;">Abonos de Pié</th>
			</tr>
			<tr style="height: 10px">
				<th colspan="8"></th>
			</tr>
		</thead>
		<?php  
		$consulta = 
			"
			SELECT 
				pro.nombre_pro, 
				pro.nombre2_pro, 
				pro.apellido_paterno_pro, 
				pro.apellido_materno_pro,
				con.nombre_con, 
				viv.nombre_viv 
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
		$nombre_pro = $fila["nombre_pro"];
		$nombre2_pro = $fila["nombre2_pro"];
		$apellido_paterno_pro = $fila["apellido_paterno_pro"];
		$apellido_materno_pro = $fila["apellido_materno_pro"];
		$nombre_con = $fila["nombre_con"];
		$nombre_viv = $fila["nombre_viv"];
		?>
		<tbody>
			<tr class="cabecera">
				<td class="color" style="width: 7%">Cliente</td>
				<td style="width: 22%" colspan="2" class="borde"><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></td>
				<td class="color" style="width: 9%">Depto.</td>
				<td style="width: 12%" class="borde centrado"><?php echo utf8_encode($nombre_viv);?></td>
				<td style="width: 3%"></td>
				<td colspan="2" class="color" style="text-align: center;">ARQUEO</td>
			</tr>
			<!-- Desistimientos del mes -->
			<tr>
				<td colspan="6"></td>
				<td class="borde centrado">Valor Depto.</td>
				<td class="borde centrado">3.443</td>
			</tr>
			<tr class="lista">
				<td class="color centrado">Abono</td>
				<td class="color centrado">Abono UF</td>
				<td class="color centrado">Fecha Cobro</td>
				<td class="color centrado">Estado de Cobro</td>
				<td class="color centrado">Valor UF Pago Efectivo</td>
				<td></td>
				<td class="borde centrado">Pié Cancelado</td>
				<td class="borde centrado">27</td>
			</tr>
			<tr class="lista">
				<td class="borde">$ 2000</td>
				<td class="borde">10</td>
				<td class="borde">12-12-2010</td>
				<td class="borde centrado">Cobrado</td>
				<td class="borde derecha">$ 26.000</td>
				<td></td>
				<td class="borde centrado">Abono Inmobiliaria</td>
				<td class="borde centrado">317</td>
			</tr>
			<tr class="lista">
				<td class="borde">$ 2000</td>
				<td class="borde">10</td>
				<td class="borde">12-12-2010</td>
				<td class="borde centrado">Cobrado</td>
				<td class="borde derecha">$ 26.000</td>
				<td></td>
				<td class="borde centrado">Pié por Cobrar</td>
				<td class="borde centrado">0.0</td>
			</tr>
			<tr class="lista">
				<td class="borde">$ 8000</td>
				<td class="borde">26</td>
				<td class="borde"></td>
				<td class="borde"></td>
				<td class="borde"></td>
				<td></td>
				<td class="borde centrado">Crédito Hipotecario</td>
				<td class="borde centrado">3.900</td>
			</tr>
			<tr class="lista">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="borde centrado">Total</td>
				<td class="borde centrado">3.900</td>
			</tr>
			<tr class="lista">
				<td colspan="2" class="borde" style="text-align: center">Fondo puesta en Marcha</td>
				<td></td>
				<td colspan="2" class="borde" style="text-align: center">Fondo gastos OOPP Contado</td>
				<td></td>
				<td colspan="2" rowspan="4">
					logo
				</td>
			</tr>
			<tr class="lista">
				<td class="borde centrado">Monto</td>
				<td class="borde centrado">Fecha Pago</td>
				<td></td>
				<td class="borde centrado">Monto</td>
				<td class="borde centrado">Fecha Pago</td>
				<td></td>
			</tr>
			<tr class="lista">
				<td class="borde centrado">34545</td>
				<td class="borde centrado"></td>
				<td></td>
				<td class="borde centrado"></td>
				<td class="borde centrado"></td>
				<td></td>
			</tr>
			<tr class="lista">
				<td class="borde centrado">34545</td>
				<td class="borde centrado"></td>
				<td></td>
				<td class="borde centrado"></td>
				<td class="borde centrado"></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</body>
</html>