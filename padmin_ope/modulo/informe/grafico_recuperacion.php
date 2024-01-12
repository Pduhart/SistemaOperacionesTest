<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Ventas - Informe</title>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">

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
	                            <li class="active"><a href="grafico.php">GRAFICO RECUPERACIÓN</a></li>
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
                                            <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
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
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                    <?php
                                                    $filtro_consulta = $_SESSION["sesion_filtro_condominio_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span>  
                                                    <?php    
                                                    $filtro_consulta = 1;
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
                                                <h3 class="box-title">Ventas & Recuperación</h3>
                                                
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body no-padding">
												<div class="container-fluid">
													<div class="row">
					                        			<div class="col-md-12">
					                        				<div id="genero" style="width: 100%; height: 480px; max-width: 100%; margin: 0 auto; padding: 10px"></div>
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
		        pointFormat: '<b>{point.percentage:.1f}%</b><br><b>{point.y:.,2f} UF</b>'
		    },
		    plotOptions: {
		        pie: {
		            allowPointSelect: true,
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
		    <?php
		    // reservas
			$consulta1 = 
			  "
			  SELECT 
			    IFNULL(SUM(ven.monto_reserva_ven),0) AS reservas
			  FROM
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			    ven.id_est_ven > 3 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta1);
			$fila1 = $conexion->extraer_registro_unico();
			$monto_reservas = $fila1["reservas"];
			$monto_reservas = round($monto_reservas,2);

			// disponibles
			$consulta2 = 
                "
                SELECT
                    IFNULL(SUM(viv.valor_viv),0) AS disponible
                FROM
                    torre_torre AS tor
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                WHERE
                    tor.id_con = ".$filtro_consulta." AND
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
            $conexion->consulta($consulta2);
            $fila2 = $conexion->extraer_registro_unico();
			$monto_disponible = $fila2["disponible"];
			$monto_disponible = round($monto_disponible,2);

			// pie cancelado en UF
			$consulta3 = 
			  "
			  SELECT 
			    IFNULL(SUM(pag.monto_pag),0) AS pie_cancelado_uf
			  FROM
			  	pago_pago AS pag,
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	pag.id_ven = ven.id_ven AND
			  	pag.id_est_pag = 1 AND
			  	pag.id_cat_pag = 2 AND
			  	pag.id_for_pag = 6 AND
			    ven.id_est_ven > 3 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta3);
			$fila3 = $conexion->extraer_registro_unico();
			$monto_pie_cancelado_uf = $fila3["pie_cancelado_uf"];

			// pie cancelado en Pesos
			$consulta4 = 
			  "
			  SELECT 
			    pag.monto_pag,
			    ven.fecha_ven,
			    ven.id_ven
			  FROM
			  	pago_pago AS pag,
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	pag.id_ven = ven.id_ven AND
			  	pag.id_est_pag = 1 AND
			  	pag.id_cat_pag = 2 AND
			  	pag.id_for_pag <> 6 AND
			    ven.id_est_ven > 3 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  GROUP BY 
			  	ven.id_ven
			  ";
			$conexion->consulta($consulta4);
			$fila_consulta4 = $conexion->extraer_registro();
			if(is_array($fila_consulta4)){
			    foreach ($fila_consulta4 as $fila4) {
					$monto_pag = $fila4["monto_pag"];
					$fecha_ven = $fila4["fecha_ven"];
					$consulta = 
						"
					    SELECT
					        valor_uf
					    FROM
					        uf_uf
					    WHERE
					        fecha_uf = '".date("Y-m-d",strtotime($fecha_ven))."'
					    ";
					$conexion->consulta($consulta);
					$cantidaduf = $conexion->total();
					if($cantidaduf > 0){
            			$filauf = $conexion->extraer_registro_unico();
						$valor_uf = $filauf["valor_uf"];
						$monto_pie_cancelado_pe_uf = $monto_pag / $valor_uf;
					} else {
						$monto_pie_cancelado_pe_uf = 0; //si no está la uf lo toma 0
					}
					$total_monto_pie_cancelado_pe_uf = $total_monto_pie_cancelado_pe_uf + $monto_pie_cancelado_pe_uf; 
			    }
			}

			// pie NO cancelado en UF
			$consulta5 = 
			  "
			  SELECT 
			    IFNULL(SUM(pag.monto_pag),0) AS pie_nocancelado_uf
			  FROM
			  	pago_pago AS pag,
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	pag.id_ven = ven.id_ven AND
			  	pag.id_est_pag = 2 AND
			  	pag.id_cat_pag = 2 AND
			  	pag.id_for_pag = 6 AND
			    ven.id_est_ven > 3 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta5);
			$fila5 = $conexion->extraer_registro_unico();
			$monto_pie_nocancelado_uf = $fila5["pie_nocancelado_uf"];
			$monto_pie_nocancelado_uf = round($monto_pie_nocancelado_uf,2);

			// pie NOcancelado en Pesos
			$consulta6 = 
			  "
			  SELECT 
			    pag.monto_pag,
			    ven.fecha_ven,
			    ven.id_ven
			  FROM
			  	pago_pago AS pag,
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	pag.id_ven = ven.id_ven AND
			  	pag.id_est_pag = 2 AND
			  	pag.id_cat_pag = 2 AND
			  	pag.id_for_pag <> 6 AND
			    ven.id_est_ven > 3 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  GROUP BY 
			  	ven.id_ven
			  ";
			$conexion->consulta($consulta6);
			$fila_consulta6 = $conexion->extraer_registro();
			if(is_array($fila_consulta6)){
			    foreach ($fila_consulta6 as $fila6) {
					$monto_pag = $fila6["monto_pag"];
					$fecha_ven = $fila6["fecha_ven"];
					$consulta = 
						"
					    SELECT
					        valor_uf
					    FROM
					        uf_uf
					    WHERE
					        fecha_uf = '".date("Y-m-d",strtotime($fecha_ven))."'
					    ";
					$conexion->consulta($consulta);
					$cantidaduf = $conexion->total();
					if($cantidaduf > 0){
            			$filauf = $conexion->extraer_registro_unico();
						$valor_uf = $filauf["valor_uf"];
						$monto_pie_nocancelado_pe_uf = $monto_pag / $valor_uf;
					} else {
						$monto_pie_nocancelado_pe_uf = 0; //si no está la uf lo toma 0
					}
					$total_monto_pie_nocancelado_pe_uf = $total_monto_pie_nocancelado_pe_uf + $monto_pie_nocancelado_pe_uf; 
			    }
			}

			$pie_por_recuperar = $total_monto_pie_nocancelado_pe_uf + $monto_pie_nocancelado_uf;
			$pie_por_recuperar = round($pie_por_recuperar,2);
			$pie_recuperado = $total_monto_pie_cancelado_pe_uf + $monto_pie_cancelado_uf;
			$pie_recuperado = round($pie_recuperado,2);


			// créditos liquidados
			$consulta7 = 
			  "
			  SELECT 
			    IFNULL(SUM(ven_liq.monto_liq_uf_ven),0) AS credito_liquidado
			  FROM
			    venta_venta AS ven,
			    venta_liquidado_venta AS ven_liq,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	ven_liq.id_ven = ven.id_ven AND
			    ven.id_est_ven > 3 AND 
			    ven.id_for_pag = 1 AND 
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta7);
			$fila7 = $conexion->extraer_registro_unico();
			$credito_liquidado = $fila7["credito_liquidado"];
			$credito_liquidado = round($credito_liquidado,2);

			// créditos NO liquidados
			$consulta8 = 
			  "
			  SELECT 
			    IFNULL(SUM(ven.monto_credito_ven),0) AS total_credito
			  FROM
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			    ven.id_est_ven > 3 AND
			    ven.id_for_pag = 1 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta8);
			$fila8 = $conexion->extraer_registro_unico();
			$total_credito = $fila8["total_credito"];
			$credito_noliquidado = $total_credito - $credito_liquidado;
			$credito_noliquidado = round($credito_noliquidado,2);

			// contado liquidados
			$consulta9 = 
			  "
			  SELECT 
			    IFNULL(SUM(ven_liq.monto_liq_uf_ven),0) AS contado_liquidado
			  FROM
			    venta_venta AS ven,
			    venta_liquidado_venta AS ven_liq,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			  	ven_liq.id_ven = ven.id_ven AND
			    ven.id_est_ven > 3 AND 
			    ven.id_for_pag = 2 AND 
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta9);
			$fila9 = $conexion->extraer_registro_unico();
			$contado_liquidado = $fila9["contado_liquidado"];
			$contado_liquidado = round($contado_liquidado,2);

			// contado NO liquidados
			$consulta10 = 
			  "
			  SELECT 
			    IFNULL(SUM(ven.monto_credito_ven),0) AS total_contado
			  FROM
			    venta_venta AS ven,
			    vivienda_vivienda AS viv,
			    torre_torre AS tor
			  WHERE
			    ven.id_est_ven > 3 AND
			    ven.id_for_pag = 2 AND
			    ven.id_viv = viv.id_viv AND
			    viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$filtro_consulta."
			  ";
			$conexion->consulta($consulta10);
			$fila10 = $conexion->extraer_registro_unico();
			$total_contado = $fila10["total_contado"];
			$contado_noliquidado = $total_contado - $contado_liquidado;
			$contado_noliquidado = round($contado_noliquidado,2);
        	?>
		    series: [{
                name: 'Recuperación',
		        colorByPoint: true,
		        colors: ['#6aa951', '#215d18', '#dfb442', '#ecce7e', '#5072c7','#829ad7', '#b9c04a', '#d2d787'],
		        data: [
                    { name: 'P. Vender', y: <?php echo $monto_disponible; ?>},
                    { name: 'Reservas', y: <?php echo $monto_reservas; ?>},
                    { name: 'Pie P. Recuperar', y: <?php echo $pie_por_recuperar; ?>},
                    { name: 'Pie Recuperado', y: <?php echo $pie_recuperado; ?>},
                    { name: 'CH Por Recuperar', y: <?php echo $credito_noliquidado; ?>},
                    { name: 'CH Recuperado', y: <?php echo $credito_liquidado; ?>},
                    { name: 'Contado por Recuperar', y: <?php echo $contado_noliquidado; ?>},
                    { name: 'Contado Recuperado', y: <?php echo $contado_liquidado; ?>}
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
