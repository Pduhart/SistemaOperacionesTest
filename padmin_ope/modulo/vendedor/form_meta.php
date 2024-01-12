<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_vendedor_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- <link href="<?php//  echo _ASSETS?>plugins/lou-multi-select/css/multi-select.css" media="screen" rel="stylesheet" type="text/css"> -->
<!-- daterange picker -->
<!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>plugins/datepicker/datepicker3.css"> -->
<!-- iCheck for checkboxes and radio inputs -->
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/iCheck/all.css"> -->
<style type="text/css">
	.ms-container{
	  width: 600px;
	}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";
        ?>
        <div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Vendedor
                    <small>Asignación</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Vendedor</a></li>
                    <li class="active">Asignacion Clientes</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- <div class="box-header with-border">
                                <h3 class="box-title">Complete el formulario</h3>
                            </div> -->
                            <!-- /.box-header -->
                            <!-- form start -->
                            
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="vendedor">Vendedor:</label>
                                            <select class="form-control" id="vendedor" name="vendedor"> 
                                                <option value="">Seleccione Vendedor</option>
                                                <?php  
                                                $consulta = "SELECT * FROM vendedor_vendedor WHERE id_est_vend = 1 ORDER BY nombre_vend, apellido_paterno_vend, apellido_materno_vend";
                                                $conexion->consulta($consulta);
                                                $fila_consulta = $conexion->extraer_registro();
                                                if(is_array($fila_consulta)){
                                                    foreach ($fila_consulta as $fila) {
                                                        ?>
                                                        <option value="<?php echo $fila['id_vend'];?>"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']);?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div id="contenedor_vendedor" class="box box-solid no-border margin-bottom-40">

                                            
                                        </div>
                                        
                                    </div>
                                </div>
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

<!-- validate -->
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<!-- <script src="<?php// echo _ASSETS?>plugins/validate/jquery.numeric.js"></script> -->
<!-- <script src="<?php// echo _ASSETS?>plugins/validate/jquery.rut.js"></script> -->


<script type="text/javascript">
    $(document).ready(function(){       
        
        $('#vendedor').change(function(){
            var_vendedor = $('#vendedor').val();
            $('#contenedor_vendedor').html('<img src="../../assets/img/loading.gif">');
            if(var_vendedor != null && var_vendedor != ''){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_meta.php"),
                    data:"id="+var_vendedor,
                    success: function(data) {
                        $('#contenedor_vendedor').html(data);
                    }
                })
            }
            else{
                $('#contenedor_vendedor').html('');
            }
            
        });



        $(document).on( "click",".detalle" , function() {
        	alert("pasa");
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("../propietario/form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });
        
    });
</script>


</body>
</html>
