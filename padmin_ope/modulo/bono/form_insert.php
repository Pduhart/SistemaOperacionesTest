<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_bono_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<style type="text/css">
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de;
    border-radius: 0px;
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 34px;
    user-select: none;
    -webkit-user-select: none;
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
                    Bono
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Bono</a></li>
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
                            <form id="formulario" method="POST" action="insert.php" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="condominio">Condominio:</label>
                                                <select class="form-control" id="condominio" name="condominio"> 
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
                                            <div class="form-group">
                                                <label for="tipo_bono">Tipo de Bono:</label>
                                                <select class="form-control" id="tipo_bono" name="tipo_bono"> 
                                                    <option value="">Seleccione Tipo de Bono</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM bono_tipo_bono ORDER BY nombre_tip_bon";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_tip_bon'];?>"><?php echo utf8_encode($fila['nombre_tip_bon']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="valor">Monto Bono:</label>
                                                <input type="text" name="valor" class="form-control numero" id="valor"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fecha_desde">Fecha Desde:</label>
                                                <input type="text" name="fecha_desde" class="form-control datepicker" id="fecha_desde"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="fecha_hasta">Fecha Hasta:</label>
                                                <input type="text" name="fecha_hasta" class="form-control datepicker" id="fecha_hasta"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="categoria">Categoría:</label>
                                                <select class="form-control" id="categoria" name="categoria"> 
                                                    <option value="">Seleccione Categoría</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM bono_categoria_bono ORDER BY id_cat_bon";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_cat_bon'];?>"><?php echo utf8_encode($fila['nombre_cat_bon']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="modelo">Modelo:</label>
                                                <select class="form-control" id="modelo" name="modelo"> 
                                                    <option value="">Seleccione Modelo</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Nombre:</label>
                                                <input type="text" name="nombre" class="form-control" id="nombre"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="desde">Desde:</label>
                                                <input type="text" name="desde" class="form-control numero" id="desde"/>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="hasta">Hasta:</label>
                                                <input type="text" name="hasta" class="form-control numero" id="hasta"/>
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

<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        $('.numero').numeric();

        $(document).on( "change","#categoria" , function() {
            valor = $(this).val();

            if(valor == 1){
               $('#modelo').prop('disabled', true);
               $('#fecha_desde').prop('disabled', true);
               $('#fecha_hasta').prop('disabled', true);
            }
            else if(valor == 2){
               $('#modelo').prop('disabled', true);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
            }
            else if(valor == 3){
               $('#modelo').prop('disabled', false);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
            }
            else if(valor == 4){
               $('#modelo').prop('disabled', true);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
            }
            else{
               $('#modelo').prop('disabled', false);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
            }
        });


        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true,
                    minlength: 3
                },
                 desde: { 
                    required: true
                },
                tipo_bono: { 
                    required: true
                },
                condominio: { 
                    required: true
                },
                hasta: { 
                    required: true
                },
                valor: { 
                    required: true
                },
                categoria: { 
                    required: true
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                desde: {
                    required: "Ingrese valor"
                },
                tipo_bono: {
                    required: "Seleccione tipo de bono"
                },
                condominio: {
                    required: "Seleccione condominio"
                },
                hasta: {
                    required: "Ingrese valor"
                },
                valor: {
                    required: "Ingrese Bono"
                },
                categoria: {
                    required: "Ingrese Categoría"
                }
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
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
                swal("Atención!", "Bono ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 4) {
                swal("Error!", "Ya existe un bono para la información ingresada", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
            // }
        }
        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#modelo').html(data);
                    }
                })
            }
        });
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
