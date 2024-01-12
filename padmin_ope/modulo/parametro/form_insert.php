<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_parametro_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
require_once _INCLUDE."head.php";

?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>dist/css/ajuste_form.css"> -->
<style type="text/css">
	h3{
		font-size: 1.7rem;
		font-weight: bold;
		text-decoration: underline;
		margin-bottom: 2rem;
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
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Parámetros Generales
                    <small>Modificación</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Parámetros Generales</a></li>
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
                            <!-- <div class="box-header with-border">
                                <h3 class="box-title">Complete el formulario</h3>
                            </div> -->
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form id="formulario" method="POST" action="update.php" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">

                                            <?php
                                            $consulta = "SELECT * FROM parametro_parametro WHERE id_con = 0 ORDER BY id_par ASC";
                                            $conexion->consulta($consulta);
                                            $cantidad = $conexion->total();
                                            $fila_consulta = $conexion->extraer_registro();
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $valor_par = $fila['valor_par'];
                                                    ?>
                                                    <div class="form-group col-sm-6">
                                                        <label for="parametro_fijo<?php echo $fila['id_par'];?>"><?php echo utf8_encode($fila['nombre_par']); ?>:</label>
                                                        <input type="text" value="<?php echo $valor_par;?>" name="parametro_fijo<?php echo $fila['id_par'];?>" class="form-control campo_parametro_fijo" id="parametro_fijo<?php echo $fila['id_par'];?>">
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>



                                            <div class="form-group col-sm-6">
                                                <label for="condominio">Condominio:</label>
                                                <select class="form-control" id="condominio" name="condominio" > 
                                                    <option value="">Seleccione Condominio</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-12" id="contenedor_parametro">
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer" id="contenedor_boton">
                                    <button type="submit" class="btn btn-primary pull-right">Registrar</button>
                                </div>
                            </form>
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

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        // $('#formulario').validate({ // initialize the plugin
        //     rules: {
        //         condominio: { 
        //             // required: true
        //         }
        //     },
        //     messages: { 
        //         condominio: {
        //             // required: "Seleccione condominio"
        //         }
        //     },
        //     submitHandler: function (form) { // for demo
        //         return false; // for demo
        //     }
        // });
        
        $('.campo_parametro').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required:  "Ingrese valor"
                }
            });
        });
        
        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                        $('#contenedor_parametro').html(data);
                    }
                })
            }
            else{
                $('#contenedor_parametro').html('');
            }
        });    

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información modificada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.href = "form_insert.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Cuenta ya ha sido ingresada", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        }
        $('#formulario').submit(function () {
            if ($("#formulario").validate().form() == true){
                $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario').serialize();
                $.ajax({
                    data: dataString,
                    type: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    success: function (data) {
                        resultado(data);
                    }
                })
            }
            
            return false;
        });
    });
</script>


</body>
</html>
