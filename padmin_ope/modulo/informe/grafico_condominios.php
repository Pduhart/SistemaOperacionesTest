<div style="min-height: 240px">
	<h4 class="text-center">Unidades por Condominio</h4>
    <div id="grafico_venta" style="width: 98%; margin-top: 0px; margin-left: auto; margin-right: auto; height: 100%"></div>
</div>
<!-- graficos highchart -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    $(function () {
    	Highcharts.setOptions({
            lang: {
                decimalPoint: ',',
                thousandsSep: '.'
            },
            credits: {
                enabled: false
            }
        });
		$('#grafico_venta').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: ''
		    },
		    xAxis: {
		    	labels: {
	                style: {
	                    fontSize:'14px'
	                }
	            },
		        categories: [
		        <?php
				$tvlc = '';
				$tvpc = '';
				$tvec = '';
				$tvdc = '';
				$tvdic = '';
		        $HOY = date("Y-m-d");
		        
		        $consulta_con = 
				    "
				    SELECT
				        DISTINCT con.alias_con,
				        con.id_con
				    FROM
				        condominio_condominio as con,
				        vivienda_vivienda as viv,
				        torre_torre as tor
				    WHERE
				        con.id_est_con = 1 AND
				        viv.id_tor = tor.id_tor AND
				        tor.id_con = con.id_con AND
				        con.id_con <> 1
				    ";
				$conexion->consulta($consulta_con);
				$cantidad_condo = $conexion->total();
				$conta_condo = 0;
				$fila_consulta_condo = $conexion->extraer_registro();
				if(is_array($fila_consulta_condo)){
				    foreach ($fila_consulta_condo as $fila_condo) {
				    	// promesas
				    	$consulta_ventas_promesa_condo = 
						  "
						  SELECT 
						    ven.id_ven
						  FROM
						    venta_venta ven,
				        	vivienda_vivienda as viv,
				        	torre_torre as tor
						  WHERE
						    (ven.id_est_ven <> 3) AND
							ven.id_viv = viv.id_viv AND
							viv.id_tor = tor.id_tor AND
						    tor.id_con = ".$fila_condo["id_con"]." AND NOT
                            EXISTS(
                                SELECT 
                                    ven_eta.id_ven
                                FROM
                                    venta_etapa_venta AS ven_eta
                                WHERE
                                    ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
                            )
						  ";
						$conexion->consulta($consulta_ventas_promesa_condo);
						$total_ventas_promesa_condo = $conexion->total();
						$tvpc .= $total_ventas_promesa_condo.",";


						// ventas
				   //  	$consulta_ventas_escri_condo = 
						 //  "
						 //  SELECT 
						 //    ven.id_ven
						 //  FROM
						 //    venta_venta ven,
				   //      	vivienda_vivienda as viv,
				   //      	torre_torre as tor
						 //  WHERE
						 //    (ven.id_est_ven = 6 or ven.id_est_ven = 7) AND
							// ven.id_viv = viv.id_viv AND
							// viv.id_tor = tor.id_tor AND
						 //    tor.id_con = ".$fila_condo["id_con"]."
						 //  ";
						// ventas en proceso de escrituración

						$consulta_ventas_escri_condo = 
						  "
						  SELECT 
						    ven.id_ven
						  FROM
						    venta_venta as ven,
						    vivienda_vivienda as viv
						  WHERE
						    ven.id_est_ven > 3 AND
						    ven.id_viv = viv.id_viv AND
						    viv.id_tor = ".$fila_condo["id_con"]." AND EXISTS(
						        SELECT 
						            ven_eta.id_ven
						        FROM
						            venta_etapa_venta AS ven_eta
						        WHERE
						            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
						    ) AND 
                            NOT EXISTS(
                                SELECT 
                                    ven_liq.id_ven
                                FROM
                                    venta_liquidado_venta AS ven_liq
                                WHERE
                                    ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '' AND monto_liq_uf_ven <> 0 AND ven_liq.fecha_liq_ven < '".$HOY."'
                            )";
						 
						$conexion->consulta($consulta_ventas_escri_condo);
						$total_ventas_escri_condo = $conexion->total();
						$tvec .= $total_ventas_escri_condo.",";

						// ventas y con liquidación credito
						$consulta_ventas_liqui_condo_cr = 
						  "
						  SELECT 
						    ven.id_ven
						  FROM
						    venta_venta ven,
				        	vivienda_vivienda as viv,
				        	torre_torre as tor
						  WHERE
						    (ven.id_est_ven > 3) AND
							ven.id_viv = viv.id_viv AND
							viv.id_tor = tor.id_tor AND
							ven.id_for_pag = 1 AND
						    tor.id_con = ".$fila_condo["id_con"]." AND 
                            EXISTS(
                                SELECT 
                                    ven_liq.id_ven
                                FROM
                                    venta_liquidado_venta AS ven_liq
                                WHERE
                                    ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '' AND monto_liq_uf_ven <> 0 AND ven_liq.fecha_liq_ven < '".$HOY."'
                            )
						  ";
						$conexion->consulta($consulta_ventas_liqui_condo_cr);
						$total_ventas_liqui_condo_cr = $conexion->total();

						$consulta_ventas_liqui_condo_co = 
						  "
						  SELECT 
						    ven.id_ven
						  FROM
						    venta_venta ven,
				        	vivienda_vivienda as viv,
				        	torre_torre as tor
						  WHERE
						    (ven.id_est_ven > 3) AND
							ven.id_viv = viv.id_viv AND
							viv.id_tor = tor.id_tor AND
							ven.id_for_pag = 2 AND
						    tor.id_con = ".$fila_condo["id_con"]." AND 
                            EXISTS(
                                SELECT 
                                    ven_liq.id_ven
                                FROM
                                    venta_liquidado_venta AS ven_liq
                                WHERE
                                    ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '' AND monto_liq_uf_ven <> 0 AND ven_liq.fecha_liq_ven < '".$HOY."'
                            )
						  ";
						$conexion->consulta($consulta_ventas_liqui_condo_co);
						$total_ventas_liqui_condo_co = $conexion->total();

						$tvlc .= $total_ventas_liqui_condo_cr + $total_ventas_liqui_condo_co.",";

						// desistimiento
				    	$consulta_ventas_desis_condo = 
						  "
						  SELECT 
						    ven.id_ven
						  FROM
						    venta_venta ven,
				        	vivienda_vivienda as viv,
				        	torre_torre as tor
						  WHERE
						    ven.id_est_ven = 3 AND
							ven.id_viv = viv.id_viv AND
							viv.id_tor = tor.id_tor AND
						    tor.id_con = ".$fila_condo["id_con"]."
						  ";
						$conexion->consulta($consulta_ventas_desis_condo);
						$total_ventas_desis_condo = $conexion->total();
						$tvdc .= $total_ventas_desis_condo.",";

						// disponibles
						$consulta_disp_condo = 
                            "
                            SELECT
                                viv.id_viv
                            FROM
                                torre_torre AS tor
                                INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                            WHERE
                                tor.id_con = ? AND
                                viv.id_est_viv = 1 AND NOT
                                EXISTS(
                                    SELECT 
                                        ven.id_ven
                                    FROM
                                        venta_venta AS ven
                                    WHERE
                                        ven.id_viv = viv.id_viv AND 
                                        ven.id_est_ven <> 3
                                )
                            ";
                        $conexion->consulta_form($consulta_disp_condo,array($fila_condo["id_con"]));
                        $total_vivienda_disponible_condo = $conexion->total();
						$tvdic .= $total_vivienda_disponible_condo.",";

				        $alias_con = $fila_condo["alias_con"];
				        $conta_condo++;
				        if ($conta_condo==$cantidad_condo) {
							echo "'".$alias_con."'";
				        } else {
							echo "'".$alias_con."',";
				        }					        
				    }
				}
		        ?>
		    	]
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Porcentaje'
		        },
		        stackLabels: {
		            enabled: true,
		            style: {
		                fontWeight: 'normal',
		                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		            }
		        }
		    },
		    legend: {
		        align: 'right',
		        x: -30,
		        verticalAlign: 'top',
		        y: -10,
		        floating: true,
		        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
		        borderColor: '#CCC',
		        borderWidth: 1,
		        shadow: false
		    },
		    tooltip: {
		        headerFormat: '<b>{point.x}</b><br/>',
		        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}<br/>({point.percentage:.0f}%)'
		    },
		    plotOptions: {
		        column: {
		            stacking: 'percent',
		            dataLabels: {
		                enabled: true,
		                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
		            }
		        }
		    },
		    colors: ['#fac090', '#64f164', '#00af50', '#50612a'],
		    series: [{
		        name: 'Disp.',
		        data: [
				<?php
				echo substr($tvdic, 0, -1);
				?>
		        ]
		    }, {
		        name: 'Prom.',
		        data: [
				<?php
				echo substr($tvpc, 0, -1);
				?>
		        ]
		    }, {
		        name: 'Proc. Escrit.',
		        data: [
				<?php
				echo substr($tvec, 0, -1);
				?>
		        ]
		    }, {
		        name: 'Liquidadas',
		        data: [
				<?php
				echo substr($tvlc, 0, -1);
				?>
		        ]
		    }]
		});
    });
</script>