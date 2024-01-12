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
$id_emp = $_POST["valor"];
$cantidad_emp = $_POST["cantidad"];
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
        <h3 class="box-title"><i class="fa fa-plus" aria-hidden="true"></i> Crear Campaña de Mailing Masivo</h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="insert_campana_masiva.php">
        <input type="hidden" name="ids" id="ids" value="<?php echo $id_emp;?>"></input>
        
        <div class="box-body">
            <div class="row">                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="asunto">Asunto Mail:</label>
                        <input type="text" name="asunto" class="form-control" id="asunto"/>
                    </div>
                    <div class="form-group">
                        <label for="enlace_imagen">Link de la Imagen:</label>
                        <input type="text" name="enlace_imagen" class="form-control" id="enlace_imagen"/>
                    </div>
                    
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tipo_mail">Plantilla a enviar:</label>
                        <select class="form-control send" id="tipo_mail" name="tipo_mail"> 
                            <option value="">Seleccione Plantilla</option>
                            <?php  
                            $consulta = "SELECT * FROM campana_plantilla_campana WHERE id_est_cam_pla = 1 ORDER BY id_cam_pla";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo utf8_encode($fila['codigo_cam_pla']);?>"><?php echo utf8_encode($fila['titulo_cam_pla']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enlace_imagen">Cantidad Destinatarios:</label>
                        <input type="text" readonly name="ids_cant" id="ids_cant" class="form-control" value="<?php echo $cantidad_emp;?>"></input>
                    </div>
                </div>
            </div>
            <div id="contenedor_boton" class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Ingresar</button>
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
        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });


        $("#formulario").validate({
            rules: {
                asunto: { 
                    required: true
                },
                enlace_imagen: { 
                    required: true
                },
                tipo_mail: { 
                    required: true
                }
            },
            messages: {
                asunto: {
                    required: "Ingrese asunto al mail"
                },
                enlace_imagen: {
                    required: "Ingrese link"
                },
                tipo_mail: {
                    required: "Seleccione Plantilla"
                }
            }
        });

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Campaña ingresada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: true
                },
                function () {
                    location.href = "form_select.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Información ya ha sido ingresada", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Ingresar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Ingresar</button>');
            }
            if (data.envio == 4) {
                swal("Error!", "Su Envío supera cantidad Máxima mensual. (Total: "+data.supera+")", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Ingresar</button>');
            }
            if (data.envio == 5) {
            	console.log(data.respuesta);
                swal("Error!", "Campaña NO Enviada. (ERROR: "+data.respuesta.errors[0].message+")", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Ingresar</button>');
            }
            if (data.envio == 6) {
            	// alert(data.respuesta);
                swal("Error!", "Campaña Repetida fue recién enviada", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Ingresar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        }

        $('#formulario').submit(function () {
            if ($("#formulario").validate().form() == true){
                $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario').serialize();
                console.log(dataString);
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