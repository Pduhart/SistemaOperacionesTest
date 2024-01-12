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
$id_eta = $_POST["valor"];
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
    <form id="formulario" role="form" method="post" action="update.php">
        <?php  
        $consulta = 
            "
            SELECT
                eta.nombre_eta,
                eta.alias_eta,
                eta.numero_eta,
                eta.duracion_eta,
                cat_eta.id_cat_eta,
                cat_eta.nombre_cat_eta,
                for_pag.id_for_pag,
                for_pag.nombre_for_pag
            FROM
                etapa_etapa AS eta 
                INNER JOIN etapa_categoria_etapa AS cat_eta ON cat_eta.id_cat_eta = eta.id_cat_eta
                INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = eta.id_for_pag
            WHERE
                eta.id_eta = ?
            ";
        $conexion->consulta_form($consulta,array($id_eta));
        $fila = $conexion->extraer_registro_unico();
        
        
        $nombre_eta = utf8_encode($fila['nombre_eta']);
        $alias_eta = utf8_encode($fila['alias_eta']);
        $numero_eta = utf8_encode($fila['numero_eta']);
        $duracion_eta = utf8_encode($fila['duracion_eta']);
        $id_cat_eta = utf8_encode($fila['id_cat_eta']);
        $nombre_cat_eta = utf8_encode($fila['nombre_cat_eta']);
        $id_for_pag = utf8_encode($fila['id_for_pag']);
        $nombre_for_pag = utf8_encode($fila['nombre_for_pag']);
        


        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_eta;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select class="form-control" id="categoria" name="categoria"> 
                            <option value="<?php echo $id_cat_eta;?>"><?php echo $nombre_cat_eta;?></option>
                            <?php  
                            $consulta = "SELECT * FROM etapa_categoria_etapa WHERE id_cat_eta <> '".$id_cat_eta."' ORDER BY nombre_cat_eta";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_cat_eta'];?>"><?php echo utf8_encode($fila['nombre_cat_eta']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_eta;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="alias">Alias:</label>
                        <input type="text" name="alias" class="form-control" id="alias" value="<?php echo $alias_eta;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="numero">Número:</label>
                        <input type="text" name="numero" class="form-control numero" id="numero" value="<?php echo $numero_eta;?>"></input>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="forma_pago">Forma de Pago:</label>
                        <select class="form-control" id="forma_pago" name="forma_pago"> 
                            <option value="<?php echo $id_for_pag;?>"><?php echo $nombre_for_pag;?></option>
                            <?php  
                            $consulta = "SELECT * FROM pago_forma_pago WHERE id_for_pag <> '".$id_for_pag."' AND id_for_pag <= 2 ORDER BY nombre_for_pag";
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
                        <label for="duracion">Duración (Días):</label>
                        <input type="text" name="duracion" class="form-control numero" id="duracion" value="<?php echo $duracion_eta;?>"></input>
                    </div>
                </div>
                <div class="col-sm-12">
                    <h4 class="text-center">Campos Adicionales</h4>
                </div>
                <div class="col-sm-4 controls_num">
                    <div style="width: 100%">
                        <label>Campos Numéricos</label>
                        <div class="entry input-group">
                            <input class="form-control addnum" name="campo_numero[]" type="text" placeholder="nombre de campo numérico" />
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add-num" type="button">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 controls_fec">
                    <div style="width: 100%">
                        <label>Campos de Fecha</label>
                        <div class="entry input-group">
                            <input class="form-control addfec" name="campo_fecha[]" type="text" placeholder="nombre de campo fecha" />
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add-fec" type="button">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 controls_tex">
                    <div style="width: 100%">
                        <label>Campos de Texto</label>
                        <div class="entry input-group">
                            <input class="form-control addtex" name="campo_texto[]" type="text" placeholder="nombre de campo texto" />
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-add-tex" type="button">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                        </div>
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
        // add de campo numérico
        $(document).on('click', '.btn-add-num', function(e)
        {
            // alert("dsfsd");
            e.preventDefault();
            var controlForm = $('.controls_num div:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input.addnum').val('');
            controlForm.find('.entry:not(:last) .btn-add-num')
                .removeClass('btn-add-num').addClass('btn-remove-num')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.btn-remove-num', function(e)
        {
            $(this).parents('.entry:first').remove();
            e.preventDefault();
            return false;
        });
        // add de campo fecha
        $(document).on('click', '.btn-add-fec', function(e)
        {
            // alert("dsfsd");
            e.preventDefault();
            var controlForm = $('.controls_fec div:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input.addfec').val('');
            controlForm.find('.entry:not(:last) .btn-add-fec')
                .removeClass('btn-add-fec').addClass('btn-remove-fec')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.btn-remove-fec', function(e)
        {
            $(this).parents('.entry:first').remove();
            e.preventDefault();
            return false;
        });
        // add de campo texto
        $(document).on('click', '.btn-add-tex', function(e)
        {
            // alert("dsfsd");
            e.preventDefault();
            var controlForm = $('.controls_tex div:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input.addtex').val('');
            controlForm.find('.entry:not(:last) .btn-add-tex')
                .removeClass('btn-add-tex').addClass('btn-remove-tex')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.btn-remove-tex', function(e)
        {
            $(this).parents('.entry:first').remove();
            e.preventDefault();
            return false;
        });

        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                categoria: { 
                    required: true
                },
                forma_pago: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                alias: { 
                    required: true
                },
                numero: { 
                    required: true
                },
                duracion: { 
                    required: true
                }
            },
            messages: { 
                categoria: {
                    required: "Seleccione categoría"
                },
                forma_pago: {
                    required: "Seleccione forma de pago"
                },
                nombre: {
                    required: "Ingrese nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                alias: {
                    required: "Ingrese alias"
                },
                numero: {
                    required: "Ingrese número"
                },
                duracion: {
                    required: "Ingrese duración"
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
                swal("Atención!", "Etapa ya ha sido ingresada", "warning");
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