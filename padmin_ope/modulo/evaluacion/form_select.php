<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_evaluacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<style>
    table{
        margin-top:25px;     
    }
    table,tr,td,th{
      text-align:center;
    }
</style>
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Listado de evaluaciones.
                    <small>Trabajadores</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Evaluacion</a></li>
                    <li class="active">Listado</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                        <div class="box box-primary" style="padding-bottom:100px; padding-top:50px;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Listado de vendedores.</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="container" >
                                <table class="table table-bordered table-hover"  id="example" style="margin-bottom:100px;">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Puntos</th>
                                        <th>% Porcentaje</th>
                                        <th>Vendedor</th>
                                        <th>Respuesta 1</th>
                                        <th>Respuesta 2</th>
                                        <th>Respuesta 3</th>
                                        <th>Respuesta 4</th>
                                        <th>Fecha de creación</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                                                  
                                        $data = conexion::select("SELECT 
                                                                CONCAT(vende.nombre_vend,' ',vende.apellido_paterno_vend,' ',vende.apellido_materno_vend) as vendedor,
                                                                matriz.id,
                                                                matriz.puntos,
                                                                matriz.porcentaje,
                                                                matriz.rpregunta1,
                                                                matriz.rpregunta2,
                                                                matriz.rpregunta3,
                                                                matriz.rpregunta4,
                                                                matriz.fecha_creacion                                                                  
                                                                FROM matriz_desarrollo as matriz 
                                                                INNER JOIN vendedor_vendedor as vende 
                                                                ON matriz.id_vendedor = vende.id_vend");                                          
                                        if(count($data)>0):
                                        foreach($data as $val){                               
                                        ?>
                                        <tr>
                                            <td><?php echo $val['id']?></td>
                                            <td><?php echo $val['puntos']. " Pts"?></td>
                                            <td><?php echo $val['porcentaje']. " %"?></td>
                                            <td><?php echo utf8_encode($val['vendedor'])?></td>
                                            <td><?php echo $val['rpregunta1']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta2']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta3']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta4']. " Pts"?></td>
                                            <td><?php echo date_format(date_create($val['fecha_creacion']),'d-m-Y')?></td>
                                        </tr>
                                        <?php }?> 
                                        <?php else: ?>
                                        <td colspan="9"><h4 style="color:grey;">Aun no hay encuestas por mostrar, para crear una ve a <a href="form_insert.php">Formulario de evaluaciones</a></h4></td>
                                        <?php endif?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Puntos</th>
                                        <th>% Porcentaje</th>
                                        <th>Vendedor</th>
                                        <th>Respuesta 1</th>
                                        <th>Respuesta 2</th>
                                        <th>Respuesta 3</th>
                                        <th>Respuesta 4</th>
                                        <th>Fecha de creación</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="col-md-12" style="padding:3%">
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Listado de Operaciones.</h3>
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover"  id="example2" >
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Puntos</th>
                                        <th>% Porcentaje</th>
                                        <th>Operador(a)</th>
                                        <th>Respuesta 1</th>
                                        <th>Respuesta 2</th>
                                        <th>Respuesta 3</th>
                                        <th>Respuesta 4</th>
                                        <th>Fecha de creación</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                                                  
                                        $data = conexion::select("SELECT 
                                                                CONCAT(usu.nombre_usu,' ',usu.apellido1_usu,' ',usu.apellido2_usu) as operador,
                                                                matriz.id,
                                                                matriz.puntos,
                                                                matriz.porcentaje,
                                                                matriz.rpregunta1,
                                                                matriz.rpregunta2,
                                                                matriz.rpregunta3,
                                                                matriz.rpregunta4,
                                                                matriz.fecha_creacion                                                                  
                                                                FROM matriz_desarrollo as matriz 
                                                                INNER JOIN usuario_usuario as usu 
                                                                ON matriz.id_vendedor = usu.id_usu
                                                                WHERE usu.id_per=3");                                          
                                        if(count($data)>0):
                                        foreach($data as $val){                               
                                        ?>
                                        <tr>
                                            <td><?php echo $val['id']?></td>
                                            <td><?php echo $val['puntos']. " Pts"?></td>
                                            <td><?php echo $val['porcentaje']. " %"?></td>
                                            <td><?php echo utf8_encode($val['operador'])?></td>
                                            <td><?php echo $val['rpregunta1']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta2']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta3']. " Pts"?></td>
                                            <td><?php echo $val['rpregunta4']. " Pts"?></td>
                                            <td><?php echo date_format(date_create($val['fecha_creacion']),'d-m-Y')?></td>
                                        </tr>
                                        <?php }?> 
                                        <?php else: ?>
                                        <td colspan="9"><h4 style="color:grey;">Aun no hay encuestas por mostrar, para crear una ve a <a href="form_insert.php">Formulario de evaluaciones</a></h4></td>
                                        <?php endif?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Puntos</th>
                                        <th>% Porcentaje</th>
                                        <th>Operador(a)</th>
                                        <th>Respuesta 1</th>
                                        <th>Respuesta 2</th>
                                        <th>Respuesta 3</th>
                                        <th>Respuesta 4</th>
                                        <th>Fecha de creación</th>
                                    </tr>
                                    </tfoot>
                                </table>
                           </div>
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
<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/datetime-moment.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script>
   $(document).ready(function () {
    $('#example').DataTable();
});
$(document).ready(function () {
    $('#example2').DataTable();
});
</script>
</body>
</html>
