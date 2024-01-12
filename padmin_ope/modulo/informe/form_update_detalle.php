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

$consultafechas = 
    "
    SELECT
        fecha_pago_fondo_expotacion,
        fecha_pago_cliente_fondo_expotacion,
        monto_pago_fpm_cliente_ven,
        monto_pago_fpm_adm_ven
    FROM
        venta_campo_venta
    WHERE 
        id_ven = ?
    ";
$conexion->consulta_form($consultafechas,array($id_ven));
$fila = $conexion->extraer_registro_unico();
$fecha_pago_fondo_expotacion = $fila['fecha_pago_fondo_expotacion'];
$fecha_pago_cliente_fondo_expotacion = $fila['fecha_pago_cliente_fondo_expotacion'];
$monto_pago_fpm_cliente_ven = $fila['monto_pago_fpm_cliente_ven'];
$monto_pago_fpm_adm_ven = $fila['monto_pago_fpm_adm_ven'];

if ($fecha_pago_fondo_expotacion<>'') {
	$fecha_pago_fondo_expotacion = date("d-m-Y",strtotime($fecha_pago_fondo_expotacion));
}
if ($fecha_pago_cliente_fondo_expotacion<>'') {
	$fecha_pago_cliente_fondo_expotacion = date("d-m-Y",strtotime($fecha_pago_cliente_fondo_expotacion));
}
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"> Fecha Real Pago Administrador Condominio</h4>
        </div>
        

        <div class="modal-body">

            <label><b>Venta:</b> </label> <span><?php echo $id_ven;?></span><br>
            <label><b>Unidad:</b> </label> <span><?php echo $nombre_viv;?></span><br>

            <form id="formulario" method="POST" action="insert_fecha_real.php" role="form">
                <input type="hidden" id="id" value="<?php echo $id_ven;?>" name="id" />
                <input type="hidden" id="id_for_pag" value="<?php echo $id_for_pag;?>" name="id_for_pag" />
                
                <div class="modal-body">
                        <div class="row margin-bottom-40">
                        	<div class="col-sm-12">
                                <div class="form-group">
                                    <label for="fecha_cli">Fecha Pago Cliente:</label>
                                    <input type="text" name="fecha_cli" value="<?php echo $fecha_pago_cliente_fondo_expotacion; ?>" class="form-control datepicker elemento" id="fecha_cli"/>
                                </div>
                                
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="fecha_adm">Fecha Real Pago Adm. Cond.:</label>
                                    <input type="text" value="<?php echo $fecha_pago_fondo_expotacion; ?>" name="fecha_adm" class="form-control datepicker elemento" id="fecha_adm"/>
                                </div>
                                
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="monto_cli">Monto Pago Cliente:</label>
                                    <input type="number" value="<?php echo $monto_pago_fpm_cliente_ven; ?>" name="monto_cli" class="form-control elemento" id="monto_cli"/>
                                </div>
                                
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="monto_adm">Monto Pago a Adm:</label>
                                    <input type="number" value="<?php echo $monto_pago_fpm_adm_ven; ?>" name="monto_adm" class="form-control elemento" id="monto_adm"/>
                                </div>
                                
                            </div>
                            
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
                    
                    <button type="submit" id="guarda_fecha" class="btn btn-primary">Registrar Fechas y Montos</button>
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
<script type="text/javascript">
      
  $(document).ready(function () {
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
                    location.href = "venta_fondo_listado.php";
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

        // $("#formulario").validate({
        //     rules: {
        //         fecha_adm: { 
        //             required: true
        //         }
        //     },
        //     messages: { 
        //         fecha_adm: {
        //             required: "Ingrese Fecha"
        //         }
        //     }
        // });

  });  
     
</script>   
