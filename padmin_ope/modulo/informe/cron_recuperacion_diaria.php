<?php 
session_start(); 
require "../../config.php"; 


include _INCLUDE."class/conexion.php";
$conexion = new conexion();

// require "../helpers/get_pagos_contados_fecha.php";
// require "../helpers/get_saldos_pagar_fecha.php";

$FECHA_ANALISIS = date("Y-m-d");
$FECHA_ANALISIS = date("Y-m-d", strtotime("$FECHA_ANALISIS - 1 day"));
// $FECHA_ANALISIS = "2021-07-07";

$consulta = "SELECT id_con FROM condominio_condominio WHERE id_con > 3 ORDER BY nombre_con";
$conexion->consulta($consulta);
$fila_consulta_condominio_original = $conexion->extraer_registro();
if(is_array($fila_consulta_condominio_original)){
    foreach ($fila_consulta_condominio_original as $fila) {

		$id_con = $fila['id_con'];

		// -------------------------CREDITOS POR RECUPERAR Y RECUPERADOS HOY

		//------------------------->CH RECUPERADOS FECHA
		$ch_recup = 0;
		$cons_ch_recup = 
		  "
		  SELECT 
		    SUM(ven_liq.monto_liq_uf_ven) AS chrecup,
		    COUNT(ven.id_ven) AS cant_chrecup
		  FROM
		    venta_venta as ven,
		    venta_liquidado_venta as ven_liq,
		    vivienda_vivienda as viv
		  WHERE
		    ven.id_est_ven > 3 AND
		    ven.id_viv = viv.id_viv AND
		    viv.id_tor = ".$id_con." AND
		    ven.id_for_pag = 1 AND
		    ven.id_ven = ven_liq.id_ven AND
	        EXISTS(
		        SELECT 
		            ven_liq.id_ven
		        FROM
		            venta_liquidado_venta AS ven_liq
		        WHERE
		            ven.id_ven = ven_liq.id_ven AND
		            ven_liq.monto_liq_uf_ven <> 0 AND 
		            ven_liq.fecha_liq_ven = '".$FECHA_ANALISIS."'
		    )
		  ";
		  // echo $cons_ch_recup;
		$conexion->consulta($cons_ch_recup);
		$fila = $conexion->extraer_registro_unico();
		$ch_recup = $fila['chrecup'];
		$cant_ch_recup = $fila['cant_chrecup'];


		//------------------------- CREDITOS POR RECUPERAR NUEVO
		$cred_p_recup_n = 0;
		$creditos_por_recuperar = 
		  "
		  SELECT 
		    ven.id_viv,
		    ven.monto_credito_ven,
		    ven.monto_credito_real_ven
		  FROM
		    venta_venta as ven,
		    vivienda_vivienda as viv
		  WHERE
		    ven.id_est_ven > 3 AND
		    ven.id_viv = viv.id_viv AND
		    viv.id_tor = ".$id_con." AND
		    ven.id_for_pag = 1 AND NOT
		    EXISTS(
		    	SELECT ven_liq.id_ven 
		    	FROM venta_liquidado_venta AS ven_liq 
		    	WHERE ven_liq.id_ven = ven.id_ven AND 
		    	ven_liq.fecha_liq_ven <= '".$FECHA_ANALISIS."' AND
		    	ven_liq.monto_liq_uf_ven <> '')
		  ";

		$conexion->consulta($creditos_por_recuperar);
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila_cred_p_rec) {
		    	if ($fila_cred_p_rec["monto_credito_real_ven"]<>0) {
					$credito_hipo = $fila_cred_p_rec["monto_credito_real_ven"];
				} else {
					$credito_hipo = $fila_cred_p_rec["monto_credito_ven"];
				}
		        $cred_p_recup_n = $cred_p_recup_n + $credito_hipo;
		    }
		}

		// ----------------------------FIN CREDITOS

		// ---------------------------CONTADOS POR RECUPERAR Y RECUPERADOS HOY
		// ---------CONTADOS POR RECUPERAR y RECUPERADS NUEVO
		//---POR RECUPERAR recorre por venta contado
		// toma el saldo a pagar (total - reserva + pie)
		// le resta los pagos tipo saldo contado realizados
		// el monto respultante es lo que falta por cobrar
		$contados_por_recup_n = 0;
		$consulta_contado_pr = 
		    "
		    SELECT 
		    	ven.id_ven,
		    	ven.id_viv,
		        ven.fecha_ven,
		        ven.monto_credito_ven
		    FROM
		        venta_venta AS ven 
		        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		    WHERE 
		        ven.id_est_ven > 3 AND
		        viv.id_tor = ".$id_con." AND
		        ven.id_for_pag = 2
		    ";
		// echo $consulta_pie;
		$conexion->consulta($consulta_contado_pr);
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila_ven_con) {
		    	$id_venta = $fila_ven_con['id_ven'];
		    	$saldo_contado_venta = $fila_ven_con['monto_credito_ven'];
		    	$saldo_contado_por_pagar = 0;
		    	$total_pago_saldo_uf = 0;
		    	// busca los pagos saldo contado pagados
		    	$consulta = 
	                "
	                SELECT 
	                    pag.id_pag,
	                    ven.id_ven,
	                    pag.monto_pag,
	                    pag.id_est_pag,
	                    pag.fecha_real_pag
	                FROM
	                    pago_pago AS pag 
	                    INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
	                WHERE 
	                    pag.id_ven = ? AND 
	                    pag.id_cat_pag = 3 AND 
	                    pag.id_est_pag = 1 AND 
	                    pag.fecha_real_pag <= '".$FECHA_ANALISIS."'
	                ";
	            $conexion->consulta_form($consulta,array($id_venta));
	            $fila_consulta = $conexion->extraer_registro();
	            if(is_array($fila_consulta)){
	                foreach ($fila_consulta as $fila) {
						$valor_uf_efectivo = 0;
	                	
	                    if ($fila["fecha_real_pag"]=="0000-00-00" || $fila["fecha_real_pag"]==null) { //abonos no cancelados aún
	                        
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
								$abono_uf = $fila["monto_pag"] / $valor_uf_efectivo;
							} else {
								$valor_uf_efectivo = 0;
							}                                
	                    }
	                    // $total_abono = $total_abono + $fila["monto_pag"];
						$total_pago_saldo_uf = $total_pago_saldo_uf + $abono_uf;
	                }
	            }
	            $saldo_contado_por_pagar = $saldo_contado_venta - $total_pago_saldo_uf;
	            $contados_por_recup_n = $contados_por_recup_n + $saldo_contado_por_pagar;
		    }
		}


		// ---RECUPERADOS sumar los pagos en el día tipo Saldo Contado realizados de ventas contado
		$contados_recuperados_n = 0;
		$total_pago_saldo_uf = 0;
		$consulta = 
	        "
	        SELECT 
	            pag.id_pag,
	            ven.id_ven,
	            pag.monto_pag,
	            pag.id_est_pag,
	            pag.fecha_real_pag
	        FROM
	            pago_pago AS pag 
	            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
	            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	        WHERE 
	        	ven.id_est_ven > 3 AND
		        viv.id_tor = ".$id_con." AND
		        ven.id_for_pag = 2 AND 
	            pag.id_cat_pag = 3 AND 
	            pag.id_est_pag = 1 AND 
	            pag.fecha_real_pag = '".$FECHA_ANALISIS."'
	        ";
	    $conexion->consulta_form($consulta,array($id_venta));
	    $fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	        	$consulta = "SELECT valor_uf FROM uf_uf WHERE fecha_uf = ?";
				$conexion->consulta_form($consulta,array($fila["fecha_real_pag"]));
				$cantidad_uf = $conexion->total();
				if($cantidad_uf > 0){
					$filauf = $conexion->extraer_registro_unico();
					$valor_uf_efectivo = $filauf['valor_uf'];
					$abono_uf = $fila["monto_pag"] / $valor_uf_efectivo;
				} else {
					$valor_uf_efectivo = 0;
				}                          
	            // $total_abono = $total_abono + $fila["monto_pag"];
				$total_pago_saldo_uf = $total_pago_saldo_uf + $abono_uf;
	        }
	    }

	    $contados_recuperados_n = $total_pago_saldo_uf;


	    //---------------------------------> CÁLCULOS DE LOS PIE, PAGADOS Y POR PAGAR
		$pie_pagado_efectivo_fecha = 0;
		$pie_pagado_efectivo_general = 0;
		$pie_general_porcobrar = 0;
		$total_pie_general = 0;
		$cuenta_por_cobrar = 0;
		$consulta_pie = 
		    "
		    SELECT 
		        pag.id_pag,
		        cat_pag.nombre_cat_pag,
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
		        INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
		        INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
		        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		    WHERE 
		        ven.id_est_ven > 3 AND
		        viv.id_tor = ".$id_con." AND
		            (pag.id_cat_pag = 1 OR pag.id_cat_pag = 2)
		    ";
		// echo $consulta_pie;
		$conexion->consulta($consulta_pie);
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila_pag) {
				$valor_uf_efectivo = 0;
				// $pie_pagado_efectivo = 0;
				// $pie_pagado_porcobrar = 0;
				
		    	
		    	//---------------------------> PIE NO CANCELADOS A LA FECHA
		        if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null || $fila_pag["fecha_real_pag"] > $FECHA_ANALISIS) { //abonos no cancelados aún
		            // $fecha_real_mostrar = "";

		            // ------------------- las UF PIE de estas ventas se calculan con la fecha venta

		            $consultauf = "SELECT valor_uf FROM uf_uf WHERE fecha_uf = '".date("Y-m-d",strtotime($fila_pag["fecha_ven"]))."'";
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
							// $abono_uf = 0;
						}
						
					} else {
						$valor_uf = 0;
					}
					// PIE QUE NO HAN SIDO PAGADOS O PAGADOS EN EL FUTURO
					$pie_general_porcobrar = $pie_general_porcobrar + $abono_uf;
					$total_pie_general = $total_pie_general + $abono_uf;
					$cuenta_por_cobrar++;

		        }
		        else{
		        	// -------------->SI TIENE FECHA Y ES LA FECHA ANÁLISIS
		            if ($fila_pag["fecha_real_pag"] == $FECHA_ANALISIS) {

		            	$consultauf = "SELECT valor_uf FROM uf_uf WHERE fecha_uf = ?";
						$conexion->consulta_form($consultauf,array($fila_pag["fecha_real_pag"]));
						$cantidad_uf = $conexion->total();

						// ------------------- las UF PIE de estas ventas se calculan con la fecha del día

						// echo $fila_pag["id_ven"]."--".$fila_pag["monto_pag"];

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
						// PIE PAGADOS JUSTO EN EL DÍA BUSCADO
						$pie_pagado_efectivo_fecha = $pie_pagado_efectivo_fecha + $abono_uf;
						$total_pie_general = $total_pie_general + $abono_uf;

						// ----------------------->PAGADO EN OTRA FECHA
		            } else {
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
						// ------------------- las UF PIE de estas ventas se calculan con la fecha del pago real
						// PIE PAGADOS EN FECHAS ANTERIORES
						$pie_pagado_efectivo_general = $pie_pagado_efectivo_general + $abono_uf;
						$total_pie_general = $total_pie_general + $abono_uf;          
		            }
		            
		        }
		        // $total_abono = $total_abono + $monto_pag;
				// $total_uf = $total_uf + $abono_uf;
				// $acumula_pie_pagados = $acumula_pie_pagados + $pie_pagado_efectivo;
		       
		    }
		}
		// -------------------FIN PIES


        // TOTAL CANTIDADES HOY
		$total_hoy_recibido_hoy = $contados_recuperados_n + $pie_pagado_efectivo_fecha + $ch_recup;


		// echo "<br><br>La fecha: ".$FECHA_ANALISIS."<br>";
		// echo "El condo: ".$id_con."<br>";

		// echo "CREDITOS<br>";
		// echo "2- Créditos Recuperados OK: ".$ch_recup."<br>";
		// echo "5- Créditos por recuperar: ".$ch_por_recup."<br>";
		// echo "5- NUEVO: Crédito por recuperar OK: ".$cred_p_recup_n."<br>";


		// echo "<br><br>";
		// echo "CONTADOS<br>";
		// echo "3- Contados Recuperados OK: ".$contado_recup."<br>";
		// echo "6- Contados Por Recuperar OK: ".$total_contados_por_recibir."<br>";
		// echo "6- Contados Por Recuperar NUEVO OK: ".$contados_p_recup_n."<br><br><br>";

		// echo "CONTADOS POR RECUPERAR, en base a saldos: ".$contados_por_recup_n."<br>";
		// echo "CONTADOS RECUPERADOS  FECHA, en base a saldos: ".$contados_recuperados_n;

		// echo "<br><br>";
		// echo "PIES<br>";
		// echo "1- Pie Pagado fecha OK: ".$pie_pagado_efectivo_fecha."<br>";		
		// echo "4- Pie por cobrar OK: ".$pie_general_porcobrar."<br><br><br><br>";

		// echo "----------->".$cuenta_por_cobrar;
		
		
		// echo "Pie pagados no fecha: ".$pie_pagado_efectivo_general."<br>";
		// echo "Total de HOY: ".$total_hoy_recibido_hoy."<br>";

		// $FECHA_ANALISIS;

		$consulta_insercion = 
		"
		    SELECT
		        id_his
		    FROM
		        historico_recuperacion_historico_diario
		    WHERE
		        fecha_his = '".$FECHA_ANALISIS."' AND id_tor = ".$id_con."
		    ";
		$conexion->consulta($consulta_insercion);
		$cantidad_insertado = $conexion->total();

		if($cantidad_insertado == 0) {
			$consulta = "INSERT INTO historico_recuperacion_historico_diario VALUES(?,?,?,?,?,?,?,?,?,?)";
			$conexion->consulta_form($consulta,array(0,$id_con,41,$FECHA_ANALISIS,$contados_recuperados_n,$pie_pagado_efectivo_fecha,$ch_recup,$cred_p_recup_n,$pie_general_porcobrar,$contados_por_recup_n));
		}

		
	}
}


?>

