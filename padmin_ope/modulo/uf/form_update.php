<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_proyecto_panel"])) {
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
        $consulta = "SELECT * FROM proyecto_proyecto WHERE id_pro = ?";
        $conexion->consulta_form($consulta,array($id_pro));
        $fila = $conexion->extraer_registro_unico();
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $codigo_pro = utf8_encode($fila['codigo_pro']);
        $id_uni = utf8_encode($fila['id_uni']);
        $id_tip_pro = utf8_encode($fila['id_tip_pro']);
        $id_bon_pro = utf8_encode($fila['id_bon_pro']);
        $id_com_pro = utf8_encode($fila['id_com_pro']);

        $consulta = "SELECT * FROM unidad_unidad WHERE id_uni = ?";
        $conexion->consulta_form($consulta,array($id_uni));
        $fila = $conexion->extraer_registro_unico();
        $codigo_uni = utf8_encode($fila['codigo_uni']);
        $nombre_uni = utf8_encode($fila['nombre_uni']);

        $consulta = "SELECT * FROM proyecto_tipo_proyecto WHERE id_tip_pro = ?";
        $conexion->consulta_form($consulta,array($id_tip_pro));
        $fila = $conexion->extraer_registro_unico();
        $nombre_tip_pro = utf8_encode($fila['nombre_tip_pro']);

        $consulta = "SELECT * FROM proyecto_bono_proyecto WHERE id_bon_pro = ?";
        $conexion->consulta_form($consulta,array($id_bon_pro));
        $fila = $conexion->extraer_registro_unico();
        $nombre_bon_pro = utf8_encode($fila['nombre_bon_pro']);
        
        $consulta = "SELECT * FROM proyecto_detalle_proyecto WHERE id_pro = ?";
        $conexion->consulta_form($consulta,array($id_pro));
        $fila = $conexion->extraer_registro_unico();
        $nombre_cliente_det_pro = utf8_encode($fila['nombre_cliente_det_pro']);
        $direccion_det_pro = utf8_encode($fila['direccion_det_pro']);
        $ciudad_det_pro = utf8_encode($fila['ciudad_det_pro']);
        $rut_det_pro = utf8_encode($fila['rut_det_pro']);
        $giro_det_pro = utf8_encode($fila['giro_det_pro']);
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_pro;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="unidad">Unidad:</label>
                        <select class="form-control" id="unidad" name="unidad">
                            <option value="<?php echo $id_uni;?>"><?php echo $codigo_uni."-".$nombre_uni;?></option>
                            <?php  
                            $consulta = "SELECT * FROM unidad_unidad WHERE id_uni <> ".$id_uni." ORDER BY nombre_uni";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_uni'];?>"><?php echo utf8_encode($fila['codigo_uni']." - ".$fila['nombre_uni']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unidad">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_pro;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="unidad">Código:</label>
                        <input type="text" name="codigo" class="form-control" id="codigo" value="<?php echo $codigo_pro;?>"></input>
                    </div>
                    <?php  
                    if ($id_bon_pro == 1) {
                        ?>
                        <div class="form-group">
                            <label for="bono">Bono:</label>
                            <br>
                            <label>
                                <input type="radio" name="bono" class="minimal" value="1" checked>
                                Si
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="bono" class="minimal" value="2">
                                No
                            </label>
                        </div>
                        <?php
                    }
                    else{
                        ?>
                        <div class="form-group">
                            <label for="bono">Bono:</label>
                            <br>
                            <label>
                                <input type="radio" name="bono" class="minimal" value="1">
                                Si
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="bono" class="minimal" value="2" checked>
                                No
                            </label>
                        </div>
                        <?php
                    }

                    if ($id_com_pro == 1) {
                        ?>
                        <div class="form-group">
                            <label for="comentario">Comentario:</label>
                            <br>
                            <label>
                                <input type="radio" name="comentario" class="minimal" value="1" checked>
                                Si
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="comentario" class="minimal" value="2">
                                No
                            </label>
                        </div>
                        <?php
                    }
                    else{
                        ?>
                        <div class="form-group">
                            <label for="comentario">Comentario:</label>
                            <br>
                            <label>
                                <input type="radio" name="comentario" class="minimal" value="1">
                                Si
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="comentario" class="minimal" value="2" checked>
                                No
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                    
                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="unidad">Tipo Documento:</label>
                        <select class="form-control" id="tipo_documento" name="tipo_documento">
                            <option value="<?php echo $id_tip_pro;?>"><?php echo $nombre_tip_pro;?></option>
                            <?php  
                            $consulta = "SELECT * FROM proyecto_tipo_proyecto WHERE id_tip_pro <> ".$id_tip_pro." ORDER BY nombre_tip_pro";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tip_pro'];?>"><?php echo utf8_encode($fila['nombre_tip_pro']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div id="contenedor_tipo">
                        <?php  
                        if ($id_tip_pro == 2) {
                            ?>
                            <div class="form-group">
                                <label for="rut">Rut:</label>
                                <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_det_pro;?>" />
                            </div>
                            <div class="form-group">
                                <label for="nombre_cliente">Nombre Cliente:</label>
                                <input type="text" name="nombre_cliente" class="form-control" id="nombre_cliente" value="<?php echo $nombre_cliente_det_pro;?>"/>
                            </div>
                            <?php
                        }
                        if($id_tip_pro == 3) {
                            ?>
                            <div class="form-group">
                                <label for="rut">Rut:</label>
                                <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_det_pro;?>" />
                            </div>
                            <div class="form-group">
                                <label for="nombre_cliente">Nombre Cliente:</label>
                                <input type="text" name="nombre_cliente" class="form-control" id="nombre_cliente" value="<?php echo $nombre_cliente_det_pro;?>"/>
                            </div>
                            <div class="form-group">
                                <label for="giro">Giro:</label>
                                <input type="text" name="giro" class="form-control" id="giro" value="<?php echo $giro_det_pro;?>"/>
                            </div>
                            <div class="form-group">
                                <label for="ciudad">Ciudad:</label>
                                <input type="text" name="ciudad" class="form-control" id="ciudad" value="<?php echo $ciudad_det_pro;?>"/>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección:</label>
                                <input type="text" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion_det_pro;?>" />
                            </div>
                            <?php
                        }
                        ?>
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

<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $("#formulario").validate({
            rules: {
                unidad: { 
                    required: true
                },
                tipo_documento: { 
                    required: true
                },
                rut: { 
                    required: true
                },
                nombre_cliente: { 
                    required: true,
                    minlength: 4
                },
                nombre: { 
                    required: true,
                    minlength: 4
                },
                codigo:{
                    required: true,
                    minlength: 4
                },
                direccion:{
                    required: true,
                    minlength: 4
                },
                ciudad:{
                    required: true,
                    minlength: 4
                }
            },
            messages: { 
                unidad: {
                    required: "Seleccione unidad"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 4 caracteres"
                },
                nombre_cliente: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 4 caracteres"
                },
                codigo: {
                    required: "Ingrese código",
                    minlength: "Mínimo 4 caracteres"
                },
                direccion: {
                    required: "Ingrese dirección",
                    minlength: "Mínimo 4 caracteres"
                },
                tipo_documento: {
                    required: "Seleccione tipo documento"
                },
                rut: {
                    required: "Ingrese rut"
                },
                ciudad: {
                    required: "Ingrese ciudad",
                    minlength: "Mínimo 4 caracteres"
                }
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
        $(document).on( "change","#tipo_documento" , function() {
            $('#contenedor_tipo').html('<img src="../../assets/img/loading.gif">');
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_tipo_documento.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#contenedor_tipo').html(data);
                    }
                })
            }
            else{
                $('#contenedor_tipo').html('');
            }
        });
        $('#rut').Rut({
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