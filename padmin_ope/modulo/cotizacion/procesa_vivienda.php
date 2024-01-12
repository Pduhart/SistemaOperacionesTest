<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_viv = $_POST["valor"];

?>
<div class="row">
	<div class="col-sm-6">
	    <?php
	    $consulta = 
	        "
	        SELECT
	            nombre_bod
	        FROM
	            bodega_bodega
	        WHERE
	            id_viv = ?
	        ";
	    $conexion->consulta_form($consulta,array($id_viv));
	    $fila_consulta = $conexion->extraer_registro();
	    $cantidad = $conexion->total();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	            $nombre_bod = utf8_encode($fila["nombre_bod"]);
	            ?>
	                <i class="fa fa-cubes"></i> Bod. <span><?php echo $nombre_bod;?></span>
	            <?php
	        }
	    }
	    ?>
	</div>
	<div class="col-sm-6">
	    <?php
	    $consulta = 
	        "
	        SELECT
	            nombre_esta
	        FROM
	            estacionamiento_estacionamiento
	        WHERE
	            id_viv = ?
	        ";
	    $conexion->consulta_form($consulta,array($id_viv));
	    $fila_consulta = $conexion->extraer_registro();
	    $cantidad = $conexion->total();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	            $nombre_esta = utf8_encode($fila["nombre_esta"]);
	            ?>
	                <i class="fa fa-car"></i> Est. <span><?php echo $nombre_esta;?></span>
	            <?php
	        }
	    }
	    ?>
	</div>
</div>