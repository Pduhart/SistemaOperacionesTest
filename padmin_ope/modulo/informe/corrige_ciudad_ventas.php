<?php
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$consulta = 
        "
        SELECT 
            id_ven,
            id_ciu
        FROM 
            venta_ciudad
        ORDER BY 
            id_ven_ciu
        "; 
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
        	$id_ven = $fila['id_ven'];
        	$id_ciu = $fila['id_ciu'];


			$consulta = "UPDATE venta_campo_venta SET ciudad_notaria_ven = ? WHERE id_ven = ?";    
			$conexion->consulta_form($consulta,array($id_ciu,$id_ven));


			$consulta_existe_eta = "SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 5 AND id_cam_eta = 67";
	        $conexion->consulta_form($consulta_existe_eta,array($id_ven));
	        $existe = $conexion->total();
	        if ($existe>0) {
	        	$filaeta = $conexion->extraer_registro_unico();
	        	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta_cam_ven = ?";
				$conexion->consulta_form($consulta,array($id_ciu,$id_ven,$filaeta['id_eta_cam_ven']));
	        }

	        $consulta_existe_eta = "SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 27 AND id_cam_eta = 68";
	        $conexion->consulta_form($consulta_existe_eta,array($id_ven));
	        $existe = $conexion->total();
	        if ($existe>0) {
	        	$filaeta = $conexion->extraer_registro_unico();
	        	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta_cam_ven = ?";
				$conexion->consulta_form($consulta,array($id_ciu,$id_ven,$filaeta['id_eta_cam_ven']));
	        }

	        echo $id_ven." ".$id_ciu."<br>";
		}
	}

?>