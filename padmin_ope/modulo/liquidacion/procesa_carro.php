<?php 
include ("../../class/carro.php");
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
?>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<table  class="table table-bordered">
<?php
// -------- CHECK SELECCIONADOS ---------
$id_res = $_POST["reserva"];
$programa_base = $_POST["programa_base"];
$consulta = 
    "
    SELECT 
        res.monto_total_res,
        res.monto_total_base_res,
        res.monto_dia_res,
        res.monto_dia_base_res,
        res.cantidad_dia_res,
        res.cantidad_pasajero_res,
        res.monto_adicional_res,
        res.monto_comision_res,
        res.monto_comision_base_res,
        res.id_tip_res
    FROM
        reserva_reserva AS res,
        vivienda_vivienda AS viv
    WHERE 
        res.id_viv = viv.id_viv AND
        res.id_res = ".$id_res."
    ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$cantidad_dia_res = $fila["cantidad_dia_res"];
$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
$monto_adicional_res = $fila["monto_adicional_res"];

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

$monto_total_res = $monto_dia_res * $cantidad_dia_res;
$porcentaje_comision = ($monto_dia_res * $cantidad_dia_res) / $monto_comision_res;
$porcentaje_comision = round($porcentaje_comision);


$id_emp = $_POST["valor"];
$cantidad_emp = $_POST["cantidad"];
$id_emp = explode(',',$id_emp);
$cantidad = $cantidad_emp - 1;
$contador = 0;
$valor_total_acumulado = 0;
while($contador <= $cantidad ){
    $consulta = 
        "
        SELECT 
            int_ser.id_int_ser,
            int_ser.nombre_int_ser,
            int_ser.valor_alto_int_ser,
            int_ser.valor_medio_int_ser,
            int_ser.valor_bajo_int_ser,
            tip_cob.id_tip_cob,
            tip_cob.nombre_tip_cob
        FROM
            servicio_interno_servicio AS int_ser,
            cobro_tipo_cobro AS tip_cob
        WHERE 
            int_ser.id_tip_cob = tip_cob.id_tip_cob AND
            int_ser.id_est_int_ser = 1 AND
            int_ser.id_int_ser = ".$id_emp[$contador]."
        ORDER BY 
            int_ser.nombre_int_ser 
        ASC
        ";
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
            if($fila["id_int_ser"] == 1){
                $clase = "checked disabled";
            }
            else{
                $clase = "";
            }
            if($id_tip_res == 1){
                //Alta
                $valor = $fila["valor_alto_int_ser"];
            }
            else if($id_tip_res == 2){
                //Media
                $valor = $fila["valor_medio_int_ser"];
            }
            else{
                //Baja
                $valor = $fila["valor_bajo_int_ser"];
            }
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
         }
    }  
    $contador++;
}




  
$numero_alumno_sesion = 0;
if(!isset($_SESSION["numero_item"])){
    $numero_item = 1;
}
else{
    $numero_item = $_SESSION["numero_item"];
}

$cantidad_item = 0;
for ($i=0;$i<$numero_item;$i++){
    if($_SESSION["ocarrito_item"]->array_id_ite[$i]!=0){
        $cantidad_item = 1;
    }
}
if($cantidad_item == 1){

    ?>
    <tbody>
    <tr>
        <td colspan="2" class="text-center bg-gray">Egresos Agregados</td>
    </tr>
    
        
    <?php
}
$valor_acumulado = 0;
for ($i=0;$i<$numero_item;$i++){
	if($_SESSION["ocarrito_item"]->array_id_ite[$i]!=0){
        $numero_item_sesion = 1;
        $detalle = $_SESSION["ocarrito_item"]->array_detalle_ite[$i];
        
        $valor = $_SESSION["ocarrito_item"]->array_valor_ite[$i];
        $valor_acumulado = $valor_acumulado + $valor;
        ?>
        <tr>
            <td><button style="font-size: 10px;padding: 3px 5px;" type="button" class="btn btn-danger input-search-close icon fa fa-times eliminar_carro" value="<?php echo $_SESSION["ocarrito_item"]->array_id_ite[$i];?>" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Eliminar Item"></button> <?php echo utf8_encode($detalle);?></td>
            <td style="width: 120px">$ <?php echo number_format($valor, 0, '', '.');?></td>
        </tr>
        <?php             
    }

}
$total_reserva = $valor_acumulado + $valor_total_acumulado + $monto_comision_res;
?>
<tr>
    <td><b>Total</b></td>
    <td style="width: 120px">$ <?php echo number_format($total_reserva, 0, '', '.');?>.-</td>
</tr>
</tbody>
</table>
<table class="table table-bordered">
    <thead>
        <tr class="bg-aqua color-palette">
            <th colspan="2">Arqueo</th>
        </tr>
    </thead>
    <?php
    $deposito_cliente = $monto_total_res - $total_reserva;
    ?>
    <tbody> 
        <tr>
            <td>Ingresos</td>
            <td>$ <?php echo number_format($monto_total_res, 0, ',', '.');?>.-</td>
        </tr>
        <tr>
            <td>Egresos</td>
            <td>$ <?php echo number_format($total_reserva, 0, '', '.');?>.-</td>
        </tr>
        <tr>
            <td><b>Depósito Cliente</b></td>
            <td>$ <?php echo number_format($deposito_cliente, 0, ',', '.');?>.-</td>
        </tr>
        
    </tbody>
</table>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
    });
</script>