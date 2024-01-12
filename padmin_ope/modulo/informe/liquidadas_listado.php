<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

$nombre = 'Liquidadas_'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");

if ($_GET['tor']==1) {
	$filtro_condo = " AND viv.id_tor = 1";
} else if($_GET['tor']==2) {
	$filtro_condo = " AND (viv.id_tor = 4 OR viv.id_tor = 5)";
} else {
	$filtro_condo = "";
}
// require_once _INCLUDE."head_informe.php";
?>
<head>
<title>Operación - Listado</title>
<!-- DataTables -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css"> -->
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/select2/select2.min.css"> -->
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<meta charset="utf-8">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
// require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <!-- <section class="content-header">
        <h1>
          Ventas
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Ventas</li>
            <li class="active">informe</li>
        </ol>
      </section> -->

      <!-- Main content -->
      <section class="content">
        <div class="col-sm-12">
            <!-- general form elements -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <?php  
                        $consulta = 
                            "
                            SELECT 
                                usu.id_mod 
                            FROM 
                                usuario_usuario_proceso AS usu,
                                usuario_proceso AS proceso
                            WHERE 
                                usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                usu.id_mod = 1 AND
                                proceso.opcion_pro = 1 AND
                                proceso.id_pro = usu.id_pro AND
                                proceso.id_mod = 1
                            ";
                        $conexion->consulta($consulta);
                        $cantidad_opcion = $conexion->total();
                        // if($cantidad_opcion > 0){
                            ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <div class="col-md-12">
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
                                                        <div class="table-responsive">
                                                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                    	<th>N°</th>
                                                                        <th>ID Vivienda</th>
                                                                        <th>ID Venta</th>
                                                                        <th>Nombre Depto</th>
                                                                        <th>RUT Propietario</th>
                                                                        <th>Nombre Propietario</th>
                                                                    </tr>    
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $contador = 0;
                                                                    
                                                                    $consulta_ventas_liquidadas_oopp_ch_4 = 
																	  "
																	  SELECT 
																	    ven.id_viv,
																	    viv.nombre_viv,
																	    ven.id_ven,
																	    ven.id_pro
																	  FROM
																	    venta_venta as ven,
																	    venta_etapa_venta as ven_eta,
																	    vivienda_vivienda as viv
																	  WHERE
																	    ven.id_est_ven > 3 AND
																	    ven.id_viv = viv.id_viv AND
																	    viv.id_tor = 4 AND
																	    ven.id_for_pag = 1 AND
																	    ven.id_ven = ven_eta.id_ven AND
																	    (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
																	  ";
                                                                    // echo $consulta;
                                                                    $conexion->consulta($consulta_ventas_liquidadas_oopp_ch_4);
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                        	$consulta_pro = 
																                "
																                SELECT
																                    rut_pro,
																                    nombre_pro,
																                    apellido_paterno_pro
																                FROM
																                    propietario_propietario
																                WHERE
																                    id_pro = ?
																                ";
																            $conexion->consulta_form($consulta_pro,array($fila['id_pro']));
																            $fila_pro = $conexion->extraer_registro_unico(); 
                                                                        	$contador++;
                                                                            
                                                                            ?>
                                                                            <tr>
                                                                            	<td><?php echo $contador; ?></td>
                                                                                <td><?php echo $fila['id_viv']; ?></td>
                                                                                <td><?php echo $fila['id_ven']; ?></td>
                                                                                <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                                <td><?php echo utf8_encode($fila_pro['rut_pro']); ?></td>
                                                                                <td><?php echo utf8_encode($fila_pro['nombre_pro']." ".$fila_pro['apellido_paterno_pro']); ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        	
                                                                        }
                                                                    }

                                                                    $consulta_ventas_liquidadas_oopp_co_4 = 
																	  "
																	  SELECT 
																	    ven.id_viv,
																	    viv.nombre_viv,
																	    ven.id_ven,
																	    ven.id_pro
																	  FROM
																	    venta_venta as ven,
																	    venta_etapa_venta as ven_eta,
																	    vivienda_vivienda as viv
																	  WHERE
																	    ven.id_est_ven > 3 AND
																	    ven.id_viv = viv.id_viv AND
																	    viv.id_tor = 4 AND
																	    ven.id_for_pag = 2 AND
																	    ven.id_ven = ven_eta.id_ven AND
																	    (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
																	  ";
																	$conexion->consulta($consulta_ventas_liquidadas_oopp_co_4);
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) { 
                                                                        	$consulta_pro = 
																                "
																                SELECT
																                    rut_pro,
																                    nombre_pro,
																                    apellido_paterno_pro
																                FROM
																                    propietario_propietario
																                WHERE
																                    id_pro = ?
																                ";
																            $conexion->consulta_form($consulta_pro,array($fila['id_pro']));
																            $fila_pro = $conexion->extraer_registro_unico();
                                                                            $contador++;
                                                                            ?>
                                                                            <tr>
                                                                            	<td><?php echo $contador; ?></td>
                                                                                <td><?php echo $fila['id_viv']; ?></td>
                                                                                <td><?php echo $fila['id_ven']; ?></td>
                                                                                <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                                <td><?php echo utf8_encode($fila_pro['rut_pro']); ?></td>
                                                                                <td><?php echo utf8_encode($fila_pro['nombre_pro']." ".$fila_pro['apellido_paterno_pro']); ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        	
                                                                        }
                                                                    }
                                                                    ?>   
                                                                </tbody>                                                               
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <?php
                        // }
                        ?>
                        
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
</body>
</html>
