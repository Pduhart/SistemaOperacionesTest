<?php 
session_start(); 
require "../../config.php"; 

date_default_timezone_set('America/Santiago');

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Ventas - Informe</title>
<!-- DataTables -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css"> -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
.container-fluid .content .filtros .form-control {
    display: block;
    width: 100%;
    height: 24px;
    padding: 8px 4px;
    font-size: 12px;
    line-height: 1.3;
    height: 35px;
}

.container-fluid .content .input-group .form-control.chico {
    display: block;
    width: 100%;
    /*height: 24px;*/
    padding: 3px 4px;
    font-size: 12px;
    line-height: 1.3;
    height: 24px;
}

.container-fluid .content .filtros .form-control.chico {
    display: block;
    width: 100%;
    padding: 3px 4px;
    font-size: 12px;
    line-height: 1.3;
    height: 24px;
}

.filtros .input-group-addon {
    padding: 4px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center;
    background-color: #eee;
    border: 1px solid #ccc;
    border-radius: 0px;
}

#contenedor_filtro .label {
    display: inline;
    padding: .6em .8em .6em;
    font-size: 80%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
}

.bg-grays{
  background-color: #e8f0f5;
}

.filtros label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 0px;
    font-weight: 600;
    font-size: 90%;
}

h4.titulo_informe{
  margin-top: 0;
}

.form-group.filtrar {
    margin-bottom: 0px;
    padding-top: 20px;
}

.container-fluid .content .form-control {
    display: inline-block;
    width: auto;
}

.info-box-number2 {
    display: inline-block;
    font-weight: normal;
    font-size: 15px;
    margin-top: 10px;
}
</style>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Ventas
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Ventas</li>
            <li class="active">informe</li>
        </ol>
      </section>

      <!-- Main content -->
    <section class="content">
      	<div class="col-sm-12">
            <!-- general form elements -->
	        <div class="row">
	            <div class="col-md-12">
	                <!-- Custom Tabs -->
	                <div class="nav-tabs-custom">
	                    <ul class="nav nav-tabs">
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
	                                proceso.opcion_pro = 19 AND
	                                proceso.id_pro = usu.id_pro AND
	                                proceso.id_mod = 1
	                            ";
	                        $conexion->consulta($consulta);
	                        $cantidad_opcion = $conexion->total();
	                        if($cantidad_opcion > 0){
	                            ?>
	                            <li><a href="grafico.php">GRAFICO PERFIL</a></li>
	                            <?php
	                        }

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
	                                proceso.opcion_pro = 20 AND
	                                proceso.id_pro = usu.id_pro AND
	                                proceso.id_mod = 1
	                            ";
	                        $conexion->consulta($consulta);
	                        $cantidad_opcion = $conexion->total();
	                        if($cantidad_opcion > 0){
	                            ?>
	                            <li><a href="grafico_recuperacion.php">GRAFICO RECUPERACIÓN</a></li>
	                            <?php
	                        }

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
	                                proceso.opcion_pro = 22 AND
	                                proceso.id_pro = usu.id_pro AND
	                                proceso.id_mod = 1
	                            ";
	                        $conexion->consulta($consulta);
	                        $cantidad_opcion = $conexion->total();
	                        if($cantidad_opcion > 0){
	                            ?>
	                            <li class="active"><a href="grafico_vendedores.php">GRAFICO COTIZACIONES</a></li>
	                            <?php
	                        }
	                        ?>
	                    </ul>
						<div class="tab-content">
	                        <div class="tab-pane active" id="tab_1">
	                        	<div class="box-body">
	                        		<div class="row">
                                        <div id="contenedor_opcion"></div>
                                        <div class="col-sm-12 filtros">
                                            <div class="row">
                                                
                                                <div class="col-sm-5">
                                                    
                                                    <!-- <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="condominio">Condominio:</label>
                                                               <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> 
                                                            <select class="form-control chico" id="condominio" name="condominio"> 
                                                                <option value="">Seleccione Condominio</option>
                                                                <?php  
                                                                // $consulta = "SELECT id_con, nombre_con FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
                                                                // $conexion->consulta($consulta);
                                                                // $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                                // if(is_array($fila_consulta_condominio_original)){
                                                                //     foreach ($fila_consulta_condominio_original as $fila) {
                                                                        ?>
                                                                        <option value="<?php //echo $fila['id_con'];?>"><?php //echo utf8_encode($fila['nombre_con']);?></option>
                                                                        <?php
                                                                    // }
                                                                // }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="mes">Mes:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="mes" name="mes"> 
                                                                <option value="">Seleccione Mes</option>
                                                                <?php  
                                                                $consulta = "SELECT id_mes, nombre_mes FROM mes_mes ORDER BY id_mes";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_mes_original = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_mes_original)){
                                                                    foreach ($fila_consulta_mes_original as $fila) {
                                                                        ?>
                                                                        <option value="<?php echo $fila['id_mes'];?>"><?php echo utf8_encode($fila['nombre_mes']);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="vend_fil">Vendedor:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="vend_fil" name="vend_fil"> 
                                                                <option value="">Todos</option>
                                                                <?php  
                                                                $consulta = "SELECT 
																    		id_vend,
																    		nombre_vend,
																    		apellido_paterno_vend 
																    	FROM 
																    		vendedor_vendedor
																    	WHERE
																    		id_est_vend = 1 AND NOT
																    		id_vend IN (5,12)";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_vend_original = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_vend_original)){
                                                                    foreach ($fila_consulta_vend_original as $fila_vend) {
                                                                        ?>
                                                                        <option value="<?php echo $fila_vend['id_vend'];?>"><?php echo utf8_encode($fila_vend['nombre_vend']." ".$fila_vend['apellido_paterno_vend']);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                               
                                                <div class="col-sm-1 text-center">
                                                  <div class="form-group filtrar">
                                                      <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div id="resultado" class="text-center"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" id="contenedor_filtro">
                                            <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button>
                                            <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                              <i>Filtro: 
                                                <?php 
                                                $filtro_consulta = '';
                                                $elimina_filtro = 0;
                                                
                                                

                                                // if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                                                //     $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                                                //     $fila_consulta_condominio = array();
                                                //     foreach($it as $v) {
                                                //         $fila_consulta_condominio[]=$v;
                                                //     }
                                                //     $elimina_filtro = 1;
                                                    
                                                //     if(is_array($fila_consulta_condominio)){
                                                //         foreach ($fila_consulta_condominio as $fila) {
                                                //             if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                //                 $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                //                 $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                //             }
                                                //         }
                                                //     }
                                                    ?>
                                                    <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span>    -->
                                                    <?php
                                                    // $filtro_consulta .= " AND con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                // }
                                                // else{
                                                    ?>
                                                    <!-- <span class="label label-default">Sin filtro</span>   -->
                                                    <?php       
                                                // }

                                                if(isset($_SESSION["sesion_filtro_mes_cot_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_mes_original));
                                                    $fila_consulta_mes = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_mes[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_mes)){
                                                        foreach ($fila_consulta_mes as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_mes_cot_panel"],$fila_consulta_mes)){
                                                                $key = array_search($_SESSION["sesion_filtro_mes_cot_panel"], $fila_consulta_mes);
                                                                $texto_filtro = $fila_consulta_mes[$key + 1];
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                    <?php
                                                    $filtro_consulta .= " MONTH(cot.fecha_cot) = ".$_SESSION["sesion_filtro_mes_cot_panel"];
                                                    $mes_grafico = $_SESSION["sesion_filtro_mes_cot_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span>  
                                                    <?php
                                                    $filtro_consulta .= " MONTH(cot.fecha_cot) = ".date("m"); 
                                                    $mes_grafico = date("m");

                                                    setlocale(LC_ALL, 'spanish');
													$monthNum  = $mes_grafico;
													$dateObj   = DateTime::createFromFormat('!m', $monthNum);
													$monthName = strftime('%B', $dateObj->getTimestamp());
													$texto_filtro = $monthName;

                                                }

                                                if(isset($_SESSION["sesion_filtro_vendedor_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vend_original));
                                                    $fila_consulta_vend = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_vend[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_vend)){
                                                        foreach ($fila_consulta_vend as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_vendedor_panel"],$fila_consulta_vend)){
                                                                $key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vend);
                                                                $texto_filtro = $fila_consulta_vend[$key + 1];
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                    <?php
                                                    $filtro_consulta .= " AND cot.id_vend = ".$_SESSION["sesion_filtro_vendedor_panel"];
                                                    $filtro_busca_vend = "id_vend IN (".$_SESSION["sesion_filtro_vendedor_panel"].")";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span>  
                                                    <?php
                                                    $filtro_consulta .= "";
                                                    $filtro_busca_vend = "id_vend NOT IN (5,12)";
                                                }


                                                

                                                if ($elimina_filtro<>0) {
                                                  ?>
                                                  <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i> 
                                                  <?php
                                                }

                                                ?>
                                                
                                            </i>
                                       	</h6>
                                    </div>
	                        	</div>
								<div class="col-md-12">
                                    <div class="row" id="contenedor_tabla">
                                        <div class="box box-solid">
                                            <div class="box-header">
                                                <h3 class="box-title">Cotizaciones</h3>
                                                
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
												<div class="container-fluid">
													<div class="row">
					                        			<div class="col-md-12">
					                        				<div class="box box-success">
					                        					<div class="box-body row">
					                        						<div class="col-md-9">
					                        							<div id="genero" style="width: 100%; min-height: 450px; max-width: 1024px; margin: 0 auto"></div>
					                        						</div>
					                        						<div class="col-md-3">
					                        							<?php
																		$year = date("Y");
					                        							 ?>	
					                        							<table class="table">
					                        								<thead>
					                        									<tr>
					                        										<th colspan="2">Totales Mes <?php echo utf8_encode($texto_filtro);?> - <?php echo $year; ?></th>
					                        									</tr>
					                        								</thead>
					                        								<?php 
																			  	$total_cot_mes = 0;
																		    	$consulta_vend = 
																			    	"
																			    	SELECT 
																			    		id_vend,
																			    		nombre_vend,
																			    		apellido_paterno_vend 
																			    	FROM 
																			    		vendedor_vendedor
																			    	WHERE
																			    		id_est_vend = 1 AND NOT
																			    		id_vend IN (5,12)
																			    	";
																			    $conexion->consulta($consulta_vend);
																			    $fila_consulta = $conexion->extraer_registro();
																			    if(is_array($fila_consulta)){
																			        foreach ($fila_consulta as $fila_vend) {
																						$id_vend = $fila_vend['id_vend'];
																						$nombre_vend = utf8_encode($fila_vend['nombre_vend']);
																						$apellido_paterno_vend = utf8_encode($fila_vend['apellido_paterno_vend']);

																						$consulta_cots = 
																					        "
																					        SELECT 
																					            cot.id_cot
																					        FROM 
																					            cotizacion_cotizacion AS cot
																					        WHERE 
																					            cot.id_vend = ".$id_vend." AND
																					            (cot.id_est_cot <> 3 && cot.id_est_cot <> 2) AND
																					            YEAR(cot.fecha_cot) = ".$year." AND
																					            ".$filtro_consulta."
																					        ";
																					    // echo $consulta_cots;
																					    $conexion->consulta($consulta_cots);
																					    $cantidad_mes = $conexion->total();
																					    $cant_mes_cots = $cantidad_mes;
																					    $total_cot_mes = $total_cot_mes + $cant_mes_cots;
																					    ?>
																						<tr>
								                        									<td><?php echo $nombre_vend." ".$apellido_paterno_vend ?></td>
								                        									<td><?php echo $cant_mes_cots; ?></td>
								                        								</tr>
																					    <?php
																					}
																				}
					                        								 ?>
					                        								 <tr class="info">
					                        								 	<td><b>Total Mes</b></td>
					                        								 	<td><b><?php echo $total_cot_mes; ?></b></td>
					                        								 </tr>
					                        							</table>
																		<h3 style="font-size: 2rem; margin-top: 25px">Canales de Llegada del Mes</h3>
					                        							<div id="canal" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        						</div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                            	</div>
												</div>
                                            </div>
                                            <!-- /.box-body -->

                                            <div class="box-body no-padding">
												<div class="container-fluid">
													<div class="row">
					                        			<div class="col-md-12">
					                        				<div class="box box-success">
					                        					<div class="box-body row">
					                        						<div class="col-md-12">
					                        							<div id="historico" style="width: 100%; min-height: 450px; max-width: 1170px; margin: 0 auto"></div>
					                        						</div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                            	</div>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
	                        </div>
	                    </div>
	                    <!-- nav-tabs-custom -->
	                </div>
	            </div>
	            <!-- /.box -->
	        </div>
	    </div>
	    <?php 
	  //   	$year = date("Y");
	  //   	$consulta_vend = 
		 //    	"
		 //    	SELECT 
		 //    		id_vend,
		 //    		nombre_vend,
		 //    		apellido_paterno_vend 
		 //    	FROM 
		 //    		vendedor_vendedor
		 //    	WHERE
		 //    		id_est_vend = 1 AND
		 //    		id_vend IN (4,10,13,14)
		 //    	";
		 //    $conexion->consulta($consulta_vend);
		 //    $fila_consulta = $conexion->extraer_registro();
		 //    if(is_array($fila_consulta)){
		 //        foreach ($fila_consulta as $fila_vend) {
			// 		$id_vend = $fila_vend['id_vend'];
			// 		$nombre_vend = utf8_encode($fila_vend['nombre_vend']);
			// 		$apellido_paterno_vend = utf8_encode($fila_vend['apellido_paterno_vend']);

			// 		for ($i=1; $i < 32; $i++) { 
			// 			$consulta_cots = 
			// 		        "
			// 		        SELECT 
			// 		            cot.id_cot
			// 		        FROM 
			// 		            cotizacion_cotizacion AS cot
			// 		        WHERE 
			// 		            cot.id_vend = ".$id_vend." AND
			// 		            DAYOFMONTH(cot.fecha_cot) = ".$i." AND
			// 		            YEAR(cot.fecha_cot) = ".$year." AND
			// 		            ".$filtro_consulta."
			// 		        ";
			// 		    echo $consulta_cots;
			// 		    $conexion->consulta($consulta_cots);
			// 		    $cantidad = $conexion->total();
			// 		    $cant_day_cots = $cantidad;
			// 		    echo "-->".$cant_day_cots."<br>";
			// 		}
			// 	}
			// }
 		?>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>



  <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!-- DataTables -->
<!-- <script src="<?php// echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script> -->
<!-- <script src="<?php// echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script> -->
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
    	Highcharts.setOptions({
            lang: {
                decimalPoint: ',',
                thousandsSep: '.'
            },
            credits: {
                enabled: false
            }
        });

    	Highcharts.chart('genero', {
    		chart: {
    			width: 1000,
		        height: (9 / 16 * 100) + '%' // 16:9 ratio
		    },
		    title: {
		        text: 'Cotizaciones por día / Vendedor'
		    },
		    yAxis: {
		        title: {
		            text: 'Cantidad Cotizaciones'
		        }
		    },
		    xAxis: {
				type: 'datetime',
				tickInterval: 24 * 3600 * 1000,
				dateTimeLabelFormats: {
		            day: '%e'
		        },
		        labels: {
		            step: 1
		        }
			},
			tooltip: {
				xDateFormat: '%d-%m-%Y',
	            shared: true
	        },
		    series: [
		    	<?php 
				$year = date("Y");
		    	$consulta_vend = 
			    	"
			    	SELECT 
			    		id_vend,
			    		nombre_vend,
			    		apellido_paterno_vend 
			    	FROM 
			    		vendedor_vendedor
			    	WHERE
			    		id_est_vend = 1 AND
			    		".$filtro_busca_vend;
			    		
			    $conexion->consulta($consulta_vend);
			    $cantidad_vend = $conexion->total();
			    $contador_vend = 1;
			    $fila_consulta = $conexion->extraer_registro();
			    if(is_array($fila_consulta)){
			        foreach ($fila_consulta as $fila_vend) {
						$id_vend = $fila_vend['id_vend'];
						$nombre_vend = utf8_encode($fila_vend['nombre_vend']);
						$apellido_paterno_vend = utf8_encode($fila_vend['apellido_paterno_vend']);
		    	 		?>
		        		{
		        		pointStart: Date.UTC(<?php echo date("Y"); ?>, <?php echo $mes_grafico-1; ?>, 1), 
		        		pointInterval: 24 * 3600 * 1000,
			        	name: '<?php echo $nombre_vend." ".$apellido_paterno_vend; ?>',
				        data: [
					        	<?php
								for ($i=1; $i < 32; $i++) { 
									$consulta_cots = 
								        "
								        SELECT 
								            cot.id_cot
								        FROM 
								            cotizacion_cotizacion AS cot
								        WHERE 
								            cot.id_vend = ".$id_vend." AND
								            (cot.id_est_cot <> 3 && cot.id_est_cot <> 2) AND
								            DAYOFMONTH(cot.fecha_cot) = ".$i." AND
								            YEAR(cot.fecha_cot) = ".$year." AND
								            ".$filtro_consulta."
								        ";
								    $conexion->consulta($consulta_cots);
								    $cantidad = $conexion->total();
								    $cant_day_cots = $cantidad;
								    if ($i<31) {
										 echo $cant_day_cots.",";
								    } else {
										echo $cant_day_cots;
								    }
								}
					        	?>
				        	]
		    			}
		    		<?php
		    			if($contador_vend < $cantidad_vend){
		    				echo ",";
		    			} else {
							echo "";
		    			}
		    		$contador_vend++;
					}
				}
		    	?>
		    ],

		    responsive: {
		        rules: [{
		            condition: {
		                maxWidth: 1000
		            },
		            chartOptions: {
		                legend: {
		                    layout: 'horizontal',
		                    align: 'center',
		                    verticalAlign: 'bottom'
		                }
		            }
		        }]
		    }

		});

		// GRÁFICO 7
        Highcharts.chart('canal', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f} %',
                        distance: 5,
                        filter: {
		                    property: 'percentage',
		                    operator: '>',
		                    value: 1
		                },
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Canal',
                colorByPoint: true,
                data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(cot.id_cot),0) AS cantidad,
                            cot_can.id_can_cot,
                            cot_can.nombre_can_cot
                        FROM 
                            cotizacion_canal_cotizacion AS cot_can
                            INNER JOIN cotizacion_cotizacion AS cot ON cot_can.id_can_cot = cot.id_can_cot
                        WHERE 
                            (cot.id_est_cot <> 3 && cot.id_est_cot <> 2) AND
                            YEAR(cot.fecha_cot) = ".$year." AND
                            ".$filtro_consulta."
                        GROUP BY 
                            cot_can.id_can_cot 
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_can_cot']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_can_cot']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
            }]
        });


        // Gráfico Histórico
        Highcharts.chart('historico', {
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: 'Histórico Anual Cotizaciones por Vendedor'
		    },
		    xAxis: {
		        categories: [
		            'Ene',
		            'Feb',
		            'Mar',
		            'Abr',
		            'May',
		            'Jun',
		            'Jul',
		            'Ago',
		            'Sep',
		            'Oct',
		            'Nov',
		            'Dic'
		        ],
		        crosshair: true
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Cantidad Cotizaciones'
		        }
		    },
		    tooltip: {
		        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
		        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
		            '<td style="padding:0"><b>{point.y} cot</b></td></tr>',
		        footerFormat: '</table>',
		        shared: true,
		        useHTML: true
		    },
		    plotOptions: {
		        column: {
		            pointPadding: 0.2,
		            borderWidth: 0
		        }
		    },
		    // series: [{
		    //     name: 'María José Callejas',
		    //     data: [39, 45, 35, 47, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

		    // }, {
		    //     name: 'New York',
		    //     data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

		    // }, {
		    //     name: 'London',
		    //     data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

		    // }, {
		    //     name: 'Berlin',
		    //     data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

		    // }]
		    series: [
				<?php 
				$year = date("Y");
		    	$consulta_vend = 
			    	"
			    	SELECT 
			    		id_vend,
			    		nombre_vend,
			    		apellido_paterno_vend 
			    	FROM 
			    		vendedor_vendedor
			    	WHERE
			    		id_est_vend = 1 AND
			    		".$filtro_busca_vend;
			    		
			    $conexion->consulta($consulta_vend);
			    $cantidad_vend = $conexion->total();
			    $contador_vend = 1;
			    $fila_consulta = $conexion->extraer_registro();
			    if(is_array($fila_consulta)){
			        foreach ($fila_consulta as $fila_vend) {
						$id_vend = $fila_vend['id_vend'];
						$nombre_vend = utf8_encode($fila_vend['nombre_vend']);
						$apellido_paterno_vend = utf8_encode($fila_vend['apellido_paterno_vend']);
		    	 		?>
		        		{
			        	name: '<?php echo $nombre_vend." ".$apellido_paterno_vend; ?>',
				        data: [
					        	<?php
								for ($i=1; $i < 13; $i++) { 
									$consulta_cots = 
								        "
								        SELECT 
								            cot.id_cot
								        FROM 
								            cotizacion_cotizacion AS cot
								        WHERE 
								            cot.id_vend = ".$id_vend." AND
								            (cot.id_est_cot <> 3 && cot.id_est_cot <> 2) AND
								            MONTH(cot.fecha_cot) = ".$i." AND
								            YEAR(cot.fecha_cot) = ".$year."
								        ";
								    $conexion->consulta($consulta_cots);
								    $cantidad_his = $conexion->total();
								    $cant_his_cots = $cantidad_his;
								    if ($i<12) {
										 echo $cant_his_cots.",";
								    } else {
										echo $cant_his_cots;
								    }
								}
					        	?>
				        	]
		    			}
		    		<?php
		    			if($contador_vend < $cantidad_vend){
		    				echo ",";
		    			} else {
							echo "";
		    			}
		    		$contador_vend++;
					}
				}
		    	?>
		    ]
		});
		

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_mes = $('#mes').val();
            var_vend = $('#vend_fil').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"mes="+var_mes+"&vendedor="+var_vend,
                success: function(data) {
                    location.reload();
                }
            })
        });

        $(document).on( "click",".borra_sesion" , function() {
            // $('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            $.ajax({
                type: 'POST',
                url: ("filtro_delete.php"),
                // data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&vehiculo="+var_vehiculo,
                success: function(data) {
                    location.reload();
                }
            })
        });
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        
    });
</script>
</body>
</html>
