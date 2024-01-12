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
            <h3 class="box-title">EDITAR VENTA</h3>
        </div>
        <div class="box-body no-padding">
        	<form id="formulario" role="form" method="post" action="../venta/update.php">
		        <?php  
		        $id = $id_ven;
		        $consulta = 
		            "
		            SELECT
		                ven.pie_cancelado_ven,
		                ven.monto_reserva_ven,
		                ven.descuento_ven,
		                ven.monto_estacionamiento_ven,
		                ven.monto_bodega_ven,
		                ven.monto_credito_ven,
		                ven.id_pre,
		                ven.numero_compra_ven,
		                for_pag.id_for_pag,
		                for_pag.nombre_for_pag,
		                ban.id_ban,
		                ban.nombre_ban,
		                tip_pag.id_tip_pag,
		                tip_pag.nombre_tip_pag,
		                ven.monto_ven,
		                ven.id_pie_abo_ven,
		                ven.monto_vivienda_ven,
		                ven.id_viv,
		                ven.id_pro,
		                ven.monto_credito_real_ven,
		                pro.nombre_pro,
		                pro.apellido_paterno_pro,
		                pro.apellido_materno_pro,
		                pro.rut_pro,
		                ven.fecha_ven
		            FROM
		                venta_venta AS ven 
		                INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
		                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
		                LEFT JOIN pago_tipo_pago AS tip_pag ON tip_pag.id_tip_pag = ven.id_tip_pag
		            WHERE
		                ven.id_ven = ?
		            ";
		        $conexion->consulta_form($consulta,array($id));
		        $fila = $conexion->extraer_registro_unico();

		        $id_pro = $fila['id_pro'];
		        $pie_cancelado_ven = utf8_encode($fila['pie_cancelado_ven']);

		        $nombre_pro = utf8_encode($fila['nombre_pro']);
		        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
		        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
		        $rut_pro = utf8_encode($fila['rut_pro']);
		        $fecha_ven = utf8_encode($fila['fecha_ven']);
		        $fecha_ven_formato = date("d-m-Y",strtotime($fecha_ven));
		        
		        $monto_reserva_ven = utf8_encode($fila['monto_reserva_ven']);
		        $descuento_ven = utf8_encode($fila['descuento_ven']);
		        $monto_estacionamiento_ven = utf8_encode($fila['monto_estacionamiento_ven']);
		        $monto_bodega_ven = utf8_encode($fila['monto_bodega_ven']);
		        $monto_credito_ven = utf8_encode($fila['monto_credito_ven']);
		        $monto_credito_real_ven = utf8_encode($fila['monto_credito_real_ven']);  //por si entra por 2da vez
		        if ($monto_credito_real_ven > 0) {
		        	$monto_credito_ven1 = number_format($monto_credito_real_ven, 2, '.', '');
		        } else {
		        	$monto_credito_ven1 = number_format($monto_credito_ven, 2, '.', '');
		        }
		        if ($monto_credito_ven > 0) {
		            $monto_credito_mostrar = number_format($monto_credito_ven, 2, ',', '.');
		        }
		        else{
		            $monto_credito_mostrar = 0;
		        }

		        $id_pre = utf8_encode($fila['id_pre']);
		        $numero_compra_ven = utf8_encode($fila['numero_compra_ven']);
		        $id_for_pag = utf8_encode($fila['id_for_pag']);
		        $nombre_for_pag = utf8_encode($fila['nombre_for_pag']);
		        $id_ban = utf8_encode($fila['id_ban']);
		        $nombre_ban = utf8_encode($fila['nombre_ban']);
		        $id_tip_pag = utf8_encode($fila['id_tip_pag']);
		        $nombre_tip_pag = utf8_encode($fila['nombre_tip_pag']);
		        $monto_ven = utf8_encode($fila['monto_ven']);
		        $monto_vivienda_ven = utf8_encode($fila['monto_vivienda_ven']);
		        $id_pie_abo_ven = utf8_encode($fila['id_pie_abo_ven']);
		        $id_viv = utf8_encode($fila['id_viv']);
		        $pie_total = $monto_ven - $monto_credito_real_ven;
		        $consulta = 
		            "
		            SELECT
		                nombre_viv
		            FROM
		                vivienda_vivienda
		            WHERE
		                id_viv = ?
		            ";
		        $conexion->consulta_form($consulta,array($id_viv));
		        $fila = $conexion->extraer_registro_unico();
		        $nombre_viv = utf8_encode($fila['nombre_viv']);

		        if ($id_pie_abo_ven==1) {
		        	$aplica = "al pie";
		        	$cheq1 = 'checked="checked"';
		        	$cheq2 = "";
		        } else {
		        	$aplica = "al valor final";
		        	$cheq1 = "";
		        	$cheq2 = 'checked="checked"';
		        }

		        if ($id_pre > 0) {
		            $consulta = 
		                "
		                SELECT
		                    nombre_pre
		                FROM
		                    premio_premio
		                WHERE
		                    id_pre = ?
		                ";
		            $conexion->consulta_form($consulta,array($id_pre));
		            $fila = $conexion->extraer_registro_unico();
		            $nombre_pre = utf8_encode($fila['nombre_pre']);
		        }
		        
		        ?>
		        <input type="hidden" name="id" id="id" value="<?php echo $id;?>"></input>
		        <input type="hidden" name="monto_credito_original" id="monto_credito_original" value="<?php echo $monto_credito_ven;?>"></input>
		        <div class="box-body">
		            <div class="row">
		                <div class="col-sm-12">
		                    <table class="table table-bordered">
		                    	<tr class="bg-light-blue color-palette">
		                    		<td colspan="3"><b>Departamento:</b> <?php echo $nombre_viv;?></td>
		                        </tr>
		                    	<tr class="bg-light-blue color-palette">
		                    		<?php 
									if ($id_pie_abo_ven==1) {
		                             ?>
		                            <td><b>Valor Lista:</b> <?php echo number_format($monto_vivienda_ven, 2, ',', '.');?> UF .-</td>
		                        	<?php } else { ?>
									<td>--</td>
									<?php } ?>
		                            <td><b>Valor Final Venta:</b> <?php echo number_format($monto_ven, 2, ',', '.');?> UF .-</td>
		                            <td><b>Descuento Aplica:</b> <?php echo $aplica;?></td>
		                        </tr>
		                        <tr class="bg-light-blue color-palette">
		                            <td><b>Pie Cancelado:</b> <?php echo number_format($pie_cancelado_ven, 2, ',', '.');?> UF .-</td>
		                            <td><b>Monto Reserva:</b> <?php echo number_format($monto_reserva_ven, 2, ',', '.');?> UF .-</td>
		                            <?php 
									if ($id_pie_abo_ven==1) {
		                             ?>
		                            <td><b>Descuentos:</b> <?php echo number_format($descuento_ven, 2, ',', '.');?> UF .-</td>
		                        	<?php } else { ?>
									<td><b>Descuento Registrado en la Venta:</b> <?php echo number_format($descuento_ven, 2, ',', '.');?> UF .-</td>
									<?php } ?>
		                        </tr>
		                        <tr class="bg-light-blue color-palette">
		                            <td><b>Monto Estacionamiento:</b> <?php echo number_format($monto_estacionamiento_ven, 2, ',', '.');?> UF .-</td>
		                            <td><b>Monto Bodega:</b> <?php echo number_format($monto_bodega_ven, 2, ',', '.');?> UF .-</td>
		                            <?php
									if ($id_for_pag==1) {
										 ?>
										<td><b>Monto Crédito Inicial:</b> <?php echo $monto_credito_mostrar;?> UF .-</td>
										<?php
									} else {
										 ?>
										<td><b>Monto Saldo Inicial:</b> <?php echo $monto_credito_mostrar;?> UF .-</td>
										<?php
									}
									?>
		                        </tr>
		                        <tr class="bg-light-blue color-palette">
		                        	<?php 
									if ($monto_credito_real_ven<>'') {
										?>
										<td></td>
										<td><b>Pie Total Venta:</b> <?php echo number_format($pie_total, 2, '.', '');?> UF .-</td>
										<?php
										if ($id_for_pag==1) {
											 ?>
											<td><b>Monto Crédito Real:</b> <?php echo number_format($monto_credito_real_ven, 2, '.', '');?> UF .-</td>
											<?php
										} else {
											 ?>
											<td><b>Saldo a Pagar real:</b> <?php echo number_format($monto_credito_real_ven, 2, '.', '');?> UF .-</td>
											<?php
										}
									}
		                        	 ?>
		                        </tr>
		                    </table>
		                </div>
		                <div class="col-sm-2">
		                    <div class="form-group">
		                        <label for="premio">Premio:</label>
		                        <select class="form-control" id="premio" name="premio">
		                            <?php
		                            if ($id_pre > 0) {
		                                ?>
		                                <option value="<?php echo $id_pre;?>"><?php echo $nombre_pre;?></option>
		                                <option value="">Sin Premio</option>
		                                <?php
		                            }
		                            else{
		                                ?>
		                                <option value="">Seleccione Premio</option>
		                                <?php
		                            }
		                            ?>
		                            
		                            <?php  
		                            $consulta = "SELECT * FROM premio_premio WHERE id_est_pre = 1 AND id_pre <> '".$id_pre."' ORDER BY nombre_pre";
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
		                    <div class="form-group">
		                        <label for="forma_pago">Forma de Pago:</label>
		                        <select class="form-control" id="forma_pago" name="forma_pago"> 
		                            <option value="<?php echo $id_for_pag;?>"><?php echo $nombre_for_pag;?></option>
		                            <?php  
		                            $consulta = "SELECT * FROM pago_forma_pago WHERE id_for_pag <> '".$id_for_pag."' AND (id_for_pag = 1 OR id_for_pag = 2) ORDER BY nombre_for_pag";
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
		                <div class="col-sm-3">
		                    <div class="form-group row">
		                        <h4 class="col-sm-12">Descuento Aplica PIE:</h4>
		                        <div class="col-sm-4" >
		                            <input id="aplica_pie_1" type="radio" name="aplica_pie" class="aplica_pie elemento" <?php echo $cheq1;?> value="1">
		                            <label for="aplica_pie_1">SI</label>
		                        </div>
		                        <div class="col-sm-4" >
		                            <input id="aplica_pie_2" type="radio" name="aplica_pie" class="aplica_pie elemento" <?php echo $cheq2;?> value="2">
		                            <label for="aplica_pie_2">NO</label>
		                        </div>
		                    </div>
		                    <input type="hidden" name="total_vivienda" class="form-control numero" id="total_vivienda" value="1"/>
		                    <div id="contenedor_forma_pago">
		                        <?php
		                        if ($id_for_pag == 1) {
		                            ?>
		                            <div class="form-group">
		                                <label for="banco">Banco:</label>
		                                <select class="form-control" id="banco" name="banco"> 
		                                    <?php
		                                    if ($id_ban > 0) {
		                                        ?>
		                                        <option value="<?php echo $id_ban;?>"><?php echo $nombre_ban;?></option>
		                                        <?php
		                                    }
		                                    else{
		                                        ?>
		                                        <option value="">Seleccione Banco</option>
		                                        <?php
		                                    }
		                                    ?>
		                                    <?php  
		                                    $consulta = "SELECT * FROM banco_banco ORDER BY nombre_ban";
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
		                            <?php
		                        }
		                        else if ($id_for_pag == 2){
		                            ?>
		                            <div class="form-group">
		                                <label for="tipo_pago">Tipo de Pago:</label>
		                                <select class="form-control" id="tipo_pago" name="tipo_pago"> 
		                                    <?php
		                                    if ($id_tip_pag > 0) {
		                                        ?>
		                                        <option value="<?php echo $id_tip_pag;?>"><?php echo $nombre_tip_pag;?></option>
		                                        <?php
		                                    }
		                                    else{
		                                        ?>
		                                        <option value="">Seleccione Tipo de Pago</option>
		                                        <?php
		                                    }
		                                    ?>
		                                    <?php  
		                                    $consulta = "SELECT * FROM pago_tipo_pago ORDER BY nombre_tip_pag";
		                                    $conexion->consulta($consulta);
		                                    $fila_consulta = $conexion->extraer_registro();
		                                    if(is_array($fila_consulta)){
		                                        foreach ($fila_consulta as $fila) {
		                                            ?>
		                                            <option value="<?php echo $fila['id_tip_pag'];?>"><?php echo utf8_encode($fila['nombre_tip_pag']);?></option>
		                                            <?php
		                                        }
		                                    }
		                                    ?>
		                                </select>
		                            </div>
		                            <?php
		                        }
		                        ?>
		                    </div>
		                    <!-- <div class="form-group">
		                        <label for="fecha">Fecha:</label>
		                        <input type="text" name="fecha" class="form-control datepicker" id="fecha" value="<?php //echo $fecha_cot;?>" />
		                    </div> -->
		                </div>
		                <div class="col-sm-2">
	                        <div class="form-group">
	                            <label for="fecha_ven">Fecha Venta:</label>
	                            <input type="text" name="fecha_ven" class="form-control datepicker elemento" id="fecha_ven" value="<?php echo $fecha_ven_formato; ?>" />
	                        </div>
	                    </div>
		                <?php
		                if ($id_for_pag == 1) {
		                    ?>
		                    <div class="col-sm-4" id="contenedor_credito_real">
		                        <div class="form-group">
		                            <label for="credito_real">Monto Crédito Real:</label>
		                            <input type="text" name="credito_real" class="form-control numero" id="credito_real" value="<?php echo $monto_credito_ven1;?>"/>
		                        </div>
		                    </div>
		                    <?php
		                } else {
		                	?>
							<input type="hidden" name="credito_real" id="credito_real" value="<?php echo $monto_credito_ven1;?>"></input>
		                	<?php
		                }
		                ?>
		                <div class="col-sm-4">
		                	<div class="form-group">
		                        <label for="propietario">Cambio Titular Venta:</label>
		                        <select class="form-control select2" id="propietario" name="propietario"> 
		                            <option value="<?php echo $id_pro;?>"><?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." / ".$rut_pro;?></option>
		                            <?php  
		                            $consulta = 
		                                "
		                                SELECT
		                                    pro.id_pro,
		                                    pro.nombre_pro,
		                                    pro.apellido_paterno_pro,
		                                    pro.apellido_materno_pro,
		                                    pro.rut_pro
		                                FROM
		                                    propietario_propietario AS pro
		                                WHERE
		                                    pro.id_est_pro = 1 AND
		                                    pro.id_pro <> ".$id_pro."
		                                ORDER BY
		                                    pro.nombre_pro";
		                            $conexion->consulta($consulta);
		                            $fila_consulta = $conexion->extraer_registro();
		                            if(is_array($fila_consulta)){
		                                foreach ($fila_consulta as $fila) {
		                                    ?>
		                                    <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']." / ".$fila['rut_pro']);?></option>
		                                    <?php
		                                }
		                            }
		                            ?>
		                        </select>
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
                },
                credito_real: { 
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
                },
                credito_real: { 
                    required: "Ingrese monto"
                }
            }
        });

        $(document).on( "change","#forma_pago" , function() {
            valor = $(this).val();
            var_monto_credito_original = $('#monto_credito_original').val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../venta/procesa_forma_pago.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#contenedor_forma_pago').html(data);
                    }
                })
                $.ajax({
                    type: 'POST',
                    url: ("../venta/procesa_credito_real.php"),
                    data:"valor="+valor+"&monto_credito_original="+var_monto_credito_original,
                    success: function(data) {
                         $('#contenedor_credito_real').html(data);
                    }
                })
            }
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
                    text: "Información actualizada con éxito!",
                    icon: "success"
                    
                }).then(()=>window.history.back());
            }
            if (data.envio == 2) {
                swal("Atención!", "Venta ya ha sido ingresado", "warning");
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