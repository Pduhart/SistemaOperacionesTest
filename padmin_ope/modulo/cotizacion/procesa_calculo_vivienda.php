<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion(); 

$id_cot = $_POST["id"]; //id cotizacion
$id_vivienda = $_POST["id_vivienda"]; // id_vivienda
$id_condominio = $_POST["id_condominio"]; // id_condominio
$valor_viv = $_POST["valor_viv"]; // valor original de la vivienda
$fecha = $_POST["fecha"]; // fecha para ver valor uf
$forma_pago = $_POST["forma_pago"]; // id de forma de pago
$premio = $_POST["premio"]; // premio si es que aplica
$pie = $_POST["pie"]; // pie de la venta

$tipo_descuento = $_POST['tipo_descuento'];
$descuento_al_precio = $_POST['descuento_al_precio'];
$abonoInmobiliario = $_POST["abonoInmobiliario"];  // valor del abono inmobiliario
$monto_pie_sin_reserva = 0; //??? (innecesario)
/*
$aplica_pie = 1 es abono inmobiliario, osea descuento al pie
$aplica_pie = 2 es descuento al precio total

*/
?>
<div class="row mt-2 proceso">
	<div class="col-md-12">
		<hr>
	</div>
<?php
if (empty($valor_viv) || empty($fecha) ||  empty($forma_pago) || empty($pie)) {
    ?>
    <div class="col-sm-12" style="margin-top: 20px;">
        <span class="label label-danger" style="font-size: 16px;">* Falta Información por Completar</span>
        <?php
        if (empty($valor_viv)) echo '<h6>- Precio Departamento</h6>';              
        if (empty($fecha)) echo '<h6>- Fecha</h6>';                   
        if (empty($forma_pago)) echo '<h6>- Forma de Pago</h6>';           
        if (empty($pie)) echo '<h6>- PIE (%)</h6>';                        
        ?>
    </div>
    
    <?php
    exit();
}

$monto_estacionamiento = 0;
$monto_bodega = 0;
$total_descuento = 0;
$monto_reserva = 0;
$monto_descuento = 0;
$cantidad_estacionamiento = 0;
$cantidad_bodega = 0;


// valor2_par = 12 = Monto Reserva
$conexion->consulta_form("SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?",array(12,$id_condominio));
$fila = $conexion->extraer_registro_unico();
$monto_reserva = utf8_encode($fila['valor_par']);


//  obtencion de valor uf por la fecha entregada
$fecha_uf = date("Y-m-d",strtotime($fecha));
$conexion->consulta_form("SELECT valor_uf FROM uf_uf WHERE fecha_uf = ?",array($fecha_uf));
$cantidad_uf = $conexion->total(); // el metodo total da un numero de registros que arrojo la consulta
    if($cantidad_uf == 0){
        ?>
        <div class="col-sm-12" style="margin-top: 20px;">
            <span class="label label-info" style="font-size: 16px;">* No existe Uf cargada para la fecha seleccionada. Debe cargar UF para ver el resumen de promesa.</span>
        </div>
        <?php
        exit();
    }
$fila = $conexion->extraer_registro_unico();
$valor_uf = utf8_encode($fila['valor_uf']);




// ******* SUMA DE VALORES EN ESTACIONAMIENTO Y BODEGAS ADICIONALES

if( isset($_POST["estacionamiento"])){
	$cantidad_estacionamiento = count($_POST["estacionamiento"]);
}
if( isset($_POST["bodega"])){
	$cantidad_bodega = count($_POST["bodega"]);
}
if($cantidad_estacionamiento > 0){
    $conexion->consulta("SELECT IFNULL(SUM(valor_esta),0) AS suma FROM estacionamiento_estacionamiento WHERE id_esta IN (".implode(',',$_POST["estacionamiento"]).") ");
    $fila = $conexion->extraer_registro_unico();
    $monto_estacionamiento = $fila["suma"];
}
if($cantidad_bodega > 0){   
    $conexion->consulta("SELECT IFNULL(SUM(valor_bod),0) AS suma FROM bodega_bodega WHERE id_bod IN (".implode(',',$_POST["bodega"]).") ");
    $fila = $conexion->extraer_registro_unico();
    $monto_bodega = $fila["suma"];
}


// ********** FORMA DE PAGO

$conexion->consulta_form("SELECT nombre_for_pag FROM pago_forma_pago WHERE id_for_pag = ? ",array($forma_pago));
$fila = $conexion->extraer_registro_unico();
$forma_pago = utf8_encode($fila['nombre_for_pag']);


$monto_vivienda_total = $valor_viv + $monto_bodega + $monto_estacionamiento;
$porc_pie = ($pie * 100) / $monto_vivienda_total; // valor del pie convertido en porcentaje
?>
<div class="col-md-4">
	<?php  if ($tipo_descuento == 1) { ?>
		
	<div class="info"><b>Precio Depto:</b> <?php echo number_format($valor_viv, 2, ',', '.'); ?></div>

	<?php } else if($tipo_descuento == 2) {  ?>
				
	<div class="info"><b>Precio Depto:</b> <?php echo number_format(($valor_viv - $descuento_al_precio), 2, ',', '.'); ?></div>	
	<?php } ?>	
	<div class="info"><b>Fecha Promesa:</b> <?php echo date("d/m/Y",strtotime($fecha)); ?></div>
	<div class="info"><b>Valor UF:</b> <?php echo number_format($valor_uf, 2, ',', '.'); ?></div>
	<div class="info"><b>% Pie:</b> <?php echo round($porc_pie); ?></div>
<?php

if(!empty($premio)){
    $consulta = "SELECT nombre_pre FROM premio_premio WHERE id_pre = ? ";
    $conexion->consulta_form($consulta,array($premio));
    $fila = $conexion->extraer_registro_unico();
    ?>
    <div class="info">Premio: <?php echo utf8_encode($fila['nombre_pre']);?></div>
	<?php    
}

?>
</div>
<div class="col-md-4">

<?php

if($cantidad_estacionamiento > 0){
	?>
	<h5 class="mb-0">Estacionamientos Adicionales</h5>

	<?php
    // echo "Estacionamientos Adicionales <br>";
    $consulta ="SELECT nombre_esta, valor_esta FROM estacionamiento_estacionamiento WHERE id_esta IN (".implode(',',$_POST["estacionamiento"]).") ";
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
            $valor_esta = $fila["valor_esta"];
            ?>
			<div class="info"><?php echo utf8_encode($fila['nombre_esta']);?> - <?php echo number_format($valor_esta, 2, ',', '.');?> UF</div>
            <?php           
        }
    }
}
if($cantidad_bodega > 0){
	?>
	<h5 class="mb-0">Bodegas Adicionales</h5>
	<?php
    // echo "Bodegas Adicionales <br>";
    $consulta = "SELECT nombre_bod, valor_bod FROM bodega_bodega WHERE id_bod IN (".implode(',',$_POST["bodega"]).") ";
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
            $valor_bod = $fila["valor_bod"];
            ?>
			<div class="info"><?php echo utf8_encode($fila['nombre_bod']);?> - <?php echo number_format($valor_bod, 2, ',', '.');?> UF</div>
            <?php            
        }
    }
}

?>

<!-- /////////////////////////////////////////////////    DESCUENTOS Y PIE       /////////////////////////////////////////////////////////////////////////////////////// -->


<h5>Descuentos Y Pié</h5>


<?php  if($tipo_descuento == 1){ ?>

		<div class="info"><b>Abono Inmobiliaria:</b> <?php echo number_format($abonoInmobiliario, 2, ',', '.');?> UF</div>
             
        <?php   } else if($tipo_descuento == 2){ ?>

		<div class="info"><b>Monto Depto con Descuentos:</b> <?php echo number_format(($valor_viv - $descuento_al_precio), 2, ',', '.');?> UF</div>

        <?php       
    }

if($cantidad_estacionamiento > 0){?>
	<div class="info"><b>Monto Total Est. Adicionales:</b> <?php echo number_format($monto_estacionamiento, 2, ',', '.');?> UF</div>
<?php }if($cantidad_bodega > 0){?>
	<div class="info"><b>Monto Total Bodegas Adicionales:</b> <?php echo number_format($monto_bodega, 2, ',', '.');?> UF</div>
<?php }?>
<div class="info"><b>Monto Reserva:</b> <?php echo number_format($monto_reserva, 2, ',', '.');?> UF</div>
<?php if($tipo_descuento == 1){ //descuento de abono inmobiliario  

    // pie = valor vivienda * (%pie / 100) 
    $monto_pie = $monto_vivienda_total * ($porc_pie / 100);
    // $monto_pie_sin_reserva = $monto_pie - $monto_reserva;
	
    $monto_pie_con_descuento = $monto_pie - $abonoInmobiliario;
	// $monto_pie_con_descuento_sin_reserva = $monto_pie_con_descuento - $monto_reserva;
	$monto_pie_con_descuento_sin_reserva = $pie - $abonoInmobiliario;
    ?>   
	<div class="info"><b>Monto Pie Con Descuento:</b> <?php echo number_format($monto_pie_con_descuento_sin_reserva, 2, ',', '.');?> UF</div>
	<?php
} else { //el pie no tiene descuentos
	$monto_pie = $monto_vivienda_total * ($porc_pie / 100);
	$monto_pie_sin_reserva = $monto_pie - $monto_reserva;
	?>
	<div class="info"><b>Monto Pie (Sin Reserva):</b> <?php echo number_format($monto_pie_sin_reserva, 2, ',', '.');?> UF</div>
	<?php
}

if ($cantidad_estacionamiento > 0 || $cantidad_bodega > 0) {
	?>
	<div class="info"><b>Valor Venta Total:</b> <?php echo number_format($monto_vivienda_total, 2, ',', '.');?> UF</div>
	<?php
}
?>
</div>
</div>
<div id="contenedor_boton" class="box-footer">
    <button type="button" class="btn btn-primary pull-right" id="guardar">Pasar a Promesa</button>
</div>