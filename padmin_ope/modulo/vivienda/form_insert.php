<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_vivienda_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
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
                    Departamento
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Departamento</a></li>
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
                                                <select class="form-control select2" id="condominio" name="condominio"> 
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
                                                <label for="torre">Torre:</label>
                                                <select class="form-control select2" id="torre" name="torre">
                                                <option value="">Seleccione Torre</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="modelo">Modelo:</label>
                                                <select class="form-control select2" id="modelo" name="modelo"> 
                                                    <option value="">Seleccione Modelo</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM modelo_modelo WHERE id_est_mod = 1 ORDER BY nombre_mod";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="propietario">Propietario:</label>
                                                <select class="form-control select2" id="propietario" name="propietario"> 
                                                    <option value="">Seleccione Propietario</option>
                                                    <?php  
                                                    $consulta = "SELECT id_pro,nombre_pro,apellido_paterno_pro,apellido_materno_pro FROM propietario_propietario WHERE id_est_pro = 1 ORDER BY nombre_pro";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="orientacion">Orientación:</label>
                                                <select class="form-control" id="orientacion" name="orientacion"> 
                                                    <option value="">Seleccione Orientación</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM vivienda_orientacion_vivienda ORDER BY id_ori_viv";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_ori_viv'];?>"><?php echo utf8_encode($fila['nombre_ori_viv']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="piso">Piso:</label>
                                                <select class="form-control" id="piso" name="piso"> 
                                                    <option value="">Seleccione Piso</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM piso_piso ORDER BY id_pis";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_pis'];?>"><?php echo utf8_encode($fila['nombre_pis']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            
                                            <div class="form-group">
                                                <label for="nombre">Nombre:</label>
                                                <input type="text" name="nombre" class="form-control" id="nombre"/>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="valor">Valor Depto:</label>
                                                <input type="text" name="valor" class="form-control numero" id="valor"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="metro">Metro Depto:</label>
                                                <input type="text" name="metro" class="form-control numero" id="metro"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="metro_terraza">Metro Terraza:</label>
                                                <input type="text" name="metro_terraza" class="form-control numero" id="metro_terraza"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="bono">Bono:</label>
                                                <input type="text" name="bono" class="form-control numero" id="bono"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="prorrateo">Prorrateo (%):</label>
                                                <input type="text" name="prorrateo" class="form-control numero" id="prorrateo"/>
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
<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true,
                    minlength: 1
                },
                 torre: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                piso: { 
                    required: true
                },
                /*propietario: { 
                    required: true
                },*/
                orientacion: { 
                    required: true
                },
                valor: { 
                    required: true
                },
                metro: { 
                    required: true
                },
                metro_terraza: { 
                    required: true
                },
                bono: { 
                    required: true
                }

            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 1 caracteres"
                },
                torre: {
                    required: "Seleccione torre"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                piso: {
                    required: "Seleccione piso"
                },
                /*propietario: {
                    required: "Seleccione propietario"
                },*/
                orientacion: {
                    required: "Seleccione orientación"
                },
                valor: {
                    required: "Ingrese valor"
                },
                metro: {
                    required: "Ingrese metro"
                },
                metro_terraza: {
                    required: "Ingrese metro"
                },
                bono: {
                    required: "Ingrese bono"
                }
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        
        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
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
                swal("Atención!", "Departamento ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
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
