<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_uf_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/daterangepicker/daterangepicker-bs3.css">

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
                    UF
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">UF</a></li>
                    <li class="active">Ingreso</li>
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
                                  	<div class="col-md-6">
                                    	Cargue la UF directo <a href="insert_api.php" class="btn btn-primary">CLICK AQUÍ</a>
                                    </div>
                            		<form id="formulario" method="POST" action="insert.php" role="form" enctype="multipart/form-data">
                                
	                                    <div class="col-sm-6">
	                                        
	                                        <div class="form-group">
	                                            <label for="archivo">Carga mediante Archivo</label>
	                                            <input type="file" id="file_uf" name="file_uf">
	                                            <p class="help-block">Solo formato CSV</p>
	                                            <p>Descargar desde aquí: <a target="_blank" href="https://si3.bcentral.cl/indicadoressiete/secure/Serie.aspx?gcode=UF&param=RABmAFYAWQB3AGYAaQBuAEkALQAzADUAbgBNAGgAaAAkADUAVwBQAC4AbQBYADAARwBOAGUAYwBjACMAQQBaAHAARgBhAGcAUABTAGUAYwBsAEMAMQA0AE0AawBLAF8AdQBDACQASABzAG0AXwA2AHQAawBvAFcAZwBKAEwAegBzAF8AbgBMAHIAYgBDAC4ARQA3AFUAVwB4AFIAWQBhAEEAOABkAHkAZwAxAEEARAA=">Banco Central</a><br>Una vez descargado lo debe guardar como .csv</p>
	                                        </div>
	                                        <button type="submit" class="btn btn-primary">Registrar Archivo CSV</button>
	                                    </div>
                                    </form> 
                                </div>
                            </div>
                                <!-- /.box-body -->
                            <div class="box-footer" id="contenedor_boton">
                                <!-- <button type="submit" class="btn btn-primary pull-right">Registrar</button> -->
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

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/additional-methods.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>
<!--<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>-->
<script type="text/javascript">
    $(document).ready(function(){
        // $('.numero').numeric();
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");
        $("#formulario").validate({
            rules: {
                file_uf: {
                    required: true, 
                    extension: "csv"
                }
            },
            messages: {
                file_uf: {
                    required: 'Debe ingresar ingresar un archivo',
                    extension: 'Solo formato CSV!' 
                }
            }
        });
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.href = "form_select.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Cuenta ya ha sido ingresada", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                alert(data.error_consulta);
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        } 
        
        // $('#formulario').submit(function () {
        //     if ($("#formulario").validate().form() == true){
        //         $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
        //         var dataString = $('#formulario').serialize();
        //         $.ajax({
        //             data: dataString,
        //             type: 'POST',
        //             url: $(this).attr('action'),
        //             dataType: 'json',
        //             success: function (data) {
        //                 resultado(data);
        //             }
        //         })
        //     }
            
        //     return false;
        // });
    });
</script>


</body>
</html>
