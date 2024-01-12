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
                  Cartas de mérito y demérito
                    <small>Listado</small>
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
                        <div class="box box-primary" >
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Listado de Cartas de mérito y demérito.  <small>Vendedores y operaciones</small></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="container" >
                                <table class="table table-bordered table-hover"  id="example" style="margin-bottom:100px;">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Trabajador(a)</th>
                                        <th>Estado</th>
                                        <th>Anotación</th>
                                        <th>Descripción</th>
                                        <th>Referencia</th>
                                        <th>Fundamentación</th>
                                        <th>Resolución</th>
                                        <th>Fecha de creación</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                                                  
                                        $data = conexion::select("SELECT                                                                
                                                                matriz.id,
                                                                matriz.trabajador_id,
                                                                matriz.estado,
                                                                matriz.anotacion,
                                                                matriz.descripcion,
                                                                matriz.referencia,
                                                                matriz.fundamentacion,
                                                                matriz.resolucion,
                                                                matriz.fecha_creacion                                                                  
                                                                FROM matriz_carta as matriz");                                          
                                        if(count($data)>0):
                                        foreach($data as $val){   
                                            $name = "";
                                            if($val['trabajador_id'] == 34){
                                                $name ="Margot Andrea Moya Olivares";
                                            }else{
                                                $nombre = conexion::select("SELECT  CONCAT(vende.nombre_vend,' ',vende.apellido_paterno_vend,' ',vende.apellido_materno_vend) as vendedor
                                                                    FROM vendedor_vendedor as vende WHERE id_vend = ".$val['trabajador_id']);
                                                $name = utf8_encode($nombre[0]['vendedor']);
                                            }
                                                                    
                                        ?>
                                        <tr>
                                            <td><?php echo $val['id']?></td>
                                            <td><?php echo $name?></td>
                                            <td><?php echo ($val['estado'] == 1)?'Activo':'Inactivo'?></td>
                                            <td><?php echo $val['anotacion']?></td>
                                            <td><?php echo utf8_encode($val['descripcion'])?></td>
                                            <td><?php echo utf8_encode($val['referencia'])?></td>
                                            <td><?php echo utf8_encode($val['fundamentacion'])?></td>
                                            <td><?php echo utf8_encode($val['resolucion'])?></td>                                            
                                            <td><?php echo date_format(date_create($val['fecha_creacion']),'d-m-Y')?></td>
                                        </tr>
                                        <?php }?> 
                                        <?php else: ?>
                                        <td colspan="9"><h4 style="color:grey;">Aun no hay Cartas para mostrar, agregar en : <a href="form_carta.php">Formulario de Cartas de mérito y demérito</a></h4></td>
                                        <?php endif?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Trabajador(a)</th>
                                        <th>Estado</th>
                                        <th>Anotación</th>
                                        <th>Descripción</th>
                                        <th>Referencia</th>
                                        <th>Fundamentación</th>
                                        <th>Resolución</th>
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
</script>
</body>
</html>
