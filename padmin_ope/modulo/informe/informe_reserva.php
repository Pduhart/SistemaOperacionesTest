<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head.php";
?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
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

.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de;
    border-radius: 0px;
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 34px;
    user-select: none;
    -webkit-user-select: none;
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
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Reservas
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Reservas</li>
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
                            
                            <li class="active"><a href="informe_solicitud.php">RESERVAS</a></li>
                            <!-- <li><a href="alumno_nivel.php">POR NIVEL</a></li> -->
                        </ul>
                        <?php  
                        $consulta = "SELECT id_tor, nombre_tor FROM torre_torre ORDER BY nombre_tor";
                        $conexion->consulta($consulta);
                        $fila_consulta = $conexion->extraer_registro();
                        $fila_consulta_torre_original = $fila_consulta;

                        $consulta = "SELECT id_viv, nombre_viv FROM vivienda_vivienda ORDER BY nombre_viv";
                        $conexion->consulta($consulta);
                        $fila_consulta = $conexion->extraer_registro();
                        $fila_consulta_vivienda_original = $fila_consulta;
                        ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body" style="padding-top: 0">
                                    <div class="row">
                                        <div id="contenedor_opcion"></div>
                                        <div class="col-sm-12 filtros">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="condominio">Condominio:</label>
                                                        <select class="form-control select2" id="condominio" name="condominio"> 
                                                            <option value="">Seleccione Condominio</option>
                                                            <?php  
                                                            $consulta = "SELECT id_con, nombre_con FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            $fila_consulta_condominio_original = $fila_consulta;
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
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
                                                        <select class="form-control select2" id="torre" name="torre"> 
                                                            <option value="">Seleccione Torre</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="vivienda">Vivienda:</label>
                                                        <select class="form-control select2" id="vivienda" name="vivienda"> 
                                                            <option value="">Seleccione Vivienda</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 radiomio">
                                                    <div class="radio bg-grays" style="padding:5px;font-size: 1.2rem">
                                                        <input id="estado1" type="radio" name="estado_reserva" class="filtro_reserva" value="1">
                                                        <label for="estado1">Liquidado</label>

                                                        <input id="estado2" type="radio" name="estado_reserva" class="filtro_reserva" value="2">
                                                        <label for="estado2">Con Saldo</label>
                                                            
                                                        <input id="estado3" type="radio" name="estado_reserva" class="filtro_reserva" value="3">
                                                        <label for="estado3">Sin Saldo</label>
                                                    </div> 
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fecha_desde">Desde:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fecha_hasta">Hasta:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="fecha_hasta" id="fecha_hasta">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="propietario">Cliente:</label>
                                                <select class="form-control select2" id="propietario" name="propietario"> 
                                                    <option value="">Seleccione Cliente</option>
                                                    <?php  
                                                    $consulta = "SELECT id_pro, rut_pro, nombre_pro, nombre2_pro, apellido_paterno_pro, apellido_materno_pro FROM propietario_propietario WHERE id_est_pro = 1 ORDER BY nombre_pro";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    $fila_consulta_propietario_original = $fila_consulta;
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['rut_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['nombre2_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="procedencia">Procedencia:</label>
                                                <select class="form-control" id="procedencia" name="procedencia"> 
                                                    <option value="">Seleccione Procedencia</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM procedencia_procedencia ORDER BY id_proc";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    $fila_consulta_procedencia_original = $fila_consulta;
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_proc'];?>"><?php echo utf8_encode($fila['nombre_proc']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
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
                                            <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                              <i>Filtro: 
                                                <?php 
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
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                    <?php
                                                    $filtro_consulta .= " AND con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                                                if(isset($_SESSION["sesion_filtro_torre_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_torre_original));
                                                    $fila_consulta_torre = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_torre[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_torre)){
                                                        foreach ($fila_consulta_torre as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_torre_panel"],$fila_consulta_torre)){
                                                                $key = array_search($_SESSION["sesion_filtro_torre_panel"], $fila_consulta_torre);
                                                                $texto_filtro = $fila_consulta_torre[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                    <?php
                                                    $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_torre_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                                                if(isset($_SESSION["sesion_filtro_vivienda_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vivienda_original));
                                                    $fila_consulta_vivienda = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_vivienda[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_vivienda)){
                                                        foreach ($fila_consulta_vivienda as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_vivienda_panel"],$fila_consulta_vivienda)){
                                                                $key = array_search($_SESSION["sesion_filtro_vivienda_panel"], $fila_consulta_vivienda);
                                                                $texto_filtro = $fila_consulta_vivienda[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                    <?php
                                                    $filtro_consulta .= " AND viv.id_viv = ".$_SESSION["sesion_filtro_vivienda_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                                                if(isset($_SESSION["sesion_filtro_reserva_panel"])){
                                                    $elimina_filtro = 1;
                                                    if($_SESSION["sesion_filtro_reserva_panel"] == 1){
                                                        $texto_filtro = "Liquidado";
                                                        $filtro_consulta .= " AND res.id_est_res = ".$_SESSION["sesion_filtro_reserva_panel"]." ";
                                                    }
                                                    else if($_SESSION["sesion_filtro_reserva_panel"] == 2){
                                                        $texto_filtro = "Con Saldo";
                                                        $filtro_consulta .= " AND res.id_est_res = ".$_SESSION["sesion_filtro_reserva_panel"]." AND res.id_est_pag_res = 2 ";
                                                    } else{
                                                        $texto_filtro = "Sin Saldo";
                                                        $filtro_consulta .= " AND res.id_est_res = 2 AND res.id_est_pag_res = 1 ";
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo $texto_filtro;?></span> |
                                                    <?php
                                                    
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> |
                                                    <?php       
                                                }
                                                if(isset($_SESSION["sesion_filtro_fecha_desde_panel"])){
                                                    $elimina_filtro = 1;
                                                    ?>
                                                    <span class="label label-primary"><?php echo date("d-m-Y",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));?></span> |
                                                    <?php
                                                    $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                    $filtro_consulta .= " AND res.fecha_desde_res >= '".$fecha."'";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                        
                        
                                                if(isset($_SESSION["sesion_filtro_fecha_hasta_panel"])){
                                                    $elimina_filtro = 1;
                                                    ?>
                                                    <span class="label label-primary"><?php echo date("d-m-Y",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));?></span> |
                                                    <?php
                                                    $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));
                                                    $filtro_consulta .= " AND res.fecha_hasta_res <= '".$fecha."'";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> |
                                                    <?php       
                                                }
                                                if(isset($_SESSION["id_propietario_filtro_panel"])){
                                                    $elimina_filtro = 1;
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_propietario_original));
                                                    $fila_consulta_propietario = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_propietario[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_propietario)){
                                                        foreach ($fila_consulta_propietario as $fila) {
                                                            if(in_array($_SESSION["id_propietario_filtro_panel"],$fila_consulta_propietario)){
                                                                $key = array_search($_SESSION["id_propietario_filtro_panel"], $fila_consulta_propietario);
                                                                $texto_filtro = $fila_consulta_propietario[$key];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo $texto_filtro;?></span> | 
                                                    <?php
                                                    $filtro_consulta = " AND res.rut_propietario_res = '".$_SESSION["id_propietario_filtro_panel"]."' ";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                                                if(isset($_SESSION["id_procedencia_filtro_panel"])){
                                                    $elimina_filtro = 1;
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_procedencia_original));
                                                    $fila_consulta_procedencia = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_procedencia[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_procedencia)){
                                                        foreach ($fila_consulta_procedencia as $fila) {
                                                            if(in_array($_SESSION["id_procedencia_filtro_panel"],$fila_consulta_procedencia)){
                                                                $key = array_search($_SESSION["id_procedencia_filtro_panel"], $fila_consulta_procedencia);
                                                                $texto_filtro = $fila_consulta_procedencia[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo $texto_filtro;?></span> 
                                                    <?php
                                                    $filtro_consulta = " AND res.id_proc = ".$_SESSION["id_procedencia_filtro_panel"];
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
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>N° Res.</th>
                                                        <th>Condominio</th>
                                                        <th>Torre</th>
                                                        <th>Modelo</th>
                                                        <th>Departamento</th>
                                                        <th>Pasajero</th>
                                                        <th>Programa</th>
                                                        <th>Check In</th>
                                                        <th>Check Out</th>
                                                        <th>Cant. Noche</th>
                                                        <th>Cant. Pasaj.</th>
                                                        <th>Temporada</th>
                                                        <th>A liquidar</th>
                                                        <th>Adicional</th>
                                                        <th>Comisión</th>
                                                        <th>Saldo</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $consulta = 
                                                        "
                                                        SELECT
                                                            id_res,
                                                            IFNULL(SUM(monto_res_ing),0) AS suma
                                                        FROM
                                                            ingreso_reserva_ingreso
                                                        GROUP BY
                                                            id_res
                                                        ";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta_ingreso_original = $conexion->extraer_registro();
                                                    $fila_consulta_ingreso = array();
                                                    if(is_array($fila_consulta_ingreso_original)){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_ingreso_original));
                                                        foreach($it as $v) {
                                                            $fila_consulta_ingreso_monto[]=$v;
                                                        }
                                                    }  

                                                    $consulta = 
                                                        "
                                                        SELECT
                                                            res.id_res,
                                                            con.nombre_con,
                                                            tor.nombre_tor,
                                                            mode.nombre_mod,
                                                            viv.nombre_viv,
                                                            prog.nombre_prog,
                                                            res.fecha_desde_res,
                                                            res.fecha_hasta_res,
                                                            res.cantidad_dia_res,
                                                            res.monto_dia_res,
                                                            res.monto_dia_base_res,
                                                            res.cantidad_pasajero_res,
                                                            tip_res.nombre_tip_res,
                                                            res.monto_total_res,
                                                            res.monto_comision_res,
                                                            res.monto_comision_base_res,
                                                            res.monto_interno_res,
                                                            res.monto_adicional_res,
                                                            res.programa_base_res,
                                                            est_res.nombre_est_res,
                                                            res.id_est_res,
                                                            arr.nombre_arr,
                                                            arr.apellido_paterno_arr,
                                                            arr.apellido_materno_arr
                                                        FROM
                                                            reserva_reserva AS res
                                                            INNER JOIN reserva_tipo_reserva AS tip_res ON tip_res.id_tip_res = res.id_tip_res
                                                            INNER JOIN reserva_estado_reserva AS est_res ON est_res.id_est_res = res.id_est_res
                                                            INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
                                                            INNER JOIN programa_programa AS prog ON prog.id_prog = res.id_prog
                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                            INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                            INNER JOIN arrendatario_arrendatario AS arr ON arr.id_arr = res.id_arr
                                                        WHERE
                                                            res.id_res > 0
                                                            ".$filtro_consulta."
                                                        ";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            if($fila["programa_base_res"] == 1){
                                                                $liquidado = (($fila["cantidad_dia_res"] * $fila["monto_dia_base_res"]) - $fila["monto_interno_res"]) - $fila["monto_comision_base_res"];

                                                            }
                                                            else{
                                                                $liquidado = ($fila["cantidad_dia_res"] * $fila["monto_dia_res"]) - $fila["monto_comision_res"];
                                                            }
                                                            
                                                            $monto_total_saldo = $fila["monto_total_res"];
                                                            $monto_saldo = $liquidado;
                                                            if(in_array($fila["id_res"],$fila_consulta_ingreso_monto)){
                                                                $key = array_search($fila["id_res"], $fila_consulta_ingreso_monto);
                                                                $monto_saldo = $fila_consulta_ingreso_monto[$key + 1];
                                                                if($monto_saldo == 0){
                                                                    $monto_saldo = $monto_total_saldo;
                                                                }
                                                                else{
                                                                    $monto_saldo = $monto_total_saldo - $monto_saldo;
                                                                    if($monto_saldo < 0){
                                                                        $monto_saldo = 0;
                                                                    }
                                                                }
                                                            }

                                                            ?>
                                                            <tr>
                                                                <td><?php echo $fila["id_res"];?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_mod"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_arr"]." ".$fila["apellido_paterno_arr"]." ".$fila["apellido_materno_arr"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_prog"]);?></td>
                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_desde_res"]));?></td>
                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_hasta_res"]));?></td>
                                                                <td><?php echo utf8_encode($fila["cantidad_dia_res"]);?></td>
                                                                <td><?php echo utf8_encode($fila["cantidad_pasajero_res"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_tip_res"]);?></td>
                                                                <td><?php echo number_format($liquidado, 0, ',', '.');?></td>
                                                                <td><?php echo number_format($fila["monto_adicional_res"], 0, ',', '.');?></td>
                                                                <td><?php echo number_format($fila["monto_comision_res"], 0, ',', '.');?></td>
                                                                <td><?php echo number_format($monto_saldo, 0, ',', '.');?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_est_res"]);?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    
                                                    
                                                </tfoot>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
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
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>

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

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        $('#condominio').change(function () {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("procesa_condominio.php"),
                data:"valor="+valor,
                success: function(data) {
                    $('#torre').html(data);
                }
            })
        });
        $('#torre').change(function () {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("procesa_torre.php"),
                data:"valor="+valor,
                success: function(data) {
                    $('#vivienda').html(data);
                }
            })
        });

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_condominio = $('#condominio').val();
            var_torre = $('#torre').val();
            var_vivienda = $('#vivienda').val();
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_estado = $('.filtro_reserva:checked').val();
            var_propietario = $('#propietario').val();
            var_procedencia = $('#procedencia').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&condominio="+var_condominio+"&torre="+var_torre+"&vivienda="+var_vivienda+"&propietario="+var_propietario+"&procedencia="+var_procedencia,
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
        //$("#busqueda_proyecto").focus();
        $("#busqueda_proyecto").keyup(function(e){
            $('#resultado').show();               
              //obtenemos el texto introducido en el campo de búsqueda
            var consulta = $("#busqueda_proyecto").val();
            var unidad_id = $("#unidad").val();
                                                                  
            $.ajax({
                type: "POST",
                url: "busca_proyecto.php",
                data: "b="+consulta+"&unidad="+unidad_id,
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

        $("#busqueda_proveedor").keyup(function(e){
            $('#resultado').show();               
              //obtenemos el texto introducido en el campo de búsqueda
            var consulta = $("#busqueda_proveedor").val();
            
                                                                  
            $.ajax({
                type: "POST",
                url: "busca_proveedor.php",
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
        $(document).on( "click",".busqueda_proyecto" , function() {
            //$('#contenedor_opcion').html('<img src="../../imagen/ajax-loader.gif">');
            var valor = $(this).attr( "id" );
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_estado = $('.estado:checked').val();
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&unidad="+var_unidad+"&proyecto="+valor,
                success: function(data) {
                    location.reload();
                }       
            })
        });

        $(document).on( "click",".busqueda_proveedor" , function() {
            //$('#contenedor_opcion').html('<img src="../../imagen/ajax-loader.gif">');
            var valor = $(this).attr( "id" );
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_estado = $('.estado:checked').val();
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&unidad="+var_unidad+"&proveedor="+valor,
                success: function(data) {
                    location.reload();
                }       
            })
        });


        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
            // "bProcessing": true,
            //"bServerSide": true,
            responsive: true,
            // "sAjaxSource": "select_reserva.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 0, "desc" ]],
            "aoColumns": [
                { "bSortable": false },
                null,
                null,
                null,
                null,
                null,
                null,
                { "sType": "date-uk" },
                { "sType": "date-uk" },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { "bSortable": false }
            ]
        } );
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
          } );
        // $.fn.dataTable.moment( 'DD.MM.YYYY' );

        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

    });
</script>
</body>
</html>
