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
        <h3 class="box-title"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Observación Masiva</h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="insert_observacion_masiva.php">
        <input type="hidden" name="ids" id="ids" value="<?php echo $id_emp;?>"></input>
        <input type="hidden" name="ids_cant" id="ids_cant" value="<?php echo $cantidad_emp;?>"></input>
        <div class="box-body">
            <div class="row">                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="descripcion">Observación Texto:</label>
                        <textarea name="descripcion" class="form-control send" id="descripcion"></textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tipo_obs">Observación Definida:</label>
                        <select class="form-control send" id="tipo_obs" name="tipo_obs"> 
                            <option value="">Seleccione Observación</option>
                            <?php  
                            $consulta = "SELECT * FROM propietario_tipo_observacion_propietario ORDER BY id_tip_obs_pro";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo utf8_encode($fila['nombre_tip_obs_pro']);?>"><?php echo utf8_encode($fila['nombre_tip_obs_pro']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div id="contendor_boton" class="box-footer">
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

		jQuery.validator.addMethod("require_from_group", function (value, element, options) {
	        var numberRequired = options[0];
	        var selector = options[1];
	        var fields = $(selector, element.form);
	        var filled_fields = fields.filter(function () {
	            // it's more clear to compare with empty string
	            return $(this).val() != "";
	        });
	        var empty_fields = fields.not(filled_fields);
	        // we will mark only first empty field as invalid
	        if (filled_fields.length < numberRequired && empty_fields[0] == element) {
	            return false;
	        }
	        return true;
	        // {0} below is the 0th item in the options field
	    }, jQuery.format("Debes Ingresar Observación en texto o seleccionarla."));

        $("#formulario").validate({
            groups: {
            	names: "uname email"
	        },
	        rules: {
	            tipo_obs: {
	                require_from_group: [1, ".send"]
	            },
	            descripcion: {
	                require_from_group: [1, ".send"]
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
                    closeOnConfirm: true
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