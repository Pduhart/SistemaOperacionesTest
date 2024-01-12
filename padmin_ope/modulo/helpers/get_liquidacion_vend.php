<?php

function get_uf_disistimiento($id_ven,$conexion){

	// echo $id_ven;

	$consulta = 
	    "
	    SELECT
	        id_cie
	    FROM
	        cierre_venta_cierre
	    WHERE
	        id_ven = ? AND id_est_ven <> 3 
	    ";
	$conexion->consulta_form($consulta,array($id_ven));
	$fila = $conexion->extraer_registro_unico();
	$id_cie = $fila["id_cie"];

	$consulta = 
	    "
	    SELECT
	        fecha_hasta_cie
	    FROM
	        cierre_cierre
	    WHERE
	        id_cie = ?
	    ";
	$conexion->consulta_form($consulta,array($id_cie));
	$fila = $conexion->extraer_registro_unico();
	$fecha_hasta_cie = $fila["fecha_hasta_cie"];

	$consulta = 
	    "
	    SELECT
	        valor_uf
	    FROM
	        uf_uf
	    WHERE
	        fecha_uf = ?
	    ";
	$conexion->consulta_form($consulta,array($fecha_hasta_cie));
	$fila = $conexion->extraer_registro_unico();
	$valor_uf_desistimiento = utf8_encode($fila['valor_uf']);

	return $valor_uf_desistimiento;

}

function get_liquidacion_vend($id_vend,$id_cierre,$conexion){
	$id_cierre = $id_cierre;
	$id_vendedor = $id_vend;


	$consulta = 
	    "
	    SELECT
	        nombre_vend,
	        apellido_paterno_vend,
	        apellido_materno_vend,
	        id_usu
	    FROM
	        vendedor_vendedor
	    WHERE
	        id_vend = ?
	    ";
	$conexion->consulta_form($consulta,array($id_vendedor));
	$fila = $conexion->extraer_registro_unico();
	$id_usu = $fila["id_usu"];

	$total_liquidacion_bonos = 0;
	$total_liquidacion_comisiones = 0;
	$total_liquidacion_a_pagar = 0;

	$consulta = 
	    "
	    SELECT
	        cie.fecha_desde_cie,
	        cie.fecha_hasta_cie,
	        cie.anio_cie,
	        uf.valor_uf,
	        con.id_con,
	        con.nombre_con,
	        con.alias_con,
	        mes.id_mes,
	        mes.nombre_mes
	    FROM
	        cierre_cierre AS cie
	        INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
	        INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
	        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
	        INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
	        INNER JOIN uf_uf AS uf ON uf.fecha_uf = cie.fecha_hasta_cie
	    WHERE
	        cie.id_cie = ? AND
	        ven.id_vend = ?
	    GROUP BY
	        cie.id_cie,
	        cie.anio_cie,
	        con.nombre_con,
	        uf.valor_uf,
	        mes.id_mes,
	        con.alias_con,
	        cie.fecha_desde_cie,
	        cie.fecha_hasta_cie,
	        con.id_con
	    ";
	$conexion->consulta_form($consulta,array($id_cierre,$id_vendedor));
	$cantidad_condominio = $conexion->total();
	// echo $cantidad_condominio."---------";
	$fila_consulta_cierre = $conexion->extraer_registro();
	$contador_pagina = 1;

	$acumula_cantidad_promesas = 0;
	$acumula_cantidad_escrituras = 0;

	if(is_array($fila_consulta_cierre)){
	    foreach ($fila_consulta_cierre as $fila_cierre) {
	        $fecha_desde_cie = $fila_cierre["fecha_desde_cie"];
	        $fecha_hasta_cie = $fila_cierre["fecha_hasta_cie"];
	        $anio_cie = $fila_cierre["anio_cie"];
	        $valor_uf = $fila_cierre["valor_uf"];
	        $nombre_con = $fila_cierre["nombre_con"];
	        $id_mes = $fila_cierre["id_mes"];
	        $nombre_mes = $fila_cierre["nombre_mes"];
	        $alias_con = $fila_cierre["alias_con"];
	        $id_con = $fila_cierre["id_con"];

	        $UF_LIQUIDACION = $valor_uf;

	        $consulta = 
    		    "
    		    SELECT
    		        valor_par,
    		        valor2_par
    		    FROM
    		        parametro_parametro
    		    WHERE
    		        id_con = ? AND
    		        valor2_par IN (6,7,8)
    		    ";
    		$conexion->consulta_form($consulta,array($id_con));
    		$fila_consulta = $conexion->extraer_registro();

            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                	if($fila["valor2_par"] == 6){
                		$porcentaje_promesa = $fila["valor_par"];
                	}
                	else if($fila["valor2_par"] == 7){
                		$porcentaje_escritura = $fila["valor_par"];
                	}
                	else{
                		$comision = $fila["valor_par"];
                	}
                }
            }

            $total_desistimiento_acumulado = 0;
            $monto_uf_acumulado_promesa = 0;
            $monto_uf_acumulado_escritura = 0;
            $monto_acumulado_promesa = 0;
            $monto_acumulado_escritura = 0;
            $monto_uf_acumulado_escritura_desi = 0;
            $monto_uf_acumulado_promesa_desi = 0;

            $monto_acumulado_bonos = 0;

            // desistimientos
            $consulta = 
                "
                SELECT
                    vend.id_vend,
                    pro.nombre_pro,
                    pro.nombre2_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    ven.id_ven,
                    ven.id_est_ven,
                    ven.monto_ven,
                    ven.fecha_ven,
                    ven.promesa_monto_comision_ven,
                    ven.escritura_monto_comision_ven,
                    ven.total_comision_ven,
                    ven.promesa_bono_precio_ven,
                    ven.escritura_bono_precio_ven,
                    ven.total_bono_precio_ven,
                    ven.factor_categoria_ven,
                    ven.porcentaje_comision_ven,
                    viv.nombre_viv,
                    uf.valor_uf,
                    des_ven.fecha_des_ven,
                    ven.id_pie_abo_ven,
                    ven.descuento_ven
                FROM
                    vendedor_vendedor AS vend
                    INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
                    INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                WHERE
                    tor.id_con = ? AND
                    vend.id_vend = ? AND
                    EXISTS(
                        SELECT 
                            ven_cie.id_ven_cie
                        FROM 
                            cierre_venta_cierre AS ven_cie
                        WHERE
                            ven_cie.id_cie = ? AND
                            ven_cie.id_ven = ven.id_ven AND
                            ven_cie.id_est_ven = 3
                    )
                ";
            $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
            $fila_consulta_detalle = $conexion->extraer_registro();
            $contador_promesa = 0;
            if(is_array($fila_consulta_detalle)){
                foreach ($fila_consulta_detalle as $fila_det) {
                    // $monto_comision_promesa_desi = round($fila_det['promesa_monto_comision_ven'] * $UF_LIQUIDACION);
                    // $monto_comision_escritura_desi = round($fila_det['escritura_monto_comision_ven'] * $UF_LIQUIDACION);

                    $UF_DESISTIMIENTO_VENTA = 0;

                	$UF_DESISTIMIENTO_VENTA = get_uf_disistimiento($fila_det['id_ven'],$conexion);

                    $monto_comision_promesa_desi = round(round($fila_det['promesa_monto_comision_ven'],2) * $UF_DESISTIMIENTO_VENTA);
                    $monto_comision_escritura_desi = round(round($fila_det['escritura_monto_comision_ven'],2) * $UF_DESISTIMIENTO_VENTA);

                    $total_desistimiento = 0;

                    $porcentaje_comision_ven = $fila_det['porcentaje_comision_ven'];
                    $factor_categoria_ven = $fila_det['factor_categoria_ven'];

                    $consulta = 
		                "
		                SELECT
		                    valor_par
		                FROM
		                    parametro_parametro
		                WHERE
		                    id_con = ? AND
		                    valor2_par IN (".$factor_categoria_ven.")
		                ";
		            $conexion->consulta_form($consulta,array($id_con));
		            $filacategoria = $conexion->extraer_registro_unico();
		            $porcentaje_multiplo_categoria = $filacategoria['valor_par'];

                    $porcentaje_comision_venta = $porcentaje_comision_ven * $porcentaje_multiplo_categoria;


                    
                    $consulta = 
                        "
                        SELECT
                            id_est_ven
                        FROM
                            cierre_venta_cierre
                        WHERE
                            id_ven = ? AND
                            id_est_ven = ?
                        ";
                    $conexion->consulta_form($consulta,array($fila_det['id_ven'],4));
                    $cantidad_estado_promesa = $conexion->total();
					// echo "promesas-->".$cantidad_estado_promesa;
                    $consulta = 
                        "
                        SELECT
                            id_est_ven
                        FROM
                            cierre_venta_cierre
                        WHERE
                            id_ven = ? AND
                            id_est_ven = ?
                        ";
                    $conexion->consulta_form($consulta,array($fila_det['id_ven'],6));
                    $cantidad_estado_escritura = $conexion->total();
					// echo "escrituras-->".$cantidad_estado_escritura;
                    if ($fila_det['id_pie_abo_ven']==1) {
                    	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                    } else {
                    	$valor_venta_comision = $fila_det['monto_ven'];
                    }
                    $monto_acumulado_promesa_desi = 0;
                    if($cantidad_estado_promesa > 0){
                        	$monto_uf_acumulado_promesa_desi = $monto_uf_acumulado_promesa_desi - $fila_det['promesa_monto_comision_ven'];
                        	$monto_acumulado_promesa_desi = $monto_acumulado_promesa_desi - $monto_comision_promesa_desi;
                            $total_desistimiento = $total_desistimiento + $monto_comision_promesa_desi;
                    }
                    else{
                    }
                    if($cantidad_estado_escritura > 0){
                    	$monto_uf_acumulado_escritura_desi = $monto_uf_acumulado_escritura_desi - $fila_det['escritura_monto_comision_ven'];
                    	$monto_acumulado_escritura_desi = $monto_acumulado_escritura_desi - $monto_comision_escritura_desi;
                        $total_desistimiento = $total_desistimiento + $monto_comision_escritura_desi;
                    }
                    else{
                    }
                    $total_desistimiento_acumulado = $total_desistimiento_acumulado + $total_desistimiento;
                }
            }

            // escrituras
            $monto_acumulado_a_pagar = 0;
                
            $consulta = 
                "
                SELECT
                    vend.id_vend,
                    pro.nombre_pro,
                    pro.nombre2_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    ven.id_ven,
                    ven.id_est_ven,
                    ven.monto_ven,
                    ven.fecha_ven,
                    ven.promesa_monto_comision_ven,
                    ven.escritura_monto_comision_ven,
                    ven.total_comision_ven,
                    ven.promesa_bono_precio_ven,
                    ven.escritura_bono_precio_ven,
                    ven.total_bono_precio_ven,
                    ven.factor_categoria_ven,
                    ven.porcentaje_comision_ven,
                    viv.nombre_viv,
                    uf.valor_uf,
                    ven.id_pie_abo_ven,
                    ven.descuento_ven
                FROM
                    vendedor_vendedor AS vend
                    INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                    INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                WHERE
                    tor.id_con = ? AND
                    vend.id_vend = ? AND
                    EXISTS(
                        SELECT 
                            ven_cie.id_ven_cie
                        FROM 
                            cierre_venta_cierre AS ven_cie
                        WHERE
                            ven_cie.id_cie = ? AND
                            ven_cie.id_ven = ven.id_ven AND
                            ven_cie.id_est_ven IN (4,6)
                    )
                    
                ";
            $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
            $fila_consulta_detalle = $conexion->extraer_registro();
            $contador_promesa = 0;
            if(is_array($fila_consulta_detalle)){
                foreach ($fila_consulta_detalle as $fila_det) {
                    // $monto_comision_promesa = round($fila_det['promesa_monto_comision_ven'] * $fila_det['valor_uf']);
                    // $monto_comision_escritura = round($fila_det['escritura_monto_comision_ven'] * $fila_det['valor_uf']);
                    // $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $fila_det['valor_uf']);
                    // $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $fila_det['valor_uf']);

                    $porcentaje_comision_ven = $fila_det['porcentaje_comision_ven'];
                    $factor_categoria_ven = $fila_det['factor_categoria_ven'];

                    $consulta = 
		                "
		                SELECT
		                    valor_par
		                FROM
		                    parametro_parametro
		                WHERE
		                    id_con = ? AND
		                    valor2_par IN (".$factor_categoria_ven.")
		                ";
		            $conexion->consulta_form($consulta,array($id_con));
		            $filacategoria = $conexion->extraer_registro_unico();
		            $porcentaje_multiplo_categoria = $filacategoria['valor_par'];

                    $porcentaje_comision_venta = $porcentaje_comision_ven * $porcentaje_multiplo_categoria;
                    
					if ($fila_det['id_pie_abo_ven']==1) {
                    	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                    } else {
                    	$valor_venta_comision = $fila_det['monto_ven'];
                    }

                    // PROMESAS
                    $comision_promesa_red = floor($fila_det['promesa_monto_comision_ven'] * 1000) / 1000;
			        $comision_promesa_red = round($comision_promesa_red, 2);

			        $monto_comision_promesa = round($comision_promesa_red * $UF_LIQUIDACION);
                    $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $UF_LIQUIDACION);

                    // ESCRITURAS
                    $comision_escritura_red = floor($fila_det['escritura_monto_comision_ven'] * 1000) / 1000;
			        $comision_escritura_red = round($comision_escritura_red, 2);

                    $monto_comision_escritura = round($comision_escritura_red * $UF_LIQUIDACION);
                    $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $UF_LIQUIDACION);

                    $consulta = 
	                    "
	                    SELECT
	                        id_est_ven
	                    FROM
	                        cierre_venta_cierre
	                    WHERE
	                        id_ven = ? AND
	                        id_est_ven = ? AND
	                        id_cie = ?
	                    ";
                    
	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],4,$id_cierre));
	                $cantidad_estado_promesa = $conexion->total();

	                $acumula_cantidad_promesas = $acumula_cantidad_promesas + $cantidad_estado_promesa;

	                $consulta = 
	                    "
	                    SELECT
	                        id_est_ven
	                    FROM
	                        cierre_venta_cierre
	                    WHERE
	                        id_ven = ? AND
	                        id_est_ven = ? AND
	                        id_cie = ?
	                    ";
	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],6,$id_cierre));
	                $cantidad_estado_escritura = $conexion->total();

	                $acumula_cantidad_escrituras = $acumula_cantidad_escrituras + $cantidad_estado_escritura;

                    if($cantidad_estado_promesa > 0){
						
                    	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $fila_det['promesa_monto_comision_ven'];
                    	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_comision_promesa;
                        $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_promesa;
                    }
                    else{
                    }
                    
                	if($cantidad_estado_escritura > 0){
                		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $fila_det['escritura_monto_comision_ven'];
                		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_comision_escritura;
                		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_escritura;
                	}
                	else{
                	}
            	}
        	}
        	// <!-- bonos por rango -->
        	$consulta = 
                "
                SELECT
                    *
                FROM
                    cierre_bono_cierre
                WHERE
                    id_usu = ? AND
                    id_cie = ? AND
                    id_con = ?
                ";
            $conexion->consulta_form($consulta,array($id_usu,$id_cierre,$id_con)); 
            $cantidad_bono = $conexion->total();
            if($cantidad_bono > 0){
                $fila_consulta_bono = $conexion->extraer_registro(); 
                if(is_array($fila_consulta_bono)){
                    foreach ($fila_consulta_bono as $fila_bono) {
                        
                        $monto_bono = $valor_uf * $fila_bono['monto_bon_cie'];
                        $monto_acumulado_promesa = $monto_acumulado_promesa + $monto_bono;
                        $monto_acumulado_bonos = $monto_acumulado_bonos + $monto_bono;
                    }
                }
            }
            else{
            }

            // bonos al precio

            $monto_acumulado_a_pagar = 0;
            $consulta = 
                "
                SELECT
                    vend.id_vend,
                    pro.nombre_pro,
                    pro.nombre2_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    ven.id_ven,
                    ven.id_est_ven,
                    ven.monto_ven,
                    ven.promesa_monto_comision_ven,
                    ven.escritura_monto_comision_ven,
                    ven.total_comision_ven,
                    ven.promesa_bono_precio_ven,
                    ven.escritura_bono_precio_ven,
                    ven.total_bono_precio_ven,
                    viv.nombre_viv,
                    uf.valor_uf,
                    ven.id_pie_abo_ven,
                    ven.descuento_ven
                FROM
                    vendedor_vendedor AS vend
                    INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
                    INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                WHERE
                    tor.id_con = ? AND
                    vend.id_vend = ? AND
                    EXISTS(
                        SELECT 
                            ven_cie.id_ven_cie
                        FROM 
                            cierre_venta_cierre AS ven_cie
                        WHERE
                            ven_cie.id_cie = ? AND
                            ven_cie.id_ven = ven.id_ven AND
                            ven_cie.id_est_ven IN (4,6)
                    )
                ";
            $conexion->consulta_form($consulta,array($id_con,$id_vendedor,$id_cierre));
            $fila_consulta_detalle = $conexion->extraer_registro();
            $contador_promesa = 0;
            if(is_array($fila_consulta_detalle)){
                foreach ($fila_consulta_detalle as $fila_det) {
					if ($fila_det['id_pie_abo_ven']==1) {
                    	$valor_venta_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
                    } else {
                    	$valor_venta_comision = $fila_det['monto_ven'];
                    }

                    $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $UF_LIQUIDACION);
                    $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $UF_LIQUIDACION);

                    $consulta = 
	                    "
	                    SELECT
	                        id_est_ven
	                    FROM
	                        cierre_venta_cierre
	                    WHERE
	                        id_ven = ? AND
	                        id_est_ven = ? AND
	                        id_cie = ?
	                    ";
	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],4,$id_cierre));
	                $cantidad_estado_promesa = $conexion->total();

	                $consulta = 
	                    "
	                    SELECT
	                        id_est_ven
	                    FROM
	                        cierre_venta_cierre
	                    WHERE
	                        id_ven = ? AND
	                        id_est_ven = ? AND
	                        id_cie = ?
	                    ";
	                $conexion->consulta_form($consulta,array($fila_det['id_ven'],6,$id_cierre));
	                $cantidad_estado_escritura = $conexion->total();

                    if($cantidad_estado_promesa > 0){
                    	$monto_uf_acumulado_promesa = $monto_uf_acumulado_promesa + $fila_det['promesa_bono_precio_ven'];
                    	$monto_acumulado_promesa = $monto_acumulado_promesa + $monto_bono_promesa;
                        $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_promesa;
                    }
                    else{
                    }
                    
                	if($cantidad_estado_escritura > 0){
                		$monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $fila_det['escritura_bono_precio_ven'];
                		$monto_acumulado_escritura = $monto_acumulado_escritura + $monto_bono_escritura;
                		$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_escritura;
                	}
                	else{
                	}
            	}
        	}

        	// $total_bonos = 0;
    		$total_comisiones = 0;
    		$total_pago = ($monto_acumulado_promesa + $monto_acumulado_escritura) - $total_desistimiento_acumulado;


    		$total_bonos = $monto_acumulado_bonos + $monto_acumulado_a_pagar;
    		$total_comisiones = $total_pago - $total_bonos;

    		$total_liquidacion_bonos = $total_liquidacion_bonos + $total_bonos;
    		$total_liquidacion_comisiones = $total_liquidacion_comisiones + $total_comisiones;


		}
	}

	$total_liquidacion_a_pagar = $total_liquidacion_comisiones + $total_liquidacion_bonos;

	return [$total_liquidacion_comisiones, $total_liquidacion_bonos, $total_liquidacion_a_pagar, $acumula_cantidad_promesas, $acumula_cantidad_escrituras];
}


?>