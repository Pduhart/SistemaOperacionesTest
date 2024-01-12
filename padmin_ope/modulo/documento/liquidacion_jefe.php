<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_cierre = $_GET["id"];
$id_usuario = $_GET["id_usu"];

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
	<a class="btn no-print" href="liquidacion_jefe_pdf.php?id=<?php echo $id_cierre; ?>&id_usu=<?php echo $id_usuario; ?>" target="_blank">PDF</a>
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
        ven.id_jefe_ven = ?
    GROUP BY
        cie.id_cie,
        cie.anio_cie,
        mes.id_mes,
        cie.fecha_desde_cie,
        cie.fecha_hasta_cie,
        con.nombre_con,
        con.alias_con,
        uf.valor_uf,
        con.id_con
    ";
$conexion->consulta_form($consulta,array($id_cierre,$id_usuario));
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
        ?>
        <table class="sin-borde">
            <tr>
            	<?php 
        		
        		$consulta = 
        		    "
        		    SELECT
        		        valor_par,
        		        valor2_par
        		    FROM
        		        parametro_parametro
        		    WHERE
        		        id_con = ? AND
        		        valor2_par IN (6,7,10)
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
            	?>
        		<td style="width: 20%; text-align: left"><img src="<?php echo _ASSETS."img/".$logo."";?>"></td>
        		<td style="text-align: center;"><h2>Detalle Bono Jefe de Ventas: <?php echo utf8_encode($nombre_usu." ".$apellido1_usu." ".$apellido2_usu);?></h2><h6>Período: <?php echo $id_mes;?>/<?php echo utf8_encode($anio_cie);?></h6></td>
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
        			<th colspan="4" style="border:1px solid #000000;">CONDOMINIO <?php echo utf8_encode($nombre_con);?></th>
        			<th colspan="4" style="text-align: center; border:1px solid #000000;">Bono UF</th>
        			<th colspan="2" style="text-align: center; border:1px solid #000000;">Bono $</th>
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
        			<td style="width: 7%">Promesa<br><?php echo $porcentaje_promesa;?></td>
        			<td style="width: 7%">Escritura<br><?php echo $porcentaje_escritura;?></td>
        			<td style="width: 7%" class="bl-1">Promesa<br><?php echo $porcentaje_promesa;?></td>
        			<td style="width: 7%">Escritura<br><?php echo $porcentaje_escritura;?></td>
        			<td style="width: 8%" class="bl-1">Fecha</td>
        			<td style="width: 9%">Descuento $</td>
        		</tr>
        		<!-- Desistimientos del mes -->
        		<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Desistimientos</td>
        		</tr>
        		<?php  
                $total_desistimiento_acumulado = 0;
                $monto_uf_acumulado_promesa = 0;
                $monto_uf_acumulado_escritura = 0;
                $monto_acumulado_promesa = 0;
                $monto_acumulado_escritura = 0;
                $monto_uf_acumulado_escritura_desi = 0;
                $monto_uf_acumulado_promesa_desi = 0;
                $consulta = 
                    "
                    SELECT
                        usu.id_usu,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.fecha_ven,
                        ven.promesa_monto_comision_jefe_ven,
                        ven.escritura_monto_comision_jefe_ven,
                        ven.total_comision_jefe_ven,
                        ven.promesa_bono_precio_jefe_ven,
                        ven.escritura_bono_precio_jefe_ven,
                        ven.total_bono_precio_jefe_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        des_ven.fecha_des_ven,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        usuario_usuario AS usu
                        INNER JOIN venta_venta AS ven ON ven.id_jefe_ven = usu.id_usu
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                        INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                    WHERE
                        tor.id_con = ? AND
                        usu.id_usu = ? AND
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
                $conexion->consulta_form($consulta,array($id_con,$id_usuario,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {
                        // $monto_comision_promesa_desi = round($fila_det['promesa_monto_comision_jefe_ven'] * $UF_LIQUIDACION);
                        // $monto_comision_escritura_desi = round($fila_det['escritura_monto_comision_jefe_ven'] * $UF_LIQUIDACION);

                        $UF_DESISTIMIENTO_VENTA = 0;

                    	$UF_DESISTIMIENTO_VENTA = get_uf_disistimiento($fila_det['id_ven']);

                        $monto_comision_promesa_desi = round(round($fila_det['promesa_monto_comision_jefe_ven'],2) * $UF_DESISTIMIENTO_VENTA);
                        $monto_comision_escritura_desi = round(round($fila_det['escritura_monto_comision_jefe_ven'],2) * $UF_DESISTIMIENTO_VENTA);

                        // if ($fila_det['id_ven'] == 484) {

                        // 	$promesa_monto_comision_ven_desistimiento = round($fila_det['promesa_monto_comision_jefe_ven'],2);
                        	
                        // 	$monto_comision_promesa_desi = $promesa_monto_comision_ven_desistimiento * 29706.87;

                        // }

                        // if ($fila_det['id_ven'] == 509) {

                        // 	$promesa_monto_comision_ven_desistimiento = round($fila_det['promesa_monto_comision_jefe_ven'],2);
                        	
                        // 	$monto_comision_promesa_desi = $promesa_monto_comision_ven_desistimiento * 29753.8;

                        // }

                        $total_desistimiento = 0;

                        $total_comision_jefe_ven = $fila_det['total_comision_jefe_ven'];

                        
                        
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

                        $porcentaje_jefe = $total_comision_jefe_ven * 100 /$valor_venta_comision;
                        ?>
                        <tr class="lista">
                        	<td><?php echo utf8_encode($alias_con);?></td>
                        	<td><?php echo number_format($valor_venta_comision, 2, ',', '.');?></td>
                            <td class="nombre"><?php echo utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']);?></td>
                            <td><?php echo date("d/m/Y",strtotime($fila_det['fecha_ven'])); ?></td>
                            <td><?php echo number_format($porcentaje_jefe, 2, ',', '.'); ?></td>
                            <?php  
                            if($cantidad_estado_promesa > 0){
                            	$monto_uf_acumulado_promesa_desi = $monto_uf_acumulado_promesa_desi - $fila_det['promesa_monto_comision_jefe_ven'];
                            	$monto_acumulado_promesa_desi = $monto_acumulado_promesa_desi - $monto_comision_promesa_desi;
                                $total_desistimiento = $total_desistimiento + $monto_comision_promesa_desi;
                                ?>
                                <td><?php echo number_format($fila_det['promesa_monto_comision_jefe_ven'], 2, ',', '.');?></td>
                                <?php
                            }
                            else{
                                ?>
                                <td></td>
                                <?php
                            }
                            if($cantidad_estado_escritura > 0){
                            	$monto_uf_acumulado_escritura_desi = $monto_uf_acumulado_escritura_desi - $fila_det['escritura_monto_comision_jefe_ven'];
                            	$monto_acumulado_escritura_desi = $monto_acumulado_escritura_desi - $monto_comision_escritura_desi;
                                $total_desistimiento = $total_desistimiento + $monto_comision_escritura_desi;
                                ?>
                                <td><?php echo number_format($fila_det['escritura_monto_comision_jefe_ven'], 2, ',', '.');?></td>
                                <?php
                            }
                            else{
                                ?>
                                <td></td>
                                <?php
                            }
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo date("d/m/Y",strtotime($fila_det['fecha_des_ven'])); ?></td>
                            <td><?php echo number_format($total_desistimiento, 0, ',', '.');?>.-</td>
                        </tr>
                        <?php
                        $total_desistimiento_acumulado = $total_desistimiento_acumulado + $total_desistimiento;
                    }
                }
                ?>
        		
        		<!-- Ventas del Mes -->
        		<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Cierres y Escrituras</td>
        		</tr>
        		<?php  
                $monto_acumulado_a_pagar = 0;
                
                $consulta = 
                    "
                    SELECT
                        usu.id_usu,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.fecha_ven,
                        ven.promesa_monto_comision_jefe_ven,
                        ven.escritura_monto_comision_jefe_ven,
                        ven.total_comision_jefe_ven,
                        ven.promesa_bono_precio_jefe_ven,
                        ven.escritura_bono_precio_jefe_ven,
                        ven.total_bono_precio_jefe_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        usuario_usuario AS usu
                        INNER JOIN venta_venta AS ven ON ven.id_jefe_ven = usu.id_usu
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    WHERE
                        tor.id_con = ? AND
                        usu.id_usu = ? AND
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
                $conexion->consulta_form($consulta,array($id_con,$id_usuario,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {
                        // $monto_comision_promesa = round($fila_det['promesa_monto_comision_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_comision_escritura = round($fila_det['escritura_monto_comision_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_promesa = round($fila_det['promesa_bono_precio_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_escritura = round($fila_det['escritura_bono_precio_jefe_ven'] * $fila_det['valor_uf']);

                        $total_comision_jefe_ven = $fila_det['total_comision_jefe_ven'];
                        

						if ($fila_det['id_pie_abo_ven']==1) {
                        	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                        } else {
                        	$valor_venta_comision = $fila_det['monto_ven'];
                        }

                        $comision_promesa_jefe_red = floor($fila_det['promesa_monto_comision_jefe_ven'] * 1000) / 1000;
				        $comision_promesa_jefe_red = round($comision_promesa_jefe_red, 2);
                        $monto_comision_promesa_jefe = round($comision_promesa_jefe_red * $UF_LIQUIDACION);
                        


                        $comision_escritura_jefe_red = floor($fila_det['escritura_monto_comision_jefe_ven'] * 1000) / 1000;
				        $comision_escritura_jefe_red = round($comision_escritura_jefe_red, 2);
                        $monto_comision_escritura_jefe = round($comision_escritura_jefe_red * $UF_LIQUIDACION);

                        $porcentaje_jefe = $total_comision_jefe_ven * 100 /$valor_venta_comision;
                        ?>
                        <tr class="lista">
                        	<td><?php echo utf8_encode($alias_con);?></td>
                        	<td><?php echo number_format($valor_venta_comision, 2, ',', '.');?></td>
                            <td class="nombre"><?php echo utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']);?></td>
                            <td><?php echo date("d/m/Y",strtotime($fila_det['fecha_ven'])); ?></td>
                            <td><?php echo number_format($porcentaje_jefe, 2, ',', '.'); ?></td>
                            <td><?php echo number_format($fila_det['total_comision_jefe_ven'], 3, ',', '.');?></td>
                            <td><?php echo $comision_promesa_jefe_red;?></td>
                            <td><?php echo $comision_escritura_jefe_red;?></td>

                            <?php  
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

                            	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $comision_promesa_jefe_red;
                            	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_comision_promesa_jefe;
                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_promesa_jefe;
                                ?>
                                <td><?php echo number_format($monto_comision_promesa_jefe, 0, ',', '.');?></td>
                                <?php
                            }
                            else{
                                ?>
                                <td>0</td>
                                <?php 
                            }
                            
                        	if($cantidad_estado_escritura > 0){
                        		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $comision_escritura_jefe_red;
                        		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_comision_escritura_jefe;
                        		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_escritura_jefe;
                                ?>
                                <td><?php echo number_format($monto_comision_escritura_jefe, 0, ',', '.');?></td>
                                <?php
                        	}
                        	else{
                        		?>
                                <td>0</td>
                                <?php
                        	}
                                
                            
                            ?>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <!-- bonos por rango -->
                <?php
                
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
                            ?>
                            <tr class="separa">
                                <td colspan="2"></td>
                                <td colspan="3">Bono <?php echo utf8_encode($fila_bono['nombre_bon_cie']);?></td>
                                <td style="text-align: right"><?php echo number_format($fila_bono['monto_bon_cie'], 2, ',', '.');?></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right"><?php echo number_format($monto_bono, 0, ',', '.');?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                        }
                    }
                	
                }
                else{
                	?>
                	<tr class="separa">
        				<td colspan="2"></td>
        				<td colspan="3"></td>
        				<td style="text-align: right"></td>
        				<td></td>
        				<td></td>
        				<td style="text-align: right"></td>
        				<td></td>
        				<td></td>
        				<td></td>
        			</tr>
                	<?php
                }
                 
                ?>
        		
        		
        		
        		<!-- bonos al precio -->
        		<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="10">Bono al Precio</td>
        		</tr>
        		<?php  

                $monto_acumulado_a_pagar = 0;
                $consulta = 
                    "
                    SELECT
                        usu.id_usu,
                        pro.nombre_pro,
                        pro.nombre2_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        ven.id_ven,
                        ven.id_est_ven,
                        ven.monto_ven,
                        ven.promesa_monto_comision_jefe_ven,
                        ven.escritura_monto_comision_jefe_ven,
                        ven.total_comision_jefe_ven,
                        ven.promesa_bono_precio_jefe_ven,
                        ven.escritura_bono_precio_jefe_ven,
                        ven.total_bono_precio_jefe_ven,
                        viv.nombre_viv,
                        uf.valor_uf,
                        ven.id_pie_abo_ven,
                        ven.descuento_ven
                    FROM
                        usuario_usuario AS usu
                        INNER JOIN venta_venta AS ven ON ven.id_jefe_ven = usu.id_usu
                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    WHERE
                        tor.id_con = ? AND
                        usu.id_usu = ? AND
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
                $conexion->consulta_form($consulta,array($id_con,$id_usuario,$id_cierre));
                $fila_consulta_detalle = $conexion->extraer_registro();
                $contador_promesa = 0;
                if(is_array($fila_consulta_detalle)){
                    foreach ($fila_consulta_detalle as $fila_det) {
                        // $monto_comision_promesa = round($fila_det['promesa_monto_comision_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_comision_escritura = round($fila_det['escritura_monto_comision_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_promesa = round($fila_det['promesa_bono_precio_jefe_ven'] * $fila_det['valor_uf']);
                        // $monto_bono_escritura = round($fila_det['escritura_bono_precio_jefe_ven'] * $fila_det['valor_uf']);
                        
						if ($fila_det['id_pie_abo_ven']==1) {
                        	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                        } else {
                        	$valor_venta_comision = $fila_det['monto_ven'];
                        }

                        $promesa_bono_precio_jefe_ven = round($fila_det['promesa_bono_precio_jefe_ven'],2);
                        $monto_bono_promesa_jefe = round($promesa_bono_precio_jefe_ven * $UF_LIQUIDACION);

                        $escritura_bono_precio_jefe_ven = round($fila_det['escritura_bono_precio_jefe_ven'],2);
                        $monto_bono_escritura_jefe = round($escritura_bono_precio_jefe_ven * $UF_LIQUIDACION);

                        ?>
                        <tr class="lista">
                        	<td><?php echo utf8_encode($alias_con);?></td>
                        	<td><?php echo number_format($valor_venta_comision, 2, ',', '.');?></td>
                            <td class="nombre"><?php echo utf8_encode($fila_det['nombre_pro']." ".$fila_det['apellido_paterno_pro']." ".$fila_det['apellido_materno_pro']);?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo number_format($fila_det['total_bono_precio_jefe_ven'], 2, ',', '.');?></td>
                            <td><?php echo $promesa_bono_precio_jefe_ven;?></td>
                            <td><?php echo $escritura_bono_precio_jefe_ven;?></td>

                            <?php  
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
                            	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $promesa_bono_precio_jefe_ven;
                            	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_bono_promesa_jefe;
                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_promesa_jefe;
                                ?>
                                <td><?php echo number_format($monto_bono_promesa_jefe, 0, ',', '.');?></td>
                                <?php
                            }
                            else{
                                ?>
                                <td>0</td>
                                <?php
                            }
                            
                        	if($cantidad_estado_escritura > 0){
                        		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $escritura_bono_precio_jefe_ven;
                        		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_bono_escritura_jefe;
                        		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_escritura_jefe;
                                ?>
                                <td><?php echo number_format($monto_bono_escritura_jefe, 0, ',', '.');?></td>
                                <?php
                        	}
                        	else{
                        		?>
                                <td>0</td>
                                <?php
                        	}
                                
                            
                            ?>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>
        		
        		<!-- Totales -->
        		<tr class="separa total">
        			<td colspan="2"></td>
        			<td colspan="3">TOTALES</td>
        			<td></td>
        			<td style="text-align: right"><?php echo number_format($monto_uf_acumulado_promesa, 2, ',', '.');?></td>
        			<td style="text-align: right"><?php echo number_format($monto_uf_acumulado_escritura, 2, ',', '.');?></td>
        			<td style="text-align: right"><?php echo number_format($monto_acumulado_promesa, 0, ',', '.');?></td>
        			<td style="text-align: right"><?php echo number_format($monto_acumulado_escritura, 0, ',', '.');?></td>
        			<td></td>
        			<td style="text-align: right"><?php echo number_format($total_desistimiento_acumulado, 0, ',', '.');?></td>
        		</tr>
        		<!-- a PAgar -->
        		<?php 
        		$total_pago = ($monto_acumulado_promesa + $monto_acumulado_escritura) - $total_desistimiento_acumulado;

        		$total_liquidacion_a_pagar = $total_liquidacion_a_pagar + $total_pago;
        		?>
        		<tr class="separa">
        			<td colspan="2"></td>
        			<td colspan="6">TOTAL A PAGAR</td>
        			<td colspan="2" style="text-align: center"><?php echo number_format($total_pago, 0, ',', '.');?></td>
                    <td></td>
        			<td></td>
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
<table class="liquida">
	<thead>
		<tr>
			<th colspan="4" style="border:1px solid #000000;">RESUMEN A PAGAR</th>
			<th colspan="5"></th>
			<th colspan="2" style="text-align: center; border:1px solid #000000;">TOTAL BONOS</th>
		</tr>
	</thead>
	<tbody>
		<tr class="cabecera">
			<td colspan="4"></td>
			<td colspan="5"></td>
			<td colspan="2" class="bl-1">$ <?php echo number_format($total_liquidacion_a_pagar, 0, ',', '.');?></td>
		</tr>
	</tbody>
</table>
</body>
</html>