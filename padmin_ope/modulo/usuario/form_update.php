<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_usuario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>

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
        $consulta = "SELECT * FROM usuario_usuario WHERE id_usu = ?";
        $conexion->consulta_form($consulta,array($id));
        $fila = $conexion->extraer_registro_unico();
        $id_usu = utf8_encode($fila['id_usu']);
        $id_per = utf8_encode($fila['id_per']);
        $nombre_usu = utf8_encode($fila['nombre_usu']);
        $apellido1_usu = utf8_encode($fila['apellido1_usu']);
        $apellido2_usu = utf8_encode($fila['apellido2_usu']);
        $rut_usu = utf8_encode($fila['rut_usu']);
        $correo_usu = utf8_encode($fila['correo_usu']);
        $fono_usu = utf8_encode($fila['fono_usu']);
        $contrasena_usu = utf8_encode($fila['contrasena_usu']);
        $id_cat_vend = utf8_encode($fila['id_cat_vend']);

        $consulta = "SELECT * FROM vendedor_categoria_vendedor WHERE id_cat_vend = ?";
        $conexion->consulta_form($consulta,array($id_cat_vend));
        $fila = $conexion->extraer_registro_unico();
        $nombre_cat_vend = utf8_encode($fila['nombre_cat_vend']);
        ?>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_usu;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Apellido Paterno:</label>
                        <input type="text" name="apellido1" class="form-control" id="apellido1" value="<?php echo $apellido1_usu;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Apellido Materno:</label>
                        <input type="text" name="apellido2" class="form-control" id="apellido2" value="<?php echo $apellido2_usu;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="rut">Rut:</label>
                        <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_usu;?>" disabled/>
                    </div>
                    <?php 
					if ($id_per==2 || $id_per==5) {
                     ?>
                     <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control" id="categoria" name="categoria"> 
                            <option value="<?php echo $id_cat_vend;?>"><?php echo $nombre_cat_vend;?></option>
                            <?php  
                            $consulta = "SELECT * FROM vendedor_categoria_vendedor WHERE id_cat_vend <> '".$id_cat_vend."' ORDER BY id_cat_vend";
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
                    <?php 
					} else {
					 ?>
					<input type="hidden" name="categoria" id="categoria" value="0">
					<?php 
					}
                     ?>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="correo">Correo:</label>
                        <input type="text" name="correo" class="form-control" id="correo" value="<?php echo $correo_usu;?>"/>
                    </div>
                    <?php 
                    $readonly = "readonly";
                    if ($_SESSION["sesion_perfil_panel"] == 1) {
                    	$readonly = "";
                    }
                     ?>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" name="contrasena" class="form-control" id="contrasena" value="<?php echo $contrasena_usu;?>" <?php echo $readonly; ?>/>
                    </div>
                    <div class="form-group">
                        <label for="confirmar_contrasena">Confirmar Contraseña:</label>
                        <input type="password" name="confirmar_contrasena" class="form-control" id="confirmar_contrasena" value="<?php echo $contrasena_usu;?>" <?php echo $readonly; ?>/>
                    </div>
                    
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Condominio</h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled list-inline margin-0">
                            <?php
                            $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con ASC";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    $nombre_con = utf8_encode($fila['nombre_con']);
                                    $id_con = $fila['id_con'];
                                    $consulta = "SELECT * FROM usuario_condominio_usuario WHERE id_usu = ? AND id_con = ?";
                                    $conexion->consulta_form($consulta,array($id,$id_con));
                                    $cantidad = $conexion->total();
                                    if($cantidad > 0){
                                        ?>
                                        <li class="margin-bottom-10 col-sm-4">
                                            <input type="checkbox" name="modulo_condominio[]" id="modulo_<?php echo $id_con;?>" value="<?php echo $id_con;?>" checked class="condominio check_registro"><label for="modulo_<?php echo $id_con;?>"><span></span><?php echo $nombre_con;?></label>
                                            
                                        </li>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <li class="margin-bottom-10 col-sm-4">
                                            <input type="checkbox" name="modulo_condominio[]" id="modulo_<?php echo $id_con;?>" value="<?php echo $id_con;?>" class="condominio check_registro"><label for="modulo_<?php echo $id_con;?>"><span></span><?php echo $nombre_con;?></label>
                                            
                                        </li>
                                        <?php
                                    }
                                }
                            }
                            ?>  
                        </ul>
                    </div>
                </div>
            
            </div>
        </div>
            
        <input type="hidden" name="id" id="id" value="<?php echo $id;?>"></input>
        
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
<script type="text/javascript">
    $(document).ready(function() {
        $('.numero').numeric();
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $("#formulario").validate({
            rules: {
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
                fono: { 
                    required: true,
                    minlength: 6
                },
                correo: { 
                    required: true,
                    email: true,
                    minlength: 9
                },
                contrasena: {
                    required: true
                },
                confirmar_contrasena: {
                    required: true,
                    equalTo: "#contrasena"
                }
            },
            messages: { 
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
                fono: {
                    required: "Ingrese fono",
                    minlength: "Mínimo 6 caracteres"
                },
                correo: {
                    required: "Ingrese correo",
                    email: "Ingrese correo válido",
                    minlength: " Mínimo 9 caracteres"
                },
                contrasena: {
                    required: "Debe ingresar contraseña"
                },
                confirmar_contrasena: {
                    required: "Debe confirmar contraseña",
                    equalTo: "Las contraseñas deben coincidir"
                }
            }
        });
        $('#rut').Rut({
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
                swal("Atención!", "Proyecto ya ha sido ingresado", "warning");
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