<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_con = $_POST["valor"];
$contador_par = 1;
$consulta = "SELECT * FROM parametro_parametro WHERE id_con = ".$id_con." ORDER BY id_par ASC";
$conexion->consulta($consulta);
$cantidad = $conexion->total();
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        if($fila['valor2_par'] >= 1 && $fila['valor2_par'] <> 17 && $fila['valor2_par'] <> 26 && $fila['valor2_par'] <> 27 && $fila['valor2_par'] <> 28){
            $valor_par = $fila['valor_par'];
            $clase = "";
            $clase_numero = "numero";
        }
        else if($fila['valor2_par'] <> 27 && $fila['valor2_par'] <> 28){
        	if ($fila['valor_par']<>'' && $fila['valor_par']<>null) {
        		$valor_par = date("d-m-Y",strtotime($fila['valor_par']));
        	} else {
        		$valor_par = "";
        	}
            $clase = "datepicker";
            $clase_numero = "";
        } else {
        	$valor_par = $fila['valor_par'];
            $clase = "";
            $clase_numero = "";
        }
        if ($contador_par==1) {
       	?>
		<div class="col-sm-12"><h3>Factor Categoría Vendedor</h3></div>
       	<?php
        }
        if ($contador_par==4) {
       	?>
		<div class="col-sm-12"><h3>Cálculo Precio con Descuento y % para Bono al Precio</h3></div>
       	<?php
        }
        if ($contador_par==6) {
       	?>
		<div class="col-sm-12"><h3>Porcentaje Reparto Comisiones</h3></div>
       	<?php
        }
        if ($contador_par==8) {
       	?>
		<div class="col-sm-12"><h3>Porcentaje de Comisión</h3></div>
       	<?php
        }
        if ($contador_par==11) {
       	?>
		<div class="col-sm-12"><h3>Bono por Escritura para operaciones, UF Reserva Departamentos.</h3></div>
       	<?php
        }
        if ($contador_par==13) {
       	?>
		<div class="col-sm-6"><h3>% Bonos al Precio</h3></div>
		<div class="col-sm-6"><h3>Montos a prorratear, a Recuperar y Fecha Recuperación</h3></div>
       	<?php
        }
        if ($contador_par==17) {
       	?>
		<div class="col-sm-6"></div>
       	<?php
        }
        if ($contador_par==18) {
       	?>
		<div class="col-sm-12"><h3>Valores estacionamiento y bodega</h3></div>
       	<?php
        }
        if ($contador_par==20) {
       	?>
		<div class="col-sm-12"><h3>Factores Jefe y Supervisor</h3></div>
       	<?php
        }
        if ($contador_par==26) {
       	?>
		<div class="col-sm-12"><h3>Información Condominio</h3></div>
       	<?php
        }
        ?>
        <!-- <div class="col-sm-6"><?php //echo $contador_par; ?></div> -->
        <div class="form-group col-sm-6">
            <label for="parametro<?php echo $fila['valor2_par'];?>"><?php echo $fila['nombre_par']; ?>:</label>
            <input type="text" value="<?php echo $valor_par;?>" name="parametro<?php echo $fila['valor2_par'];?>" class="form-control campo_parametro <?php echo $clase." ".$clase_numero;?>" id="parametro<?php echo $fila['valor2_par'];?>">
        </div>
        <?php
        $contador_par++;
    }
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
    });
</script>
