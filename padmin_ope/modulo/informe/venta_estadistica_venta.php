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

.mt-0{
	margin-top: 0;
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
                                <li class="active"><a href="venta_estadistica_venta.php">ANÁLISIS DE VENTAS</a></li>
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
                                                    <div class="col-sm-7">
                                                        
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="condominio">Condominio:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
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

                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="fecha_desde">Fecha Desde:</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                    <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="fecha_hasta">Fecha Hasta:</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                                    <input type="text" class="form-control chico pull-right datepicker" name="fecha_hasta" id="fecha_hasta">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="form-group">
                                                                <label for="vendedor">Vendedor:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="vendedor" name="vendedor"> 
                                                                    <option value="">Seleccione Vendedor</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_vend, nombre_vend, apellido_paterno_vend FROM vendedor_vendedor ORDER BY nombre_vend, apellido_paterno_vend";
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
                                                                // if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                                //     $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                //     $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                                // }
                                                                if(isSet($flipped_condominio[$_SESSION["sesion_filtro_condominio_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                    $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> | 
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
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
                                                                //     $texto_filtro = $fila_consulta_vendedor[$key + 1]." ".$fila_consulta_vendedor[$key + 2];
                                                                // }

                                                                if(isSet($flipped_vendedor[$_SESSION["sesion_filtro_vendedor_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vendedor);
                                                                    $texto_filtro = $fila_consulta_vendedor[$key + 1]." ".$fila_consulta_vendedor[$key + 2];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                        <?php
                                                        $filtro_consulta .= " AND cot.id_vend = ".$_SESSION["sesion_filtro_vendedor_panel"];
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
                                                        <h3 class="box-title">Análisis de Ventas</h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
														<div class="row">
						                            		<div class="col-sm-12 table-responsive">
                                                                <?php  
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 4) THEN ven.monto_ven ELSE 0 END),0) AS venta_promesa,
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 6 or cot.id_est_cot = 7) THEN ven.monto_ven ELSE 0 END),0) AS venta_escritura
                                                                    FROM 
                                                                        cotizacion_cotizacion AS cot 
                                                                        INNER JOIN venta_venta AS ven ON ven.cotizacion_ven = cot.id_cot
                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    WHERE
                                                                        cot.id_est_cot IN (1,4,5,6,7)
                                                                        ".$filtro_consulta."
                                                                    ";
                                                                $conexion->consulta($consulta);
                                                                $fila = $conexion->extraer_registro_unico();
                                                                // $monto_cotizacion_promesa = $fila["venta_promesa"];
                                                                // $monto_cotizacion_escritura = $fila["venta_escritura"];
																// caca hay que revisar, porque no están llegnado estos estados.
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 1) THEN 1 ELSE 0 END),0) AS coti_activa,
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 3) THEN 1 ELSE 0 END),0) AS coti_desistimiento,
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 4) THEN 1 ELSE 0 END),0) AS coti_promesa,
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 5) THEN 1 ELSE 0 END),0) AS coti_venta,
                                                                        IFNULL(SUM(CASE WHEN (cot.id_est_cot = 6 or cot.id_est_cot = 7) THEN 1 ELSE 0 END),0) AS coti_escritura
                                                                    FROM 
                                                                        cotizacion_cotizacion AS cot 
                                                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    WHERE
                                                                        cot.id_est_cot IN (1,3,4,5,6,7)
                                                                        ".$filtro_consulta."
                                                                    ";
                                                                // echo $consulta;
                                                                $conexion->consulta($consulta);
                                                                $fila = $conexion->extraer_registro_unico();
                                                                $cantidad_cotizacion_activa = $fila["coti_activa"];
                                                                $cantidad_cotizacion_desistimiento = $fila["coti_desistimiento"];
                                                                $cantidad_cotizacion_promesa = $fila["coti_promesa"];
                                                                $cantidad_cotizacion_venta = $fila["coti_venta"];
                                                                $cantidad_cotizacion_escritura = $fila["coti_escritura"];

                                                                $cantidad_cotizacion = $cantidad_cotizacion_activa + $cantidad_cotizacion_desistimiento + $cantidad_cotizacion_promesa + $cantidad_cotizacion_venta + $cantidad_cotizacion_escritura;


                 //                                                $consulta_cotizacion_promesa = 
																 //  "
																 //  SELECT 
																 //    IFNULL(SUM(ven.monto_ven),0) AS suma,
			              //                                           IFNULL(COUNT(ven.cotizacion_ven),0) AS cantidad
																 //  FROM
																 //    venta_venta as ven,
																 //    cotizacion_cotizacion AS cot,
																 //    vivienda_vivienda as viv,
																 //    torre_torre as tor
																 //  WHERE
																 //    (ven.id_est_ven = 4 or ven.id_est_ven = 5) AND 
																	// ven.id_viv = viv.id_viv AND
																 //    viv.id_tor = tor.id_tor AND
																 //    ven.cotizacion_ven = cot.id_cot AND
																 //    NOT EXISTS(
																 //        SELECT 
																 //            ven_eta.id_ven
																 //        FROM
																 //            venta_etapa_venta AS ven_eta
																 //        WHERE
																 //            ven_eta.id_ven = ven.id_ven
																 //    )
																 //   ".$filtro_consulta."
																 //  ";
																// cotizaciones que pasaron a venta
																$consulta_cotizacion_promesa = 
																  "
																  SELECT 
																    IFNULL(SUM(ven.monto_ven),0) AS suma,
			                                                        IFNULL(COUNT(ven.cotizacion_ven),0) AS cantidad
																  FROM
																    venta_venta as ven,
																    cotizacion_cotizacion AS cot,
																    vivienda_vivienda as viv,
																    torre_torre as tor
																  WHERE
																    cot.id_est_cot > 3 AND 
																	ven.id_viv = viv.id_viv AND
																    viv.id_tor = tor.id_tor AND
																    ven.cotizacion_ven = cot.id_cot
																   ".$filtro_consulta."
																  ";
																$conexion->consulta($consulta_cotizacion_promesa);
			                                                    $fila_prom = $conexion->extraer_registro_unico();
			                                                    $monto_cotizacion_promesa = $fila_prom["suma"];
			                                                    $cantidad_cotizacion_promesa = $fila_prom["cantidad"];


			                                                    $consulta_cotizacion_escrituracion = 
																  "
																  SELECT 
																    IFNULL(SUM(ven.monto_ven),0) AS suma,
			                                                        IFNULL(COUNT(ven.cotizacion_ven),0) AS cantidad
																  FROM
																    venta_venta as ven,
																    cotizacion_cotizacion AS cot,
																    vivienda_vivienda as viv,
																    torre_torre as tor
																  WHERE
																    (ven.id_est_ven = 4 or ven.id_est_ven = 5 or ven.id_est_ven = 6 or ven.id_est_ven = 7) AND 
																	ven.id_viv = viv.id_viv AND
																    viv.id_tor = tor.id_tor AND
																    ven.cotizacion_ven = cot.id_cot AND EXISTS(
																        SELECT 
																            ven_eta.id_ven
																        FROM
																            venta_etapa_venta AS ven_eta
																        WHERE
																            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
																    )
																   ".$filtro_consulta."
																  ";
																$conexion->consulta($consulta_cotizacion_escrituracion);
			                                                    $fila_escri = $conexion->extraer_registro_unico();
			                                                    $monto_cotizacion_escri = $fila_escri["suma"];
			                                                    $cantidad_cotizacion_escri = $fila_escri["cantidad"];

                                                                if($cantidad_cotizacion > 0){
                                                                    $porcentaje_promesa = ($cantidad_cotizacion_promesa * 100) / $cantidad_cotizacion;
                                                                    $porcentaje_escritura = ($cantidad_cotizacion_escri * 100) / $cantidad_cotizacion;
                                                                }
                                                                else{
                                                                    $porcentaje_promesa = 0;
                                                                    $porcentaje_escritura = 0;
                                                                }
                                                                

                                                                ?>
						                            			<table class="table table-bordered">
						                            				<tr>
						                            					<td style="width: 30%">
						                            						<table class="table" style="text-align: center">
									                            				<tr class="bg-navy color-palette">
									                            					<td>Cotizaciones</td>
									                            				</tr>
									                            				<tr>
									                            					<td>cantidad
									                            						<h2 class="mt-0"><?php echo number_format($cantidad_cotizacion, 0, ',', '.');?></h2>
									                            						<h2 class="mt-0">&nbsp;</h2>
									                            					</td>
									                            				</tr>
									                            				<tr class="bg-aqua color-palette">
									                            					<td>
									                            						canales
									                            					</td>
									                            				</tr>
									                            				<tr>
									                            					<td>
                                                                                        <?php  
                                                                                        $consulta = 
                                                                                            "
                                                                                            SELECT 
                                                                                                can_cot.nombre_can_cot,
                                                                                                IFNULL(COUNT(cot.id_cot),0) AS cantidad
                                                                                            FROM 
                                                                                                cotizacion_cotizacion AS cot 
                                                                                                INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                                                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                                            WHERE
                                                                                                cot.id_est_cot IN (1,3,4,5,6,7)
                                                                                                ".$filtro_consulta."
                                                                                            GROUP BY
                                                                                                can_cot.id_can_cot
                                                                                            ";
                                                                                        $conexion->consulta($consulta);
                                                                                        $fila_consulta = $conexion->extraer_registro();
                                                                                        if(is_array($fila_consulta)){
                                                                                            foreach ($fila_consulta as $fila) {
                                                                                                echo utf8_encode($fila['nombre_can_cot'].": ".number_format($fila['cantidad'], 0, ',', '.')." <br>");
                                                                                            }
                                                                                        }
                                                                                        ?>
									                            						
									                            					</td>
									                            				</tr>
									                            			</table>
						                            					</td>
						                            					<td style="width: 2%; vertical-align: middle;">
						                            						<i class="fa fa-long-arrow-right fa-4x" aria-hidden="true"></i>
						                            					</td>
						                            					<td style="width: 30%">
						                            						<table class="table" style="text-align: center">
									                            				<tr class="bg-navy color-palette">
									                            					<td>Promesas</td>
									                            				</tr>
									                            				<tr>
									                            					<td>cantidad
									                            						<h2 class="mt-0"><?php echo number_format($cantidad_cotizacion_promesa, 0, ',', '.');?> (<?php echo number_format($porcentaje_promesa, 2, ',', '.');?>%)</h2>
									                            						<h2 class="mt-0">UF <?php echo number_format($monto_cotizacion_promesa, 2, ',', '.');?></h2>
									                            					</td>
									                            				</tr>
									                            				<tr class="bg-aqua color-palette">
									                            					<td>
									                            						canales
									                            					</td>
									                            				</tr>
									                            				<tr>
									                            					<td>
									                            						<?php  

																						// canales de promesas
																						$consulta_canales_promesa = 
																						  "
																						  SELECT 
																						  	can_cot.nombre_can_cot,
                                                                                            IFNULL(COUNT(cot.id_cot),0) AS cantidad
																						    -- IFNULL(SUM(ven.monto_ven),0) AS suma,
									                                                        -- IFNULL(COUNT(ven.cotizacion_ven),0) AS cantidad
																						  FROM
																						    venta_venta as ven,
																						    cotizacion_cotizacion AS cot,
																						    cotizacion_canal_cotizacion AS can_cot,
																						    vivienda_vivienda as viv,
																						    torre_torre as tor
																						  WHERE
																						    cot.id_est_cot > 3 AND 
																							ven.id_viv = viv.id_viv AND
																						    viv.id_tor = tor.id_tor AND
																						    ven.cotizacion_ven = cot.id_cot AND
																						    can_cot.id_can_cot = cot.id_can_cot
																						   ".$filtro_consulta." 
																						   GROUP BY
                                                                                                can_cot.id_can_cot
																						  ";

                                                                                        // $consulta = 
                                                                                        //     "
                                                                                        //     SELECT 
                                                                                        //         can_cot.nombre_can_cot,
                                                                                        //         IFNULL(COUNT(cot.id_cot),0) AS cantidad
                                                                                        //     FROM 
                                                                                        //         cotizacion_cotizacion AS cot 
                                                                                        //         INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                                        //         INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                                        //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                                        //     WHERE
                                                                                        //         cot.id_est_cot IN (4)
                                                                                        //         ".$filtro_consulta."
                                                                                        //     GROUP BY
                                                                                        //         can_cot.id_can_cot
                                                                                        //     ";
                                                                                        $conexion->consulta($consulta_canales_promesa);
                                                                                        $fila_consulta = $conexion->extraer_registro();
                                                                                        if(is_array($fila_consulta)){
                                                                                            foreach ($fila_consulta as $fila) {
                                                                                                echo utf8_encode($fila['nombre_can_cot'].": ".number_format($fila['cantidad'], 0, ',', '.')." <br>");
                                                                                            }
                                                                                        }
                                                                                        ?>
									                            					</td>
									                            				</tr>
									                            			</table>
						                            					</td>
						                            					<td style="width: 2%; vertical-align: middle;">
						                            						<i class="fa fa-long-arrow-right fa-4x" aria-hidden="true"></i>
						                            					</td>
						                            					<td style="width: 30%">
						                            						<table class="table" style="text-align: center">
									                            				<tr class="bg-navy color-palette">
									                            					<td>En Proceso Escrituración</td>
									                            				</tr>
									                            				<tr>
									                            					<td>cantidad
									                            						<h2 class="mt-0"><?php echo number_format($cantidad_cotizacion_escri, 0, ',', '.');?> (<?php echo number_format($porcentaje_escritura, 2, ',', '.');?>%)</h2>
									                            						<h2 class="mt-0">UF <?php echo number_format($monto_cotizacion_escri, 2, ',', '.');?></h2>
									                            					</td>
									                            				</tr>
									                            				<!-- <tr class="bg-aqua color-palette">
									                            					<td>
									                            						canales
									                            					</td>
									                            				</tr> -->
									                            				<!-- <tr>
									                            					<td> -->
									                            						<?php  

									                            						// canales de escri
																						// $consulta_canales_escri = 
																						//   "
																						//   SELECT 
																						//   	can_cot.nombre_can_cot,
                      //                                                                       IFNULL(COUNT(cot.id_cot),0) AS cantidad
																						//     -- IFNULL(SUM(ven.monto_ven),0) AS suma,
									             //                                            -- IFNULL(COUNT(ven.cotizacion_ven),0) AS cantidad
																						//   FROM
																						//     venta_venta as ven,
																						//     cotizacion_cotizacion AS cot,
																						//     cotizacion_canal_cotizacion AS can_cot,
																						//     vivienda_vivienda as viv,
																						//     torre_torre as tor
																						//   WHERE
																						//     (ven.id_est_ven = 4 or ven.id_est_ven = 5 or ven.id_est_ven = 6 or ven.id_est_ven = 7) AND 
																						// 	ven.id_viv = viv.id_viv AND
																						//     viv.id_tor = tor.id_tor AND
																						//     ven.cotizacion_ven = cot.id_cot AND
																						//     can_cot.id_can_cot = cot.id_can_cot AND 
																						//     EXISTS(
																						//         SELECT 
																						//             ven_eta.id_ven
																						//         FROM
																						//             venta_etapa_venta AS ven_eta
																						//         WHERE
																						//             ven_eta.id_ven = ven.id_ven
																						//     )
																						//    ".$filtro_consulta." 
																						//    GROUP BY
                      //                                                                           can_cot.id_can_cot
																						//   ";

                                                                                        // $consulta = 
                                                                                        //     "
                                                                                        //     SELECT 
                                                                                        //         can_cot.nombre_can_cot,
                                                                                        //         IFNULL(COUNT(cot.id_cot),0) AS cantidad
                                                                                        //     FROM 
                                                                                        //         cotizacion_cotizacion AS cot 
                                                                                        //         INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                                        //         INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                                        //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                                        //     WHERE
                                                                                        //         cot.id_est_cot IN (6,7)
                                                                                        //         ".$filtro_consulta."
                                                                                        //     GROUP BY
                                                                                        //         can_cot.id_can_cot
                                                                                        //     ";
                                                                                        // $conexion->consulta($consulta_canales_escri);
                                                                                        // $fila_consulta = $conexion->extraer_registro();
                                                                                        // if(is_array($fila_consulta)){
                                                                                        //     foreach ($fila_consulta as $fila) {
                                                                                        //         echo utf8_encode($fila['nombre_can_cot'].": ".number_format($fila['cantidad'], 0, ',', '.')." <br>");
                                                                                        //     }
                                                                                        // }
                                                                                        ?>
<!-- 									                            					</td>
									                            				</tr> -->
									                            			</table>
						                            					</td>
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
            var_vendedor = $('#vendedor').val();
            var_condominio = $('#condominio').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&vendedor="+var_vendedor+"&condominio="+var_condominio,
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
