<?php

function get_liquidacion_j_ope($id_usu,$id_cierre,$conexion){
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
		    INNER JOIN cierre_bono_cierre_venta AS cie_bon_cie ON cie_bon_cie.id_cie = cie.id_cie
		    INNER JOIN condominio_condominio AS con ON con.id_con = cie_bon_cie.id_con
		    INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
		    INNER JOIN uf_uf AS uf ON uf.fecha_uf = cie.fecha_hasta_cie
		WHERE
		    cie.id_cie = ? 
		GROUP BY
		    cie.id_cie,
		    cie.anio_cie,
		    con.nombre_con,
		    con.alias_con,
		    mes.id_mes,
		    uf.valor_uf,
		    con.id_con
		";

		$conexion->consulta_form($consulta,array($id_cierre));
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

	        $acumula_monto_pesos_bono = 0;
	        $acumula_monto_uf_bono = 0;

	        $consulta_jo = 
            "
            SELECT
                ven.monto_ven,
                pro.nombre_pro,
                pro.apellido_paterno_pro,
                pro.apellido_materno_pro,
                viv.id_mod,
                viv.nombre_viv,
                ven.id_ven,
                ven_liq.fecha_liq_ven,
                ven.fecha_escritura_ven,
                cie_bon.nombre_bon_cie,
                cie_bon.monto_bon_cie
            FROM
                venta_venta AS ven
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                INNER JOIN cierre_bono_cierre_venta AS cie_bon ON cie_bon.id_ven = ven.id_ven
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                INNER JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
            WHERE
                tor.id_con = ? AND 
                cie_bon.id_cie = ? AND
                cie_bon.id_usu = ?
            ";
            $conexion->consulta_form($consulta_jo,array($id_con, $id_cierre, $id_usu));
	        $fila_consulta_joperaciones = $conexion->extraer_registro();
	        $contador_jo = 0;
	        if(is_array($fila_consulta_joperaciones)){
	            foreach ($fila_consulta_joperaciones as $fila_jo) {
	            	$fecha_liq_ven = $fila_jo["fecha_liq_ven"];
            		$fecha_escritura_ven = $fila_jo["fecha_escritura_ven"];

            		$bono_uf = $fila_jo['monto_bon_cie'];
            		$acumula_monto_uf_bono = $acumula_monto_uf_bono + $bono_uf;
            		$bono_pesos = $valor_uf * $bono_uf;
                	$acumula_monto_pesos_bono = $acumula_monto_pesos_bono + $bono_pesos;
                	$bono_pesos = number_format($bono_pesos, 0, ',', '.');

                }
            }

        }
    }


	$total_liquidacion_a_pagar = $acumula_monto_pesos_bono;

	return [$total_liquidacion_a_pagar];
}


?>