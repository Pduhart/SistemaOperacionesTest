<?php


function get_saldos_pagar_fecha($id_ven,$fecha_analisis,$conexion){

	// $id = $id_ven;

	$total_pagado_uf_pie = 0;
	$total_pagado_pesos_pie = 0;

	$total_pagado_uf_saldo = 0;
	$total_pagado_pesos_saldo = 0;

	$total_por_pagar_uf_pie = 0;
	$total_por_pagar_uf_saldo = 0;


	$consulta = "
	    SELECT
	        viv.nombre_viv,
	        ven.monto_vivienda_ven,
	        ven.monto_ven,
	        ven.descuento_ven,
	        ven.monto_estacionamiento_ven,
			ven.monto_bodega_ven,
	        ven.id_ven,
	        ven.fecha_escritura_ven,
	        pro.nombre_pro,
	        pro.apellido_paterno_pro,
	        pro.apellido_materno_pro,
	        ven_liq.fecha_liq_ven,
	        ven_liq.monto_liq_uf_ven,
	        ven.pie_cancelado_ven,
	        ven.monto_reserva_ven,
	        ven_liq.monto_liq_pesos_ven,
	        ven.monto_credito_real_ven,
	        ven.monto_credito_ven,
	        ven.id_for_pag
	    FROM
	        venta_venta AS ven
	    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	    LEFT JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
	    LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
	    WHERE
	        ven.id_ven = ".$id_ven."
	    ";
	$conexion->consulta($consulta);
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
	    foreach ($fila_consulta as $fila) {
	    	$monto_liq_uf_ven = 0;
	        $monto_ven = $fila["monto_ven"];
	        $id_for_pag = $fila["id_for_pag"];
	        $descuento_ven = $fila["descuento_ven"];
	        $pie_cancelado_ven = $fila["pie_cancelado_ven"];
	        $monto_reserva_ven = $fila["monto_reserva_ven"];
	        $monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
		    $monto_liq_pesos_ven = $fila["monto_liq_pesos_ven"];
		    // Crédito
	        if ($fila["monto_credito_real_ven"]<>0) {
				$credito_hipo = $fila["monto_credito_real_ven"];
			} else {
				$credito_hipo = $fila["monto_credito_ven"];
			}

			$consulta = 
	            "
	            SELECT 
	                pag.id_pag,
	                cat_pag.nombre_cat_pag,
	                -- ban.nombre_ban,
	                for_pag.nombre_for_pag,
	                pag.fecha_pag,
	                pag.fecha_real_pag,
	                pag.numero_documento_pag,
	                pag.monto_pag,
	                est_pag.nombre_est_pag,
	                pag.id_est_pag,
	                pag.id_ven,
	                ven.fecha_ven,
	                ven.pie_cobrar_ven,
	                pag.id_for_pag
	            FROM
	                pago_pago AS pag 
	                INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
	                INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
	                -- INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
	                INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
	                INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
	            WHERE 
	                pag.id_ven = ? AND
	                (pag.id_cat_pag = 1 OR pag.id_cat_pag = 2)
	            ";
	        $conexion->consulta_form($consulta,array($id_ven));
	        $fila_consulta = $conexion->extraer_registro();
	        if(is_array($fila_consulta)){
	            foreach ($fila_consulta as $fila_pag) {
					$valor_uf_efectivo = 0;
					// $pie_pagado_efectivo = 0;
					$pie_pagado_porcobrar = 0;
	            	
	                if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null || $fila_pag["fecha_real_pag"] > $fecha_analisis) { //abonos no cancelados aún
	                    $fecha_real_mostrar = "";

	                    $consultauf = 
						"
						    SELECT
						        valor_uf
						    FROM
						        uf_uf
						    WHERE
						        fecha_uf = '".date("Y-m-d",strtotime($fila_pag["fecha_ven"]))."'
						    ";
						$conexion->consulta($consultauf);
						$cantidaduf = $conexion->total();
						if($cantidaduf > 0){
	            			$filauf = $conexion->extraer_registro_unico();
							$valor_uf = $filauf["valor_uf"];
							if ($fila_pag["id_for_pag"]==6) { // si es pago contra escritura UF
								$monto_pag = $fila_pag["monto_pag"] * $valor_uf;
								$abono_uf = $fila_pag["monto_pag"];
								// $abono_uf = 0;
								$monto_pag = 0;
							} else {
								$monto_pag = $fila_pag["monto_pag"];
								$abono_uf = $fila_pag["monto_pag"] / $valor_uf;
								$abono_uf = 0;
							}
							
						} else {
							$valor_uf = 0;
						}

						$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;

	                }
	                else{
	                    $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
	                    
	                    $consultauf = 
						"
						    SELECT
						        valor_uf
						    FROM
						        uf_uf
						    WHERE
						        fecha_uf = ?
						    ";
						$conexion->consulta_form($consultauf,array($fila_pag["fecha_real_pag"]));
						$cantidad_uf = $conexion->total();
						if($cantidad_uf > 0){
							$filauf = $conexion->extraer_registro_unico();
							$valor_uf_efectivo = $filauf['valor_uf'];
							if ($fila_pag["id_for_pag"]==6) { // si es pago contra escritura UF
								$monto_pag = $fila_pag["monto_pag"] * $valor_uf;
								$abono_uf = $fila_pag["monto_pag"] * $valor_uf_efectivo;
								// para que no sume
							} else {
								$monto_pag = $fila_pag["monto_pag"];
								$abono_uf = $fila_pag["monto_pag"] / $valor_uf_efectivo;
							}
						} else {
							$valor_uf_efectivo = 0;
						} 


						$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;          
	                }
	                $total_abono = $total_abono + $monto_pag;
					$total_uf = $total_uf + $abono_uf;
	               
	            }
	        }

	        if($monto_liq_uf_ven==0){
	    		$pie_cancelado = $pie_cancelado_ven + $monto_reserva_ven;

	        	$total = $pie_cancelado + $fila_pag["pie_cobrar_ven"] + $credito_hipo;

				$saldo_pie = $total - ($credito_hipo + $pie_pagado_porcobrar + $pie_pagado_efectivo);

				$monto_por_recibir = $monto_por_recibir + round($saldo_pie,2);
	    	}

	    	if($id_for_pag==1){

	    		return [$monto_por_recibir];

	    	}

	    }
	}
}
?>