<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";

if(!isset($_SESSION["sesion_filtro_condominio_panel"])){
	$_SESSION["sesion_filtro_condominio_panel"] = 5;
}
?>
<title>Tubo Clientes | 00ppsav</title>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>plugins/select2/select2.min.css"> -->
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

/*.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de;
    border-radius: 0px;
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 26px;
    user-select: none;
    -webkit-user-select: none;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 21px;
}*/

.btn-aqui{
	font-weight: bold;
	text-decoration: underline;
	cursor: pointer;
}

table#example{
	width: 160% !important;
}

.wmd-view-topscroll, .wmd-view {
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
    border: none 0px RED;
}

.wmd-view-topscroll { height: 20px; }
.wmd-view { height: 100%; }
.scroll-div1 { 
    width: 160%; 
    overflow-x: scroll;
    overflow-y: hidden;
    height:20px;
}
.scroll-div2 { 
    width: 3000px; 
    height:20px;
}
</style>
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/datepicker/datepicker3.css"> -->
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
	<!-- modalo GGOOPP -->
	<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>

    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Tubo / Flujo
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Tubo / Flujo</li>
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
                            $tiene_tubo=0;
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
                            	$tiene_tubo=1;
                                ?>
                                <li class="active"><a href="../informe/informe_tubo.php">TUBO CLIENTES</a></li>
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
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <div class="row">
                                            <div id="contenedor_opcion"></div>
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    
                                                    <div class="col-sm-11">
                                                    
                                                        <div class="col-sm-2">
                                                        	<div class="form-group">
	                                                            <label for="condominio">Condominio:</label>
	                                                            <select class="form-control chico" id="condominio" name="condominio"> 
	                                                                <option value="">Seleccione Condominio</option>
	                                                                <?php  
	                                                                $consulta = "SELECT id_con, nombre_con FROM condominio_condominio ORDER BY nombre_con";
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
                                                    
                                                    
													// TORRE
                                                    if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                                                        $elimina_filtro = 1;
                                                         $consulta = 
                                                            "
                                                            SELECT
                                                                condo.nombre_con,
                                                                torre.nombre_tor
                                                            FROM 
                                                                torre_torre AS torre
                                                                INNER JOIN condominio_condominio AS condo ON torre.id_con = condo.id_con
                                                            WHERE
                                                                torre.id_tor = ?
                                                            ";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_condominio_panel"]));
                                                        $fila_consulta_condo = $conexion->extraer_registro_unico();
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($fila_consulta_condo["nombre_con"])." (".utf8_encode($fila_consulta_condo["nombre_tor"]).")";?></span> |
                                                        <?php
                                                        // $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_condominio_panel"]));
                                                        $filtro_consulta .= " AND viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."";
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
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
                                        	<?php 
                                        	if ($tiene_tubo>0) {
                                        	 ?>
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">
                                                    <div class="box-header">
                                                        <h3 class="box-title">Tubo Clientes</h3><br>

                                                        <a href="informe_tubo_plano.php" target="_blank" class="btn btn-info btn-sm">VER FORMATO PLANO Y EXCEL</a>
                                                    </div>
                                                    <!-- /.box-header -->

                                                    <div class="box-body no-padding">
                                                    	<div class="wmd-view-topscroll">
														    <div class="scroll-div1">
														    </div>
														</div>
                                                        <div class="table-responsive wmd-view">
                                                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                    	<th>N</th>
                                                                    	<th>Estado</th>
                                                                        <th>Depto</th>
                                                                        <th>Precio con Abono</th>
                                                                        <th>Precio Inmob.</th>
                                                                        <th>Cliente</th>
                                                                        <th>RUT</th>
                                                                        <th>Email</th>
                                                                        <th>Fono</th>
                                                                        <th>Vendedor</th>
                                                                        <th>Vendido</th>
                                                                        <th>Banco</th>
                                                                        <th>Por Vender</th>
                                                                        <th colspan="2">Crédito</th>
                                                                        <th>Por Escriturar</th>
                                                                        <th>Escrituradas</th>
                                                                        <th>Notaría</th>
                                                                        <th>N Repertorio</th>
                                                                        <th>Fecha Envío Carta Oferta</th>
                                                                        <th>Mes Escrituración</th>
                                                                        <th>Fecha Escritura</th>
                                                                        <th>Fecha Entrega</th>
                                                                        <th>Ent. Contab.</th>
                                                                        <th>CR Recepc.</th>
                                                                        <th>CR Aprobada</th>
                                                                        <th>Obs.</th>
                                                                    </tr>
                                                                    <tr>
                                                                    	<th colspan="13"></th>
                                                                    	<th>SI</th>
                                                                    	<th>NO</th>
                                                                    	<th colspan="12"></th>
                                                                    </tr>  
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $acumulado_monto = 0;
                                                                    $contador_depto = 0;

                                                                    $consulta = 
	                                                                    "
	                                                                    SELECT 
	                                                                        viv.id_viv,
	                                                                        viv.nombre_viv,
	                                                                        viv.valor_viv
	                                                                    FROM 
	                                                                        vivienda_vivienda AS viv
	                                                                    WHERE 
	                                                                        viv.id_viv > 0 AND
	                                                                        viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."
	                                                                    ORDER BY 
	                                                                        viv.id_viv
                                                                    "; 
	                                                                $conexion->consulta($consulta);
	                                                                $fila_consulta = $conexion->extraer_registro();
	                                                                if(is_array($fila_consulta)){
	                                                                    foreach ($fila_consulta as $fila) {
	                                                                    	$id_viv = $fila['id_viv'];
	                                                                    	$nombre_viv = utf8_encode($fila['nombre_viv']);
	                                                                    	$valor_viv = $fila['valor_viv'];
	                                                                    	$contador_depto++;

	                                                                    	$estado_venta = "";
                                                                        
	                                                                    	$fecha_envio_carta = "";
	                                                                    	$a_contab = "";
	                                                                    	$cr_aprobada = "";


	                                                                    	// va a buscar las venta
	                                                                    	$consulta_ven = 
		                                                                        "
		                                                                        SELECT 
		                                                                            ven.id_ven,
		                                                                            ven.fecha_ven,
		                                                                            ven.monto_ven,
		                                                                            ven.monto_vivienda_ven,
		                                                                            vend.id_vend,
		                                                                            vend.nombre_vend,
		                                                                            vend.apellido_paterno_vend,
		                                                                            vend.apellido_materno_vend,
		                                                                            pro.id_pro,
		                                                                            pro.nombre_pro,
		                                                                            pro.apellido_paterno_pro,
		                                                                            pro.apellido_materno_pro,
		                                                                            pro.rut_pro,
		                                                                            pro.correo_pro,
		                                                                            pro.fono_pro,
		                                                                            for_pag.id_for_pag,
		                                                                            for_pag.nombre_for_pag,
		                                                                            ven.descuento_ven,
		                                                                            ven.monto_estacionamiento_ven,
		                                                                            ven.monto_bodega_ven,
		                                                                            ven.monto_credito_ven,
		                                                                            ven.monto_credito_real_ven,
		                                                                            estado_venta.nombre_est_ven,
		                                                                            ven.id_ban,
		                                                                            ven.id_tip_pag,
		                                                                            ven.id_est_ven,
		                                                                            ven.fecha_escritura_ven
		                                                                        FROM 
		                                                                            venta_venta AS ven
		                                                                            INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
		                                                                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
		                                                                            INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
		                                                                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                                                                        WHERE 
		                                                                            ven.id_viv = ".$id_viv." AND ven.id_est_ven > 3
		                                                                        "; 
		                                                                    // echo $consulta;
		                                                                    // busca ventas activas de la viv 
		                                                                    $conexion->consulta($consulta_ven);
		                                                                    $tiene_venta = $conexion->total();
		                                                                    if($tiene_venta>0) {
		                                                                    	$fila_consulta = $conexion->extraer_registro();
			                                                                    if(is_array($fila_consulta)){
			                                                                        foreach ($fila_consulta as $fila_ven) {
			                                                                        	
			                                                                        	$notaria = "";

			                                                                        	$total_monto_inmob = ($fila_ven["monto_vivienda_ven"] + $fila_ven["monto_estacionamiento_ven"] + $fila_ven["monto_bodega_ven"]) - $fila_ven["descuento_ven"];


			                                                                            if ($fila_ven['fecha_escritura_ven']) {
			                                                                                $fecha_escritura = date("d-m-Y",strtotime($fila_ven['fecha_escritura_ven']));
			                                                                                $mes_escritura = date("n",strtotime($fila_ven['fecha_escritura_ven']));

			                                                                                
			                                                                                
			                                                                                $consulta_mes = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    nombre_mes
				                                                                                FROM
				                                                                                    mes_mes
				                                                                                WHERE
				                                                                                    id_mes = ?";
				                                                                            $conexion->consulta_form($consulta_mes,array($mes_escritura));
				                                                                            $fila_nomfec = $conexion->extraer_registro_unico();
				                                                                            $mes_escritura = utf8_encode($fila_nomfec['nombre_mes']);
				                                                                            $estado_venta = "Escriturado";

			                                                                                $estado_escritura = 1;
			                                                                            }
			                                                                            else{
			                                                                                $fecha_escritura = "";
			                                                                                $mes_escritura = "";
			                                                                                $estado_escritura = "";
			                                                                            }
			                                                                            
			                                                                            if ($fila_ven['id_for_pag']==1) { //crédito

			                                                                            	// emisión CR
			                                                                            	
			                                                                            	$consulta_emision_cr = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    id_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 34 AND id_est_eta_ven = 1";
				                                                                            $conexion->consulta_form($consulta_emision_cr,array($fila_ven["id_ven"]));
				                                                                            $emite_cr = $conexion->total();
				                                                                            if($emite_cr===0){
				                                                                            	$emite_cr = "";
				                                                                            }

				                                                                            // Envío Carta Oferta
			                                                                            	
			                                                                            	$consulta_envio_carta = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    fecha_hasta_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 24 AND id_est_eta_ven = 1";
				                                                                            $conexion->consulta_form($consulta_envio_carta,array($fila_ven["id_ven"]));
				                                                                            $envia_carta = $conexion->total();
				                                                                            if($envia_carta===0){
				                                                                            	$fecha_envio_carta = "";
				                                                                            } else {
				                                                                            	$fila_fecha_car = $conexion->extraer_registro_unico();
				                                                                            	$fecha_envio_carta = $fila_fecha_car['fecha_hasta_eta_ven'];
				                                                                            	 $fecha_envio_carta = date("d-m-Y",strtotime($fecha_envio_carta));
				                                                                            }

				                                                                            // Envío Contabilidad
			                                                                            	
			                                                                            	$consulta_env_contable = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    id_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 48 AND id_est_eta_ven = 1";
				                                                                            $conexion->consulta_form($consulta_env_contable,array($fila_ven["id_ven"]));
				                                                                            $a_contab = $conexion->total();
				                                                                            if($a_contab===0){
				                                                                            	$a_contab = "";
				                                                                            }

				                                                                            // CR AProbada
			                                                                            	
			                                                                            	$consulta_CR_aprobada = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    id_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 35 AND (id_est_eta_ven = 1 OR id_est_eta_ven = 2 OR id_est_eta_ven = 3)";
				                                                                            $conexion->consulta_form($consulta_CR_aprobada,array($fila_ven["id_ven"]));
				                                                                            $cr_aprobada = $conexion->total();
				                                                                            if($cr_aprobada===0){
				                                                                            	$cr_aprobada = "";
				                                                                            }


			                                                                            	// fecha entrega
				                                                                            $consulta_ent = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    ven_eta.fecha_hasta_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta AS ven_eta,
	                                                                                    			venta_etapa_campo_venta AS eta_cam_ven
				                                                                                WHERE
				                                                                                    ven_eta.id_ven = ? AND 
				                                                                                    ven_eta.id_eta = 29 AND 
	                                                                                    			ven_eta.id_eta_ven = eta_cam_ven.id_eta_ven AND 
	                                                                                    			valor_campo_eta_cam_ven <> ''
				                                                                                ";
				                                                                            $conexion->consulta_form($consulta_ent,array($fila_ven["id_ven"]));
				                                                                            $cantidad_fecha = $conexion->total();
				                                                                            if($cantidad_fecha > 0){
				                                                                                $fila_fecha_ent = $conexion->extraer_registro_unico();
				                                                                                if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
					                                                                                $fecha_entrega = "";
					                                                                            }
					                                                                            else{
					                                                                                $fecha_entrega = date("d-m-Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
					                                                                            }
				                                                                                
				                                                                            }
				                                                                            else{
				                                                                                $fecha_entrega = "";
				                                                                            }

				                                                                            $consulta_notaria = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    valor_campo_eta_cam_ven
				                                                                                FROM
				                                                                                    venta_etapa_campo_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 27 AND id_cam_eta = 15";


				                                                                            // Repertorio
				                                                                            $consulta_repertorio = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    valor_campo_eta_cam_ven
				                                                                                FROM
				                                                                                    venta_etapa_campo_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 27 AND id_cam_eta = 33";
				                                                                        } else {

				                                                                        	// fecha entrega
				                                                                            $consulta_ent = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    ven_eta.fecha_hasta_eta_ven
				                                                                                FROM
				                                                                                    venta_etapa_venta AS ven_eta
				                                                                                WHERE
				                                                                                    ven_eta.id_ven = ? AND 
				                                                                                    ven_eta.id_eta = 10
				                                                                                ";
				                                                                            $conexion->consulta_form($consulta_ent,array($fila_ven["id_ven"]));
				                                                                            $cantidad_fecha = $conexion->total();
				                                                                            if($cantidad_fecha > 0){
				                                                                                $fila_fecha_ent = $conexion->extraer_registro_unico();
				                                                                                if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
					                                                                                $fecha_entrega = "";
					                                                                            }
					                                                                            else{
					                                                                                $fecha_entrega = date("d-m-Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
					                                                                            }
				                                                                                
				                                                                            }
				                                                                            else{
				                                                                                $fecha_entrega = "";
				                                                                            }

				                                                                        	$consulta_notaria = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    valor_campo_eta_cam_ven
				                                                                                FROM
				                                                                                    venta_etapa_campo_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 5 AND id_cam_eta = 2";

				                                                                            // Repertorio
				                                                                            $consulta_repertorio = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    valor_campo_eta_cam_ven
				                                                                                FROM
				                                                                                    venta_etapa_campo_venta
				                                                                                WHERE
				                                                                                    id_ven = ? AND id_eta = 6 AND id_cam_eta = 1";
				                                                                        }
				                                                                        $conexion->consulta_form($consulta_notaria,array($fila_ven["id_ven"]));
				                                                                        $hay_not = $conexion->total();

				                                                                        $filarep = "";
				                                                                        $filanot = "";

				                                                                        $notaria = "";

				                                                                        if($hay_not){
				                                                                        	$filanot = $conexion->extraer_registro_unico();
				                                                                        	$notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);
				                                                                        }
				                                                                        


				                                                                        $conexion->consulta_form($consulta_repertorio,array($fila_ven["id_ven"]));
				                                                                        $hay_rep = $conexion->total();

				                                                                        // echo "-------------".$hay_rep;
				                                                                        $repertorio = "";
				                                                                        if($hay_rep){
				                                                                        	$filarep = $conexion->extraer_registro_unico();
				                                                                        	$repertorio = utf8_encode($filarep['valor_campo_eta_cam_ven']);
				                                                                        }
				                                                                        
				                                                                        


				                                                                        // observaciones
				                                                                        $lista_obs = "";
				                                                                        $consulta_obs = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    ven_obs.descripcion_obs_eta_ven,
				                                                                                    ven_obs.fecha_obs_eta_ven,
				                                                                                    usu.nombre_usu,
				                                                                                    usu.apellido1_usu
				                                                                                FROM
				                                                                                    venta_observacion_etapa_venta AS ven_obs,
				                                                                                    usuario_usuario AS usu
				                                                                                WHERE
				                                                                                    ven_obs.id_ven = ".$fila_ven["id_ven"]." AND
				                                                                                    ven_obs.id_usu = usu.id_usu
				                                                                                ORDER BY
				                                                                                	ven_obs.id_eta DESC";
				                                                                        $conexion->consulta($consulta_obs);
					                                                                    $tiene_obs = $conexion->total();
					                                                                    if($tiene_obs>0) {
					                                                                    	$fila_consulta = $conexion->extraer_registro();
						                                                                    if(is_array($fila_consulta)){
						                                                                        foreach ($fila_consulta as $filaobs) {
						                                                                        	$fecha_obs = $filaobs['fecha_obs_eta_ven'];
						                                                                        	$fecha_obs = date("d-m-Y",strtotime($fecha_obs));
						                                                                        	$nombre_usu_obs = utf8_encode($filaobs['nombre_usu']);
						                                                                        	$apellido1_usu_obs = utf8_encode($filaobs['apellido1_usu']);
						                                                                        	$descripcion_obs = utf8_encode($filaobs['descripcion_obs_eta_ven']);

						                                                                        	$lista_obs .= $fecha_obs.": ".$descripcion_obs." (".$nombre_usu_obs." ".$apellido1_usu_obs.")<br>";
						                                                                        }
						                                                                    }
						                                                                }

						                                                                if ($fila_ven['id_for_pag']==1) {
																							$consulta_banco = 
																							  "
																							  SELECT 
																							    nombre_ban
																							  FROM
																							    banco_banco
																							  WHERE
																							  	id_ban = ".$fila_ven["id_ban"]."
																							  ";
																							$conexion->consulta($consulta_banco);
																							$tiene_ban = $conexion->total();
																							if($tiene_ban){
																								$filaban = $conexion->extraer_registro_unico();
																							}
																							$nombre_ban = utf8_encode($filaban["nombre_ban"]);

																							// ver credito si no
																							// buscar si está la fecha aprobación en la etapa 2
																							$consulta_aprobacion_cre = 
				                                                                                "
				                                                                                SELECT 
				                                                                                    valor_campo_eta_cam_ven
				                                                                                FROM
				                                                                                    venta_etapa_campo_venta
				                                                                                WHERE
				                                                                                    id_ven = ".$fila_ven["id_ven"]." AND id_eta = 23 AND id_cam_eta = 11";
				                                                                            $conexion->consulta($consulta_aprobacion_cre);
				                                                                            $tiene_aprob = $conexion->total();
				                                                                            if($tiene_aprob){
				                                                                            	$filaacre = $conexion->extraer_registro_unico();
				                                                                            }
																							$fecha_aprobacion_cre = $filaacre["valor_campo_eta_cam_ven"];
																							if($fecha_aprobacion_cre) {
																								if ($estado_venta==="") {
																									$estado_venta = "Aprobado";
																								}
																								
																								$cre_si = "1";
                                                                                    	 		$cre_no = "";
																							} else {
																								if ($estado_venta==="") {
																									$estado_venta = "Evaluación";
																								}
																								$cre_si = "";
                                                                                    	 		$cre_no = "1";
																							}

																						} else {
                                                                                    	 	$nombre_ban = "CONTADO";
                                                                                    	 	$cre_si = "";
                                                                                    	 	$cre_no = "";
                                                                                    	 	if ($estado_venta==="") {
																								$estado_venta = "Aprobado";
																							}
																						}

																						if ($estado_escritura === 1) {
																							$cre_si = "";
                                                                                    	 	$cre_no = "";
																						}
				                                                                        ?>
				                                                                        <tr>
		                                                                                	<td><?php echo $contador_depto; ?></td>
		                                                                                	<td><?php echo $estado_venta; ?></td>
		                                                                                    <td><?php echo $nombre_viv; ?></td>
		                                                                                    <td><?php echo $valor_viv; ?></td>
		                                                                                    <td><?php echo $total_monto_inmob; ?></td>
		                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila_ven['nombre_pro']." ".$fila_ven['apellido_paterno_pro']." ".$fila_ven['apellido_materno_pro']); ?></td>
		                                                                                    <td><?php echo utf8_encode($fila_ven['rut_pro']); ?></td>
		                                                                                    <td><?php echo utf8_encode($fila_ven['correo_pro']); ?></td>
		                                                                                    <td><?php echo utf8_encode($fila_ven['fono_pro']); ?></td>
		                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila_ven['nombre_vend']." ".$fila_ven['apellido_paterno_vend']." ".$fila_ven['apellido_materno_vend']); ?></td>
		                                                                                    <td>1</td>
		                                                                                    <td><?php echo $nombre_ban;?></td>
		                                                                                    <td></td>
		                                                                                    <td><?php echo $cre_si; ?></td>
		                                                                                    <td><?php echo $cre_no; ?></td>
		                                                                                    <td><?php echo $estado_escritura ? '' : 1 ?></td>
		                                                                                    <td><?php echo $estado_escritura; ?></td>
		                                                                                    <td><?php echo $notaria ?></td>
		                                                                                    <td><?php echo $repertorio; ?></td>
		                                                                                    <td><?php echo $fecha_envio_carta; ?></td>
		                                                                                    <td><?php echo $mes_escritura; ?></td>
		                                                                                    <td><?php echo $fecha_escritura; ?></td>
		                                                                                    <td><?php echo $fecha_entrega; ?></td>
		                                                                                    <td><?php echo $a_contab; ?></td>
		                                                                                    <td><?php echo $emite_cr; ?></td>
		                                                                                    <td><?php echo $cr_aprobada; ?></td>
		                                                                                    <td><?php echo $lista_obs; ?></td>
		                                                                                </tr>
		                                                                             <?php
			                                                                        }
			                                                                    }
		                                                                    } else {
		                                                                    	// cuando está disponible
		                                                                    		// sacar precio con descuento
		                                                                    		$consulta_par = 
		                                                                                "
		                                                                                SELECT 
		                                                                                    valor_par
		                                                                                FROM
		                                                                                    parametro_parametro
		                                                                                WHERE
		                                                                                    id_con = ? AND valor2_par = 4";
		                                                                            $conexion->consulta_form($consulta_par,array($_SESSION["sesion_filtro_condominio_panel"]));
		                                                                            $fila_par = $conexion->extraer_registro_unico();
		                                                                            $descuento_unidad = $fila_par['valor_par'];


		                                                                            $valor_depto_novendido = $valor_viv - ($valor_viv * $descuento_unidad/100);

		                                                                   			?>
					                                                                   	<tr>
					                                                                   		<td><?php echo $contador_depto; ?></td>
					                                                                   		<td>Disponible</td>
					                                                                   		<td><?php echo $nombre_viv; ?></td>
					                                                                   		<td></td>
					                                                                   		<td><?php echo round($valor_depto_novendido); ?></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td>1</td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   		<td></td>
					                                                                   	</tr>
		                                                                   	<?php
		                                                                    }
	                                                                    }
	                                                                }
                                                                    
                                                                    ?>   
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="27"></td>
                                                                    </tr> 
                                                                </tfoot>
                                                                
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>
                                            </div>
                                            <?php 
                                        	}
                                             ?>
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
<!-- <script src="<?php //echo _ASSETS?>plugins/daterangepicker/moment.min.js"></script> -->
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/datetime-moment.js"></script> -->
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script>
<!-- <script src="<?php // echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- <script src="<?php // echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/select2/select2.full.min.js"></script> -->
<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/natural.js"></script>
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

    $(function(){
	    $(".wmd-view-topscroll").scroll(function(){
	        $(".wmd-view")
	            .scrollLeft($(".wmd-view-topscroll").scrollLeft());
	    });
	    $(".wmd-view").scroll(function(){
	        $(".wmd-view-topscroll")
	            .scrollLeft($(".wmd-view").scrollLeft());
	    });
	});

    $(document).ready(function () {

    	// ver modal
        $(document).on( "click",".carga_liquida" , function() {
            valor = $(this).attr("id");
            // alert(valor);
            $.ajax({
                type: 'POST',
                url: ("form_update_liquida.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });

        // $(document).on( "change","#condominio" , function() {
        //     valor = $(this).val();
        //     if(valor != ""){
        //         $.ajax({
        //             type: 'POST',
        //             url: ("procesa_condominio.php"),
        //             data:"valor="+valor,
        //             success: function(data) {
        //                  $('#torre').html(data);
        //             }
        //         })
        //     }
        // });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $('#example_filter input').keyup(function() {
            table
              .search(
                jQuery.fn.dataTable.ext.type.search.string(this.value)
              )
              .draw();
        });

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            // var_fecha_desde = $('#fecha_desde').val();
            // var_fecha_hasta = $('#fecha_hasta').val();
            // var_estado = $('.filtro:checked').val();
            // var_vendedor = $('#vendedor').val();
            // var_cliente = $('#cliente').val();
            // var_forma_pago = $('#forma_pago').val();
            // var_estado_venta = $('#estado_venta').val();
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
            "pageLength": 120,
            "lengthMenu": [ 120, 50 ],
            dom:'lfBrtip',
            // success de tabla
            lengthChange: true,
            buttons: [ 'copy', {
                extend: 'excelHtml5',
                exportOptions: {
                	orthogonal: 'export',
                    columns: ':visible',
                    format: {
                        header: function ( data, columnIdx ) {
                            if(columnIdx==11){
                            	return 'CH SI';
                            } else if(columnIdx==12) {
                            	return 'CH NO';
                            }
                            else{
                            return data;
                            }
                        }
                    }
          //           format: {
	         //            body: function(data, row, column, node) {
	         //            	if (typeof data !== 'undefined') {
          //           			if (data !== null) {
          //           				if (column === 12) {
										// return "hola";
          //           				} else {
										// return data;
          //           				}
          //           				if (column === 12 || column === 13 || column === 18 || column === 19){
										// //data contain only one comma we need to split there
		        //                         var arr = data;
		        //                         arr = arr.replace( /[\.]/g, "" );
		        //                         arr = arr.replace( /[\,]/g, "." );
	         //                        	return arr;
          //           				} else if (column === 2) {
          //           					var arr = data;
          //           					arr = arr.split("-");
          //           					return arr[0];
          //           				} else {
          //           					return data;
          //           				}
                    		// 	} else {
                    		// 		return data;
                    		// 	}
                    		// } else {
                    		// 	return data;
                    		// }
	                    	// return data.replace('.', '');
	                        // return data.replace(',', '');
	                    // }
                	// }
                }
            }, 'pdf', 'print', 'colvis' ],
            "bProcessing": true,
            //"bServerSide": true,
            responsive: true,
            //"sAjaxSource": "select_alumno.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "asc" ]],
            "aoColumns": [
                { "sType": "natural" },
                { "sType": "string" },
                { "sType": "natural" },
                { "sType": "natural" },
                { "sType": "natural" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "natural" },
                { "sType": "string" },
                { "sType": "natural" },
                { "sType": "natural" },
                { "sType": "natural" },
                { "sType": "natural" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
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
