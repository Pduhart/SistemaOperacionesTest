<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_cierre = $_GET["id"];
$id_usuario = $_GET["id_usu"];

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

?>
<!DOCTYPE html>
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
	<a class="btn no-print" href="liquidacion_j_operacion_pdf.php?id=<?php echo $id_cierre; ?>&id_usu=<?php echo $id_usuario; ?>" target="_blank">PDF</a>
<?php  
$consulta = 
    "
    SELECT
        nombre_usu,
        apellido1_usu,
        apellido2_usu,
        id_usu
    FROM
        usuario_usuario
    WHERE
        id_usu = ?
    ";
$conexion->consulta_form($consulta,array($id_usuario));
$fila = $conexion->extraer_registro_unico();
$nombre_usu = $fila["nombre_usu"];
$apellido1_usu = $fila["apellido1_usu"];
$apellido2_usu = $fila["apellido2_usu"];
$id_usu = $fila["id_usu"];
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
        INNER JOIN cierre_bono_cierre_venta AS cie_bon_cie ON cie_bon_cie.id_cie = cie.id_cie
        INNER JOIN condominio_condominio AS con ON con.id_con = cie_bon_cie.id_con
        INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
        INNER JOIN uf_uf AS uf ON uf.fecha_uf = cie.fecha_hasta_cie
    WHERE
        cie.id_cie = ? 
    GROUP BY
        cie.id_cie,
        cie.anio_cie,
        con.nombre_con,
        con.alias_con,
        mes.id_mes,
        uf.valor_uf,
        con.id_con
    ";

$conexion->consulta_form($consulta,array($id_cierre));
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

        if ($id_con==1) {
                	$logo = "logo-empresa.jpg";
                } else {
                	$logo = "logo-icp.jpg";
                }
        ?>
		<table class="sin-borde">
		    <tr>
		    	
				<td style="width: 20%; text-align: left"><img src="<?php echo _ASSETS."img/".$logo;?>"></td>
				<td style="text-align: center;"><h2>Detalle Bono Jefe de Operaciones: <?php echo utf8_encode($nombre_usu." ".$apellido1_usu." ".$apellido2_usu);?></h2><h6>Período: <?php echo $id_mes;?>/<?php echo utf8_encode($anio_cie);?></h6></td>
				<td style="width: 20%;">
					<table class="hoy">
						<tr>
							<td style="width: 50%"><b>Fecha:</b></td>
							<td><?php echo date("d-m-Y"); ?></td>
						</tr>
						<tr>
							<td style="width: 50%"><b>Hora:</b></td>
							<td><?php echo date("H:i"); ?></td>
						</tr>
						<tr>
							<td style="width: 50%"><b>Página:</b></td>
							<td><?php echo $contador_pagina; ?>/<?php echo $cantidad_condominio; ?></td>
						</tr>
					</table>
					<table class="periodo">
						<tr>
							<td style="width: 50%"><b>Desde:</b></td>
							<td><?php echo date("d-m-Y",strtotime($fecha_desde_cie)); ?></td>
						</tr>
						<tr>
							<td style="width: 50%"><b>Hasta:</b></td>
							<td><?php echo date("d-m-Y",strtotime($fecha_hasta_cie)); ?></td>
						</tr>
						<tr>
							<td style="width: 50%"><b>Valor UF:</b></td>
							<td><?php echo number_format($valor_uf, 2, ',', '.');?></td>
						</tr>
					</table>
				</td>
		    </tr>
		</table>

		<table class="liquida">
			<thead>
				<tr>
					<th colspan="6" style="border:1px solid #000000;">CONDOMINIO <?php echo utf8_encode($nombre_con);?></th>
					<th style="text-align: center; border:1px solid #000000;">Bono UF</th>
					<th style="text-align: center; border:1px solid #000000;">Bono $</th>
				</tr>
			</thead>
			<tbody>
				<tr class="cabecera">
					<td style="width: 7%"></td>
					<td style="width: 7%"># Depto</td>
					<td>Nombre Cliente</td>
					<td>Fecha Escritura</td>
					<td >Fecha Liquidación</td>
					<td colspan="3">Bonos</td>
				</tr>
				
				<!-- Ventas del Mes -->
				<tr class="separa">
					<td colspan="2"></td>
					<td colspan="3">Recuperadas</td>
					<td style="text-align: right">Nombre Bono</td>
					<td></td>
					<td></td>
				</tr>
				<?php  
		        $monto_acumulado_a_pagar = 0;
		        $monto_acumulado_escritura = 0;

		        $acumula_monto_pesos_bono = 0;
		        $acumula_monto_uf_bono = 0;

		        $consulta_jo = 
	            "
	            SELECT
	                ven.monto_ven,
	                pro.nombre_pro,
	                pro.apellido_paterno_pro,
	                pro.apellido_materno_pro,
	                viv.id_mod,
	                viv.nombre_viv,
	                ven.id_ven,
	                ven_liq.fecha_liq_ven,
	                ven.fecha_escritura_ven,
	                cie_bon.nombre_bon_cie,
	                cie_bon.monto_bon_cie
	            FROM
	                venta_venta AS ven
	                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                INNER JOIN cierre_bono_cierre_venta AS cie_bon ON cie_bon.id_ven = ven.id_ven
	                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	                INNER JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
	            WHERE
	                tor.id_con = ? AND 
	                cie_bon.id_cie = ? AND
	                cie_bon.id_usu = ?
	            ";
	            $conexion->consulta_form($consulta_jo,array($id_con, $id_cierre, $id_usu));
		        $fila_consulta_joperaciones = $conexion->extraer_registro();
		        $contador_jo = 0;
		        if(is_array($fila_consulta_joperaciones)){
		            foreach ($fila_consulta_joperaciones as $fila_jo) {
		            	$fecha_liq_ven = $fila_jo["fecha_liq_ven"];
	            		$fecha_escritura_ven = $fila_jo["fecha_escritura_ven"];

	            		$bono_uf = $fila_jo['monto_bon_cie'];
	            		$acumula_monto_uf_bono = $acumula_monto_uf_bono + $bono_uf;
	            		$bono_pesos = $valor_uf * $bono_uf;
                    	$acumula_monto_pesos_bono = $acumula_monto_pesos_bono + $bono_pesos;
                    	$bono_pesos = number_format($bono_pesos, 0, ',', '.');
		            	?>
		                <tr class="lista">
		                	<td><?php echo utf8_encode($alias_con);?></td>
		                	<td><?php echo utf8_encode($fila_jo['nombre_viv']);?></td>
		                    <td class="nombre"><?php echo utf8_encode($fila_jo['nombre_pro']." ".$fila_jo['apellido_paterno_pro']." ".$fila_jo['apellido_materno_pro']);?></td>
		                    <td><?php echo date("d/m/Y",strtotime($fecha_escritura_ven)); ?></td>
		                    <td><?php echo date("d/m/Y",strtotime($fecha_liq_ven)); ?></td>
		                    <td><?php echo utf8_encode($fila_jo['nombre_bon_cie']);?></td>
		                    <td><?php echo number_format($bono_uf, 2, ',', '.');?></td>
		                    <td><?php echo $bono_pesos;?></td>
		                </tr>
		                <?php
		            }
		        }

		       
		        ?>
		        
				
				<!-- Totales -->
				<tr class="separa total">
					<td></td>
					<td>TOTAL BONOS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align: center"><?php echo number_format($acumula_monto_uf_bono, 2, ',', '.');?></td>
					<td style="text-align: center"><?php echo number_format($acumula_monto_pesos_bono, 0, ',', '.');?></td>
				</tr>
				
			</tbody>
		</table>
		<br/>
        <br/>
		<?php 
		$contador_pagina++; 
	}
}
?>
</body>
</html>