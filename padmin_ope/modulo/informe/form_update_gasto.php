<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$id_ven = $_POST["valor"];


$consulta = 
    "
    SELECT
        ven.id_for_pag,
        viv.nombre_viv
    FROM
        venta_venta as ven,
        vivienda_vivienda as viv
    WHERE 
        ven.id_ven = ? AND
        ven.id_viv = viv.id_viv
    ";
$conexion->consulta_form($consulta,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$id_for_pag = utf8_encode($fila['id_for_pag']);
$nombre_viv = utf8_encode($fila['nombre_viv']);

$consultahay = 
    "
    SELECT
        monto_gas_ven,
        fecha_gas_ven
    FROM
        venta_gastosop_venta
    WHERE 
        id_ven = ?
    ";
$conexion->consulta_form($consultahay,array($id_ven));
$insert = $conexion->total();
if ($insert>0) {
	$fila = $conexion->extraer_registro_unico();
	$fecha_gas_ven = utf8_encode($fila['fecha_gas_ven']);
	$monto_gas_ven = utf8_encode($fila['monto_gas_ven']);
	$fecha_gas = date("d-m-Y",strtotime($fecha_gas_ven));
}

?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"> Valor y Fecha Pago Gastos Operacionales</h4>
        </div>
        

        <div class="modal-body">

            <label><b>Venta:</b> </label> <span><?php echo $id_ven;?></span><br>
            <label><b>Unidad:</b> </label> <span><?php echo $nombre_viv;?></span><br>

            <form id="formulario" method="POST" action="insert_gasto_venta.php" role="form">
                <input type="hidden" id="id" value="<?php echo $id_ven;?>" name="id" />
                <input type="hidden" id="insert" value="<?php echo $insert;?>" name="insert" />
                
                <div class="modal-body">
                        <div class="row margin-bottom-40">
                            <div class="col-sm-12">
                            	<div class="form-group">
                                    <label for="monto_gas">Monto Fondo gastos OOPP:</label>
                                    <input type="text" name="monto_gas" value="<?php echo $monto_gas_ven; ?>" class="form-control elemento numero" id="monto_gas"/>
                                </div>
                                <div class="form-group">
                                    <label for="fecha_gas">Fecha Pago gastos OOPP:</label>
                                    <input type="text" name="fecha_gas" value="<?php echo $fecha_gas; ?>" class="form-control datepicker elemento" id="fecha_gas"/>
                                </div>
                                
                            </div>
                            
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
                    
                    <button type="submit" id="guarda_fecha" class="btn btn-primary">Registrar GOP</button>
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
        	</form>
        </div>
    </div>
</div>

<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
      
  $(document).ready(function () {
		$('.numero').numeric();

  		$('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

        function resultado(data) {
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
                    location.href = "operacion_listado.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Registro ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<input type="button" name="boton" class="btn2" value="Guardar" id="bt"/>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<input type="button" name="boton" class="btn2" value="Guardar" id="bt"/>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            //  }
        }
        
        $('#formulario').submit(function () {
            
            if ($("#formulario").validate().form() == true){
                //$('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
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

        $("#formulario").validate({
            rules: {
                monto_gas: { 
                    required: true
                }
            },
            messages: { 
                monto_gas: {
                    required: "Ingrese Monto"
                }
            }
        });

  });  
     
</script>   
