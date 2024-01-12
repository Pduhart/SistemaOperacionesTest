<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_pro = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
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
                pro.id_pro,
                pro.nombre_pro,
                pro.nombre2_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                pro.rut_pro,
                pro.pasaporte_pro,
                pro.fono_pro,
                pro.fono2_pro,
                pro.direccion_pro,
                pro.direccion_trabajo_pro,
                pro.correo_pro,
                pro.correo2_pro,
                pro.fecha_nacimiento_pro,
                pro.profesion_promesa_pro,
                nac.id_nac,
                nac.nombre_nac,
                reg.id_reg,
                reg.descripcion_reg,
                com.id_com,
                com.nombre_com,
                sex.id_sex,
                sex.nombre_sex,
                civ.id_civ,
                civ.nombre_civ,
                est.id_est,
                est.nombre_est,
                prof.id_prof,
                prof.nombre_prof
            FROM 
                propietario_propietario AS pro
                INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                INNER JOIN sexo_sexo  AS sex ON sex.id_sex = pro.id_sex
                INNER JOIN civil_civil  AS civ ON civ.id_civ = pro.id_civ
                LEFT JOIN estudio_estudio  AS est ON est.id_est = pro.id_est
                LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
            WHERE 
                pro.id_pro = ?
            ";
        $conexion->consulta_form($consulta,array($id_pro));

        $fila = $conexion->extraer_registro_unico();

        $nombre_pro = utf8_encode($fila['nombre_pro']);
    	$nombre2_pro = utf8_encode($fila['nombre2_pro']);
    	$apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
    	$apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);

        $rut_pro = utf8_encode($fila['rut_pro']);
        $pasaporte_pro = utf8_encode($fila['pasaporte_pro']);
        $direccion_pro = utf8_encode($fila['direccion_pro']);
        $direccion_trabajo_pro = utf8_encode($fila['direccion_trabajo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $fono2_pro = utf8_encode($fila['fono2_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $correo2_pro = utf8_encode($fila['correo2_pro']);

        $id_nac = utf8_encode($fila['id_nac']);
        $id_sex = utf8_encode($fila['id_sex']);
        $id_civ = utf8_encode($fila['id_civ']);
        $id_est = utf8_encode($fila['id_est']);
        $id_prof = utf8_encode($fila['id_prof']);
        $id_com = $fila['id_com'];
        $id_reg = $fila['id_reg'];

        $nombre_com = utf8_encode($fila['nombre_com']);
        $descripcion_reg = utf8_encode($fila['descripcion_reg']);
        $nombre_nac = utf8_encode($fila['nombre_nac']);
        
        $nombre_sex = utf8_encode($fila['nombre_sex']);
        $nombre_civ = utf8_encode($fila['nombre_civ']);
        $nombre_est = utf8_encode($fila['nombre_est']);
        $nombre_prof = utf8_encode($fila['nombre_prof']);
        $profesion_promesa_pro = utf8_encode($fila['profesion_promesa_pro']);
        $fecha_nacimiento_pro = utf8_encode($fila['fecha_nacimiento_pro']);
        $fecha_nacimiento_pro = date("d-m-Y",strtotime($fecha_nacimiento_pro));


        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_pro;?>"></input>
        <div class="box-body">
            <div class="row">
                <h4 class="text-center">Datos Generales</h4>
                
                <div class="col-sm-4">
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="nacionalidad">Nacionalidad:</label>
                        <select class="form-control select2" id="nacionalidad" name="nacionalidad"> 
                            <option value="<?php echo $id_nac;?>"><?php echo $nombre_nac;?></option>
                            <?php  
                            $consulta = "SELECT * FROM nacionalidad_nacionalidad WHERE id_nac <> '".$id_nac."' ORDER BY nombre_nac";
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
                            <option value="<?php echo $id_reg;?>"><?php echo $descripcion_reg;?></option>
                            <?php  
                            $consulta = "SELECT * FROM region_region WHERE id_reg <> ".$id_reg." ORDER BY descripcion_reg";
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
                            <option value="<?php echo $id_com;?>"><?php echo $nombre_com;?></option>
                            <?php  
                            $consulta = "SELECT * FROM comuna_comuna WHERE id_reg = ".$id_reg." AND id_com <> ".$id_com." ORDER BY nombre_com";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_com'];?>"><?php echo utf8_encode($fila['nombre_com']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo:</label>
                        <select class="form-control" id="sexo" name="sexo"> 
                            <option value="<?php echo $id_sex;?>"><?php echo $nombre_sex;?></option>
                            <?php  
                            $consulta = "SELECT * FROM sexo_sexo WHERE id_sex <> '".$id_sex."' ORDER BY id_sex";
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
                            <option value="<?php echo $id_civ;?>"><?php echo $nombre_civ;?></option>
                            <?php  
                            $consulta = "SELECT * FROM civil_civil WHERE id_civ <> '".$id_civ."' ORDER BY id_civ";
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
                            <option value="<?php echo $id_est;?>"><?php echo $nombre_est;?></option>
                            <?php  
                            $consulta = "SELECT * FROM estudio_estudio WHERE id_est <> '".$id_est."' ORDER BY id_est";
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
                            <?php  
                            if(!empty($id_prof)){
                                ?>
                                <option value="<?php echo $id_prof;?>"><?php echo $nombre_prof;?></option>
                                <?php
                            }
                            else{
                                ?>
                                <option value="">Seleccione profesión</option>
                                <?php
                            }
                            ?>
                            
                            <?php  
                            $consulta = "SELECT * FROM profesion_profesion WHERE id_prof <> '".$id_prof."' ORDER BY nombre_prof";
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
                        <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_pro;?>" />
                    </div>
                        
                    <div class="form-group">
                        <label for="nombre">Primer Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_pro;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="nombre2">Segundo Nombre:</label>
                        <input type="text" name="nombre2" class="form-control" id="nombre2" value="<?php echo $nombre2_pro;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="apellido_paterno">Apellido Paterno:</label>
                        <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" value="<?php echo $apellido_paterno_pro;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="apellido_materno">Apellido Materno:</label>
                        <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo $apellido_materno_pro;?>"/>
                    </div>
					
					<div class="form-group">
                        <label for="fecha_nacimiento">Fecha Nacimiento:</label>
                        <input type="text" name="fecha_nacimiento" class="form-control datepicker" id="fecha_nacimiento" value="<?php echo $fecha_nacimiento_pro;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="profesion_promesa">Profesión para Promesa:</label>
                        <input type="text" name="profesion_promesa" class="form-control" id="profesion_promesa" value="<?php echo $profesion_promesa_pro;?>"/>
                    </div>
                    
                </div>
                <div class="col-sm-4">

                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion_pro;?>"/>
                    </div>

                    <div class="form-group">
                        <label for="direccion_trabajo">Dirección Trabajo:</label>
                        <input type="text" name="direccion_trabajo" class="form-control" id="direccion_trabajo" value="<?php echo $direccion_trabajo_pro;?>"/>
                    </div>
                    
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="text" name="correo" class="form-control" id="correo" value="<?php echo $correo_pro;?>"/>
                    </div>

                    <div class="form-group">
                        <label for="correo2">Correo 2:</label>
                        <input type="text" name="correo2" class="form-control" id="correo2" value="<?php echo $correo2_pro;?>"/>
                    </div>
                    
                    <div class="form-group">
                        <label for="fono">Fono:</label>
                        <input type="text" name="fono" class="form-control numero" id="fono" value="<?php echo $fono_pro;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="fono2">Fono 2:</label>
                        <input type="text" name="fono2" class="form-control numero" id="fono2" value="<?php echo $fono2_pro;?>"/>
                    </div>
                </div>
                

            </div>
        </div>
        <!-- /.box-body -->
        <div id="contendor_boton" class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
        </div>
    </form>
</div>

<?php //include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

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
        

        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");
        $('.numero').numeric();

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

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
                comuna: {
                    required: true
                },
                region: {
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
                comuna: {
                    required: "Seleccione comuna"
                },
                region: {
                    required: "Seleccione región"
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

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
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
                swal("Atención!", "Propietario ya ha sido ingresado", "warning");
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