<?php 
session_start(); 
require "../../config.php"; 
// if (!isset($_SESSION["sesion_usuario_panel"])) {
//     header("Location: "._ADMIN."index.php");
// }

$_SESSION["sesion_id_panel"] = 19;

require_once _INCLUDE."head_informe.php";
?>
<title>Operación - Listado</title>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
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

.container-fluid .content .form-control {
    display: inline-block;
    width: auto;
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
	<!-- modalo GGOOPP -->
	<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>

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
                                    proceso.opcion_pro = 1 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="../informe/operacion_listado.php">LISTADO VENTAS</a></li>
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
                                    proceso.opcion_pro = 23 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="cliente_seguimiento_cot.php">CLIENTES SIN SEG.</a></li>
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
                                <li><a href="venta_ficha_cliente_venta.php">FICHA DE CLIENTE</a></li>
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
                                proceso.opcion_pro = 1 AND
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
                                                    
                                                    <div class="col-sm-11">

                                                        <div class="col-sm-5">
                                                            <!-- <div class="col-sm-4 radiomio">
                                                                <div class="radio bg-grays" style="margin-top: 20px; padding: 5px">
                                                                    
                                                                    <input id="estado_operacion1" type="radio" name="estado_operacion" class="filtro" value="1">
                                                                    <label for="estado_operacion1">Atrasadas</label>


                                                                    <input id="estado_operacion2" type="radio" name="estado_operacion" class="filtro" value="2">
                                                                    <label for="estado_operacion2">En Fecha</label>

                                                                        
                                                                </div> 
                                                            </div> -->
                                                            <div class="col-sm-3">
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
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="fecha_desde">Fecha Desde:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                        <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label for="fecha_hasta">Fecha Hasta:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                        <input type="text" class="form-control chico pull-right datepicker" name="fecha_hasta" id="fecha_hasta">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="vendedor">Vendedor:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="vendedor" name="vendedor"> 
                                                                    <option value="">Seleccione Vendedor</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_vend, CONCAT(nombre_vend,' ',apellido_paterno_vend) AS nombre FROM vendedor_vendedor ORDER BY nombre_vend, apellido_paterno_vend";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_vendedor_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_vendedor_original)){
                                                                        foreach ($fila_consulta_vendedor_original as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_vend'];?>"><?php echo utf8_encode($fila['nombre']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="cliente">Cliente:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control select2" id="cliente" name="cliente"> 
                                                                    <option value="">Seleccione Cliente</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_pro, CONCAT(nombre_pro,' ',apellido_paterno_pro) AS nombre_prop, nombre_pro, apellido_paterno_pro, apellido_materno_pro FROM propietario_propietario ORDER BY nombre_pro, apellido_paterno_pro, apellido_materno_pro";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_propietario_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_propietario_original)){
                                                                        foreach ($fila_consulta_propietario_original as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <label for="forma_pago">F. de Pago:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="forma_pago" name="forma_pago"> 
                                                                    <option value="">Seleccione F. de Pago</option>
                                                                    <?php  
                                                                    $consulta = "SELECT * FROM pago_forma_pago ORDER BY nombre_for_pag";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_forma_pago_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_forma_pago_original)){
                                                                        foreach ($fila_consulta_forma_pago_original as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_for_pag'];?>"><?php echo utf8_encode($fila['nombre_for_pag']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-1" >
                                                            <div class="form-group">
                                                                <label for="estado_venta">Estado:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="estado_venta" name="estado_venta"> 
                                                                    <option value="">Seleccione</option>
                                                                    <?php  
                                                                    $consulta = "SELECT * FROM venta_estado_venta WHERE id_est_ven IN (1,2,3) ORDER BY nombre_est_ven";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_estado_venta_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_estado_venta_original)){
                                                                        foreach ($fila_consulta_estado_venta_original as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_est_ven'];?>"><?php echo utf8_encode($fila['nombre_est_ven']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <option value="4">Ventas No Escrituradas</option>
                                                                    <option value="6">Ventas Escrituradas</option>
                                                                    <option value="8">Sin Desistimientos</option>
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
                                                    $elimina_filtro = 0;
                                                    if(isset($_SESSION["id_estado_filtro_panel"])){
                                                        $elimina_filtro = 1;
                                                        if($_SESSION["id_estado_filtro_panel"] == 1){
                                                            $texto_filtro = "Atrasadas";
                                                        }
                                                        else{
                                                            $texto_filtro = "En Fecha";
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo $texto_filtro;?></span> | 
                                                        <?php
                                                        //$filtro_consulta = " AND alu.id_alu = ".$_SESSION["id_alumno_filtro_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                                                    	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                                                        $fila_consulta_condominio = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_condominio[]=$v;
                                                        }
                                                        $elimina_filtro = 1;

                                                        $flipped_condominio = array_flip($fila_consulta_condominio);
                                                        
                                                        if(is_array($fila_consulta_condominio)){
                                                            foreach ($fila_consulta_condominio as $fila) {
                                                            	if(isSet($flipped_condominio[$_SESSION["sesion_filtro_condominio_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                    $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                            	}
                                                                // if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                //     $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                                // }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |
                                                        <?php
                                                        $filtro_consulta .= " AND viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }
                                                    
                                                    if(isset($_SESSION["sesion_filtro_fecha_desde_panel"])){
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <span class="label label-primary"><?php echo $_SESSION["sesion_filtro_fecha_desde_panel"];?></span> |
                                                        <?php
                                                        $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                        $filtro_consulta .= " AND ven.fecha_ven >= '".$fecha."'";
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }
                            
                            
                                                    if(isset($_SESSION["sesion_filtro_fecha_hasta_panel"])){
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <span class="label label-primary"><?php echo $_SESSION["sesion_filtro_fecha_hasta_panel"];?></span> |
                                                        <?php
                                                        $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));
                                                        $filtro_consulta .= " AND ven.fecha_ven <= '".$fecha."'";
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> |
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_vendedor_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vendedor_original));
                                                        $fila_consulta_vendedor = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_vendedor[]=$v;
                                                        }
                                                        $elimina_filtro = 1;

                                                        $flipped_vendedor = array_flip($fila_consulta_vendedor);
                                                        
                                                        if(is_array($fila_consulta_vendedor)){
                                                            foreach ($fila_consulta_vendedor as $fila) {
                                                                // if(in_array($_SESSION["sesion_filtro_vendedor_panel"],$fila_consulta_vendedor)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vendedor);
                                                                //     $texto_filtro = $fila_consulta_vendedor[$key + 1];
                                                                // }
                                                                if(isSet($flipped_vendedor[$_SESSION["sesion_filtro_vendedor_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vendedor);
                                                                    $texto_filtro = $fila_consulta_vendedor[$key + 1];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_vend = ".$_SESSION["sesion_filtro_vendedor_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }
                                                    
                                                    if(isset($_SESSION["sesion_filtro_propietario_panel"])){
                                                        // $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_propietario_original));
                                                        // $fila_consulta_propietario = array();
                                                        // foreach($it as $v) {
                                                        //     $fila_consulta_propietario[]=$v;
                                                        // }
                                                        $elimina_filtro = 1;
                                                        
                                                        // if(is_array($fila_consulta_propietario)){
                                                        //     foreach ($fila_consulta_propietario as $fila) {
                                                        //         if(in_array($_SESSION["sesion_filtro_propietario_panel"],$fila_consulta_propietario)){
                                                        //             $key = array_search($_SESSION["sesion_filtro_propietario_panel"], $fila_consulta_propietario);
                                                        //             $texto_filtro = $fila_consulta_propietario[$key + 1];
                                                        //         }
                                                        //     }
                                                        // }
                                                        $consulta = "SELECT nombre_pro, apellido_paterno_pro FROM propietario_propietario WHERE id_pro = ".$_SESSION["sesion_filtro_propietario_panel"]."";
                                                        $conexion->consulta($consulta);
                                                        $filanombre = $conexion->extraer_registro_unico();


                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($filanombre['nombre_pro']." ".$filanombre['apellido_paterno_pro']);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_pro = ".$_SESSION["sesion_filtro_propietario_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_forma_pago_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_forma_pago_original));
                                                        $fila_consulta_forma_pago = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_forma_pago[]=$v;
                                                        }
                                                        $elimina_filtro = 1;

                                                        $flipped_forma_pago = array_flip($fila_consulta_forma_pago);
                                                        
                                                        if(is_array($fila_consulta_forma_pago)){
                                                            foreach ($fila_consulta_forma_pago as $fila) {
                                                                // if(in_array($_SESSION["sesion_filtro_forma_pago_panel"],$fila_consulta_forma_pago)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_forma_pago_panel"], $fila_consulta_forma_pago);
                                                                //     $texto_filtro = $fila_consulta_forma_pago[$key + 1];
                                                                // }
                                                                if(isSet($flipped_forma_pago[$_SESSION["sesion_filtro_forma_pago_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_forma_pago_panel"], $fila_consulta_forma_pago);
                                                                    $texto_filtro = $fila_consulta_forma_pago[$key + 1];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_for_pag = ".$_SESSION["sesion_filtro_forma_pago_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> |
                                                        <?php       
                                                    }


                                                    if(isset($_SESSION["sesion_filtro_estado_venta_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_estado_venta_original));
                                                        $fila_consulta_estado_venta = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_estado_venta[]=$v;
                                                        }
                                                        $elimina_filtro = 1;

                                                        $flipped_estado_venta = array_flip($fila_consulta_estado_venta);
                                                        
                                                        if(is_array($fila_consulta_estado_venta)){
                                                            foreach ($fila_consulta_estado_venta as $fila) {
                                                                // if(in_array($_SESSION["sesion_filtro_estado_venta_panel"],$fila_consulta_estado_venta)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_estado_venta_panel"], $fila_consulta_estado_venta);
                                                                //     $texto_filtro = $fila_consulta_estado_venta[$key + 1];
                                                                // }
                                                                if(isSet($flipped_estado_venta[$_SESSION["sesion_filtro_estado_venta_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_estado_venta_panel"], $fila_consulta_estado_venta);
                                                                    $texto_filtro = $fila_consulta_estado_venta[$key + 1];
                                                            	}
                                                            }
                                                        }
														if ($_SESSION["sesion_filtro_estado_venta_panel"]<>4 && $_SESSION["sesion_filtro_estado_venta_panel"]<> 6 && $_SESSION["sesion_filtro_estado_venta_panel"]<> 8) {
                                                        ?>
                                                        	<span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        	$filtro_consulta .= " AND ven.id_est_ven = ".$_SESSION["sesion_filtro_estado_venta_panel"];
                                                        } else if ($_SESSION["sesion_filtro_estado_venta_panel"]==4) {
                                                        ?>
                                                        	<span class="label label-primary"><?php echo "No Escrituradas";?></span>  
                                                        <?php
                                                        	$filtro_consulta .= " AND ven.id_est_ven = 4 OR ven.id_est_ven = 5";
                                                        } else if ($_SESSION["sesion_filtro_estado_venta_panel"]==6) {
														?>
                                                        	<span class="label label-primary"><?php echo "Escrituradas";?></span>  
                                                        <?php
                                                        	$filtro_consulta .= " AND ven.id_est_ven = 6 OR ven.id_est_ven = 7";
                                                        } else if ($_SESSION["sesion_filtro_estado_venta_panel"]==8) {
														?>
                                                        	<span class="label label-primary"><?php echo "Sin Desistimientos";?></span>  
                                                        <?php
                                                        	$filtro_consulta .= " AND NOT ven.id_est_ven = 3";
                                                        }
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
                                                        <h3 class="box-title">Listado de Ventas - 
                                                        <?php 
														if($_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
                                                        	?>
                                                        <a href="operacion_listado_exc.php" target="_blank" class="btn btn-info">Exportar Listado Completo</a></h3>
                                                        <?php 
														} else if($_SESSION["sesion_perfil_panel"]==6){
                                                         ?>
                                                        <a href="listado_cierre_exc.php" target="_blank" class="btn btn-info">Exportar Listado Cierre Negocio</a></h3>
                                                        <?php 
														}
														?>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
                                                    	<div class="wmd-view-topscroll">
														    <div class="scroll-div1">
														    </div>
														</div>
                                                        <div class="table-responsive wmd-view">
                                                            <table id="example" class="table table-striped table-bordered scroll-div2" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Condominio</th>
                                                                        <th>Modelo</th>
                                                                        <th>Depto</th>
                                                                        <th>Orientación</th>
                                                                        <th>Vendedor</th>
                                                                        <th>Categoría</th>
                                                                        <th>Cliente</th>
                                                                        <th>Fecha Venta</th>
                                                                        <th>Est. Adic.</th>
                                                                        <th>Forma Pago</th>
                                                                        <th>Banco/Tipo Pago</th>
                                                                        <th>Pie(%)</th>
                                                                        <th>Crédito Hipotecario</th>
                                                                        <th>Saldo a Pagar</th>
                                                                        <th>Estado Venta</th>
                                                                        <th>Etapa Actual</th>
                                                                        <th>Estado Etapa</th>
                                                                        <th>Premio</th>
                                                                        <th>Canal Llegada</th>
                                                                        <th>Dcto. (UF)</th>
                                                                        <th>Desistimiento</th>
                                                                        <th>Total</th>
                                                                    </tr>    
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $acumulado_monto = 0;
                                                                    
                                                                    
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            ven.id_ven,
                                                                            ven.fecha_ven,
                                                                            ven.monto_ven,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            ori_viv.nombre_ori_viv,
                                                                            mode.id_mod,
                                                                            mode.nombre_mod,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            vend.id_vend,
                                                                            vend.nombre_vend,
                                                                            vend.apellido_paterno_vend,
                                                                            vend.apellido_materno_vend,
                                                                            pro.id_pro,
                                                                            pro.nombre_pro,
                                                                            pro.apellido_paterno_pro,
                                                                            pro.apellido_materno_pro,
                                                                            esta.id_esta,
                                                                            esta.nombre_esta,
                                                                            for_pag.id_for_pag,
                                                                            for_pag.nombre_for_pag,
                                                                            ven.id_pie_ven,
                                                                            cat.nombre_cat_vend,
                                                                            ven.descuento_ven,
                                                                            pre.nombre_pre,
                                                                            ven.monto_credito_ven,
                                                                            ven.monto_credito_real_ven,
                                                                            estado_venta.nombre_est_ven,
                                                                            ven.id_ban,
                                                                            ven.id_tip_pag,
                                                                            ban.nombre_ban,
                                                                            tip_pag.nombre_tip_pag,
                                                                            ven.id_est_ven,
                                                                            ven.cotizacion_ven
                                                                        FROM 
                                                                            venta_venta AS ven
                                                                            INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
                                                                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
                                                                            -- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                            INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
                                                                            INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                            INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                                            LEFT JOIN venta_estacionamiento_venta AS est_ven ON est_ven.id_ven = ven.id_ven
                                                                            LEFT JOIN estacionamiento_estacionamiento AS esta ON esta.id_esta = est_ven.id_esta
                                                                            LEFT JOIN vendedor_categoria_vendedor AS cat ON cat.id_cat_vend = ven.factor_categoria_ven
                                                                            LEFT JOIN premio_premio AS pre ON pre.id_pre = ven.id_pre
                                                                            LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
                                                                            LEFT JOIN pago_tipo_pago AS tip_pag ON tip_pag.id_tip_pag = ven.id_tip_pag
                                                                        WHERE 
                                                                            ven.id_ven > 0
                                                                            ".$filtro_consulta."
                                                                        ORDER BY 
                                                                            ven.fecha_ven
                                                                        "; 
                                                                    // echo $consulta;
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
                                                                                    eta_ven.fecha_desde_eta_ven,
                                                                                    eta.duracion_eta,
                                                                                    eta.nombre_eta
                                                                                FROM
                                                                                    venta_venta AS ven
                                                                                    INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
                                                                                    INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
                                                                                WHERE
                                                                                    ven.id_ven = ?
                                                                                ORDER BY 
                                                                                    eta_ven.id_eta_ven
                                                                                DESC
                                                                                LIMIT 0,1
                                                                                ";
                                                                            $conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                            $cantidad_etapa = $conexion->total();
                                                                            if($cantidad_etapa > 0){
                                                                                $fila_venta = $conexion->extraer_registro_unico();
                                                                                $hoy = date("Y-m-d");
                                                                                $fecha_inicio = $fila_venta["fecha_desde_eta_ven"];
                                                                                $duracion = $fila_venta["duracion_eta"];
                                                                                $etapa_nombre = $fila_venta["nombre_eta"];
                                                                                $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
                                                                                $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
                                                                                if($fecha_final > $hoy){
                                                                                    $etapa_estado = "Atrasada";
                                                                                    $etapa = 1;
                                                                                }
                                                                                else{
                                                                                    $etapa_estado = "En fecha";
                                                                                    $etapa = 2;
                                                                                }
                                                                            }
                                                                            else{
                                                                                $etapa_nombre = "--";
                                                                                $etapa_estado = "No iniciada";
                                                                                $etapa = 3;
                                                                            }
                                                                            $mostrar = 1;
                                                                            if(isset($_SESSION["id_estado_filtro_panel"])){
                                                                                if($_SESSION["id_estado_filtro_panel"] != $etapa){
                                                                                    $mostrar = 2;
                                                                                }
                                                                            }
																			$nombre_tip_des = "";
																			if ($fila['id_est_ven']==3) {
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
															                    $nombre_tip_des = "<br><font style='font-size:11px'>(".utf8_encode($fila_des["nombre_tip_des"]).")</font>";
																			}

																			$consulta_canal = 
															                        "
																                        SELECT
																                            cot_can.nombre_can_cot
																                        FROM
																                            cotizacion_canal_cotizacion AS cot_can,
																                            cotizacion_cotizacion AS cot
																                        WHERE
																                            cot.id_cot = " .$fila["cotizacion_ven"]. " AND 
																                            cot.id_can_cot = cot_can.id_can_cot
																                        ";
																                    $conexion->consulta($consulta_canal);
																                    $fila_can = $conexion->extraer_registro_unico();
																                    $canal_llegada = utf8_encode($fila_can['nombre_can_cot']);

                                                                            if($mostrar == 1){
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_mod']); ?></td>
                                                                                    <?php
                                                                                    // filtrar solo contabilidad
                                                                                    $boton_liquidado = "";
                                                                                    if (($_SESSION["sesion_perfil_panel"]==3 || $_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_perfil_panel"]==2) && ($fila['id_est_ven']<>3)) {
																							$consultahay = 
																							    "
																							    SELECT
																							        monto_liq_uf_ven,
																							        fecha_liq_ven
																							    FROM
																							        venta_liquidado_venta
																							    WHERE 
																							        id_ven = ?
																							    ";
																							$conexion->consulta_form($consultahay,array($fila["id_ven"]));
																							$insert = $conexion->total();
																							if ($insert==0) {
																								$btn = "btn-warning";
																							} else {
																								$btn = "btn-info";
																							}

	                                                                                        $boton_liquidado = "- <a id='".$fila["id_ven"]."' class='btn ".$btn." btn-sm carga_liquida'>Cargar Información<br>de la Operación</a>";
	                                                                                    
	                                                                                }
                                                                                    ?>
                                                                                    <td data-order="<?php echo utf8_encode($fila['nombre_viv']); ?>"><?php echo utf8_encode($fila['nombre_viv']); ?><?php echo $boton_liquidado; ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_ori_viv']); ?></td>
                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_cat_vend']); ?></td>
                                                                                    <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                                    <td><?php echo $fecha_venta; ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_esta']); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_for_pag']); ?></td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($fila['id_for_pag'] == 1) {
                                                                                            echo utf8_encode($fila['nombre_ban']);
                                                                                        }
                                                                                        else if ($fila['id_for_pag'] == 2){
                                                                                            echo utf8_encode($fila['nombre_tip_pag']);
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td><?php echo utf8_encode($fila['id_pie_ven']); ?></td>
                                                                                    <?php 
																					if ($fila['id_for_pag']==2) {
																						$saldo_contado = number_format($fila['monto_credito_ven'], 2, ',', '.');
																						$saldo_credito = 0;
																					} else {
                                                                                    	if ($fila['monto_credito_real_ven']<>'' && $fila['monto_credito_real_ven']<>0) {
                                                                                    		$saldo_credito = number_format($fila['monto_credito_real_ven'], 2, ',', '.');
                                                                                    	} else {
                                                                                    		$saldo_credito = number_format($fila['monto_credito_ven'], 2, ',', '.');
                                                                                    	}
                                                                                    	$saldo_contado = 0;
																					}
																					?>
                                                                                    <td><?php echo $saldo_credito;?></td>
                                                                                    <td><?php echo $saldo_contado;?></td>
                                                                                    <td><?php 
                                                                                    	echo utf8_encode($fila['nombre_est_ven']); 
                                                                                    	echo $nombre_tip_des;
                                                                                    	?></td>
                                                                                    <td><?php echo utf8_encode($etapa_nombre); ?></td>
                                                                                    <td><?php echo utf8_encode($etapa_estado); ?></td>
                                                                                    <td><?php echo utf8_encode($fila['nombre_pre']); ?></td>
                                                                                    <td><?php echo $canal_llegada; ?></td>
                                                                                    <td><?php echo number_format($fila['descuento_ven'], 2, ',', '.');?></td>
                                                                                    <?php 
																					if ($fila['id_est_ven']==3) {
																						$monto_desestimiento = $fila['monto_ven'];
																						$monto_ven = 0;
																					} else {
                                                                                    	$monto_desestimiento = 0;
                                                                                    	$monto_ven = $fila['monto_ven'];
																					}
																					?>
                                                                                    <td><?php echo number_format($monto_desestimiento, 2, ',', '.');?></td>
                                                                                    <td><?php echo number_format($monto_ven, 2, ',', '.');?></td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>   
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="20"></td>
                                                                        
                                                                        <td>$<?php echo number_format($acumulado_monto, 2, ',', '.'); ?></td>
                                                                    </tr> 
                                                                </tfoot>
                                                                
                                                                
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
                            <?php
                        }
                        ?>
                        
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
<script src="<?php echo _ASSETS?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/datetime-moment.js"></script>
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
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
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
            var_condominio = $('#condominio').val();
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            // var_estado = $('.filtro:checked').val();
            var_vendedor = $('#vendedor').val();
            var_cliente = $('#cliente').val();
            var_forma_pago = $('#forma_pago').val();
            var_estado_venta = $('#estado_venta').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&cliente="+var_cliente+"&vendedor="+var_vendedor+"&forma_pago="+var_forma_pago+"&estado_venta="+var_estado_venta+"&condominio="+var_condominio,
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
        //BUSQUEDA ALUMNO
        $("#busqueda_alu").focus();
        $("#busqueda_alu").keyup(function(e){
           $('#resultado').show();               
              //obtenemos el texto introducido en el campo de búsqueda
           var consulta = $("#busqueda_alu").val();
                                                                  
              $.ajax({
                    type: "POST",
                    url: "busca_alumno.php",
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
        $(document).on( "click",".busqueda_alumno" , function() {
            //$('#contenedor_opcion').html('<img src="../../imagen/ajax-loader.gif">');
            var valor = $(this).attr( "id" );
            $.ajax({
                type: 'POST',
                url: ("procesa_alumno.php"),
                data:"valor="+valor,
                success: function(data) {
                    location.reload();
                }       
            })
            
        });
        var table = $('#example').DataTable( {
            "pageLength": 70,
            dom:'lfBrtip',
            // success de tabla
            lengthChange: true,
            buttons: [ 'copy', {
                extend: 'excelHtml5',
                exportOptions: {
                	orthogonal: 'export',
                    columns: ':visible',
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
            "aaSorting": [[ 6, "asc" ]],
            "aoColumns": [
                { "sType": "string" }, //1
                { "sType": "string" }, //2
                { "sClass": "prueba", "sType": "natural", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
       					arr = arr.split('-');
          				return arr[0];
                	} else {
                		return data;
                	}
	            } }, //3
                { "sType": "string" }, //4
                { "sType": "string" }, //5
                { "sType": "string" }, //6
                { "sType": "string" }, //7
                { "sType": "date-uk" }, //8
                { "sType": "string" }, //9
                { "sType": "string" }, //10
                { "sType": "string" }, //11
                null, //12
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //13
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //14
                { "sType": "string" }, //15
                { "sType": "string" }, //16
                { "sType": "string" }, //17
                { "sType": "string" }, //18
                { "sType": "string" }, //19
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //19
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //20
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } } //21
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
