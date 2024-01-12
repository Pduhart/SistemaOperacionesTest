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

require "../helpers/get_pagos_contados.php";
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Tubo / Flujo
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
                                <li class="active"><a href="../informe/venta_flujo_banco.php">INFORME POR BANCOS</a></li>
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
                                <li><a href="../informe/venta_recuperacion_bancos.php">RESUMEN BANCOS Y CUADRO RECUPERACIÓN</a></li>
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
	                                                                <label for="banco_fil">Banco:</label>
	                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
	                                                                <select class="form-control chico" id="banco_fil" name="banco_fil"> 
	                                                                    <option value="">Seleccione Banco</option>
	                                                                    <?php  
	                                                                    $consulta = "SELECT id_ban, nombre_ban FROM banco_banco WHERE id_ban <> 17 ORDER BY nombre_ban";
	                                                                    $conexion->consulta($consulta);
	                                                                    $fila_consulta_banco_original = $conexion->extraer_registro();
	                                                                    if(is_array($fila_consulta_banco_original)){
	                                                                        foreach ($fila_consulta_banco_original as $fila) {
	                                                                        	$sel = "";
	                                                                        	if ($_SESSION["sesion_filtro_banco_panel"]==$fila['id_ban']) {
	                                                                        		$sel = "selected";
	                                                                        	}
	                                                                            ?>
	                                                                            <option value="<?php echo $fila['id_ban'];?>" <?php echo $sel; ?>><?php echo utf8_encode($fila['nombre_ban']);?></option>
	                                                                            <?php
	                                                                        }
	                                                                    }
	                                                                    $sel = "";
                                                                    	if ($_SESSION["sesion_filtro_banco_panel"]==100) {
                                                                    		$sel = "selected";
                                                                    	}
	                                                                    ?>
	                                                                    <option value="100" <?php echo $sel; ?>>CONTADO</option>
	                                                                    <?php 
	                                                                    $sel = "";
                                                                    	if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
                                                                    		$sel = "selected";
                                                                    	}
	                                                                     ?>
	                                                                    <option value="1000" <?php echo $sel; ?>>TODAS LAS OPERACIONES</option>
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

                                                    if(isset($_SESSION["sesion_filtro_banco_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_banco_original));
                                                        $fila_consulta_banco = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_banco[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_banco)){
                                                            foreach ($fila_consulta_banco as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_banco_panel"],$fila_consulta_banco)){
                                                                    $key = array_search($_SESSION["sesion_filtro_banco_panel"], $fila_consulta_banco);
                                                                    $texto_filtro = $fila_consulta_banco[$key + 1];
                                                                }
                                                            }
                                                        }

                                                        if ($_SESSION["sesion_filtro_banco_panel"]==100) {
                                                        	?>
                                                        	<span class="label label-primary">CONTADO</span>  
                                                        	<?php
                                                        	$filtro_consulta .= " AND ven.id_ban = 0 AND ven.id_for_pag = 2";
                                                        } else if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
                                                        	?>
                                                        	<span class="label label-primary">TODAS LAS OPERACIONES</span>  
                                                        	<?php
                                                        	$filtro_consulta .= " ";
                                                        } else {
                                                        	?>
                                                        	<span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        	<?php
                                                        	$filtro_consulta .= " AND ven.id_ban = ".$_SESSION["sesion_filtro_banco_panel"]." AND ven.id_for_pag = 1";
                                                    	}
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_ciudad_panel"])){
                                                        $texto_filtro = $_SESSION["sesion_filtro_ciudad_panel"] == 1 ? "La Serena" : "Santiago";
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND ven_cam.ciudad_notaria_ven = ".$_SESSION["sesion_filtro_ciudad_panel"];
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
                                        if(isset($_SESSION["sesion_filtro_condominio_panel"]) && isset($_SESSION["sesion_filtro_banco_panel"])){  
	                                        ?>
	                                        <div class="col-md-12">
	                                            <div class="row" id="contenedor_tabla">
	                                                <div class="box">
	                                                    <div class="box-header">
	                                                        <h3 class="box-title">Flujo por Banco</h3>
	                                                        <a href="venta_flujo_banco_exc.php?con=<?php echo $_SESSION["sesion_filtro_condominio_panel"]; ?>&ban=<?php echo $_SESSION["sesion_filtro_banco_panel"]; ?>&ciu=<?php echo $_SESSION["sesion_filtro_ciudad_panel"]; ?>" target="_blank" class="btn btn-info btn-sm">EXPORTAR EXCEL</a>
	                                                    </div>

	                                                    <!-- /.box-header -->
	                                                    <div class="box-body no-padding">
															<div class="row">
																<div class="col-md-5">
															
																</div>
																<div class="col-md-12">
																	<table class="table table-bordered">
																		<tr class="bg-gray color-palette">
																			<td>N°</td>
																			<td>Nombre</td>
																			<td>Depto.</td>
																			<?php 
																			if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
																			 ?>
																			<td>Banco</td>
																			<?php 
																			}
																			 ?>
																			<td>Precio</td>
																			<td>Pagado</td>
																			<td>Por Pagar</td>
																			<td>Pie Recibido en UF</td>
																			<td>Pie Recibido en pesos</td>
																			<td>Monto por Recibir UF</td>
																			<?php 
																			if ($_SESSION["sesion_filtro_banco_panel"]<>100) {
																			 ?>
																			<td>Precio Venta</td>
																			<?php 
																			}
																			 ?>
																			<td>Fecha firma Cliente</td>
																			<td>Notaría</td>
																			<td>Monto recibido UF</td>
																			<td>Fecha</td>
																			<td>Monto en $ según Liquid.</td>
																		</tr>
	                                                                    <?php
	                                                                    $contador = 1;
	                                                                    $acumula_total_monto_inmob = 0;
	                                                                    $acumula_pie_pagado_efectivo = 0;
	                                                                    $acumula_monto_por_recibir = 0;
	                                                                    $acumula_monto_liq_uf_ven = 0;
	                                                                    $acumula_monto_liq_pesos_ven = 0;

	                                                                    $fecha_hoy = date("Y-m-d");
	                                                                    
	                                                                    $consulta = "
	                                                                        SELECT DISTINCT																	
	                                                                            viv.nombre_viv,
	                                                                            ven.monto_vivienda_ven,
	                                                                            ven.monto_ven,
	                                                                            ven.descuento_ven,
	                                                                            ven.monto_estacionamiento_ven,
                        														ven.monto_bodega_ven,
	                                                                            ven.id_ven,
	                                                                            ven.fecha_escritura_ven,
	                                                                            pro.nombre_pro,
	                                                                            pro.apellido_paterno_pro,
	                                                                            pro.apellido_materno_pro,
	                                                                            ven_liq.fecha_liq_ven,
	                                                                            ven_liq.monto_liq_uf_ven,
	                                                                            ven.pie_cancelado_ven,
	                                                                            ven.monto_reserva_ven,
	                                                                            ven_liq.monto_liq_pesos_ven,
	                                                                            ven.monto_credito_real_ven,
	                                                                            ven.monto_credito_ven,
	                                                                            ven.id_for_pag,
	                                                                            ven.id_ban
	                                                                        FROM
	                                                                            venta_venta AS ven
	                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
	                                                                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
	                                                                        LEFT JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
	                                                                        LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
	                                                                        WHERE
	                                                                            ven.id_est_ven > 3
	                                                                            ".$filtro_consulta."
	                                                                        
	                                                                        ";

	                                                                    if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
	                                                                    	// TODAS LAS OPERACIONES
	                                                                    	$consulta .= " ORDER BY ven.id_ban ASC";
	                                                                    }    

	                                                                    $conexion->consulta($consulta);
	                                                                    $fila_consulta = $conexion->extraer_registro();
	                                                                    if(is_array($fila_consulta)){
	                                                                        foreach ($fila_consulta as $fila) {

	                                                                        	$monto_liq_uf_ven = 0;
	                                                                            $nombre_viv = utf8_encode($fila["nombre_viv"]);
	                                                                            $monto_ven = $fila["monto_ven"];
	                                                                            $descuento_ven = $fila["descuento_ven"];
	                                                                            $pie_cancelado_ven = $fila["pie_cancelado_ven"];
	                                                                            $monto_reserva_ven = $fila["monto_reserva_ven"];
	                                                                            $id_ven = $fila["id_ven"];
	                                                                            $id_for_pag = $fila["id_for_pag"];
	                                                                            $fecha_escritura_ven = $fila["fecha_escritura_ven"];
	                                                                            if($fecha_escritura_ven) {
	                                                                            	$fecha_escritura_ven = date("d-m-Y",strtotime($fecha_escritura_ven));
	                                                                            }

	                                                                            $id_ban = $fila["id_ban"];
	                                                                            if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
		                                                                            $consulta_ban = "SELECT id_ban, nombre_ban FROM banco_banco WHERE id_ban = ".$id_ban."";
		                                                                    		$conexion->consulta($consulta_ban);
		                                                                    		$filaban = $conexion->extraer_registro_unico();
		                                                                    		$nombre_ban = utf8_encode($filaban['nombre_ban']);
		                                                                    	}

	                                                                            
	                                                                            $nombre_cliente = utf8_encode($fila["nombre_pro"]." ".$fila["apellido_paterno_pro"]." ".$fila["apellido_materno_pro"]);
	                                                                            $fecha_liq_ven = $fila["fecha_liq_ven"];
	                                                                            if($fecha_liq_ven){
	                                                                            	if($fecha_liq_ven <= $fecha_hoy){
																			        	$pagado = 1;
																			        	$por_pagar = 0;
																			        } else {
																			        	$pagado = 0;
																			        	$por_pagar = 1;
																			        }


	                                                                            	$fecha_liq_ven = date("d-m-Y",strtotime($fecha_liq_ven));

	                                                                            } else {
	                                                                            	$fecha_liq_ven = "";
	                                                                            	$pagado = 0;
																			        $por_pagar = 1;
	                                                                            }
	                                                                            
	                                                                            $monto_liq_uf_ven = $fila["monto_liq_uf_ven"];
	                                                                            $monto_liq_pesos_ven = $fila["monto_liq_pesos_ven"];

	                                                                            $acumula_monto_liq_uf_ven = $acumula_monto_liq_uf_ven + $fila["monto_liq_uf_ven"];
	                                                                            $acumula_monto_liq_pesos_ven = $acumula_monto_liq_pesos_ven + $fila["monto_liq_pesos_ven"];

	                                                                            // Crédito
	                                                                            if ($fila["monto_credito_real_ven"]<>0) {
																					$credito_hipo = $fila["monto_credito_real_ven"];
																				} else {
																					$credito_hipo = $fila["monto_credito_ven"];
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
															                            -- ban.nombre_ban,
															                            for_pag.nombre_for_pag,
															                            pag.fecha_pag,
															                            pag.fecha_real_pag,
															                            pag.numero_documento_pag,
															                            pag.monto_pag,
															                            est_pag.nombre_est_pag,
															                            pag.id_est_pag,
															                            pag.id_ven,
															                            ven.fecha_ven,
															                            ven.pie_cobrar_ven,
															                            pag.id_for_pag
															                        FROM
															                            pago_pago AS pag 
															                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
															                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
															                            -- INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
															                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
															                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
															                        WHERE 
															                            pag.id_ven = ? AND
															                            (pag.id_cat_pag = 1 OR pag.id_cat_pag = 2)
															                        ";
															                    $conexion->consulta_form($consulta,array($id_ven));
																				$totalPagos = conexion::select('SELECT SUM(monto_pag) as totalPagos FROM pago_pago WHERE id_ven='.$id_ven);
															                    $fila_consulta = $conexion->extraer_registro();
															                    if(is_array($fila_consulta)){
															                        foreach ($fila_consulta as $fila_pag) {
																						$valor_uf_efectivo = 0;
																						// $pie_pagado_efectivo = 0;
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
															                           
															                        }
															                    }


															                    $precio_venta = $credito_hipo + $pie_pagado_efectivo;

															                    // $total_monto_inmob = $monto_ven - $descuento_ven;

															                    $total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];

															                    $acumula_total_monto_inmob = $acumula_total_monto_inmob + $total_monto_inmob;

															                    if ($id_for_pag==1) {
																                    if ($monto_liq_uf_ven==0) {

																                    	$monto_por_recibir = $credito_hipo;
																                    } else {
																                    	$monto_por_recibir = 0;
																                    }
																                } else {
																                	$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo;
																                }
															                    

															                    if ($id_for_pag==1) {
															                    	$consulta_notaria = 
												                                    "
												                                    SELECT 
												                                        valor_campo_eta_cam_ven
												                                    FROM
												                                        venta_etapa_campo_venta
												                                    WHERE
												                                        id_ven = ? AND id_eta = 27 AND id_cam_eta = 15";
												                                    $conexion->consulta_form($consulta_notaria,array($id_ven));
												                                    $filanot = $conexion->extraer_registro_unico();
												                                    $notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);

												                                } else {
											                                    	$consulta_notaria = 
												                                    "
												                                    SELECT 
												                                        valor_campo_eta_cam_ven
												                                    FROM
												                                        venta_etapa_campo_venta
												                                    WHERE
												                                        id_ven = ? AND id_eta = 5 AND id_cam_eta = 2";
												                                    $conexion->consulta_form($consulta_notaria,array($id_ven));
												                                    $filanot = $conexion->extraer_registro_unico();
												                                    $notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);
												                                }

												                                //-------------- AJUSTE PAGOS CONTADOS
												                                if($id_for_pag==2){
												                                	// prueba función
	                                                                            	$pagosVentaContado = get_pagos_contados($id_ven,$conexion);

	                                                                            	$monto_por_recibir = 0;

	                                                                            	// $pie_pagado_efectivo = $pagosVentaContado[1] - $fila["monto_liq_uf_ven"];
	                                                                            	//----trae lo ya cobrado de pagos tipo 1 y 2
	                                                                            	$pie_pagado_efectivo = $pagosVentaContado[2];

	                                                                            	//----trae lo ya cobrado de pagos tipo 3
	                                                                            	$saldos_contados_cobrados = $pagosVentaContado[4];
	                                                                            	if($monto_liq_uf_ven > 0) {
	                                                                            		// $monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $monto_liq_uf_ven - $saldos_contados_cobrados;
	                                                                            		// ya no considera lo liquidado, solo pagos
	                                                                            		$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $saldos_contados_cobrados;
	                                                                            	} else {
	                                                                            		$monto_por_recibir = $total_monto_inmob - $pie_pagado_efectivo - $saldos_contados_cobrados;
	                                                                            	}

	                                                                            	if($monto_por_recibir<0) {
	                                                                            		$monto_por_recibir = 0;
	                                                                            	}

	                                                                            	// YA NO USA EL MONTO REGISTRADO COMO UF LIQUIDADAS, SOLO USA LOS RECIBIDO COMO PAGOS SALDO CONTADO.
	                                                                            	if($monto_liq_uf_ven > 0){
	                                                                            		$monto_liq_uf_ven = $saldos_contados_cobrados;
	                                                                            	} else {
	                                                                            		$monto_liq_uf_ven = $saldos_contados_cobrados;
	                                                                            	}                                                                       	


	                                                                            	$acumula_pie_pagado_efectivo = $acumula_pie_pagado_efectivo + $pie_pagado_efectivo;
	                                                                            	$acumula_monto_por_recibir = $acumula_monto_por_recibir + $monto_por_recibir;

	                                                                            	$precio_venta = "-";


												                                } else { //créditos

												                                	if($monto_liq_uf_ven==0){
												                                		$pie_cancelado = $pie_cancelado_ven + $monto_reserva_ven;

													                                	$total = $pie_cancelado + $fila_pag["pie_cobrar_ven"] + $credito_hipo;

																						$saldo_pie = $total - ($credito_hipo + $pie_pagado_porcobrar + $pie_pagado_efectivo);


																						$monto_por_recibir = $monto_por_recibir + round($saldo_pie,2);
												                                	}
												                                	
												                                	$acumula_pie_pagado_efectivo = $acumula_pie_pagado_efectivo + $pie_pagado_efectivo;
												                                	$acumula_monto_por_recibir = $acumula_monto_por_recibir + $monto_por_recibir;

												                                	$precio_venta = number_format($precio_venta, 2, ',', '.');
												                                }
															                    ?>
	                                                                            <tr>
	                                                                                <td><?php echo $contador;?> <?php echo $id_ven;?></td>
	                                                                                <td><?php echo $nombre_cliente;?></td>
	                                                                                <td><?php echo $nombre_viv;?></td>
	                                                                                <?php 
																					if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
																					 ?>
	                                                                                <td><?php echo $nombre_ban;?></td>
	                                                                            	<?php } ?>
	                                                                                <td><?php echo number_format($total_monto_inmob, 2, ',', '.');?></td>
	                                                                                <td><?php echo $pagado; ?></td>
	                                                                                <td><?php echo $por_pagar; ?></td>
	                                                                                <td><?php echo number_format($pie_pagado_efectivo, 2, ',', '.');?></td>
	                                                                                <td><?php echo number_format($totalPagos[0]['totalPagos'], 0, ',', '.');?></td>
	                                                                                <td><?php echo number_format($monto_por_recibir, 2, ',', '.');?></td>
	                                                                                <?php 
																					if ($_SESSION["sesion_filtro_banco_panel"]<>100) {
																					 ?>
	                                                                                
	                                                                                <td><?php echo $precio_venta;?></td>
	                                                                            	<?php } ?>
	                                                                                <td><?php echo $fecha_escritura_ven;?></td>
	                                                                                <td><?php echo $notaria;?></td>
	                                                                                <td><?php echo number_format($monto_liq_uf_ven, 2, ',', '.');?></td>
	                                                                                <td><?php echo $fecha_liq_ven;?></td>
	                                                                                <td><?php echo number_format($monto_liq_pesos_ven, 0, ',', '.');?></td>
	                                                                            </tr>
	                                                                            <?php
	                                                                            $contador++;
	                                                                        }
	                                                                    }
	                                                                    ?>
																		<tr class="bg-light-blue color-palette">
																			<td>Totales</td>
																			<td></td>
																			<td></td>
																			<?php 
																			if ($_SESSION["sesion_filtro_banco_panel"]==1000) {
																			 ?>
																			<td></td>
																			<?php } ?>
																			<td><?php echo number_format($acumula_total_monto_inmob, 2, ',', '.'); ?></td>
																			<td></td>
																			<td></td>
																			<td><?php echo number_format($acumula_pie_pagado_efectivo, 2, ',', '.'); ?></td>
																			<td><?php echo number_format($acumula_monto_por_recibir, 2, ',', '.'); ?></td>
																			<?php 
																			if ($_SESSION["sesion_filtro_banco_panel"]<>100) {
																			 ?>
																			
																			<td></td>
																			<?php } ?>
																			<td></td>
																			<td></td>
																			<td><?php echo number_format($acumula_monto_liq_uf_ven, 2, ',', '.'); ?></td>
																			<td></td>
																			<td><?php echo number_format($acumula_monto_liq_pesos_ven, 2, ',', '.'); ?></td>
																		</tr>
																	</table>																	
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
