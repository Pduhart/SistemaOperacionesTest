<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_vivienda_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_viv = $_POST["valor"];
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
                viv.id_viv,
                viv.id_tor,
                viv.id_mod,
                viv.id_ori_viv,
                viv.nombre_viv,
                viv.valor_viv,
                viv.metro_viv,
                viv.metro_terraza_viv,
                viv.bono_viv,
                viv.prorrateo_viv,
                tor.nombre_tor,
                modelo.nombre_mod,
                con.id_con,
                con.nombre_con,
                ori_viv.nombre_ori_viv,
                piso.id_pis,
                piso.nombre_pis
            FROM
                vivienda_vivienda AS viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
                INNER JOIN piso_piso AS piso ON piso.id_pis = viv.id_pis
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
            WHERE
                viv.id_viv = ?
            ";
        $conexion->consulta_form($consulta,array($id_viv));
        $fila = $conexion->extraer_registro_unico();
        $id_tor = utf8_encode($fila['id_tor']);
        $id_mod = utf8_encode($fila['id_mod']);
        $id_con = utf8_encode($fila['id_con']);
        $id_ori_viv = utf8_encode($fila['id_ori_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);

        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $id_pis = utf8_encode($fila['id_pis']);
        $nombre_pis = utf8_encode($fila['nombre_pis']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $nombre_ori_viv = utf8_encode($fila['nombre_ori_viv']);
        $valor_viv = utf8_encode($fila['valor_viv']);
        $metro_viv = utf8_encode($fila['metro_viv']);
        $metro_terraza_viv = utf8_encode($fila['metro_terraza_viv']);
        $bono_viv = utf8_encode($fila['bono_viv']);
        $prorrateo_viv = utf8_encode($fila['prorrateo_viv']);


        $consulta = 
            "
            SELECT
                pro.id_pro,
                pro.nombre_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro
            FROM
                propietario_propietario AS pro
            INNER JOIN propietario_vivienda_propietario AS viv ON pro.id_pro = viv.id_pro
            WHERE
                viv.id_viv = ?
            ";
        $conexion->consulta_form($consulta,array($id_viv));
        $fila = $conexion->extraer_registro_unico();
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_viv;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="condominio">Condominio:</label>
                        <select class="form-control select2" id="condominio" name="condominio"> 
                            <option value="<?php echo $id_con;?>"><?php echo $nombre_con;?></option>
                            <?php  
                            $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 AND id_con <> '".$id_con."' ORDER BY nombre_con";
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
                        <label for="torre">Torre:</label>
                        <select class="form-control select2" id="torre" name="torre">
                            <option value="<?php echo $id_tor;?>"><?php echo $nombre_tor;?></option>
                            <?php  
                            $consulta = "SELECT * FROM torre_torre WHERE id_est_tor = 1 AND id_con = '".$id_con."' AND id_tor <> '".$id_tor."' ORDER BY nombre_tor";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tor'];?>"><?php echo utf8_encode($fila['nombre_tor']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo:</label>
                        <select class="form-control select2" id="modelo" name="modelo"> 
                            <option value="<?php echo $id_mod;?>"><?php echo $nombre_mod;?></option>
                            <?php  
                            $consulta = "SELECT * FROM modelo_modelo WHERE id_est_mod = 1 AND id_mod <> '".$id_mod."' ORDER BY nombre_mod";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="propietario">Propietario:</label>
                        <select class="form-control select2" id="propietario" name="propietario"> 
                            <option value="<?php echo $id_pro;?>"><?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></option>
                            <?php  
                            $consulta = "SELECT id_pro,nombre_pro,apellido_paterno_pro,apellido_materno_pro FROM propietario_propietario WHERE id_est_pro = 1 AND id_pro <> '".$id_pro."' ORDER BY nombre_pro";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orientacion">Orientación:</label>
                        <select class="form-control select2" id="orientacion" name="orientacion"> 
                            <option value="<?php echo $id_ori_viv;?>"><?php echo $nombre_ori_viv;?></option>
                            <?php  
                            $consulta = "SELECT * FROM vivienda_orientacion_vivienda WHERE id_ori_viv <> '".$id_ori_viv."' ORDER BY id_ori_viv";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_ori_viv'];?>"><?php echo utf8_encode($fila['nombre_ori_viv']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="piso">Piso:</label>
                        <select class="form-control" id="piso" name="piso"> 
                            <option value="<?php echo $id_pis;?>"><?php echo $nombre_pis;?></option>
                            <?php  
                            $consulta = "SELECT * FROM piso_piso WHERE id_pis <> '".$id_pis."' ORDER BY id_pis";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_pis'];?>"><?php echo utf8_encode($fila['nombre_pis']);?></option>
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
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_viv;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor Depto:</label>
                        <input type="text" name="valor" class="form-control numero" id="valor" value="<?php echo $valor_viv;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="metro">Metro Depto:</label>
                        <input type="text" name="metro" class="form-control numero" id="metro" value="<?php echo $metro_viv;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="metro_terraza">Metro Terraza:</label>
                        <input type="text" name="metro_terraza" class="form-control numero" id="metro_terraza" value="<?php echo $metro_terraza_viv;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="bono">Bono:</label>
                        <input type="text" name="bono" class="form-control numero" id="bono" value="<?php echo $bono_viv;?>"/>
                    </div>
                    <div class="form-group">
                        <label for="prorrateo">Prorrateo (%):</label>
                        <input type="text" name="prorrateo" class="form-control numero" id="prorrateo" value="<?php echo $prorrateo_viv;?>"/>
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
                    minlength: 1
                },
                 torre: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                /*propietario: { 
                    required: true
                },*/
                orientacion: { 
                    required: true
                },
                valor: { 
                    required: true
                },
                metro: { 
                    required: true
                },
                metro_terraza: { 
                    required: true
                },
                bono: { 
                    required: true
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 1 caracteres"
                },
                torre: {
                    required: "Seleccione torre"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                /*propietario: {
                    required: "Seleccione propietario"
                },*/
                orientacion: {
                    required: "Seleccione orientación"
                },
                valor: {
                    required: "Ingrese valor"
                },
                metro: {
                    required: "Ingrese metro"
                },
                metro_terraza: {
                    required: "Ingrese metro"
                },
                bono: {
                    required: "Ingrese bono"
                }
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
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
                swal("Atención!", "Departamento ya ha sido ingresado", "warning");
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