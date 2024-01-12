<?php


function get_pagos_contados_fecha($id_ven,$fecha_analisis,$conexion){

	$id = $id_ven;

	$total_pagado_uf_pie = 0;
	$total_pagado_pesos_pie = 0;

	$total_pagado_uf_saldo = 0;
	$total_pagado_pesos_saldo = 0;

	$total_por_pagar_uf_pie = 0;
	$total_por_pagar_uf_saldo = 0;
  
  $consulta = 
        "
        SELECT 
            pag.id_pag,
            cat_pag.nombre_cat_pag,
            cat_pag.id_cat_pag,
            for_pag.nombre_for_pag,
            pag.fecha_pag,
            pag.fecha_real_pag,
            pag.numero_documento_pag,
            pag.monto_pag,
            est_pag.nombre_est_pag,
            pag.id_est_pag,
            pag.id_ven,
            ven.fecha_ven,
            pag.id_for_pag
        FROM
            pago_pago AS pag 
            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
            -- INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
        WHERE 
            pag.id_ven = ?
        ";
    $conexion->consulta_form($consulta,array($id));
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
			$valor_uf_efectivo = 0;
			$pie_agado_efectivo = 0;
			$pie_pagado_porcobrar = 0;
        	
            if ($fila["fecha_real_pag"]=="0000-00-00" || $fila["fecha_real_pag"]==null || $fila["fecha_real_pag"] > $fecha_analisis) { //abonos no cancelados aún

                $consulta = 
				"
				    SELECT
				        valor_uf
				    FROM
				        uf_uf
				    WHERE
				        fecha_uf = '".date("Y-m-d",strtotime($fila["fecha_ven"]))."'
				    ";
				$conexion->consulta($consulta);
				$cantidaduf = $conexion->total();
				if($cantidaduf > 0){
        			$filauf = $conexion->extraer_registro_unico();
					$valor_uf = $filauf["valor_uf"];
					if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
						$monto_pag = $fila["monto_pag"] * $valor_uf;
						$abono_uf = $fila["monto_pag"];
						// $abono_uf = 0;
						$monto_pag = 0;
					} else {
						$monto_pag = $fila["monto_pag"];
						$abono_uf = $fila["monto_pag"] / $valor_uf;
						$abono_uf = 0;
					}
					
				} else {
					$valor_uf = 0;
				}

				// acumula pagos registrado no pagados aún

				if($fila["id_cat_pag"] <> 3) {
					$total_por_pagar_uf_pie = $total_por_pagar_uf_pie + $abono_uf;
				} else {
					$total_por_pagar_uf_saldo = $total_por_pagar_uf_saldo + $abono_uf;
				}

            }
            else{
                
                $consulta = 
				"
				    SELECT
				        valor_uf
				    FROM
				        uf_uf
				    WHERE
				        fecha_uf = ?
				    ";
				$conexion->consulta_form($consulta,array($fila["fecha_real_pag"]));
				$cantidad_uf = $conexion->total();
				if($cantidad_uf > 0){
					$filauf = $conexion->extraer_registro_unico();
					$valor_uf_efectivo = $filauf['valor_uf'];
					if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
						$monto_pag = $fila["monto_pag"] * $valor_uf;
						$abono_uf = $fila["monto_pag"] * $valor_uf_efectivo;
						// para que no sume
					} else {
						$monto_pag = $fila["monto_pag"];
						$abono_uf = $fila["monto_pag"] / $valor_uf_efectivo;
					}
				} else {
					$valor_uf_efectivo = 0;
				} 

				if($fila["id_cat_pag"] <> 3) {
					$total_pagado_uf_pie = $total_pagado_uf_pie + $abono_uf;
					$total_pagado_pesos_pie = $total_pagado_pesos_pie + $monto_pag;   
				} else {
					$total_pagado_uf_saldo = $total_pagado_uf_saldo + $abono_uf;
					$total_pagado_pesos_saldo = $total_pagado_pesos_saldo + $monto_pag;   
				}

            }
        }
    }


  return [round($total_por_pagar_uf_pie,2),round($total_por_pagar_uf_saldo,2), round($total_pagado_uf_pie,2), $total_pagado_pesos_pie, round($total_pagado_uf_saldo,2), $total_pagado_pesos_saldo];
 }
?>