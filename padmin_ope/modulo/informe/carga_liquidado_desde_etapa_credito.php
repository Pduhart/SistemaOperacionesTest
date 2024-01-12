<?php
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();


$id_for_pag = 1;
$id_tor = 4;

$consulta_ventas_contado = 
    "
    SELECT 
        ven.id_ven
    FROM 
        venta_venta as ven,
        vivienda_vivienda as viv
    WHERE EXISTS (
    	SELECT
    		ven_eta.id_ven
    	FROM
    		venta_etapa_venta as ven_eta
    	WHERE
    		ven_eta.id_ven = ven.id_ven
    ) AND
    ven.id_for_pag = ".$id_for_pag." AND
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = ".$id_tor."

    "; 
$conexion->consulta($consulta_ventas_contado);
$cant_ventas = $conexion->total();

$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$id_ven = $fila['id_ven'];
		echo "------>Venta: ".$id_ven."<br>";

		// falta ver hasta qué etapa analizar
		$consulta_etapa_liquidado = "
			SELECT 
				ven_eta_cam.valor_campo_eta_cam_ven,
				ven_eta_cam.id_cam_eta
			FROM 
				venta_etapa_campo_venta as ven_eta_cam
			WHERE 
				ven_eta_cam.id_ven = ".$id_ven." AND
				ven_eta_cam.id_eta = 38 AND
				(ven_eta_cam.id_cam_eta = 51 OR ven_eta_cam.id_cam_eta = 52)
		";
		$conexion->consulta($consulta_etapa_liquidado);
		$tiene_el_campo = $conexion->total();

		if($tiene_el_campo>0){
			$fila_consulta_ac = $conexion->extraer_registro();
			if(is_array($fila_consulta_ac)){
			    foreach ($fila_consulta_ac as $fila_datos) {
			    	$id_cam_eta = $fila_datos['id_cam_eta'];
			    	$valor_campo_eta_cam_ven = $fila_datos['valor_campo_eta_cam_ven'];

			    	$consulta_liquidado_venta = "SELECT id_liq_ven FROM venta_liquidado_venta WHERE id_ven = ".$id_ven."";
					$conexion->consulta($consulta_liquidado_venta);
					$tiene_registro = $conexion->total();

					if($tiene_registro>0) {
						if($id_cam_eta == 51){
							echo "------>en pesos: ".$valor_campo_eta_cam_ven."<br>";

							$consulta_liquidado_venta_pesos = "SELECT monto_liq_pesos_ven FROM venta_liquidado_venta WHERE id_ven = ".$id_ven."";
							$conexion->consulta($consulta_liquidado_venta_pesos);
							$fila_pesos = $conexion->extraer_registro_unico();

							if($fila_pesos['monto_liq_pesos_ven'] <> 0) {

							} else {
								$consulta = "UPDATE venta_liquidado_venta SET monto_liq_pesos_ven = ? WHERE id_ven = ?";
								$conexion->consulta_form($consulta,array($valor_campo_eta_cam_ven,$id_ven));
							}


						} else {
							echo "------>en UF: ".$valor_campo_eta_cam_ven."<br>";

							$consulta_liquidado_venta_uf = "SELECT monto_liq_uf_ven FROM venta_liquidado_venta WHERE id_ven = ".$id_ven."";
							$conexion->consulta($consulta_liquidado_venta_uf);
							$fila_uf = $conexion->extraer_registro_unico();

							if($fila_uf['monto_liq_uf_ven'] <> 0) {

							} else {
								$consulta = "UPDATE venta_liquidado_venta SET monto_liq_uf_ven = ? WHERE id_ven = ?";
								$conexion->consulta_form($consulta,array($valor_campo_eta_cam_ven,$id_ven));
							}

						}
					}

			    }
			}
		}

	}
}


?>