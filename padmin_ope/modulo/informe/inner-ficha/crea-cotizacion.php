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
            <h3 class="box-title">NUEVA COTIZACIÓN</h3>
        </div>
        <div class="box-body no-padding">
        	<form id="formulario" method="POST" action="../cotizacion/insert.php" role="form">
        		<input type="hidden" name="propietario" id="propietario" value="<?php echo $id_pro;?>"></input>
	        	<input type="hidden" name="no-crea-pro" id="no-crea-pro" value="0"></input>
                <div class="box-body">
                    <div class="row">
                    	<?php                    
                    	$consulta_pro = 
				            "
				            SELECT
				                pro.rut_pro,
				                pro.id_pro,
				                pro.nombre_pro,
				                pro.nombre2_pro,
				                pro.apellido_paterno_pro,
				                pro.apellido_materno_pro,
				                pro.rut_pro,
				                pro.correo_pro,
				                pro.fono_pro
				            FROM
				                propietario_propietario AS pro
				            WHERE
				                pro.id_pro = ?
				            ";
				        $conexion->consulta_form($consulta_pro,array($id_pro));
				        $fila_pro = $conexion->extraer_registro_unico();
				        $rut_pro = utf8_encode($fila_pro['rut_pro']);
				        $id_pro = utf8_encode($fila_pro['id_pro']);
				        $nombre_pro = utf8_encode($fila_pro['nombre_pro']);
				        $nombre2_pro = utf8_encode($fila_pro['nombre2_pro']);
				        $apellido_paterno_pro = utf8_encode($fila_pro['apellido_paterno_pro']);
				        $apellido_materno_pro = utf8_encode($fila_pro['apellido_materno_pro']);
				        $correo_pro = utf8_encode($fila_pro['correo_pro']);
				        $fono_pro = utf8_encode($fila_pro['fono_pro']);
                    	?>

                    	<!-- DATOS CLIENTE <-->
                    	<div class="col-sm-12" id="contenedor_propietario">
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="rut">Rut:</label>
		                            <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_pro;?>" />
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="nombre">Primer Nombre:</label>
		                            <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_pro;?>"/>
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="segundo_nombre">Segundo Nombre:</label>
		                            <input type="text" name="segundo_nombre" class="form-control" id="segundo_nombre" value="<?php echo $nombre2_pro;?>"/>
		                        </div>
		                        
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="apellido_paterno">Apellido Paterno:</label>
		                            <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" value="<?php echo $apellido_paterno_pro;?>"/>
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="apellido_materno">Apellido Materno:</label>
		                            <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo $apellido_materno_pro;?>"/>
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                        <div class="form-group">
		                            <label for="fono">Fono:</label>
		                            <input type="text" name="fono" class="form-control numero" id="fono" value="<?php echo $fono_pro;?>"/>
		                        </div>
		                    </div>
		                    <div class="col-md-3">
		                        <div class="form-group">
		                            <label for="correo">Correo:</label>
		                            <input type="text" name="correo" class="form-control" id="correo" value="<?php echo $correo_pro;?>"/>
		                        </div>
		                        
		                    </div>
		                </div>
                       
                        <div class="col-sm-12">
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="condominio">Condominio:</label>
                                    <select class="form-control select2" id="condominio" name="condominio"> 
                                        <option value="">Seleccione Condominio</option>
                                        <?php  
                                        $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
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
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="torre">Torre:</label>
                                    <select class="form-control select2" id="torre" name="torre">
                                    <option value="">Seleccione Torre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="modelo">Modelo:</label>
                                    <select class="form-control select2" id="modelo" name="modelo"> 
                                        <option value="">Seleccione Modelo</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="vivienda">Departamento:</label>
                                    <select class="form-control select2" id="vivienda" name="vivienda"> 
                                        <option value="">Seleccione Departamento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="canal">Canal:</label>
                                    <select class="form-control" id="canal" name="canal"> 
                                        <option value="">Seleccione Canal</option>
                                        <?php  
                                        $consulta = "SELECT * FROM cotizacion_canal_cotizacion WHERE id_can_cot IN (1,2,3,4,8,11,12,14,15,17) ORDER BY id_can_cot";
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                ?>
                                                <option value="<?php echo $fila['id_can_cot'];?>"><?php echo utf8_encode($fila['nombre_can_cot']);?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="preaprobacion">Pre aprobación de Crédito:</label>
                                    <select class="form-control" id="preaprobacion" name="preaprobacion"> 
                                        <option value="">Seleccione</option>
                                        <?php  
                                        $consulta = "SELECT * FROM cotizacion_preaprobacion_cotizacion ORDER BY id_pre_cot";
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
                            <div class="col-sm-3">
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
                            </div>
                            <div class="col-sm-2">
		                        <div class="form-group">
		                            <label for="fecha_cot">Fecha:</label>
		                            <input type="text" name="fecha_cot" class="form-control datepicker elemento" id="fecha_cot" autocomplete="off"/>
		                        </div>
		                    </div>
		                    <div class="col-sm-3">
                            	<div class="form-group">
			                        <label for="renta">Renta Aproximada:</label>
			                        <select class="form-control" id="renta" name="renta"> 
			                            <option value="">Seleccione Renta</option>
			                            <?php  
			                            $consulta = "SELECT * FROM cotizacion_renta_cotizacion ORDER BY id_ren_cot ASC";
			                            $conexion->consulta($consulta);
			                            $fila_consulta = $conexion->extraer_registro();
			                            if(is_array($fila_consulta)){
			                                foreach ($fila_consulta as $fila) {
			                                    ?>
			                                    <option value="<?php echo $fila['id_ren_cot'];?>"><?php echo utf8_encode($fila['nombre_ren_cot']);?></option>
			                                    <?php
			                                }
			                            }
			                            ?>
			                        </select>
			                    </div>
                            </div>
                            <div class="col-sm-2">
                            	
	                            <div class="form-group">
		                            <label for="porcentaje_credito">Porcentaje Crédito:</label>
		                            <input type="text" name="porcentaje_credito" class="form-control" id="porcentaje_credito"/>
		                        </div>
                            </div>
                            <div class="col-sm-6" id="contenedor_info_vivienda"></div>

                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer" id="contenedor_boton">
                    <button type="submit" class="btn btn-primary pull-right">Registrar</button>
                </div>
            </form>
		</div>
	</div>
</div>

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();


        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });


        $("#formulario").validate({
            rules: {
                // rut: { 
                //     required: true
                // },
                // nombre: { 
                //     required: true,
                //     minlength: 3
                // },
                // apellido_paterno: { 
                //     required: true,
                //     minlength: 3
                // },
                // correo:{
                //     required: true,
                //     minlength: 4,
                //     email: true
                // },
                // region: {
                //     required: true
                // },
                // comuna: {
                //     required: true
                // },
                // nacionalidad: {
                //     required: true
                // },
                // sexo: {
                //     required: true
                // },
                // fono:{
                //     required: true,
                //     minlength: 4
                // },
                condominio: { 
                    required: true
                },
                porcentaje_credito: {
                	required: true
                },
                 torre: { 
                    required: true
                },
                // direccion_trabajo: { 
                //     required: true
                // },
                departamento: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                vivienda: { 
                    required: true
                },
                canal: { 
                    required: true
                },
                fecha_cot: { 
                    required: true
                },
                preaprobacion: { 
                    required: true
                },
                interes: { 
                    required: true
                },
                renta: { 
                    required: true
                }
            },
            messages: {
                // rut: {
                //     required: "Ingrese Rut"
                // },
                // nombre: {
                //     required: "Ingrese Nombre",
                //     minlength: "Mínimo 3 caracteres"
                // },
                // apellido_paterno: {
                //     required: "Ingrese Apellido Paterno",
                //     minlength: "Mínimo 3 caracteres"
                // },
                // nacionalidad: {
                //     required: "Seleccione nacionalidad"
                // },
                // region: {
                //     required: "Seleccione Región"
                // },
                // comuna: {
                //     required: "Seleccione comuna"
                // },
                // direccion_trabajo: {
                //     required: "Ingrese Lugar de trabajo"
                // },
                // sexo: {
                //     required: "Seleccione sexo"
                // },
                // correo: {
                //     required: "Ingrese correo",
                //     minlength: "Mínimo 4 caracteres",
                //     email: "Ingrese correo válido"
                // },
                // fono: {
                //     required: "Ingrese fono",
                //     minlength: "Mínimo 4 caracteres"
                // },
                condominio: {
                    required: "Seleccione condominio"
                },
                porcentaje_credito: {
                    required: "Ingrese Porcentaje"
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
                vivienda: {
                    required: "Seleccione Depto"
                },
                canal: {
                    required: "Ingrese canal"
                },
                fecha_cot: {
                    required: "Ingrese fecha"
                },
                preaprobacion: {
                    required: "Ingrese preaprobación de crédito"
                },
                interes: {
                    required: "Ingrese Nivel de Interés"
                },
                renta: {
                    required: "Ingrese Renta"
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
                    url: ("../cotizacion/procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
            }
            else{
                $('#torre').html("Seleccione Torre");
            }
        });
        $(document).on( "change","#torre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/procesa_torre.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#modelo').html(data);
                    }
                })
            }
            else{
                $('#vivienda').html("Seleccione Departamento");
            }
        });
        $(document).on( "change","#modelo" , function() {
            valor = $(this).val();
            var_torre = $('#torre').val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/procesa_modelo.php"),
                    data:"valor="+valor+"&torre="+var_torre,
                    success: function(data) {
                         $('#vivienda').html(data);
                    }
                })
            }
            else{
                $('#vivienda').html("Seleccione Departamento");
            }
        });

        $(document).on( "change","#vivienda" , function() {
            var_valor = $(this).val();
            var_modelo = $('#modelo').val();
            if(var_valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/procesa_vivienda.php"),
                    data:"valor="+var_valor,
                    success: function(data) {
                        $('#contenedor_info_vivienda').html(data);
                    }
                })
                
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/procesa_vivienda_modelo.php"),
                    data:"valor="+var_valor,
                    success: function(data) {
                        $('#modelo').html(data);
                    }
                })
                
            }
            else{
                $('#contenedor_info_vivienda').html("");
            }

            
        });

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    icon: "success"                                    
                }).then(()=> window.history.back());
            }
            if (data.envio == 2) {
                swal("Atención!", "Cotización ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 4) {
                swal("Atención!", "Cliente esta asignado a otro vendedor", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
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