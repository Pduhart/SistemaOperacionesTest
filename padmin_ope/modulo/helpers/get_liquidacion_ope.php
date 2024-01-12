<?php

function get_liquidacion_ope($id_usu,$id_cierre,$conexion){
	$id_cierre = $id_cierre;
	$id_usuario = $id_usu;

	$consulta = 
	    "
	    SELECT
	        nombre_usu,
	        apellido1_usu,
	        apellido2_usu,
	        id_usu
	    FROM
	        usuario_usuario
	    WHERE
	        id_usu = ?
	    ";
	$conexion->consulta_form($consulta,array($id_usuario));
	$fila = $conexion->extraer_registro_unico();
	$id_usu = $fila["id_usu"];

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
	        ven.id_operacion_ven = ?
	    GROUP BY
	        cie.id_cie,
	        cie.anio_cie,
	        con.nombre_con,
	        con.alias_con,
	        mes.id_mes,
	        uf.valor_uf,
	        con.id_con
	    ";
	$conexion->consulta_form($consulta,array($id_cierre,$id_usuario));
	$cantidad_condominio = $conexion->total();
	$fila_consulta_cierre = $conexion->extraer_registro();
	$contador_pagina = 1;
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

	        $monto_acumulado_a_pagar = 0;
	        $monto_acumulado_escritura = 0;
	        $consulta = 
	            "
	            SELECT
	            	DISTINCT (ven.id_ven),
	                usu.id_usu,
	                pro.nombre_pro,
	                pro.nombre2_pro,
	                pro.apellido_paterno_pro,
	                pro.apellido_materno_pro,
	                ven.id_ven,
	                ven.id_for_pag,
	                ven.id_est_ven,
	                ven.monto_ven,
	                ven.fecha_ven,
	                ven.escritura_monto_comision_operacion_ven,
	                viv.nombre_viv,
	                uf.valor_uf,
	                ven.fecha_escritura_ven
	            FROM
	                usuario_usuario AS usu
	                INNER JOIN venta_venta AS ven ON ven.id_operacion_ven = usu.id_usu
	                INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_ven = ven.id_ven
	                INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
	                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	            WHERE
	                tor.id_con = ? AND
	                usu.id_usu = ? AND
	                ven_cie.id_cie = ? AND
	                EXISTS(
	                    SELECT 
	                        ven_cie.id_ven_cie
	                    FROM 
	                        cierre_venta_cierre AS ven_cie
	                    WHERE
	                        ven_cie.id_ven = ven.id_ven AND
	                        ven_cie.id_est_ven IN (6)
	                )
	            ";
	        $conexion->consulta_form($consulta,array($id_con,$id_usuario,$id_cierre));
	        $fila_consulta_detalle = $conexion->extraer_registro();
	        $monto_uf_acumulado_escritura = 0;
	        if(is_array($fila_consulta_detalle)){
	            foreach ($fila_consulta_detalle as $fila_det) {
	                $valor_uf = 0;
					
					$consulta = 
                        "
                        SELECT
                            uf.valor_uf
                        FROM
                            uf_uf AS uf
                        WHERE
                            uf.fecha_uf = ?
                        ";
                    $conexion->consulta_form($consulta,array($fila_det['fecha_escritura_ven']));
                    $fila = $conexion->extraer_registro_unico();
                    $fecha_escritura_ven = $fila_det['fecha_escritura_ven'];
                    $valor_uf = $fila["valor_uf"];

	                $monto_comision_escritura = round($fila_det['escritura_monto_comision_operacion_ven'] * $valor_uf);

	                $monto_uf_acumulado_escritura = $monto_uf_acumulado_escritura + $fila_det['escritura_monto_comision_operacion_ven'];
		            $monto_acumulado_escritura = $monto_acumulado_escritura + $monto_comision_escritura;

				}
		    }



		}
	}


	$total_liquidacion_a_pagar = $monto_acumulado_escritura;

	return [$total_liquidacion_a_pagar];
}


?>