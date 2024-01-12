<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_banco_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_ban = $_POST["valor"];
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
        $consulta = "SELECT * FROM banco_banco WHERE id_ban = ?";
        $conexion->consulta_form($consulta,array($id_ban));
        $fila = $conexion->extraer_registro_unico();
        $nombre_ban = utf8_encode($fila['nombre_ban']);
        $convenio_ban = utf8_encode($fila['convenio_ban']);


        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_ban;?>"></input>
        <label>Banco: <?php echo $nombre_ban;?></label>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="nombre">Banco:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_ban;?>"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="convenio">Convenio Banco:</label>
                        <input type="text" name="convenio" class="form-control" id="convenio" value="<?php echo $convenio_ban;?>"/>
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
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.numero').numeric();
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true,
                    minlength: 3
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
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
                swal("Atención!", "Forma de pago ya ha sida ingresada", "warning");
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