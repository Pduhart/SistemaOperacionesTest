<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$_SESSION["numero_linea"]++;
$numero_documento = $_POST["numero_documento"];
if ($numero_documento<>'') {
	$numero_documento++;
}

$fecha = $_POST["fecha"];
$fecha = date('d-m-Y',strtotime ( '+1 month' , strtotime ( $fecha)));
$monto = $_POST["monto"];
$forma_pago = $_POST["forma_pago"];
$categoria = $_POST["categoria"];
$banco = $_POST["banco"];
?>
<hr class="col-sm-12">
<div class="col-sm-12">
    <div style="float: right;" class="show-tooltip" title="Cerrar Línea" data-original-title="Detalle"><button class="btn btn-icon btn-default bot-accion detalle_departamento" value="<?php echo $_SESSION["numero_linea"];?>" id="cerrar_linea_<?php echo $_SESSION["numero_linea"];?>" type="button"><i class="fa fa-close" aria-hidden="true"></i></button></div>
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
                        	if ($fila['id_for_pag']==$forma_pago) {
                        		$sele = "selected";
                        	} else {
                        		$sele = "";
                        	}
                            ?>
                            <option value="<?php echo $fila['id_for_pag'];?>" <?php echo $sele; ?>><?php echo utf8_encode($fila['nombre_for_pag']);?></option>
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
                        	if ($fila['id_cat_pag']==$categoria) {
                        		$sele = "selected";
                        	} else {
                        		$sele = "";
                        	}
                            ?>
                            <option value="<?php echo $fila['id_cat_pag'];?>" <?php echo $sele; ?>><?php echo utf8_encode($fila['nombre_cat_pag']);?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
            	<?php 
				if ($forma_pago==6 || $forma_pago==2) {
					$disa = "disabled";
				} else {
					$disa = "";
				}
            	?>
                <label for="banco<?php echo $_SESSION["numero_linea"];?>">Banco:</label>
                <select class="form-control select2 elemento_select" id="banco<?php echo $_SESSION["numero_linea"];?>" name="banco<?php echo $_SESSION["numero_linea"];?>" <?php echo $disa;?>> 
                    <option value="">Seleccione Banco</option>
                    <?php  
                    $consulta = "SELECT id_ban, nombre_ban FROM banco_banco ORDER BY nombre_ban";
                    $conexion->consulta($consulta);
                    $fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if ($fila['id_ban']==$banco) {
                        		$sele = "selected";
                        	} else {
                        		$sele = "";
                        	}
                            ?>
                            <option value="<?php echo $fila['id_ban'];?>" <?php echo $sele; ?>><?php echo utf8_encode($fila['nombre_ban']);?></option>
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
                <input type="text" name="monto<?php echo $_SESSION["numero_linea"];?>" class="form-control numero elemento_input" id="monto<?php echo $_SESSION["numero_linea"];?>" value="<?php echo $monto;?>"/>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="fecha<?php echo $_SESSION["numero_linea"];?>">Fecha:</label>
                <input type="text" name="fecha<?php echo $_SESSION["numero_linea"];?>" class="form-control datepicker" id="fecha<?php echo $_SESSION["numero_linea"];?>" value="<?php echo $fecha;?>"/>
            </div>
        </div>
        <!-- <div class="col-sm-6">
            <div class="form-group">
                <label for="fecha_real<?php //echo $_SESSION["numero_linea"];?>">Fecha Real:</label>
                <input type="text" name="fecha_real<?php //echo $_SESSION["numero_linea"];?>" class="form-control datepicker" id="fecha_real<?php //echo $_SESSION["numero_linea"];?>"/>
            </div>
        </div> -->
        
        <div class="col-sm-6">
            <div class="form-group">
            	<?php 
				if ($forma_pago==6 || $forma_pago==2 || $forma_pago==9) {
					$disa = "disabled";
				} else {
					$disa = "";
				}
            	?>
                <label for="numero_documento<?php echo $_SESSION["numero_linea"];?>">Número Documento:</label>
                <input type="text" name="numero_documento<?php echo $_SESSION["numero_linea"];?>" class="form-control elemento_input numero" id="numero_documento<?php echo $_SESSION["numero_linea"];?>" value="<?php echo $numero_documento;?>" <?php echo $disa;?>/>
            </div>
        </div>
        
        <!-- <div class="col-sm-6">
            <div class="form-group">
                <label for="numero_serie<?php// echo $_SESSION["numero_linea"];?>">Número Serie:</label>
                <input type="text" name="numero_serie<?php //echo $_SESSION["numero_linea"];?>" class="form-control " id="numero_serie<?php //echo $_SESSION["numero_linea"];?>"/>
            </div>
        </div> -->
        
    </div>
    <div id="contenedor_linea<?php echo $_SESSION["numero_linea"];?>"></div>
</div>
<!-- <script src="../../assets/vendor/jquery/jquery.js"></script> -->
<script type="text/javascript">
      
  $(document).ready(function () {
		$(document).on( "click","#cerrar_linea_<?php echo $_SESSION["numero_linea"];?>" , function() {
			valor = $(this).val();
			$('#linea_contenedor_'+valor).html('');
			return false;
		});

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

        $('#formulario').validate({ // initialize the plugin
            submitHandler: function (form) { // for demo
                return false; // for demo
            }
        });
        
        $('.elemento_select').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required:  "Seleccione opción"
                }
            });
        });
         $('.elemento_input').each(function() {
            $(this).rules('add', {
                required: true,
                number: true,
                messages: {
                    required:  "Ingrese valor"
                }
            });
        });


	});	

</script>  

