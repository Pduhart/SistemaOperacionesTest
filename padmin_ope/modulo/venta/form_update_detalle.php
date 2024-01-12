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
    <form id="formulario" role="form" method="post" action="../venta/update_detalle.php">
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
                est_pag.nombre_est_pag,
                pag.numero_documento_pag,
                pag.numero_serie_pag
            FROM
                pago_pago AS pag 
                INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                LEFT JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
            WHERE
                id_pag = ?
            ";
        $conexion->consulta_form($consulta,array($id_pag));
        $fila = $conexion->extraer_registro_unico();
        
        $id_cat_pag = utf8_encode($fila['id_cat_pag']);
        $nombre_cat_pag = utf8_encode($fila['nombre_cat_pag']);
        $id_ban = utf8_encode($fila['id_ban']);
        if ($id_ban=='') {
        	$id_ban=17;
        }
        $nombre_ban = utf8_encode($fila['nombre_ban']);
        $id_for_pag = utf8_encode($fila['id_for_pag']);
        $nombre_for_pag = utf8_encode($fila['nombre_for_pag']);
        $fecha_pag = utf8_encode($fila['fecha_pag']);
        $fecha_pag = date("d-m-Y",strtotime($fecha_pag));
        $fecha_real_pag = utf8_encode($fila['fecha_real_pag']);
        if(!empty($fecha_real_pag) && $fecha_real_pag != '0000-00-00' && $fecha_real_pag != null){
			$fecha_real_pag = date("d-m-Y",strtotime($fecha_real_pag));
        }
        else{
        	$fecha_real_pag = "";
        }
        
        $monto_pag = utf8_encode($fila['monto_pag']);
        $nombre_est_pag = utf8_encode($fila['nombre_est_pag']);
        $numero_documento_pag = utf8_encode($fila['numero_documento_pag']);
        $numero_serie_pag = utf8_encode($fila['numero_serie_pag']);
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_pag;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="forma_pago">Forma de Pago:</label>
                        <select class="form-control select2 elemento_select" id="forma_pago" name="forma_pago"> 
                            <option value="<?php echo $id_for_pag;?>"><?php echo $nombre_for_pag;?></option>
                            <?php  
                            $consulta = "SELECT id_for_pag, nombre_for_pag FROM pago_forma_pago WHERE id_for_pag <> ".$id_for_pag." ORDER BY nombre_for_pag";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_for_pag'];?>"><?php echo utf8_encode($fila['nombre_for_pag']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="banco">Banco:</label>
                        <select class="form-control select2 elemento_select" id="banco" name="banco"> 
                            <option value="<?php echo $id_ban;?>"><?php echo $nombre_ban;?></option>
                            <?php  
                            $consulta = "SELECT id_ban, nombre_ban FROM banco_banco WHERE id_ban <> ".$id_ban." ORDER BY nombre_ban";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_ban'];?>"><?php echo utf8_encode($fila['nombre_ban']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="text" name="fecha" class="form-control datepicker elemento_input" id="fecha" value="<?php echo $fecha_pag;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="numero_documento">Número Documento:</label>
                        <input type="text" name="numero_documento" class="form-control elemento_input" id="numero_documento" value="<?php echo $numero_documento_pag;?>"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control select2 elemento_select" id="categoria" name="categoria"> 
                            <option value="<?php echo $id_cat_pag;?>"><?php echo $nombre_cat_pag;?></option>
                            <?php  
                            $consulta = "SELECT id_cat_pag, nombre_cat_pag FROM pago_categoria_pago WHERE id_cat_pag <> ".$id_cat_pag." ORDER BY nombre_cat_pag";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_cat_pag'];?>"><?php echo utf8_encode($fila['nombre_cat_pag']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="monto">Monto:</label>
                        <input type="text" name="monto" class="form-control numero elemento_input" id="monto" value="<?php echo $monto_pag;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="fecha_real">Fecha Real:</label>
                        <input type="text" name="fecha_real" class="form-control datepicker" id="fecha_real" value="<?php echo $fecha_real_pag;?>"/>
                    </div>
                    <!-- <div class="form-group"> -->
                        <!-- <label for="numero_serie">Número Serie:</label> -->
                        <input type="hidden" name="numero_serie" class="form-control elemento_input" id="numero_serie" value="<?php echo $numero_serie_pag;?>"/>
                    <!-- </div> -->
                    
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
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       
       $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

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