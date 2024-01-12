<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_promesa_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_cot = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
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
<body>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil" aria-hidden="true"></i> Formulario Actualización       </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="update.php">
        <?php  
        $consulta = 
            "
            SELECT
                con.nombre_con,
                con.id_con,
                viv.id_viv,
                viv.nombre_viv,
                can_cot.id_can_cot,
                can_cot.nombre_can_cot,
                tor.id_tor,
                tor.nombre_tor,
                mode.id_mod,
                mode.nombre_mod,
                pro.rut_pro,
                pro.id_pro,
                pro.nombre_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                pro.correo_pro,
                pro.fono_pro,
                cot.fecha_cot
            FROM
                cotizacion_cotizacion AS cot 
                INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
            WHERE
                cot.id_cot = ?
            ";
        $conexion->consulta_form($consulta,array($id_cot));
        $fila = $conexion->extraer_registro_unico();
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $id_can_cot = utf8_encode($fila['id_can_cot']);
        $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_tor = utf8_encode($fila['id_tor']);
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $id_mod = utf8_encode($fila['id_mod']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_cot;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-4">
                    <h4>Búsqueda o Ingreso de Cliente</h4>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="propietario">Cliente:</label>
                        <select class="form-control select2" id="propietario" name="propietario"> 
                            <option value="<?php echo $id_pro;?>"><?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></option>
                            <?php  
                            $consulta = "SELECT id_pro,nombre_pro,apellido_paterno_pro,apellido_materno_pro FROM propietario_propietario WHERE id_est_pro = 1 AND id_pro <> '".$id_pro."' ORDER BY nombre_pro";
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
                </div>
                <div class="col-sm-8" id="contenedor_propietario">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="rut">Rut:</label>
                            <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_pro;?>" disabled />
                        </div>
                        <div class="form-group">
                            <label for="apellido_materno">Apellido Materno:</label>
                            <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo $apellido_materno_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_pro;?>"/>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="text" name="correo" class="form-control" id="correo" value="<?php echo $correo_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="apellido_paterno">Apellido Paterno:</label>
                            <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" value="<?php echo $apellido_paterno_pro;?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="fono">Fono:</label>
                            <input type="text" name="fono" class="form-control numero" id="fono" value="<?php echo $fono_pro;?>"/>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <hr> 
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="condominio">Condominio:</label>
                        <select class="form-control select2" id="condominio" name="condominio"> 
                            <option value="<?php echo $id_con;?>"><?php echo $nombre_con;?></option>
                            <?php  
                            $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 AND id_con <> '".$id_con."' ORDER BY nombre_con";
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
                        <label for="vivienda">Departamento:</label>
                        <select class="form-control select2" id="vivienda" name="vivienda"> 
                            <option value="<?php echo $id_viv;?>"><?php echo $nombre_viv;?></option>
                            <?php  
                            $consulta = "SELECT * FROM vivienda_vivienda WHERE id_est_viv = 1 AND id_viv <> '".$id_viv."' ORDER BY nombre_viv";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="canal">Canal:</label>
                        <select class="form-control select2" id="canal" name="canal"> 
                            <option value="<?php echo $id_can_cot;?>"><?php echo $nombre_can_cot;?></option>
                            <?php  
                            $consulta = "SELECT * FROM cotizacion_canal_cotizacion WHERE id_can_cot <> '".$id_can_cot."' ORDER BY nombre_can_cot";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_can_cot'];?>"><?php echo utf8_encode($fila['nombre_can_cot']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="torre">Torre:</label>
                        <select class="form-control select2" id="torre" name="torre">
                            <option value="<?php echo $id_tor;?>"><?php echo $nombre_tor;?></option>
                            <?php  
                            $consulta = "SELECT * FROM torre_torre WHERE id_est_tor = 1 AND id_con = '".$id_con."' AND id_tor <> '".$id_tor."' ORDER BY nombre_tor";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tor'];?>"><?php echo utf8_encode($fila['nombre_tor']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <select class="form-control select2" id="modelo" name="modelo"> 
                            <option value="<?php echo $id_mod;?>"><?php echo $nombre_mod;?></option>
                            <?php  
                            $consulta = "SELECT * FROM modelo_modelo WHERE id_est_mod = 1 AND id_mod <> '".$id_mod."' ORDER BY nombre_mod";
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
                    <!-- <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="text" name="fecha" class="form-control datepicker" id="fecha" value="<?php echo $fecha_cot;?>" />
                    </div> -->
                </div>
            </div>
            <div id="contendor_boton" class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
            </div>
        </div>
        <!-- /.box-body -->
        
    </form>
</div>

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                rut: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                apellido_paterno: { 
                    required: true,
                    minlength: 3
                },
                apellido_materno: { 
                    required: true,
                    minlength: 3
                },
                correo:{
                    required: true,
                    minlength: 4,
                    email: true
                },
                fono:{
                    required: true,
                    minlength: 4
                },
                condominio: { 
                    required: true
                },
                 torre: { 
                    required: true
                },
                departamento: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                canal: { 
                    required: true
                },
                fecha: { 
                    required: true
                }

            },
            messages: {
                rut: {
                    required: "Ingrese Rut"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                apellido_paterno: {
                    required: "Ingrese Apellido Paterno",
                    minlength: "Mínimo 3 caracteres"
                },
                apellido_materno: {
                    required: "Ingrese Apellido Materno",
                    minlength: "Mínimo 3 caracteres"
                },
                correo: {
                    required: "Ingrese correo",
                    minlength: "Mínimo 4 caracteres",
                    email: "Ingrese correo válido"
                },
                fono: {
                    required: "Ingrese fono",
                    minlength: "Mínimo 4 caracteres"
                },
                condominio: {
                    required: "Seleccione condominio"
                },
                torre: {
                    required: "Seleccione torre"
                },
                departamento: {
                    required: "Seleccione departamento"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                canal: {
                    required: "Ingrese canal"
                },
                fecha: {
                    required: "Ingrese fecha"
                }
            }
        });

        $(document).on( "change","#propietario" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_propietario.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#contenedor_propietario').html(data);
                    }
                })
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
        $(document).on( "change","#torre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_torre.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#vivienda').html(data);
                    }
                })
            }
        });
        

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información actualizada con éxito!",
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
                swal("Atención!", "Información ya ha sido ingresada", "warning");
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