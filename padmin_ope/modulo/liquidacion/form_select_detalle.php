<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_liquidacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
$id_cie = $_GET["id"];
?>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<!-- <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css"> -->
<!-- siempre al final los ajustes -->
<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">

</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";

        require "../helpers/get_liquidacion_vend.php";
        require "../helpers/get_liquidacion_j_vend.php";
        require "../helpers/get_liquidacion_ope.php";
        require "../helpers/get_liquidacion_j_ope.php";
        ?>
        <!-- Modal Ver -->
        <!-- Modal -->
        <div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>

        <!-- Fin modal -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Liquidación
                    <small>Detalle</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Liquidación</a></li>
                    <li class="active">Detalle</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                        <div id="contenedor_opcion"></div>
                        <!-- general form elements -->
                        <div class="box box-primary">
                        	<?php 
							$consulta = 
			                    "
			                    SELECT
			                        id_mes,
			                        anio_cie
			                    FROM
			                        cierre_cierre
			                    WHERE
			                        id_cie = ? 
			                    ";
			                $conexion->consulta_form($consulta,array($id_cie));
			                $fila = $conexion->extraer_registro_unico();
			                $id_mes = utf8_encode($fila['id_mes']);
			                $anio_cie = utf8_encode($fila['anio_cie']);
                           
			                $consulta = 
							    "
							    SELECT
							        uf.valor_uf
							    FROM
							        cierre_cierre AS cie
							        INNER JOIN uf_uf AS uf ON uf.fecha_uf = cie.fecha_hasta_cie
							    WHERE
							        cie.id_cie = ?
							    ";
							$conexion->consulta_form($consulta,array($id_cie));
                            $filauf = $conexion->extraer_registro_unico();
                            $valor_uf_liq = $filauf["valor_uf"];
                            
                        	 ?>
                            <div class="box-header with-border">
                                <h3 class="box-title">Cierre: <?php echo $id_mes; ?>/<?php echo $anio_cie; ?> </h3>
                                <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Volver">
					            	<button onClick="window.history.back();return false;" type="button" class="btn btn-info btn-sm" data-toggle="tooltip" title="" data-original-title="Volver"><i class="fa fa-arrow-left"></i></button>
					            </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Perfil</th>
                                            <th>Nombre</th>
                                            <th>Período</th>
                                            <th>N° Ventas Prom.</th>
                                            <th>N° Ventas Escr.</th>
                                            <th>Bono</th>
                                            <th>Comisión</th>
                                            <th>Total</th>
                                            <th style="width:10%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                       
                                        $total_acumulado = 0;

                                        $consulta = 
                                            "
                                            SELECT 
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                cie.fecha_hasta_cie,
                                                vend.nombre_vend,
                                                vend.apellido_paterno_vend,
                                                vend.apellido_materno_vend,
                                                vend.id_vend,
                                                vend.id_usu
                                            FROM
                                                cierre_cierre AS cie
                                                INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
                                                INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
                                                INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                            WHERE
                                                cie.id_cie = '".$id_cie."' and
                                                vend.id_est_vend = 1
                                            GROUP BY
                                                vend.id_vend,
                                                cie.id_cie,
                                                vend.id_usu,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                vend.nombre_vend,
                                                vend.apellido_paterno_vend,
                                                vend.apellido_materno_vend
                                            ";
                                            
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                $consulta = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_monto_comision_ven ELSE 0 END),0) AS promesa_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_monto_comision_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_monto_comision_ven ELSE 0 END),0) AS escritura_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_monto_comision_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_bono_precio_ven ELSE 0 END),0) AS promesa_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_bono_precio_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_bono_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_bono_precio_ven ELSE 0 END),0) AS escritura_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_bono_precio_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_bono_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta,
                                                        uf.valor_uf
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = ven_cie.id_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_vend = '".$fila["id_vend"]."' AND 
                                                        ven_cie.id_est_ven <> 3
                                                    GROUP BY
                                                        ven.id_vend
                                                    "; 
                                                // echo $consulta;
                                                
                                                $conexion->consulta($consulta);
                                                $fila_detalle = $conexion->extraer_registro_unico();
                                                $cantidad_venta = $fila_detalle["cantidad_venta"];
                                                // $promesa_monto = $fila_detalle["promesa_monto"];
                                                // $escritura_monto = $fila_detalle["escritura_monto"];

                                                // $promesa_bono_monto = $fila_detalle["promesa_bono_monto"];
                                                // $escritura_bono_monto = $fila_detalle["escritura_bono_monto"];
                                                // $valor_uf_consultas = $fila_detalle["valor_uf"];


                                                // $total_comision = $promesa_monto + $escritura_monto;

                                                // desistimiento resta
                                                $consulta_desi = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 3) THEN ven.promesa_monto_comision_ven ELSE 0 END),0) AS promesa_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 3) THEN (ven.promesa_monto_comision_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_vend = ".$fila["id_vend"]." AND 
                                                        ven_cie.id_est_ven = 3
                                                    GROUP BY
                                                        ven.id_vend
                                                    "; 
                                                $conexion->consulta($consulta_desi);
                                                $fila_detalle_desi = $conexion->extraer_registro_unico();
                                                $cantidad_desi = $fila_detalle_desi["cantidad_venta"];
                                                // $promesa_monto_desi = $fila_detalle_desi["promesa_monto"];
												// echo $promesa_monto_desi."<br>";
                                                // $total_comision = $total_comision - $promesa_monto_desi;

                                                // $consulta = 
                                                //     "
                                                //     SELECT
                                                //         IFNULL(SUM(bon_cie.monto_bon_cie),0) AS bono_uf,
                                                //         IFNULL(SUM(bon_cie.monto_bon_cie * uf.valor_uf),0) AS bono_monto
                                                //     FROM
                                                //         cierre_bono_cierre AS bon_cie
                                                //         INNER JOIN cierre_cierre AS cie ON cie.id_cie = bon_cie.id_cie
                                                //         INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                //     WHERE
                                                //         bon_cie.id_cie = ".$fila["id_cie"]." AND
                                                //         bon_cie.id_usu = ".$fila["id_usu"]."
                                                //     GROUP BY
                                                //         bon_cie.id_cie,
                                                //         bon_cie.id_usu
                                                //     "; 
                                                // $conexion->consulta($consulta);
                                                // $fila_detalle = $conexion->extraer_registro_unico();
                                                // $monto_bono_uf = $fila_detalle["bono_uf"];
                                                
                                                // $monto_bono = $fila_detalle["bono_monto"] + $promesa_bono_monto + $escritura_bono_monto;
                                                
                                                $total_monto = 0;
                                                if(isset($fila['fecha_ven'])){
                                                    if ($fila['fecha_ven'] == '0000-00-00') {
                                                        $fecha_venta = "";
                                                    }
                                                    else{
                                                        $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                    }
                                                }
                                                                                                

                                                $liquidacionVendTotales = get_liquidacion_vend($fila["id_vend"],$id_cie,$conexion);

                                                $total_periodo = $liquidacionVendTotales[2]; 
                                                $total_acumulado = $total_acumulado + $total_periodo;
                                                $cantidad_venta_prom = $liquidacionVendTotales[3]; 
                                                $cantidad_venta_escr = $liquidacionVendTotales[4]; 
                                                ?>
                                                <tr>
                                                    <td>Vendedor</td>
                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']); ?></td>
                                                    <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                    <td><?php echo number_format($cantidad_venta_prom, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($cantidad_venta_escr, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($liquidacionVendTotales[1], 0, ',', '.');?></td>
                                                    
                                                    <td><?php echo number_format($liquidacionVendTotales[0], 0, ',', '.');?></td>
                                                    <td><?php echo number_format($liquidacionVendTotales[2], 0, ',', '.');?></td>
                                                    <td>
                                                        <button value="<?php echo $fila['id_cie'];?>" class="btn btn-sm btn-icon btn-success liquidacion_vendedor" data-toggle="tooltip" data-valor="<?php echo $fila['id_vend'];?>" data-original-title="Liquidación Vendedor"><i"><i class="fa fa-print"></i></button>
                                                    </td>
                                                    
                                                    
                                                    
                                                </tr>
                                                <?php
                                            }
                                        }

                                        $consulta = 
                                            "
                                            SELECT 
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu,
                                                usu.id_usu
                                            FROM
                                                cierre_cierre AS cie
                                                INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
                                                INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
                                                INNER JOIN usuario_usuario AS usu ON usu.id_usu = ven.id_supervisor_ven
                                            WHERE
                                                cie.id_cie = '".$id_cie."'
                                            GROUP BY
                                                usu.id_usu,
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu
                                            ";
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                $consulta = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_monto_comision_supervisor_ven ELSE 0 END),0) AS promesa_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_monto_comision_supervisor_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_monto_comision_supervisor_ven ELSE 0 END),0) AS escritura_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_monto_comision_supervisor_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_bono_precio_supervisor_ven ELSE 0 END),0) AS promesa_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_bono_precio_supervisor_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_bono_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_bono_precio_supervisor_ven ELSE 0 END),0) AS escritura_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_bono_precio_supervisor_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_bono_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = ven_cie.id_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_supervisor_ven = ".$fila["id_usu"]." AND 
                                                        ven_cie.id_est_ven <> 3
                                                    GROUP BY
                                                        ven.id_supervisor_ven
                                                    "; 
                                                $conexion->consulta($consulta);
                                                $fila_detalle = $conexion->extraer_registro_unico();
                                                $cantidad_venta = $fila_detalle["cantidad_venta"];
                                                $promesa_monto = $fila_detalle["promesa_monto"];
                                                $escritura_monto = $fila_detalle["escritura_monto"];

                                                $promesa_bono_monto = $fila_detalle["promesa_bono_monto"];
                                                $escritura_bono_monto = $fila_detalle["escritura_bono_monto"];

                                                $total_comision = $promesa_monto + $escritura_monto;
                                                

                                                $consulta = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(bon_cie.monto_bon_cie),0) AS bono_uf,
                                                        IFNULL(SUM(bon_cie.monto_bon_cie * uf.valor_uf),0) AS bono_monto
                                                    FROM
                                                        cierre_bono_cierre AS bon_cie
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = bon_cie.id_cie
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        bon_cie.id_cie = ".$fila["id_cie"]." AND
                                                        bon_cie.id_usu = ".$fila["id_usu"]."
                                                    GROUP BY
                                                        bon_cie.id_cie,
                                                        bon_cie.id_usu
                                                    "; 
                                                $conexion->consulta($consulta);
                                                $fila_detalle = $conexion->extraer_registro_unico();
                                                $monto_bono_uf = $fila_detalle["bono_uf"];
                                                
                                                $monto_bono = $fila_detalle["bono_monto"] + $promesa_bono_monto + $escritura_bono_monto;

                                                $total_monto = 0;
                                                if ($fila['fecha_ven'] == '0000-00-00') {
                                                    $fecha_venta = "";
                                                }
                                                else{
                                                    $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                }
                                                

                                                $total_periodo = $total_comision + $monto_bono; 
                                                $total_acumulado = $total_acumulado + $total_periodo;
                                                ?>
                                                <tr>
                                                    <td>Supervisor de Ventas</td>
                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']); ?></td>
                                                    <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                    <td><?php echo number_format($cantidad_venta, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($monto_bono, 0, ',', '.');?></td>
                                                    
                                                    <td><?php echo number_format($total_comision, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                    <td>
                                                        <button value="<?php echo $fila['id_cie'];?>" class="btn btn-sm btn-icon btn-success liquidacion_supervisor" data-toggle="tooltip" data-valor="<?php echo $fila['id_usu'];?>" data-original-title="Liquidación Supervisor"><i"><i class="fa fa-print"></i></button>
                                                    </td>
                                                    
                                                    
                                                    
                                                </tr>
                                                <?php
                                            }
                                        }

                                        $consulta = 
                                            "
                                            SELECT 
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu,
                                                usu.id_usu
                                            FROM
                                                cierre_cierre AS cie
                                                INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
                                                INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
                                                INNER JOIN usuario_usuario AS usu ON usu.id_usu = ven.id_jefe_ven
                                            WHERE
                                                cie.id_cie = '".$id_cie."'
                                            GROUP BY
                                                usu.id_usu,
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu
                                            ";
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                $consulta = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_monto_comision_jefe_ven ELSE 0 END),0) AS promesa_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_monto_comision_jefe_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_monto_comision_jefe_ven ELSE 0 END),0) AS escritura_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_monto_comision_jefe_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_bono_precio_jefe_ven ELSE 0 END),0) AS promesa_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_bono_precio_jefe_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_bono_monto,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_bono_precio_jefe_ven ELSE 0 END),0) AS escritura_bono_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_bono_precio_jefe_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_bono_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta,
                                                        ven.id_ven
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = ven_cie.id_cie
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_jefe_ven = ".$fila["id_usu"]." AND 
                                                        ven_cie.id_est_ven <> 3
                                                    GROUP BY
                                                        ven.id_jefe_ven
                                                    "; 
                                                // echo $consulta;
                                                $conexion->consulta($consulta);
                                                $fila_detalle = $conexion->extraer_registro_unico();
                                                $cantidad_venta = $fila_detalle["cantidad_venta"];
                                                // $promesa_monto = $fila_detalle["promesa_monto"];
                                                // $escritura_monto = $fila_detalle["escritura_monto"];

                                                // $promesa_bono_monto = $fila_detalle["promesa_bono_monto"];
                                                // $escritura_bono_monto = $fila_detalle["escritura_bono_monto"];

                                                // $total_comision = $promesa_monto + $escritura_monto;

                                                // desistimiento
                                                $consulta_desi = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 3) THEN ven.promesa_monto_comision_jefe_ven ELSE 0 END),0) AS promesa_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 3) THEN (ven.promesa_monto_comision_jefe_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = ven_cie.id_cie
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_jefe_ven = ".$fila["id_usu"]." AND 
                                                        ven_cie.id_est_ven = 3
                                                    GROUP BY
                                                        ven.id_jefe_ven
                                                    "; 
                                                $conexion->consulta($consulta_desi);
                                                $fila_detalle_desi = $conexion->extraer_registro_unico();
                                                $cantidad_desi_jefe = $fila_detalle_desi["cantidad_venta"];
                                                // $promesa_desi_jefe = $fila_detalle_desi["promesa_monto"];

                                                // $total_comision = $total_comision - $promesa_desi_jefe;

                                                // $consulta = 
                                                //     "
                                                //     SELECT
                                                //         IFNULL(SUM(bon_cie.monto_bon_cie),0) AS bono_uf,
                                                //         IFNULL(SUM(bon_cie.monto_bon_cie * uf.valor_uf),0) AS bono_monto
                                                //     FROM
                                                //         cierre_bono_cierre AS bon_cie
                                                //         INNER JOIN cierre_cierre AS cie ON cie.id_cie = bon_cie.id_cie
                                                //         INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                //     WHERE
                                                //         bon_cie.id_cie = ".$fila["id_cie"]." AND
                                                //         bon_cie.id_usu = ".$fila["id_usu"]."
                                                //     GROUP BY
                                                //         bon_cie.id_cie,
                                                //         bon_cie.id_usu
                                                //     "; 
                                                // $conexion->consulta($consulta);
                                                // $fila_detalle = $conexion->extraer_registro_unico();
                                                // $monto_bono_uf = $fila_detalle["bono_uf"];
                                                
                                                // $monto_bono = $fila_detalle["bono_monto"] + $promesa_bono_monto + $escritura_bono_monto;

                                                // $total_monto = 0;
                                                if(isset($fila['fecha_ven'])){
                                                    if ($fila['fecha_ven'] == '0000-00-00') {
                                                        $fecha_venta = "";
                                                    }
                                                    else{
                                                        $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                    }
                                                }
                
                                                $liquidacionJVendTotales = get_liquidacion_j_vend($fila["id_usu"],$id_cie,$conexion);

                                                $total_periodo = $liquidacionJVendTotales[0]; 

                                                $total_acumulado = $total_acumulado + $total_periodo;

                                                $cantidad_venta_prom = $liquidacionJVendTotales[1]; 
                                                $cantidad_venta_escr = $liquidacionJVendTotales[2]; 
                                                ?>
                                                <!-- <tr>
                                                    <td>Jefe de Ventas</td>
                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']); ?></td>
                                                    <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                    <td><?php echo number_format($cantidad_venta_prom, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($cantidad_venta_escr, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($liquidacionJVendTotales[0], 0, ',', '.');?></td>
                                                    
                                                    <td><?php echo 0;?></td>
                                                    <td><?php echo number_format($liquidacionJVendTotales[0], 0, ',', '.');?></td>
                                                    <td>
                                                        <button value="<?php echo $fila['id_cie'];?>" class="btn btn-sm btn-icon btn-success liquidacion_jefe" data-toggle="tooltip" data-valor="<?php echo $fila['id_usu'];?>" data-original-title="Liquidación Jefe de Ventas"><i"><i class="fa fa-print"></i></button>
                                                    </td>
                                                    
                                                    
                                                    
                                                </tr> -->
                                                <?php
                                            }
                                        }


                                        $consulta = 
                                            "
                                            SELECT 
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu,
                                                usu.id_usu
                                            FROM
                                                cierre_cierre AS cie
                                                INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
                                                INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
                                                INNER JOIN usuario_usuario AS usu ON usu.id_usu = ven.id_operacion_ven
                                            WHERE
                                                cie.id_cie = '".$id_cie."'
                                            GROUP BY
                                                usu.id_usu,
                                                cie.id_cie,
                                                mes.nombre_mes,
                                                cie.anio_cie,
                                                usu.nombre_usu,
                                                usu.apellido1_usu,
                                                usu.apellido2_usu
                                            ";
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                $consulta = 
                                                    "
                                                    SELECT
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_monto_comision_jefe_ven ELSE 0 END),0) AS escritura_uf,
                                                        IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_monto_comision_operacion_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_monto,
                                                        COUNT(ven.id_ven) AS cantidad_venta
                                                    FROM
                                                        cierre_venta_cierre AS ven_cie
                                                        INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        INNER JOIN cierre_cierre AS cie ON cie.id_cie = ven_cie.id_cie
                                                        INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven AND eta_ven.id_est_eta_ven = 1
                                                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                    WHERE
                                                        ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                        ven.id_operacion_ven = ".$fila["id_usu"]." AND
                                                        ven_cie.id_est_ven = 6 AND 
                                                        (eta_ven.id_eta = 6 OR eta_ven.id_eta = 27)
                                                    GROUP BY
                                                        ven.id_operacion_ven
                                                    "; 
                                                // echo $consulta;
                                                $conexion->consulta($consulta);
                                                $fila_detalle = $conexion->extraer_registro_unico();
                                                $cantidad_venta = $fila_detalle["cantidad_venta"];
                                                
                                                // $escritura_uf = $fila_detalle["escritura_uf"];
                                                // $escritura_monto = $fila_detalle["escritura_monto"];

                                                // $total_comision = $escritura_monto;
                                                                                                
                                                // $monto_bono = 0;     

                                                $liquidacionOpeTotales = get_liquidacion_ope($fila["id_usu"],$id_cie,$conexion);

                                                $total_periodo = $liquidacionOpeTotales[0];                                          

                                                // $total_periodo = $total_comision + $monto_bono; 
                                                $total_acumulado = $total_acumulado + $total_periodo;
                                                ?>
                                                <tr>
                                                    <td>Operaciones</td>
                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']); ?></td>
                                                    <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                    <td>0</td>
                                                    <td><?php echo number_format($cantidad_venta, 0, ',', '.');?></td>
                                                    <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                    
                                                    <td><?php echo 0;?></td>
                                                    <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                    <td>
                                                        <button value="<?php echo $fila['id_cie'];?>" class="btn btn-sm btn-icon btn-success liquidacion_operacion" data-toggle="tooltip" data-valor="<?php echo $fila['id_usu'];?>" data-original-title="Liquidación Operaciones"><i"><i class="fa fa-print"></i></button>
                                                    </td>                                             
                                                </tr>
                                                <?php
                                            }
                                        }

                                        
                                        $consulta_jo = 
							            "
							            SELECT
							                SUM(cie_bon_ven.monto_bon_cie) AS total_bono,
							                COUNT(cie_bon_ven.id_ven) AS total_ventas,
							                usu.nombre_usu,
                                            usu.apellido1_usu,
                                            usu.apellido2_usu,
                                            usu.id_usu,
                                            cie.anio_cie,
                                            mes.nombre_mes
							            FROM
							                cierre_bono_cierre_venta AS cie_bon_ven
							                INNER JOIN cierre_cierre AS cie ON cie.id_cie = cie_bon_ven.id_cie
							                INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
							                INNER JOIN usuario_usuario AS usu ON usu.id_usu = cie_bon_ven.id_usu
							            WHERE
							                cie_bon_ven.id_cie = ?
							            ";
							            $conexion->consulta_form($consulta_jo,array($id_cie));
							            $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                            	// $total_bono_pesos = $fila['total_bono'] * $valor_uf_liq;

                                            	$liquidacionJOpeTotales = get_liquidacion_j_ope($fila["id_usu"],$id_cie,$conexion);

                                                $total_periodo = $liquidacionJOpeTotales[0];
                                            	?>
                                                <tr>
                                                    <td>Jefe Operaciones</td>
                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']); ?></td>
                                                    <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                    <td>0</td>
                                                    <td><?php echo number_format($fila['total_ventas'], 0, ',', '.');?></td>
                                                    <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                    
                                                    <td>0</td>
                                                    <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                    <td>
                                                        <button value="<?php echo $id_cie;?>" class="btn btn-sm btn-icon btn-success liquidacion_j_operacion" data-toggle="tooltip" data-valor="<?php echo $fila['id_usu'];?>" data-original-title="Liquidación Operaciones"><i"><i class="fa fa-print"></i></button>
                                                    </td>                                             
                                                </tr>
                                                <?php
                                            }
                                        }

                                        $total_acumulado = $total_acumulado + $total_periodo;
                                        ?>   
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>$<?php echo number_format($total_acumulado, 0, ',', '.'); ?></td>
                                            <td></td>
                                        </tr> 
                                    </tfoot>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                      <!-- /.box -->
                    </div>
                    <!--/.col (left) -->
                </div>
            <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

<!-- DataTables -->
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script>

<!-- iCheck 1.0.1 -->
<!-- <script src="<?php // echo _ASSETS?>plugins/iCheck/icheck.min.js"></script> -->

<script>
    // cheks
    var valor;
    jQuery.fn.getCheckboxValues = function(){
        var values = [];
        var i = 0;
        this.each(function(){
            // guarda los valores en un array
            valor = $(this).val();
            values[i++] = valor;
        });
        if(i == 0 ){
            return i;
        }
        else{
            return values;
        }
    }

    $(document).ready(function() {
        // $(document).on('icheck', function(){
        //     $('input[type=checkbox].flat-red').iCheck({
        //         checkboxClass: 'icheckbox_flat-red'
        //     });
        // }).trigger('icheck');
        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            // success de tabla
            // "fnInitComplete":function(oSettings,json){
            //     $(document).trigger('icheck');
            // },
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
            "bProcessing": true,
            // "bServerSide": true,
            responsive: true,
            //"sAjaxSource": "select_detalle.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 1, "asc" ]],
            "aoColumns": [
               null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { "bSortable": false }
            ]
        });
 
        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

        // eliminar
        function resultado(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registros eliminados con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }
        function resultado_eliminar(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro eliminado con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }

        $(document).on( "click",".liquidacion_vendedor" , function() {
            valor = $(this).val();
            var_vendedor = $(this).attr("data-valor");
            // location.href = "../documento/liquidacion_vendedor.php?id="+valor+"&id_vend="+var_vendedor;
            window.open('../documento/liquidacion_vendedor.php?id='+valor+'&id_vend='+var_vendedor);
        });

        $(document).on( "click",".liquidacion_supervisor" , function() {
            valor = $(this).val();
            var_supervisor = $(this).attr("data-valor");
            // location.href = "../documento/liquidacion_supervisor.php?id="+valor+"&id_usu="+var_supervisor;
            window.open('../documento/liquidacion_supervisor.php?id='+valor+'&id_usu='+var_supervisor);
        });

        $(document).on( "click",".liquidacion_jefe" , function() {
            valor = $(this).val();
            var_jefe = $(this).attr("data-valor");
            // location.href = "../documento/liquidacion_jefe.php?id="+valor+"&id_usu="+var_jefe;
            window.open('../documento/liquidacion_jefe.php?id='+valor+'&id_usu='+var_jefe);
        });

        $(document).on( "click",".liquidacion_operacion" , function() {
            valor = $(this).val();
            var_oper = $(this).attr("data-valor");
            // location.href = "../documento/liquidacion_oper.php?id="+valor+"&id_usu="+var_oper;
            window.open('../documento/liquidacion_operacion.php?id='+valor+'&id_usu='+var_oper);
        });

         $(document).on( "click",".liquidacion_j_operacion" , function() {
            valor = $(this).val();
            var_oper = $(this).attr("data-valor");
            // location.href = "../documento/liquidacion_oper.php?id="+valor+"&id_usu="+var_oper;
            window.open('../documento/liquidacion_j_operacion.php?id='+valor+'&id_usu='+var_oper);
        });

        $(document).on( "click",".eliminar" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar el registro seleccionado!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("delete_detalle.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar(data);
                    }
                })
            });
        });

        $('.borra_todo').click(function(){
            valor = $(".check_registro:checked").getCheckboxValues();
            var_check = $(".check_registro:checked").length;
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar los registros seleccionados!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("delete_todo_detalle.php"),
                    data:"valor="+valor+"&cantidad="+var_check,
                    dataType:'json',
                    success: function(data) {
                        resultado(data);
                    }
                })
            });
        });
        $('#check_todo').on('change', function(event){
            $('.check_registro:enabled').each( function() {  
                if($("input[name=check_todo]:checked").length == 1){
                    $(this).prop("checked", "checked");
                } 
                else {
                    $(this).prop("checked", "");
                }
            });
        });

        // ver modal
        $(document).on( "click",".detalle" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });


        $(document).on( "click",".edita" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
             //$('body').tooltip('destroy');
             
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_update_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 
        
         $(document).on( "click",".protestar" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
             //$('body').tooltip('destroy');
             
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_protestar_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 

        $(document).on( "click",".estado" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea cambiar el estado del registro seleccionado!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("estado_detalle.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_estado(data);                 
                    }
                })
            });
        });
        function resultado_estado(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Estado modificado con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
        } 
    } );
</script>
</body>
</html>
