<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_evento_panel"])) {
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
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
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
        $consulta = "SELECT * FROM evento_evento WHERE id_eve = ?";
        $conexion->consulta_form($consulta,array($id));
        $fila = $conexion->extraer_registro_unico();
        // $id_cat_eve = utf8_encode($fila['id_cat_eve']);
        $fecha_eve = utf8_encode($fila['fecha_eve']);
        $time_eve = utf8_encode($fila['time_eve']);
        $nombre_eve = utf8_encode($fila['nombre_eve']);
        $descripcion_eve = utf8_encode($fila['descripcion_eve']);

        $fecha_eve = date("d-m-Y",strtotime($fecha_eve));
        ?>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_eve;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="text" name="fecha" class="form-control datepicker" id="fecha" value="<?php echo $fecha_eve;?>"/>
                    </div>
                </div>
                <div class="col-sm-6">
                   	<div class="form-group">
                        <label for="medio">Hora Evento:</label>
						<input type="time" step="600" name="time" class="form-control" value="<?php echo $time_eve;?>" id="time"/>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" class="form-control" id="descripcion"><?php echo $descripcion_eve;?></textarea>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <input type="hidden" name="id" id="id" value="<?php echo $id;?>"></input>
        <div id="contendor_boton" class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
        </div> 
    </form>
    
    
    
</div>

<?php //include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        //Colorpicker
        

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });
        
        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true
                },
                time: { 
                    required: true
                },
                fecha: { 
                    required: true
                },
                descripcion: { 
                    required: true
                },
            },
            messages: { 
                nombre: {
                    required: "Ingrese nombre"
                },
                time: {
                    required: "Seleccione hora"
                },
                fecha: {
                    required: "Ingrese fecha"
                },
                descripcion: {
                    required: "Ingrese Descripción"
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