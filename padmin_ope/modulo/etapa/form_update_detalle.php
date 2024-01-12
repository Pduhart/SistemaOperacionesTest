<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_etapa_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_cam_eta = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
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
    <form id="formulario" role="form" method="post" action="update_detalle.php">
        <?php  
        $consulta = 
            "
            SELECT
                cam_eta.nombre_cam_eta,
                tip_cam_eta.id_tip_cam_eta,
                tip_cam_eta.nombre_tip_cam_eta
            FROM
                etapa_campo_etapa AS cam_eta 
                INNER JOIN etapa_tipo_campo_etapa AS tip_cam_eta ON tip_cam_eta.id_tip_cam_eta = cam_eta.id_tip_cam_eta
            WHERE
                cam_eta.id_cam_eta = ?
            ";
        $conexion->consulta_form($consulta,array($id_cam_eta));
        $fila = $conexion->extraer_registro_unico();
        
        
        $nombre_cam_eta = utf8_encode($fila['nombre_cam_eta']);
        $id_tip_cam_eta = utf8_encode($fila['id_tip_cam_eta']);
        $nombre_tip_cam_eta = utf8_encode($fila['nombre_tip_cam_eta']);
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_cam_eta;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tipo">Tipo Campo:</label>
                        <select class="form-control" id="tipo" name="tipo"> 
                            <option value="<?php echo $id_tip_cam_eta;?>"><?php echo $nombre_tip_cam_eta;?></option>
                            <?php  
                            $consulta = "SELECT * FROM etapa_tipo_campo_etapa WHERE id_tip_cam_eta <> '".$id_tip_cam_eta."' ORDER BY nombre_tip_cam_eta";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tip_cam_eta'];?>"><?php echo utf8_encode($fila['nombre_tip_cam_eta']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_cam_eta;?>"></input>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- /.box-body -->
        <div id="contenedor_boton" class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
        </div>
    </form>
</div>

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
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
        

        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true,
                    minlength: 3
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese nombre",
                    minlength: "Mínimo 3 caracteres"
                }
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
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
                swal("Atención!", "Etapa ya ha sido ingresado", "warning");
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