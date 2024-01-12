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
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">

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
                    Vendedor
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Vendedor</a></li>
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
                                                <label for="categoria">Categoría:</label>
                                                <select class="form-control" id="categoria" name="categoria"> 
                                                    <option value="">Seleccione Categoría</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM vendedor_categoria_vendedor ORDER BY id_cat_vend";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_cat_vend'];?>"><?php echo utf8_encode($fila['nombre_cat_vend']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Nombre:</label>
                                                <input type="text" name="nombre" class="form-control" id="nombre"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Apellido Paterno:</label>
                                                <input type="text" name="apellido1" class="form-control" id="apellido1"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Apellido Materno:</label>
                                                <input type="text" name="apellido2" class="form-control" id="apellido2"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="rut">Rut:</label>
                                                <input type="text" name="rut" class="form-control rut" id="rut"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="correo">Correo:</label>
                                                <input type="text" name="correo" class="form-control" id="correo"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fono">Fono:</label>
                                                <input type="text" name="fono" class="form-control" id="fono"/>
                                            </div>
                                            
                                            <div class="box-header">
                                                <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Condominios</h3>
                                            </div>
                                            <div class="box-body">
                                                <ul class="list-unstyled list-inline margin-0">
                                                    <?php
                                                    $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con ASC";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            $id_con = utf8_encode($fila['id_con']);
                                                            $nombre_con = utf8_encode($fila['nombre_con']);
                                                            ?>
                                                            <li class="margin-bottom-10 col-sm-4">
                                                                <input type="checkbox" name="modulo_condominio[]" id="modulo_<?php echo $id_con;?>" value="<?php echo $id_con;?>" class="condominio check_registro"><label for="modulo_<?php echo $id_con;?>"><span></span><?php echo $nombre_con;?></label>
                                                                
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>  
                                                </ul>
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

<!-- validate -->
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");
        $("#formulario").validate({
            rules: {
                perfil: { 
                    required: true
                },
                categoria: { 
                    required: true
                },
                rut: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 4
                },
                apellido1: { 
                    required: true,
                    minlength: 4
                },
                apellido2: { 
                    required: true,
                    minlength: 4
                },
                // fono: { 
                //     required: true,
                //     minlength: 6
                // },
                correo: { 
                    required: true,
                    email: true,
                    minlength: 9
                }
            },
            messages: { 
                perfil: {
                    required: "Seleccione perfil"
                },
                categoria: {
                    required: "Seleccione categoría"
                },
                rut: {
                    required: "Ingrese rut"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 4 caracteres"
                },
                apellido1: {
                    required: "Ingrese apellido paterno",
                    minlength: "Mínimo 4 caracteres"
                },
                apellido2: {
                    required: "Ingrese apellido materno",
                    minlength: "Mínimo 4 caracteres"
                },
                // fono: {
                //     required: "Ingrese fono",
                //     minlength: "Mínimo 6 caracteres"
                // },
                correo: {
                    required: "Ingrese correo",
                    email: "Ingrese correo válido",
                    minlength: " Mínimo 9 caracteres"
                }
            }
        });
        $('#rut').Rut({
            });
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    icon: "success"                    
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Usuario ya ha sido ingresado", "warning");
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
