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
                                    proceso.opcion_pro = 1 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/operacion_listado.php">LISTADO VENTAS</a></li>
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
                                    proceso.opcion_pro = 14 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="condominio_disponibilidad_listado.php">DISPONIBILIDAD</a></li>
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
                                    proceso.opcion_pro = 4 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_velocidad_listado.php">VELOCIDAD DE VENTAS</a></li>
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
                                    proceso.opcion_pro = 15 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="venta_recuperacion_listado.php">RECUPERACIÓN</a></li>
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
                                    proceso.opcion_pro = 8 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_estadistica_venta.php">ANÁLISIS DE VENTAS</a></li>
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
                                    proceso.opcion_pro = 10 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_cotizacion_venta.php">LISTADO COTIZACIONES</a></li>
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
                                    proceso.opcion_pro = 11 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="ficha_cliente_proceso.php">FICHA DE CLIENTE</a></li>
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
                                    proceso.opcion_pro = 11 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_pago_venta.php">LISTADO PAGOS</a></li>
                                <?php
                            }
                            // alzamiento
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
                                    proceso.opcion_pro = 16 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_alzamiento_listado.php">ALZAMIENTO</a></li>
                                <?php
                            }
                            // fondo explotación
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
                                <li><a href="venta_fondo_listado.php">FONDO EXPLOTACIÓN</a></li>
                                <?php
                            }
                            ?>
                        </ul>
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
                                proceso.opcion_pro = 2 AND
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
                                        <div class="row">
                                            <div id="contenedor_opcion"></div>
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="condominio">Condominio:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="condominio" name="condominio"> 
                                                                    <option value="">Seleccione Condominio</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
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
                                                        $filtro_consulta .= " AND tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
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
                                        <?php
                                        if(isset($_SESSION["sesion_filtro_condominio_panel"])){  
	                                        ?>
	                                        <div class="col-md-12">
	                                            <div class="row" id="contenedor_tabla">
	                                                <div class="box">
	                                                    <div class="box-header">
	                                                        <h3 class="box-title">Recuperación</h3>
	                                                    </div>
	                                                    <?php
	                                                    // ventas totales proyecto
														$consulta_ventas_promesa = 
														  "
														  SELECT 
														    IFNULL(SUM(ven.monto_ven),0) AS suma,
	                                                        IFNULL(COUNT(ven.id_ven),0) AS cantidad
														  FROM
														    torre_torre AS tor
														    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
														    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
														  WHERE NOT
														    EXISTS(
														        SELECT 
														            ven_eta.id_ven
														        FROM
														            venta_etapa_venta AS ven_eta
														        WHERE
														            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
														    )
														    ".$filtro_consulta."
														  ";
	  
														$conexion->consulta($consulta_ventas_promesa);
	                                                    $fila = $conexion->extraer_registro_unico();
	                                                    $venta_promesas_proyecto = $fila["suma"];
	                                                    // $unidad_promesas_proyecto = $fila["cantidad"];

	                                                    $consulta_ventas_inciadas_oopp = 
														  "
														  SELECT 
														    IFNULL(SUM(ven.monto_ven),0) AS suma,
	                                                        IFNULL(COUNT(ven.id_ven),0) AS cantidad
														  FROM
														    venta_venta as ven,
														    venta_etapa_venta as ven_eta,
														    vivienda_vivienda as viv,
														    torre_torre as tor
														  WHERE
														    ven.id_est_ven > 3 AND
														    ven.id_ven = ven_eta.id_ven AND
														    ven.id_viv = viv.id_viv AND
														    viv.id_tor = tor.id_tor AND
														    ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
														    ".$filtro_consulta."
														    ";
														// echo $consulta_ventas_inciadas_oopp;
														$conexion->consulta($consulta_ventas_inciadas_oopp);
	                                                    $fila = $conexion->extraer_registro_unico();
	                                                    $venta_real_oopp_proyecto = $fila["suma"];
	                                                    // $unidad_real_oopp_proyecto = $fila["cantidad"];

	                                                    $consulta_disp = 
	                                                        "
	                                                        SELECT 
	                                                            IFNULL(SUM(viv.valor_viv),0) AS suma,
	                                                            IFNULL(COUNT(viv.id_viv),0) AS cantidad
	                                                        FROM 
	                                                            vivienda_vivienda AS viv
	                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                        WHERE
	                                                            viv.id_viv > 0 AND 
	                                                            viv.id_est_viv = 1
	                                                            ".$filtro_consulta."
	                                                        ";
	                                                    // echo $consulta_disp;
	                                                    $conexion->consulta($consulta_disp);
	                                                    $fila = $conexion->extraer_registro_unico();
	                                                    $venta_dispo_proyecto = $fila["suma"];
	                                                    // $unidad_dispo_proyecto = $fila["cantidad"];


	                                                    $venta_total_proyecto = $venta_real_oopp_proyecto + $venta_promesas_proyecto + $venta_dispo_proyecto;


	                                                    // 
	                                                    $consulta = 
	                                                        "
	                                                        SELECT 
	                                                            valor_par,
	                                                            valor2_par
	                                                        FROM 
	                                                            parametro_parametro
	                                                        WHERE
	                                                            id_con = ".$_SESSION["sesion_filtro_condominio_panel"]." AND
	                                                            valor2_par IN (".$n_valor_monto_recuperar.",".$n_valor_fecha_recuperacion.")
	                                                        ";
	                                                    $conexion->consulta($consulta);
	                                                    $fila_consulta = $conexion->extraer_registro();
														if(is_array($fila_consulta)){
	                                                        foreach ($fila_consulta as $fila_par) {
	                                                        	if($fila_par["valor2_par"] == $n_valor_monto_recuperar){
																	$parametro_monto = $fila_par["valor_par"];
	                                                        	}
	                                                        	else{
																	$parametro_fecha = $fila_par["valor_par"];			
	                                                        	}
	                                                        }
	                                                    }

	                                                    // $consulta = 
	                                                    //     "
	                                                    //     SELECT 
	                                                    //         IFNULL(SUM(viv.valor_viv),0) AS suma,
	                                                    //         IFNULL(COUNT(viv.id_viv),0) AS cantidad
	                                                    //     FROM 
	                                                    //         vivienda_vivienda AS viv
	                                                    //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                    //     WHERE
	                                                    //         viv.id_viv > 0
	                                                    //         ".$filtro_consulta."
	                                                    //     ";
	                                                    // $conexion->consulta($consulta);
	                                                    // $fila = $conexion->extraer_registro_unico();
	                                                    // $venta_total_proyecto = $fila["suma"];
														// el estado debería ser mayor a 3, de promesa para arriba
														// es valor vivienda menos el descuento, para saber cuanto se recuperó
	                                                    // $consulta = 
	                                                    //     "
	                                                    //     SELECT 
	                                                    //         IFNULL(SUM(ven.monto_vivienda_ven - ven.descuento_ven),0) AS recuperacion,
	                                                    //         IFNULL((ven.monto_ven),0) AS suma,
	                                                    //         IFNULL(COUNT(ven.id_ven),0) AS cantidad
	                                                    //     FROM 
	                                                    //         venta_venta AS ven
	                                                    //         INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                                                    //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                    //     WHERE
	                                                    //         ven.id_est_ven > 3
	                                                    //         ".$filtro_consulta."
	                                                    //     ";
	                                                    // $conexion->consulta($consulta);
	                                                    // $fila = $conexion->extraer_registro_unico();
	                                                    // $venta_recuperada_proyecto = $fila["recuperacion"];
	                                                    ?>
	                                                    <!-- /.box-header -->
	                                                    <div class="box-body no-padding">
															<div class="row">
																<div class="col-md-5">
																	<?php echo $unidad_dispo_proyecto; ?>
																	<table class="table table-bordered bg-aqua-active color-palette">
																		<tr>
																			<td>Monto a Recuperar UF (<?php echo date("d-m-Y",strtotime($parametro_fecha)); ?>)</td>
																			<td>Recuperación al <?php echo date("d-m-Y"); ?></td>
																		</tr>
																		<tr>
																			<td><?php echo number_format($parametro_monto, 0, ',', '.');?>.-</td>
																			<td><?php echo number_format($venta_total_proyecto, 2, ',', '.');?>.-</td>
																		</tr>
																	</table>
																</div>
																<div class="col-md-12">
																	<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> La tabla refleja ventas efectivas realizadas, restando los descuentos.</p>
																	<table class="table table-bordered">
																		<tr class="bg-gray color-palette">
																			<td>MES</td>
																			<td>mes/año</td>
																			<td>Cant. Ventas</td>
																			<td>Desistimiento</td>
																			<td>Cant. Acumulado</td>
																			<td>UF Vendidas</td>
																			<td>UF Desistida</td>
																			<td>UF Acumulado</td>
																		</tr>
	                                                                    <?php
	                                                                    $contador = 1;
	                                                                    $total_cantidad_venta = 0;
	                                                                    $total_cantidad_desistimiento = 0;
	                                                                    $total_cantidad_acumulada = 0;
	                                                                    $total_uf_venta = 0;
	                                                                    $total_uf_desestimada = 0;
	                                                                    $total_uf_acumulada = 0;

	                                                                    // $consulta = "
	                                                                    //     SELECT
	                                                                    //         IFNULL(SUM(ven.monto_ven),0) AS uf_venta,
	                                                                    //         IFNULL(SUM(ven.monto_vivienda_ven - ven.descuento_ven),0) AS uf_recuperada,
	                                                                    //         MONTH(ven.fecha_ven) AS mes,
	                                                                    //         YEAR(ven.fecha_ven) AS anio,
	                                                                    //         COUNT(ven.id_ven) AS cantidad_venta
	                                                                    //     FROM
	                                                                    //         venta_venta AS ven
	                                                                    //     INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                                                                    //     INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                                    //     WHERE
	                                                                    //         ven.id_est_ven > 3
	                                                                    //         ".$filtro_consulta."
	                                                                    //     GROUP BY
	                                                                    //         MONTH(ven.fecha_ven),
	                                                                    //         YEAR(ven.fecha_ven)
	                                                                    //     ORDER BY
	                                                                    //         YEAR(ven.fecha_ven),
	                                                                    //         MONTH(ven.fecha_ven)
	                                                                    //     ";
																		// FALTABA EXCLUIR LAS OPERACIONES, CON DESISTIMIENTO DE ERRORES, PORQUE ESAS DESPUES NO SE DESCUENTAN
	                                                                    $consulta = "
	                                                                        SELECT
	                                                                            IFNULL(SUM(ven.monto_ven),0) AS uf_venta,
	                                                                            IFNULL(SUM(ven.monto_vivienda_ven - ven.descuento_ven),0) AS uf_recuperada,
	                                                                            MONTH(ven.fecha_ven) AS mes,
	                                                                            YEAR(ven.fecha_ven) AS anio,
	                                                                            COUNT(ven.id_ven) AS cantidad_venta
	                                                                        FROM
	                                                                            venta_venta AS ven
	                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                                        WHERE
	                                                                            ven.id_est_ven > 2 AND NOT EXISTS(
																			        SELECT 
																			            ven_des.id_ven
																			        FROM
																			            venta_desestimiento_venta AS ven_des
																			        WHERE
																			            ven_des.id_ven = ven.id_ven AND
																			            ven_des.id_tip_des = 2
																			    )
	                                                                            ".$filtro_consulta."
	                                                                        GROUP BY
	                                                                            MONTH(ven.fecha_ven),
	                                                                            YEAR(ven.fecha_ven)
	                                                                        ORDER BY
	                                                                            YEAR(ven.fecha_ven),
	                                                                            MONTH(ven.fecha_ven)
	                                                                        ";
	                                                                    $conexion->consulta($consulta);
	                                                                    $fila_consulta_condominio_original = $conexion->extraer_registro();
	                                                                    if(is_array($fila_consulta_condominio_original)){
	                                                                        foreach ($fila_consulta_condominio_original as $fila) {
	                                                                            $mes = $fila["mes"];
	                                                                            $anio = $fila["anio"];
	                                                                            $cantidad_venta = $fila["cantidad_venta"];
	                                                                            $uf_venta = $fila["uf_venta"];
	                                                                            $uf_recuperada = $fila["uf_recuperada"];

	                                                                            $consulta = 
	                                                                                "
	                                                                                SELECT
	                                                                                    IFNULL(SUM(ven.monto_ven),0) AS uf_desistimiento,
	                                                                                    IFNULL(SUM(ven.monto_vivienda_ven - ven.descuento_ven),0) AS uf_desistimiento_recuperada,
	                                                                                    IFNULL(COUNT(ven.id_ven),0) AS cantidad_desistimiento
	                                                                                FROM
	                                                                                    venta_venta AS ven
	                                                                                INNER JOIN venta_desestimiento_venta AS des ON des.id_ven = ven.id_ven
	                                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                                                                                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
	                                                                                INNER JOIN venta_desestimiento_venta AS ven_des ON ven_des.id_ven = ven.id_ven
	                                                                                WHERE
	                                                                                    ven.id_est_ven = 3 AND
	                                                                                    ven_des.id_tip_des = 1 AND
	                                                                                    MONTH(des.fecha_des_ven) = '".$mes."' AND
	                                                                                    YEAR(des.fecha_des_ven) = '".$anio."'
	                                                                                    ".$filtro_consulta."
	                                                                                ORDER BY
	                                                                                    YEAR(ven.fecha_ven),
	                                                                                    MONTH(ven.fecha_ven)
	                                                                                ";
	                                                                            // echo $consulta."<br>";
	                                                                            $conexion->consulta($consulta);
	                                                                            $fila_detalle = $conexion->extraer_registro_unico();
	                                                                            $cantidad_desistimiento = $fila_detalle["cantidad_desistimiento"];
	                                                                            $cantidad_acumulada = $cantidad_venta - $cantidad_desistimiento;
	                                                                            $uf_desistimiento = $fila_detalle["uf_desistimiento"];
	                                                                            $uf_desistimiento_recuperada = $fila_detalle["uf_desistimiento_recuperada"];

	                                                                            $uf_acumulada = $uf_recuperada - $uf_desistimiento_recuperada;

	                                                                            //TOTALES
	                                                                            $total_cantidad_venta = $total_cantidad_venta + $cantidad_venta;
	                                                                            $total_cantidad_desistimiento = $total_cantidad_desistimiento + $cantidad_desistimiento;
	                                                                            $total_cantidad_acumulada = $total_cantidad_acumulada + $cantidad_acumulada;
	                                                                            $total_uf_venta = $total_uf_venta + $uf_recuperada;
	                                                                            $total_uf_desestimada = $total_uf_desestimada + $uf_desistimiento_recuperada;
	                                                                            $total_uf_acumulada = $total_uf_acumulada + $uf_acumulada;
	                                                                            ?>
	                                                                            <tr>
	                                                                                <td><?php echo $contador;?></td>
	                                                                                <td><?php echo $mes;?>/<?php echo $anio;?></td>
	                                                                                <td><?php echo $cantidad_venta;?></td>
	                                                                                <td><?php echo $cantidad_desistimiento;?></td>
	                                                                                <td><?php echo $cantidad_acumulada;?></td>
	                                                                                <td><?php echo number_format($uf_recuperada, 2, ',', '.');?></td>
	                                                                                <td><?php echo number_format($uf_desistimiento_recuperada, 2, ',', '.');?></td>
	                                                                                <td><?php echo number_format($uf_acumulada, 2, ',', '.');?></td>
	                                                                            </tr>
	                                                                            <?php
	                                                                            $contador++;
	                                                                        }
	                                                                    }
	                                                                    ?>
																		<tr class="bg-light-blue color-palette">
																			<td colspan="2">Totales</td>
																			<td><?php echo number_format($total_cantidad_venta, 0, ',', '.');?></td>
																			<td><?php echo number_format($total_cantidad_desistimiento, 0, ',', '.');?></td>
																			<td><?php echo number_format($total_cantidad_acumulada, 0, ',', '.');?></td>
																			<td><?php echo number_format($total_uf_venta, 2, ',', '.');?></td>
																			<td><?php echo number_format($total_uf_desestimada, 2, ',', '.');?></td>
																			<td><?php echo number_format($total_uf_acumulada, 2, ',', '.');?></td>
																		</tr>
																	</table>

																	<br>
																	<table class="table table-bordered">
																		<thead>
																			<tr class="active">
																				<th colspan="7" class="text-center">Recuperación Histórica</th>
																			</tr>
																			<tr class="active">
																				<th colspan="7" class="text-center">UF</th>
																			</tr>
																			<tr>
																				<td>Fecha</td>
																				<td>Contados Recuperados</td>
																				<td>Pie dptos. Esc</td>
																				<td>C. Hip. por Recup.</td>
																				<td>CH Recuperados</td>
																				<td>Pie Faltantes</td>
																				<td>TOTAL</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php 
																			 $consulta = 
							                                                        "
							                                                        SELECT 
							                                                            fecha_his,
							                                                            contado_recuperado_his,
							                                                            pie_deptos_escr_his,
							                                                            c_h_por_recup_his,
							                                                            c_h_recup_his,
							                                                            pie_faltantes_his
							                                                        FROM 
							                                                            historico_recuperacion_historico
							                                                        WHERE
							                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." 
							                                                        ORDER BY id_his ASC
							                                                        ";
							                                                    $conexion->consulta($consulta);
							                                                    $fila_consulta = $conexion->extraer_registro();
																				if(is_array($fila_consulta)){
							                                                        foreach ($fila_consulta as $fila) {
							                                                        	$fecha_his = $fila['fecha_his'];
							                                                        	$fecha_his = date("d-m-Y",strtotime($fecha_his));
							                                                        	$contado_recuperado_his = $fila['contado_recuperado_his'];
							                                                        	$contado_recuperado_his = number_format($contado_recuperado_his, 2, ',', '.');
							                                                        	$pie_deptos_escr_his = $fila['pie_deptos_escr_his'];
							                                                        	$pie_deptos_escr_his = number_format($pie_deptos_escr_his, 2, ',', '.');
							                                                        	$c_h_por_recup_his = $fila['c_h_por_recup_his'];
							                                                        	$c_h_por_recup_his = number_format($c_h_por_recup_his, 2, ',', '.');
							                                                        	$c_h_recup_his = $fila['c_h_recup_his'];
							                                                        	$c_h_recup_his = number_format($c_h_recup_his, 2, ',', '.');
							                                                        	$pie_faltantes_his = $fila['pie_faltantes_his'];
							                                                        	$pie_faltantes_his = number_format($pie_faltantes_his, 2, ',', '.');
							                                                        	$total_fila = $fila['contado_recuperado_his'] + $fila['pie_deptos_escr_his'] + $fila['c_h_por_recup_his'] + $fila['c_h_recup_his'] + $fila['pie_faltantes_his'];
							                                                        	$total_fila = number_format($total_fila, 2, ',', '.');
							                                                        	?>
							                                                        	<tr>
																							<td><?php echo $fecha_his; ?></td>
																							<td><?php echo $contado_recuperado_his; ?></td>
																							<td><?php echo $pie_deptos_escr_his; ?></td>
																							<td><?php echo $c_h_por_recup_his; ?></td>
																							<td><?php echo $c_h_recup_his; ?></td>
																							<td><?php echo $pie_faltantes_his; ?></td>
																							<td><?php echo $total_fila; ?></td>
																						</tr>
							                                                        	<?php
							                                                        }
							                                                        $total_fila = 0;
							                                                    }
																			 ?>
																			<!-- información al día de hoy para guardar -->
																			<!-- CRÉDITO  RECUPERADO-->
																			<?php 
																			$hoy = date("Y-m-d");

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
																			    viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." AND
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
																			            ven_liq.fecha_liq_ven < '".$hoy."'
																			    )
																			  ";
																			  // echo $cons_ch_recup;
																			$conexion->consulta($cons_ch_recup);
																			$fila = $conexion->extraer_registro_unico();
																			$ch_recup = $fila['chrecup'];
																			$cant_ch_recup = $fila['cant_chrecup'];

																			// CONTADO RECUPERADO  A
																			$cons_contados_recup = 
																			  "
																			  SELECT 
																			    SUM(ven.monto_vivienda_ingreso_ven) AS conrecup,
																			    COUNT(ven.id_ven) AS cant_conrecup
																			  FROM
																			    venta_venta as ven,
																			    venta_liquidado_venta as ven_liq,
																			    vivienda_vivienda as viv
																			  WHERE
																			    ven.id_est_ven > 3 AND
																			    ven.id_viv = viv.id_viv AND
																			    viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." AND
																			    ven.id_for_pag = 2 AND
																			    ven.id_ven = ven_liq.id_ven AND
	                                                                            EXISTS(
																			        SELECT 
																			            ven_liq.id_ven
																			        FROM
																			            venta_liquidado_venta AS ven_liq
																			        WHERE
																			            ven.id_ven = ven_liq.id_ven AND
																			            ven_liq.fecha_liq_ven < '".$hoy."'
																			    )
																			  ";
																			$conexion->consulta($cons_contados_recup);
																			$fila = $conexion->extraer_registro_unico();
																			$con_recup = $fila['conrecup'];
																			$cant_con_recup = $fila['cant_conrecup'];


																			// PIE DEPTOS ESCRITURADOS
																			$pie_pagado_efectivo = 0;
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
		                                                                            ven.id_for_pag = 1 AND
		                                                                            viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."
														                        ";
														                    // echo $consulta;
														                    $conexion->consulta($consulta_pie);
														                    $fila_consulta = $conexion->extraer_registro();
														                    if(is_array($fila_consulta)){
														                        foreach ($fila_consulta as $fila_pag) {
																					$valor_uf_efectivo = 0;
																					// $pie_pagado_efectivo = 0;
																					// $pie_pagado_porcobrar = 0;
														                        	
														                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
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
																								$abono_uf = 0;
																							}
																							
																						} else {
																							$valor_uf = 0;
																						}

																						$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;

														                            }
														                            else{
														                                // $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
														                                
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

																						$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;          
														                            }
														                            // $total_abono = $total_abono + $monto_pag;
																					// $total_uf = $total_uf + $abono_uf;
																					// $acumula_pie_pagados = $acumula_pie_pagados + $pie_pagado_efectivo;
														                           
														                        }
														                    }

																			// CH POR RECUPERAR
																			$acumula_ch_por_recuperar = 0;
																			$cons_ch_por_recup = 
																			  "
																			  SELECT 
																			  	ven.id_ven,
																			    ven.monto_credito_ven,
																			    ven.monto_credito_real_ven
																			  FROM
																			    venta_venta as ven,
																			    vivienda_vivienda as viv
																			  WHERE
																			    ven.id_est_ven > 3 AND
																			    ven.id_viv = viv.id_viv AND
																			    viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." AND
																			    ven.id_for_pag = 1 AND NOT
	                                                                            EXISTS(
																			        SELECT 
																			            ven_liq.id_ven
																			        FROM
																			            venta_liquidado_venta AS ven_liq
																			        WHERE
																			            ven.id_ven = ven_liq.id_ven AND
																			            ven_liq.monto_liq_uf_ven <> 0 AND 
																			            ven_liq.fecha_liq_ven < '".$hoy."'
																			    )
																			  ";
																			// echo $cons_ch_por_recup;
																			$conexion->consulta($cons_ch_por_recup);
																			$cant_ch_por_recup = $conexion->total();
																			$fila_consulta = $conexion->extraer_registro();
														                    if(is_array($fila_consulta)){
														                        foreach ($fila_consulta as $fila_por_r) {
														                        	if ($fila_por_r["monto_credito_real_ven"]<>0) {
																						$credito_hipo = $fila_por_r["monto_credito_real_ven"];
																					} else {
																						$credito_hipo = $fila_por_r["monto_credito_ven"];
																					}

																					$acumula_ch_por_recuperar = $acumula_ch_por_recuperar + $credito_hipo;
														                        }
														                    }

																			$ch_por_recup = $acumula_ch_por_recuperar;


																			// TOTAL CANTIDADES HOY
																			$total_hoy = $con_recup + $pie_pagado_efectivo + $ch_por_recup + $ch_recup + $pie_pagado_porcobrar;
																			$total_cant_hoy = $cant_ch_recup + $cant_ch_por_recup + $cant_con_recup;
																			 ?>
																			 <tr class="info">
																			 	<td class="text-center" colspan="7">HOY</td>
																			 </tr>
																			 <tr class="info">
																			 	<td><?php echo date("d-m-Y"); ?></td>
																			 	<td><?php echo number_format($con_recup, 2, ',', '.'); ?></td>
																			 	<td><?php echo number_format($pie_pagado_efectivo, 2, ',', '.'); ?></td>
																			 	<td><?php echo number_format($ch_por_recup, 2, ',', '.'); ?></td>
																			 	<td><?php echo number_format($ch_recup, 2, ',', '.'); ?></td>
																			 	<td><?php echo number_format($pie_pagado_porcobrar, 2, ',', '.'); ?></td>
																			 	<td><?php echo number_format($total_hoy, 2, ',', '.'); ?></td>
																			 </tr>
																		</tbody>
																	</table>
																	<br>
																	<br>
																	<input type="hidden" name="val_his" id="val_his" data-conrecup="<?php echo $con_recup; ?>" data-piescr="<?php echo $pie_pagado_efectivo; ?>" data-chpr="<?php echo $ch_por_recup; ?>" data-chrecup="<?php echo $ch_recup; ?>" data-piefal="<?php echo $pie_pagado_porcobrar; ?>" data-cantchprec="<?php echo $cant_ch_por_recup ?>" data-canconrecu="<?php echo $cant_con_recup ?>" data-canchrecup="<?php echo $cant_ch_recup ?>" data-con="<?php echo $_SESSION["sesion_filtro_condominio_panel"]; ?>">
																	<table class="table table-bordered">
																		<thead>
																			<tr class="active">
																				<th colspan="5" class="text-center">Recuperación Histórica</th>
																			</tr>
																			<tr class="active">
																				<th colspan="5" class="text-center">CANTIDADES</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>Fecha</td>
																				<td>Cred.Hip. en Recup.</td>
																				<td>Contados Recup.</td>
																				<td>CH Recup.</td>
																				<td>TOTAL</td>
																			</tr>
																			<?php
																			$consulta_cantidades = 
							                                                        "
							                                                        SELECT 
							                                                            fecha_his,
							                                                            cred_hip_en_recup_cant_his,
							                                                            contado_recup_cant_his,
							                                                            ch_recup_cant_his
							                                                        FROM 
							                                                            historico_recuperacion_historico
							                                                        WHERE
							                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." 
							                                                        ORDER BY id_his ASC
							                                                        ";
							                                                    $conexion->consulta($consulta_cantidades);
							                                                    $fila_consulta = $conexion->extraer_registro();
																				if(is_array($fila_consulta)){
							                                                        foreach ($fila_consulta as $fila_cant) {
							                                                        	$fecha_his_cant = $fila_cant['fecha_his'];
							                                                        	$fecha_his_cant = date("d-m-Y",strtotime($fecha_his_cant));
							                                                        	$cred_hip_en_recup_cant_his = $fila_cant['cred_hip_en_recup_cant_his'];
							                                                        	// $cred_hip_en_recup_cant_his = number_format($cred_hip_en_recup_cant_his, 2, ',', '.');
							                                                        	$contado_recup_cant_his = $fila_cant['contado_recup_cant_his'];
							                                                        	// $contado_recup_cant_his = number_format($contado_recup_cant_his, 2, ',', '.');
							                                                        	$ch_recup_cant_his = $fila_cant['ch_recup_cant_his'];
							                                                        	// $ch_recup_cant_his = number_format($ch_recup_cant_his, 2, ',', '.');
							                                                        	$total_fila_cant = $fila_cant['cred_hip_en_recup_cant_his'] + $fila_cant['contado_recup_cant_his'] + $fila_cant['ch_recup_cant_his'];
							                                                        	$total_fila_cant = $total_fila_cant;
							                                                        	?>
							                                                        	<tr>
																							<td><?php echo $fecha_his_cant ?></td>
																							<td><?php echo $cred_hip_en_recup_cant_his ?></td>
																							<td><?php echo $contado_recup_cant_his ?></td>
																							<td><?php echo $ch_recup_cant_his ?></td>
																							<td><?php echo $total_fila_cant; ?></td>
																						</tr>
							                                                        	<?php
							                                                        }
							                                                        $total_fila_cant = 0;
							                                                    }
																			 ?>
																			 <tr class="info">
																			 	<td colspan="5" class="text-center">HOY</td>
																			 </tr>
																			<tr class="info">
																				<td><?php echo date("d-m-Y"); ?></td>
																				<td><?php echo $cant_ch_por_recup ?></td>
																				<td><?php echo $cant_con_recup ?></td>
																				<td><?php echo $cant_ch_recup ?></td>
																				<td><?php echo $total_cant_hoy; ?></td>
																			</tr>
																		</tbody>
																	</table>
																	<div id="contenedor_boton" style="text-align: center;"><input type="button" value="GUARDAR REGISTRO HISTÓRICO" name="guarda_his" id="guarda_his" class="btn btn-sm btn-icon btn-primary"></input></div>
																	
																</div>
							                            	</div>
	                                                    </div>
	                                                    <!-- /.box-body -->
	                                                </div>
	                                            </div>
	                                        </div>
	                                    	<?php
	                                    }  
	                                   	?>
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
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>
<!-- DataTables -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script> -->
<script type="text/javascript">

    $(document).ready(function () {

        

        $('#example_filter input').keyup(function() {
            table
              .search(
                jQuery.fn.dataTable.ext.type.search.string(this.value)
              )
              .draw();
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

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.reload();
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Registro ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<input type="button" value="GUARDAR REGISTRO HISTÓRICO" name="guarda_his" id="guarda_his" class="btn btn-xs btn-icon btn-primary"></input>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<input type="button" value="GUARDAR REGISTRO HISTÓRICO" name="guarda_his" id="guarda_his" class="btn btn-xs btn-icon btn-primary"></input>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            //  }
        }


        $(document).on( "click","#guarda_his" , function() {
        	$('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
            var_conrecup = $('#val_his').data('conrecup');
            var_piescr = $('#val_his').data('piescr');
            var_chpr = $('#val_his').data('chpr');
            var_chrecup = $('#val_his').data('chrecup');
            var_piefal = $('#val_his').data('piefal');
            var_cantchprec = $('#val_his').data('cantchprec');
            var_canconrecu = $('#val_his').data('canconrecu');
            var_canchrecup = $('#val_his').data('canchrecup');
            var_con = $('#val_his').data('con');

            alert(var_piescr);
            $.ajax({
                type: 'POST',
                url: ("guarda_his_registro.php"),
                data:"id_con="+var_con+"&conrecup="+var_conrecup+"&piescr="+var_piescr+"&chpr="+var_chpr+"&chrecup="+var_chrecup+"&piefal="+var_piefal+"&cantchprec="+var_cantchprec+"&canconrecu="+var_canconrecu+"&canchrecup="+var_canchrecup,
                dataType: 'json',
                success: function(data) {
                	resultado(data);
                    
                }
            })
        });

        
    });
</script>
</body>
</html>
