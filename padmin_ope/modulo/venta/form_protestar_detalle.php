<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_venta_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_pag = $_POST["valor"];
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
        <h3 class="box-title"><i class="fa fa-pencil" aria-hidden="true"></i> Protestar Pago      </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="protestar_detalle.php">
        <?php  
        $consulta = 
            "
            SELECT
                cat_pag.id_cat_pag,
                cat_pag.nombre_cat_pag,
                ban.id_ban,
                ban.nombre_ban,
                for_pag.id_for_pag,
                for_pag.nombre_for_pag,
                pag.fecha_pag,
                pag.fecha_real_pag,
                pag.monto_pag,
                pag.descripcion_protesto_pag,
                est_pag.nombre_est_pag,
                pag.numero_documento_pag
            FROM
                pago_pago AS pag 
                INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
            WHERE
                id_pag = ?
            ";
        $conexion->consulta_form($consulta,array($id_pag));
        $fila = $conexion->extraer_registro_unico();
        
        $id_cat_pag = utf8_encode($fila['id_cat_pag']);
        $nombre_cat_pag = utf8_encode($fila['nombre_cat_pag']);
        $id_ban = utf8_encode($fila['id_ban']);
        $nombre_ban = utf8_encode($fila['nombre_ban']);
        $id_for_pag = utf8_encode($fila['id_for_pag']);
        $nombre_for_pag = utf8_encode($fila['nombre_for_pag']);
        $fecha_pag = utf8_encode($fila['fecha_pag']);
        $fecha_pag = date("d-m-Y",strtotime($fecha_pag));
        $fecha_real_pag = utf8_encode($fila['fecha_real_pag']);
        $fecha_real_pag = date("d-m-Y",strtotime($fecha_real_pag));
        $monto_pag = utf8_encode($fila['monto_pag']);
        $nombre_est_pag = utf8_encode($fila['nombre_est_pag']);
        $numero_documento_pag = utf8_encode($fila['numero_documento_pag']);
        $descripcion_protesto_pag = utf8_encode($fila['descripcion_protesto_pag']);
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_pag;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="descripcion">Motivo:</label>
                        <?php
                        if ($descripcion_protesto_pag != "") {
                            ?>
                            <textarea class="form-control elemento_input" id="descripcion" name="descripcion"><?php echo $descripcion_protesto_pag;?></textarea>
                            <?php
                        }
                        else{
                            ?>
                            <textarea class="form-control elemento_input" id="descripcion" name="descripcion"></textarea>
                            <?php
                        }
                        ?>
                        
                    </div>
                
                    
                </div>
                <div class="col-sm-6">
                    
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
                descripcion: { 
                    required: true,
                    minlength: 3
                }
            },
            messages: { 
                descripcion: {
                    required: "Ingrese descripcion",
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
                    location.reload();
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