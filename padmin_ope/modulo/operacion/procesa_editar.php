<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_ven = $_POST["valor"];
$id_etapa = $_POST["id_etapa"];
$id_etapa_venta = $_POST["id_etapa_venta"];

?>
<form id="formulario_edita" method="POST" action="update_peracion.php" role="form">
    <input type="hidden" name="id_ven_editar" id="id_ven_editar" value="<?php echo $id_ven;?>" />
    <input type="hidden" name="id_etapa_editar" id="id_etapa_editar" value="<?php echo $id_etapa;?>" />
    <input type="hidden" name="id_etapa_venta_editar" id="id_etapa_venta_editar" value="<?php echo $id_etapa_venta;?>" />
    <div class="col-sm-12">
        <hr>
    </div>
    <h4>Editar Información de Etapa <i class="fa fa-pencil" aria-hidden="true"></i></h4>
    <!-- edición de fecha inicio -->
    <?php
    $consulta = 
        "
        SELECT 
            fecha_desde_eta_ven 
        FROM 
            venta_etapa_venta
        WHERE   
            id_eta_ven = ".$id_etapa_venta."
            
        ";
    $conexion->consulta($consulta);
    $fila = $conexion->extraer_registro_unico();
    $fecha_desde_eta_ven = $fila["fecha_desde_eta_ven"];

    $fecha_desde = date("d-m-Y",strtotime($fecha_desde_eta_ven));
    ?>
    <div class="form-group">
        <label for="fecha_inicio_editar">Fecha Inicio:</label>
        <input type="text" class="form-control datepicker" id="fecha_inicio_editar" name="fecha_inicio_editar" value="<?php echo $fecha_desde;?>" />
            
    </div>
    <?php
    $consulta = 
        "
        SELECT 
            fecha_hasta_eta_ven 
        FROM 
            venta_etapa_venta
        WHERE   
            id_eta_ven = ".$id_etapa_venta."
            
        ";
    $conexion->consulta($consulta);
    $fila = $conexion->extraer_registro_unico();
    $fecha_hasta_eta_ven = $fila["fecha_hasta_eta_ven"];

    $fecha = date("d-m-Y",strtotime($fecha_hasta_eta_ven));
    ?>
    <div class="form-group">
        <label for="fecha_editar">Fecha Cierre:</label>
        <input type="text" class="form-control datepicker" id="fecha_editar" name="fecha_editar" value="<?php echo $fecha;?>" />
            
    </div>

    <?php
    $consulta = 
        "
        SELECT 
            * 
        FROM 
            venta_etapa_campo_venta
        WHERE   
            id_eta = ".$id_etapa." AND
            id_ven = ".$id_ven." AND
            id_eta_ven = ".$id_etapa_venta."
            
        ";
    $conexion->consulta($consulta);
    $tiene_campos = $conexion->total();
    if($tiene_campos>0) {
    	$fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	            if($fila["id_tip_cam_eta"] == 2){
	                $clase = "numero";
	                $valor = $fila["valor_campo_eta_cam_ven"];
	            }
	            else if($fila["id_tip_cam_eta"] == 3){
	                $clase = "datepicker";
	                $fecha = date("d-m-Y",strtotime($fila["valor_campo_eta_cam_ven"]));
	                $valor = $fecha;
	            }
	            else{
	                $clase = "";
	                $valor = $fila["valor_campo_eta_cam_ven"];
	            }

	            $id_cam_eta_creados .= $fila["id_cam_eta"].",";
	            ?>
	            <div class="form-group">
	                <label for="campo_extra_editar_<?php echo utf8_encode($fila["id_eta_cam_ven"]);?>"><?php echo utf8_encode($fila["nombre_eta_cam_ven"]);?>:</label>
	                <input type="text" name="campo_extra_editar_<?php echo utf8_encode($fila["id_eta_cam_ven"]);?>" class="form-control <?php echo $clase;?> validacion_campo_editar" id="campo_extra_editar_<?php echo utf8_encode($fila["id_eta_cam_ven"]);?>" value="<?php echo $valor;?>"/>
	            </div>
	            <?php
	        }
	    }
	    $id_cam_eta_creados = substr($id_cam_eta_creados, 0, -1);
    } else {
    	$id_cam_eta_creados = "";
    }
    

    // busca campos nuevos de la etapa
    
    // echo $id_cam_eta_creados;

    if($id_cam_eta_creados<>''){
    	$consulta_nuevos = 
        "
        SELECT 
            * 
        FROM 
            etapa_campo_etapa
        WHERE   
            id_eta = ".$id_etapa."
            AND id_cam_eta NOT IN (".$id_cam_eta_creados.")            
        ";
    } else {
    	$consulta_nuevos = 
    	"
        SELECT 
            * 
        FROM 
            etapa_campo_etapa
        WHERE   
            id_eta = ".$id_etapa."       
        ";
    }
    $conexion->consulta($consulta_nuevos);
    $hay_campos = $conexion->total();
    if ($hay_campos>0) {
    	?>
    	<input type="hidden" name="nuevos_campos" id="nuevos_campos" value="1" />
    	<?php

    	$fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $filan) {
	        	if($filan["id_tip_cam_eta"] == 2){
	                $clase = "numero";
	            }
	            else if($filan["id_tip_cam_eta"] == 3){
	                $clase = "datepicker";
	            }
	            else{
	                $clase = "";
	            }

	            ?>
	            <div class="form-group">
	                <label for="campo_extra_<?php echo utf8_encode($filan["id_cam_eta"]);?>"><?php echo utf8_encode($filan["nombre_cam_eta"]);?>:</label>
	                <input type="text" name="campo_extra_<?php echo utf8_encode($filan["id_cam_eta"]);?>" class="form-control <?php echo $clase;?> validacion_campo_editar" id="campo_extra_<?php echo utf8_encode($filan["id_cam_eta"]);?>"/>
	            </div>
	            <?php

	        }
	    }

    }
    ?>
    <div class="col-sm-12" id="contenedor_boton_edita">
        <button type="submit" id="actualiza_etapa" class="btn btn-warning pull-right" style="margin-left: 40px">Editar Información Etapa</button>
    </div>
</form>
<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('.numero').numeric();        

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });


        function resultado_etapa(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.reload();
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Información ya ha sida ingresada", "warning");
                $('#contenedor_boton_edita').html('<button type="submit" id="actualiza_etapa" class="btn btn-warning pull-right" style="margin-left: 40px">Editar Información Etapa</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton_edita').html('<button type="submit" id="actualiza_etapa" class="btn btn-warning pull-right" style="margin-left: 40px">Editar Información Etapa</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
            // }
        }

        $('#formulario_edita').validate({ // initialize the plugin
            submitHandler: function (form) { // for demo
                return false; // for demo
            }
        });
        
        $('.validacion_campo_editar').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required:  "Ingrese valor"
                }
            });
        });

        $('#formulario_edita').submit(function () {
            if ($("#formulario_edita").validate().form() == true){
                $('#contenedor_boton_edita').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario_edita').serialize();
                $.ajax({
                    data: dataString,
                    type: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    success: function (data) {
                        resultado_etapa(data);
                    }
                })
            }
            
            return false;
        });
    });
</script>