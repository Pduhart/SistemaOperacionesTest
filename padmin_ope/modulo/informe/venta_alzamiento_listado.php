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

    .bg-verde{
        background-color: #DCE7BF;
        text-align:center;
    }

    .bg-verdeclaro{
        background-color: #E6EED2;
    }

    .tabla{
        font-size: 1.2rem;
        max-width: 150%;
        width: 150%;
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
          Alzamiento
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Alzamiento</li>
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
                                    proceso.opcion_pro = 13 AND
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
                                <li class="active"><a href="venta_alzamiento_listado.php">ALZAMIENTO</a></li>
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
                                proceso.opcion_pro = 16 AND
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
                                                        $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= " AND cie.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
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
                                                        <h3 class="box-title">Alzamiento y Recuperación</h3>
                                                        <div class="box-tools pull-right">
                                                        	<a href="venta_alzamiento_exc.php" target="_blank" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Exportar Alzamiento Contable Excel"><i class="fa fa-file-excel-o text-green"></i></a>
                                                        	<a href="venta_recuperacion_exc.php" target="_blank" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Exportar Recuperación Final Excel"><i class="fa fa-file-excel-o text-green"></i></a>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body" style="overflow-x: hidden;">
														<div class="row">
															
															<div class="col-md-12 table-responsive">
																<table class="table table-bordered tabla">
																	<tr class="bg-verde">
																		<td colspan="3"></td>
																		<td colspan="2">Carta Resguardo</td>
																		<td colspan="3">Provision de fondos Alzamiento</td>
																		<td colspan="2">Pago Alzamiento</td>
																		<td colspan="5">Pago efectivo Credito Hipotecario a cuenta N° 330 </td>
																		<td colspan="9">Resumen</td>
																	</tr>
																	<tr class="bg-verdeclaro">
																		<td>Cliente</td>
																		<td>Dpto.</td>
																		<td>Valor Final Inmob.</td>
																		<td>UF</td>
																		<td>Banco</td>
																		<td>UF Alzamiento <i data-toggle="tooltip" data-placement="top" title="90% valor vivienda - carta resguardo" class="fa fa-question-circle" aria-hidden="true"></i></td>
																		<td>Valor UF día</td>
																		<td>Equivalente $</td>
																		<td>Fecha Cargo Cuenta 301</td>
																		<td>Fecha Abono Cuenta 330</td>
																		<td>Fecha Crédito</td>
																		<td>Valor $</td>
																		<td>Monto UF</td>
																		<td>N° días (solicitud/pago efect.)</td>
																		<td>Dif. UF Carta resguardo/Pago Efectivo</td>
																		<td>Valor Final Inmob.</td>
																		<td>Crédito</td>
																		<td>Abono Cliente</td>
																		<td>Total Pagado</td>
																		<td>Saldo UF</td>
																		<td>Valor Factura</td>
																		<td>Valor NC</td>
																		<td>Alzado</td>
																		<td>Pagado</td>
																	</tr>
                                                                    <?php
                                                                    $acumulado_monto = 0;
                                                                    $total_valor_liquidado_uf_credito = 0;
                                                                    
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            ven.id_ven,
                                                                            ven.fecha_ven,
                                                                            ven.monto_ven,
                                                                            ven.id_for_pag,
                                                                            ven.id_ban,
                                                                            ven.id_tip_pag,
                                                                            ven.descuento_ven,
                                                                            ven.monto_credito_ven,
                                                                            ven.monto_credito_real_ven,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            mode.id_mod,
                                                                            mode.nombre_mod,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            pro.id_pro,
                                                                            pro.nombre_pro,
                                                                            pro.apellido_paterno_pro,
                                                                            pro.apellido_materno_pro,
                                                                            ven.id_pie_ven,
                                                                            estado_venta.nombre_est_ven,
                                                                            ban.nombre_ban,
                                                                            ven.monto_vivienda_ven,
                                                                            ven_cam.monto_factura_ven,
                                                                            ven_cam.monto_nc_ven
                                                                        FROM 
                                                                            venta_venta AS ven
                                                                            INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
                                                                            -- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                            INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                            INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                                            LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
                                                                            LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
                                                                        WHERE 
                                                                            ven.id_ven > 0
                                                                            ".$filtro_consulta."
                                                                        ORDER BY 
                                                                            ven.fecha_ven
                                                                        "; 
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            if ($fila['fecha_ven'] == '0000-00-00') {
                                                                                $fecha_venta = "";
                                                                            }
                                                                            else{
                                                                                $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                                            }
                                                                            $acumulado_monto = $acumulado_monto + $fila['monto_ven'];


                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    IFNULL(SUM(pag.monto_pag / uf.valor_uf),0) AS pagado
                                                                                FROM
                                                                                    pago_pago AS pag
                                                                                    INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(pag.fecha_real_pag)
                                                                                WHERE
                                                                                    pag.id_ven = ?
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                            $fila_pago = $conexion->extraer_registro_unico();
                                                                            $uf_pagado = $fila_pago["pagado"];
                                                                            

                                                                            //---- LIMPIAR VARIABLE
                                                                            $fecha_solicitud_pago_credito = '';
                                                                            $fecha_pago_credito_real = '';
                                                                            $valor_carta_resguardo_contado = 0;
                                                                            $valor_carta_resguardo_credito = 0;
                                                                            $fecha_solicitud_alzamiento_credito = '';
                                                                            $fecha_solicitud_alzamiento_contado = '';
                                                                            $uf_alzamiento = 0;
                                                                            $valor_uf_alzamiento = 0;
                                                                            $equivalente_alzamiento = 0;
                                                                            $fecha_cargo_alzamiento_credito = '';
                                                                            $fecha_abono_alzamiento_credito = '';
                                                                            $fecha_cargo_alzamiento_contado = '';
                                                                            $fecha_abono_alzamiento_contado = '';
                                                                            $uf_fecha_pago_credito_real = 0;
                                                                            $alzado = 0;
                                                                            $pagado = 0;
                                                                            $valor_liquidado_peso = 0;
                                                                            $valor_liquidado_uf = 0;
                                                                            

                                                                            
                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    *
                                                                                FROM
                                                                                    venta_etapa_venta
                                                                                WHERE
                                                                                    id_ven = ? AND
                                                                                    id_eta IN(14,35,45,47)
                                                                                ORDER BY 
                                                                                    id_eta
                                                                                DESC
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                            $cantidad_etapa = $conexion->total();
                                                                            $fila_consulta_etapa = $conexion->extraer_registro();
                                                                            if(is_array($fila_consulta_etapa)){
                                                                                foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                                    switch ($fila_etapa["id_eta"]) {
                                                                                        case 14:
                                                                                            //------- ALZADO CONTADO
                                                                                            if($fila['id_for_pag'] == 2 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                                $alzado = 1;
                                                                                            }
                                                                                            break;
                                                                                        case 35:
                                                                                            //------- ALZADO CREDITO
                                                                                            if($fila['id_for_pag'] == 1 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                                $alzado = 1;
                                                                                            }
                                                                                            break;
                                                                                        case 45:
                                                                                            //------- FECHA CREDITO 
                                                                                            if($fila_etapa["id_est_eta_ven"] == 1){
                                                                                                $fecha_solicitud_pago_credito = $fila_etapa["fecha_hasta_eta_ven"];
                                                                                            }
                                                                                            
                                                                                            break;
                                                                                        case 47:
                                                                                            //------- FECHA PAGO CRDITO REAL
                                                                                            if($fila_etapa["id_est_eta_ven"] == 1){
                                                                                                $fecha_pago_credito_real = $fila_etapa["fecha_hasta_eta_ven"];
                                                                                            }
                                                                                            if($fila['id_for_pag'] == 1 && $fila_etapa["id_est_eta_ven"] == 1){
                                                                                                $pagado = 1;
                                                                                            }
                                                                                            break;
                                                                                    }
                                                                                }
                                                                            }

                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    *
                                                                                FROM
                                                                                    venta_etapa_campo_venta
                                                                                WHERE
                                                                                    id_ven = ? AND
                                                                                    id_cam_eta IN(14,25,35,42,43,44,45,46,47,48,49,50,51,52)
                                                                                ORDER BY 
                                                                                    id_eta
                                                                                DESC
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                            $cantidad_etapa = $conexion->total();
                                                                            $fila_consulta_etapa = $conexion->extraer_registro();
                                                                            if(is_array($fila_consulta_etapa)){
                                                                                foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                                    switch ($fila_etapa["id_cam_eta"]) {
                                                                                        case 14:
                                                                                            //------- FECHA SOLICITUD

                                                                                            break;
                                                                                        case 25:
                                                                                            //------- VALOR CARTA RESGUARDO CONTADO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_carta_resguardo_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            break;
                                                                                        case 35:
                                                                                            //------- VALOR CARTA RESGUARDO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_carta_resguardo_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            
                                                                                            break;
                                                                                        case 42:
                                                                                            //------- FECHA SOLICITUD ALZAMIENTO CREDITO
                                                                                            
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_solicitud_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                $fecha_solicitud_alzamiento_credito = date("Y-m-d",strtotime($fecha_solicitud_alzamiento_credito));
                                                                                            }
                                                                                            
                                                                                            break;
                                                                                        case 43:
                                                                                            //------- FECHA CARGO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_cargo_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                $fecha_cargo_alzamiento_credito = date("Y-m-d",strtotime($fecha_cargo_alzamiento_credito));
                                                                                            }
                                                                                            break;
                                                                                        case 44:
                                                                                            //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_abono_alzamiento_credito = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                $fecha_abono_alzamiento_credito = date("Y-m-d",strtotime($fecha_abono_alzamiento_credito));
                                                                                            }
                                                                                            break;
                                                                                        case 45:
                                                                                            //------- FECHA SOLICITUD
                                                                                            break;
                                                                                        case 46:
                                                                                            //------- FECHA SOLICITUD ALZAMIENTO CONTADO
                                                                                            
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_solicitud_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                $fecha_solicitud_alzamiento_contado = date("Y-m-d",strtotime($fecha_solicitud_alzamiento_contado));
                                                                                            }
                                                                                            
                                                                                            break;
                                                                                        case 47:
                                                                                            //------- FECHA CARGO ALZAMIENTO CONTADO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_cargo_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                if ($fecha_cargo_alzamiento_contado<>'' && $fecha_cargo_alzamiento_contado <> null) {
                                                                                                	$fecha_cargo_alzamiento_contado = date("Y-m-d",strtotime($fecha_cargo_alzamiento_contado));
                                                                                                }
                                                                                                
                                                                                            }
                                                                                            break;
                                                                                        case 48:
                                                                                            //------- FECHA ABONO ALZAMIENTO CONTADO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $fecha_abono_alzamiento_contado = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                                $fecha_abono_alzamiento_contado = date("Y-m-d",strtotime($fecha_abono_alzamiento_contado));
                                                                                            }
                                                                                            break;
                                                                                        // nuevo campos liquidado
                                                                                        case 51:
                                                                                            //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_liquidado_peso = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            break;
                                                                                        case 52:
                                                                                            //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_liquidado_uf = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            break;
                                                                                        case 49:
                                                                                            //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_liquidado_peso = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            break;
                                                                                        case 50:
                                                                                            //------- FECHA ABONO ALZAMIENTO CREDITO
                                                                                            if(!empty($fila_etapa["valor_campo_eta_cam_ven"])){
                                                                                                $valor_liquidado_uf = $fila_etapa["valor_campo_eta_cam_ven"];
                                                                                            }
                                                                                            break;
                                                                                        
                                                                                    }
                                                                                    
                                                                                }
                                                                            }
                                                                            //----- CREDITO
                                                                            if($fila['id_for_pag'] == 1){
                                                                                if(!empty($fecha_solicitud_alzamiento_credito)){
                                                                                    $consulta = 
                                                                                        "
                                                                                        SELECT 
                                                                                            valor_uf
                                                                                        FROM
                                                                                            uf_uf
                                                                                        WHERE
                                                                                            fecha_uf = ?
                                                                                        ";
                                                                                    $conexion->consulta_form($consulta,array($fecha_solicitud_alzamiento_credito));
                                                                                    $cantidad_uf = $conexion->total();
                                                                                    if($cantidad_uf > 0){
                                                                                        $fila_uf = $conexion->extraer_registro_unico();
                                                                                        $uf_alzamiento = $fila_uf["valor_uf"];
                                                                                    }
                                                                                }
                                                                                // se cambia a valor viv
                                                                                $valor_uf_alzamiento = ($fila['monto_vivienda_ven'] * 0.9) - $valor_carta_resguardo_credito;

                                                                                $equivalente_alzamiento = $valor_uf_alzamiento * $uf_alzamiento;

                                                                                if(!empty($fecha_solicitud_pago_credito)){
                                                                                    $fecha_solicitud_pago_credito = date("Y-m-d",strtotime($fecha_solicitud_pago_credito));
                                                                                }

                                                                                if(!empty($fecha_pago_credito_real)){
                                                                                    $fecha_pago_credito_real = date("Y-m-d",strtotime($fecha_pago_credito_real));

                                                                                    $consulta = 
                                                                                        "
                                                                                        SELECT 
                                                                                            valor_uf
                                                                                        FROM
                                                                                            uf_uf
                                                                                        WHERE
                                                                                            fecha_uf = ?
                                                                                        ";
                                                                                    $conexion->consulta_form($consulta,array($fecha_pago_credito_real));
                                                                                    $cantidad_uf = $conexion->total();
                                                                                    if($cantidad_uf > 0){
                                                                                        $fila_uf = $conexion->extraer_registro_unico();
                                                                                        $uf_fecha_pago_credito_real = $fila_uf["valor_uf"];
                                                                                    }
                                                                                }
                                                                                $total_monto_ven_credito = 0;
                                                                                $total_carta_resguardo_credito = 0;
                                                                                $total_uf_alzamiento_credito=0;
                                                                                $total_equivalente_alzamiento_credito=0;
                                                                                $total_valor_liquidado_peso_credito=0;
                                                                                $total_valor_liquidado_uf_credito=0;
                                                                                $total_uf_pagado_credito=0;
                                                                                $acumulado_factura=0;
                                                                                $acumulado_nc=0;

                                                                                $total_monto_ven_credito = $total_monto_ven_credito + $fila['monto_vivienda_ven'];
                                                                                $total_carta_resguardo_credito = $total_carta_resguardo_credito + $valor_carta_resguardo_credito;
                                                                                $total_uf_alzamiento_credito = $total_uf_alzamiento_credito + $valor_uf_alzamiento;
																				$total_equivalente_alzamiento_credito = $total_equivalente_alzamiento_credito + $equivalente_alzamiento;

																				$total_valor_liquidado_peso_credito = $total_valor_liquidado_peso_credito + $valor_liquidado_peso;
																				$total_valor_liquidado_uf_credito = $total_valor_liquidado_uf_credito + $valor_liquidado_uf;

																				$total_uf_pagado_credito = $total_uf_pagado_credito + $uf_pagado;

																				$acumulado_factura = $acumulado_factura + $fila['monto_factura_ven'];
                                                                            	$acumulado_nc = $acumulado_nc + $fila['monto_nc_ven'];

																				// echo $total_uf_pagado_credito."-";
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                                    <td><?php echo number_format($fila['monto_vivienda_ven'], 2, ',', '.');?></td>

                                                                                    <td><?php echo number_format($valor_carta_resguardo_credito, 2, ',', '.');?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_ban']);?></td>
                                                                                    <td><?php echo number_format($valor_uf_alzamiento, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($uf_alzamiento, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($equivalente_alzamiento, 0, ',', '.');?></td>
                                                                                    <td><?php 
                                                                                    	if ($fecha_cargo_alzamiento_credito<>'') {
                                                                                    		echo date("d-m-Y",strtotime($fecha_cargo_alzamiento_credito));
                                                                                    	} else {
                                                                                    		echo utf8_encode($fecha_cargo_alzamiento_credito);
                                                                                    	}
                                                                                    	?></td>
                                                                                    <td><?php 
                                                                                    	if ($fecha_abono_alzamiento_credito<>'') {
                                                                                    		echo date("d-m-Y",strtotime($fecha_abono_alzamiento_credito));
                                                                                    	} else {
                                                                                    		echo utf8_encode($fecha_abono_alzamiento_credito);
                                                                                    	}
                                                                                    	?></td>
                                                                                    <td><?php 
                                                                                    	if ($fecha_pago_credito_real<>'') {
                                                                                    		echo date("d-m-Y",strtotime($fecha_pago_credito_real));
                                                                                    	} else {
                                                                                    		echo utf8_encode($fecha_pago_credito_real);
                                                                                    	}
                                                                                    	?></td>
                                                                                    <!-- <td>NO sabemos</td> -->

                                                                                    <td><?php echo number_format($valor_liquidado_peso, 0, ',', '.');?></td>
                                                                                    <td><?php echo number_format($valor_liquidado_uf, 2, ',', '.');?></td>
                                                                                    <td>
                                                                                    <?php
	                                                                                $dias = (strtotime($fecha_solicitud_pago_credito)-strtotime($fecha_pago_credito_real))/86400;
	                                                                                $dias = abs($dias); 
	                                                                                $dias = floor($dias);
                                                                                    $total_diferencia_dias=0;
	                                                                                $total_diferencia_dias = $total_diferencia_dias + $dias;
	                                                                                echo $dias;
                                                                                     ?>
                                                                                    </td>
                                                                                    <?php 
																					// $dif_monto = $valor_carta_resguardo_credito - $valor_liquidado_uf;
                                                                                    $total_dif_monto_credito = 0;
                                                                                    $total_pagado_resumen=0;
                                                                                    $total_pagado_resumen_credito=0;
                                                                                    $total_saldo_uf_credito=0;
																					$dif_monto = $valor_carta_resguardo_credito - $fila['monto_vivienda_ven'];
																					$total_dif_monto_credito = $total_dif_monto_credito + $dif_monto;
                                                                                     ?>
                                                                                    <td><?php echo number_format($dif_monto, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($fila['monto_ven'], 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($valor_liquidado_uf, 2, ',', '.');?></td>
                                                                                    <?php 
                                                                                    $total_pagado_resumen = $valor_liquidado_uf + $uf_pagado;
                                                                                    $saldo_uf = $fila['monto_ven'] - $total_pagado_resumen;

                                                                                    $total_pagado_resumen_credito = $total_pagado_resumen_credito + $total_pagado_resumen;
                                                                                    $total_saldo_uf_credito = $total_saldo_uf_credito + $saldo_uf;
                                                                                    ?>
                                                                                    <td><?php echo number_format($total_pagado_resumen, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($uf_pagado, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($saldo_uf, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($fila['monto_factura_ven'], 0, ',', '.'); ?></td>
																					<td><?php echo number_format($fila['monto_nc_ven'], 0, ',', '.'); ?></td>
                                                                                    <td><?php echo number_format($alzado, 0, ',', '.');?></td>
                                                                                    <td><?php echo number_format($pagado, 0, ',', '.');?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            else{
                                                                                //------ CONTADO
                                                                                if(!empty($fecha_solicitud_alzamiento_contado)){
                                                                                    $consulta = 
                                                                                        "
                                                                                        SELECT 
                                                                                            valor_uf
                                                                                        FROM
                                                                                            uf_uf
                                                                                        WHERE
                                                                                            fecha_uf = ?
                                                                                        ";
                                                                                    $conexion->consulta_form($consulta,array($fecha_solicitud_alzamiento_contado));
                                                                                    $cantidad_uf = $conexion->total();
                                                                                    if($cantidad_uf > 0){
                                                                                        $fila_uf = $conexion->extraer_registro_unico();
                                                                                        $uf_alzamiento = $fila_uf["valor_uf"];
                                                                                    }
                                                                                }
                                                                                $valor_uf_alzamiento = ($fila['monto_vivienda_ven'] * 0.9) - $valor_carta_resguardo_contado;

                                                                                $equivalente_alzamiento = $valor_uf_alzamiento * $uf_alzamiento;
                                                                                $total_monto_ven_contado=0;
                                                                                $total_carta_resguardo_contado=0;
                                                                                $total_uf_alzamiento_contado=0;
                                                                                $total_equivalente_alzamiento_contado=0;
                                                                                $total_uf_pagado_contado=0;
                                                                                $total_monto_ven_contado = $total_monto_ven_contado + $fila['monto_vivienda_ven'];
                                                                                $total_carta_resguardo_contado = $total_carta_resguardo_contado + $valor_carta_resguardo_contado;
                                                                                $total_uf_alzamiento_contado = $total_uf_alzamiento_contado + $valor_uf_alzamiento;
																				$total_equivalente_alzamiento_contado = $total_equivalente_alzamiento_contado + $equivalente_alzamiento;
																				$total_uf_pagado_contado = $total_uf_pagado_contado + $uf_pagado;

																				$acumulado_factura = $acumulado_factura + $fila['monto_factura_ven'];
                                                                            	$acumulado_nc = $acumulado_nc + $fila['monto_nc_ven'];
                                                                                ?>
                                                                                <tr>
                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                                    <td><?php echo number_format($fila['monto_vivienda_ven'], 2, ',', '.');?></td>

                                                                                    <td><?php echo number_format($valor_carta_resguardo_contado, 2, ',', '.');?></td>
                                                                                    <td></td>
                                                                                    <td><?php echo number_format($valor_uf_alzamiento, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($uf_alzamiento, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($equivalente_alzamiento, 0, ',', '.');?></td>
                                                                                    <td>
                                                                                    	<?php 
                                                                                    		if ($fecha_cargo_alzamiento_contado<>'' && $fecha_cargo_alzamiento_contado <> null) {
                                                                                    			echo date("d-m-Y",strtotime($fecha_cargo_alzamiento_contado)); 
                                                                                    		}
                                                                                    		?></td>
                                                                                    <td><?php echo utf8_encode($fecha_abono_alzamiento_contado); ?></td> 
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td><?php echo number_format($fila['monto_ven'], 2, ',', '.');?></td><!-- valor inmobob-->
                                                                                    <td></td>
                                                                                    <?php 
                                                                                    $total_pagado_resumen_contado=0;
                                                                                    $total_saldo_uf_contado=0;
                                                                                    $total_pagado_resumen = $valor_liquidado_uf + $uf_pagado;
                                                                                    $saldo_uf = $fila['monto_ven'] - $total_pagado_resumen;
                                                                                    $total_pagado_resumen_contado = $total_pagado_resumen_contado + $total_pagado_resumen;
                                                                                    $total_saldo_uf_contado = $total_saldo_uf_contado + $saldo_uf;
                                                                                    ?>
                                                                                    <td><?php echo number_format($total_pagado_resumen, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($uf_pagado, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($saldo_uf, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($fila['monto_factura_ven'], 0, ',', '.'); ?></td>
																					<td><?php echo number_format($fila['monto_nc_ven'], 0, ',', '.'); ?></td>
                                                                                    <td><?php echo number_format($alzado, 0, ',', '.');?></td>
                                                                                    <td><?php echo number_format($pagado, 0, ',', '.');?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            $total_alzado=0;
                                                                            $total_pagado=0;
                                                                            $total_alzado += $alzado;
                                                                            $total_pagado += $pagado;
                                                                        }
                                                                    }

                                                                    $total_monto_inmob = $total_monto_ven_contado + $total_monto_ven_credito;
                                                                    $total_carta_resguardo = $total_carta_resguardo_contado + $total_carta_resguardo_credito;
                                                                    $total_uf_alzamiento = $total_uf_alzamiento_credito + $total_uf_alzamiento_contado;
                                                                    $total_equivalente_alzamiento = $total_equivalente_alzamiento_credito + $total_equivalente_alzamiento_contado;
                                                                    $total_pagado_resumen_completo = $total_pagado_resumen_credito + $total_pagado_resumen_contado;
                                                                    $total_uf_pagado_completo = $total_uf_pagado_contado + $total_uf_pagado_credito;
                                                                    $total_saldo_uf = $total_saldo_uf_credito + $total_saldo_uf_contado;
																	

                                                                    ?> 
																	<!-- totales -->
																	<tr class="bg-info font-weight-bold">
																		<td colspan="2">Totales</td>
																		<td><?php echo number_format($total_monto_inmob, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_carta_resguardo, 2, ',', '.');?></td>
																		<td></td>
																		<td><?php echo number_format($total_uf_alzamiento, 2, ',', '.');?></td>
																		<td></td>
																		<td>$ <?php echo number_format($total_equivalente_alzamiento, 0, ',', '.');?></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td>$ <?php echo number_format($total_valor_liquidado_peso_credito, 0, ',', '.');?></td>
																		<td><?php echo number_format($total_valor_liquidado_uf_credito, 2, ',', '.');?></td>
																		<td><?php echo $total_diferencia_dias; ?></td>
																		<td><?php echo number_format($total_dif_monto_credito, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_monto_inmob, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_valor_liquidado_uf_credito, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_pagado_resumen_completo, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_uf_pagado_completo, 2, ',', '.');?></td>
																		<td><?php echo number_format($total_saldo_uf, 2, ',', '.');?></td>
																		<td><?php echo number_format($acumulado_factura, 0, ',', '.'); ?></td>
																		<td><?php echo number_format($acumulado_nc, 0, ',', '.'); ?></td>
																		<td><?php echo $total_alzado; ?></td>
																		<td><?php echo $total_pagado; ?></td>
																	</tr>
																</table>
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
		$('[data-toggle="tooltip"]').popover({ boundary: 'window', container: 'body' });
        

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
