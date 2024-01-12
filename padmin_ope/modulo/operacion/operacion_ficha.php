<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";

?>
<title>Operación - Listado</title>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">

<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/bootstrap-image-gallery.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui-noscript.css"></noscript>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
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

    /*.container-fluid .content .form-control {
        display: inline-block;
        width: auto;
    }*/

    ul.etapas{
        padding: 0;
    }

    ul.etapas li{
        list-style: none;
        display: inline-block;
        border-radius: 4px;
        border: 1px solid #cccccc;
        background-color: rgba(255,255,255,.6);
        padding: 3px 6px;
        margin-bottom: 4px;
    }

    ul.etapas li.active{
        background-color: rgba(166, 203, 246, .38);
    }

    ul.etapas li .categoria{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


    button.btn.btn-sm.btn-icon {
        margin: 0px;
        padding: 2px 5px;
    }

    .click_etapa{
        cursor: pointer;
    }

    .bg-light-blue-active{
        background-color: #78B1D3 !important;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, .5);
    }

    .badge.bg-red{
        box-shadow: 1px 1px 4px rgba(0, 0, 0, .5);
    }

    .select2-container--default .select2-selection--single {
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
    }
</style>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/dias.php";
$conexion = new conexion();
$filtro_etapa = 0;
$id_cam_eta_creados=0;
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
                <li> Home </li>
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
                                        proceso.opcion_pro = 9 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li class="active"><a href="../operacion/operacion_ficha.php">OPERACIONES</a></li>
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
                                    <li><a href="../informe/operacion_etapa_listado.php">ETAPAS</a></li>
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
                                    <li><a href="../informe/operacion_listado_operacion.php">LISTADO OPERACIONES</a></li>
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
                                        <div class="col-sm-12">
                                        
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    <!-- <div class="col-sm-2"> -->
                                                        <!-- <div class="form-group"> -->
                                                            <!-- <label for="vendedor">Vendedor:</label> -->
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <!-- <select class="form-control chico" id="vendedor" name="vendedor">  -->
                                                                <!-- <option value="">Seleccione Vendedor</option> -->
                                                                <?php  
                                                                //$consulta = "SELECT * FROM vendedor_vendedor ORDER BY nombre_vend, apellido_paterno_vend";
                                                                //$conexion->consulta($consulta);
                                                                //$fila_consulta_vendedor_original = $conexion->extraer_registro();
                                                                //if(is_array($fila_consulta_vendedor_original)){
                                                                    //foreach ($fila_consulta_vendedor_original as $fila) {
                                                                        ?>
                                                                        <!-- <option value="<?php //echo $fila['id_vend'];?>"><?php //echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']);?></option> -->
                                                                        <?php
                                                                   // }
                                                                //}
                                                                ?>
                                                            <!-- </select> -->
                                                        <!-- </div> -->
                                                    <!-- </div> -->
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
                                                                        ?>
                                                                        <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="torre">Torre:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="torre" name="torre"> 
                                                                <option value="">Seleccione Torre</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="departamento">Departamento:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico  select2" id="departamento" name="departamento"> 
                                                                <option value="">Seleccione Departamento</option>
                                                                
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
                                            <div class="col-sm-12" id="contenedor_filtro">
                                                <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
                                                <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                                  <i>Filtro: 
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $elimina_filtro = 0;
                                                    
                                                    
                                                    if(isset($_SESSION["sesion_filtro_departamento_venta_panel"])){
                                                        $elimina_filtro = 1;

                                                        $consulta = 
                                                            "
                                                            SELECT
                                                                venta.id_ven,
                                                                viv.nombre_viv,
                                                                viv.id_viv,
                                                                CONCAT(pro.nombre_pro, ' ', pro.apellido_paterno_pro, ' ', pro.apellido_materno_pro) As cliente,
                                                                venta.id_ban
                                                            FROM 
                                                                venta_venta AS venta
                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = venta.id_viv
                                                                INNER JOIN propietario_propietario AS pro ON venta.id_pro = pro.id_pro
                                                            WHERE
                                                                venta.id_ven = ?
                                                            ";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_departamento_venta_panel"]));
                                                        $fila_consulta_departamento = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_departamento)){
                                                            foreach ($fila_consulta_departamento as $fila) {
																// busca banco
																$consultaban = 
	                                                                "
	                                                                SELECT 
	                                                                    nombre_ban
	                                                                FROM 
	                                                                    banco_banco
	                                                                WHERE   
	                                                                    id_ban = ".$fila["id_ban"]."
	                                                                ";

	                                                            $conexion->consulta($consultaban);
	                                                            $cantidadban = $conexion->total();
	                                                            if ($cantidadban>0) {
	                                                            	$filaban = $conexion->extraer_registro_unico();
	                                                            	$nombre_ban = $filaban["nombre_ban"];
	                                                            } else {
	                                                            	$nombre_ban = "sin banco";
	                                                            }
	                                                            

                                                                $texto_filtro = $fila['nombre_viv']." ( Venta: ".$fila['id_ven']." - Cliente: ".$fila['cliente'].") - Banco: ".$nombre_ban;
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary" style="font-size: 1.3rem"><?php echo utf8_encode($texto_filtro);?></span>
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_ven = ".$_SESSION["sesion_filtro_departamento_venta_panel"];
                                                        $departamento = $fila['id_viv'];
                                                        $venta = $fila['id_ven'];
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
                                        
                                        if(isset($_SESSION["sesion_filtro_departamento_venta_panel"]) && !empty($_SESSION["sesion_filtro_departamento_venta_panel"])){
																	
                                            ?>
                                            <div class="col-sm-12">
                                                <!-- general form elements -->
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
				                                                            "SELECT DISTINCT(etapa_categoria_etapa.id_cat_eta),
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
				                                                        //echo $consulta_cate;
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
				                                                            "SELECT DISTINCT(etapa_categoria_etapa.id_cat_eta),
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
						                                                            "SELECT 
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
                                                	<!-- puntos de las etapas -->
                                                    <div class="col-md-12">
                                                    	<ul class="etapas">
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    eta_ven.id_eta,
                                                                    eta.id_for_pag,
                                                                    eta.numero_real_eta,
                                                                    eta.id_cat_eta
                                                                FROM 
                                                                    venta_etapa_venta AS eta_ven
                                                                    INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
                                                                WHERE   
                                                                    id_ven = ".$venta."
                                                                ORDER BY 
                                                                    eta.numero_real_eta
                                                                DESC
                                                                ";

                                                            $conexion->consulta($consulta);
                                                            $cantidad_etapa = $conexion->total();
                                                            if ($cantidad_etapa > 0) {
                                                                $fila = $conexion->extraer_registro_unico();
                                                                $id_etapa_actual = $fila["id_eta"];
                                                                $id_forma_pago_actual = $fila["id_for_pag"];
                                                                $numero_real_etapa_actual = $fila["numero_real_eta"];
                                                                $id_cat_eta_actual = $fila["id_cat_eta"];
                                                            }
                                                                                                                        
                                                            if(isset($_SESSION["sesion_etapa_filtro_operacion_panel"])){
                                                                $filtro_etapa = $_SESSION["sesion_etapa_filtro_operacion_panel"];
                                                            }
                                                            else{
                                                                $filtro_etapa = $id_etapa_actual;
                                                            }
                                                            
                                                            $filtro_etapa_forma_pago = $id_forma_pago_actual;
                                                            $filtro_departamento = $departamento;
                                                            $filtro_venta = $venta;
                                                            $_SESSION["sesion_departamento_operacion_panel"] = $filtro_departamento;
                                                            $_SESSION["sesion_venta_operacion_panel"] = $filtro_venta;
                                                            $_SESSION["sesion_etapa_operacion_panel"] = $filtro_etapa;
                                                            
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    id_eta_ven,
                                                                    id_est_eta_ven,
                                                                    fecha_desde_eta_ven,
                                                                    fecha_hasta_eta_ven
                                                                FROM 
                                                                    venta_etapa_venta
                                                                WHERE   
                                                                    id_eta = ".$filtro_etapa." AND
                                                                    id_ven = ".$filtro_venta."
                                                                ";
                                                            $conexion->consulta($consulta);
                                                            $fila = $conexion->extraer_registro_unico();
                                                            $id_etapa_venta = $fila["id_eta_ven"];
                                                            $estado_etapa = $fila["id_est_eta_ven"];
                                                            $fecha_desde_etapa = $fila["fecha_desde_eta_ven"];
                                                            $fecha_hasta_etapa = $fila["fecha_hasta_eta_ven"];
                                                            $_SESSION["codigo_venta_etapa_panel"] = $id_etapa_venta;

                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    nombre_viv
                                                                FROM 
                                                                    vivienda_vivienda
                                                                WHERE
                                                                    id_viv = ".$filtro_departamento."
                                                                
                                                                ";
                                                            $conexion->consulta($consulta);
                                                            $fila = $conexion->extraer_registro_unico();
                                                            $nombre_vivienda = $fila["nombre_viv"];
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    eta.id_eta, 
                                                                    eta.id_cat_eta, 
                                                                    eta.nombre_eta, 
                                                                    eta.alias_eta, 
                                                                    eta.numero_real_eta,
                                                                    eta.duracion_eta,
                                                                    for_pag.nombre_for_pag
                                                                FROM 
                                                                    etapa_etapa AS eta
                                                                    INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = eta.id_for_pag
                                                                WHERE
                                                                    eta.id_est_eta = 1 AND
                                                                    eta.id_for_pag = ".$filtro_etapa_forma_pago."
                                                                ORDER BY 
                                                                    numero_real_eta
                                                                ";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta_etapa = $conexion->extraer_registro();

                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    * 
                                                                FROM 
                                                                    etapa_categoria_etapa AS cat_eta
                                                                WHERE   
                                                                    cat_eta.id_cat_eta >= 0 AND
                                                                    EXISTS(
                                                                        SELECT
                                                                           eta.id_eta 
                                                                        FROM
                                                                            etapa_etapa AS eta
                                                                        WHERE
                                                                            eta.id_cat_eta = cat_eta.id_cat_eta AND
                                                                            eta.id_for_pag = ".$filtro_etapa_forma_pago."
                                                                    )
                                                                ORDER BY 
                                                                    cat_eta.orden_cat_eta
                                                                ";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta_categoria = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta_categoria)){
                                                                foreach ($fila_consulta_categoria as $fila) {
                                                                	if ($id_cat_eta_actual==$fila['id_cat_eta']) {
                                                                		$cat_active = "active";
                                                                	} else {
                                                                		$cat_active = "";
                                                                	}
                                                                    ?>
                                                                    <li class="<?php echo $cat_active; ?>">
                                                                        <div class="categoria">
                                                                            <div><i class="fa fa-angle-right" aria-hidden="true"></i> <?php echo utf8_encode($fila['nombre_cat_eta']); ?></div>
                                                                            <div>
                                                                                <?php  
                                                                                if(is_array($fila_consulta_etapa)){
                                                                                    foreach ($fila_consulta_etapa as $fila_etapa) {
                                                                                        

                                                                                        if($fila_etapa["id_cat_eta"] == $fila["id_cat_eta"]){
                                                                                            if ($filtro_etapa == $fila_etapa["id_eta"]) {
                                                                                                $nombre_etapa = $fila_etapa['nombre_eta'];
                                                                                                $alias_etapa = $fila_etapa['alias_eta'];
                                                                                                $duracion_etapa = $fila_etapa['duracion_eta'];
                                                                                                $numero_real_etapa = $fila_etapa['numero_real_eta'];
                                                                                                $forma_pago_etapa = $fila_etapa['nombre_for_pag'];
                                                                                            }
                                                                                            if($id_etapa_actual == $fila_etapa["id_eta"]){
                                                                                                
                                                                                                $clase = "bg-red";
                                                                                                $clase_click = "click_etapa";
                                                                                                $data_valor = 'data-valor="'.$fila_etapa["id_eta"].'"';
                                                                                                $tool_actual = "<b>< ACTUAL</b>";
                                                                                            }
                                                                                            else{
                                                                                            	$tool_actual = "";
                                                                                            	if (isset($_SESSION["sesion_etapa_filtro_operacion_panel"]) && $_SESSION["sesion_etapa_filtro_operacion_panel"]==$fila_etapa["id_eta"]) {
                                                                                            		$clase = "bg-light-blue-active"; // para marcar la que estoy viendo con el click
                                                                                            	} else {
                                                                                            		$clase = "bg-light-blue";
                                                                                            	}
                                                                                                
                                                                                                if($fila_etapa['numero_real_eta'] <= $numero_real_etapa_actual){
                                                                                                    $clase_click = "click_etapa"; 
                                                                                                    $data_valor = 'data-valor="'.$fila_etapa["id_eta"].'"';
                                                                                                }
                                                                                                else{
                                                                                                    $clase_click = ""; 
                                                                                                    $data_valor = '';
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            <span class="badge <?php echo $clase;?> <?php echo $clase_click;?>" <?php echo $data_valor;?> data-toggle="tooltip" data-placement="top" title="<?php echo utf8_encode($fila_etapa['nombre_eta']); ?> <?php echo $tool_actual; ?>"><?php echo utf8_encode($fila_etapa['alias_eta']); ?></span> 
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                    }
                                                                                }
                                                                                ?>
                                                                                <!-- <span class="badge bg-red" data-toggle="tooltip" data-placement="top" title="Nombre completo Etapa">1-CD</span>  -->
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                    		
                                                    	</ul>                    
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <!-- general form elements -->
                                                        <div class="box box-default">
                                                            <div class="box-header with-border">
                                                                <h3 class="box-title">
                                                                    <?php echo utf8_encode($nombre_etapa); ?> - <?php echo utf8_encode($alias_etapa);?>
                                                                    <?php  
                                                                    // if($estado_etapa == 1 && ($_SESSION["sesion_perfil_panel"] == 1)){
                                                                    if($estado_etapa == 1){
                                                                        ?>
                                                                        <button id="editar" name="editar" value="<?php echo $filtro_venta;?>" type="button" class="btn btn-xs btn-icon btn-warning">Editar</button>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                </h3>
                                                                <!-- <div class="box-tools pull-right">
                                                                	<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                                                                </div> -->
                                                            </div>
                                                            <!-- /.box-header -->
                                                            <!-- form start -->

                                                            
                                                            <div class="box-body">
                                                                <form id="formulario" method="POST" role="form">
                                                                <div class="row">
                                                                   	<input type="hidden" name="id_ven" id="id_ven" value="<?php echo $filtro_venta;?>" />
                                                                    <input type="hidden" name="id_etapa" id="id_etapa" value="<?php echo $filtro_etapa;?>" />
                                                                    <input type="hidden" name="id_etapa_venta" id="id_etapa_venta" value="<?php echo $id_etapa_venta;?>" />
                                                                    <div class="col-sm-6">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <td>Unidad</td>
                                                                                <td><?php echo utf8_encode($nombre_vivienda);?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Forma de Pago</td>
                                                                                <td><?php echo utf8_encode($forma_pago_etapa);?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Duración</td>
                                                                                <td><?php echo number_format($duracion_etapa, 0, ',', '.');?> días</td>
                                                                            </tr>
                                                                            <?php  
                                                                            if($estado_etapa == 2 || $estado_etapa == 1){
                                                                                // $fecha_tentativa = date("d/m/Y", strtotime("$fecha_desde_etapa + $duracion_etapa days"));

                                                                                // $fecha_tentativa_atraso = date("Y-m-d", strtotime("$fecha_desde_etapa + $duracion_etapa days"));
																				

																				 //días hábiles
																				 $startdate = date("Y-m-d",strtotime($fecha_desde_etapa));
																				 $fecha_tentativa = add_business_days($startdate,$duracion_etapa,$holidays,'d/m/Y');
																				 $fecha_tentativa_atraso = add_business_days($startdate,$duracion_etapa,$holidays,'Y-m-d');
                                                                                ?>
                                                                                <tr>
                                                                                    <td>Fecha inicio</td>
                                                                                    <td><?php echo date("d/m/Y",strtotime($fecha_desde_etapa));?></td>
                                                                                </tr>
                                                                                
                                                                                <tr>
                                                                                    <td>Fecha cierre Tentativa</td>
                                                                                    <td><?php echo $fecha_tentativa;?></td>
                                                                                </tr>
                                                                                    
                                                                                <?php
																				// EXCEPCIÓN
																				// Crédito : ETA 28 Liquidación abonos 
																				// Contado: ETA 8 Saldo a Inmob
																				// link a informe_pago
																				if ($filtro_etapa==28 || $filtro_etapa==8) {
																				?>
																				<tr>
                                                                                    <td>Informe de Pagos</td>
                                                                                    <td><a href="../documento/informe_pago.php?id=<?php echo $filtro_venta;?>" target="_blank">Ver Informe</a></td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==30 || $filtro_etapa==12) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* LA FECHA DE CIERRE DE ESTA ETAPA ES LA FECHA DE PAGO DEL CLIENTE.<br>
                                                                                    	* LA FECHA REAL DE PAGO ADM. LA CARGA CONTABILIDAD EN EL INFORME CORRESPONDIENTE (VENTA > Fondo Explotación).<br>
                                                                                    * INGRESA AQUÍ LA FECHA TENTATIVA PAGO ADM. CONDOMINIO</td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==2) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* EL MONTO Y LA FECHA LO DEBE CARGAR CONTABILIDAD. Y DEDE CERRAR LA ETAPA</td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==23) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* SI EL BANCO HA CAMBIADO DEBE MODIFICARLO EN LA VENTA.</td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==38 || $filtro_etapa==47) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* RECUERDE QUE ESTOS VALORES SIEMPRE SE PUEDEN CARGAR DESDE EL INFORME DE VENTAS. Y SE PUEDEN ACTUALIZAR SIEMPRE DESDE ALLÍ<br>
                                                                                    	<a class="btn btn-info" target="_blank" href="../informe/operacion_listado.php">IR</a></td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==14 || $filtro_etapa==35) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* LOS VALORES Y FECHAS QUE CORRESPONDEN A CONTABILIDAD LO PUEDEN CARGAR DESDE EL FORMULARIO ÚNICO.<br>
                                                                                    	<a class="btn btn-info" target="_blank" href="../informe/operacion_listado.php">IR</a></td>
                                                                                </tr>
																				<?php
																				}

																				if ($filtro_etapa==16 || $filtro_etapa==39) {
																				?>
																				<tr>
                                                                                    <td colspan="2">* EL VALOR Y NÚMERO DE FACTURA SE CARGA POR EL FORMULARIO ÚNICO<br>
                                                                                    * EL VALOR Y NÚMERO DE NC SE CARGA POR EL FORMULARIO ÚNICO<br>
                                                                                    	<a class="btn btn-info" target="_blank" href="../informe/operacion_listado.php">IR</a></td>
                                                                                </tr>
																				<?php
																				}

                                                                            }

                                                                            if($estado_etapa == 1){
                                                                                $fecha_tentativa_formato = date("Y-m-d",strtotime($fecha_tentativa));
                                                                                // $dias = (strtotime($fecha_hasta_etapa)-strtotime($fecha_desde_etapa))/86400;
                                                                                // $dias = abs($dias); 
                                                                                // $dias = floor($dias);

                                                                                $dias = getWorkingDays($fecha_desde_etapa,$fecha_hasta_etapa,$holidays);
                                                                                ?>
                                                                                <tr>
                                                                                    <td>Fecha cierre</td>
                                                                                    <td><?php echo date("d/m/Y",strtotime($fecha_hasta_etapa));?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Duración real</td>
                                                                                    <td><?php echo $dias;?> días hábiles</td>
                                                                                </tr>
                                                                                
                                                                                <?php
                                                                                $fecha_hasta_etapa = date("Y-m-d",strtotime($fecha_hasta_etapa));
                                                                                // echo $fecha_hasta_etapa."-".$fecha_tentativa_atraso;
                                                                                if($fecha_hasta_etapa > $fecha_tentativa_atraso){
                                                                                    // $dias = (strtotime($fecha_hasta_etapa)-strtotime($fecha_tentativa_atraso))/86400;
                                                                                    // $dias = abs($dias); 
                                                                                    // $dias = floor($dias);

                                                                                    $dias = getWorkingDays($fecha_tentativa_atraso,$fecha_hasta_etapa,$holidays);
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>Días de Atraso</td>
                                                                                        <td><?php echo $dias;?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                    
                                                                                }
                                                                                $consulta = 
                                                                                    "
                                                                                    SELECT 
                                                                                        a.nombre_eta_cam_ven,
                                                                                        a.valor_campo_eta_cam_ven,
                                                                                        a.id_tip_cam_eta,
                                                                                        a.id_cam_eta
                                                                                    FROM 
                                                                                        venta_etapa_campo_venta AS a
                                                                                    WHERE   
                                                                                        a.id_eta = ".$filtro_etapa." AND
                                                                                        a.id_ven = ".$filtro_venta."
                                                                                    ";
                                                                                $conexion->consulta($consulta);
                                                                                $fila_consulta = $conexion->extraer_registro();
                                                                                if(is_array($fila_consulta)){
                                                                                    foreach ($fila_consulta as $fila) {
                                                                                    	$campo_extra = "";
                                                                                        if($fila["id_tip_cam_eta"] == 2){
                                                                                            // $campo_extra = number_format($fila["valor_campo_eta_cam_ven"], 2, ',', '.');
                                                                                            $campo_extra = $fila["valor_campo_eta_cam_ven"];
                                                                                        }
                                                                                        else if($fila["id_tip_cam_eta"] == 3){
                                                                                        	if ($fila["valor_campo_eta_cam_ven"]<>null) {
                                                                                        		$campo_extra = date("d/m/Y",strtotime($fila["valor_campo_eta_cam_ven"]));
                                                                                        	}
                                                                                        }
                                                                                        else{
                                                                                            $campo_extra = utf8_encode($fila["valor_campo_eta_cam_ven"]);
                                                                                        }

                                                                                        $id_cam_eta_creados .= $fila["id_cam_eta"].",";
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td><?php echo utf8_encode($fila["nombre_eta_cam_ven"]);?></td>
                                                                                            <td><?php echo $campo_extra;?></td>
                                                                                        </tr>
                                                                                        <?php

                                                                                    }
                                                                                }

                                                                                // mostrar campos vacios nuevos
                                                                                if ($id_cam_eta_creados<>'') {
                                                                                
	                                                                                $id_cam_eta_creados = substr($id_cam_eta_creados, 0, -1);
	                                                                                $consulta_nuevos = 
																				        "
																				        SELECT 
																				            * 
																				        FROM 
																				            etapa_campo_etapa
																				        WHERE   
																				            id_eta = ".$filtro_etapa."
																				            AND id_cam_eta NOT IN (".$id_cam_eta_creados.")            
																				        ";
																				    // echo $consulta_nuevos;
																				    $conexion->consulta($consulta_nuevos);
																				    $hay_campos = $conexion->total();
																				    if ($hay_campos>0) {
																				    	$fila_consulta = $conexion->extraer_registro();
																				    	if(is_array($fila_consulta)){
		        																			foreach ($fila_consulta as $filan) {
																					    	?>
		                                                                                    <tr>
		                                                                                        <td><?php echo utf8_encode($filan["nombre_cam_eta"]);?></td>
		                                                                                        <td>NO INGRESADO</td>
		                                                                                    </tr>
		                                                                                    <?php
		                                                                                	}
		                                                                                }
																				    }
																				}

                                                                            }
                                                                            ?>
                                                                            

                                                                        </table>
                                                                            
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <?php  
                                                                        $no_inicia = 0;
                                                                        if($estado_etapa == 3){
                                                                        	// excepción inicio por contabilidad 38
																			// if($filtro_etapa == 38 && $_SESSION["sesion_perfil_panel"] == 3){
																			// 	$no_inicia = 1;
																			// }
																			// excepción inicio por contabilidad 39
																			// if($filtro_etapa == 39 && $_SESSION["sesion_perfil_panel"] == 3){
																			// 	$no_inicia = 1;
																			// }
																			if ($no_inicia==0) {
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label for="fecha">Fecha Ingreso:</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control datepicker" id="fecha" name="fecha" />
                                                                                    <span class="input-group-btn" id="contenedor_boton_inicio">
                                                                                        <button type="button" class="btn btn-success btn-flat" id="iniciar_etapa">Iniciar Etapa</button>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <?php
                                                                        	} else {
                                                                        		echo "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Esta etapa la debe iniciar Contabilidad.";
                                                                        	}
                                                                        }

                                                                        if($estado_etapa == 2){
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label for="fecha_cierre">Fecha Cierre Etapa:</label>
                                                                                <input type="text" class="form-control datepicker validacion_campo" id="fecha_cierre" name="fecha_cierre" />
                                                                                    
                                                                            </div>
                                                                            
                                                                            <?php
                                                                             $no_cierre = 0;
                                                                            $consulta = 
                                                                                "
                                                                                SELECT 
                                                                                    * 
                                                                                FROM 
                                                                                    etapa_campo_etapa
                                                                                WHERE   
                                                                                    id_eta = ".$filtro_etapa."
                                                                                    
                                                                                ";
                                                                            $conexion->consulta($consulta);
                                                                            $hay_campos = $conexion->total();
                                                                            $fila_consulta = $conexion->extraer_registro();
                                                                            if(is_array($fila_consulta)){
                                                                                foreach ($fila_consulta as $fila) {
                                                                                    $valor_etapa = '';

																					$validacion = "validacion_campo";

                                                                                    $consulta = "SELECT valor_campo_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_eta_ven = ? AND id_ven = ? AND id_eta = ? AND id_cam_eta = ?";
                                                                                    $conexion->consulta_form($consulta,array($id_etapa_venta,$filtro_venta,$filtro_etapa,$fila["id_cam_eta"]));
                                                                                    $cantidad_registro = $conexion->total();
                                                                                    if($cantidad_registro > 0){
                                                                                        $fila_etapa = $conexion->extraer_registro_unico();
                                                                                        $valor_etapa = utf8_encode($fila_etapa["valor_campo_eta_cam_ven"]);
                                                                                    }
                                                                                    if($fila["id_tip_cam_eta"] == 2){
                                                                                        $clase = "numero";
                                                                                    }
                                                                                    else if($fila["id_tip_cam_eta"] == 3){
                                                                                        $clase = "datepicker";
                                                                                        if($cantidad_registro > 0){
                                                                                        	if ($valor_etapa<>null || $valor_etapa <>'') {
                                                                                        		$valor_etapa = date("d-m-Y",strtotime($valor_etapa));
                                                                                        	}
                                                                                        }
                                                                                    }
                                                                                    else{
                                                                                        $clase = "";
                                                                                    }

                                                                                    // EXEPCIONES
                                                                                    $noedita = "";
                                                                                   

																					// campos no obligatorios - Factura Valor y Número
																					if ($fila["id_cam_eta"]==61 || $fila["id_cam_eta"]==53 || $fila["id_cam_eta"]==62 || $fila["id_cam_eta"]==26) {
																						$validacion = "";
																						$noedita = "readonly";
																					}

																					// campos no obligatorios - Factura Valor y Número
																					if ($fila["id_cam_eta"]==65 || $fila["id_cam_eta"]==64 || $fila["id_cam_eta"]==63 || $fila["id_cam_eta"]==27) {
																						$validacion = "";
																						$noedita = "readonly";
																					}

                                                                                    // etapa 1 crédito, traer el banco
                                                                                    if ($fila["id_cam_eta"]==10) { //campo banco
                                                                                    	$consulta_ban = "SELECT
                                                                                    					banco_banco.nombre_ban 
                                                                                    				FROM 
                                                                                    					venta_venta,
                                                                                    					banco_banco 
                                                                                    				WHERE 
                                                                                    					venta_venta.id_ven = ? AND
                                                                                    					venta_venta.id_ban = banco_banco.id_ban";
                                                                                    	$conexion->consulta_form($consulta_ban,array($filtro_venta));
                                                                                        $filaban = $conexion->extraer_registro_unico();
                                                                                        $valor_etapa = utf8_encode($filaban['nombre_ban']);
                                                                                        $noedita = "readonly";
                                                                                    }
																					if ($_SESSION["sesion_perfil_panel"]==3) { //operaciones
																						if ($fila["id_cam_eta"]==42 || $fila["id_cam_eta"]==43 || $fila["id_cam_eta"]==44 || $fila["id_cam_eta"]==46 || $fila["id_cam_eta"]==47 || $fila["id_cam_eta"]==48) { //campo banco
																							$noedita = "readonly";
																							$clase = "";
																							$validacion = "";
                                                                                    	}

                                                                                    	// fecha pago adm. condominio
                                                                                    	if ($fila["id_cam_eta"]==55 || $fila["id_cam_eta"]==56) { //campo banco
																							$noedita = "";
																							$clase = "datepicker";
                                                                                    	}

                                                                                    	// valores de etapa 38 ECR16
                                                                                    	if ($fila["id_cam_eta"]==20 || $fila["id_cam_eta"]==51 || $fila["id_cam_eta"]==52 || $fila["id_cam_eta"]==66) { //campo banco
																							$noedita = "readonly";
																							$clase = "";
																							$validacion = "";
                                                                                    	}

                                                                                    	// valores de etapa 39 ECR17 || 16 contado
                                                                                    	if ($fila["id_cam_eta"]==53 || $fila["id_cam_eta"]==26 || $fila["id_cam_eta"]==27) { //campo banco
																							$noedita = "readonly";
																							$clase = "";
                                                                                    	}

                                                                                    	// valores de etapa 2 CONTADO ECR2 Fondos OP
                                                                                    	if ($fila["id_cam_eta"]==41 || $fila["id_cam_eta"]==58) { //campo banco
																							$noedita = "";
																							$clase = "";
                                                                                    	}

																					}

																					// valor CRE
																					if ($fila["id_cam_eta"]==35 || $fila["id_cam_eta"]==25) { //campo banco
																						$noedita = "readonly";
																						$clase = "";
																						$validacion = "";
                                                                                    }

																					if ($_SESSION["sesion_perfil_panel"]==6) { //contabilidad
																						if ($fila["id_cam_eta"]==36 || $fila["id_cam_eta"]==45) { //campo banco
																							$noedita = "readonly";
																							$clase = "";
                                                                                    	}

                                                                                    	if ($fila["id_cam_eta"]==37 || $fila["id_cam_eta"]==22) { //campo banco
																							$noedita = "readonly";
																							$clase = "";
                                                                                    	}
																					}

																					// convenio de pago
                                                                                    if ($fila["id_cam_eta"]==20) { //convenio de pago - Crédito
                                                                                    	$consulta_con = "SELECT
                                                                                    					banco_banco.convenio_ban 
                                                                                    				FROM 
                                                                                    					venta_venta,
                                                                                    					banco_banco 
                                                                                    				WHERE 
                                                                                    					venta_venta.id_ven = ? AND
                                                                                    					venta_venta.id_ban = banco_banco.id_ban";
                                                                                    	$conexion->consulta_form($consulta_con,array($filtro_venta));
                                                                                        $filacon = $conexion->extraer_registro_unico();
                                                                                        $valor_etapa = utf8_encode($filacon['convenio_ban']);
                                                                                        // $noedita = "readonly";
                                                                                    }

																					// campos de liquidación, revisa si se cargó por el informe
                                                                                    if ($fila["id_cam_eta"]==51 || $fila["id_cam_eta"]==49) { //campo banco
                                                                                    	$consulta_liq_pesos = "SELECT
                                                                                    					monto_liq_pesos_ven 
                                                                                    				FROM 
                                                                                    					venta_liquidado_venta
                                                                                    				WHERE 
                                                                                    					id_ven = ?";
                                                                                    	$conexion->consulta_form($consulta_liq_pesos,array($filtro_venta));
                                                                                        $fila_liq_pesos = $conexion->extraer_registro_unico();
                                                                                        $valor_etapa = utf8_encode($fila_liq_pesos['monto_liq_pesos_ven']);
                                                                                        $noedita = "";
                                                                                    }

                                                                                    if ($fila["id_cam_eta"]==52 || $fila["id_cam_eta"]==50) { //campo banco
                                                                                    	$consulta_liq_uf = "SELECT
                                                                                    					monto_liq_uf_ven 
                                                                                    				FROM 
                                                                                    					venta_liquidado_venta
                                                                                    				WHERE 
                                                                                    					id_ven = ?";
                                                                                    	$conexion->consulta_form($consulta_liq_uf,array($filtro_venta));
                                                                                        $fila_liq_uf = $conexion->extraer_registro_unico();
                                                                                        $valor_etapa = utf8_encode($fila_liq_uf['monto_liq_uf_ven']);
                                                                                        $noedita = "";
                                                                                    }


                                                                                    // -------ACTA ENTREGA NO OBLIGATORIO ETAPA ECR8
                                                                                    if ($fila["id_cam_eta"]==16) {
																						$validacion = "";
																						$noedita = "";
																					}


                                                                                    // liquidado en Pesos busca el de Preinforme 38
                                                                                    // if ($fila["id_cam_eta"]==49) { 
                                                                                    // 	$consulta_liqpesos = "SELECT
                                                                                    // 					valor_campo_eta_cam_ven
                                                                                    // 				FROM 
                                                                                    // 					venta_etapa_campo_venta
                                                                                    // 				WHERE 
                                                                                    // 					id_cam_eta = 51 AND
                                                                                    // 					id_ven = ?";
                                                                                    // 	$conexion->consulta_form($consulta_liqpesos,array($filtro_venta));
                                                                                    //     $filapesos = $conexion->extraer_registro_unico();
                                                                                    //     $valor_etapa = utf8_encode($filapesos['valor_campo_eta_cam_ven']);
                                                                                    //     // $noedita = "readonly";
                                                                                    // }

                                                                                    // liquidado en UF busca el de Preinforme 38
                                                                                    // if ($fila["id_cam_eta"]==50) { 
                                                                                    // 	$consulta_liquf = "SELECT
                                                                                    // 					valor_campo_eta_cam_ven
                                                                                    // 				FROM 
                                                                                    // 					venta_etapa_campo_venta
                                                                                    // 				WHERE 
                                                                                    // 					id_cam_eta = 52 AND
                                                                                    // 					id_ven = ?";
                                                                                    // 	$conexion->consulta_form($consulta_liquf,array($filtro_venta));
                                                                                    //     $filauf = $conexion->extraer_registro_unico();
                                                                                    //     $valor_etapa = utf8_encode($filauf['valor_campo_eta_cam_ven']);
                                                                                    //     // $noedita = "readonly";
                                                                                    // }

                                                                                    ?>
                                                                                    <div class="form-group">
                                                                                        <label for="campo_extra_<?php echo utf8_encode($fila["id_cam_eta"]);?>"><?php echo utf8_encode($fila["nombre_cam_eta"]);?>:</label>
                                                                                        <input type="text" name="campo_extra_<?php echo utf8_encode($fila["id_cam_eta"]);?>" class="form-control <?php echo $clase;?> <?php echo $validacion; ?>" id="campo_extra_<?php echo utf8_encode($fila["id_cam_eta"]);?>" value="<?php echo $valor_etapa;?>" <?php echo $noedita; ?>/>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <div class="col-sm-12" id="contenedor_cierre_etapa">
                                                                            	<?php 
                                                                            	// EXCEPCIONES DE CIERRE
																				// if ($_SESSION["sesion_perfil_panel"]==3 && ($filtro_etapa==35 || $filtro_etapa==14)) {
																				// $no_cierre = 1;
																				// }
																				// if ($_SESSION["sesion_perfil_panel"]==3 && ($filtro_etapa==2)) {
																				// $no_cierre = 1;
																				// }
																				// if ($_SESSION["sesion_perfil_panel"]==6 && ($filtro_etapa==30 || $filtro_etapa==12)) {
																				// $no_cierre = 1;
																				// }
																				// if ($_SESSION["sesion_perfil_panel"]==3 && ($filtro_etapa==38)) {
																				// $no_cierre = 1;
																				// }

																				if ($_SESSION["sesion_perfil_panel"]==6 && ($filtro_etapa==39)) {
																				$no_cierre = 1;
																				}

																				if ($no_cierre==0) {
                                                                            	 ?>
                                                                                <button type="submit" id="cerrar_etapa" class="btn btn-warning pull-right" style="margin-left: 40px"><i class="fa fa-lock" aria-hidden="true"></i> Cerrar Etapa</button>
                                                                                <?php 
                                                                            	}
																				if ($hay_campos>0) { //si no hay campos estras no muestra el botón
																				?>
																					<button type="button" id="guarda_etapa" class="btn btn-success pull-right" style="margin-left: 40px"> Guardar Información</button>
																				<?php
																				}
                                                                                 ?>
                                                                            </div>

                                                                            <div class="col-sm-12">
                                                                                <hr>
                                                                            </div>
                                                                            <!-- <div class="form-group">
                                                                                <label for="observacion">Ingresar Observación:</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" id="observacion" name="observacion">
                                                                                    <span class="input-group-btn" id="contenedor_boton_observacion">
                                                                                        <button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Guardar</button>
                                                                                    </span>
                                                                                </div>
                                                                            </div> -->
                                                                            <?php
                                                                        }

                                                                        if($estado_etapa != 3){
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <label for="observacion">Ingresar Observación:</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" id="observacion" name="observacion">
                                                                                    <span class="input-group-btn" id="contenedor_boton_observacion">
                                                                                        <button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Guardar</button>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <table class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th colspan="5">Observaciones</th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>N°</th>
                                                                                        <th>Usuario</th>
                                                                                        <th>obs.</th>
                                                                                        <th>Fecha</th>
                                                                                        <th>Acción</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php  
                                                                                    $consulta = 
                                                                                        "
                                                                                        SELECT 
                                                                                            a.id_obs_eta_ven, 
                                                                                            a.fecha_obs_eta_ven, 
                                                                                            a.descripcion_obs_eta_ven, 
                                                                                            a.id_usu,
                                                                                            b.nombre_usu, 
                                                                                            b.apellido1_usu
                                                                                        FROM 
                                                                                            venta_observacion_etapa_venta AS a
                                                                                            INNER JOIN usuario_usuario AS b ON a.id_usu = b.id_usu
                                                                                        WHERE   
                                                                                            a.id_eta = ".$filtro_etapa." AND
                                                                                            a.id_ven = ".$filtro_venta."
                                                                                        ORDER BY
                                                                                            fecha_obs_eta_ven
                                                                                        DESC
                                                                                        ";
                                                                                    $conexion->consulta($consulta);
                                                                                    $cantidad_observacion = $conexion->total();
                                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                                    if(is_array($fila_consulta)){
                                                                                        foreach ($fila_consulta as $fila) {
                                                                                            // if($fila["id_cam_eta"] == 2){
                                                                                            //     $clase = "numero";
                                                                                            // }
                                                                                            // else if($fila["id_cam_eta"] == 3){
                                                                                            //     $clase = "datepicker";
                                                                                            // }
                                                                                            // else{
                                                                                            //     $clase = "";
                                                                                            // }
                                                                                            ?>
                                                                                            
                                                                                            <tr>
                                                                                                <td><?php echo $cantidad_observacion;?></td>
                                                                                                <td><?php echo utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]);?></td>
                                                                                                <td><?php echo utf8_encode($fila["descripcion_obs_eta_ven"]);?></td>
                                                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_obs_eta_ven"]));?></td>
                                                                                                <td>
                                                                                                    <?php  
                                                                                                    if($estado_etapa == 2 || $estado_etapa == 1 ){
                                                                                                    	if ($_SESSION["sesion_id_panel"]==$fila["id_usu"]) {
                                                                                                        ?>
                                                                                                        <button value="<?php echo $fila["id_obs_eta_ven"];?>" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>
                                                                                                        <?php
                                                                                                    	}
                                                                                                    }
                                                                                                    ?>
                                                                                                    
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $cantidad_observacion--;
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    
                                                                                </tbody>                                            
                                                                            </table>
                                                                            <?php
                                                                        }
                                                                        
                                                                        ?>
                                                                    </div>    
                                                                </div>      
                                                                </form>
                                                                <div class="row">
                                                                	<div class="col-sm-6">
                                                                		
                                                                	</div>
                                                                	<div class="col-sm-6">
                                                                		<?php  
	                                                                    if($estado_etapa != 3){
	                                                                        ?>
	                                                                        <hr>
	                                                                        <h4><i class="fa fa-paperclip" aria-hidden="true"></i> Adjuntos</h4>
	                                                                        <div id="zona1" class="zona">   
	                                                                            <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
	                                                                                <div class="fileupload-buttonbar">
	                                                                                    <div class="span7">
	                                                                                        <span class="btn btn-success fileinput-button btn-sm">
	                                                                                            <i class="icon-plus icon-white"><img src="<?php echo _ASSETS?>plugins/upload/imagen/icono/adjuntar.png" width="16" height="16"/></i>
	                                                                                            <span>Adjuntar Archivo</span>
	                                                                                            <input type="file" name="files[]" multiple>
	                                                                                        </span>
	                                                                                        <!-- <button type="submit" class="btn btn-primary btn-sm start">
	                                                                                            <i class="icon-upload icon-white"><img src="plugin/upload/imagen/icono/subir.png" width="16" height="16"/></i>
	                                                                                            <span>Subir Archivos</span>
	                                                                                        </button> -->
	                                                                                        <button type="reset" class="btn btn-info btn-sm cancel" style="padding: 0; border: none">
	                                                                                        </button>                                        
	                                                                                    </div>
	                                                                                    <div class="span5 fileupload-progress fade">
	                                                                                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="margin-bottom: 0">
	                                                                                            <div class="bar progress-bar progress-bar-success" style="width:0%;"></div>
	                                                                                        </div>
	                                                                                        <div class="progress-extended">&nbsp;</div>
	                                                                                    </div>
	                                                                                </div>
	                                                                                <div class="fileupload-loading"></div>
	                                                                                <br>
	                                                                                <table role="presentation" class="table table-striped tabladoc"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
	                                                                            </form>
	                                                                        </div>
	                                                                        <?php  
	                                                                    }
	                                                                    ?>
	                                                                    <?php
	                                                                    if($estado_etapa == 1){
	                                                                        ?>
	                                                                        <div class="form-group">
	                                                                            <div class="row">
	                                                                                <div class="form-group">
	                                                                                    <div id="contenedor_editar" class="col-sm-12">
	                                                                                        
	                                                                                    </div>
	                                                                                </div>
	                                                                            </div>
	                                                                        </div>
	                                                                        <?php
	                                                                    }
	                                                                    ?> 
                                                                	</div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>


                                                    </div>
                                                      <!-- /.box -->
                                                </div>
                                                <!-- forumlario CIERRE ETAPA -->
                                                    
                                            </div>
                                            <?php  
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<?php include_once _INCLUDE."js_comun.php";?>
<!-- .wrapper cierra en el footer -->


<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

<script src="<?php echo _ASSETS?>plugins/upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/tmpl.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/load-image.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/canvas-to-blob.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/bootstrap-image-gallery.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.iframe-transport.js"></script>
    
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-fp.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-ui.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>


<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span><br><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary btn-sm">
                    <i class="icon-upload icon-white"></i>
                    <span>Subir</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade" >
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span><br><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}">{%=file.name%}</a><br><span>{%=o.formatFileSize(file.size)%}</span>
            </td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger btn-sm" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Borrar</span>
            </button>
            <!--<input type="checkbox" name="delete" value="1">-->
        </td>
    </tr>
{% } %}
</script>

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
		$('.badge').tooltip({html:true});

        $('.numero').numeric();
        
        $('#formulario').validate({ // initialize the plugin
            submitHandler: function (form) { // for demo
                return false; // for demo
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        
        $('.validacion_campo').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required:  "Ingrese valor"
                }
            });
        });
        
        <?php 
        if ($filtro_etapa) {
        ?>
        $('#fileupload').fileupload({
            url: '../../../archivo/operacion/<?php echo $filtro_etapa; ?>/documento/',
            maxNumberOfFiles: 10,
            
            dropZone: $('#zona1'),
            pasteZone: $('#zona1'),
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|pdf|doc|docx|xls|xlsx)$/i
        });
        $.ajax({
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
              .call(this, null, {result: result});
        });
        <?php	
        }
         ?>
    	

        $(document).on( "click",".click_etapa" , function() {
            var_etapa = $(this).attr("data-valor");
            $.ajax({
                type: 'POST',
                url: ("filtro_update_etapa.php"),
                data:"etapa="+var_etapa,
                success: function(data) {
                    location.reload();
                }
            })
        });

        $(document).on( "click","#filtro" , function() {
            var_departamento = $('#departamento').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"departamento_venta="+var_departamento,
                success: function(data) {
                    location.reload();
                }
            })
        });

        $(document).on( "click","#editar" , function() {
            valor = $(this).val();
            var_id_etapa = $('#id_etapa').val();
            var_id_etapa_venta = $('#id_etapa_venta').val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_editar.php"),
                    data:"valor="+valor+"&id_etapa="+var_id_etapa+"&id_etapa_venta="+var_id_etapa_venta,
                    success: function(data) {
                        $('#contenedor_editar').html(data);
                    }
                })
            }
        });

        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../informe/procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
            }
        });

        $(document).on( "change","#torre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_torre.php"),
                    data:"valor="+valor,
                    success: function(data) {
                        console.log(data);
                         $('#departamento').html(data);
                    }
                })
            }
        });

        $(document).on( "change","#vendedor" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_vendedor.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#departamento').html(data);
                    }
                })
            }
        });

        $(document).on( "change","#fecha_cierre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_cierre.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_fecha_cierre(data);
                    }
                })
            }
        });

        function resultado_fecha_cierre(data) {
            if (data.envio == 1) {

            }
            if (data.envio == 2) {
                swal("Atención!", "Fecha cierre supera un mes a partir de Hoy", "warning");
            }
        }

        

        $(document).on( "click",".borra_sesion" , function() {
            // $('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            $.ajax({
                type: 'POST',
                url: ("../informe/filtro_delete.php"),
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
        
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    icon: "success"                    
                }).then(()=>location.reload());
            }
            if (data.envio == 2) {
                swal("Atención!", "Información ya ha sida ingresada", "warning");
                $('#contenedor_boton_observacion').html('<button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Grabar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton_observacion').html('<button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Grabar</button>');
            }
            if (data.envio == 4) {
                swal("Atención!", "Fecha Cierre menor a inicio", "warning");
                $('#contenedor_boton_observacion').html('<button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Grabar</button>');
            }
            if (data.envio == 5) {
                swal("Atención!", "Fecha Incio Etapa menor a cierre etapa anterior", "warning");
                $('#contenedor_boton_inicio').html('<button type="button" class="btn btn-success btn-flat" id="iniciar_etapa">Iniciar Etapa</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
            // }
        }
        //iniciar_etapa

        $(document).on( "click","#iniciar_etapa" , function() {
            $('#contenedor_boton_inicio').html('<img src="../../assets/img/loading.gif">');
            var_fecha = $('#fecha').val();
            var_id_ven = $('#id_ven').val();
            var_id_etapa = $('#id_etapa').val();
            
            if(var_fecha != ''){
               $.ajax({
                    type: 'POST',
                    url: ("insert_fecha.php"),
                    data:"fecha="+var_fecha+"&id_ven="+var_id_ven+"&id_etapa="+var_id_etapa,
                    dataType: 'json',
                    success: function(data) {
                        resultado(data);
                    }
                }) 
            }
            else{
                $('#contenedor_boton_inicio').html('<button type="button" class="btn btn-success btn-flat" id="iniciar_etapa">Iniciar Etapa</button>');
            }
            
        });

        $(document).on( "click","#guarda_observacion" , function() {
            $('#contenedor_boton_observacion').html('<img src="../../assets/img/loading.gif">');
            var_observacion = $('#observacion').val();
            var_id_ven = $('#id_ven').val();
            if(var_observacion != ''){
               $.ajax({
                    type: 'POST',
                    url: ("insert_observacion.php"),
                    data:"observacion="+var_observacion+"&id_ven="+var_id_ven,
                    dataType: 'json',
                    success: function(data) {
                        resultado(data);
                    }
                }) 
            }
            else{
                $('#contenedor_boton_observacion').html('<button type="button" class="btn btn-success btn-flat" id="guarda_observacion">Guardar</button>');
            }
            
        });



        $(document).on( "click","#guarda_etapa" , function() {
            // $('#contenedor_boton_observacion').html('<img src="../../assets/img/loading.gif">');
            
           var dataString = $('#formulario').serialize();
            $.ajax({
                data: dataString,
                type: 'POST',
                url: 'insert_etapa.php',
                dataType: 'json',
                success: function (data) {
                    resultado(data);
                }
            })
            
            
        });



        function resultado_eliminar(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro eliminado con éxito!",
                  icon: "success"
                    
                }).then(()=>location.reload());
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }
        $(document).on( "click",".eliminar" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar el registro seleccionado!",
                icon: "warning"
                
            }).then(()=>{
                $.ajax({
                    type: 'POST',
                    url: ("delete_observacion.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar(data);
                    }
                })
            });
        });
        

        $('#formulario').submit(function () {        	
            if ($("#formulario").validate().form() == true){            	
                $('#contenedor_cierre_etapa').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario').serialize();
                $.ajax({
                    data: dataString,
                    type: 'POST',
                	url: 'insert_cierre.php',                    
                    dataType: 'json',
                    success: function (data) {
                        resultado(data);
                    }
                })
            }
            
            return false;
        });
        
    });
</script>
</body>
</html>
