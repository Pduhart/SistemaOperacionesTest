<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_parametro_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/daterangepicker/daterangepicker-bs3.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/bootstrap3-editable/css/bootstrap-editable.css">

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
                    Parámetros UF
                    <small>Cuadro Edición</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Parámetros UF</a></li>
                    <li class="active">Modificación</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php 
                                            $consulta = "SELECT * FROM mes_mes ORDER BY id_mes ASC";
                                            $conexion->consulta($consulta);
                                            $fila_consulta = $conexion->extraer_registro();
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $nombre_mes = utf8_encode($fila['nombre_mes']);
                                                    $id_mes = $fila['id_mes'];
                                             ?>
                                            <th><?php echo $nombre_mes ?></th>
                                            <?php }
                                            } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for ($i=1; $i < 32; $i++) { 
                                            ?>
                                            <tr>
                                            <td><?php echo $i; ?></td>
                                            <?php 
                                            
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $id_mes = $fila['id_mes'];
                                             ?>
                                            <td class="edita"><a href="#" id="<?php echo $i."-".$id_mes; ?>" data-type="text" data-pk="<?php echo $i."-".$id_mes; ?>" data-value="<?php echo $notas['not_nota1'];?>" title="Ingrese Valor Uf">xxx<?php echo $notas['not_nota1'];?></a></td>
                                            <?php
                                                }
                                            } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
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
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script type="text/javascript">
    $(function(){
        // $.fn.editable.defaults.mode = 'inline';   
        $('.edita a').editable({
         url: 'procesa_uf.php' 
        });
    });
</script>
</body>
</html>
