<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";

$estado_venta = 3; //promesadas
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
	                            <li class="active"><a href="grafico.php">GRAFICO PERFIL</a></li>
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
	                            <li><a href="grafico_vendedores.php">GRAFICO COTIZACIONES</a></li>
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
                                                    
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="condominio">Condominio:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="condominio" name="condominio"> 
                                                                <option value="">Seleccione Condominio</option>
                                                                <option value="100">TODO Condominio Pacífico 2800</option>
                                                                <?php  
                                                                $consulta = "SELECT id_con, nombre_con FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_condominio_original)){
                                                                    foreach ($fila_consulta_condominio_original as $fila) {
                                                                        ?>
                                                                        <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
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
                                                    if ($_SESSION["sesion_filtro_condominio_panel"]<>100) {
                                                    	$texto_filtro = $texto_filtro;
                                                    } else {
                                                    	$texto_filtro = "Cond. Pac&iacute;fico 2800";
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                    <?php
                                                    if ($_SESSION["sesion_filtro_condominio_panel"]<>100) {
                                                    	$filtro_consulta .= " AND con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    } else {
                                                    	$filtro_consulta .= " AND (con.id_con = 4 OR con.id_con = 5)";
                                                    }
                                                    
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span>  
                                                    <?php       
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
                                                <h3 class="box-title">Perfíl del Cliente - Sobre Promesas Ingresadas | <a class="btn btn-xs btn-primary" href="<?php echo _MODULO?>informe/clientes_graficos_export.php?fil=<?php echo base64_encode(base64_encode($filtro_consulta)); ?>" target="_blank">Exportar Listado Clientes</a></h3>
                                                
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
												<div class="container-fluid">
													<div class="row">
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Género</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
					                        						<div id="genero" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Grupo Etareo</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
					                        						<div id="etareo" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Estado Civil</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
					                        						<div id="civil" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Profesión</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
                                                                    <div id="profesion" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Región Procedencia</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
                                                                    <div id="procedencia" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Comunas de Procedencia <small>10 principales</small></h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
                                                                    <div id="comunas" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Cuenta Corriente</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
                                                                    <div id="cuenta_corriente" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                        			<div class="col-md-4">
					                        				<div class="box box-success">
					                        					<div class="box-header with-border">
					                        						<h3 class="box-title">Canal de Llegada</h3>
					                        						<div class="box-tools pull-right">
					                        							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					                        							</button>
					                        							<!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
					                        						</div>
					                        					</div>
					                        					<div class="box-body">
					                        						<div id="canal" style="width: 100%; height: 400px; max-width: 600px; margin: 0 auto"></div>
					                        					</div>
					                        					<!-- /.box-body -->
					                        				</div>
					                        			</div>
					                            	</div>
												</div>
                                            </div>
                                            <!-- /.box-body -->
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
    </section>
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
  <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
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
		        plotBackgroundColor: null,
		        plotBorderWidth: null,
		        plotShadow: false,
		        type: 'pie'
		    },
		    title: {
		        text: ''
		    },
		    tooltip: {
		        pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: false,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            },
		            showInLegend: true
		        }
		    },
		    series: [{
                name: 'Género',
		        colorByPoint: true,
		        data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            sex.id_sex,
                            sex.nombre_sex
                        FROM 
                            sexo_sexo AS sex
                            INNER JOIN propietario_propietario AS pro ON sex.id_sex = pro.id_sex
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            sex.id_sex 
                        ";

                    // echo $consulta;
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_sex']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_sex']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                ]
		    }]
		});
		// GRAFICO 2
		Highcharts.chart('etareo', {
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
		        pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: false,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            },
		            showInLegend: true
		        }
		    },
		    series: [{
		        name: 'Grupo Etáreo',
		        colorByPoint: true,
		        data: [
                    <?php 
                    $consulta = 
                        "
                        SELECT 
                            pro.id_pro,
                            pro.fecha_nacimiento_pro
                        FROM 
                            propietario_propietario AS pro
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            pro.id_pro,
                            pro.fecha_nacimiento_pro
                        ";
                    $conexion->consulta($consulta);
                    $cantidad_venta_grupo_etareo = $conexion->total();
                    $fila_consulta_propietario = $conexion->extraer_registro();

                    if($cantidad_venta_grupo_etareo > 0){
                        $consulta = 
                            "
                            SELECT 
                                *
                            FROM 
                                grupo_grupo
                            ORDER BY 
                                id_gru
                            ";
                        $conexion->consulta($consulta);
                        $cantidad = $conexion->total();
                        $fila_consulta = $conexion->extraer_registro();
                        $contador = 1;
                        if(is_array($fila_consulta)){
                            foreach ($fila_consulta as $fila) {
                                $cantidad_grupo = 0;
                                if(is_array($fila_consulta_propietario)){
                                    foreach ($fila_consulta_propietario as $fila_propietario) {
                                        $fecha_nacimiento = $fila_propietario["fecha_nacimiento_pro"];

                                        $dia=date("d");
                                        $mes=date("m");
                                        $ano=date("Y");


                                        $dianaz=date("d",strtotime($fecha_nacimiento));
                                        $mesnaz=date("m",strtotime($fecha_nacimiento));
                                        $anonaz=date("Y",strtotime($fecha_nacimiento));


                                        if (($mesnaz == $mes) && ($dianaz > $dia)) {
                                            $ano=($ano-1); 
                                        }

                                        if ($mesnaz > $mes) {
                                            $ano=($ano-1);
                                        }

                                        $edad=($ano-$anonaz);
                                        
                                        if($edad >= $fila["desde_gru"] && $edad <= $fila["hasta_gru"]){
                                            $cantidad_grupo++;
                                        }

                                    }
                                }
                                if($contador < $cantidad){
                                    echo utf8_encode("{ name: '".$fila['nombre_gru']."', y: ".$cantidad_grupo."},");
                                }
                                else{
                                    echo utf8_encode("{ name: '".$fila['nombre_gru']."', y: ".$cantidad_grupo."}");
                                }
                                $contador++;
                            }
                        }
                    }
                    ?>
                    
                ]
		    }]
		});
		// GRÁFICO 3
		Highcharts.chart('civil', {
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
		        pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: false,
		            cursor: 'pointer',
		            dataLabels: {
		                enabled: true,
		                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                style: {
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                }
		            },
		            showInLegend: true
		        }
		    },
		    series: [{
		        name: 'Estado Civil',
		        colorByPoint: true,
		        data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            civ.id_civ,
                            civ.nombre_civ
                        FROM 
                            civil_civil AS civ
                            INNER JOIN propietario_propietario AS pro ON civ.id_civ = pro.id_civ
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            civ.id_civ 
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_civ']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_civ']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
		    }]
		});

        // GRÁFICO 4
        Highcharts.chart('profesion', {
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
                pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Profesión',
                colorByPoint: true,
                data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            prof.id_prof,
                            prof.nombre_prof
                        FROM 
                            profesion_profesion AS prof
                            INNER JOIN propietario_propietario AS pro ON prof.id_prof = pro.id_prof
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            prof.id_prof 
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_prof']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_prof']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
            }]
        });

        // GRÁFICO 5
        Highcharts.chart('procedencia', {
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
                pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Región de Prodecencia',
                colorByPoint: true,
                data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            reg.id_reg,
                            reg.descripcion_reg
                        FROM 
                            region_region AS reg
                            INNER JOIN propietario_propietario AS pro ON reg.id_reg = pro.id_reg
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            reg.id_reg
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['descripcion_reg']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['descripcion_reg']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
            }]
        });


        // GRÁFICO 6
        Highcharts.chart('cuenta_corriente', {
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
                pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Cuenta Corriente',
                colorByPoint: true,
                data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            ban.id_ban,
                            ban.nombre_ban
                        FROM 
                            banco_banco AS ban
                            INNER JOIN venta_venta AS ven ON ban.id_ban = ven.id_ban
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            ban.id_ban 
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_ban']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_ban']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
            }]
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
                pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
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
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            cot_can.id_can_cot,
                            cot_can.nombre_can_cot
                        FROM 
                            cotizacion_canal_cotizacion AS cot_can
                            INNER JOIN cotizacion_cotizacion AS cot ON cot_can.id_can_cot = cot.id_can_cot
                            INNER JOIN venta_venta AS ven ON cot.id_cot = ven.cotizacion_ven
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                            ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            cot_can.id_can_cot 
                        ";
                    // echo $consulta;
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

        // GRÁFICO 5
        Highcharts.chart('comunas', {
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
                pointFormat: 'Total: <b>{point.y}</b><br>{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Región de Prodecencia',
                colorByPoint: true,
                data: [
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            IFNULL(COUNT(ven.id_ven),0) AS cantidad,
                            com.id_com,
                            com.nombre_com
                        FROM 
                            comuna_comuna AS com
                            INNER JOIN propietario_propietario AS pro ON com.id_com = pro.id_com
                            INNER JOIN venta_venta AS ven ON pro.id_pro = ven.id_pro
                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        WHERE 
                             ven.id_est_ven > ".$estado_venta." 
                            ".$filtro_consulta."
                        GROUP BY 
                            com.id_com
                        ORDER BY cantidad DESC LIMIT 0,10
                        ";
                    $conexion->consulta($consulta);
                    $cantidad = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    $contador = 1;
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($contador < $cantidad){
                                echo utf8_encode("{ name: '".$fila['nombre_com']."', y: ".$fila['cantidad']."},");
                            }
                            else{
                                echo utf8_encode("{ name: '".$fila['nombre_com']."', y: ".$fila['cantidad']."}");
                            }
                            $contador++;
                        }
                    }
                    ?>
                    
                ]
            }]
        });

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_condominio = $('#condominio').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"condominio="+var_condominio,
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
