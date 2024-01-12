<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_estacionamiento_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_esta = $_POST["valor"];
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
                estac.nombre_esta,
                estac.valor_esta,
                tor.id_tor,
                tor.nombre_tor,
                viv.id_viv,
                viv.nombre_viv,
                con.id_con,
                con.nombre_con
            FROM
                estacionamiento_estacionamiento AS estac 
                INNER JOIN condominio_condominio AS con ON con.id_con = estac.id_con
                LEFT JOIN vivienda_vivienda AS viv ON viv.id_viv = estac.id_viv
                LEFT JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN estacionamiento_estado_estacionamiento AS est_esta ON est_esta.id_est_esta = estac.id_est_esta
            WHERE
                estac.id_esta = ?
            ";
        $conexion->consulta_form($consulta,array($id_esta));
        $fila = $conexion->extraer_registro_unico();

        
        
        if (empty($fila['id_tor'])) {
            $id_tor = 0;
        }
        else{
            $id_tor = $fila['id_tor'];
        }
        if (empty($fila['id_con'])) {
            $id_con = 0;
        }
        else{
            $id_con = $fila['id_con'];
        }


        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_esta = utf8_encode($fila['nombre_esta']);
        $valor_esta = utf8_encode($fila['valor_esta']);


        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        


        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_esta;?>"></input>
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
                            <?php
                            if ($id_tor <> 0) {
                                ?>
                                <option value="<?php echo $id_tor;?>"><?php echo $nombre_tor;?></option>
                                <?php    
                            }
                            else{
                                ?>
                                <option value="">Seleccione Torre</option>
                                <?php
                            }
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
                        <label for="departamento">Departamento:</label>
                        <select class="form-control select2" id="departamento" name="departamento"> 
                            <?php
                            if ($id_viv <> 0) {
                                ?>
                                <option value="<?php echo $id_viv;?>"><?php echo $nombre_viv;?></option>
                                <?php    
                            }
                            else{
                                ?>
                                <option value="">Seleccione Departamento</option>
                                <?php
                            }
                            $consulta = "SELECT * FROM vivienda_vivienda WHERE id_est_viv = 1 AND id_viv <> '".$id_viv."' ORDER BY nombre_viv";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?></option>
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
                        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_esta;?>"></input>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor Estacionamiento:</label>
                        <input type="text" name="valor" class="form-control numero" id="valor" value="<?php echo $valor_esta;?>"/>
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
                /*torre: { 
                    required: true
                },*/
                condominio: { 
                    required: true
                },
                propietario: { 
                    required: true
                },
                orientacion: { 
                    required: true
                },
                valor: { 
                    required: true
                }
            },
            messages: { 
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 1 caracteres"
                },
                /*torre: {
                    required: "Seleccione torre"
                },*/
                condominio: {
                    required: "Seleccione condominio"
                },
                propietario: {
                    required: "Seleccione propietario"
                },
                orientacion: {
                    required: "Seleccione orientación"
                },
                valor: {
                    required: "Ingrese valor"
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
        $(document).on( "change","#torre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_torre.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#departamento').html(data);
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
                swal("Atención!", "Estacionamiento ya ha sido ingresado", "warning");
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