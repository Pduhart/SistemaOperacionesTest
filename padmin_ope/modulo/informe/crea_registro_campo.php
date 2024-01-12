<?php 
require "../../config.php";

include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$consulta_ventas_inciadas_oopp = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven
  WHERE
    ven.id_est_ven > 3 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven)";

$conexion->consulta($consulta_ventas_inciadas_oopp);
$filac = $conexion->extraer_registro();
if(is_array($filac)){
    foreach ($filac as $fila) {
        $id_ven = $fila["id_ven"];

        $consulta_hay = 
		  "
		  SELECT 
		    id_ven
		  FROM
		    venta_campo_venta
		  WHERE
		  	id_ven = ".$id_ven."
		  ";
		$conexion->consulta($consulta_hay);
		$existe = $conexion->total();
		if ($existe==0) {
			// campos extras fuera de etapa
        	$consulta = "INSERT INTO venta_campo_venta (id_ven) VALUES (?)";
        	$conexion->consulta_form($consulta,array($id_ven));
		}
    }
}
?>