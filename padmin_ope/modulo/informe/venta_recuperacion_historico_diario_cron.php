<?php 
session_start(); 
require "../../config.php"; 


include _INCLUDE."class/conexion.php";
$conexion = new conexion();


require "../helpers/get_pagos_contados_fecha.php";
require "../helpers/get_saldos_pagar_fecha.php";

$FECHA_ANALISIS = date("Y-m-d");
$FECHA_ANALISIS = date("Y-m-d", strtotime("$FECHA_ANALISIS - 1 day"));
// $FECHA_ANALISIS = "2021-08-20";

// echo $FECHA_ANALISIS;


$consulta = "SELECT id_con FROM condominio_condominio WHERE id_con > 3 ORDER BY nombre_con";
$conexion->consulta($consulta);
$fila_consulta_condominio_original = $conexion->extraer_registro();
if(is_array($fila_consulta_condominio_original)){
    foreach ($fila_consulta_condominio_original as $fila) {

		$id_con = $fila['id_con'];


		// $hoy = date("Y-m-d");
		//------------------------->CH RECUPERADOS FECHA
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


		//------------------------->CH POR RECUPERAR
	    $acumula_saldos_ch = 0;
	    $credito_hipo = 0;
	    $saldoPagarCredito = 0;
	    $cant_ch_por_recup = 0;
	    $saldo_por_pagar = 0;
	    $consulta = "
	        SELECT
	            ven.monto_ven,
	            ven.descuento_ven,
	            ven.id_ven,
	            ven.fecha_escritura_ven,
	            ven_liq.fecha_liq_ven,
	            ven_liq.monto_liq_uf_ven,
	            ven_liq.monto_liq_pesos_ven,
	            ven.monto_credito_real_ven,
	            ven.monto_credito_ven
	        FROM
	            venta_venta AS ven
	        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	        LEFT JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
	        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
	        WHERE
	            ven.id_est_ven > 3 AND
	            ven.id_ban <> 0 AND
	            ven.id_for_pag = 1 AND
	            viv.id_tor = ".$id_con."
	        ";
	    // echo $consulta;
	    $conexion->consulta($consulta);
	    $fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	        	$credito_hipo = 0;
	        	$fecha_liq_ven = $fila["fecha_liq_ven"];
	        	$monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
	        	$id_ven = $fila["id_ven"];
	        	
	            if($fecha_liq_ven){
	            	if($fecha_liq_ven <= $FECHA_ANALISIS & $monto_liq_uf_ven > 0){
	            		// echo $id_ven." - ";

			        	$pagado = 1;
			        	$por_pagar = 0;
			        	$acumula_uf_recuperado = $acumula_uf_recuperado + $fila["monto_liq_uf_ven"];
			        	$acumula_pesos_recuperado = $acumula_pesos_recuperado + $fila["monto_liq_pesos_ven"];	

			        } else {
			        	$pagado = 0;
			        	$por_pagar = 1;
			        	if ($fila["monto_credito_real_ven"]<>0) {
							$credito_hipo = $fila["monto_credito_real_ven"];
						} else {
							$credito_hipo = $fila["monto_credito_ven"];
						}
			        	$acumula_saldos_ch = $acumula_saldos_ch + $credito_hipo;
			        }
	            } else {
	            	$fecha_liq_ven = "";
	            	$pagado = 0;
			        $por_pagar = 1;
			        $cant_ch_por_recup++;
			        if ($fila["monto_credito_real_ven"]<>0) {
						$credito_hipo = $fila["monto_credito_real_ven"];
					} else {
						$credito_hipo = $fila["monto_credito_ven"];
					}

					// revisar
					$saldoPagarCredito = get_saldos_pagar_fecha($id_ven,$FECHA_ANALISIS,$conexion);

	    			$saldo_por_pagar = $saldoPagarCredito[0];

	    			// echo $saldo_por_pagar."-------<br>";

			        $acumula_saldos_ch = $acumula_saldos_ch + $credito_hipo + $saldo_por_pagar;

			        // echo $acumula_saldos_ch."<br>";

	            }
	        }
	    }

	    $acumula_ch_por_recup = $acumula_saldos_ch;

		//---------------------> CONTADO RECUPERADO FECHA
		$cons_contados_recup = 
		  "
		  SELECT 
		    -- SUM(ven.monto_vivienda_ingreso_ven) AS conrecup,
		    SUM(ven_liq.monto_liq_uf_ven) AS conrecup,
		    COUNT(ven.id_ven) AS cant_conrecup
		  FROM
		    venta_venta as ven,
		    venta_liquidado_venta as ven_liq,
		    vivienda_vivienda as viv
		  WHERE
		    ven.id_est_ven > 3 AND
		    ven.id_viv = viv.id_viv AND
		    viv.id_tor = ".$id_con." AND
		    ven.id_for_pag = 2 AND
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
		$conexion->consulta($cons_contados_recup);
		$fila = $conexion->extraer_registro_unico();
		$con_recup = $fila['conrecup'];
		$cant_con_recup = $fila['cant_conrecup'];

		//------------------------> CONTADOS POR RECUPERAR
		$acumula_por_recibir_deptos = 0;

	    $consulta = "
	        SELECT
	            ven.monto_ven,
	            ven.monto_vivienda_ven,
	            ven.monto_estacionamiento_ven,
	            ven.monto_bodega_ven,
	            ven.descuento_ven,
	            ven.id_ven,
	            ven.fecha_escritura_ven,
	            ven_liq.fecha_liq_ven,
	            ven_liq.monto_liq_uf_ven,
	            ven_liq.monto_liq_pesos_ven,
	            ven.monto_credito_real_ven,
	            ven.monto_credito_ven
	        FROM
	            venta_venta AS ven
	        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	        LEFT JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
	        WHERE
	            ven.id_est_ven > 3 AND
	            ven.id_ban = 0 AND
	            ven.id_for_pag = 2 AND
	            viv.id_tor = ".$id_con."
	        ";
	    // echo $consulta;
	    $conexion->consulta($consulta);
	    $fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
	        foreach ($fila_consulta as $fila) {
	        	$credito_hipo = 0;
	        	$id_ven = $fila["id_ven"];
	        	$fecha_liq_ven = $fila["fecha_liq_ven"];
	        	$monto_liq_uf_ven = 0;

	        	$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

	        	// acumula valores deptos
	        	$acumula_total_inmob = $acumula_total_inmob + $total_monto_inmob;
	            if($fecha_liq_ven){
	            	if($fecha_liq_ven <= $FECHA_ANALISIS){
			        	$pagado = 1;
			        	$por_pagar = 0;
			        	$monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
			        	$acumula_uf_recuperado_contado = $acumula_uf_recuperado_contado + $fila["monto_liq_uf_ven"];
			        	$acumula_pesos_recuperado = $acumula_pesos_recuperado + $fila["monto_liq_pesos_ven"];	

			        	// $monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven;
			        	// echo $id_ven.".---------------";

			        } else {
			        	$pagado = 0;
			        	$por_pagar = 1;
			        	if ($fila["monto_credito_real_ven"]<>0) {
							$credito_hipo = $fila["monto_credito_real_ven"];
						} else {
							$credito_hipo = $fila["monto_credito_ven"];
						}
			        	$acumula_saldos_ch = $acumula_saldos_ch + $credito_hipo;
			        }
	            } else {
	            	$fecha_liq_ven = "";
	            	$pagado = 0;
			        $por_pagar = 1;
			        if ($fila["monto_credito_real_ven"]<>0) {
						$credito_hipo = $fila["monto_credito_real_ven"];
					} else {
						$credito_hipo = $fila["monto_credito_ven"];
					}
			        $acumula_saldos_ch = $acumula_saldos_ch + $credito_hipo;

	            }

	            // revisa helper
	            $pagosVentaContado = get_pagos_contados_fecha($id_ven,$FECHA_ANALISIS,$conexion);
	            $pie_pagado_efectivo = $pagosVentaContado[2];


	            $por_recibir_depto = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven;
	            if( $por_recibir_depto<0) {
	            	 $por_recibir_depto = 0;
	            }

	            // echo $id_ven." - ".$por_recibir_depto." - ".$total_monto_inmob." - ".$pie_pagado_efectivo." - ".$monto_liq_uf_ven."<br>";

	            $acumula_por_recibir_deptos = $acumula_por_recibir_deptos +  $por_recibir_depto;

	        }
	    }

	    $total_contados_por_recibir = $acumula_por_recibir_deptos;


		//---------------------------------> PIE DEPTOS RECIBIDOS
		$pie_pagado_efectivo_fecha = 0;
		$pie_pagado_efectivo_general = 0;
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
							// $abono_uf = 0;
						}
						
					} else {
						$valor_uf = 0;
					}

					$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;

		        }
		        else{
		            // $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));

		        	// -------------->SI TIENE FECHA Y ES LA FECHA ANÁLISIS
		            if ($fila_pag["fecha_real_pag"] == $FECHA_ANALISIS) {

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

						$pie_pagado_efectivo_fecha = $pie_pagado_efectivo_fecha + $abono_uf;  

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

						$pie_pagado_efectivo_general = $pie_pagado_efectivo_general + $abono_uf;          
		            }
		            
		        }
		        // $total_abono = $total_abono + $monto_pag;
				// $total_uf = $total_uf + $abono_uf;
				// $acumula_pie_pagados = $acumula_pie_pagados + $pie_pagado_efectivo;
		       
		    }
		}

		// CREDITOS POR RECUPERAR
		$ch_por_recup = $acumula_ch_por_recup;

		// todo lo POR RECUPERAR
		$ch_con_por_recup = $total_contados_por_recibir + $ch_por_recup;

		// TOTAL CANTIDADES HOY
		$total_hoy = $con_recup + $pie_pagado_efectivo_fecha + $ch_con_por_recup + $ch_recup + $pie_pagado_porcobrar;
		// $total_cant_hoy = $cant_ch_recup + $cant_ch_por_recup + $cant_con_recup;

		// CONTADOS RECUPERADOS FECHA
		$con_recup;

		// PIE PAGADOS HOY
		$pie_pagado_efectivo_fecha;

		// PIE PAGADOS NO FECHA
		$pie_pagado_efectivo_general;

		// todo lo POR RECUPERAR
		$ch_con_por_recup;

		// CREDITOS RECUPERADOS FECHA
		$ch_recup;

		// $cant_ch_por_recup;
		// $cant_con_recup;
		// $cant_ch_recup;
		$id_con;

		// $piefal = 0;

		// echo "La fecha: ".$FECHA_ANALISIS."<br>";
		// echo "El condo: ".$id_con."<br>";
		// echo "Contados Recuperados: ".$con_recup."<br>";
		// echo "Pie Pagado fecha: ".$pie_pagado_efectivo_fecha."<br>";
		// echo "Créditos Recuperados: ".$ch_recup."<br><br><br>";


		// echo "Contados Por Recuperar: ".$total_contados_por_recibir."<br>";
		// echo "Pie pagados no fecha: ".$pie_pagado_efectivo_general."<br>";
		// echo "Pie por cobrar: ".$pie_pagado_porcobrar."<br>";
		// echo "Créditos por recuperar: ".$ch_por_recup."<br>";
		// echo "Créd. + Contados por recuperar: ".$ch_con_por_recup."<br>";
		// echo "Total: ".$total_hoy."<br>";
		// echo "<br><br><br>";
		// echo $cant_ch_por_recup."<b r>";
		// echo $cant_con_recup."<br>";
		// echo $cant_ch_recup."<br>";
		// echo $total_cant_hoy."<br><br><br>";

		$consulta = "INSERT INTO historico_recuperacion_historico VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_con,41,$FECHA_ANALISIS,$con_recup,$pie_pagado_efectivo_fecha,$ch_con_por_recup,$ch_recup,$pie_pagado_porcobrar,$cant_ch_por_recup,$cant_con_recup,$cant_ch_recup));
	}
}

?>

