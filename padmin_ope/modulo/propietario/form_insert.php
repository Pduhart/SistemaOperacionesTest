<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
require_once _INCLUDE."head.php";
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
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
                    Cliente
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Cliente</a></li>
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
                                        <h4 class="text-center">Datos Generales</h4>
                                        <div class="col-sm-4">
                                            <!-- <div class="form-group">
                                                <div class="col-sm-12">
                                                    <label class="col-sm-12">Opción:</label>
                                                    <div class="fxradio col-sm-8">
                                                        <div class="radio">
                                                            <input class="opcion" id="opcion1" type="radio" name="opcion" value="1">
                                                            <label for="opcion1">Rut</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input class="opcion" id="opcion2" type="radio" name="opcion" value="2">
                                                            <label for="opcion2">Pasaporte</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div> -->
                                            <div class="form-group">
                                                <label for="nacionalidad">Nacionalidad:</label>
                                                <select class="form-control select2" id="nacionalidad" name="nacionalidad"> 
                                                    <option value="">Seleccione Nacionalidad</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM nacionalidad_nacionalidad ORDER BY nombre_nac";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_nac'];?>"><?php echo utf8_encode($fila['nombre_nac']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="region">Región:</label>
                                                <select class="form-control select2" id="region" name="region"> 
                                                    <option value="">Seleccione Región</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM region_region ORDER BY descripcion_reg";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_reg'];?>"><?php echo utf8_encode($fila['descripcion_reg']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="comuna">Comuna:</label>
                                                <select class="form-control select2" id="comuna" name="comuna"> 
                                                    <option value="">Seleccione Comuna</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="sexo">Sexo:</label>
                                                <select class="form-control" id="sexo" name="sexo"> 
                                                    <option value="">Seleccione Sexo</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM sexo_sexo ORDER BY nombre_sex";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_sex'];?>"><?php echo utf8_encode($fila['nombre_sex']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="civil">Estado Civil:</label>
                                                <select class="form-control" id="civil" name="civil"> 
                                                    <option value="">Seleccione Estado Civil</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM civil_civil ORDER BY id_civ";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_civ'];?>"><?php echo utf8_encode($fila['nombre_civ']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="estudio">Estudios:</label>
                                                <select class="form-control" id="estudio" name="estudio"> 
                                                    <option value="">Seleccione Estudios</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM estudio_estudio ORDER BY id_est";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_est'];?>"><?php echo utf8_encode($fila['nombre_est']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="profesion">Profesión:</label>
                                                <select class="form-control" id="profesion" name="profesion"> 
                                                    <option value="">Seleccione Profesión</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM profesion_profesion ORDER BY nombre_prof";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_prof'];?>"><?php echo utf8_encode($fila['nombre_prof']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="rut">Rut:</label>
                                                <input type="text" name="rut" class="form-control rut" id="rut"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="nombre">Primer Nombre:</label>
                                                <input type="text" name="nombre" class="form-control" id="nombre"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre2">Segundo Nombre:</label>
                                                <input type="text" name="nombre2" class="form-control" id="nombre2"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="apellido_paterno">Apellido Paterno:</label>
                                                <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="apellido_materno">Apellido Materno:</label>
                                                <input type="text" name="apellido_materno" class="form-control" id="apellido_materno"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fecha_nacimiento">Fecha Nacimiento:</label>
                                                <input type="text" name="fecha_nacimiento" class="form-control datepicker" id="fecha_nacimiento"/>
                                            </div>
                                            <div class="form-group">
				                                <label for="apellido_paterno">Profesión para Promesa:</label>
				                                <input type="text" name="profesion_promesa" class="form-control" id="profesion_promesa"/>
				                            </div>                                       
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="direccion">Dirección:</label>
                                                <input type="text" name="direccion" class="form-control" id="direccion"/>
                                            </div>


                                            <div class="form-group">
                                                <label for="direccion_trabajo">Lugar de Trabajo:</label>
                                                <input type="text" name="direccion_trabajo" class="form-control" id="direccion_trabajo"/>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label for="correo">Correo:</label>
                                                <input type="text" name="correo" class="form-control" id="correo"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="correo2">Correo 2:</label>
                                                <input type="text" name="correo2" class="form-control" id="correo2"/>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="fono">Fono:</label>
                                                <input type="text" name="fono" class="form-control numero" id="fono"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="fono2">Fono 2:</label>
                                                <input type="text" name="fono2" class="form-control numero" id="fono2"/>
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
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
    	$('#region').change(function(){
            var_region = $('#region').val();
            $.ajax({
                type: 'POST',
                url: ("select_comuna.php"),
                data:"region="+var_region,
                success: function(data) {
                    $('#comuna').html(data);
                }
            })
            
        });


        $('.numero').numeric();
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");
        $("#formulario").validate({
            rules: {
                rut: {
                    required: true
                },
                pasaporte: {
                    required: true
                },
                nacionalidad: {
                    required: true
                },
                region: {
                    required: true
                },
                comuna: {
                    required: true
                },
                sexo: {
                    required: true
                },
                tipo_cuenta: {
                    required: true
                },
                cuenta: {
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                nombre_cuenta: { 
                    required: true,
                    minlength: 3
                },
                apellido_paterno: { 
                    required: true,
                    minlength: 3
                },
                descripcion:{
                    required: true,
                    minlength: 4
                },
                correo:{
                    required: true,
                    minlength: 4,
                    email: true
                },
                fono:{
                    required: true,
                    minlength: 4
                }
            },
            messages: { 
                rut: {
                    required: "Ingrese rut"
                },
                pasaporte: {
                    required: "Ingrese pasaporte"
                },
                nacionalidad: {
                    required: "Seleccione nacionalidad"
                },
                region: {
                    required: "Seleccione Región"
                },
                comuna: {
                    required: "Seleccione comuna"
                },
                sexo: {
                    required: "Seleccione sexo"
                },
                tipo_cuenta: {
                    required: "Seleccione tipo cuenta"
                },
                cuenta: {
                    required: "Ingrese cuenta"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 4 caracteres"
                },
                nombre_cuenta: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 4 caracteres"
                },
                apellido_paterno: {
                    required: "Ingrese apellido paterno",
                    minlength: "Mínimo 4 caracteres"
                },
                descripcion: {
                    required: "Ingrese descripción",
                    minlength: "Mínimo 4 caracteres"
                },
                correo: {
                    required: "Ingrese correo",
                    minlength: "Mínimo 4 caracteres",
                    email: "Ingrese correo válido"
                },
                fono: {
                    required: "Ingrese fono",
                    minlength: "Mínimo 4 caracteres"
                }
            }
        });
        $('#rut').Rut({
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

        $(document).on( "click",".opcion" , function() {
            valor = $(this).val();

            if(valor == 1){
                $("#pasaporte").val('');
                $("#rut").prop('disabled', false);
                $("#pasaporte").prop('disabled', true);
            }
            else{
                $("#rut").val('');
                $("#rut").prop('disabled', true);
                $("#pasaporte").prop('disabled', false);
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
                swal("Atención!", "Propietario ya ha sido ingresada", "warning");
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
