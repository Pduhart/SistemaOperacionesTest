<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_torre_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_tor = $_POST["valor"];
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
        $consulta = "SELECT tor.nombre_tor, tor.id_con, con.nombre_con FROM torre_torre AS tor, condominio_condominio AS con WHERE tor.id_tor = ? AND tor.id_con = con.id_con";
        $conexion->consulta_form($consulta,array($id_tor));
        $fila = $conexion->extraer_registro_unico();
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_con = utf8_encode($fila['id_con']);
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_tor;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="condominio">Condominio:</label>
                        <select class="form-control" id="condominio" name="condominio"> 
                            <option value="<?php echo $id_con;?>"><?php echo $nombre_con;?></option>
                            <?php  
                            $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 AND id_con <> ".$id_con." ORDER BY nombre_con";
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
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="unidad">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_tor;?>"></input>
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

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $("#formulario").validate({
            rules: {
                condominio: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                }
            },
            messages: { 
                condominio: {
                    required: "Seleccione condominio"
                },
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
                    icon: "success"                    
                }).then(()=> location.href = "form_select.php");
            }
            if (data.envio == 2) {
                swal("Atención!", "Torre ya ha sido ingresada", "warning");
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