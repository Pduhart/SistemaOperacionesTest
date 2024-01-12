<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_cotizacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_cot = $_POST["valor"];
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
        <h3 class="box-title"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Seguimiento      </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="insert_seguimiento.php">
        <?php  
        $consulta = 
            "
            SELECT
                con.nombre_con,
                con.id_con,
                viv.id_viv,
                viv.nombre_viv,
                can_cot.id_can_cot,
                can_cot.nombre_can_cot,
                tor.id_tor,
                tor.nombre_tor,
                mode.id_mod,
                mode.nombre_mod,
                pro.rut_pro,
                pro.id_pro,
                pro.nombre_pro,
                pro.nombre2_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                pro.correo_pro,
                pro.fono_pro,
                cot.fecha_cot,
                pre_cot.id_pre_cot,
                pre_cot.nombre_pre_cot,
                nac.id_nac,
                nac.nombre_nac,
                reg.id_reg,
                reg.descripcion_reg,
                com.id_com,
                com.nombre_com,
                prof.id_prof,
                prof.nombre_prof,
                pro.direccion_trabajo_pro
            FROM
                cotizacion_cotizacion AS cot 
                INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                INNER JOIN cotizacion_preaprobacion_cotizacion AS pre_cot ON pre_cot.id_pre_cot = cot.id_pre_cot
                LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
            WHERE
                cot.id_cot = ?
            ";
        $conexion->consulta_form($consulta,array($id_cot));
        $fila = $conexion->extraer_registro_unico();
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $id_can_cot = utf8_encode($fila['id_can_cot']);
        $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $nombre2_pro = utf8_encode($fila['nombre2_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_tor = utf8_encode($fila['id_tor']);
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $id_mod = utf8_encode($fila['id_mod']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));
        $id_pre_cot = utf8_encode($fila['id_pre_cot']);
        $nombre_pre_cot = utf8_encode($fila['nombre_pre_cot']);
        $id_com = $fila['id_com'];
        $id_reg = $fila['id_reg'];
        $nombre_com = utf8_encode($fila['nombre_com']);
        $descripcion_reg = utf8_encode($fila['descripcion_reg']);
        $nombre_nac = utf8_encode($fila['nombre_nac']);
        $id_nac = utf8_encode($fila['id_nac']);
        $id_prof = utf8_encode($fila['id_prof']);
        $nombre_prof = utf8_encode($fila['nombre_prof']);
        $direccion_trabajo_pro = utf8_encode($fila['direccion_trabajo_pro']);
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_cot;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Cotización: <?php echo $id_cot; ?> - Cliente: <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." - Fono:".$fono_pro;?></h4>
                </div>
                <div class="col-sm-12">
                	<div class="row">
                		<div class="col-sm-3">
	                        <div class="form-group">
	                            <label for="segundo_nombre">Segundo Nombre:</label>
	                            <input type="text" name="segundo_nombre" class="form-control" id="segundo_nombre" value="<?php echo $nombre2_pro;?>"/>
	                            <input type="hidden" name="id_pro" id="id_pro" value="<?php echo $id_pro;?>"/>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label for="apellido_materno">Apellido Materno:</label>
	                            <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo $apellido_materno_pro;?>"/>
	                        </div>
	                    </div>
	                	<div class="col-sm-3">
	                    	<div class="form-group">
	                            <label for="nacionalidad">Nacionalidad:</label>
	                            <select class="form-control select2" id="nacionalidad" name="nacionalidad"> 
	                                <option value="<?php echo $id_nac;?>"><?php echo $nombre_nac;?></option>
	                                <?php  
	                                $consulta = "SELECT * FROM nacionalidad_nacionalidad WHERE id_nac <> '".$id_nac."' ORDER BY nombre_nac";
	                                $conexion->consulta($consulta);
	                                $fila_consulta = $conexion->extraer_registro();
	                                if(is_array($fila_consulta)){
	                                    foreach ($fila_consulta as $fila) {
	                                        ?>
	                                        <option value="<?php echo $fila['id_nac'];?>"><?php echo utf8_encode($fila['nombre_nac']);?></option>
	                                        <?php
	                                    }
	                                }
	                                ?>
	                            </select>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
	                            <label for="region">Región:</label>
	                            <select class="form-control select2" id="region" name="region"> 
	                                <option value="<?php echo $id_reg;?>"><?php echo $descripcion_reg;?></option>
	                                <?php  
	                                $consulta = "SELECT * FROM region_region WHERE id_reg <> ".$id_reg." ORDER BY descripcion_reg";
	                                $conexion->consulta($consulta);
	                                $fila_consulta = $conexion->extraer_registro();
	                                if(is_array($fila_consulta)){
	                                    foreach ($fila_consulta as $fila) {
	                                        ?>
	                                        <option value="<?php echo $fila['id_reg'];?>"><?php echo utf8_encode($fila['descripcion_reg']);?></option>
	                                        <?php
	                                    }
	                                }
	                                ?>
	                            </select>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
		                        <label for="comuna">Comuna:</label>
		                        <select class="form-control select2" id="comuna" name="comuna"> 
		                            <option value="<?php echo $id_com;?>"><?php echo $nombre_com;?></option>
		                            <?php  
		                            $consulta = "SELECT * FROM comuna_comuna WHERE id_reg = ".$id_reg." AND id_com <> ".$id_com." ORDER BY nombre_com";
		                            $conexion->consulta($consulta);
		                            $fila_consulta = $conexion->extraer_registro();
		                            if(is_array($fila_consulta)){
		                                foreach ($fila_consulta as $fila) {
		                                    ?>
		                                    <option value="<?php echo $fila['id_com'];?>"><?php echo utf8_encode($fila['nombre_com']);?></option>
		                                    <?php
		                                }
		                            }
		                            ?>
		                        </select>
		                    </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
	                            <label for="profesion">Profesión:</label>
	                            <select class="form-control select2" id="profesion" name="profesion"> 
	                                <?php  
		                            if(!empty($id_prof)){
		                                ?>
		                                <option value="<?php echo $id_prof;?>"><?php echo $nombre_prof;?></option>
		                                <?php
		                            }
		                            else{
		                                ?>
		                                <option value="">Seleccione profesión</option>
		                                <?php
		                            }
		                            ?>
	                                <?php  
	                                $consulta = "SELECT * FROM profesion_profesion WHERE id_prof <> '".$id_prof."' ORDER BY nombre_prof";
	                                $conexion->consulta($consulta);
	                                $fila_consulta = $conexion->extraer_registro();
	                                if(is_array($fila_consulta)){
	                                    foreach ($fila_consulta as $fila) {
	                                        ?>
	                                        <option value="<?php echo $fila['id_prof'];?>"><?php echo utf8_encode($fila['nombre_prof']);?></option>
	                                        <?php
	                                    }
	                                }
	                                ?>
	                            </select>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                    	<div class="form-group">
	                            <label for="direccion_trabajo">Lugar de Trabajo:</label>
	                            <input type="text" name="direccion_trabajo" class="form-control" id="direccion_trabajo" value="<?php echo $direccion_trabajo_pro;?>"/>
	                        </div>
	                    </div>
                	</div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="interes">Interés:</label>
                        <select class="form-control" id="interes" name="interes"> 
                            <option value="">Seleccione Nivel de Interés</option>
                            <?php  
                            $consulta = "SELECT * FROM cotizacion_seguimiento_interes_cotizacion ORDER BY id_seg_int_cot DESC";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_seg_int_cot'];?>"><?php echo utf8_encode($fila['nombre_seg_int_cot']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="medio">Medio:</label>
                        <select class="form-control" id="medio" name="medio"> 
                            <option value="">Seleccione Medio</option>
                            <?php  
                            $consulta = "SELECT * FROM cotizacion_medio_cotizacion ORDER BY nombre_med_cot";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_med_cot'];?>"><?php echo utf8_encode($fila['nombre_med_cot']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="medio">Preaprobación crédito:</label>
                        <select class="form-control" id="preaprobacion" name="preaprobacion"> 
                            <option value="<?php echo $id_pre_cot;?>"><?php echo $nombre_pre_cot;?></option>
                            <?php  
                            $consulta = "SELECT * FROM cotizacion_preaprobacion_cotizacion WHERE id_pre_cot <> ".$id_pre_cot." ORDER BY nombre_pre_cot";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_pre_cot'];?>"><?php echo utf8_encode($fila['nombre_pre_cot']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" class="form-control" id="descripcion"></textarea>
                    </div>
                </div>
            </div>
            <div id="contendor_boton" class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
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
                interes: { 
                    required: true
                },
                descripcion: { 
                    required: true,
                    minlength: 3
                },
                medio:{
                    required: true
                },
                preaprobacion: { 
                    required: true
                }

            },
            messages: {
                interes: {
                    required: "Seleccione nivel interés"
                },
                descripcion: {
                    required: "Ingrese Descripción",
                    minlength: "Mínimo 3 caracteres"
                },
                medio: {
                    required: "Seleccione medio"
                },
                preaprobacion: {
                    required: "Ingrese preaprobación de crédito"
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

        $('#region').change(function(){
            var_region = $('#region').val();
            $.ajax({
                type: 'POST',
                url: ("select_comuna.php"),
                data:"region="+var_region,
                success: function(data) {
                    $('#comuna').html(data);
                }
            })
            
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