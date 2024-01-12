<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_bono_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_bon = $_POST["valor"];
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
    <form id="formulario" role="form" method="post" action="update.php">
        <?php  
        $consulta = 
            "
            SELECT
                bon.id_cat_bon,
                bon.nombre_bon,
                bon.desde_bon,
                bon.hasta_bon,
                bon.monto_bon,
                bon.fecha_desde_bon,
                bon.fecha_hasta_bon,
                tipo_bono.id_tip_bon,
                tipo_bono.nombre_tip_bon,
                con.id_con,
                con.nombre_con,
                mode.id_mod,
                mode.nombre_mod,
                cat_bono.nombre_cat_bon
            FROM
                bono_bono AS bon 
                INNER JOIN condominio_condominio AS con ON con.id_con = bon.id_con
                INNER JOIN bono_tipo_bono AS tipo_bono ON tipo_bono.id_tip_bon = bon.id_tip_bon
                INNER JOIN bono_categoria_bono AS cat_bono ON cat_bono.id_cat_bon = bon.id_cat_bon
                LEFT JOIN modelo_modelo AS mode ON mode.id_mod = bon.id_mod
            WHERE
                bon.id_bon = ?
            ";
        $conexion->consulta_form($consulta,array($id_bon));
        $fila = $conexion->extraer_registro_unico();
        
        $id_cat_bon = utf8_encode($fila['id_cat_bon']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_bon = utf8_encode($fila['nombre_bon']);
        $desde_bon = utf8_encode($fila['desde_bon']);
        $hasta_bon = utf8_encode($fila['hasta_bon']);
        $monto_bon = utf8_encode($fila['monto_bon']);
        $id_tip_bon = utf8_encode($fila['id_tip_bon']);
        $nombre_tip_bon = utf8_encode($fila['nombre_tip_bon']);
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_mod = utf8_encode($fila['id_mod']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $nombre_cat_bon = utf8_encode($fila['nombre_cat_bon']);

        if(!empty($fila['fecha_desde_bon'])){
            $fecha_desde_bon = date("d-m-Y",strtotime($fila['fecha_desde_bon']));
        }
        else{
            $fecha_desde_bon = "";
        }

        if(!empty($fila['fecha_hasta_bon'])){
            $fecha_hasta_bon = date("d-m-Y",strtotime($fila['fecha_hasta_bon']));
        }
        else{
            $fecha_hasta_bon = "";
        }

        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_bon;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="condominio">Condominio:</label>
                        <select class="form-control" id="condominio" name="condominio"> 
                            <option value="<?php echo $id_con;?>"><?php echo $nombre_con;?></option>
                            <?php  
                            $consulta = "SELECT * FROM condominio_condominio WHERE id_con <> '".$id_con."' ORDER BY nombre_con";
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
                    <div class="form-group">
                        <label for="tipo_bono">Tipo de Bono:</label>
                        <select class="form-control" id="tipo_bono" name="tipo_bono"> 
                            <option value="<?php echo $id_tip_bon;?>"><?php echo $nombre_tip_bon;?></option>
                            <?php  
                            $consulta = "SELECT * FROM bono_tipo_bono WHERE id_tip_bon <> '".$id_tip_bon."' ORDER BY nombre_tip_bon";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tip_bon'];?>"><?php echo utf8_encode($fila['nombre_tip_bon']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="valor">Monto Bono:</label>
                        <input type="text" name="valor" class="form-control numero" id="valor" value="<?php echo $monto_bon;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="fecha_desde">Fecha Desde:</label>
                        <input type="text" name="fecha_desde" class="form-control datepicker" id="fecha_desde" value="<?php echo $fecha_desde_bon;?>" />
                    </div>

                    <div class="form-group">
                        <label for="fecha_hasta">Fecha Hasta:</label>
                        <input type="text" name="fecha_hasta" class="form-control datepicker" id="fecha_hasta" value="<?php echo $fecha_hasta_bon;?>" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <select class="form-control" id="modelo" name="modelo"> 
                            <?php  
                            if(!empty($id_mod)){
                                ?>
                                <option value="<?php echo $id_mod;?>"><?php echo $nombre_mod;?></option>
                                <?php
                                $consulta = 
                                    "
                                    SELECT 
                                        mode.id_mod, 
                                        mode.nombre_mod
                                    FROM
                                        torre_torre AS tor 
                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                        INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                    WHERE
                                        tor.id_con = '".$id_con."' AND NOT
                                        mode.id_mod = '".$id_mod."' AND
                                        mode.id_est_mod = 1
                                    GROUP BY
                                        mode.id_mod, 
                                        mode.nombre_mod
                                    ORDER BY 
                                        mode.nombre_mod
                                    ASC
                                    ";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
                                        <?php
                                    }
                                }
                            }
                            else{
                                ?>
                                <option value="">Seleccione Modelo</option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="categoria" id="categoria" value="<?php echo $id_cat_bon; ?>">
                    <div class="form-group">
                        <label for="categoria">Categoría Bono:</label>
                        <span class="form-control"><?php echo $nombre_cat_bon; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_bon;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="desde">Desde:</label>
                        <input type="text" name="desde" class="form-control numero" id="desde" value="<?php echo $desde_bon;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta:</label>
                        <input type="text" name="hasta" class="form-control numero" id="hasta" value="<?php echo $hasta_bon;?>"/>
                    </div>
                </div>
                <div class="col-sm-6">
                    
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
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        $('.numero').numeric();

        <?php
        if ($id_cat_bon == 1) {
            echo "
                $('#modelo').prop('disabled', true);
                $('#fecha_desde').prop('disabled', true);
                $('#fecha_hasta').prop('disabled', true);
                ";
        }
        else if ($id_cat_bon == 2) {
            echo "
                $('#modelo').prop('disabled', true);
                $('#fecha_desde').prop('disabled', false);
                $('#fecha_hasta').prop('disabled', false);
                ";
        }
        else if($id_cat_bon == 3){
        	echo "
               $('#modelo').prop('disabled', false);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
               ";
            }
        else if($id_cat_bon == 4){
        	echo "
               $('#modelo').prop('disabled', true);
               $('#fecha_desde').prop('disabled', false);
               $('#fecha_hasta').prop('disabled', false);
               ";
            }
         else{
            echo "
                $('#modelo').prop('disabled', false);
                $('#fecha_desde').prop('disabled', false);
                $('#fecha_hasta').prop('disabled', false);
                ";
        }
        ?>

        $("#formulario").validate({
            rules: {
                nombre: { 
                    required: true,
                    minlength: 3
                },
                 desde: { 
                    required: true
                },
                tipo_bono: { 
                    required: true
                },
                condominio: { 
                    required: true
                },
                hasta: { 
                    required: true
                },
                valor: { 
                    required: true
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                desde: {
                    required: "Ingrese valor"
                },
                tipo_bono: {
                    required: "Seleccione tipo de bono"
                },
                condominio: {
                    required: "Seleccione condominio"
                },
                hasta: {
                    required: "Ingrese valor"
                },
                valor: {
                    required: "Ingrese Bono"
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
                swal("Atención!", "Bono ya ha sido ingresado", "warning");
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