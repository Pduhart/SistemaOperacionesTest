<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

$nombre = 'escrituras'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment;filename=".$nombre.".xls");

if (isset($_GET['tor'])) {
	$filtro_condo = " AND viv.id_tor = ".$_GET['tor'];
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
                                                                        <th>Condominio</th>
                                                                        <th>Cliente</th>
                                                                        <th>Rut</th>
                                                                        <!-- <th>Sexo</th> -->
                                                                        <th>Celular</th>
                                                                        <th>Mail</th>
                                                                        <th>Depto</th>
                                                                        <th>Estacionamiento</th>
                                                                        <th>Bodega</th>
                                                                        <th>Ciudad Notaría</th>
                                                                        <th>Fecha Firma Escritura</th>
                                                                        <th>Fecha Entrega</th>
                                                                    </tr>    
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $acumulado_monto = 0;
                                                                    
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            ven.id_ven,
                                                                            ven.id_for_pag,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            pro.id_pro,
                                                                            pro.id_sex,
                                                                            pro.nombre_pro,
                                                                            pro.apellido_paterno_pro,
                                                                            pro.apellido_materno_pro,
                                                                            pro.rut_pro,
                                                                            pro.fono_pro,
                                                                            pro.correo_pro,
                                                                            ven.fecha_escritura_ven,
                                                                            ven_cam.ciudad_notaria_ven
                                                                        FROM 
                                                                            venta_venta AS ven
                                                                            INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven 
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                            INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                                            LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
                                                                        WHERE 
                                                                            ven.id_ven > 0 AND
                                                                            ven.id_est_ven >= 5
                                                                        "; 

                                                                    $consulta .= $filtro_condo;
                                                                    $consulta .= " ORDER BY 
                                                                            ven.id_ven";
                                                                    // echo $consulta;
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) { 
                                                                        	$nombre_bod = ""; 
                                                                        	$nombre_esta = ""; 
                                                                        	$id_viv = $fila["id_viv"];

                                                                        	$ciudad_notaria_ven = $fila["ciudad_notaria_ven"];
																			$consulta_esta = 
																                "
																                SELECT
																                    nombre_esta
																                FROM
																                    estacionamiento_estacionamiento
																                WHERE
																                    id_viv = ?
																                ";
																            $conexion->consulta_form($consulta_esta,array($id_viv));
																            $fila_consulta = $conexion->extraer_registro();
																            $cantidad = $conexion->total();
																            if(is_array($fila_consulta)){
																                foreach ($fila_consulta as $filaest) {
																                	$nombre_esta .= utf8_encode($filaest['nombre_esta'])." - ";
																                }
																            }

																            $consulta_bod = 
																                "
																                SELECT
																                    nombre_bod
																                FROM
																                    bodega_bodega
																                WHERE
																                    id_viv = ?
																                ";
																            $conexion->consulta_form($consulta_bod,array($id_viv));
																            $fila_consulta = $conexion->extraer_registro();
																            $cantidad = $conexion->total();
																            if(is_array($fila_consulta)){
																                foreach ($fila_consulta as $filabod) {
																                	$nombre_bod .= utf8_encode($filabod['nombre_bod'])." - ";
																                }
																            }

																			$nombre_bod = substr($nombre_bod, 0, -2);
																			$nombre_esta = substr($nombre_esta, 0, -2);

																			

                                                                        	if ($fila["id_for_pag"]==2) { //contado
                                                                        		$consulta_escr = 
	                                                                                "
	                                                                                SELECT 
	                                                                                    eta_cam.valor_campo_eta_cam_ven
	                                                                                FROM
	                                                                                    venta_etapa_campo_venta AS eta_cam
	                                                                                WHERE
	                                                                                    eta_cam.id_ven = ? AND 
	                                                                                    eta_cam.id_eta = 6 AND 
	                                                                                    eta_cam.id_cam_eta = 5
	                                                                                ";
	                                                                            $conexion->consulta_form($consulta_escr,array($fila["id_ven"]));
	                                                                            $cantidad_fecha = $conexion->total();
	                                                                            if($cantidad_fecha > 0){
	                                                                                $fila_fecha = $conexion->extraer_registro_unico();
	                                                                                if ($fila_fecha['valor_campo_eta_cam_ven'] == '0000-00-00' || $fila_fecha['valor_campo_eta_cam_ven'] == null) {
		                                                                                $fecha_escritura = "";
		                                                                            }
		                                                                            else{
		                                                                                $fecha_escritura = date("d/m/Y",strtotime($fila_fecha['valor_campo_eta_cam_ven']));
		                                                                            }
	                                                                                
	                                                                            }
	                                                                            else{
	                                                                                $fecha_escritura = "";
	                                                                            }

	                                                                            // fecha entrega
	                                                                            $consulta_ent = 
	                                                                                "
	                                                                                SELECT 
	                                                                                    ven_eta.fecha_hasta_eta_ven
	                                                                                FROM
	                                                                                    venta_etapa_venta AS ven_eta
	                                                                                WHERE
	                                                                                    ven_eta.id_ven = ? AND 
	                                                                                    ven_eta.id_eta = 10
	                                                                                ";
	                                                                            $conexion->consulta_form($consulta_ent,array($fila["id_ven"]));
	                                                                            $cantidad_fecha = $conexion->total();
	                                                                            if($cantidad_fecha > 0){
	                                                                                $fila_fecha_ent = $conexion->extraer_registro_unico();
	                                                                                if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
		                                                                                $fecha_entrega = "";
		                                                                            }
		                                                                            else{
		                                                                                $fecha_entrega = date("d/m/Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
		                                                                            }
	                                                                                
	                                                                            }
	                                                                            else{
	                                                                                $fecha_entrega = "";
	                                                                            }
                                                                        	} else { // crédito
                                                                        		$consulta_escr = 
	                                                                                "
	                                                                                SELECT 
	                                                                                    eta_cam.valor_campo_eta_cam_ven
	                                                                                FROM
	                                                                                    venta_etapa_campo_venta AS eta_cam
	                                                                                WHERE
	                                                                                    eta_cam.id_ven = ? AND 
	                                                                                    eta_cam.id_eta = 27 AND 
	                                                                                    eta_cam.id_cam_eta = 32
	                                                                                ";
	                                                                            $conexion->consulta_form($consulta_escr,array($fila["id_ven"]));
	                                                                            $cantidad_fecha = $conexion->total();
	                                                                            if($cantidad_fecha > 0){
	                                                                                $fila_fecha = $conexion->extraer_registro_unico();
	                                                                                if ($fila_fecha['valor_campo_eta_cam_ven'] == '0000-00-00' || $fila_fecha['valor_campo_eta_cam_ven'] == null) {
		                                                                                $fecha_escritura = "";
		                                                                            }
		                                                                            else{
		                                                                                $fecha_escritura = date("d/m/Y",strtotime($fila_fecha['valor_campo_eta_cam_ven']));
		                                                                            }
	                                                                                
	                                                                            }
	                                                                            else{
	                                                                                $fecha_escritura = "";
	                                                                            }

	                                                                            // fecha entrega
	                                                                            $consulta_ent = 
	                                                                                "
	                                                                                SELECT 
	                                                                                    ven_eta.fecha_hasta_eta_ven
	                                                                                FROM
	                                                                                    venta_etapa_venta AS ven_eta,
	                                                                                    venta_etapa_campo_venta AS eta_cam_ven
	                                                                                WHERE
	                                                                                    ven_eta.id_ven = ? AND 
	                                                                                    ven_eta.id_eta = 29 AND 
	                                                                                    ven_eta.id_eta_ven = eta_cam_ven.id_eta_ven AND 
	                                                                                    valor_campo_eta_cam_ven <> ''
	                                                                                ";
	                                                                            $conexion->consulta_form($consulta_ent,array($fila["id_ven"]));
	                                                                            $cantidad_fecha = $conexion->total();
	                                                                            if($cantidad_fecha > 0){
	                                                                                $fila_fecha_ent = $conexion->extraer_registro_unico();
	                                                                                if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
		                                                                                $fecha_entrega = "";
		                                                                            }
		                                                                            else{
		                                                                                $fecha_entrega = date("d/m/Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
		                                                                            }
	                                                                                
	                                                                            }
	                                                                            else{
	                                                                                $fecha_entrega = "";
	                                                                            }

	                                                                        }

	                                                                        if ($ciudad_notaria_ven==1) {
	                                                                        	$ciudad = "La Serena";
	                                                                        } else if ($ciudad_notaria_ven==2){
	                                                                        	$ciudad = "Santiago";
	                                                                        } else {
	                                                                        	$ciudad = "NO INGRESADA";
	                                                                        }

	                                                                        $fecha_escritura = "";

	                                                                        if ($fila["fecha_escritura_ven"]<>'') {

	                                                                        	$fecha_escritura = date("d/m/Y",strtotime($fila["fecha_escritura_ven"]));
                                                                            
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                                <td style="text-align: left;"><?php echo utf8_encode(ucwords(strtolower($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']))); ?></td>
                                                                                <td><?php echo utf8_encode($fila['rut_pro']); ?></td>
                                                                                <!-- <td><?php //echo $fila['id_sex']; ?></td> -->
                                                                                <td><?php echo utf8_encode($fila['fono_pro']); ?></td>
                                                                                <td><?php echo utf8_encode($fila['correo_pro']); ?></td>
                                                                                <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                                <td><?php echo $nombre_esta; ?></td>
                                                                                <td><?php echo $nombre_bod; ?></td>
                                                                                <td><?php echo $ciudad; ?></td>
                                                                                <td><?php echo $fecha_escritura; ?></td>
                                                                                <td><?php echo $fecha_entrega; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        	}
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
