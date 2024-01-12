<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Etapa - Cantidad Atraso y en Fecha</title>
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
.container-fluid .content .form-control {
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
    padding-top: 15px;
}
.container-fluid .content .form-control {
    display: inline-block;
    width: auto;
}

.clase_contado{
	background-color: #D4F0F6;
}

.clase_credito{
	background-color: #D8F7EA;
}
</style>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
include _INCLUDE."class/dias.php";
require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Etapa
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Etapa</li>
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
                                    proceso.opcion_pro = 9 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../operacion/operacion_ficha.php">OPERACIONES</a></li>
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
                                    proceso.opcion_pro = 17 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/operacion_etapa.php">OPERACIONES / ETAPAS</a></li>
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
                                    proceso.opcion_pro = 3 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="../informe/operacion_etapa_listado.php">ETAPAS</a></li>
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
                                        proceso.opcion_pro = 21 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li><a href="operacion_listado_operacion.php">LISTADO OPERACIONES</a></li>
                                    <?php
                                }
                                ?>

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
                                        proceso.opcion_pro = 25 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li><a href="../informe/informe_tubo.php">TUBO CLIENTES</a></li>
                                    <?php
                                }
                                ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body" style="padding-top: 0">
                                    
                                    <div class="col-md-12">
                                    	<div class="row">
                                    		<div class="col-md-6 pull-right text-right">
                                        		<a class="btn btn-primary btn-sm" role="button" data-toggle="collapse" href="#collapseetapas" aria-expanded="false" aria-controls="collapseetapas"><i class="fa fa-search" aria-hidden="true"></i> Nombres de Etapas</a>
                                        		<div class="collapse" id="collapseetapas">
                                        			<div class="well">
                                        				<div class="row">
                                        					<div class="col-sm-6 text-left">
                                        						<h4>Etapas Crédito</h4>
                                                				<?php
                                                				$consulta_cate = 
		                                                            "
		                                                            SELECT DISTINCT(etapa_categoria_etapa.id_cat_eta),
		                                                                etapa_categoria_etapa.nombre_cat_eta,
		                                                                etapa_categoria_etapa.orden_cat_eta
		                                                            FROM 
		                                                                etapa_categoria_etapa,
		                                                                etapa_etapa
		                                                            WHERE 
		                                                            	etapa_categoria_etapa.id_cat_eta = etapa_etapa.id_cat_eta AND
		                                                            	etapa_etapa.id_for_pag = 1 AND
		                                                            	etapa_etapa.id_est_eta = 1
		                                                            ORDER BY
		                                                                etapa_categoria_etapa.orden_cat_eta ASC
		                                                            ";
		                                                        $conexion->consulta($consulta_cate);
		                                                        $fila_consulta_cate = $conexion->extraer_registro();
		                                                        if(is_array($fila_consulta_cate)){
		                                                            foreach ($fila_consulta_cate as $fila) {
																		?>
																		<h5 class="font-weight-bold mb-1">> <?php echo utf8_encode($fila['nombre_cat_eta']);?></h5>
																		<?php
																		$consulta_eta = 
				                                                            "
				                                                            SELECT 
				                                                            	nombre_eta,
				                                                                alias_eta,
				                                                                numero_eta
				                                                            FROM 
				                                                                etapa_etapa
				                                                            WHERE 
				                                                            	id_cat_eta = ".$fila['id_cat_eta']." AND
				                                                            	id_for_pag = 1 AND
				                                                            	id_est_eta = 1
				                                                            ORDER BY
				                                                                numero_eta ASC
				                                                            ";
				                                                        $conexion->consulta($consulta_eta);
				                                                        $fila_consulta_eta = $conexion->extraer_registro();
				                                                        if(is_array($fila_consulta_eta)){
				                                                            foreach ($fila_consulta_eta as $fila_eta) {
				                                                            	?>
																				<p class="mb-1"><b><?php echo utf8_encode($fila_eta['alias_eta']);?>:</b> <?php echo utf8_encode($fila_eta['nombre_eta']);?></p>
				                                                            	<?php
				                                                            }
				                                                        }
		                                                            }
		                                                        }
                                                            	?>
                                        					</div>
                                        					<div class="col-sm-6 text-left" style="border-left: 1px solid rgba(0,0,0,.1)">
                                        						<h4>Etapas Contado</h4>
                                                				<?php
                                                				$consulta_cate = 
		                                                            "
		                                                            SELECT DISTINCT(etapa_categoria_etapa.id_cat_eta),
		                                                                etapa_categoria_etapa.nombre_cat_eta,
		                                                                etapa_categoria_etapa.orden_cat_eta
		                                                            FROM 
		                                                                etapa_categoria_etapa,
		                                                                etapa_etapa
		                                                            WHERE 
		                                                            	etapa_categoria_etapa.id_cat_eta = etapa_etapa.id_cat_eta AND
		                                                            	etapa_etapa.id_for_pag = 2 AND
		                                                            	etapa_etapa.id_est_eta = 1
		                                                            ORDER BY
		                                                                etapa_categoria_etapa.orden_cat_eta ASC
		                                                            ";
		                                                        $conexion->consulta($consulta_cate);
		                                                        $fila_consulta_cate = $conexion->extraer_registro();
		                                                        if(is_array($fila_consulta_cate)){
		                                                            foreach ($fila_consulta_cate as $fila) {
																		?>
																		<h5 class="font-weight-bold mb-1">> <?php echo utf8_encode($fila['nombre_cat_eta']);?></h5>
																		<?php
																		$consulta_eta = 
				                                                            "
				                                                            SELECT 
				                                                            	nombre_eta,
				                                                                alias_eta,
				                                                                numero_eta
				                                                            FROM 
				                                                                etapa_etapa
				                                                            WHERE 
				                                                            	id_cat_eta = ".$fila['id_cat_eta']." AND
				                                                            	id_for_pag = 2 AND
				                                                            	id_est_eta = 1
				                                                            ORDER BY
				                                                                numero_eta ASC
				                                                            ";
				                                                        $conexion->consulta($consulta_eta);
				                                                        $fila_consulta_eta = $conexion->extraer_registro();
				                                                        if(is_array($fila_consulta_eta)){
				                                                            foreach ($fila_consulta_eta as $fila_eta) {
				                                                            	?>
																				<p class="mb-1"><b><?php echo utf8_encode($fila_eta['alias_eta']);?>:</b> <?php echo utf8_encode($fila_eta['nombre_eta']);?></p>
				                                                            	<?php
				                                                            }
				                                                        }
		                                                            }
		                                                        }
                                                            	?>
                                        					</div>
                                        				</div>
                                        			</div>
                                        		</div>
                                        	</div>
                                    	</div>
                                        <div class="row contenedor_tabla" id="contenedor_tabla">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Listado de Etapas</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                	<div class="row">
                                                		<div class="col-sm-3">
                                                			<b>A</b>= Atrasada<br>
                                                			<b>F</b>= En Fecha<br>
                                                			<b>N.I.</b>= No Iniciada
                                                		</div>
                                                		<div class="col-sm-3">
                                                			<div style="width: 10px; height: 10px; display: inline-block;" class="clase_contado"></div> Contado &nbsp;&nbsp;
                                                			<div style="width: 10px; height: 10px; display: inline-block;" class="clase_credito"></div> Crédito 
                                                		</div>
                                                	</div>
                                                    <div class="table-responsive">
                                                        <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <?php  
                                                                    $consulta = "SELECT * FROM etapa_etapa ORDER BY id_for_pag DESC, numero_eta ASC";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_etapa = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_etapa)){
                                                                        foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                        	if ($fila_etapa['id_for_pag']==2) {
                                                                        		$colo = "clase_contado";
                                                                        	} else {
																				$colo = "clase_credito";
                                                                        	}
                                                                            ?>
                                                                            <th class="<?php echo $colo; ?>" colspan="2" data-toggle="tooltip" data-placement="top" title="<?php echo utf8_encode($fila_etapa['nombre_eta']); ?>"><?php echo utf8_encode($fila_etapa['alias_eta']);?></th>

                                                                            <?php 
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <th>Condominio</th>
                                                                    <?php  
                                                                    if(is_array($fila_consulta_etapa)){
                                                                        foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                            ?>
                                                                            <th>A</th>
                                                                            <th>F</th>
                                                                            <?php 
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $hoy = date("Y-m-d");
                                                                $total_acumulado_vivienda_operacion = 0;
                                                                $consulta = 
                                                                    "
                                                                    SELECT
                                                                        *
                                                                    FROM
                                                                        condominio_condominio
                                                                    WHERE
                                                                        id_est_con = 1
                                                                    ORDER BY
                                                                        nombre_con
                                                                    ";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
                                                                        $total_acumulado_vivienda_operacion = 0;
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo utf8_encode($fila["nombre_con"]); ?></td>
                                                                        <?php
                                                                        if(is_array($fila_consulta_etapa)){
                                                                            foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                            	$acumulado_atrasado_etapa = 0;
		                                                                        $acumulado_fecha_etapa = 0;
		                                                                        $acumulado_noiniciado_etapa = 0;
                                                                                $text1 = "";
                                                                                $text2 = "";
                                                                                $ventas_noiniciadas = "";
                                                                                $ventas_atrasada = "";
                                                                                $ventas_enfecha = "";
                                                                                $consulta = 
                                                                                    "
                                                                                    SELECT
                                                                                        viv.id_viv,
                                                                                        viv.nombre_viv,
                                                                                        eta_ven.fecha_desde_eta_ven,
                                                                                        eta_ven.fecha_hasta_eta_ven,
                                                                                        eta.duracion_eta,
                                                                                        eta_ven.id_est_eta_ven,
                                                                                        ven.id_ven
                                                                                    FROM
                                                                                        torre_torre AS tor
                                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                                                                        INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
                                                                                        INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven AND (eta_ven.id_est_eta_ven = 2 or eta_ven.id_est_eta_ven = 3)
                                                                                        INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
                                                                                    WHERE
                                                                                        tor.id_con = ? AND
                                                                                        eta.id_eta = ?
                                                                                    ";
																				$id_est_eta_ven= 0;
                                                                                $conexion->consulta_form($consulta,array($fila["id_con"],$fila_etapa["id_eta"]));
                                                                                $fila_consulta_estado = $conexion->extraer_registro();
                                                                                if(is_array($fila_consulta_estado)){
                                                                                    foreach ($fila_consulta_estado as $fila_estado) {
                                                                                    	$id_est_eta_ven = $fila_estado["id_est_eta_ven"];
                                                                                        $fecha_inicio = $fila_estado["fecha_desde_eta_ven"];
                                                                                        $duracion = $fila_estado["duracion_eta"];
                                                                                        $id_ven = $fila_estado["id_ven"];
                                                                                        $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
                                                                                        // $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
                                                                                        $fecha_final = add_business_days($fecha_inicio,$duracion,$holidays,'Y-m-d');
                                                                                        if ($id_est_eta_ven==3) {
                                                                                        	 $acumulado_noiniciado_etapa = $acumulado_noiniciado_etapa + 1;
                                                                                        	 $ventas_noiniciadas .= $id_ven." - ";
                                                                                        } else if($fecha_final < $hoy){
                                                                                            $acumulado_atrasado_etapa = $acumulado_atrasado_etapa + 1;
                                                                                            $ventas_atrasada .= $id_ven." - ";
                                                                                        }
                                                                                        else{
                                                                                            $acumulado_fecha_etapa = $acumulado_fecha_etapa + 1;
                                                                                            $ventas_enfecha .= $id_ven." - ";
                                                                                        }
                                                                                    }
                                                                                }
                                                                                $total_acumulado_vivienda_operacion = $total_acumulado_vivienda_operacion + $acumulado_atrasado_etapa + $acumulado_fecha_etapa + $acumulado_noiniciado_etapa;

                                                                                // echo $fila["id_con"]." - atrasado: ".$acumulado_atrasado_etapa."- en fecha: ".$acumulado_fecha_etapa." - no inicia: ".$acumulado_noiniciado_etapa."<br>";


                                                                                $ventas_noiniciadas = substr($ventas_noiniciadas, 0, -3);
                                                                                $ventas_atrasada = substr($ventas_atrasada, 0, -3);
                                                                                $ventas_enfecha = substr($ventas_enfecha, 0, -3);
                                                                                if ($ventas_atrasada<>'') {
                                                                                	$text1 = "<b>Ventas Atrasadas:</b> ".$ventas_atrasada;
                                                                                }
                                                                                if ($ventas_noiniciadas<>'' || $ventas_enfecha<>'') {
                                                                                	$text2 = "<b>Ventas NO iniciadas:</b> ".$ventas_noiniciadas."<br><b>Ventas en Fecha:</b>".$ventas_enfecha;
                                                                                }
                                                                                ?>
                                                                                <td data-toggle="tooltip" data-placement="top" title="<?php echo $text1; ?>"><?php 
                                                                                	echo number_format($acumulado_atrasado_etapa, 0, ',', '.');
                                                                                	?>
                                                                                </td>
                                                                                <td data-toggle="tooltip" data-placement="top" title="<?php echo $text2; ?>"><?php
                                                                                	if ($acumulado_noiniciado_etapa>0) {
                                                                                		echo "<b>N.I.</b>: ".number_format($acumulado_noiniciado_etapa, 0, ',', '.')." - ";
                                                                                	}
                                                                                	echo number_format($acumulado_fecha_etapa, 0, ',', '.');
                                                                                	?></td>
                                                                                <?php 
                                                                            }
                                                                        }

                                                                        
                                                                        ?>
                                                                        </tr>
                                                                        <?php
                                                                        $data[$fila["id_con"]] = $total_acumulado_vivienda_operacion;
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    
                                                                </tr> 
                                                            </tfoot>
                                                            
                                                        </table>
                                                    </div>
                                                    <?php //print_r($data) ?>
                                                    <div class="col-md-12">
                                                    	<table class="table table-striped table-bordered">
                                                    		<thead>
                                                    			<tr>
	                                                    			<th>Condominio <?php echo $totales_oopp_condos ?></th>
	                                                    			<th>Unidades en Operaciones</th>
	                                                    			<!-- <th>Unidades en OOPP<br>pero sin CH aprobado o Provisión de F. Pagados</th> -->
	                                                    			<th>OOPP Terminadas</th>
	                                                    			<th>Unidades en Promesa/Venta<br><small>No pasadas aún a operaciones</small></th>
	                                                    			<th>Unidades Disponibles</th>
	                                                    			<th>Total Unidades</th>
	                                                    		</tr>
                                                    		</thead>
                                                            <?php  
                                                            echo $acumulado_oopp_condominios;
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila){

                                                                    $consulta = 
                                                                        "
                                                                        SELECT
                                                                            viv.id_viv
                                                                        FROM
                                                                            torre_torre AS tor
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                                                        WHERE
                                                                            tor.id_con = ?
                                                                        ";
                                                                    $conexion->consulta_form($consulta,array($fila["id_con"]));
                                                                    $cantidad_total_unidad = $conexion->total();

                                                                    $consulta_ventas_etapa2_pasada = 
																	  "
																	  SELECT 
																	    ven.id_ven
																	  FROM
																	    venta_venta as ven,
																	    vivienda_vivienda as viv
																	  WHERE
																	  	ven.id_viv = viv.id_viv AND
																	  	viv.id_tor = ".$fila["id_con"]." AND 
																	    ven.id_est_ven > 3 AND EXISTS(
																	        SELECT 
																	            ven_eta.id_ven
																	        FROM
																	            venta_etapa_venta AS ven_eta
																	        WHERE
																	            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
																	    )";
																	$conexion->consulta($consulta_ventas_etapa2_pasada);
                                                                    $cantidad_pasadas_2etapa = $conexion->total();

                                                                    // $consulta = 
                                                                    //     "
                                                                    //     SELECT
                                                                    //         viv.id_viv
                                                                    //     FROM
                                                                    //         torre_torre AS tor
                                                                    //         INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                                                    //         INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
                                                                    //     WHERE
                                                                    //         tor.id_con = ? AND NOT
                                                                    //         EXISTS(
                                                                    //             SELECT 
                                                                    //                 eta.id_ven
                                                                    //             FROM
                                                                    //                 venta_etapa_venta AS eta
                                                                    //             WHERE
                                                                    //                 ven.id_ven = eta.id_ven
                                                                    //         )
                                                                    //     ";

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
																	    tor.id_con = ? AND NOT
											                            EXISTS(
											                                SELECT 
											                                    ven_eta.id_ven
											                                FROM
											                                    venta_etapa_venta AS ven_eta
											                                WHERE
											                                    ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
											                            )
																	  ";
                                                                    $conexion->consulta_form($consulta_ventas_promesa_condo,array($fila["id_con"]));
                                                                    $cantidad_promesa_venta = $conexion->total();

                                                                    // viviendas con operaciones cerradas
                                                                    $consulta = 
                                                                        "
                                                                        SELECT
                                                                            viv.id_viv
                                                                        FROM
                                                                            torre_torre AS tor
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                                                            INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
                                                                        WHERE
                                                                            tor.id_con = ? AND 
                                                                            EXISTS(
                                                                                SELECT 
                                                                                    eta.id_ven
                                                                                FROM
                                                                                    venta_etapa_venta AS eta
                                                                                WHERE
                                                                                    ven.id_ven = eta.id_ven AND
                                                                                    (eta.id_est_eta_ven = 1)
                                                                            ) AND NOT
                                                                            EXISTS(
                                                                                SELECT 
                                                                                    eta.id_ven
                                                                                FROM
                                                                                    venta_etapa_venta AS eta
                                                                                WHERE
                                                                                    ven.id_ven = eta.id_ven AND
                                                                                    (eta.id_est_eta_ven = 2 OR eta.id_est_eta_ven = 3)
                                                                            )
                                                                        ";
                                                                    $conexion->consulta_form($consulta,array($fila["id_con"]));
                                                                    $cantidad_oopp_terminadas = $conexion->total();


                                                                    $consulta = 
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
                                                                    $conexion->consulta_form($consulta,array($fila["id_con"]));
                                                                    $cantidad_vivienda_disponible = $conexion->total();
																	
																	$no_pasan_2etapa = $data[$fila["id_con"]] - $cantidad_pasadas_2etapa;
																	if($no_pasan_2etapa<0){
																		$no_pasan_2etapa = 0;
																	}
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo utf8_encode($fila["nombre_con"]); ?></td>
                                                                        <td><?php echo number_format($data[$fila["id_con"]], 0, ',', '.');?></td>
                                                                        <!-- <td><?php //echo $no_pasan_2etapa; ?></td> -->
                                                                        <td><?php echo number_format($cantidad_oopp_terminadas, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($cantidad_promesa_venta, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($cantidad_vivienda_disponible, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($cantidad_total_unidad, 0, ',', '.');?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                    	</table>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
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
<!-- DataTables -->
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script>
<script type="text/javascript">
    jQuery.fn.dataTable.ext.type.search.string = function(data) {
    return !data ?
        '' :
        typeof data === 'string' ?
        data
        .replace(/έ/g, 'ε')
        .replace(/ύ/g, 'υ')
        .replace(/ό/g, 'ο')
        .replace(/ώ/g, 'ω')
        .replace(/ά/g, 'α')
        .replace(/ί/g, 'ι')
        .replace(/ή/g, 'η')
        .replace(/\n/g, ' ')
        .replace(/[áÁ]/g, 'a')
        .replace(/[éÉ]/g, 'e')
        .replace(/[íÍ]/g, 'i')
        .replace(/[óÓ]/g, 'o')
        .replace(/[úÚ]/g, 'u')
        .replace(/[üÜ]/g, 'u')
        .replace(/ê/g, 'e')
        .replace(/î/g, 'i')
        .replace(/ô/g, 'o')
        .replace(/è/g, 'e')
        .replace(/ï/g, 'i')
        .replace(/ã/g, 'a')
        .replace(/õ/g, 'o')
        .replace(/ç/g, 'c')
        .replace(/ì/g, 'i') :
        data;
    };
    $(document).ready(function () {
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip({container: 'body',html: true})
		})


        var table = $('#example').DataTable( {
            "pageLength": 50,
            dom:'lfBrtip',
            // success de tabla
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
            "bProcessing": true,
            //"bServerSide": true,
            responsive: true,
            //"sAjaxSource": "select_alumno.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 1, "asc" ]],
            "aoColumns": [
                { "sType": "string" },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ]
        });

        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

        $('#example_filter input').keyup(function() {
            table
              .search(
                jQuery.fn.dataTable.ext.type.search.string(this.value)
              )
              .draw();
        });

        $(document).on( "click","#filtro" , function() {
            var_matricula = $('.filtro_matricula:checked').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"matricula="+var_matricula,
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
    });
</script>
</body>
</html>
