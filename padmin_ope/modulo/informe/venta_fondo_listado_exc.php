<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

$nombre = 'listado_FPM_'.date('d-m-Y');

// echo $_SESSION["sesion_id_panel"];
// echo $_SESSION["sesion_usuario_panel"];

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<title>Ventas - Informe</title>
<!-- DataTables -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body>
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">

    <section class="content">
        <div class="col-sm-12">
            <!-- general form elements -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <!-- <div class="nav-tabs-custom"> -->
                        
                        <?php  
                        $consulta = 
                            "
                            SELECT 
                                usu.id_mod 
                            FROM 
                                usuario_usuario_proceso AS usu,
                                usuario_proceso AS proceso
                            WHERE 
                                usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                usu.id_mod = 1 AND
                                proceso.opcion_pro = 18 AND
                                proceso.id_pro = usu.id_pro AND
                                proceso.id_mod = 1
                            ";
                        $conexion->consulta($consulta);
                        $cantidad_opcion = $conexion->total();
                        if($cantidad_opcion > 0){
                            ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                    	<table>
                                    		<tr>
                                    			<td></td>
                                    			<td rowspan="6">
                                    				<img src="<?php echo _ASSETS."img/logo-icp.jpg";?>" width="103" height="90">
                                    			</td>
                                    			<td rowspan="6" colspan="2">
                                    				<?php 
													$consulta = 
									                    "
									                    SELECT
									                        nombre_doc_con
									                    FROM 
									                        condominio_documento_condominio
									                    WHERE 
									                        id_con = ? AND
									                        nombre_doc_con LIKE '%logo%'
									                    ";
									                $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_condominio_panel"]));
									                $haylogo = $conexion->total();
									                if ($haylogo==0) {
									                	
									                	
									                } else{
									                	$fila = $conexion->extraer_registro_unico();
									                	$nombre_doc_con = $fila["nombre_doc_con"];
									                	?>
									                	<img src="<?php echo _RUTA."archivo/condominio/documento/".$_SESSION['sesion_filtro_condominio_panel']."/".$nombre_doc_con;?>" height="90">
									                	<?php
									                }
													 ?>
                                    			</td>
                                    		</tr>
                                    	</table>
                                        <!-- <div class="row"> -->
                                            <!-- <div id="contenedor_opcion"></div> -->
                                            <!-- <div class="col-sm-12 filtros"> -->
                                                <!-- <div class="row"> -->
                                                    <!-- <div class="col-sm-5"> -->
                                                        
                                                        <!-- <div class="col-sm-4"> -->
                                                            <!-- <div class="form-group"> -->
                                                                <!-- <label for="condominio">Condominio:</label> -->
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <!-- <select class="form-control chico" id="condominio" name="condominio">  -->
                                                                    <!-- <option value="">Seleccione Condominio</option> -->
                                                                    <?php  
                                                                    $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_condominio_original)){
                                                                        foreach ($fila_consulta_condominio_original as $fila) {
                                                                            ?>
                                                                            <!-- <option value="<?php //echo $fila['id_con'];?>"><?php //echo utf8_encode($fila['nombre_con']);?></option> -->
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                <!-- </select> -->
                                                            <!-- </div> -->
                                                        <!-- </div> -->

                                                        

                                                    <!-- </div> -->
                                                    
                                                    <!-- <div class="col-sm-1 text-center"> -->
                                                      <!-- <div class="form-group filtrar"> -->
                                                          <!-- <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input> -->
                                                      <!-- </div> -->
                                                    <!-- </div> -->
                                                <!-- </div> -->
                                                <!-- <div class="row"> -->
                                                    <!-- <div class="col-sm-5"> -->
                                                        <!-- <div id="resultado" class="text-center"></div> -->
                                                    <!-- </div> -->
                                                <!-- </div> -->

                                                
                                            <!-- </div> -->
                                            
                                            
                                            
                                        <!-- </div> -->
                                        <!-- <div class="row"> -->
                                            <!-- <div class="col-sm-12" id="contenedor_filtro"> -->
                                                <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
                                                <!-- <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px"> -->
                                                  <!-- <i>Filtro:  -->
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $filtro_consulta_cierre = '';
                                                    $elimina_filtro = 0;
                                                    
                                                    


                                                    if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                                                        $fila_consulta_condominio = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_condominio[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_condominio)){
                                                            foreach ($fila_consulta_condominio as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                                    $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                    $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span>   -->
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= " AND cie.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span>  -->
                                                        <?php       
                                                    }


                                                    if ($elimina_filtro<>0) {
                                                      ?>
                                                      <!-- <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i>  -->
                                                      <?php
                                                    }

                                                    ?>
                                                    
                                                <!-- </i> -->
                                              <!-- </h6> -->
                                            <!-- </div> -->
                                        <!-- </div> -->

															<?php 
															if (isset($_SESSION["sesion_filtro_condominio_panel"])) {
															// valor a prorratear
															$consultapar = 
														        "
														        SELECT
														            valor_par
														        FROM
														            parametro_parametro
														        WHERE
														            valor2_par = ? AND
														            id_con = ?
														        ";
														    $conexion->consulta_form($consultapar,array(14,$_SESSION["sesion_filtro_condominio_panel"]));
														    $filapar = $conexion->extraer_registro_unico();
														    $porcentaje_prorrateo = utf8_encode($filapar['valor_par']);
															 ?>
																<table class="table table-bordered tabla">
																	<thead>
																		<tr>
																			<th></th>
																			<th>Cliente</th>
																			<th>RUT</th>
																			<th>Depto.</th>
																			<th>Fecha de Firma de Escritura.</th>
																			<th>Fecha de Entrega.</th>																			
																			<th>Valor FPM</th>
																			<th>Fecha Pago Cliente</th>
																			<th>Monto Pago Cliente</th>
																			
																			<!-- <th style="width: 10%">Fecha Tentativa Pag. Adm. Condominio</th> -->
																			<th>Fecha Pago Adm. Condominio</th>
																			<th>Monto Pagado Adm.</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		$contador = 0;
																		$acumula_colores = 0;
																		$acumulado_monto_pago_cliente = 0;
																		$acumulado_monto_pago_adm = 0;
																		$acumulado_total_prorrateo_depto = 0;
									                                    $consulta = "SELECT 
									                                    				viv.nombre_viv,
									                                    				viv.id_viv,
																						viv.prorrateo_viv
									                                    			FROM
									                                    				vivienda_vivienda as viv,
																						torre_torre as tor
																					WHERE
																						viv.id_tor = tor.id_tor AND
																						tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"]."
									                                    			ORDER BY
									                                    				id_pis ASC, nombre_viv ASC";
			                                                            $conexion->consulta($consulta);
			                                                            $fila_consulta = $conexion->extraer_registro();
			                                                            if(is_array($fila_consulta)){
			                                                                foreach ($fila_consulta as $fila) {
			                                                                	$contador++;
			                                                                	$consultaventa = "SELECT 
									                                    				pro.nombre_pro,
									                                    				pro.nombre2_pro,
									                                    				pro.apellido_paterno_pro,
									                                    				pro.apellido_materno_pro,
									                                    				pro.rut_pro,
									                                    				ven.id_ven,
									                                    				ven.id_for_pag
									                                    			FROM
									                                    				venta_venta as ven,
																						propietario_propietario as pro
																					WHERE
																						ven.id_viv = ".$fila['id_viv']." AND
																						ven.id_est_ven > 3 AND
																						ven.id_pro = pro.id_pro";
					                                                            $conexion->consulta($consultaventa);
					                                                            $vendido = $conexion->total();
					                                                            $filaventa = $conexion->extraer_registro_unico();
																				if ($vendido>0) {
																					$id_ven = $filaventa['id_ven'];
																					$id_for_pag = $filaventa['id_for_pag'];
																					$nombre_propietario = $filaventa['nombre_pro']." ".$filaventa['nombre2_pro']." ".$filaventa['apellido_paterno_pro']." ".$filaventa['apellido_materno_pro'];
																					$rut_propietario = $filaventa['rut_pro'];
																					// lo que paga de prorrateo
																					$total_prorrateo_depto = ($fila['prorrateo_viv'] * $porcentaje_prorrateo) / 100;
																					$total_prorrateo_depto = $total_prorrateo_depto*2;
																					$acumulado_total_prorrateo_depto = $acumulado_total_prorrateo_depto + $total_prorrateo_depto;
																					$pagado_valor = $total_prorrateo_depto;
																					$total_prorrateo_depto = number_format($total_prorrateo_depto, 0, ',', '.');
																					// fecha pago
																					// $consultafecha = "SELECT 
										           //                          				fecha_hasta_eta_ven
										           //                          			FROM
										           //                          				venta_etapa_venta
																					// 	WHERE
																					// 		id_ven = ".$id_ven." AND
																					// 		id_est_eta_ven = 1";
																					// if ($id_for_pag==1) {
																					// 	$consultafecha .= " AND id_eta = ".$n_etacr_inf_adm_con."";
																					// } else {
																					// 	$consultafecha .= " AND id_eta = ".$n_etaco_inf_adm_con."";
																					// }
						               //                                              $conexion->consulta($consultafecha);
						               //                                              $filafecha = $conexion->extraer_registro_unico();
						               //                                              $fecha_cierre = $filafecha['fecha_hasta_eta_ven'];
						               //                                              if ($fecha_cierre<>'') {
						               //                                              	$fecha_cierre = date("d-m-Y",strtotime($fecha_cierre));
						               //                                              } else {
						               //                                              	$fecha_cierre = "--";
						               //                                              }

						                                                            // Fecha Pago Adm. Condominio, con etapa cerrada, ver si está la fecha
																					$consultafechareal = "SELECT 
										                                    				cam_eta.valor_campo_eta_cam_ven
										                                    			FROM
										                                    				venta_etapa_venta as ven_eta,
										                                    				venta_etapa_campo_venta as cam_eta
																						WHERE
																							ven_eta.id_ven = ".$id_ven." AND
																							ven_eta.id_est_eta_ven = 1 AND 
																							ven_eta.id_eta_ven = cam_eta.id_eta_ven";
																					if ($id_for_pag==1) {
																						$consultafechareal .= " AND cam_eta.id_cam_eta = ".$n_cam_etacr_fecha_adm_con."";
																					} else {
																						$consultafechareal .= " AND cam_eta.id_cam_eta = ".$n_cam_etaco_fecha_adm_con."";
																					}
						                                                            $conexion->consulta($consultafechareal);
						                                                            $haycampo = $conexion->total();
						                                                            $hay_pagado = 0;
						                                                            if ($haycampo>0) {
						                                                            	$filafreal = $conexion->extraer_registro_unico();
							                                                            $fecha_pago_real = $filafreal['valor_campo_eta_cam_ven'];
							                                                            if ($fecha_pago_real<>'' || $fecha_pago_real<>null) {
							                                                            	$fecha_pago_real = date("d-m-Y",strtotime($fecha_pago_real));
							                                                            	// $hay_pagado = 1;
							                                                            	// $acumula_pagado = $acumula_pagado + $pagado_valor;
							                                                            } else {
							                                                            	$fecha_pago_real = "no cargado";
							                                                            }
						                                                            } else {
						                                                            	$fecha_pago_real = "Etapa no cerrada";
						                                                            }

																				} else {
																					$nombre_propietario = "--";
																					$rut_propietario = "--";
																					// $total_prorrateo_depto = "--";
																					$total_prorrateo_depto = ($fila['prorrateo_viv'] * $porcentaje_prorrateo) / 100;
																					$total_prorrateo_depto = $total_prorrateo_depto*2;
																					$acumulado_total_prorrateo_depto = $acumulado_total_prorrateo_depto + $total_prorrateo_depto;
																					$total_prorrateo_depto = number_format($total_prorrateo_depto, 0, ',', '.');
																					$fecha_cierre = "--";
																					$fecha_pago_real = "--";
																				}
																			$pagado = 0;
																		 	?>
																		<tr>
																			<td><?php echo $contador; ?></td>
																			<td><?php echo $nombre_propietario; ?></td>
																			<td><?php echo $rut_propietario; ?></td>
																			<td><?php echo $fila['nombre_viv']; ?></td>																			
                                                                            <td style="text-align:center;">
                                                                                <?php
                                                                                // Consulta para fecha de escritura
                                                                                if ($vendido>0) {
                                                                                $consultaFirmaEscritura="
                                                                                SELECT
                                                                                    venta.fecha_escritura_ven
                                                                                FROM
                                                                                    venta_venta as venta
                                                                                WHERE
                                                                                    venta.id_ven = ".$id_ven."";                                                                                                                                                                
                                                                                    $conexion->consulta($consultaFirmaEscritura);
                                                                                    $firma = $conexion->extraer_registro_unico();
                                                                                    echo (isset($firma['fecha_escritura_ven']))?$firma['fecha_escritura_ven']:'';                                                                                   
                                                                                }else{
                                                                                    echo '';
                                                                                }
                                                                                ?>
                                                                            </td>
																			<td style="text-align:center;">
                                                                                <?php                                                                               
                                                                                // consulta fecha de entrega
                                                                                // if ($vendido>0) {
                                                                                $consultaEntrega="
                                                                                SELECT 
	                                                                                    ven_eta.fecha_hasta_eta_ven
	                                                                                FROM
	                                                                                    venta_etapa_venta AS ven_eta,
	                                                                                    venta_etapa_campo_venta AS eta_cam_ven
	                                                                                WHERE
	                                                                                    ven_eta.id_ven = ".$id_ven." AND 
	                                                                                    ven_eta.id_eta = 29 AND 
	                                                                                    ven_eta.id_eta_ven = eta_cam_ven.id_eta_ven AND 
	                                                                                    valor_campo_eta_cam_ven <> ''
																						";
                                                                                    $conexion->consulta($consultaEntrega);
                                                                                    $filaentrega = $conexion->extraer_registro_unico();
                                                                                    echo (isset($filaentrega['fecha_hasta_eta_ven']))?$filaentrega['fecha_hasta_eta_ven']:'';                                                                                   
                                                                                // }else{
                                                                                //     echo '';
                                                                                // }
                                                                                ?>
                                                                            </td>
																			<td><?php echo $total_prorrateo_depto; ?></td>
																			<td>
																				<?php
																				if ($vendido>0) {
																					$consultafechapagocliente = "SELECT 
											                                    				fecha_pago_cliente_fondo_expotacion
											                                    			FROM
											                                    				venta_campo_venta
																							WHERE
																								id_ven = ".$id_ven."";
																					// echo $consultafechareal;
																					$conexion->consulta($consultafechapagocliente);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['fecha_pago_cliente_fondo_expotacion']<>'' && $filareal['fecha_pago_cliente_fondo_expotacion']<> null) {
								                                                        echo $fecha_pago_real = date("d-m-Y",strtotime($filareal['fecha_pago_cliente_fondo_expotacion']));
							                                                        } else {
																						echo $fecha_pago_real = "--";
							                                                        }
																				} else {
																				?>

																				<?php
																				}
																				?>
																			</td>
																			<?php
																				$color_celda = ""; 
																				$fecha_pago_real = "";
																				$monto_pago_cliente = "";
																				if ($vendido>0) {
																					$consultafechareal = "SELECT 
											                                    				fecha_pago_fondo_expotacion
											                                    			FROM
											                                    				venta_campo_venta
																							WHERE
																								id_ven = ".$id_ven."";
																					// echo $consultafechareal;
																					$conexion->consulta($consultafechareal);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['fecha_pago_fondo_expotacion']<>'' && $filareal['fecha_pago_fondo_expotacion']<> null) {
							                                                        	$hay_pagado = 1;
							                                                        	$acumula_pagado = $acumula_pagado + $pagado_valor;
								                                                        $fecha_pago_real = date("d-m-Y",strtotime($filareal['fecha_pago_fondo_expotacion']));
							                                                        } else {
																						$fecha_pago_real = "--";
																						$color_celda = "#ffff00";
							                                                        }

																				}																			

																				if ($vendido>0) {
																					$consultamontopagocliente = "
																						SELECT 
										                                    				monto_pago_fpm_cliente_ven
										                                    			FROM
										                                    				venta_campo_venta
																						WHERE
																							id_ven = ".$id_ven."";
																					$conexion->consulta($consultamontopagocliente);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['monto_pago_fpm_cliente_ven']<>'' && $filareal['monto_pago_fpm_cliente_ven']<> null) {
							                                                        	$acumulado_monto_pago_cliente = $acumulado_monto_pago_cliente + $filareal['monto_pago_fpm_cliente_ven'];
								                                                        $monto_pago_cliente = number_format($filareal['monto_pago_fpm_cliente_ven'], 0, ',', '.');
								                                                        if($color_celda<>''){
								                                                        	$acumula_colores = $acumula_colores + $filareal['monto_pago_fpm_cliente_ven'];

								                                                        }
							                                                        } else {
																						$monto_pago_cliente = "";
							                                                        }
																				}
																				 ?>
																			<td style="background-color: <?php echo $color_celda; ?>">
																				<?php echo $monto_pago_cliente; ?>
																			</td>
																			<td>
																				<?php echo $fecha_pago_real; ?>
																			</td>
																			<td><?php
																				if ($vendido>0) {
																					$consultamontopagoadm = "
																						SELECT 
										                                    				monto_pago_fpm_adm_ven
										                                    			FROM
										                                    				venta_campo_venta
																						WHERE
																							id_ven = ".$id_ven."";
																					$conexion->consulta($consultamontopagoadm);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['monto_pago_fpm_adm_ven']<>'' && $filareal['monto_pago_fpm_adm_ven']<> null) {
							                                                        	$acumulado_monto_pago_adm = $acumulado_monto_pago_adm + $filareal['monto_pago_fpm_adm_ven'];
								                                                        echo $monto_pago_adm = number_format($filareal['monto_pago_fpm_adm_ven'], 0, ',', '.');
							                                                        } else {
																						echo $monto_pago_adm = "";
							                                                        }
																				}
																			?></td>
																		</tr>
																		<?php 
																			}
																		}
																		 ?>
																	</tbody>
																	<tfoot>
																		<tr>
																			<th>Total</th>
																			<th></th>
																			<th></th>
																			<th><?php echo number_format($acumulado_total_prorrateo_depto, 0, ',', '.'); ?></th>
																			<th></th>
																			<th><?php echo number_format($acumulado_monto_pago_cliente, 0, ',', '.'); ?></th>
																			<th></th>
																			<th><?php echo number_format($acumulado_monto_pago_adm, 0, ',', '.'); ?></th>
																		</tr>
																	</tfoot>
																</table>

																<table>
																	<tr>
																		<td></td>
																		<td></td>
																		<td></td>
																	</tr>
																	<tr>
																		<td></td>
																		<td colspan="2">
																			<table>
																				<tr>
																					<td colspan="2" style="background-color: #b2c800">Arqueo al xx/xx/xx ( fecha del informe)</td>
																				</tr>
																				<tr>
																					<td>Total FPM</td>
																					<td><?php echo number_format($acumulado_total_prorrateo_depto, 0, ',', '.'); ?></td>
																				</tr>
																				<tr>
																					<td>Pagado a Administracion a la fecha</td>
																					<td><?php echo number_format($acumulado_monto_pago_adm, 0, ',', '.'); ?></td>
																				</tr>
																				<tr>
																					<td>Por pagar a Administracion a la fecha</td>
																					<td><?php echo number_format($acumula_colores, 0, ',', '.'); ?></td>
																				</tr>
																				<tr>
																					<td>Por recuperar</td>
																					<td>
																						<?php 
																						$por_recup = $acumulado_total_prorrateo_depto - ($acumulado_monto_pago_adm + $acumula_colores);
																						echo number_format($por_recup, 0, ',', '.');
																						 ?>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															<?php 
															}
															?>
                                                    <!-- /.box-body -->
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <?php
                        }
                        ?>
                        
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
        </div>
    </section>

      <!-- Main content -->
   	
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
  <!-- /.content-wrapper -->

</body>
</html>
