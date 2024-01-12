<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Cliente - Ficha</title>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>dist/css/cotizador.css">
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
.cabecera_tabla{
    width: 11%;
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

.esta_bode .box{
	border-top: 0;
}
</style>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
 	<!-- Modal -->
   	<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    </div>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Cliente
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Cliente</li>
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
                                <li class="active"><a href="ficha_cliente_proceso.php">FICHA DE CLIENTE</a></li>
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
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body" style="padding-top: 0">
                                    <div class="row">
                                        <div id="contenedor_opcion"></div>
                                        <div class="col-sm-12 filtros">
                                            <div class="row">
                                                <div class="col-md-4 col-md-offset-4">
                                                    <div class="form-group">
                                                        <label for="fecha_desde">Buscador Cliente:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="busqueda" id="busqueda">
                                                             <span class="input-group-addon clear_buscador" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                        </div>
                                                    </div>
                                                    <div id="resultado" class="text-center"></div>
                                                </div>
                                                <?php
	                                            if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                ?>
	                                                <div class="col-md-4 text-right">
	                                                    <?php
	                                                
	                                                    ?>
	                                                    <h3 class="panel-title"><i class="icon fa fa-users"></i>
	                                                    <?php
	                                                    $consulta = 
	                                                    "
	                                                    SELECT 
	                                                        nombre_pro,
	                                                        apellido_paterno_pro,
	                                                        apellido_materno_pro
	                                                    FROM 
	                                                        propietario_propietario
	                                                    WHERE 
	                                                        id_pro = '".$_SESSION["id_cliente_filtro_ficha_panel"]."'
	                                                    ";
	                                                    $conexion->consulta($consulta);
	                                                    $fila = $conexion->extraer_registro_unico();

	                                                    ?>
	                                                    Cliente Seleccionado: <span style="font-size: 16px;" class="label label-default"><?php echo utf8_encode($fila["nombre_pro"]." ".$fila["apellido_paterno_pro"]." ".$fila["apellido_materno_pro"]);?></span>
	                                                    &nbsp;&nbsp;<button style="font-size: 10px;padding: 3px 5px;" type="button" class="btn btn-danger input-search-close icon fa fa-times eliminar_sesion" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Cerrar sesión Cliente"></button>
	                                                    </h3>
	                                                
	                                                </div>
	                                            <?php
	                                            }
	                                            ?> 
                                            </div>
                                        </div>

                                        <?php 
                                        if(!isset($_SESSION["id_cliente_filtro_ficha_panel"])){
                                        	?>
                                        <div class="col-md-12 text-center">
                                        	<button type="button" class="btn btn-primary crea_cliente">Crear Cliente</button>
                                        </div>
                                        	<?php
                                        }
                                        ?>
                                        
                                        <?php 
                                        if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){

                                       	//------------------- SI HAY CLIENTE SELECCIONADO
                                        ?>
                                        <div class="col-md-12">
                                        	<div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">INFORMACIÓN CLIENTE</h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                	<table class="table">
                                                		<thead>
                                                			<tr class="active">
	                                                			<th>ID</th>
	                                                			<th>Nombre</th>
	                                                			<th>RUT</th>
	                                                			<th>Email</th>
	                                                			<th>Vendedor</th>
	                                                			<th>Acción</th>
	                                                		</tr>
                                                		</thead>
                                                		<?php 
                                                		$consulta = 
                                                            "
                                                            SELECT 
                                                                pro.id_pro,
                                                                pro.nombre_pro,
                                                                pro.nombre2_pro,
                                                                pro.apellido_paterno_pro,
                                                                pro.apellido_materno_pro,
                                                                pro.rut_pro,
                                                                pro.correo_pro,
                                                                vend_pro.id_vend
                                                            FROM 
                                                                propietario_propietario AS pro
                                                                LEFT JOIN vendedor_propietario_vendedor AS vend_pro ON vend_pro.id_pro = pro.id_pro
                                                            WHERE 
                                                                pro.id_pro = ?
                                                            ";

                                                        $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                        $fila = $conexion->extraer_registro_unico();

                                                        if($fila['id_vend']) {
                                                		 	$consulta = 
																"
																SELECT 
																	nombre_vend,
																	apellido_paterno_vend
																FROM
																	vendedor_vendedor
																WHERE 
																	id_vend = ?
																";
															$conexion->consulta_form($consulta,array($fila['id_vend']));
															$fila2 = $conexion->extraer_registro_unico();
												        	$nombre_vend = utf8_encode($fila2['nombre_vend']." ".$fila2['apellido_paterno_vend']);
                                                        } else {
                                                        	$nombre_vend = "Cliente No Asignado";
                                                        }
                                                        ?>
                                                		<tr>
                                                			<td><span class="badge"><?php echo $fila['id_pro'];?></span></td>
                                                			<td><?php echo utf8_encode($fila['nombre_pro']." ".$fila['nombre2_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></td>
                                                			<td><?php echo utf8_encode($fila['rut_pro']);?></td>
                                                			<td><?php echo utf8_encode($fila['correo_pro']);?></td>
                                                			<td><?php echo $nombre_vend;?></td>
                                                			<td>
                                                				<!-- revisar perfil -->
                                                				<?php 
                                                				$ve_accion_cliente = 0;
                                                				if ($_SESSION["sesion_perfil_panel"] <> 4) {
                                                					$ve_accion_cliente = 1;
                                                				} else {
                                                					if ($fila['id_vend'] == $_SESSION["sesion_id_vend"]) {
                                                						$ve_accion_cliente = 1;
                                                					}
                                                					if(!$fila['id_vend']) {
                                                						$ve_accion_cliente = 1;
                                                					}
                                                				}

                                                				?>
                                                				<!-- revisar en vendedor, si es mio -->
                                                				<!-- VER -->
                                                				<button value="<?php echo e64($fila['id_pro']) ?>" class="btn btn-sm btn-icon btn-info detalle_cliente" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button>
                                                				<?php 
                                                				if($ve_accion_cliente == 1) {
                                                				 ?>
                                                				<!-- EDITAR -->
                                                				<button value="<?php echo e64($fila['id_pro']) ?>" type="button" class="btn btn-sm btn-icon btn-warning edita_cliente" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>
                                                				<!-- AGREGAR OBSERVACION -->
                                                				<button value="<?php echo e64($fila['id_pro']) ?>" type="button" class="btn btn-sm btn-icon btn-info agrega_observacion" data-toggle="tooltip" data-original-title="Agregar Obs."><i class="fa fa-plus"></i></button>
                                                				<!-- VER DETALLES -->
                                                				<button value="<?php echo $fila['id_pro'] ?>" type="button" class="btn btn-sm btn-icon btn-info detalle_propietario" data-toggle="tooltip" data-original-title="Ver Observaciones"><i class="fa fa-search"></i></button>
                                                				<?php 
                                                				}
                                                				 ?>
                                                			</td>
                                                		</tr>
                                                	</table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- COTIZACIONES -->
                                        <?php 
                        				$ve_modulo_cotizacion = 0;
                        				if ($_SESSION["sesion_perfil_panel"] <> 4) {
                        					$ve_modulo_cotizacion = 1;
                        				} else {
                        					if ($fila['id_vend'] == $_SESSION["sesion_id_vend"]) {
                        						$ve_modulo_cotizacion = 1;
                        					}
                        				}

                        				if($ve_modulo_cotizacion == 1) {
                        				?>
                                        <div class="col-sm-12" style="margin-top: 20px;">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">COTIZACIONES <?php if($_SESSION["sesion_perfil_panel"] == 4) { ?> | <button type="button" value="<?php echo e64($_SESSION["id_cliente_filtro_ficha_panel"]) ?>" class="btn btn-primary crea_cotizacion" style="margin-left: 30px">Nueva Cotización</button> <?php } ?></h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-condensed">
                                                        <thead>
                                                            <tr class="active">
                                                                <th>Id</th>
                                                                <th>Número</th>
                                                                <th>Condominio</th>
                                                                <!-- <th>Torre</th> -->
                                                                <th>Modelo</th>
                                                                <th>Depto</th>
                                                                <th>Canal</th>
                                                                <th>Vendedor</th>
                                                                <th>Fecha</th>
                                                                <th>Seguimientos</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    con.nombre_con,
                                                                    con.id_con,
                                                                    viv.id_viv,
                                                                    viv.nombre_viv,
                                                                    can_cot.id_can_cot,
                                                                    can_cot.nombre_can_cot,
                                                                    tor.id_tor,
                                                                    tor.nombre_tor,
                                                                    mode.id_mod,
                                                                    mode.nombre_mod,
                                                                    pro.rut_pro,
                                                                    pro.id_pro,
                                                                    pro.nombre_pro,
                                                                    pro.nombre2_pro,
                                                                    pro.apellido_paterno_pro,
                                                                    pro.apellido_materno_pro,
                                                                    pro.rut_pro,
                                                                    pro.correo_pro,
                                                                    pro.fono_pro,
                                                                    cot.id_cot,
                                                                    cot.fecha_cot,
                                                                    vend.nombre_vend,
                                                                    vend.apellido_paterno_vend,
                                                                    est_cot.nombre_est_cot,
                                                                    cot.id_vend
                                                                FROM 
                                                                    cotizacion_cotizacion AS cot 
                                                                    INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                                                                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                                                                    INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                                                                    INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                                                                    INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                    LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
                                                                WHERE 
                                                                    pro.id_pro = ?
                                                                ";
                                                            $contador = 1;
                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><span class="badge"><?php echo $fila["id_cot"];?></span></td>
                                                                        <td><span class="badge"><?php echo $fila["id_cot"];?></span></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                        <!-- <td><?php // echo utf8_encode($fila["nombre_tor"]);?></td> -->
                                                                        <td><?php echo utf8_encode($fila["nombre_mod"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_can_cot"]);?></td>

                                                                        <td><?php echo utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);?></td>
                                                                        <td><?php echo date("d/m/Y",strtotime($fila["fecha_cot"]));?></td>
                                                                        <?php 
																		$consulta = "SELECT id_seg_cot FROM cotizacion_seguimiento_cotizacion WHERE id_cot = ".$fila['id_cot']."";
                                                                    	$conexion->consulta($consulta);
                                                                    	$cant_seg = $conexion->total();
                                                                         ?>
                                                                        <td><?php echo $cant_seg; ?>
                                                                        	<?php 
																			if ($cant_seg > 0) {
																				?>
																				&nbsp;&nbsp;<button value="<?php echo $fila['id_cot']; ?>" class="btn btn-xs btn-info detalle" data-toggle="tooltip" data-original-title="Ver Seguimientos"><i class="fa fa-search"></i></button>
																				<?php
																			}
                                                                        	?>
                                                                        </td>
                                                                        <td><?php echo utf8_encode($fila["nombre_est_cot"]);?></td>
                                                                        <td>
                                                                        	<?php 
                                                                        	
                                                                    		// revisar si la cotización tiene venta
                                                                    		$consulta_esta_vendida = 
																	                "
																	                SELECT 
																	                    id_ven 
																	                FROM 
																	                    venta_venta 
																	                WHERE 
																	                    id_viv = ".$fila["id_viv"]." AND 
																	                    id_est_ven <> 3
																	                ";
																	        $conexion->consulta($consulta_esta_vendida);
																	        $tiene_venta = $conexion->total();

																	        if($tiene_venta==0) {
                                                                    	 	?>
                                                                        	<!-- editar -->
                                                                        	<button value="<?php echo e64($fila['id_cot']) ?>" class="btn btn-sm btn-icon btn-warning edita_cotizacion" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>
                                                                        	<?php 
	                                                                        	if ($_SESSION["sesion_perfil_panel"] <> 4) {
	                                                                        	 ?>
	                                                                        	<!-- eliminar -->
	                                                                        	<button value="<?php echo $fila['id_cot']; ?>" class="btn btn-sm btn-icon btn-danger elimina_cotizacion" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>
	                                                                			<?php 
	                                                                			}
                                                                        	}
                                                                        	 ?>
                                                                        	<?php 
                                                                        	if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) {
                                                                        	?>
	                                                                        	<!-- agregrar seguimiento -->
	                                                                        	<button value="<?php echo e64($fila['id_cot']) ?>" class="btn btn-sm btn-icon btn-info agrega_seguimiento" data-toggle="tooltip" data-original-title="Agregar Seguimiento"><i class="fa fa-plus"></i></button>
	                                                                        	<!-- agregrar evento agenda -->
	                                                                        	<button value="<?php echo e64($fila['id_cot']) ?>" class="btn btn-sm btn-icon btn-info agrega_evento" data-toggle="tooltip" data-original-title="Agregar Evento Agenda"><i class="fa fa-calendar-plus-o"></i></button>

                                                                        	<?php 
	                                                                    	}
	                                                                         ?>
	                                                                        	<!-- formato cotización print -->
	                                                                        	<button value="<?php echo $fila['id_cot'] ?>" class="btn btn-sm btn-icon btn-primary print_cotizacion" data-toggle="tooltip" data-original-title="Imprimir Cotización"><i class="fa fa-print"></i></button>

	                                                                        	<!-- formato cotización PDF -->
	                                                                        	<button value="<?php echo $fila['id_cot'] ?>" class="btn btn-sm btn-icon btn-primary pdf_cotizacion" data-toggle="tooltip" data-original-title="PDF de Cotización"><i class="fa fa-file-pdf-o"></i></button>

	                                                                        	<!-- Enviar por email -->
	                                                                        	<button value="<?php echo $fila['id_cot'] ?>" class="btn btn-sm btn-icon btn-primary email_pdf_cotizacion" data-toggle="tooltip" data-original-title="Enviar PDF por email"><i class="fa fa-envelope-o"></i></button>
	                                                                        
                                                                        	<?php 
                                                                        	// if ($_SESSION["sesion_perfil_panel"] <> 4) {
                                                                        	if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) {
                                                                        		$consulta_esta_vendida = 
																		                "
																		                SELECT 
																		                    id_ven 
																		                FROM 
																		                    venta_venta 
																		                WHERE 
																		                    id_viv = ".$fila["id_viv"]." AND 
																		                    id_est_ven <> 3
																		                ";
																		        $conexion->consulta($consulta_esta_vendida);
																		        $tiene_venta = $conexion->total();
	                                                                    		if($tiene_venta==0) {
	                                                                    	 	?>
	                                                                        	<!-- pasar a promesa -->
	                                                                        	<button value="<?php echo e64($fila['id_cot']) ?>" class="btn btn-sm btn-icon btn-success pasar_promesa" data-toggle="tooltip" data-original-title="Pasar a Promesa"><i class="fa fa-check"></i></button>
	                                                                    		<?php 
	                                                                    		}
                                                                        	}
                                                                        	 ?>
                                                                        </td>
                                                                    </tr>

                                                                    <?php
                                                                    $contador++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <?php 
                                    	}

                                        // if ($_SESSION["sesion_perfil_panel"] <> 4) {
                                        ?>

                                        <!-- VENTA -->
                                        <div class="col-sm-12" style="margin-top: 20px;">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">PROMESA / VENTA</h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                	<table class="table table-condensed" style="font-size: 16px;">
                                                        <thead>
                                                            <tr class="active">
                                                                <th>Id</th>
                                                                <th>Condominio</th>
                                                                <th>Torre</th>
                                                                <th>Depto</th>
                                                                <th>Vendedor</th>
                                                                <th>Fecha</th>
                                                                <th>Total</th>
                                                                <th>F. Pago</th>
                                                                <th>N° Bien Inscrito</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    ven.id_ven,
                                                                    con.nombre_con,
                                                                    tor.nombre_tor,
                                                                    viv.nombre_viv,
                                                                    pro.nombre_pro,
                                                                    pro.apellido_paterno_pro,
                                                                    pro.apellido_materno_pro,
                                                                    vend.nombre_vend,
                                                                    vend.apellido_paterno_vend,
                                                                    vend.apellido_materno_vend,
                                                                    ven.fecha_ven,
                                                                    ven.monto_ven,
                                                                    est_ven.nombre_est_ven,
                                                                    ven.numero_compra_ven,
                                                                    for_pag.nombre_for_pag,
                                                                    ven.id_est_ven,
                                                                    vend.id_vend
                                                                FROM 
                                                                    venta_venta AS ven 
                                                                    INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
                                                                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                    INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
                                                                    LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                    INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
                                                                WHERE 
                                                                    pro.id_pro = ?
                                                                ";
                                                            $contador = 1;
                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><span class="badge"><?php echo $fila["id_ven"];?></span></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);?></td>
                                                                        <td><?php echo date("d/m/Y",strtotime($fila["fecha_ven"]));?></td>
                                                                        <td><?php echo number_format($fila["monto_ven"], 2, ',', '.');?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["numero_compra_ven"]);?></td>
                                                                        <td><?php 
                                                                        echo utf8_encode($fila["nombre_est_ven"]);
                                                                        $desistida = 0;
                                                                        if ($fila["id_est_ven"]==3) {
                                                                        	$desistida = 1;
                                                                        	$consulta_des = 
														                        "
														                        SELECT
														                            tip_des.nombre_tip_des
														                        FROM
														                            venta_desestimiento_venta AS des,
														                            desistimiento_tipo_desistimiento AS tip_des
														                        WHERE
														                            des.id_ven = " .$fila["id_ven"]. " AND 
														                            des.id_tip_des = tip_des.id_tip_des
														                        ";
														                    $conexion->consulta($consulta_des);
														                    $fila_des = $conexion->extraer_registro_unico();
														                    echo "<br><font style='font-size:11px'>".utf8_encode($fila_des["nombre_tip_des"])."</font>";
                                                                        }

                                                                        ?></td>
                                                                        <td>
                                                                        <?php
                                                                        if($desistida <> 1){
                                                                        	if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) {
	                                                                        		?>
	                                                                        	<!-- registrar pago -->
	                                                                        	<button value="<?php echo e64($fila['id_ven']); ?>" class="btn btn-sm btn-icon btn-success registra_pago" data-toggle="tooltip" data-original-title="Registrar Pago"><i class="fa fa-usd"></i></button>

	                                                                        	<button value="<?php echo $fila['id_ven']; ?>" data-cat="1" class="btn btn-sm btn-icon btn-primary comprobante_pago" data-toggle="tooltip" data-original-title="Comprobante Pago Reserva"><i class="fa fa-credit-card"></i> Cierre Neg.</button>
	                                                                        	<button value="<?php echo $fila['id_ven']; ?>" data-cat="2" class="btn btn-sm btn-icon btn-primary comprobante_pago" data-toggle="tooltip" data-original-title="Comprobante Pago Pie"><i class="fa fa-credit-card"></i> Pie</button><br>
	                                                                        	<button value="<?php echo $fila['id_ven']; ?>" data-cat="3" class="btn btn-sm btn-icon btn-primary comprobante_pago" data-toggle="tooltip" data-original-title="Comprobante Pago Saldo Contado"><i class="fa fa-credit-card"></i> Saldo Contado</button>
	                                                                        	<?php 
	                                                                        	$consulta_tiene_pagos = 
																	                "
																	                SELECT 
																	                    id_pag 
																	                FROM 
																	                    pago_pago 
																	                WHERE 
																	                    id_ven = ".$fila["id_ven"]." AND 
																	                    id_est_pag = 1
																	                ";
																		        $conexion->consulta($consulta_tiene_pagos);
																		        $tiene_pagos = $conexion->total();
																		        if($tiene_pagos==0) {
	                                                                        	 ?>
	                                                                        		<button value="<?php echo e64($fila['id_ven']); ?>" class="btn btn-sm btn-icon btn-danger desistimiento" data-toggle="tooltip" data-original-title="Desistir"><i class="fa fa-thumbs-o-down"></i></button>
                                                                        	<?php 
                                                                        		}
                                                                        	}
                                                                        	if ($_SESSION["sesion_perfil_panel"] == 1) {
	                                                                        ?>
                                                                        		<!-- editar -->
                                                                        		<button value="<?php echo e64($fila['id_ven']); ?>" class="btn btn-sm btn-icon btn-warning edita_venta" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>
                                                                        	
                                                                        		<!-- Recalcula comisión -->
                                                                        		<button value="<?php echo $fila['id_ven']; ?>" class="btn btn-sm btn-icon btn-warning recalcula_venta" data-toggle="tooltip" data-original-title="Recalcular Comisión"><i class="fa fa-calculator"></i></button>
	                                                                        	<?php
                                                                        	}
                                                                        }
                                                                        ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $contador++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <?php 
                                    	// }
                                         ?>

                                        <!-- PROPIEDAD -->
                                        <div class="col-sm-12" style="margin-top: 10px;">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">PROPIEDAD CLIENTE</h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                	<table class="table table-condensed" style="font-size: 16px;">
                                                        <thead>
                                                            <tr class="active">
                                                                <th>N°</th>
                                                                <th>Depto</th>
                                                                <th>Condominio</th>
                                                                <th>Torre</th>
                                                                <th>Modelo</th>
                                                                <th>Orientación</th>
                                                                <th>Mt Totales</th>
                                                                <th>Mt Terraza</th>
                                                                <th>Estado</th>
                                                                <th>Valor</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    viv.nombre_viv,
                                                                    con.nombre_con,
                                                                    tor.nombre_tor,
                                                                    modelo.nombre_mod,
                                                                    ori_viv.nombre_ori_viv,
                                                                    viv.metro_viv,
                                                                    viv.metro_terraza_viv,
                                                                    viv.valor_viv,
                                                                    est_viv.nombre_est_viv,
                                                                    viv.bono_viv
                                                                FROM 
                                                                    propietario_vivienda_propietario AS pro
                                                                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                    INNER JOIN vivienda_estado_vivienda AS est_viv ON est_viv.id_est_viv = viv.id_est_viv
                                                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
                                                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                    INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
                                                                WHERE 
                                                                    pro.id_pro = ?
                                                                ";
                                                            $contador = 1;
                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><span class="badge"><?php echo $contador;?></span></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_mod"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_ori_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["metro_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["metro_viv"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["metro_terraza_viv"]);?></td>
                                                                        <td><?php echo number_format($fila["valor_viv"], 2, ',', '.');?> UF</td>
                                                                    </tr>

                                                                    <?php
                                                                    $contador++;
                                                                }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row esta_bode" id="contenedor_tabla">
                                                <div class="col-sm-6" style="margin-top: 0;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Estacionamientos <i class="fa fa-car"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>N°</th>
                                                                        <th>Nombre</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Depto</th>
                                                                        <th>Estado</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            estac.nombre_esta,
                                                                            estac.valor_esta,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            est_esta.nombre_est_esta
                                                                        FROM 
                                                                            propietario_vivienda_propietario AS pro
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                            INNER JOIN estacionamiento_estacionamiento AS estac ON estac.id_viv = viv.id_viv
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = estac.id_con
                                                                            LEFT JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN estacionamiento_estado_estacionamiento AS est_esta ON est_esta.id_est_esta = estac.id_est_esta
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    $total_estacionamiento = $conexion->total();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $contador;?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_esta"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_esta"]);?></td>
                                                                                <td><?php echo number_format($fila["valor_esta"], 2, ',', '.');?> UF</td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="margin-top: 0;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Bodegas <i class="fa fa-cubes"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>N°</th>
                                                                        <th>Nombre</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Depto</th>
                                                                        <th>Estado</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            bod.nombre_bod,
                                                                            bod.valor_bod,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            est_bod.nombre_est_bod
                                                                        FROM 
                                                                            propietario_vivienda_propietario AS pro
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                            INNER JOIN bodega_bodega AS bod ON bod.id_viv = viv.id_viv
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = bod.id_con
                                                                            LEFT JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN bodega_estado_bodega AS est_bod ON est_bod.id_est_bod = bod.id_est_bod
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    $total_estacionamiento = $conexion->total();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $contador;?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_bod"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_bod"]);?></td>
                                                                                <td><?php echo number_format($fila["valor_bod"], 2, ',', '.');?> UF</td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php 
                                        // if ($_SESSION["sesion_perfil_panel"] <> 4) {
                                        ?>

                                        <!-- PAGOS -->
                                        <div class="col-sm-12" style="margin-top: 20px;">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">REGISTRO DE PAGOS</h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-condensed" style="font-size: 16px;">
                                                        <thead>
                                                            <tr class="active">
                                                                <th>ID Venta</th>
                                                                <th>Categoría</th>
                                                                <th>Banco</th>
                                                                <th>Forma de Pago</th>
                                                                <th>Fecha</th>
                                                                <th>Fecha Real</th>
                                                                <th>Documento</th>
                                                                <th>Serie</th>
                                                                <th>Monto</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    pag.id_pag,
                                                                    cat_pag.nombre_cat_pag,
                                                                    ban.nombre_ban,
                                                                    for_pag.nombre_for_pag,
                                                                    pag.fecha_pag,
                                                                    pag.fecha_real_pag,
                                                                    pag.numero_documento_pag,
                                                                    pag.numero_serie_pag,
                                                                    pag.monto_pag,
                                                                    est_pag.nombre_est_pag,
                                                                    pag.id_est_pag,
                                                                    pag.id_ven,
                                                                    ven.id_vend
                                                                FROM
                                                                    pago_pago AS pag 
                                                                    INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                                                                    INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                                                                    LEFT JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                                                                    INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
                                                                    INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
                                                                WHERE 
                                                                    ven.id_pro = ? ORDER BY
                           											pag.id_for_pag ASC, pag.fecha_pag ASC, pag.numero_documento_pag ASC
                                                                ";
                                                            $contador = 1;
                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    if (empty($fila["fecha_real_pag"])) {
                                                                        $fecha_real_mostrar = "";
                                                                    }
                                                                    else{
                                                                        $fecha_real_mostrar = date("d/m/Y",strtotime($fila["fecha_real_pag"]));
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td><span class="badge"><?php echo $fila["id_ven"];?></span></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_cat_pag"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_ban"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
                                                                        <td><?php echo date("d/m/Y",strtotime($fila["fecha_pag"]));?></td>
                                                                        <td><?php echo $fecha_real_mostrar;?></td>
                                                                        <td><?php echo utf8_encode($fila["numero_documento_pag"]);?></td>
                                                                        <td><?php echo utf8_encode($fila["numero_serie_pag"]);?></td>
                                                                        
                                                                        <td><?php echo number_format($fila["monto_pag"], 0, ',', '.');?></td>
                                                                        <td><?php echo utf8_encode($fila["nombre_est_pag"]);?></td>
                                                                        <td>
                                                                        	<?php 
                                                                        	if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) {
	                                                							if($fila["id_est_pag"] == 2) {
	                                                							 ?>
	                                                                        	<!-- EDITAR -->
	                                                							<button value="<?php echo e64($fila['id_pag']) ?>" type="button" class="btn btn-sm btn-icon btn-warning edita_pago" data-toggle="tooltip" data-original-title="Editar Pago "><i class="fa fa-pencil"></i></button>
	                                                							<!-- ELIMINAR PAGO -->
	                                                							
	                                                                        	<button value="<?php echo $fila['id_pag']; ?>" class="btn btn-sm btn-icon btn-danger elimina_pago" data-toggle="tooltip" data-original-title="Eliminar Pago"><i class="fa fa-trash"></i></button>
	                                                                        	<?php 
	                                                                        	}
	                                                                        }
                                                                        	 ?>
                                                                        </td>			
                                                                    </tr>
                                                                    <?php
                                                                    $contador++;
                                                                }
                                                            }
                                                            ?>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DOCUMENTOS -->
                                        <div class="col-sm-12" style="margin-top: 20px;">
                                            <div class="box box-primary">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">DOCUMENTOS VENTA</h3>
                                                </div>
                                                <div class="box-body no-padding">
                                                    <table class="table table-condensed" style="font-size: 16px;">
                                                    	<thead>
                                                            <tr class="active">
                                                                <th>Nombre</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        	<?php 
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    ven.id_ven,
                                                                    viv.nombre_viv,
                                                                    viv.id_mod,
                                                                    viv.id_tor,
                                                                    ven.id_vend
                                                                FROM 
                                                                    venta_venta AS ven 
                                                                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                    INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
                                                                WHERE 
                                                                    pro.id_pro = ? AND 
                                                                    ven.id_est_ven > 3
                                                                ";
                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_ficha_panel"]));
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                        		?>
	                                                        	<tr>
	                                                        		<td>Promesa de Compra Venta ( Depto: <?php echo $fila['nombre_viv']; ?>)</td>
	                                                        		<td>
	                                                        			<?php 
	                                                        			if ($fila['id_tor'] > 5) {
		                                                        			if ($_SESSION["sesion_perfil_panel"] == 1) {
			                                                        			if( $fila['id_mod'] <> 3) {
			                                                        			 ?>
			                                                        			<!-- Formato Promesa -->
				                                                        			<button value="<?php echo $fila['id_ven']; ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="1" data-per="1" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_prueba" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-text-o"></i> Compraventa A</button>
			                                                        				<button value="<?php echo $fila['id_ven']; ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="1" data-per="2" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_prueba" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-text-o"></i> Compraventa B</button>
			                                                        			<?php 
			                                                        			} else {
			                                                        			 ?>
			                                                        			<!-- Formato Promesa -->
				                                                        			<button value="<?php echo $fila['id_ven']; ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="2" data-per="1" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_prueba" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-text-o"></i> Compraventa A</button>
			                                                        				<button value="<?php echo $fila['id_ven']; ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="2" data-per="2" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_prueba" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-text-o"></i> Compraventa B</button>
			                                                        			<?php 
			                                                        			}
			                                                        		}
			                                                        		if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) {
		                                                        			?>
		                                                        				<button value="<?php echo e64($fila['id_ven']); ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="<?php echo $fila['id_mod']; ?>" data-opc="1" data-pie="1" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_pdf" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-pdf-o"></i> Compraventa A PDF</button>
		                                                        				<button value="<?php echo e64($fila['id_ven']); ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="<?php echo $fila['id_mod']; ?>" data-opc="1" data-pie="2" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_pdf" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-pdf-o"></i> Compraventa A - SIN PIE PDF</button>
		                                                        				<button value="<?php echo e64($fila['id_ven']); ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="<?php echo $fila['id_mod']; ?>" data-opc="2" data-pie="1" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_pdf" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-pdf-o"></i> Compraventa B PDF</button>
		                                                        				<button value="<?php echo e64($fila['id_ven']); ?>" data-pro="<?php echo $fila['id_tor']; ?>" data-mod="<?php echo $fila['id_mod']; ?>" data-opc="2" data-pie="2" type="button" class="btn btn-sm btn-icon btn-success promesa_venta_pdf" data-toggle="tooltip" data-original-title="Promesa CompraVenta"><i class="fa fa-file-pdf-o"></i> Compraventa B - SIN PIE PDF</button>
		                                                        			<?php 
		                                                        			}
		                                                        		}
	                                                        			 ?>
	                                                        		</td>
	                                                        	</tr>
	                                                        	<tr>
	                                                        		<td>Detalle Cierre de Negocio ( Depto: <?php echo $fila['nombre_viv']; ?>)</td>
	                                                        		<td>
	                                                        			<?php
	                                                        			if ($_SESSION["sesion_id_vend"] == $fila['id_vend'] || !isset($_SESSION["sesion_id_vend"])) { 
	                                                        			 ?>
	                                                        			<!-- Formato Cierre -->
	                                                        			<button value="<?php echo $fila['id_ven'] ?>" type="button" class="btn btn-sm btn-icon btn-success cierre_negocio" data-pie="1" data-toggle="tooltip" data-original-title="Carta Cierre Negocio "><i class="fa fa-handshake-o"></i></button>

	                                                        			<button value="<?php echo $fila['id_ven'] ?>" type="button" class="btn btn-sm btn-icon btn-success cierre_negocio" data-pie="0" data-toggle="tooltip" data-original-title="Carta Cierre Negocio - Sin Pie Firma"><i class="fa fa-handshake-o"></i> SIN PIE FIRMA</button>
	                                                        			<?php 
	                                                        			}
	                                                        			 ?>
	                                                        		</td>
	                                                        	</tr>
	                                                        	<?php 
	                                                        	}
	                                                        }
	                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    <?php  
                                    	// }
                                    }
                                    ?>
                                    
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

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
    	$(document).on( "click",".clear_buscador" , function() {
    		$("#resultado").empty();
           	$(document).find('#busqueda').val('');
        });
    	// VER DETALLE CLIENTE
    	$(document).on( "click",".detalle_cliente" , function() {
            valor = $(this).val();
            opc = 1; //ficha cliente
            window.open('ficha_cliente_proceso_detail.php?id_pro='+valor+'&opc='+opc,"_self");
        });
    	// EDITAR DATOS CLIENTE
        $(document).on( "click",".edita_cliente" , function() {
            valor = $(this).val();
            opc = 2; //edita cliente
            window.open('ficha_cliente_proceso_detail.php?id_pro='+valor+'&opc='+opc,"_self");
        });
        // CREAR CLIENTE
        $(document).on( "click",".crea_cliente" , function() {
            valor = $(this).val();
            opc = 3; //crea cliente
            window.open('ficha_cliente_proceso_detail.php?opc='+opc,"_self");
        });
        // OBSERVACIÓN CLIENTE
    	$(document).on( "click",".agrega_observacion" , function() {
            valor = $(this).val();
            opc = 13; //obs cliente
            window.open('ficha_cliente_proceso_detail.php?id_pro='+valor+'&opc='+opc,"_self");
        });

        // EDITAR COTIZACIÓN
        $(document).on( "click",".edita_cotizacion" , function() {
            valor = $(this).val();
            opc = 4; //edita cot
            window.open('ficha_cliente_proceso_detail.php?id_cot='+valor+'&opc='+opc,"_self");
        });
        // CREAR COTIZACION
        $(document).on( "click",".crea_cotizacion" , function() {
            valor = $(this).val();
            opc = 5; //crea cotizacion
            window.open('ficha_cliente_proceso_detail.php?id_pro='+valor+'&opc='+opc,"_self");
        });
        // AGREGA SEGUIMIENTO
        $(document).on( "click",".agrega_seguimiento" , function() {
            valor = $(this).val();
            opc = 6; //crea cotizacion
            window.open('ficha_cliente_proceso_detail.php?id_cot='+valor+'&opc='+opc,"_self");
        });
        // AGREGA EVENTO AGENDA
        $(document).on( "click",".agrega_evento" , function() {
            valor = $(this).val();
            opc = 7; //crea cotizacion
            window.open('ficha_cliente_proceso_detail.php?id_cot='+valor+'&opc='+opc,"_self");
        });
        // PASAR A PROMESA
        $(document).on( "click",".pasar_promesa" , function() {
            valor = $(this).val();
            opc = 8; //crea cotizacion
            window.open('ficha_cliente_proceso_detail.php?id_cot='+valor+'&opc='+opc,"_self");
        });
        // REGISTRAR PAGO
        $(document).on( "click",".registra_pago" , function() {
            valor = $(this).val();
            opc = 9; //registra pago
            window.open('ficha_cliente_proceso_detail.php?id_ven='+valor+'&opc='+opc,"_self");
        });
        // EDITA PAGO
        $(document).on( "click",".edita_pago" , function() {
            valor = $(this).val();
            opc = 10; //editar pago
            window.open('ficha_cliente_proceso_detail.php?id_pag='+valor+'&opc='+opc,"_self");
        });

        // IMPRIMIR COTIZACIÓN
        $(document).on( "click",".print_cotizacion" , function() {
            valor = $(this).val();
            // opc = 10; //editar pago
            window.open('../documento/print_cotizacion.php?id_cot='+valor);
        });

        // PDF COTIZACIÓN
        $(document).on( "click",".pdf_cotizacion" , function() {
            valor = $(this).val();
            // opc = 10; //editar pago
            window.open('../documento/pdf_cotizacion.php?id_cot='+valor);
        });

        // EMAIL COTIZACIÓN
        $(document).on( "click",".email_pdf_cotizacion" , function() {
            valor = $(this).val();
            // opc = 10; //editar pago
            window.open('../documento/email_pdf_cotizacion.php?id_cot='+valor);
        });

        // EDITAR VENTA
        $(document).on( "click",".edita_venta" , function() {
            valor = $(this).val();
            opc = 11; //edita cot
            window.open('ficha_cliente_proceso_detail.php?id_ven='+valor+'&opc='+opc,"_self");
        });

        // DESISTIR VENTA
        $(document).on( "click",".desistimiento" , function() {
            valor = $(this).val();
            opc = 12; //desistir
            window.open('ficha_cliente_proceso_detail.php?id_ven='+valor+'&opc='+opc,"_self");
        });

        // CARTA CIERRE
        $(document).on( "click",".cierre_negocio" , function() {
            valor = $(this).val();
            pie = $(this).attr("data-pie");
            // opc = 10; //editar pago
            window.open('../documento/carta_cierre.php?id='+valor+'&pie='+pie);
        });


        // COMPROBANTE PAGOS
        $(document).on( "click",".comprobante_pago" , function() {
            valor = $(this).val();
            cat = $(this).attr("data-cat");
            // opc = 10; //editar pago
            window.open('../documento/comprobante_valores.php?id='+valor+'&cat='+cat);
        });


        // PROMESA PDF
        $(document).on( "click",".promesa_venta_pdf" , function() {
        	
            valor = $(this).val();
            opc = $(this).attr("data-opc");
            mod = $(this).attr("data-mod");
            pro = $(this).attr("data-pro");
            pie = $(this).attr("data-pie");


            if(mod==3) {
            	window.open('../documento/promesa_venta_oficina_'+pro+'.php?id='+valor+'&opc='+opc+'&pie='+pie);
            } else {
            	window.open('../documento/promesa_venta_depto_'+pro+'.php?id='+valor+'&opc='+opc+'&pie='+pie);
            }
        });

        $(document).on( "click",".promesa_venta_prueba" , function() {
            valor = $(this).val();
            pro = $(this).attr("data-pro");
            mod = $(this).attr("data-mod");
            per = $(this).attr("data-per");

            // opc = 1; //editar pago
            window.open('../documento/word_formato/promesa.php?id_pro='+pro+'&id_mod='+mod+'&id_ven='+valor+'&per='+per);
        });



    	// ver modal
        $(document).on( "click",".detalle" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("../cotizacion/form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });

        // ver modal Propietario
        $(document).on( "click",".detalle_propietario" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("../propietario/form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });
        
        //BUSQUEDA
        $("#busqueda").focus();
        $("#busqueda").keyup(function(e){
           $('#resultado').show();               
              //obtenemos el texto introducido en el campo de búsqueda
           var consulta = $("#busqueda").val();
                                                                  
              $.ajax({
                    type: "POST",
                    url: "busca_cliente.php",
                    data: "b="+consulta,
                    dataType: "html",
                    beforeSend: function(){
                          //imagen de carga
                          $("#resultado").html("<p align='center'><img src='../../assets/img/loading.gif'/></p>");
                    },
                    error: function(){
                          alert("error petición ajax");
                    },
                    success: function(data){                                              
                          $("#resultado").empty();
                          $("#resultado").append(data);                                  
                    }
              });                                                              
        });
        $(document).on( "click",".busqueda" , function() {
            //$('#contenedor_opcion').html('<img src="../../imagen/ajax-loader.gif">');
            var valor = $(this).attr( "id" );
            $.ajax({
                type: 'POST',
                url: ("procesa_cliente.php"),
                data:"valor="+valor,
                dataType:'json',
                success: function(data) {
                    resultado_busqueda(data);
                }       
            })
            
        });
        function resultado_busqueda(data) {
            location.reload();
        }

        //ELIMINAR SESION  CLIENTE
        $(document).on("click",".eliminar_sesion" , function() {
            valor = 1;
            $.ajax({
                type: 'POST',
                url: ("sesion_cliente_delete.php"),
                data:"valor="+valor,
                success: function(data) {
                    location.reload();
                }
            })
        });
        //*******//

        // -----------Elimina cotización
        function resultado_eliminar(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro eliminado con éxito!",
                  type: "success"                    
                }).then(()=>{
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }
        $(document).on( "click",".elimina_cotizacion" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar el registro seleccionado!",
                type: "warning"
                
            }).then(()=>{
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/delete.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar(data);
                    }
                })
            });
        });

        // elimina pago
        $(document).on( "click",".elimina_pago" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar el registro seleccionado!",
                type: "warning"                
            }).then(()=>{
                $.ajax({
                    type: 'POST',
                    url: ("../venta/delete_detalle.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar(data);
                    }
                })
            });
        });
        
        // recalcula venta
        function resultado_recalcula(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro actualizado con éxito!",
                  type: "success"                    
                }).then(()=> location.reload());
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }
        $(document).on( "click",".recalcula_venta" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Recalculará comsiones de la venta!",
                type: "warning"
                
            }).then(()=>{
                $.ajax({
                    type: 'POST',
                    url: ("../venta/recalcula_venta.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_recalcula(data);
                    }
                })
            });
        });
        
    });
</script>
</body>
</html>
