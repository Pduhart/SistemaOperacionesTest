<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
// require_once _INCLUDE."head_informe.php";




$ciudad = (isset($_GET['ciu']))?$_GET['ciu']:'';
$banco = $_GET['ban'];
$id_con = $_GET['con'];
$totalPagos = 0;
$filtro_consulta = " AND viv.id_tor = ".$id_con;


if ($banco<>100 && $banco<>1000) {
	$filtro_consulta .= " AND ven.id_ban = ".$banco." AND ven.id_for_pag = 1";
} else if($banco==1000) {
	$filtro_consulta .= " ";
} else {
	$filtro_consulta .= " AND ven.id_ban = 0 AND ven.id_for_pag = 2";
}

$nombre = 'venta_flujo_banco'.date('d-m-Y');

if($ciudad<>''){
	$filtro_consulta .= " AND ven_cam.ciudad_notaria_ven = ".$ciudad;
	if ($ciudad==1) {
		$nombre .= '_laserena';
	} else {
		$nombre .= '_santiago';
	}
}else{
	$nombre .= '_laserena';
}


header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");




?>
<!DOCTYPE html>
<html>
<head>
<title>Venta Flujo Banco</title>
<meta charset="utf-8">
<!-- DataTables -->
<!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>plugins/select2/select2.min.css"> -->
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
body{
	font-size: .8rem;
}

table{
	font-size: .75rem;
	border-collapse: collapse;
}

table.table {
	min-width: 140%;
}

table.table tr th{
	border: 1px solid #ccc;
}

table.table tr td{
	border: 1px solid #ebebeb;
}

table.table tfoot tr td{
	border: 1px solid #ccc;
}

.min-col{
	min-width: 90px;
}

</style>
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/datepicker/datepicker3.css"> -->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

require "../helpers/get_pagos_contados.php";
 ?>
<table>
	<tr>
		<td></td>
		<td rowspan="6" colspan="3">
			<img src="<?php echo _ASSETS."img/logo-icp.jpg";?>" width="103" height="90">
		</td>
		<td></td>
		<td rowspan="6" colspan="2">
			<?php 
			$consulta = 
                "
                SELECT
                    nombre_doc_con
                FROM 
                    condominio_documento_condominio
                WHERE 
                    id_con = ? AND
                    nombre_doc_con LIKE '%logo%'
                ";
            $conexion->consulta_form($consulta,array($id_con));
            $haylogo = $conexion->total();
            if ($haylogo==0) {
            	
            	
            } else{
            	$fila = $conexion->extraer_registro_unico();
            	$nombre_doc_con = $fila["nombre_doc_con"];
            	?>
            	<img src="<?php echo _RUTA."archivo/condominio/documento/".$id_con."/".$nombre_doc_con;?>" height="90">
            	<?php
            }
			 ?>
		</td>
	</tr>
</table>


<?php 
if ($_SESSION["sesion_filtro_banco_panel"]<>1000) {
$consulta = "SELECT nombre_ban FROM banco_banco WHERE id_ban = ".$banco." ";
$conexion->consulta($consulta);
$fila_banco = $conexion->extraer_registro_unico();
$nombre_ban = utf8_encode($fila_banco["nombre_ban"]);
 ?>
<table>
	<tr>
		<td colspan="4">
			<b>BANCO: <?php echo strtoupper($nombre_ban);  ?></b>
		</td>
	</tr>
</table>

<?php 
}
 ?>

<table class="table table-bordered">
	<tr class="bg-gray color-palette" style="background-color: #e7e7d4;">
		<td>N°</td>
		<td>Nombre</td>
		<td>Depto.</td>
		<?php 
		if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
		 ?>
		<td>Banco</td>
		<?php 
		}
		 ?>
		<td>Precio</td>
		<td>Pagado</td>
		<td>Por Pagar</td>
		<td>Pie Recibido en UF</td>
		<td>Pie Recibido en pesos</td>
		<td>Monto por Recibir UF</td>
		<?php 
		if ($banco<>100) {
		 ?>
		<td>Precio Venta</td>
		<?php 
		}
		 ?>
		<td>Fecha firma Cliente</td>
		<td>Notaría</td>
		<td>Monto recibido UF</td>
		<td>Fecha</td>
		<td>Monto en $ según Liquid.</td>
	</tr>
    <?php
    $contador = 1;
    $acumula_total_monto_inmob = 0;
    $acumula_pie_pagado_efectivo = 0;
    $acumula_monto_por_recibir = 0;
    $acumula_monto_liq_uf_ven = 0;
    $acumula_monto_liq_pesos_ven = 0;

    $fecha_hoy = date("Y-m-d");
    
    $consulta = "
        SELECT DISTINCT
            viv.nombre_viv,
            ven.monto_vivienda_ven,
            ven.monto_ven,
            ven.descuento_ven,
            ven.monto_estacionamiento_ven,
			ven.monto_bodega_ven,
            ven.id_ven,
            ven.fecha_escritura_ven,
            pro.nombre_pro,
            pro.apellido_paterno_pro,
            pro.apellido_materno_pro,
            ven_liq.fecha_liq_ven,
            ven_liq.monto_liq_uf_ven,
            ven.pie_cancelado_ven,
            ven.monto_reserva_ven,
            ven_liq.monto_liq_pesos_ven,
            ven.monto_credito_real_ven,
            ven.monto_credito_ven,
            ven.id_for_pag,
	        ven.id_ban
        FROM
            venta_venta AS ven
        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
        LEFT JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
        WHERE
            ven.id_est_ven > 3
            ".$filtro_consulta."
        
        ";

    if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
    	$consulta .= " ORDER BY ven.id_ban ASC";
    } 

    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {

        	$monto_liq_uf_ven = 0;
            $nombre_viv = utf8_encode($fila["nombre_viv"]);
            $monto_ven = $fila["monto_ven"];
            $descuento_ven = $fila["descuento_ven"];
            $pie_cancelado_ven = $fila["pie_cancelado_ven"];
	        $monto_reserva_ven = $fila["monto_reserva_ven"];
            $id_ven = $fila["id_ven"];
            $id_for_pag = $fila["id_for_pag"];
            $fecha_escritura_ven = $fila["fecha_escritura_ven"];
            if($fecha_escritura_ven) {
            	$fecha_escritura_ven = date("d-m-Y",strtotime($fecha_escritura_ven));
            }

            $id_ban = $fila["id_ban"];
            if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
                $consulta_ban = "SELECT id_ban, nombre_ban FROM banco_banco WHERE id_ban = ".$id_ban."";
        		$conexion->consulta($consulta_ban);
        		$filaban = $conexion->extraer_registro_unico();
        		$nombre_ban = utf8_encode($filaban['nombre_ban']);
        	}
            
            $nombre_cliente = utf8_encode($fila["nombre_pro"]." ".$fila["apellido_paterno_pro"]." ".$fila["apellido_materno_pro"]);
            $fecha_liq_ven = $fila["fecha_liq_ven"];
            if($fecha_liq_ven){
            	if($fecha_liq_ven <= $fecha_hoy){
		        	$pagado = 1;
		        	$por_pagar = 0;
		        } else {
		        	$pagado = 0;
		        	$por_pagar = 1;
		        }


            	$fecha_liq_ven = date("d-m-Y",strtotime($fecha_liq_ven));

            } else {
            	$fecha_liq_ven = "";
            	$pagado = 0;
		        $por_pagar = 1;
            }
            
            $monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
            $monto_liq_pesos_ven = $fila["monto_liq_pesos_ven"];

            $acumula_monto_liq_uf_ven = $acumula_monto_liq_uf_ven + $fila["monto_liq_uf_ven"];
            $acumula_monto_liq_pesos_ven = $acumula_monto_liq_pesos_ven + $fila["monto_liq_pesos_ven"];

            // Crédito
            if ($fila["monto_credito_real_ven"]<>0) {
				$credito_hipo = $fila["monto_credito_real_ven"];
			} else {
				$credito_hipo = $fila["monto_credito_ven"];
			}


            // pie pagado
			$total_abono = 0;
			$total_uf = 0;
			$pie_pagado_efectivo = 0;
            $consulta = 
                "
                SELECT DISTINCT
                    pag.id_pag,
                    cat_pag.nombre_cat_pag,
                    -- ban.nombre_ban,
                    for_pag.nombre_for_pag,
                    pag.fecha_pag,
                    pag.fecha_real_pag,
                    pag.numero_documento_pag,
                    pag.monto_pag,
                    est_pag.nombre_est_pag,
                    pag.id_est_pag,
                    pag.id_ven,
                    ven.fecha_ven,
                    ven.pie_cobrar_ven,
                    pag.id_for_pag
                FROM
                    pago_pago AS pag 
                    INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                    INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                    -- INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                    INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
                    INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
                WHERE 
                    pag.id_ven = ? AND
                    (pag.id_cat_pag = 1 OR pag.id_cat_pag = 2)
                ";
            $conexion->consulta_form($consulta,array($id_ven));
			$totalPagos = conexion::select('SELECT SUM(monto_pag) as totalPagos FROM pago_pago WHERE id_ven='.$id_ven);
            $fila_consulta = $conexion->extraer_registro();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila_pag) {
					$valor_uf_efectivo = 0;
					// $pie_pagado_efectivo = 0;
					$pie_pagado_porcobrar = 0;
                	
                    if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
                        $fecha_real_mostrar = "";

                        $consultauf = 
						"
						    SELECT
						        valor_uf
						    FROM
						        uf_uf
						    WHERE
						        fecha_uf = '".date("Y-m-d",strtotime($fila_pag["fecha_ven"]))."'
						    ";
						$conexion->consulta($consultauf);
						$cantidaduf = $conexion->total();
						if($cantidaduf > 0){
                			$filauf = $conexion->extraer_registro_unico();
							$valor_uf = $filauf["valor_uf"];
							if ($fila_pag["id_for_pag"]==6) { // si es pago contra escritura UF
								$monto_pag = $fila_pag["monto_pag"] * $valor_uf;
								$abono_uf = $fila_pag["monto_pag"];
								// $abono_uf = 0;
								$monto_pag = 0;
							} else {
								$monto_pag = $fila_pag["monto_pag"];
								$abono_uf = $fila_pag["monto_pag"] / $valor_uf;
								$abono_uf = 0;
							}
							
						} else {
							$valor_uf = 0;
						}

						$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;

                    }
                    else{
                        $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
                        
                        $consultauf = 
						"
						    SELECT
						        valor_uf
						    FROM
						        uf_uf
						    WHERE
						        fecha_uf = ?
						    ";
						$conexion->consulta_form($consultauf,array($fila_pag["fecha_real_pag"]));
						$cantidad_uf = $conexion->total();
						if($cantidad_uf > 0){
							$filauf = $conexion->extraer_registro_unico();
							$valor_uf_efectivo = $filauf['valor_uf'];
							if ($fila_pag["id_for_pag"]==6) { // si es pago contra escritura UF
								$monto_pag = $fila_pag["monto_pag"] * $valor_uf;
								$abono_uf = $fila_pag["monto_pag"] * $valor_uf_efectivo;
								// para que no sume
							} else {
								$monto_pag = $fila_pag["monto_pag"];
								$abono_uf = $fila_pag["monto_pag"] / $valor_uf_efectivo;
							}
						} else {
							$valor_uf_efectivo = 0;
						} 


						$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;          
                    }
                    $total_abono = $total_abono + $monto_pag;
					$total_uf = $total_uf + $abono_uf;
                   
                }
            }

            // $acumula_pie_pagado_efectivo = $acumula_pie_pagado_efectivo + $pie_pagado_efectivo;

            $precio_venta = $credito_hipo + $pie_pagado_efectivo;

            // $total_monto_inmob = $monto_ven - $descuento_ven;

            $total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

            $acumula_total_monto_inmob = $acumula_total_monto_inmob + $total_monto_inmob;

            if ($id_for_pag==1) {
                if ($monto_liq_uf_ven==0) {
                	$monto_por_recibir = $credito_hipo;
                } else {
                	$monto_por_recibir = 0;
                }
            } else {
            	$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo;
            }
            // $acumula_monto_por_recibir = $acumula_monto_por_recibir + $monto_por_recibir;

            if ($id_for_pag==1) {
            	$consulta_notaria = 
                "
                SELECT 
                    valor_campo_eta_cam_ven
                FROM
                    venta_etapa_campo_venta
                WHERE
                    id_ven = ? AND id_eta = 27 AND id_cam_eta = 15";
                $conexion->consulta_form($consulta_notaria,array($id_ven));
                $filanot = $conexion->extraer_registro_unico();
                $notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);

            } else {
            	$consulta_notaria = 
                "
                SELECT 
                    valor_campo_eta_cam_ven
                FROM
                    venta_etapa_campo_venta
                WHERE
                    id_ven = ? AND id_eta = 5 AND id_cam_eta = 2";
                $conexion->consulta_form($consulta_notaria,array($id_ven));
                $filanot = $conexion->extraer_registro_unico();
                $notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);
            }

            // ajuste pagos contados
            if($id_for_pag==2){
            	// prueba función
            	$pagosVentaContado = get_pagos_contados($id_ven,$conexion);

            	$monto_por_recibir = 0;

            	// $pie_pagado_efectivo = $pagosVentaContado[1] - $fila["monto_liq_uf_ven"];
            	$pie_pagado_efectivo = $pagosVentaContado[2];
            	// $monto_por_recibir = $pagosVentaContado[0] + $pagosVentaContado[1];
            	if($monto_liq_uf_ven > 0) {
            		$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven;
            	} else {
            		$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo;
            	}

            	if($monto_por_recibir<0) {
            		$monto_por_recibir = 0;
            	}         

            	$acumula_pie_pagado_efectivo = $acumula_pie_pagado_efectivo + $pie_pagado_efectivo;
	            $acumula_monto_por_recibir = $acumula_monto_por_recibir + $monto_por_recibir;

	            $precio_venta = "-";
            } else { //créditos

            	if($monto_liq_uf_ven==0){
            		$pie_cancelado = $pie_cancelado_ven + $monto_reserva_ven;

                	$total = $pie_cancelado + $fila_pag["pie_cobrar_ven"] + $credito_hipo;

					$saldo_pie = $total - ($credito_hipo + $pie_pagado_porcobrar + $pie_pagado_efectivo);


					$monto_por_recibir = $monto_por_recibir + round($saldo_pie,2);
            	}


            	$acumula_pie_pagado_efectivo = $acumula_pie_pagado_efectivo + $pie_pagado_efectivo;
				$acumula_monto_por_recibir = $acumula_monto_por_recibir + $monto_por_recibir;

				$precio_venta = number_format($precio_venta, 2, ',', '.');
				
            }
            ?>
            <tr>
                <td><?php echo $contador;?> <?php echo $id_ven;?></td>
                <td><?php echo $nombre_cliente;?></td>
                <td><?php echo $nombre_viv;?></td>
                <?php 
				if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
				 ?>
                <td><?php echo $nombre_ban;?></td>
            	<?php } ?>
                <td><?php echo number_format($total_monto_inmob, 2, ',', '.');?></td>
                <td><?php echo $pagado; ?></td>
                <td><?php echo $por_pagar; ?></td>
                <td><?php echo number_format($pie_pagado_efectivo, 2, ',', '.');?></td>
				<td><?php echo number_format($totalPagos[0]['totalPagos'], 0, ',', '.');?></td>
                <td><?php echo number_format($monto_por_recibir, 2, ',', '.');?></td>
                <?php 
				if ($banco<>100) {
				 ?>
                
                <td><?php echo $precio_venta;?></td>
            	<?php } ?>
                <td><?php echo $fecha_escritura_ven;?></td>
                <td><?php echo $notaria;?></td>
                <td><?php echo number_format($monto_liq_uf_ven, 2, ',', '.');?></td>
                <td><?php echo $fecha_liq_ven;?></td>
                <td><?php echo number_format($monto_liq_pesos_ven, 0, ',', '.');?></td>
            </tr>
            <?php
            $contador++;
        }
    }
    ?>
	<tr class="bg-light-blue color-palette" style="background-color: #e7e7d4;">
		<td>Totales</td>
		<td></td>
		<td></td>
		<?php 
		if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
		 ?>
		<td></td>
		<?php } ?>
		<td><?php echo number_format($acumula_total_monto_inmob, 2, ',', '.'); ?></td>
		<td></td>
		<td></td>
		<td><?php echo number_format($acumula_pie_pagado_efectivo, 2, ',', '.'); ?></td>
		<td><?php echo number_format($acumula_monto_por_recibir, 2, ',', '.'); ?></td>
		<?php 
		if ($banco<>100) {
		 ?>
		
		<td></td>
		<?php } ?>
		<td></td>
		<td></td>
		<td><?php echo number_format($acumula_monto_liq_uf_ven, 2, ',', '.'); ?></td>
		<td></td>
		<td><?php echo number_format($acumula_monto_liq_pesos_ven, 2, ',', '.'); ?></td>
	</tr>
</table>


</body>
</html>
