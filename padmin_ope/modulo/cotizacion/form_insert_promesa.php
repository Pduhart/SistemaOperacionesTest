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

    .proceso .info{
        padding:6px 0px;
    }

    .proceso h5{
        font-weight: bold;
        text-decoration: underline;
}
</style>
</head>
<body>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-check" aria-hidden="true"></i> Pasar a Promesa      </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div id="formulario" class="form form-inline">
        <?php  
        $consulta = 
            "
            SELECT
                con.nombre_con,
                con.id_con,
                viv.id_viv,
                viv.nombre_viv,
                viv.valor_viv,
                can_cot.id_can_cot,
                can_cot.nombre_can_cot,
                tor.id_tor,
                tor.nombre_tor,
                mode.id_mod,
                mode.nombre_mod,
                pro.rut_pro,
                pro.id_pro,
                pro.nombre_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                pro.correo_pro,
                pro.fono_pro,
                cot.fecha_cot
            FROM
                cotizacion_cotizacion AS cot 
                INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
            WHERE
                cot.id_cot = ?
            ";
        $conexion->consulta_form($consulta,array($id_cot));
        $fila = $conexion->extraer_registro_unico();
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $valor_viv = utf8_encode($fila['valor_viv']);
        $id_can_cot = utf8_encode($fila['id_can_cot']);
        $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_tor = utf8_encode($fila['id_tor']);
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $id_mod = utf8_encode($fila['id_mod']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));
        
        $consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ? ";
        $conexion->consulta_form($consulta,array(4,$id_con));
        $fila = $conexion->extraer_registro_unico();
        $porcentaje_descuento = utf8_encode($fila['valor_par']);
        $total_descuento = ($valor_viv * $porcentaje_descuento) / 100;
        $total_vivienda = $valor_viv - $total_descuento;

        $consulta ="SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
        $conexion->consulta_form($consulta,array(12,$id_con));
        $fila = $conexion->extraer_registro_unico();
        $monto_reserva = utf8_encode($fila['valor_par']);
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_cot;?>"></input>
        <input type="hidden" name="id_vivienda" id="id_vivienda" value="<?php echo $id_viv;?>"></input>
        <input type="hidden" name="id_condominio" id="id_condominio" value="<?php echo $id_con;?>"></input>
        <input type="hidden" name="id_pro" id="id_pro" value="<?php echo $id_pro;?>"></input>
        <input type="hidden" name="monto_reserva" id="monto_reserva" value="<?php echo $monto_reserva;?>"></input>
        <input type="hidden" name="valor_viv" id="valor_viv" value="<?php echo $valor_viv;?>"></input>
        <input type="hidden" name="porcentaje_descuento" id="porcentaje_descuento" value="<?php echo $porcentaje_descuento;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Cotización: <?php echo $id_cot; ?> - Cliente: <?php echo ucfirst(strtolower($nombre_pro))." ".ucfirst(strtolower($apellido_paterno_pro))." ".ucfirst(strtolower($apellido_materno_pro))." - Fono : ".$fono_pro;?></h4>
                </div>
                <div class="col-sm-12">
                	<table class="table table-bordered">
                		<tr class="bg-light-blue color-palette">
                			<td colspan="3" style="font-weight:bold;">UNIDAD : <?php echo $nombre_viv; ?> - <?php echo $nombre_con; ?></td>
                		</tr>
                		<tr class="bg-light-blue color-palette">
                			<td><b>Precio Depto:</b> <?php echo number_format($valor_viv, 2, ',', '.');?> UF .-</td>
                			<td><b>Monto Descuento:</b> <?php echo number_format($total_descuento, 2, ',', '.');?> UF .- ( <?php echo $porcentaje_descuento; ?> %) </td>
                			<td><b>Total Depto:</b> <?php echo number_format($total_vivienda, 2, ',', '.');?> UF .-</td>
                		</tr>
                	</table>
					<div class="container">
	                    <div class="col-sm-4 text-right">
	                        <?php
	                        $consulta ="SELECT nombre_bod FROM bodega_bodega WHERE id_viv = ?";
	                        $conexion->consulta_form($consulta,array($id_viv));
	                        $fila_consulta = $conexion->extraer_registro();
	                        $cantidad = $conexion->total();
	                        if(is_array($fila_consulta)){
	                            foreach ($fila_consulta as $fila){
	                                $nombre_bod = utf8_encode($fila["nombre_bod"]);
	                                ?>
	                                    <i class="fa fa-cubes"></i> Bod. <span><?php echo $nombre_bod;?></span>
	                                <?php
	                            }
	                        }
	                        ?>
	                    </div>
	                    <div class="col-sm-4 text-right">
	                        <?php
	                        $consulta = " SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE id_viv = ?";
	                        $conexion->consulta_form($consulta,array($id_viv));
	                        $fila_consulta = $conexion->extraer_registro();
	                        $cantidad = $conexion->total();
	                        if(is_array($fila_consulta)){
	                            foreach ($fila_consulta as $fila) {
	                                $nombre_esta = utf8_encode($fila["nombre_esta"]);
	                                ?>
	                                    <i class="fa fa-car"></i> Est. <span><?php echo $nombre_esta;?></span>
	                                <?php
	                            }
	                        }
	                        ?>
	                    </div>
                        <hr class="col-sm-12">
                    </div>
                    
                </div>
                <div class="container">

                	<div class="row">
                        <div class="col-sm-12 text-center">
                            <h4><b>* Tipos de descuentos disponiples para aplicar</b></h4>
                            <b><small>Monto Reserva (UF): <?php echo $monto_reserva;?></small></b>
                        </div>
                        <hr class="col-sm-12">
                    </div>

                    <div class="row" style="margin:10px 0 25px 20px;"  >
                        <div class="col-sm-4 col-sm-offset-2 text-center">                           
                            <input type="radio" name="inlineRadioOptions" id="altotal" value="2">  Aplicar descuento al valor total de la propiedad                                           
                        </div>
                        <div class="col-sm-4 text-center">
                            <input type="radio" name="inlineRadioOptions" id="alpie" value="1" checked>  Aplicar descuento al pie (Abono Inmobiliario)
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:20px;">                       
	                    <div class="col-sm-4 col-sm-offset-2 text-center" id="alTotal">
                            <div class="col-sm-12">
                                <label for="monto_vivienda">Descuento al total (<?php echo number_format($total_vivienda, 2, ',', '.');?>) <i class="fa fa-check-square-o bg-success" aria-hidden="true"></i><br><small>*descuento al valor total del precio.</small></label>
                            </div>
                            <div class="col-sm-3 col-sm-offset-3">
                                <div class="form-group">                                    
                                    <input type="number" name="alPrecio" class="form-control numero elemento" id="alPrecio" step="any" value="<?php echo intval(number_format($total_descuento,2,',','.'));?>"/>
                                </div>
                            </div>                              
	                    </div>
                        <div class="col-sm-4 text-center" id="alAbono">
	                        <div class="form-group">
	                            <label for="monto_vivienda">Abono Inmobiliario <i class="fa fa-check-square-o bg-success"></i><br><small>*Descuento al valor del pie del departamento.</small></label>	                            
	                        </div>
	                    </div>
                    </div>
                    <hr class="col-sm-12">
                    <div class="row">   

                        <div class="col-sm-4 text-right">
                            <div class="form-group">
                                <label for="fecha">Fecha:</label>
                                <input type="text" name="fecha" class="form-control datepicker elemento" id="fecha"/>
                            </div>
                        </div>	

	                    <div class="col-sm-4 text-center">
	                        <div class="form-group">
	                            <label for="forma_pago">Forma de Pago:</label>
	                            <select class="form-control elemento" id="forma_pago" name="forma_pago"> 
	                                <option value="">Seleccione Opción</option>
	                                <?php  
	                                $consulta = "SELECT * FROM pago_forma_pago WHERE id_for_pag <= 2 ORDER BY nombre_for_pag";
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
	                    </div>

	                    <div class="col-sm-4 text-left">
	                        <div class="form-group">
	                            <label for="pie">PIE (UF):</label>
                                <input type="number" step="any" class="form-control elemento" id="pie" name="pie">	                            
	                        </div>
	                    </div>                        
                    </div>
                   <div class="row" style="padding:3%;">

                   <div class="col-sm-5 col-sm-offset-2">
	                        <div class="form-group">
	                            <label for="premio">Premio:</label>
	                            <select class="form-control" id="premio" name="premio"> 
	                                <option value="">Seleccione Premio</option>
	                                <?php  
	                                $consulta = "SELECT * FROM premio_premio WHERE id_est_pre = 1 ORDER BY nombre_pre";
	                                $conexion->consulta($consulta);
	                                $fila_consulta = $conexion->extraer_registro();
	                                if(is_array($fila_consulta)){
	                                    foreach ($fila_consulta as $fila) {
	                                        ?>
	                                        <option value="<?php echo $fila['id_pre'];?>"><?php echo utf8_encode($fila['nombre_pre']);?></option>
	                                        <?php
	                                    }
	                                }
	                                ?>
	                            </select>
	                        </div>
	                    </div>

                   </div>
	                    
                   
                    <div class="row" style="padding-bottom:30px;">                       
                        <?php if($id_con == 7){?>
                                <div class="col-sm-12">
                                    <div class="box-body col-sm-6">
                                        <h4 class="col-sm-12">Estacionamiento Adicional:</h4>
                                        <ul class="list-unstyled list-inline margin-0">
                                            <?php
                                            $consulta = "SELECT * FROM estacionamiento_estacionamiento WHERE id_viv = 0 AND id_con = ".$id_con." ORDER BY nombre_esta ASC";
                                            $conexion->consulta($consulta);
                                            $fila_consulta = $conexion->extraer_registro();
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $id_esta = utf8_encode($fila['id_esta']);
                                                    $nombre_esta = utf8_encode($fila['nombre_esta']);
                                                    ?>
                                                    <li class="margin-bottom-10 col-sm-3">
                                                        <input type="checkbox" name="estacionamiento[]" id="estacionamiento_<?php echo $id_esta;?>" value="<?php echo $id_esta;?>" class="estacionamiento check_registro elemento"><label for="estacionamiento_<?php echo $id_esta;?>"><span></span><?php echo $nombre_esta;?></label>
                                                        
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>  
                                        </ul>
                                    </div>
                                    <div class="box-body col-sm-6">
                                        <h4 class="col-sm-12">Bodega Adicional:</h4>
                                        <ul class="list-unstyled list-inline margin-0">
                                            <?php
                                            $consulta = "SELECT * FROM bodega_bodega WHERE id_viv = 0 AND id_con = ".$id_con." ORDER BY nombre_bod ASC";
                                            $conexion->consulta($consulta);
                                            $fila_consulta = $conexion->extraer_registro();
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $id_bod = utf8_encode($fila['id_bod']);
                                                    $nombre_bod = utf8_encode($fila['nombre_bod']);
                                                    ?>
                                                    <li class="margin-bottom-10 col-sm-3">
                                                        <input type="checkbox" name="bodega[]" id="bodega_<?php echo $id_bod;?>" value="<?php echo $id_bod;?>" class="bodega check_registro elemento"><label for="bodega_<?php echo $id_bod;?>"><span></span><?php echo $nombre_bod;?></label>
                                                        
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>  
                                        </ul>
                                    </div>	                        
                                </div>
                        <?php }?>

	                    <div class="col-sm-3 col-sm-offset-8">
	                        <button type="button" id="procesar_boton" name="procesar_boton" class="btn btn-warning btn-lg"><i class="fa fa-spinner" aria-hidden="true"></i> Procesar</button>
	                    </div>
	                </div>

                </div>
                <div class="col-sm-12" id="contenedor_vivienda"></div>
            </div>
            
        </div>
        <!-- /.box-body -->
        
    </div> <!-- form -->
</div>

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<!-- <script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script> -->
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

        
        $(document).on( "click","#procesar_boton" , function() {        
            let opt = $( "input[name=inlineRadioOptions]:checked" ).val();
            let monto_viv = '';
            let vivienda = parseInt("<?php echo $valor_viv;?>");
            let alPrecio = parseInt($("#alPrecio").val());
            if(opt == "1"){
                monto_viv = vivienda;
            }else{
                monto_viv = vivienda - alPrecio;
            }        
            var procesa = {  
                    id: "<?php echo $id_cot;?>",  
                    id_vivienda : "<?php echo $id_viv;?>", 
                    id_condominio:  "<?php echo $id_con;?>",
                    valor_viv:  "<?php echo $valor_viv;?>",
                    monto_vivienda:  monto_viv,
                    fecha:  $('#fecha').val(),
                    precio_descuento:  opt,                   
                    forma_pago:  $("#forma_pago").val(),
                    pie:  $("#pie").val(),
                    premio:  $("#premio").val(),                    
                    aplica_pie:   $( "input[name=inlineRadioOptions]:checked" ).val(), // 1 = abono inmobiliario ; 2 = desc al precio
                    estacionamiento: [],
                    bodega:[]
                };
            // var dataString = $('#formulario').serialize();            
            $.ajax({
                data: procesa,
                type: 'POST',
                url: ("procesa_calculo_vivienda.php"),
                success: function (data) {
                    $('#contenedor_vivienda').html(data);
                }
            })
        });
        
        $(document).on( "change",".elemento" , function() {
            $('#contenedor_vivienda').html('');
        });

        // $("#formulario").validate({
        //     rules: {
        //         rut: { 
        //             required: true
        //         },
        //         nombre: { 
        //             required: true,
        //             minlength: 3
        //         },
        //         apellido_paterno: { 
        //             required: true,
        //             minlength: 3
        //         },
        //         apellido_materno: { 
        //             required: true,
        //             minlength: 3
        //         },
        //         correo:{
        //             required: true,
        //             minlength: 4,
        //             email: true
        //         },
        //         fono:{
        //             required: true,
        //             minlength: 4
        //         },
        //         condominio: { 
        //             required: true
        //         },
        //          torre: { 
        //             required: true
        //         },
        //         departamento: { 
        //             required: true
        //         },
        //         modelo: { 
        //             required: true
        //         },
        //         canal: { 
        //             required: true
        //         },
        //         fecha: { 
        //             required: true
        //         }

        //     },
        //     messages: {
        //         rut: {
        //             required: "Ingrese Rut"
        //         },
        //         nombre: {
        //             required: "Ingrese Nombre",
        //             minlength: "Mínimo 3 caracteres"
        //         },
        //         apellido_paterno: {
        //             required: "Ingrese Apellido Paterno",
        //             minlength: "Mínimo 3 caracteres"
        //         },
        //         apellido_materno: {
        //             required: "Ingrese Apellido Materno",
        //             minlength: "Mínimo 3 caracteres"
        //         },
        //         correo: {
        //             required: "Ingrese correo",
        //             minlength: "Mínimo 4 caracteres",
        //             email: "Ingrese correo válido"
        //         },
        //         fono: {
        //             required: "Ingrese fono",
        //             minlength: "Mínimo 4 caracteres"
        //         },
        //         condominio: {
        //             required: "Seleccione condominio"
        //         },
        //         torre: {
        //             required: "Seleccione torre"
        //         },
        //         departamento: {
        //             required: "Seleccione departamento"
        //         },
        //         modelo: {
        //             required: "Seleccione modelo"
        //         },
        //         canal: {
        //             required: "Ingrese canal"
        //         },
        //         fecha: {
        //             required: "Ingrese fecha"
        //         }
        //     }
        // });

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
                    icon: "success"
                    
                }).then(()=>location.href = "form_select.php");
            }
            if (data.envio == 2) {
                swal("Atención!", "Información ya ha sido ingresada", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }            
        }

        $(document).on( "click","#guardar" , function() {   
            let opt = $( "input[name=inlineRadioOptions]:checked" ).val();
            let monto_viv = '';
            let vivienda = parseInt("<?php echo $valor_viv;?>");
            let alPrecio = parseInt($("#alPrecio").val());
            if(opt == "1"){
                monto_viv = vivienda;
            }else{
                monto_viv = vivienda - alPrecio;
            } 
            $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
            let estacionamiento = [];
            let bodega = [];
            <?php if($id_con == 7){?>
                estacionamiento = $('input[name="estacionamiento"]:checked').val();
                bodega = $('input[name="bodega"]:checked').val();
            <?php };?>
            var promesa = {  
                    id: "<?php echo $id_cot;?>",  
                    id_vivienda : "<?php echo $id_viv;?>", 
                    id_condominio:  "<?php echo $id_con;?>",
                    id_pro: "<?php echo $id_pro;?>",
                    monto_reserva: "<?php echo $monto_reserva;?>",
                    porcentaje_descuento: "<?php echo $porcentaje_descuento;?>",
                    valor_viv:  "<?php echo $valor_viv;?>",
                    monto_vivienda:  monto_viv,
                    fecha:  $('#fecha').val(),
                    precio_descuento:  opt,                   
                    forma_pago:  $("#forma_pago").val(),
                    pie:  $("#pie").val(),
                    premio:  $("#premio").val(),                    
                    aplica_pie:   $( "input[name=inlineRadioOptions]:checked" ).val(), // 1 = abono inmobiliario ; 2 = desc al precio
                    estacionamiento: estacionamiento,
                    bodega: bodega
                };
                $.ajax({
                    data: promesa,
                    type: 'POST',
                    url: 'insert_promesa.php',
                    dataType: 'json',
                    success: function (data) {
                        resultado(data);
                    }
                })
           

                return false;
        });

        // $('#formulario').submit(function () {
        //     if ($("#formulario").validate().form() == true){
        //         $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
        //         var dataString = $('#formulario').serialize();
        //         $.ajax({
        //             data: dataString,
        //             type: 'POST',
        //             url: $(this).attr('action'),
        //             dataType: 'json',
        //             success: function (data) {
        //                 resultado(data);
        //             }
        //         })
        //     }
            
        //     return false;
        // });
    }); 


// radio button functions

const visible = (a) => $("#"+a+"").css('visibility','visible');
const hidden = (a) => $("#"+a+"").css('visibility','hidden');

let opc = $( "input[name=inlineRadioOptions]:checked" ).val();
if(opc == "1"){ hidden('alTotal'); }else{  hidden('alAbono'); }

$( "input[name=inlineRadioOptions]" ).on( "click", function() {
    let valor = $('input[name=inlineRadioOptions]:checked').val();
    if(valor=="1"){ visible('alAbono'); hidden('alTotal'); }
    if(valor=="2"){ visible('alTotal'); hidden('alAbono'); }
  
});

</script>
</body>
</html>