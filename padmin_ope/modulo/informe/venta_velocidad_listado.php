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

.info-box-text small{
	font-size: .85rem;
}

.progress-description, .info-box-text {
    white-space: normal;
    line-height: 15px;
}

.info-box {
    min-height: 120px;
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
                                <li class="active"><a href="venta_velocidad_listado.php">VELOCIDAD DE VENTAS</a></li>
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
                                <li><a href="venta_recuperacion_listado.php">RECUPERACIÓN</a></li>
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
                                    proceso.opcion_pro = 12 AND
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
                                                    <div class="col-sm-5">
                                                        
                                                        <div class="col-sm-4">
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
                                                <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button>
                                                <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                                  <i>Filtro: 
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
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= 
                                                        " 
                                                        AND EXISTS 
                                                        (
                                                        SELECT 
                                                        	ven_cie.id_cie
                                                        FROM
                                                        	cierre_venta_cierre AS ven_cie
                                                        	INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                        	INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                        	INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                        WHERE
                                                        	ven_cie.id_cie = cie.id_cie AND
															tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"]."
                                                    	)";
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
                                                <div class="box">
                                                    <div class="box-header">
                                                        <h3 class="box-title">KPI Ventas</h3>
                                                    </div>
                                                    <?php  
                                                    $mes_actual = date("m");
                                                    $anio_actual = date("Y");
                                                    $hoy = date("Y-m-d");
                                                    $venta_total_proyecto = 0;
                                                    $unidad_total_proyecto = 0;
                                                    $venta_real_vendida_proyecto = 0;
                                                    $unidad_real_vendida_proyecto = 0;
                                                    $venta_real_oopp_proyecto = 0;
                                                    $venta_real_vendida_proyecto_mes_actual = 0;
                                                    $unidad_real_vendida_proyecto_mes_actual = 0;


                                                    $consulta = 
                                                        "
                                                        SELECT 
                                                            cie.fecha_hasta_cie
                                                        FROM 
                                                            cierre_cierre AS cie
                                                        WHERE
                                                            cie.id_cie > 0
                                                            ".$filtro_consulta_cierre."
                                                        ORDER BY
                                                            id_cie
                                                        DESC
                                                        LIMIT 0,1
                                                        ";
                                                    
                                                    $conexion->consulta($consulta);
                                                    $cantidad_cierre = $conexion->total();
                                                    if($cantidad_cierre > 0){
                                                        $fila = $conexion->extraer_registro_unico();
                                                        $fecha_hasta_cie = $fila["fecha_hasta_cie"];
                                                    } else {
                                                    	$cantidad_cierre = 1;
                                                    	$fecha_hasta_cie = date("Y-m-d");
                                                    }
                                                    

                                                    $consulta = 
                                                        "
                                                        SELECT 
                                                            IFNULL(SUM(viv.valor_viv),0) AS suma,
                                                            IFNULL(COUNT(viv.id_viv),0) AS cantidad
                                                        FROM 
                                                            vivienda_vivienda AS viv
                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                        WHERE
                                                            viv.id_viv > 0
                                                            ".$filtro_consulta."
                                                        ";
                                                    $conexion->consulta($consulta);
                                                    $fila = $conexion->extraer_registro_unico();
                                                    $venta_total_proyecto = $fila["suma"];
                                                    $unidad_total_proyecto = $fila["cantidad"];

                                                    // unidades disponibles precio
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
                                                    $unidad_dispo_proyecto = $fila["cantidad"];


                                                    // Ventas que entraron a operaciones
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
													    ((ven_eta.id_eta=".$n_etaco_segunda_eta.") OR (ven_eta.id_eta=".$n_etacr_segunda_eta."))
													    ".$filtro_consulta."
													    ";
													// echo $consulta_ventas_inciadas_oopp;
													$conexion->consulta($consulta_ventas_inciadas_oopp);
                                                    $fila = $conexion->extraer_registro_unico();
                                                    $venta_real_oopp_proyecto = $fila["suma"];
                                                    $unidad_real_oopp_proyecto = $fila["cantidad"];


              //                                       $consulta_ventas_promesa = 
													 //  "
													 //  SELECT 
													 //    IFNULL(SUM(ven.monto_ven),0) AS suma,
              //                                           IFNULL(COUNT(ven.id_ven),0) AS cantidad
													 //  FROM
													 //    venta_venta as ven,
													 //    vivienda_vivienda as viv,
													 //    torre_torre as tor
													 //  WHERE
													 //    (ven.id_est_ven = 4 or ven.id_est_ven = 5) AND 
														// ven.id_viv = viv.id_viv AND
													 //    viv.id_tor = tor.id_tor AND
													 //    NOT EXISTS(
													 //        SELECT 
													 //            ven_eta.id_ven
													 //        FROM
													 //            venta_etapa_venta AS ven_eta
													 //        WHERE
													 //            ven_eta.id_ven = ven.id_ven
													 //    )
													 //  ";

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
                                                    $unidad_promesas_proyecto = $fila["cantidad"];
													
													// las promesas mas las opp
                                                    $unidad_real_vendida_proyecto = $unidad_promesas_proyecto + $unidad_real_oopp_proyecto;

                                                    $consulta = 
                                                        "
                                                        SELECT 
                                                            IFNULL(SUM(ven.monto_ven),0) AS suma,
                                                            IFNULL(COUNT(ven.id_ven),0) AS cantidad
                                                        FROM 
                                                            venta_venta AS ven
                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                        WHERE
                                                            ven.id_est_ven IN (4,5,6,7) AND
                                                            MONTH(ven.fecha_ven) = '".$mes_actual."' AND
                                                            YEAR(ven.fecha_ven) = '".$anio_actual."'
                                                            ".$filtro_consulta."
                                                        ";
                                                    $conexion->consulta($consulta);
                                                    $fila = $conexion->extraer_registro_unico();
                                                    $venta_real_vendida_proyecto_mes_actual = $fila["suma"];
                                                    $unidad_real_vendida_proyecto_mes_actual = $fila["cantidad"];

                                                    if($cantidad_cierre > 0){
                                                        $fecha_inicio_trimestre = date('Y-m-d',strtotime ( '-3 month' , strtotime ($fecha_hasta_cie)));
                                                        $mes_inicio_trimestre = date("m",strtotime($fecha_inicio_trimestre));
                                                        $anio_inicio_trimestre = date("Y",strtotime($fecha_inicio_trimestre));
                                                        $fecha_inicio_trimestre = "01-".$mes_inicio_trimestre."-".$anio_inicio_trimestre;
                                                        $fecha_inicio_trimestre = date("Y-m-d",strtotime($fecha_inicio_trimestre));
                                                        $consulta = 
                                                            "
                                                            SELECT 
                                                                IFNULL(SUM(ven.monto_ven),0) AS suma,
                                                                IFNULL(COUNT(ven.id_ven),0) AS cantidad
                                                            FROM 
                                                                venta_venta AS ven
                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                            WHERE
                                                                ven.id_est_ven IN (4,5,6,7) AND
                                                                ven.fecha_ven >= '".$fecha_inicio_trimestre."' AND
                                                                ven.fecha_ven <= '".$fecha_hasta_cie."'
                                                                ".$filtro_consulta."
                                                            ";
                                                        $conexion->consulta($consulta);
                                                        $fila = $conexion->extraer_registro_unico();
                                                        $venta_real_vendida_proyecto_ultimo_trimestre = $fila["suma"];
                                                        $unidad_real_vendida_proyecto_ultimo_trimestre = $fila["cantidad"];
                                                    }
                                                    else{
                                                        $venta_real_vendida_proyecto_ultimo_trimestre = 0;
                                                        $unidad_real_vendida_proyecto_ultimo_trimestre = 0;
                                                    }
                                                    



                                                    $promedio_dia = 0;
                                                    $numero_dia_mayor = 0;
                                                    if(is_array($fila_consulta_condominio_original)){
                                                        foreach ($fila_consulta_condominio_original as $fila) {
                                                            $fila['id_con'];
                                                            $fila['fecha_venta_con'];
                                                            $dias = (strtotime($hoy)-strtotime($fila['fecha_venta_con']))/86400;
                                                            $dias = abs($dias); 
                                                            $dias = floor($dias);
                                                            if($dias > $numero_dia_mayor){
                                                                $numero_dia_mayor = $dias;
                                                            } 
                                                        }
                                                    }
                                                    $promedio_mes = 1;
                                                    if($numero_dia_mayor > 0){
                                                        $promedio_mes = $numero_dia_mayor / 30;

                                                    }
														
													// echo $promedio_mes."-----------".$unidad_real_vendida_proyecto;
													// ventas totales
													$venta_total_proyecto = $venta_real_oopp_proyecto + $venta_promesas_proyecto + $venta_dispo_proyecto;

                                                    //-------- CALCULOS
                                                    $venta_recuperar = $venta_total_proyecto - $venta_real_oopp_proyecto - venta_promesas_proyecto;
                                                    $venta_recuperar_unidad = $unidad_total_proyecto - $unidad_real_oopp_proyecto - $unidad_promesas_proyecto;

													// velocidad de venta
                                                    if($unidad_real_vendida_proyecto > 0){
                                                        $velocidad_venta_proyecto = $unidad_real_vendida_proyecto / $promedio_mes;
                                                    }
                                                    else{
                                                        $velocidad_venta_proyecto = 0;
                                                    }

                                                    // meses agotar stock
                                                    if($velocidad_venta_proyecto > 0){
                                                        $mes_agotar_stock = $venta_recuperar_unidad / $velocidad_venta_proyecto;
                                                    }
                                                    else{
                                                        $mes_agotar_stock = 0;
                                                    }

                                                    // último trimestre
                                                    $velocidad_venta_ultimo_trimestre = $unidad_real_vendida_proyecto_ultimo_trimestre / 3;
                                                    if($velocidad_venta_ultimo_trimestre > 0){
                                                        $mes_agotar_stock_ultimo_trimestre = $venta_recuperar_unidad / $velocidad_venta_ultimo_trimestre;
                                                    }
                                                    else{
                                                        $mes_agotar_stock_ultimo_trimestre = 0;
                                                    }


                                                    ?>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
														<div class="row">
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-green"><i class="fa fa-bar-chart"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Venta Total Proyecto<br><small>Ventas Acumuladas + Por recuperar+ unidades por vender</small></span>
								                            			<span class="info-box-number"><?php echo number_format($venta_total_proyecto, 0, ',', '.');?> UF</span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-green"><i class="fa fa-database"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Ventas Acumuladas <small>Ventas en escrituración</small></span>
								                            			<span class="info-box-number"><?php echo number_format($venta_real_oopp_proyecto, 0, ',', '.');?> UF (<?php echo number_format($unidad_real_oopp_proyecto, 0, ',', '.');?> Un.)</span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-green"><i class="fa fa-shopping-cart"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Ventas en estado Promesa</span>
								                            			<span class="info-box-number"><?php echo number_format($venta_promesas_proyecto, 0, ',', '.');?> UF (<?php echo number_format($unidad_promesas_proyecto, 0, ',', '.');?> Un.)</span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-green"><i class="fa fa-calculator"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Unidades por Vender</span>
								                            			<span class="info-box-number"><?php echo number_format($venta_dispo_proyecto, 0, ',', '.');?> UF (<?php echo number_format($unidad_dispo_proyecto, 0, ',', '.');?> Un.)</span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            	</div>
						                        		<div class="row">
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Ventas Mes actual</span>
								                            			<span class="info-box-number"><?php echo number_format($venta_real_vendida_proyecto_mes_actual, 0, ',', '.');?> UF (<?php echo number_format($unidad_real_vendida_proyecto_mes_actual, 0, ',', '.');?> Un.)</span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-aqua"><i class="fa fa-tachometer"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Veloc. Venta Proy.<small>Deptos. Promesados dividido por la cantidad de meses desde incio de ventas hasta la fecha de HOY</small></span>
								                            			<span class="info-box-number"><?php echo number_format($velocidad_venta_proyecto, 2, ',', '.');?> (unid./m)</span>
								                            			<span class="info-box-number2" data-toggle="tooltip" data-placement="bottom" title="Promedio de unidades vendidas por mes desde el comienzo de la venta"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								                            		</div>
								                            	</div>
						                            		</div>
                                                            <div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-aqua"><i class="fa fa-hourglass-half"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Meses Agotar Stock Proyecto<small>cantidad de departamentos disponibles dividido por la velocidad de venta</small></span>
								                            			<span class="info-box-number"><?php echo number_format($mes_agotar_stock, 2, ',', '.');?> meses</span>
								                            			<span class="info-box-number2" data-toggle="tooltip" data-placement="bottom" title="Meses necesarios para vender todo el stock"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-aqua"><i class="fa fa-tachometer"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Velocidad Venta últ. Trimestre</span>
								                            			<span class="info-box-number"><?php echo number_format($velocidad_venta_ultimo_trimestre, 2, ',', '.');?> (unid./m)</span>
								                            			<span class="info-box-number2" data-toggle="tooltip" data-placement="bottom" title="Promedio de unidades vendidas por mes del último trimestre cerrado"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
								                            		</div>
								                            	</div>
						                            		</div>
						                            		<div class="col-sm-3">
						                            			<div class="info-box">
								                            		<span class="info-box-icon bg-aqua"><i class="fa fa-hourglass-half"></i></span>
								                            		<div class="info-box-content">
								                            			<span class="info-box-text">Meses Agotar Stock Últ. Trimestre</span>
								                            			<span class="info-box-number"><?php echo number_format($mes_agotar_stock_ultimo_trimestre, 2, ',', '.');?> meses</span>
								                            			<span class="info-box-number2" data-toggle="tooltip" data-placement="bottom" title="Meses necesarios para vender todo el stock segun velocidad del último trimestre"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
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
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
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
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        
        var table = $('#example').DataTable( {
            "pageLength": 1000,
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
                { "sType": "string" }
            ]
        });

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "date-uk-pre": function ( a ) {
                var ukDatea = a.split('/');
                return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
            },

            "date-uk-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "date-uk-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });
        $.fn.dataTable.moment( 'DD.MM.YYYY' );
        table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-6:eq(1)' );
        
    });
</script>
</body>
</html>
