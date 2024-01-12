<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";

?>
<title>Cotización - Listado</title>
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

.noseg{
	background-color: rgba(238, 187, 17, .2);
}

.red{
	color:#FFFFFF;
	font-weight: bold;
	background-color: #FF0500;
	border-radius: 10px;
	padding: 5px;
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

// parametro cantidad días tolerancia seg
$consulta_par = 
  "
  SELECT 
    valor_par
  FROM
    parametro_parametro
  WHERE 
  	id_par = 22
  ";
$conexion->consulta($consulta_par);
$fila = $conexion->extraer_registro_unico();
$tolerancia = $fila["valor_par"];

$hoy = date("Y-m-d");
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
          Cotizaciones
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Cotizaciones</li>
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
                                <li class="active"><a href="venta_cotizacion_venta.php">LISTADO COTIZACIONES</a></li>
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
                                proceso.opcion_pro = 10 AND
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
                                                    <div class="col-sm-3 radiomio">
                                                        <div class="radio bg-grays" style="margin-top: 20px; padding: 5px">
                                                            <?php  
                                                            $consulta = "SELECT * FROM cotizacion_estado_cotizacion ORDER BY nombre_est_cot";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <input id="estado_cotizacion<?php echo $fila['id_est_cot'];?>" type="radio" name="estado_cotizacion" class="filtro" value="<?php echo $fila['id_est_cot'];?>">
                                                                    <label for="estado_cotizacion<?php echo $fila['id_est_cot'];?>"><?php echo utf8_encode($fila['nombre_est_cot']);?></label>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>

                                                                
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div class="col-sm-5">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fecha_desde">Fecha Desde:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                        <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="fecha_hasta">Fecha Hasta:</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                        <input type="text" class="form-control chico pull-right datepicker" name="fecha_hasta" id="fecha_hasta">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="vendedor">Vendedor:</label>
                                                                      <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                    <select class="form-control select2 chico" id="vendedor" name="vendedor"> 
                                                                        <option value="">Seleccione Vendedor</option>
                                                                        <?php  
                                                                        $consulta = "SELECT * FROM vendedor_vendedor ORDER BY nombre_vend, apellido_paterno_vend";
                                                                        $conexion->consulta($consulta);
                                                                        $fila_consulta_vendedor_original = $conexion->extraer_registro();
                                                                        if(is_array($fila_consulta_vendedor_original)){
                                                                            foreach ($fila_consulta_vendedor_original as $fila) {
                                                                                ?>
                                                                                <option value="<?php echo $fila['id_vend'];?>"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']);?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="cliente">Cliente:</label>
                                                                      <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                    <select class="form-control chico select2" id="cliente" name="cliente"> 
                                                                        <option value="">Seleccione Cliente</option>
                                                                        <?php  
                                                                        $consulta = "SELECT * FROM propietario_propietario ORDER BY nombre_pro, apellido_paterno_pro, apellido_materno_pro";
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
                                                    if(isset($_SESSION["id_estado_cotizacion_filtro_panel"])){
                                                        $elimina_filtro = 1;

                                                        $consulta = "SELECT * FROM cotizacion_estado_cotizacion WHERE id_est_cot = ".$_SESSION["id_estado_cotizacion_filtro_panel"]." ORDER BY nombre_est_cot";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta)){
                                                            foreach ($fila_consulta as $fila) {
                                                                $texto_filtro = utf8_encode($fila['nombre_est_cot']);
                                                                $filtro_consulta .= " AND cot.id_est_cot = '".$_SESSION["id_estado_cotizacion_filtro_panel"]."'";
                                                            }
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
                                                    
                                                    if(isset($_SESSION["sesion_filtro_fecha_desde_panel"])){
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <span class="label label-primary"><?php echo $_SESSION["sesion_filtro_fecha_desde_panel"];?></span> |
                                                        <?php
                                                        $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                        $filtro_consulta .= " AND cot.fecha_cot >= '".$fecha."'";
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
                                                        $filtro_consulta .= " AND cot.fecha_cot <= '".$fecha."'";
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
                                                                //     $texto_filtro = $fila_consulta_vendedor[$key + 5]." ".$fila_consulta_vendedor[$key + 6];
                                                                // }

                                                                if(isSet($flipped_vendedor[$_SESSION["sesion_filtro_vendedor_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vendedor);
                                                                    $texto_filtro = $fila_consulta_vendedor[$key + 5]." ".$fila_consulta_vendedor[$key + 6];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND cot.id_vend = ".$_SESSION["sesion_filtro_vendedor_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }
                                                    
                                                    if(isset($_SESSION["sesion_filtro_propietario_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_propietario_original));
                                                        $fila_consulta_propietario = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_propietario[]=$v;
                                                        }
                                                        $elimina_filtro = 1;

                                                        $flipped_propietario = array_flip($fila_consulta_propietario);
                                                        
                                                        if(is_array($fila_consulta_propietario)){
                                                            foreach ($fila_consulta_propietario as $fila) {
                                                                // if(in_array($_SESSION["sesion_filtro_propietario_panel"],$fila_consulta_propietario)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_propietario_panel"], $fila_consulta_propietario);
                                                                //     $texto_filtro = $fila_consulta_propietario[$key + 10]." ".$fila_consulta_propietario[$key + 12];
                                                                // }

                                                                if(isSet($flipped_propietario[$_SESSION["sesion_filtro_propietario_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_propietario_panel"], $fila_consulta_propietario);
                                                                    $texto_filtro = $fila_consulta_propietario[$key + 10]." ".$fila_consulta_propietario[$key + 12];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> 
                                                        <?php
                                                        $filtro_consulta .= " AND cot.id_pro = ".$_SESSION["sesion_filtro_propietario_panel"];
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
                                                        <h3 class="box-title">Listado de Cotizaciones</h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding table-responsive">
                                                        <!-- <table class="table table-striped"> -->
                                                        <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Condominio</th>
                                                                    <th>Torre</th>
                                                                    <th>Modelo</th>
                                                                    <th>Depto</th>
                                                                    <th>Cliente</th>
                                                                    <th>Teléfono</th>
                                                                    <th>Mail</th>
                                                                    <th>Canal</th>
                                                                    <th>Vendedor</th>
                                                                    <th>Fecha</th>
                                                                    <th>Nivel Int.</th>
                                                                    <th>Seguimientos</th>
                                                                    <th>Estado</th>
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $acumulado_monto = 0;
                                                                
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
                                                                        cot.id_cot,
                                                                        con.nombre_con,
                                                                        tor.nombre_tor,
                                                                        modelo.nombre_mod,
                                                                        viv.nombre_viv,
                                                                        pro.nombre_pro,
                                                                        pro.apellido_paterno_pro,
                                                                        pro.apellido_materno_pro,
                                                                        pro.fono_pro,
                                                                        pro.correo_pro,
                                                                        can_cot.nombre_can_cot,
                                                                        vend.nombre_vend,
                                                                        vend.apellido_paterno_vend,
                                                                        vend.apellido_materno_vend,
                                                                        cot.fecha_cot,
                                                                        est_cot.nombre_est_cot,
                                                                        cot.id_est_cot,
                                                                        cot.id_pro,
                                                                        cot_seg.nombre_seg_int_cot
                                                                    FROM 
                                                                        cotizacion_cotizacion AS cot 
                                                                        INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                                                                        INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = cot.id_mod
                                                                        INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                                                                        INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                        LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
                                                                        LEFT JOIN cotizacion_seguimiento_interes_cotizacion AS cot_seg ON cot_seg.id_seg_int_cot = cot.id_seg_int_cot
                                                                    WHERE 
                                                                        cot.id_est_cot > 0 AND YEAR(cot.fecha_cot) > 2019 
                                                                        ".$filtro_consulta.""; 

                                                                $consulta .= " ORDER BY 
                                                                        cot.id_cot DESC
                                                                    ";

                                                                if (isset($_GET['sin'])) {
                                                                	$consulta = 
                                                                    "
                                                                    SELECT 
                                                                        cot.id_cot,
                                                                        con.nombre_con,
                                                                        tor.nombre_tor,
                                                                        modelo.nombre_mod,
                                                                        viv.nombre_viv,
                                                                        pro.nombre_pro,
                                                                        pro.apellido_paterno_pro,
                                                                        pro.apellido_materno_pro,
                                                                        pro.fono_pro,
                                                                        pro.correo_pro,
                                                                        can_cot.nombre_can_cot,
                                                                        vend.nombre_vend,
                                                                        vend.apellido_paterno_vend,
                                                                        vend.apellido_materno_vend,
                                                                        cot.fecha_cot,
                                                                        est_cot.nombre_est_cot,
                                                                        cot.id_est_cot,
                                                                        cot.id_pro
                                                                    FROM 
                                                                        cotizacion_cotizacion AS cot 
                                                                        INNER JOIN vendedor_jefe_vendedor AS jef ON jef.id_vend = cot.id_vend
                                                                        INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                                                                        INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = cot.id_mod
                                                                        INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                                                                        INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                        LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
                                                                    WHERE 
                                                                        (cot.id_est_cot = 1 or cot.id_est_cot = 4) AND
                                                                        jef.id_vend = cot.id_vend AND
                                                                        cot.id_pro <> 0 AND
																    	jef.id_usu = ".$_SESSION["sesion_id_panel"]." AND NOT
																        EXISTS(
																            SELECT 
																                seg.id_cot
																            FROM
																                cotizacion_seguimiento_cotizacion AS seg
																            WHERE
																                seg.id_cot = cot.id_cot
																        ) AND NOT
																        EXISTS(
																            SELECT 
																                obs.id_pro
																            FROM
																                propietario_observacion_propietario AS obs
																            WHERE
																                obs.id_pro = cot.id_pro
																        )
                                                                        ".$filtro_consulta.""; 

	                                                                $consulta .= " ORDER BY 
	                                                                        cot.id_cot DESC
	                                                                    ";
                                                                }

                                                                // echo $consulta;

                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
                                                                        if ($fila['fecha_cot'] == '0000-00-00') {
                                                                            $fecha_cotizacion = "";
                                                                        }
                                                                        else{
                                                                            $fecha_cotizacion = date("d/m/Y",strtotime($fila['fecha_cot']));
                                                                        }
                                                                        $acumulado_monto = $acumulado_monto + (isset($fila['monto_ven']))?$fila['monto_ven']:0;
                                                                        $consulta = "SELECT
                                                                        				seg_cot.id_seg_cot
                                                                        			FROM
                                                                        				cotizacion_seguimiento_cotizacion AS seg_cot,
                                                                        				cotizacion_cotizacion AS cot
                                                                        			WHERE
                                                                        				cot.id_cot = seg_cot.id_cot AND
                                                                        				cot.id_pro = ".$fila['id_pro']."";
                                                                        $conexion->consulta($consulta);
                                                                        $cant_seg = $conexion->total();

                                                                        if ($cant_seg==0) {
                                                                        	$clase_fila = "noseg";
                                                                        } else {
                                                                        	$clase_fila = "";
                                                                        }
                                                                        
                                                                        ?>
                                                                        <tr class="<?php echo $clase_fila; ?>">
                                                                            <td><?php echo utf8_encode($fila['id_cot']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_tor']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_mod']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['fono_pro']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['correo_pro']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_can_cot']); ?></td>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']); ?></td>
                                                                            <td><?php echo $fecha_cotizacion; ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_seg_int_cot']); ?></td>
                                                                            <td><?php 
                                                                            	$segatrasado = "";
																				if ($cant_seg>0) {
																					$consulta = 
																                        "
																                        SELECT
																                            seg.fecha_seg_cot
																                        FROM
																                            cotizacion_seguimiento_cotizacion AS seg,
																                            cotizacion_cotizacion AS cot
																                        WHERE
																                        	cot.id_cot = seg.id_cot AND
																                            cot.id_pro = ".$fila['id_pro']."
																                        ORDER BY
																                        	seg.fecha_seg_cot ASC
																                        LIMIT 0,1
																                        ";
																                    $conexion->consulta($consulta);
																                    $fila_ultimo = $conexion->extraer_registro_unico();
																                    $fecha_seg_cot = $fila_ultimo["fecha_seg_cot"];
																                    $fecha_ultimo = date("Y-m-d",strtotime($fecha_seg_cot));
																			        $fecha_proximo = date("Y-m-d", strtotime("$fecha_ultimo + $tolerancia days"));
																			        if($fecha_proximo < $hoy){
																			            $segatrasado = "red";
																			        } else {
																			        	$segatrasado = "";
																			        }
																			    }
																			    ?>
																			    <span class="<?php echo $segatrasado; ?>"><?php echo $cant_seg;?></span>
																			    <?php

																				if ($cant_seg > 0) {
																					?>
																					&nbsp;&nbsp;<button value="<?php echo $fila['id_cot']; ?>" class="btn btn-xs btn-info detalle" data-toggle="tooltip" data-original-title="Ver Seguimientos"><i class="fa fa-search"></i></button>
																					<?php
																				}
                                                                            	?>
                                                                            </td>
                                                                            <td><?php echo utf8_encode($fila['nombre_est_cot']); ?></td>
                                                                            <!-- <td><?php echo number_format($fila['monto_ven'], 0, ',', '.');?></td> -->
                                                                        </tr>
                                                                        <?php
                                                                        
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <!-- <tfoot>
                                                                <tr>
                                                                    <td colspan="9"></td>
                                                                    
                                                                    <td>$<?php echo number_format($acumulado_monto, 0, ',', '.'); ?></td>
                                                                </tr> 
                                                            </tfoot> -->
                                                            
                                                            
                                                        </table>
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
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
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
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_estado = $('.filtro:checked').val();
            var_vendedor = $('#vendedor').val();
            var_cliente = $('#cliente').val();
            var_forma_pago = $('#forma_pago').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&cliente="+var_cliente+"&vendedor="+var_vendedor+"&estado_cotizacion="+var_estado+"&forma_pago="+var_forma_pago,
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
            "pageLength": 500,
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
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "date-uk" },
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                null
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
