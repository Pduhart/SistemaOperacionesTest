<?php
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$consulta = 
        "
        SELECT 
            ven.id_viv,
            ven.id_pro
        FROM 
            venta_venta AS ven
        WHERE 
            ven.id_est_ven > 3
        ORDER BY 
            ven.id_ven
        "; 
    $conexion->consulta($consulta);
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
        	$id_viv = $fila['id_viv'];
        	$id_pro = $fila['id_pro'];
			$consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
			$conexion->consulta_form($consulta,array(2,$id_viv));

			$consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
			$conexion->consulta_form($consulta,array(2,$id_viv));

			$consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
			$conexion->consulta_form($consulta,array(2,$id_viv));


			$consulta_existe_pro = "SELECT id_pro FROM propietario_vivienda_propietario WHERE id_viv = ?";
	        $conexion->consulta_form($consulta_existe_pro,array($id_viv));
	        $existe = $conexion->total();
	        if ($existe==0) {
	        	$consulta = "INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$id_pro,$id_viv));
	        }

	        echo $id_viv."<br>";
		}
	}

?>