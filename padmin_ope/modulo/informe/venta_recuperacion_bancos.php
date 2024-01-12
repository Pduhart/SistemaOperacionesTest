<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";

require "../helpers/get_pagos_contados.php";
require "../helpers/get_saldos_pagar.php";
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
                                    proceso.opcion_pro = 26 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/venta_flujo_banco.php">INFORME POR BANCOS</a></li>
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
                                    proceso.opcion_pro = 27 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="../informe/venta_recuperacion_bancos.php">RESUMEN BANCOS Y CUADRO RECUPERACIÓN</a></li>
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
                                    proceso.opcion_pro = 28 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/informe_recuperacion_historico.php">HISTÓRICO RECUPERACIÓN</a></li>
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
                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                        	<div class="col-sm-6">
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
	                                                                        	$sel = "";
	                                                                        	if ($_SESSION["sesion_filtro_condominio_panel"]==$fila['id_con']) {
	                                                                        		$sel = "selected";
	                                                                        	}
	                                                                            ?>
	                                                                            <option value="<?php echo $fila['id_con'];?>" <?php echo $sel; ?>><?php echo utf8_encode($fila['nombre_con']);?></option>
	                                                                            <?php
	                                                                        }
	                                                                    }
	                                                                    ?>
	                                                                </select>
	                                                            </div>
	                                                        </div>
	                                                        <div class="col-sm-4">
	                                                            <div class="form-group">
	                                                                <label for="banco_fil">Ciudad:</label>
	                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
	                                                                <select class="form-control chico" id="ciudad_fil" name="ciudad_fil"> 
	                                                                    <option value="">Seleccione Ciudad</option>
	                                                                    <option value="1" <?php echo $_SESSION["sesion_filtro_ciudad_panel"] == 1 ? "selected" : ""; ?>>La Serena</option>
	                                                                    <option value="2" <?php echo $_SESSION["sesion_filtro_ciudad_panel"] == 2 ? "selected" : ""; ?>>Santiago</option>
	                                                                    <option value="100" <?php echo $_SESSION["sesion_filtro_ciudad_panel"] == 100 ? "selected" : ""; ?>>Contado</option>
	                                                                </select>
	                                                            </div>
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
                                                        $filtro_consulta .= " AND viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_ciudad_panel"])){
                                                    	if ($_SESSION["sesion_filtro_ciudad_panel"]<>100) {
                                                    		$texto_filtro = $_SESSION["sesion_filtro_ciudad_panel"] == 1 ? "La Serena" : "Santiago";
                                                    		$filtro_consulta .= " AND ven_cam.ciudad_notaria_ven = ".$_SESSION["sesion_filtro_ciudad_panel"];
                                                    	} else {
                                                    		$texto_filtro = "Contado";
                                                    		$filtro_consulta .= "";
                                                    	}
                                                        
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        
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
	                                                        <h3 class="box-title">Recuperación | <a class="btn btn-info" href="venta_recuperacion_bancos_exc.php?con=<?php echo $_SESSION["sesion_filtro_condominio_panel"]; ?>&ciu=<?php echo $_SESSION["sesion_filtro_ciudad_panel"]; ?>" target="_blank">Exporar Excel</a></h3> 
	                                                    </div>

	                                                    <!-- /.box-header -->
	                                                    <div class="box-body no-padding">
															<div class="row">
																<div class="col-md-5">
															
																</div>
																<div class="col-md-12">
																	<table class="table table-bordered">
																		<tr class="bg-gray color-palette">
																			<td>Banco</td>
																			<td>Pie Recibidos Dptos. UF</td>
																			<td>CH/Cont. Recuperados $</td>
																			<td>CH Recuperados UF</td>
																			<td>CH/Saldos Por Recuperar</td>
																			<td>CH/Saldos Por Recup. | No Escr.</td>
																			<td>CH/Saldos Por Recup. | Escri.</td>
																			<td>Contados Recuperados UF</td>
																		</tr>
	                                                                    <?php
	                                                                    

	                                                                    $fecha_hoy = date("Y-m-d");
	                                                                    $acumula_pie_pagado_total = 0;
	                                                                    $acumula_ch_rec_pesos = 0;
	                                                                    $acumula_ch_rec_uf = 0;
	                                                                    $acumula_saldos_ch_total = 0;
	                                                                    
	                                                                    $consulta = "SELECT id_ban, nombre_ban FROM banco_banco ORDER BY nombre_ban";
	                                                                    // $consulta = "SELECT id_ban, nombre_ban FROM banco_banco WHERE id_ban = 1 ORDER BY nombre_ban";
	                                                                    $conexion->consulta($consulta);
	                                                                    $fila_consulta_banco_original = $conexion->extraer_registro();
	                                                                    if(is_array($fila_consulta_banco_original)){
	                                                                        foreach ($fila_consulta_banco_original as $filaban) {
	                                                                        	$banco = utf8_encode($filaban['nombre_ban']);
	                                                                        	$id_ban = $filaban['id_ban'];

	                                                                        	$acumula_uf_recuperado = 0;
	                                                                    		$acumula_pesos_recuperado = 0;
	                                                                    		$acumula_saldos_ch = 0;
	                                                                    		$acumula_saldos_ch_no_escri = 0;
	                                                                    		$acumula_saldos_ch_escri = 0;


	                                                                    		$acumula_pie_pagados = 0;
	                                                                    		$acumula_saldos_pagados = 0;

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
			                                                                            ven.id_ban = ".$id_ban." AND
			                                                                            ven.id_for_pag = 1
			                                                                            ".$filtro_consulta."
			                                                                        ";
			                                                                    // echo $consulta;
			                                                                    $conexion->consulta($consulta);
			                                                                    $fila_consulta = $conexion->extraer_registro();
			                                                                    if(is_array($fila_consulta)){
			                                                                        foreach ($fila_consulta as $fila) {
			                                                                        	$credito_hipo = 0;
			                                                                        	$fecha_liq_ven = $fila["fecha_liq_ven"];
			                                                                        	$fecha_escritura_ven = $fila["fecha_escritura_ven"];
			                                                                        	$monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
			                                                                        	$id_ven = $fila["id_ven"];
			                                                                        	
			                                                                            if($fecha_liq_ven){
			                                                                            	if($fecha_liq_ven <= $fecha_hoy & $monto_liq_uf_ven > 0){
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
																								// REVISAR ESCRITURA
																								if ($fecha_escritura_ven<> '') {
	                                                                            					$acumula_saldos_ch_escri = $acumula_saldos_ch_escri + $credito_hipo;
		                                                                            			} else {
		                                                                            				$acumula_saldos_ch_no_escri = $acumula_saldos_ch_no_escri + $credito_hipo;
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

																							$saldoPagarCredito = get_saldos_pagar($id_ven,$conexion);

	                                                                            			$saldo_por_pagar = $saldoPagarCredito[0];

	                                                                            			// echo $saldo_por_pagar."-------<br>";

	                                                                            			if ($fecha_escritura_ven<> '') {
	                                                                            				$acumula_saldos_ch_escri = $acumula_saldos_ch_escri + $credito_hipo + $saldo_por_pagar;
	                                                                            			} else {
	                                                                            				$acumula_saldos_ch_no_escri = $acumula_saldos_ch_no_escri + $credito_hipo + $saldo_por_pagar;
	                                                                            			}
	                                                                            			$acumula_saldos_ch = $acumula_saldos_ch + $credito_hipo + $saldo_por_pagar;
			                                                                            }
			                                                                        }
			                                                                    }

			                                                                    // pie pagado
																				$total_abono = 0;
																				$total_uf = 0;
																				$pie_pagado_efectivo = 0;
															                    $consulta = 
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
															                            LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
															                        WHERE 
															                            ven.id_est_ven > 3 AND
			                                                                            ven.id_ban = ".$id_ban." AND
			                                                                            ven.id_for_pag = 1 AND
															                            (pag.id_cat_pag = 1 OR pag.id_cat_pag = 2)
															                            ".$filtro_consulta."
															                        ";
															                    // echo $consulta;
															                    $conexion->consulta($consulta);
															                    $fila_consulta = $conexion->extraer_registro();
															                    if(is_array($fila_consulta)){
															                        foreach ($fila_consulta as $fila_pag) {
																						$valor_uf_efectivo = 0;
																						$pie_pagado_efectivo = 0;
																						$pie_pagado_porcobrar = 0;
															                        	
															                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
															                                $fecha_real_mostrar = "";

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
															                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
															                                
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
															                            $total_abono = $total_abono + $monto_pag;
																						$total_uf = $total_uf + $abono_uf;
																						$acumula_pie_pagados = $acumula_pie_pagados + $pie_pagado_efectivo;
															                           
															                        }
															                    }
																				$acumula_saldos_ch_total_escri = 0;
																				$acumula_saldos_ch_total_no_escri = 0;
															                    $acumula_pie_pagado_total = $acumula_pie_pagado_total + $acumula_pie_pagados;
															                    $acumula_ch_rec_pesos = $acumula_ch_rec_pesos + $acumula_pesos_recuperado;
															                    $acumula_ch_rec_uf = $acumula_ch_rec_uf + $acumula_uf_recuperado;

															                    $acumula_saldos_ch_total = $acumula_saldos_ch_total + $acumula_saldos_ch;

															                    $acumula_saldos_ch_total_escri = $acumula_saldos_ch_total_escri + $acumula_saldos_ch_escri;

															                    $acumula_saldos_ch_total_no_escri = $acumula_saldos_ch_total_no_escri + $acumula_saldos_ch_no_escri;
																				if(isset($_SESSION["sesion_filtro_ciudad_panel"])){
															                      if ($_SESSION["sesion_filtro_ciudad_panel"]<>100) {

																                    if($acumula_pie_pagados<>0 || $acumula_pesos_recuperado<>0 || $acumula_uf_recuperado<>0 || $acumula_saldos_ch<>0 || $acumula_saldos_ch_escri<>0 || $acumula_saldos_ch_no_escri<>0){
																                    ?>
		                                                                            <tr>
		                                                                                <td><?php echo $banco;?></td>
		                                                                                <td><?php echo number_format($acumula_pie_pagados, 2, ',', '.');?></td>
		                                                                                <td><?php echo number_format($acumula_pesos_recuperado, 2, ',', '.');?></td>
		                                                                                <td><?php echo number_format($acumula_uf_recuperado, 2, ',', '.');?></td>
		                                                                                <td><?php echo number_format($acumula_saldos_ch, 2, ',', '.'); ?></td>
		                                                                                <td><?php echo number_format($acumula_saldos_ch_no_escri, 2, ',', '.'); ?></td>
		                                                                                <td><?php echo number_format($acumula_saldos_ch_escri, 2, ',', '.'); ?></td>
		                                                                                <td></td>
		                                                                            </tr>
		                                                                            <?php
		                                                                        	}
		                                                                          }

																			    }

	                                                                        }
	                                                                    }
	                                                                    ?>
	                                                                    <!-- CONTADOS -->
	                                                                    <?php 
	                                                                    // pie pagado
																		$total_abono = 0;
																		$total_uf = 0;
																		$pie_pagado_efectivo = 0;
																		$acumula_pie_pagados = 0;
													                    $consulta = 
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
													                            pag.id_for_pag,
													                            pag.id_cat_pag
													                        FROM
													                            pago_pago AS pag 
													                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
													                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
													                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
													                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
													                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
													                        WHERE 
													                            ven.id_est_ven > 3 AND
	                                                                            (ven.id_ban = 0 OR ven.id_ban = 17) AND
	                                                                            ven.id_for_pag = 2 AND
	                                                                            viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."
													                        ";
													                    // echo $consulta;
													                    $conexion->consulta($consulta);
													                    $fila_consulta = $conexion->extraer_registro();
													                    if(is_array($fila_consulta)){
													                        foreach ($fila_consulta as $fila_pag) {
																				$valor_uf_efectivo = 0;
																				$pie_pagado_efectivo = 0;
																				$pie_pagado_porcobrar = 0;
																				$saldos_pagado_efectivo = 0;
													                        	
													                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
													                                $fecha_real_mostrar = "";

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

																					if($fila_pag["id_cat_pag"] <> 3) {
																						$pie_pagado_porcobrar = $pie_pagado_porcobrar + $abono_uf;
																					} else {
																						$total_por_pagar_uf_saldo = $total_por_pagar_uf_saldo + $abono_uf;
																					}


													                            }
													                            else{
													                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
													                                
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

																					// if($fila_pag["id_cat_pag"] <> 3){
																					// 	$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;  
																					// } else {
																					// 	$saldos_pagado_efectivo = $saldos_pagado_efectivo + $abono_uf; 
																					// }
																					$total_pagado_pesos_pie = 0;
																					$total_pagado_pesos_saldo = 0;
																					if($fila_pag["id_cat_pag"] <> 3) {
																						$pie_pagado_efectivo = $pie_pagado_efectivo + $abono_uf;
																						$total_pagado_pesos_pie = $total_pagado_pesos_pie + $monto_pag;   
																					} else {
																						$saldos_pagado_efectivo = $saldos_pagado_efectivo + $abono_uf;
																						$total_pagado_pesos_saldo = $total_pagado_pesos_saldo + $monto_pag;   
																					}
																					        
													                            }
													                            $total_abono = $total_abono + $monto_pag;
																				$total_uf = $total_uf + $abono_uf;
																				$acumula_pie_pagados = $acumula_pie_pagados + $pie_pagado_efectivo;
																				$acumula_saldos_pagados = $acumula_saldos_pagados + $saldos_pagado_efectivo;

																				// nuevo


													                        }
													                    }

													                    $acumula_total_inmob = 0;
													                    $acumula_por_recibir_deptos = 0;
													                    $acumula_por_recibir_deptos_no_escri = 0;
													                    $acumula_por_recibir_deptos_escri = 0;

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
	                                                                            viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."
	                                                                        ";
	                                                                    // echo $consulta;
	                                                                    $conexion->consulta($consulta);
	                                                                    $fila_consulta = $conexion->extraer_registro();
																		$acumula_uf_recuperado_contado = 0;
	                                                                    if(is_array($fila_consulta)){
	                                                                        foreach ($fila_consulta as $fila) {
	                                                                        	$credito_hipo = 0;
	                                                                        	$id_ven = $fila["id_ven"];
	                                                                        	$fecha_liq_ven = $fila["fecha_liq_ven"];
	                                                                        	$monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
	                                                                        	$fecha_escritura_ven = $fila["fecha_escritura_ven"];

	                                                                        	$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

	                                                                        	// acumula valores deptos
	                                                                        	$acumula_total_inmob = $acumula_total_inmob + $total_monto_inmob;
	                                                                        	
	                                                                            if($fecha_liq_ven){
	                                                                            	if($fecha_liq_ven <= $fecha_hoy & $monto_liq_uf_ven > 0){
																			        	$pagado = 1;
																			        	$por_pagar = 0;
																			        	$acumula_uf_recuperado_contado = $acumula_uf_recuperado_contado + $fila["monto_liq_uf_ven"];
																			        	$acumula_pesos_recuperado = $acumula_pesos_recuperado + $fila["monto_liq_pesos_ven"];	

																			        	// $monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven;

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

	                                                                            $pagosVentaContado = get_pagos_contados($id_ven,$conexion);

	                                                                            //----trae lo ya cobrado de pagos tipo 1 y 2
                                                                            	$pie_pagado_efectivo = $pagosVentaContado[2];

                                                                            	//----trae lo ya cobrado de pagos tipo 3
                                                                            	$saldos_contados_cobrados = $pagosVentaContado[4];



	                                                                            if($monto_liq_uf_ven > 0) {
                                                                            		// $por_recibir_depto = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven - $saldos_contados_cobrados;
                                                                            		// ya no considera lo liquidado solo los pagos
                                                                            		$por_recibir_depto = $total_monto_inmob - $pie_pagado_efectivo - $saldos_contados_cobrados;
                                                                            	} else {
                                                                            		$por_recibir_depto = $total_monto_inmob - $pie_pagado_efectivo - $saldos_contados_cobrados;
                                                                            	}

	                                                                            if( $por_recibir_depto<0) {
	                                                                            	 $por_recibir_depto = 0;
	                                                                            }

	                                                                            // YA NO USA EL MONTO REGISTRADO COMO UF LIQUIDADAS, SOLO USA LOS RECIBIDO COMO PAGOS SALDO CONTADO.
	                                                                            // $acumula_uf_recuperado_contado = $acumula_uf_recuperado_contado + $fila["monto_liq_uf_ven"];

	                                                                            $acumula_uf_recuperado_contado = $acumula_uf_recuperado_contado + $saldos_contados_cobrados;

	                                                                            if($fecha_escritura_ven<>'') {
	                                                                            	$acumula_por_recibir_deptos_escri = $acumula_por_recibir_deptos_escri + $por_recibir_depto;
	                                                                            } else {
	                                                                            	$acumula_por_recibir_deptos_no_escri = $acumula_por_recibir_deptos_no_escri + $por_recibir_depto;
	                                                                            }
	                                                                            $acumula_por_recibir_deptos = $acumula_por_recibir_deptos +  $por_recibir_depto;

	                                                                        }
	                                                                    }


	                                                                    $total_contados_por_recibir_no_escri = $acumula_por_recibir_deptos_no_escri;
	                                                                    $total_contados_por_recibir_escri = $acumula_por_recibir_deptos_escri;
	                                                                    $total_contados_por_recibir = $acumula_por_recibir_deptos;
	                                                                    // $total_contados_por_recibir = $acumula_monto_por_recibir;
	                                                                    // $total_contados_por_recibir = $pie_pagado_porcobrar + $total_por_pagar_uf_saldo;
																		$acumula_ch_saldos_por_recuperar = 0;
																		$acumula_ch_saldos_por_recuperar_no_escri = 0;
																		$acumula_ch_saldos_por_recuperar_escri = 0;
																		if(isset($_SESSION["sesion_filtro_ciudad_panel"])){
																				if ($_SESSION["sesion_filtro_ciudad_panel"]==100) {

																					?>
																					<tr>
																						<td>CONTADO</td>
																						<td><?php echo number_format($acumula_pie_pagados, 2, ',', '.');?></td>
																						<td><?php echo number_format($acumula_pesos_recuperado, 2, ',', '.');?></td>
																						<td></td>
																						<td><?php echo number_format($total_contados_por_recibir, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($total_contados_por_recibir_no_escri, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($total_contados_por_recibir_escri, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($acumula_uf_recuperado_contado, 2, ',', '.'); ?></td>
																					</tr>

																					<?php 
																					$acumula_pie_pagado_total = $acumula_pie_pagados;

																					$acumula_ch_saldos_por_recuperar = $total_contados_por_recibir;
																					$acumula_ch_saldos_por_recuperar_escri = $total_contados_por_recibir_escri;
																					$acumula_ch_saldos_por_recuperar_no_escri = $total_contados_por_recibir_no_escri;


																					$acumula_ch_rec_pesos = $acumula_pesos_recuperado;
																					$acumula_ch_rec_uf = 0;

																				}else if($_SESSION["sesion_filtro_ciudad_panel"] =='') {
																					?>
																					<tr>
																						<td>CONTADO</td>
																						<td><?php echo number_format($acumula_pie_pagados, 2, ',', '.');?></td>
																						<td><?php echo number_format($acumula_pesos_recuperado, 2, ',', '.');?></td>
																						<td></td>
																						<td><?php echo number_format($total_contados_por_recibir, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($total_contados_por_recibir_no_escri, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($total_contados_por_recibir_escri, 2, ',', '.'); ?></td>
																						<td><?php echo number_format($acumula_uf_recuperado_contado, 2, ',', '.'); ?></td>
																					</tr>

																					<?php 
																					// $acumula_pie_pagado_total = $acumula_pie_pagados;
																					$acumula_pie_pagado_total = $acumula_pie_pagado_total + $acumula_pie_pagados;

																					$acumula_ch_rec_pesos = $acumula_ch_rec_pesos + $acumula_pesos_recuperado;

																					// $acumula_ch_saldos_por_recuperar = $total_contados_por_recibir;
																					
																					$acumula_ch_saldos_por_recuperar = $acumula_saldos_ch_total + $total_contados_por_recibir;
																					$acumula_ch_saldos_por_recuperar_escri = $acumula_saldos_ch_total_escri + $total_contados_por_recibir_escri;
																					$acumula_ch_saldos_por_recuperar_no_escri = $acumula_saldos_ch_total_no_escri + $total_contados_por_recibir_no_escri;

																				} else {
																					$acumula_ch_saldos_por_recuperar = $acumula_saldos_ch_total;
																					$acumula_ch_saldos_por_recuperar_no_escri = $acumula_saldos_ch_total_no_escri;
																					$acumula_ch_saldos_por_recuperar_escri = $acumula_saldos_ch_total_escri;
																					$acumula_uf_recuperado_contado = 0;
																				}

																		}
	                                                                    ?>

																		<tr class="bg-light-blue color-palette">
																			<td>Totales</td>
																			<td><?php echo number_format($acumula_pie_pagado_total, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_ch_rec_pesos, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_ch_rec_uf, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_ch_saldos_por_recuperar, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_ch_saldos_por_recuperar_no_escri, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_ch_saldos_por_recuperar_escri, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_uf_recuperado_contado, 2, ',', '.'); ?></td>
																		</tr>
																		<?php 
																		$suma_total = 0;
																		    if(isset($_SESSION["sesion_filtro_ciudad_panel"])){
																				if ($_SESSION["sesion_filtro_ciudad_panel"]==100) {
																					
																					$suma_total = $acumula_pie_pagado_total + $acumula_ch_saldos_por_recuperar + $acumula_uf_recuperado_contado;
																					// $suma_total = $acumula_pie_pagado_total + $acumula_ch_saldos_por_recuperar_no_escri + $acumula_ch_saldos_por_recuperar_escri + $acumula_uf_recuperado_contado;

																				} else if($_SESSION["sesion_filtro_ciudad_panel"]=='') {

																					$suma_total = $acumula_pie_pagado_total + $acumula_ch_rec_uf + $acumula_ch_saldos_por_recuperar + $acumula_uf_recuperado_contado;
																					// $suma_total = $acumula_pie_pagado_total + $acumula_ch_rec_uf + $acumula_ch_saldos_por_recuperar_escri + $acumula_ch_saldos_por_recuperar_no_escri + $acumula_uf_recuperado_contado;

																				} else {

																					$suma_total = $acumula_pie_pagado_total + $acumula_ch_rec_uf + $acumula_ch_saldos_por_recuperar;
																					// $suma_total = $acumula_pie_pagado_total + $acumula_ch_rec_uf + $acumula_ch_saldos_por_recuperar_escri + $acumula_ch_saldos_por_recuperar_no_escri;
																				}
																		    }
																		?>
																		<tr>
																			<td class="bg-light-blue color-palette">Total UF</td>
																			<td class="bg-light-blue color-palette"><?php echo number_format($suma_total, 2, ',', '.'); ?></td>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td></td>
																			<td></td>
																		</tr>
																	</table>
																	<?php 

																	if (isset($_SESSION["sesion_filtro_ciudad_panel"])) {
																	?>
																	<!-- TABLA RECUPERACIÓN -->
																		<table class="table table-bordered">
																			<tr>
																				<td colspan="8" class="text-center">
																					POR RECUPERAR AL <?php echo date("d-m-Y"); ?> - 
																					<?php 
																					echo intval($_SESSION["sesion_filtro_ciudad_panel"]) === 1 
																					? "La Serena" 
																					: (intval($_SESSION["sesion_filtro_ciudad_panel"]) === 2 
																					? "Santiago"
																					: "Contado");
																					 ?>
																				</td>
																			</tr>
																			<?php 
																			if (intval($_SESSION["sesion_filtro_ciudad_panel"])==1) {
																				// SERENA
																			 ?>
																			<tr class="bg-gray color-palette">
																				<td><b>Semana de Pago</b></td>
																				<td><b>2 Semanas</b> (ECR23-ECR26)</td>
																				<td><b>10 Semanas</b> (ECR21-ECR22)</td>
																				<td><b>11 Semanas</b> (ECR20)</td>
																				<td><b>12 Semanas</b> (ECR18-ECR19)</td>
																				<td><b>14 Semanas</b> (ECR15-ECR17)</td>
																				<td><b>15 Semanas</b> (ECR11-ECR14)</td>
																				<td><b>16 Semanas</b> (ECR1-ECR10)</td>
																			</tr>
																			<?php 
																			} else if(intval($_SESSION["sesion_filtro_ciudad_panel"])==2) {
																				// SANTIAGO
																			?>
																			<tr class="bg-gray color-palette">
																				<td><b>Semana de Pago</b></td>
																				<td><b>2 Semanas</b> (ECR23-ECR26)</td>
																				<td><b>11 Semanas</b> (ECR21-ECR22)</td>
																				<td><b>12 Semanas</b> (ECR20)</td>
																				<td><b>13 Semanas</b> (ECR18-ECR19)</td>
																				<td><b>16 Semanas</b> (ECR15-ECR17)</td>
																				<td><b>17 Semanas</b> (ECR11-ECR14)</td>
																				<td><b>18 Semanas</b> (ECR1-ECR10)</td>
																			</tr>
																			<?php 
																			} else {
																				// contado
																			?>
																			<tr class="bg-gray color-palette">
																				<td><b>Semana de Pago (ECO23)</b></td>
																				<td><b>1 Semanas</b> (ECO21)</td>
																				<td><b>8 Semanas</b> (ECO18)</td>
																				<td><b>9 Semanas</b> (ECO16)</td>
																				<td><b>10 Semanas</b> (ECO15)</td>
																				<td><b>14 Semanas</b> (ECO13)</td>
																				<td><b>15 Semanas</b> (ECO6)</td>
																			</tr>
																			<?php 
																			}
																			if (intval($_SESSION["sesion_filtro_ciudad_panel"])<>100) {
																			?>
																			<tr>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL1
																						// ECR26 ----------------------------- 0sem
																						$acumula_semana_0 = 0;
																						$eta_0 = 48;
																						$eta_prox = 50;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_0." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_0 = $acumula_semana_0 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL2
																						// ECR23 ------------------------------- 2sem
																						$acumula_semana_2 = 0;
																						$eta_2 = 44;
																						$eta_prox = 48;
																						$consulta_2 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_2." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_2 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_2);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_2 = $acumula_semana_2 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL3
																						// ECR21 -------------------------- 10sem
																						$acumula_semana_10 = 0;
																						$eta_10 = 42;
																						$eta_prox = 44;
																						$consulta_10 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_10." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";

					                                                                    $consulta_10 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";

					                                                                    $conexion->consulta($consulta_10);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_10 = $acumula_semana_10 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL4
																						// ECR20 ---------------------------- 11sem
																						$acumula_semana_11 = 0;
																						$eta_11 = 41;
																						$eta_prox = 42;
																						$consulta_11 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_11." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_11 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_11);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_11 = $acumula_semana_11 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL5
																						// ECR18 ------------------------------- 12sem
																						$acumula_semana_12 = 0;
																						$eta_12 = 39;
																						$eta_prox = 41;
																						$consulta_12 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_12." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_12 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_12);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_12 = $acumula_semana_12 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL6
																						// ECR14 ------------------------------ 14sem
																						$acumula_semana_14 = 0;
																						$eta_15 = 36;
																						$eta_prox = 39;
																						$consulta_14 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_15." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_14 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_14);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_14 = $acumula_semana_14 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<?php 
																				if(intval($_SESSION["sesion_filtro_ciudad_panel"])<>100) {
																				?>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL7
																						// ECR11 ------------------------ 15sem
																						$acumula_semana_15 = 0;
																						$eta_14 = 32;
																						$eta_prox = 36;
																						$consulta_15 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_14." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_15 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_15);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_15 = $acumula_semana_15 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<?php } ?>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL 8
																						// ECR6 ----------------------------- 16sem
																						$acumula_semana_16 = 0;
																						$eta_1 = 51;
																						$eta_prox = 32;
																						$consulta_16 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_credito_ven,
					                                                                            ven.monto_credito_real_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 1 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_1." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							    ".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_16 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    // echo $consulta_16;
					                                                                    $conexion->consulta($consulta_16);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								if ($fila["monto_credito_real_ven"]<>0) {
																									$credito_hipo = $fila["monto_credito_real_ven"];
																								} else {
																									$credito_hipo = $fila["monto_credito_ven"];
																								}
																								$acumula_semana_16 = $acumula_semana_16 + $credito_hipo;
		            																			?>
		            																			<tr>
		            																				<td><?php echo $id_ven; ?> - <?php echo number_format($credito_hipo, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																			</tr>
																			<?php 
																			} else {
																				// por recuperar contados
																				//------------------------------------------ CONTADOS
																			?>
																			<tr>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL1
																						// ECO23 ---- 0sem
																						$eta_0 = 21;
																						$eta_prox = 57;
																						$acumula_semana_0 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_0." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
		            																				$acumula_semana_0 = $acumula_semana_0 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL2
																						// ECO21 ------------------------------------ 1sem
																						$eta_2 = 55;
																						$eta_prox = 21;
																						$acumula_semana_2 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_2." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
		            																				$acumula_semana_2 = $acumula_semana_2 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL3
																						// ECO18 ----------------------------- 8sem
																						$eta_10 = 17;
																						$eta_prox = 55;
																						$acumula_semana_10 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_10." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    // echo $consulta_0;
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                   
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {


		            																				 $acumula_semana_10 = $acumula_semana_10 + $por_recuperar_venta;


		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL4
																						// ECO16 ----------------------------- 9sem
																						$eta_11 = 53;
																						$eta_prox = 17;
																						$acumula_semana_11 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_11." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
		            																				$acumula_semana_11 = $acumula_semana_11 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL5
																						// ECO15 ----------------------------- 10sem
																						$eta_12 = 16;
																						$eta_prox = 53;
																						$acumula_semana_12 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_12." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
		            																				$acumula_semana_12 = $acumula_semana_12 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL6
																						// ECRO13 ----------------------------- 14sem
																						$eta_14 = 14;
																						$eta_prox = 16;
																						$acumula_semana_14 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_14." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
		            																			if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
		            																				$acumula_semana_14 = $acumula_semana_14 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																				<td>
																					<table style="width: 100%">
																						<?php 
																						// COL7
																						// ECO6 ----------------------- 15sem
																						$eta_16 = 1;
																						$eta_prox = 14;
																						$acumula_semana_16 = 0;
																						$consulta_0 = "
					                                                                        SELECT
					                                                                            ven.monto_ven,
					                                                                            ven.descuento_ven,
					                                                                            ven.id_ven,
					                                                                            ven.monto_vivienda_ven,
					                                                                            ven.monto_estacionamiento_ven,
					                                                                            ven.monto_bodega_ven,
					                                                                            ven.pie_cancelado_ven
					                                                                        FROM
					                                                                            venta_venta AS ven
					                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
					                                                                        WHERE
					                                                                            ven.id_est_ven > 3 AND
					                                                                            ven.id_for_pag = 2 AND
					                                                                            EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_16." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    ) AND NOT EXISTS(
																							        SELECT 
																							            ven_eta.id_ven
																							        FROM
																							            venta_etapa_venta AS ven_eta
																							        WHERE
																							            ven.id_ven = ven_eta.id_ven AND
																							            ven_eta.id_eta = ".$eta_prox." AND (ven_eta.id_est_eta_ven = 3 OR ven_eta.id_est_eta_ven = 1 OR ven_eta.id_est_eta_ven = 2)
																							    )
																							".$filtro_consulta."
					                                                                        ";
					                                                                    $consulta_0 .= " AND NOT EXISTS ( SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven.id_ven = ven_liq.id_ven AND ven_liq.monto_liq_uf_ven <> '' )";
					                                                                    $conexion->consulta($consulta_0);
					                                                                    $fila_consulta = $conexion->extraer_registro();
					                                                                    if(is_array($fila_consulta)){
					                                                                        foreach ($fila_consulta as $fila) {
																								$id_ven = $fila['id_ven'];
																								
																								$total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

																								$contado_pagado_efectivo = 0;

																								$consulta = 
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
																			                            pag.id_for_pag,
																			                            pag.id_cat_pag
																			                        FROM
																			                            pago_pago AS pag 
																			                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
																			                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
																			                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
																			                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
																			                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
																			                        WHERE 
																			                        	ven.id_ven = ".$id_ven."
																			                        ";
																			                    // echo $consulta;
																			                    $conexion->consulta($consulta);
																			                    $fila_consulta = $conexion->extraer_registro();
																			                    if(is_array($fila_consulta)){
																			                        foreach ($fila_consulta as $fila_pag) {
																										$valor_uf_efectivo = 0;
																										$pie_pagado_efectivo = 0;
																										$pie_pagado_porcobrar = 0;
																										$saldos_pagado_efectivo = 0;
																			                        	
																			                            if ($fila_pag["fecha_real_pag"]=="0000-00-00" || $fila_pag["fecha_real_pag"]==null) { //abonos no cancelados aún
																			                                $fecha_real_mostrar = "";

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
																			                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila_pag["fecha_real_pag"]));
																			                                
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

																											$contado_pagado_efectivo = $contado_pagado_efectivo + $abono_uf;  
																											        
																			                            }
																			                        }
																			                    }

																			                    $por_recuperar_venta = $total_monto_inmob - $contado_pagado_efectivo;
																			                    
																			                    if (round($por_recuperar_venta)<>0 && round($por_recuperar_venta)>0) {
																			                    	$acumula_semana_16 = $acumula_semana_16 + $por_recuperar_venta;
		            																			?>
		            																			<tr>
		            																				<td><?php echo number_format($por_recuperar_venta, 2, ',', '.'); ?></td>
		            																			</tr>
		            																			<?php
		            																			}
		            																		}
		            																	}
																						 ?>
																					</table>
																				</td>
																			</tr>
																			<?php
																			}
																			?>
																			<tr>
																				<td><?php echo number_format($acumula_semana_0, 2, ',', '.'); ?></td>
																				<td><?php echo number_format($acumula_semana_2, 2, ',', '.'); ?></td>
																				<td><?php echo number_format($acumula_semana_10, 2, ',', '.'); ?></td>
																				<td><?php echo number_format($acumula_semana_11, 2, ',', '.'); ?></td>
																				<td><?php echo number_format($acumula_semana_12, 2, ',', '.'); ?></td>
																				<td><?php echo number_format($acumula_semana_14, 2, ',', '.'); ?></td>
																				<?php 
																				if(intval($_SESSION["sesion_filtro_ciudad_panel"])<>100) {
																				?>
																				<td><?php echo number_format($acumula_semana_15, 2, ',', '.'); ?></td>
																				<?php } ?>
																				<td><?php echo number_format($acumula_semana_16, 2, ',', '.'); ?></td>
																			</tr>
																		</table>
																	<?php
																	}
																	?>
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

<script type="text/javascript">

    $(document).ready(function () {


        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_condominio = $('#condominio').val();
            var_banco = $('#banco_fil').val();
            var_ciudad = $('#ciudad_fil').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"condominio="+var_condominio+"&banco="+var_banco+"&ciudad="+var_ciudad,
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

            // alert(var_canchrecup);
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
