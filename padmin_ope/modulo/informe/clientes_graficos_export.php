<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

$nombre = 'clientes_graficos'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");

$filtro = $_GET['fil'];

if ($filtro<>'') {
	$filtro_cons = base64_decode(base64_decode($filtro));
} else {
	$filtro_cons = "";
}
// require_once _INCLUDE."head_informe.php";
$estado_venta = 3; //promesadas
?>
<head>
<title>Clientes - Listado</title>
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
                                                                        <th>Cliente</th>
                                                                        <th>Rut</th>
                                                                        <th>Género</th>
                                                                        <th>Fecha Nacimiento</th>
                                                                        <th>Profesión</th>
                                                                        <th>Estado Civil</th>
                                                                        <th>Procedencia</th>
                                                                        <th>Cuenta Corriente</th>
                                                                        <th>Canal Llegada</th>
                                                                    </tr>    
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $acumulado_monto = 0;
                                                                    
                                                                    $consulta = 
												                        "
												                        SELECT 
												                            sex.id_sex,
												                            sex.nombre_sex,
												                            pro.fecha_nacimiento_pro,
												                            pro.rut_pro,
												                            pro.nombre_pro,
												                            pro.apellido_paterno_pro,
												                            pro.apellido_materno_pro,
												                            civ.id_civ,
                           	 												civ.nombre_civ,
                           	 												prof.id_prof,
                            												prof.nombre_prof,
                            												com.nombre_com,
                            												ban.id_ban,
                            												ban.nombre_ban,
                            												cot_can.id_can_cot,
                            												cot_can.nombre_can_cot
												                        FROM 
												                            propietario_propietario AS pro
												                            INNER JOIN sexo_sexo AS sex ON sex.id_sex = pro.id_sex
												                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
												                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
												                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
												                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
												                            LEFT JOIN civil_civil AS civ ON civ.id_civ = pro.id_civ
												                            LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
												                            INNER JOIN  comuna_comuna AS com ON com.id_com = pro.id_com
												                            LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
												                            INNER JOIN cotizacion_cotizacion AS cot ON cot.id_cot = ven.cotizacion_ven
												                            INNER JOIN cotizacion_canal_cotizacion AS cot_can ON cot_can.id_can_cot = cot.id_can_cot
												                        WHERE 
												                            ven.id_est_ven > ".$estado_venta." ".$filtro_cons."
												                        ";
                                                                    $consulta .= " ORDER BY 
                                                                            pro.apellido_paterno_pro";
                                                                    // echo $consulta;
                                                                    $conexion->consulta($consulta);
                                                                    $contador = 0;
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) { 
                                                                       	$contador++;                                
                                                                        ?>
                                                                           <tr>
                                                                           		<td><?php echo $contador; ?></td>
                                                                                <td style="text-align: left;"><?php echo ucwords(strtolower(utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']))); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['rut_pro']); ?></td>
                                                                                 <td><?php echo $fila['nombre_sex']; ?></td>
                                                                                 <td><?php echo date("d/m/Y",strtotime($fila['fecha_nacimiento_pro'])); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['nombre_prof']); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['nombre_civ']); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['nombre_com']); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['nombre_ban']); ?></td>
                                                                                 <td><?php echo utf8_encode($fila['nombre_can_cot']); ?></td>
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
