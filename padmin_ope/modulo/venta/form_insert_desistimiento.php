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
$id_ven = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
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
        <h3 class="box-title"><i class="fa fa-trash" aria-hidden="true"></i> Ingresar Desistimiento     </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="insert_desistimiento.php">
        <?php  
        $consulta = 
            "
            SELECT
                con.nombre_con,
                con.id_con,
                viv.id_viv,
                viv.nombre_viv,
                tor.id_tor,
                tor.nombre_tor,
                pro.rut_pro,
                pro.id_pro,
                pro.nombre_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                pro.correo_pro,
                pro.fono_pro,
                ven.fecha_ven
            FROM
                venta_venta AS ven 
                INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
            WHERE
                ven.id_ven = ?
            ";
        $conexion->consulta_form($consulta,array($id_ven));
        $fila = $conexion->extraer_registro_unico();
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_tor = utf8_encode($fila['id_tor']);
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $fecha_ven = date("d-m-Y",strtotime($fila['fecha_ven']));
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_ven;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Venta: <?php echo $id_ven; ?> | Cliente: <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." - Fono:".$fono_pro;?> | Depto: <?php echo $nombre_viv; ?></h4>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="text" name="fecha" class="form-control datepicker" id="fecha"/>
                    </div>
                </div>
                <div class="col-sm-6">
                	<div class="form-group">
                        <label for="motivo">Tipo Desistimiento:</label>
                        <select class="form-control elemento" id="motivo" name="motivo"> 
                            <option value="">Seleccione tipo</option>
                            <?php  
                            // tienen que ser los del condominio
                            $consulta = "SELECT * FROM desistimiento_tipo_desistimiento";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_tip_des'];?>"><?php echo utf8_encode($fila['nombre_tip_des']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Motivo:</label>
                        <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div id="contendor_boton" class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Ingresar Desistimiento</button>
            </div>
        </div>
        <!-- /.box-body -->
        
    </form>
</div>

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
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
                rut: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                apellido_paterno: { 
                    required: true,
                    minlength: 3
                },
                apellido_materno: { 
                    required: true,
                    minlength: 3
                },
                correo:{
                    required: true,
                    minlength: 4,
                    email: true
                },
                fono:{
                    required: true,
                    minlength: 4
                },
                condominio: { 
                    required: true
                },
                motivo: { 
                    required: true
                },
                 torre: { 
                    required: true
                },
                departamento: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                canal: { 
                    required: true
                },
                fecha: { 
                    required: true
                }

            },
            messages: {
                rut: {
                    required: "Ingrese Rut"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                apellido_paterno: {
                    required: "Ingrese Apellido Paterno",
                    minlength: "Mínimo 3 caracteres"
                },
                apellido_materno: {
                    required: "Ingrese Apellido Materno",
                    minlength: "Mínimo 3 caracteres"
                },
                correo: {
                    required: "Ingrese correo",
                    minlength: "Mínimo 4 caracteres",
                    email: "Ingrese correo válido"
                },
                fono: {
                    required: "Ingrese fono",
                    minlength: "Mínimo 4 caracteres"
                },
                condominio: {
                    required: "Seleccione condominio"
                },
                motivo: {
                    required: "Seleccione motivo"
                },
                torre: {
                    required: "Seleccione torre"
                },
                departamento: {
                    required: "Seleccione departamento"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                canal: {
                    required: "Ingrese canal"
                },
                fecha: {
                    required: "Ingrese fecha"
                }
            }
        });

        $(document).on( "change","#propietario" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_propietario.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#contenedor_propietario').html(data);
                    }
                })
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
                         $('#vivienda').html(data);
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