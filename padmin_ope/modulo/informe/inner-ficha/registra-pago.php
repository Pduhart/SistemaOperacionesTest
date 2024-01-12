<?php 
$_SESSION["numero_linea"] = 1;
 ?>
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
<div class="col-sm-12" style="margin-top: 10px;">
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">REGISTRAR PAGOS</h3>
        </div>
        <div class="box-body no-padding">
        	<form id="formulario" role="form" method="post" action="../venta/insert_pago.php">
		        <?php  
		        $consulta = 
		            "
		            SELECT
		                pro.rut_pro,
		                pro.id_pro,
		                pro.nombre_pro,
		                pro.apellido_paterno_pro,
		                pro.apellido_materno_pro,
		                pro.correo_pro,
		                pro.fono_pro
		            FROM
		                venta_venta AS ven
		                INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
		            WHERE
		                id_ven = ?
		                
		            ";
		        $conexion->consulta_form($consulta,array($id_ven));
		        $fila = $conexion->extraer_registro_unico();
		        $rut_pro = utf8_encode($fila['rut_pro']);
		        $id_pro = utf8_encode($fila['id_pro']);
		        $nombre_pro = utf8_encode($fila['nombre_pro']);
		        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
		        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
		        $correo_pro = utf8_encode($fila['correo_pro']);
		        $fono_pro = utf8_encode($fila['fono_pro']);
		        /*$id_con = utf8_encode($fila['id_con']);
		        $nombre_con = utf8_encode($fila['nombre_con']);
		        $id_viv = utf8_encode($fila['id_viv']);
		        $nombre_viv = utf8_encode($fila['nombre_viv']);
		        $id_can_cot = utf8_encode($fila['id_can_cot']);
		        $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
		        
		        $id_tor = utf8_encode($fila['id_tor']);
		        $nombre_tor = utf8_encode($fila['nombre_tor']);
		        $id_mod = utf8_encode($fila['id_mod']);
		        $nombre_mod = utf8_encode($fila['nombre_mod']);
		        $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));*/
		        
		        ?>
		        <input type="hidden" name="id" id="id" value="<?php echo $id_ven;?>"></input>
		        <div class="">
		            <div class="">
		                <div class="col-sm-12">
		                    <h4>Cliente: <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." - Fono:".$fono_pro;?> | VENTA: <?php echo $id_ven;?></h4>
		                </div>
		                
		                <div class="col-sm-9 col-sm-offset-2 linea">
		                    <h4>Pago <?php echo $_SESSION["numero_linea"];?></h4>
		                    <div class="col-sm-12">
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label for="forma_pago<?php echo $_SESSION["numero_linea"];?>">Forma de Pago:</label>
		                                <select data-valor="<?php echo $_SESSION["numero_linea"];?>" class="form-control select2 elemento_select forma_pago" id="forma_pago<?php echo $_SESSION["numero_linea"];?>" name="forma_pago<?php echo $_SESSION["numero_linea"];?>"> 
		                                    <option value="">Seleccione Forma de Pago</option>
		                                    <?php  
		                                    $consulta = "SELECT id_for_pag, nombre_for_pag FROM pago_forma_pago ORDER BY nombre_for_pag";
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
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label for="categoria<?php echo $_SESSION["numero_linea"];?>">Categoría:</label>
		                                <select class="form-control select2 elemento_select" id="categoria<?php echo $_SESSION["numero_linea"];?>" name="categoria<?php echo $_SESSION["numero_linea"];?>"> 
		                                    <option value="">Seleccione Categoría</option>
		                                    <?php  
		                                    $consulta = "SELECT id_cat_pag, nombre_cat_pag FROM pago_categoria_pago ORDER BY nombre_cat_pag";
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
		                        </div>
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label for="banco<?php echo $_SESSION["numero_linea"];?>">Banco:</label>
		                                <select class="form-control select2 elemento_select" id="banco<?php echo $_SESSION["numero_linea"];?>" name="banco<?php echo $_SESSION["numero_linea"];?>"> 
		                                    <option value="">Seleccione Banco</option>
		                                    <?php  
		                                    $consulta = "SELECT id_ban, nombre_ban FROM banco_banco ORDER BY nombre_ban";
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
		                        </div>
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label id="texto_monto<?php echo $_SESSION["numero_linea"];?>" for="monto<?php echo $_SESSION["numero_linea"];?>">Monto:</label>
		                                <input type="text" name="monto<?php echo $_SESSION["numero_linea"];?>" class="form-control numero elemento_input" id="monto<?php echo $_SESSION["numero_linea"];?>"/>
		                            </div>
		                        </div>
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label for="fecha<?php echo $_SESSION["numero_linea"];?>">Fecha:</label>
		                                <input type="text" name="fecha<?php echo $_SESSION["numero_linea"];?>" class="form-control datepicker " id="fecha<?php echo $_SESSION["numero_linea"];?>" value="<?php echo date("d-m-Y");?>"/>
		                            </div>
		                        </div>
		                        <div class="col-sm-6">
		                            <div class="form-group">
		                                <label for="numero_documento<?php echo $_SESSION["numero_linea"];?>">Número Documento:</label>
		                                <input type="text" name="numero_documento<?php echo $_SESSION["numero_linea"];?>" class="form-control elemento_input numero" id="numero_documento<?php echo $_SESSION["numero_linea"];?>"/>
		                            </div>

		                        </div>
		                    </div>
		                    
		                    
		                    <div class="clearfix">
		                    <div class="col-sm-12" id="contenedor_linea<?php echo $_SESSION["numero_linea"];?>"></div>
		                    <div class="col-sm-12" id="contenedor_forma_pago<?php echo $_SESSION["numero_linea"];?>"></div>
		                    <div class="clearfix"></div>
		                    <div >
		                        <input type="button" name="agregar_linea" class="btn" value="+ Agregar Línea" id="agregar_linea" />
		                    </div>

		                </div>
		            </div>
		            <div id="contenedor_boton" class="box-footer">
		                <button type="submit" class="btn btn-primary pull-right">Registrar Pagos</button>
		            </div>
		        </div>
		        <!-- /.box-body -->
		    </form>
        </div>
    </div>
</div>

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
     

        $(document).on( "change",".forma_pago" , function() {
            valor = $(this).val();

            var_linea = $(this).attr("data-valor");

            if(valor == 2 || valor == 6){
            	$('#banco'+var_linea).val('');
                $('#banco'+var_linea).prop('disabled', true);
                $('#numero_documento'+var_linea).val('');
                $('#numero_documento'+var_linea).prop('disabled', true);
                if (valor == 6) {
                	$('#texto_monto'+var_linea).html("Monto UF");
            	}
            }
            else if(valor == 9){
                $('#banco'+var_linea).prop('disabled', false);
                $('#numero_documento'+var_linea).val('');
                $('#numero_documento'+var_linea).prop('disabled', true);
            }
            else{
                $('#banco'+var_linea).prop('disabled', false);
                $('#numero_documento'+var_linea).prop('disabled', false);
                $('#texto_monto'+var_linea).html("Monto"); 
            }
            
        });

        $('#agregar_linea').click(function() {
            var obtener_clase = $(".linea");
            cantidad_div_linea = obtener_clase.length;

            if (cantidad_div_linea > 0) {
                var_numero_documento = $('#numero_documento'+cantidad_div_linea).val();
                var_fecha = $('#fecha'+cantidad_div_linea).val();
                var_monto = $('#monto'+cantidad_div_linea).val();
                var_forma_pago = $('#forma_pago'+cantidad_div_linea).val();
                var_categoria = $('#categoria'+cantidad_div_linea).val();
                var_banco = $('#banco'+cantidad_div_linea).val();
            }
           

            cantidad_div_linea++;
            var $contenido = $(this).before("<div class=\"linea\" id=\"linea_contenedor_"+(cantidad_div_linea) + "\"> </div>");
            

            


            $.ajax({
                type: 'POST',
                url: ("../venta/procesa_agregar_linea.php"),
                data:"numero_documento="+var_numero_documento+"&fecha="+var_fecha+"&monto="+var_monto+"&forma_pago="+var_forma_pago+"&categoria="+var_categoria+"&banco="+var_banco,
                success: function(data) {
                    $('#linea_contenedor_'+cantidad_div_linea).html(data);
                }
            })
        });

        $('.numero').numeric();

        $('#formulario').validate({ // initialize the plugin
            submitHandler: function (form) { // for demo
                return false; // for demo
            }
        });
        
        $('.elemento_select').each(function() {
            $(this).rules('add', {
                required: true,
                number: true,
                messages: {
                    required:  "Seleccione opción"
                }
            });
        });
         $('.elemento_input').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required:  "Ingrese valor"
                }
            });
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        // $(document).on( "change","#condominio" , function() {
        //     valor = $(this).val();
        //     if(valor != ""){
        //         $.ajax({
        //             type: 'POST',
        //             url: ("procesa_condominio.php"),
        //             data:"valor="+valor,
        //             success: function(data) {
        //                  $('#torre').html(data);
        //             }
        //         })
        //     }
        // });
        // $(document).on( "change","#torre" , function() {
        //     valor = $(this).val();
        //     if(valor != ""){
        //         $.ajax({
        //             type: 'POST',
        //             url: ("procesa_torre.php"),
        //             data:"valor="+valor,
        //             success: function(data) {
        //                  $('#vivienda').html(data);
        //             }
        //         })
        //     }
        // });
        

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    icon: "success"
                    }).then(()=> window.history.back());
            }
            if (data.envio == 2) {
                swal("Atención!", "Pago ya ha sido ingresado", "warning");
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