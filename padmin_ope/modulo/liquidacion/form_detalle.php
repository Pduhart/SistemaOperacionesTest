<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_liquidacion_panel"])){
    header("Location: ../../panel.php");
}
if(!isset($_POST["valor"])){
	?>
	<script type="text/javascript">
		window.location="../../index.php";
	</script>
	<?php
	exit();
}
include _INCLUDE."class/conexion.php";
?>
<style>
    small{
        font-size: 1rem;
    }
</style>
<div class="modal-dialog modal-lg modal-center">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Detalle Liquidación</h4>
        </div>
        <div class="modal-body">
            <?php
            $conexion = new conexion();
            $id_res = $_POST["valor"];

            $consulta = 
                "
                SELECT 
                    res.monto_total_res,
                    res.monto_total_base_res,
                    res.monto_dia_res,
                    res.monto_dia_base_res,
                    res.fecha_desde_res,
                    res.fecha_hasta_res,
                    res.cantidad_dia_res,
                    res.cantidad_pasajero_res,
                    res.monto_adicional_res,
                    res.monto_comision_res,
                    res.monto_comision_base_res,
                    res.fecha_deposito_res,
                    res.documento_comprobante_res,
                    res.programa_base_res,
                    res.id_tip_res,
                    viv.nombre_viv
                FROM
                    reserva_reserva AS res,
                    vivienda_vivienda AS viv
                WHERE 
                    res.id_viv = viv.id_viv AND
                    res.id_res = ".$id_res."
                ";
            $conexion->consulta($consulta);
            $fila = $conexion->extraer_registro_unico();


            $nombre_viv = $fila["nombre_viv"];
            $programa_base = $fila["programa_base_res"];
            $fecha_desde_res = $fila["fecha_desde_res"];
            $fecha_hasta_res = $fila["fecha_hasta_res"];
            $fecha_deposito_res = $fila["fecha_deposito_res"];
            $cantidad_dia_res = $fila["cantidad_dia_res"];
            $cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
            $monto_adicional_res = $fila["monto_adicional_res"];
            $documento_comprobante_res = utf8_encode($fila["documento_comprobante_res"]);

            $id_tip_res = $fila["id_tip_res"];

            if($programa_base == 1){
                $monto_total_res = $fila["monto_total_base_res"];
                $monto_dia_res = $fila["monto_dia_base_res"];
                $monto_comision_res = $fila["monto_comision_base_res"];
                
            }   
            else{
                $monto_total_res = $fila["monto_total_res"];
                $monto_dia_res = $fila["monto_dia_res"];
                $monto_comision_res = $fila["monto_comision_res"];
            }

            // if($_SESSION["sesion_perfil_panel"] == 3){
                $monto_total_res =   ($monto_dia_res * $cantidad_dia_res); 
            // }
            // else{
            //     $monto_total_res =   ($monto_dia_res * $cantidad_dia_res) + $monto_adicional_res; 
            // }
                     

            $porcentaje_comision = ($monto_dia_res * $cantidad_dia_res) / ($monto_comision_res / 1.19);
            $porcentaje_comision = round($porcentaje_comision);
            ?>
            <table class="table">
                <tr>
                    <td><!-- INGRESO -->
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2" class="bg-light-blue disabled color-palette"><b>Depto</b></td>
                                <td colspan="3"><?php echo utf8_encode($nombre_viv);?> | Res. Id: <b><?php echo $id_res; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="background-color: #ffde01; text-align: center" class="bg-light-blue color-palette">INGRESOS</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"><b>Valor diario</b></td>
                                <?php  
                                if($_SESSION["sesion_perfil_panel"] != 3){
                                    ?>
                                    <td colspan="2"><b>Adicional</b></td>
                                    <?php
                                }
                                ?>
                                
                                <td colspan="2" style="text-align: center;"><b>Noches</b></td>
                                <td><b>Total Ingreso</b></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">$ <?php echo number_format($monto_dia_res, 0, ',', '.');?></td>
                                <?php  
                                if($_SESSION["sesion_perfil_panel"] != 3){
                                    ?>
                                    <td colspan="2" style="text-align: center;">$ <?php echo number_format($monto_adicional_res, 0, ',', '.');?></td>
                                    <?php
                                }
                                ?>
                                
                                <td colspan="2" style="text-align: center;"><?php echo $cantidad_dia_res;?></td>
                                <td style="text-align: center;">$ <?php echo number_format($monto_total_res, 0, ',', '.');?></td>
                            </tr>
                            <?php  
                            if($_SESSION["sesion_perfil_panel"] != 3){
                                ?>
                                <tr>
                                    <td rowspan="2" style="text-align: center; vertical-align: top"><b>Item</b></td>
                                    <td rowspan="2" style=" vertical-align: top"><b>Tipo Cobro</b></td>
                                    <td colspan="2"><b>Cantidad</b></td>
                                    <td colspan="2" rowspan="2" style="text-align: center; vertical-align: top"><b>Valor unit.</b></td>
                                    <td rowspan="2" style="text-align: center; vertical-align: top"><b>Total</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 10px"><b>N</b></td>  
                                    <td style="width: 10px"><b>P</b></td>  
                                </tr>
                                <?php
                                $consulta = 
                                    "
                                    SELECT 
                                        ser_adi_res.valor_ser_adi_res,
                                        tip_cob.nombre_tip_cob,
                                        ser_adi_res.nombre_ser_adi_res,
                                        tip_cob.id_tip_cob
                                    FROM
                                        reserva_servicio_adicional_reserva AS ser_adi_res,
                                        cobro_tipo_cobro AS tip_cob
                                    WHERE 
                                        ser_adi_res.id_tip_cob = tip_cob.id_tip_cob AND
                                        ser_adi_res.id_res = ".$id_res."
                                    ORDER BY 
                                        ser_adi_res.nombre_ser_adi_res 
                                    ASC
                                    ";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        $valor = $fila["valor_ser_adi_res"];
                                        switch ($fila["id_tip_cob"]) {
                                            case 1:
                                                $cantidad_servicio_dia = '';
                                                $cantidad_servicio_persona = '';
                                                $valor_total = $valor;
                                                break;
                                            case 2:
                                                $cantidad_servicio_dia = $cantidad_dia_res;
                                                $cantidad_servicio_persona = '';
                                                $valor_total = $valor * $cantidad_dia_res;
                                                break;
                                            case 3:
                                                $cantidad_servicio_dia = '';
                                                $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                $valor_total = $valor * $cantidad_pasajero_res;
                                                break;
                                            case 4:
                                                $cantidad_servicio_dia = $cantidad_dia_res;
                                                $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                $valor_total = ($valor * $cantidad_pasajero_res) * $cantidad_dia_res;
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo utf8_encode($fila['nombre_ser_adi_res']);?></td>
                                            <td style="text-align: center;"><?php echo utf8_encode($fila['nombre_tip_cob']);?></td>
                                            <td style="text-align: center;"><?php echo $cantidad_servicio_dia;?></td>
                                            <td style="text-align: center;"><?php echo $cantidad_servicio_persona;?></td>
                                            <td colspan="2" style="text-align: center;">$ <?php echo number_format($fila['valor_ser_adi_res'], 0, '', '.');?></td>
                                            <td style="text-align: center;">$ <?php echo number_format($valor_total, 0, '', '.');?></td>
                                            
                                        </tr>
                                        <?php
                                        
                                     }
                                }
                            }
                            ?>
                            
                            <tr>
                                <td>Check In</td>
                                <td><?php echo date("d-m-Y",strtotime($fecha_desde_res)); ?></td>
                            </tr>
                            <tr>
                                <td>Check Out</td>
                                <td><?php echo date("d-m-Y",strtotime($fecha_hasta_res)); ?></td>
                            </tr>
                            <tr>
                                <td>Número Pasajeros</td>
                                <td style="text-align: center;"><?php echo $cantidad_pasajero_res;?></td>
                            </tr>
                            <tr>
                                <td>Cant. Noches</td>
                                <td style="text-align: center;"><?php echo $cantidad_dia_res;?></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 1%"></td>
                    <td style="width: 49%">
                        <table class="table table-bordered">
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="background-color: #ffde01; text-align: center;" class="bg-light-blue color-palette">EGRESOS</td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align: top"><b>Item</b></td>
                                <td rowspan="2" style="vertical-align: top"><b>Tipo Cobro</b></td>
                                <td colspan="2"><b>Cantidad</b></td>
                                <td rowspan="2" style="width: 18%;vertical-align: top"><b>Valor unit.</b></td>
                                <td rowspan="2" style="width: 180px;vertical-align: top;text-align: center;"><b>Total</b></td>
                            </tr>
                            <tr>
                                <td style="width: 8%">N</td>  
                                <td style="width: 8%">P</td>  
                            </tr>
                            <tr>
                                <td style="text-align: center;">Com.Administrador<br><small>Incluye IVA</small></td>
                                <td style="text-align: center;">Único</td>
                                <td colspan="2"><?php echo $porcentaje_comision;?>%</td>
                                <td></td>
                                <td style="text-align: center;">$ <?php echo number_format($monto_comision_res, 0, '', '.');?></td>
                            </tr>
                            <?php
                            $consulta = 
                                "
                                SELECT 
                                    int_res.id_ser_int_res,
                                    int_res.nombre_ser_int_res,
                                    int_res.valor_ser_int_res,
                                    tip_cob.id_tip_cob,
                                    tip_cob.nombre_tip_cob
                                FROM
                                    reserva_servicio_interno_reserva AS int_res,
                                    cobro_tipo_cobro AS tip_cob
                                WHERE 
                                    int_res.id_tip_cob = tip_cob.id_tip_cob AND
                                    int_res.id_res = ".$id_res."
                                ORDER BY 
                                    int_res.id_int_ser 
                                ASC
                                ";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    $valor = $fila["valor_ser_int_res"];
                                    
                                    switch ($fila["id_tip_cob"]) {
                                        case 1:
                                            $cantidad_servicio_dia = '';
                                            $cantidad_servicio_persona = '';
                                            $valor_total = $valor;
                                            break;
                                        case 2:
                                            $cantidad_servicio_dia = $cantidad_dia_res;
                                            $cantidad_servicio_persona = '';
                                            $valor_total = $valor * $cantidad_dia_res;
                                            break;
                                        case 3:
                                            $cantidad_servicio_dia = '';
                                            $cantidad_servicio_persona = $cantidad_pasajero_res;
                                            $valor_total = $valor * $cantidad_pasajero_res;
                                            break;
                                        case 4:
                                            $cantidad_servicio_dia = $cantidad_dia_res;
                                            $cantidad_servicio_persona = $cantidad_pasajero_res;
                                            $valor_total = ($valor * $cantidad_pasajero_res) * $cantidad_dia_res;
                                            break;
                                    }
                                    $valor_total_acumulado = $valor_total_acumulado + $valor_total;
                                    
                                    ?>
                                    <tr>
                                        <td><?php echo utf8_encode($fila['nombre_ser_int_res']);?></td>
                                        <td style="text-align: center;"><?php echo utf8_encode($fila['nombre_tip_cob']);?></td>
                                        <td><?php echo $cantidad_servicio_dia;?></td>
                                        <td><?php echo $cantidad_servicio_persona;?></td>
                                        <td style="text-align: center;">$ <?php echo number_format($valor, 0, '', '.');?></td>
                                        <td style="text-align: center;">$ <?php echo number_format($valor_total, 0, '', '.');?></td>
                                    </tr>
                                    <?php
                                 }
                            }
                            $total_reserva = $monto_comision_res + $valor_total_acumulado;
                            ?> 
                            <tr>
                                <td colspan="4"></td>
                                <td><b>Total</b></td>
                                <td>$ <?php echo number_format($total_reserva, 0, '', '.');?>.-</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="background-color: #ffde01; text-align: center;">Arqueo</td>
                            </tr>
                                <?php
                                $deposito_cliente = $monto_total_res - $total_reserva;
                                ?>
                            <tr>
                                <td colspan="3" style="text-align: center;">Ingresos</td>
                                <td colspan="3" style="text-align: center;">$ <?php echo number_format($monto_total_res, 0, ',', '.');?>.-</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">Egresos</td>
                                <td colspan="3" style="text-align: center;">$ <?php echo number_format($total_reserva, 0, '', '.');?>.-</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; background-color: #f9cb4e;"><b>Depósito Cliente</b></td>
                                <td colspan="3" style="text-align: center; background-color: #f9cb4e;">$ <?php echo number_format($deposito_cliente, 0, ',', '.');?>.-</td>
                            </tr> 
                            <tr>
                                <td colspan="3"><b>Fecha Depósito</b></td>
                                <td colspan="3" style="text-align: center;"><b><?php echo date("d-m-Y",strtotime($fecha_deposito_res)); ?></b></td>
                            </tr>              
                        </table>
                    </td>
                </tr>
            </table>
            <?php 
            if ($documento_comprobante_res<>'') {
                ?>
                <a href="../../../archivo/comprobante/documento/<?php echo $id_res; ?>/<?php echo $documento_comprobante_res; ?>" target="_blank" class="btn btn-sm btn-primary">Comprobante depósito</a>
                <?php
            }
             ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>



