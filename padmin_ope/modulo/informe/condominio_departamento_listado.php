<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Departamentos - Listado</title>
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
          Departamentos
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Departamentos</li>
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
                                    proceso.opcion_pro = 2 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="condominio_departamento_listado.php">DEPARTAMENTOS</a></li>
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
                                    proceso.opcion_pro = 6 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="condominio_estacionamiento_listado.php">ESTACIONAMIENTOS</a></li>
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
                                    proceso.opcion_pro = 7 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="condominio_bodega_listado.php">BODEGA</a></li>
                                <?php
                            }
                            // $consulta = 
                            //     "
                            //     SELECT 
                            //         usu.id_mod 
                            //     FROM 
                            //         usuario_usuario_proceso AS usu,
                            //         usuario_proceso AS proceso
                            //     WHERE 
                            //         usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                            //         usu.id_mod = 1 AND
                            //         proceso.opcion_pro = 14 AND
                            //         proceso.id_pro = usu.id_pro AND
                            //         proceso.id_mod = 1
                            //     ";
                            // $conexion->consulta($consulta);
                            // $cantidad_opcion = $conexion->total();
                            // if($cantidad_opcion > 0){
                                ?>
                                <!-- <li><a href="condominio_disponibilidad_listado.php">DISPONIBILIDAD</a></li> -->
                                <?php
                            // }
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
                                                    <div class="col-sm-2 radiomio">
                                                        <div class="radio bg-grays" style="margin-top: 20px; padding: 5px">
                                                            
                                                            <?php  
                                                            $consulta = "SELECT * FROM vivienda_estado_vivienda ORDER BY id_est_viv";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <input id="<?php echo utf8_encode($fila['nombre_est_viv']);?>" type="radio" name="filtro_estado" class="filtro_estado" value="<?php echo $fila['id_est_viv'];?>">
                                                                    <label for="<?php echo utf8_encode($fila['nombre_est_viv']);?>"><?php echo utf8_encode($fila['nombre_est_viv']);?></label>

                                                                <?php 
                                                                }
                                                            }
                                                            ?>

                                                                
                                                        </div> 
                                                    </div>
                                                    <div class="col-sm-5">
                                                        
                                                        <div class="col-sm-4">
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

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="torre">Torre:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="torre" name="torre"> 
                                                                    <option value="">Seleccione Torre</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="modelo">Modelo:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="modelo" name="modelo"> 
                                                                    <option value="">Seleccione Modelo</option>
                                                                    <?php  
                                                                    $consulta = "SELECT * FROM modelo_modelo WHERE id_est_mod = 1 ORDER BY nombre_mod";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                   <div class="col-sm-4">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="cliente">Cliente:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico select2" id="cliente" name="cliente"> 
                                                                    <option value="">Seleccione Cliente</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_pro, nombre_pro, apellido_paterno_pro, apellido_materno_pro FROM propietario_propietario ORDER BY nombre_pro, apellido_paterno_pro, apellido_materno_pro";
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
                                                    
                                                    if(isset($_SESSION["sesion_filtro_estado_panel"])){
                                                        $consulta = "SELECT * FROM vivienda_estado_vivienda WHERE id_est_viv = ?";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_estado_panel"]));
                                                        $fila_consulta_modelo = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_modelo)){
                                                            foreach ($fila_consulta_modelo as $fila) {
                                                                $texto_filtro = $fila['nombre_est_viv'];
                                                                
                                                            }
                                                        }
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND viv.id_est_viv = ".$_SESSION["sesion_filtro_estado_panel"];
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
                                                        $filtro_consulta .= " AND con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }


                                                    if(isset($_SESSION["sesion_filtro_torre_panel"])){
                                                        $consulta = "SELECT * FROM torre_torre WHERE id_tor = ? ORDER BY nombre_tor";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_torre_panel"]));
                                                        $fila_consulta_torre = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_torre)){
                                                            foreach ($fila_consulta_torre as $fila) {
                                                                $texto_filtro = $fila['nombre_tor'];
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND viv.id_tor = ".$_SESSION["sesion_filtro_torre_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_modelo_panel"])){
                                                        $consulta = "SELECT * FROM modelo_modelo WHERE id_mod = ? ORDER BY nombre_mod";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_modelo_panel"]));
                                                        $fila_consulta_modelo = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_modelo)){
                                                            foreach ($fila_consulta_modelo as $fila) {
                                                                $texto_filtro = $fila['nombre_mod'];
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND viv.id_mod = ".$_SESSION["sesion_filtro_modelo_panel"];
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
                                                                //     $texto_filtro = $fila_consulta_propietario[$key + 1]." ".$fila_consulta_propietario[$key + 2]." ".$fila_consulta_propietario[$key + 3];
                                                                // }
                                                                if(isSet($flipped_propietario[$_SESSION["sesion_filtro_propietario_panel"]])){
                                                            		$key = array_search($_SESSION["sesion_filtro_propietario_panel"], $fila_consulta_propietario);
                                                                    $texto_filtro = $fila_consulta_propietario[$key + 1]." ".$fila_consulta_propietario[$key + 2]." ".$fila_consulta_propietario[$key + 3];
                                                            	}
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND pro.id_pro = ".$_SESSION["sesion_filtro_propietario_panel"];
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
                                                        <h3 class="box-title">Listado de Departamentos</h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
                                                        <!-- <table class="table table-striped"> -->
                                                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Condominio</th>
                                                                    <th>Torre</th>
                                                                    <th>Modelo</th>
                                                                    <th>Piso</th>
                                                                    <th>Cliente</th>
                                                                    <th>Depto</th>
                                                                    <th>Orientación</th>
                                                                    <th>Metro Depto</th>
                                                                    <th>Metro Terraza</th>
                                                                    <th>Metro Total</th>
                                                                    <th>Bono Directo</th>
                                                                    <th>Estado</th>
                                                                    <th>Valor</th>
                                                                    
                                                                    
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $acumulado_incorporacion_mat = 0;
                                                                $acumulado_escolaridad_mat = 0;
                                                                $acumulado_matricula_mat = 0;
                                                                
                                                                
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
                                                                        viv.id_viv,
                                                                        viv.nombre_viv,
                                                                        viv.valor_viv,
                                                                        viv.metro_viv,
                                                                        viv.metro_terraza_viv,
                                                                        viv.metro_total_viv,
                                                                        viv.bono_viv,
                                                                        ori_viv.id_ori_viv,
                                                                        ori_viv.nombre_ori_viv,
                                                                        mode.id_mod,
                                                                        mode.nombre_mod,
                                                                        piso.nombre_pis,
                                                                        con.id_con,
                                                                        con.nombre_con,
                                                                        tor.id_tor,
                                                                        tor.nombre_tor,
                                                                        pro.id_pro,
                                                                        pro.nombre_pro,
                                                                        pro.apellido_paterno_pro,
                                                                        pro.apellido_materno_pro,
                                                                        est_viv.id_est_viv,
                                                                        est_viv.nombre_est_viv
                                                                    FROM 
                                                                        vivienda_vivienda AS viv
                                                                        INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
                                                                        INNER JOIN vivienda_estado_vivienda AS est_viv ON est_viv.id_est_viv = viv.id_est_viv
                                                                        INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                        INNER JOIN piso_piso AS piso ON piso.id_pis = viv.id_pis
                                                                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                        LEFT JOIN propietario_vivienda_propietario AS viv_pro ON viv_pro.id_viv = viv.id_viv
                                                                        LEFT JOIN propietario_propietario AS pro ON pro.id_pro = viv_pro.id_pro
                                                                    WHERE 
                                                                        viv.id_viv > 0 
                                                                        ".$filtro_consulta."
                                                                    ORDER BY 
                                                                        viv.id_viv
                                                                    "; 
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                $acumulado_monto = 0;
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
                                                                        $acumulado_monto = $acumulado_monto + $fila['valor_viv'];
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_tor']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_mod']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_pis']); ?></td>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_ori_viv']); ?></td>
                                                                            <td><?php echo number_format($fila['metro_viv'], 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($fila['metro_terraza_viv'], 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($fila['metro_total_viv'], 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($fila['bono_viv'], 0, ',', '.');?></td>
                                                                            
                                                                            <td><?php echo utf8_encode($fila['nombre_est_viv']); ?></td>
                                                                            <td><?php echo number_format($fila['valor_viv'], 0, ',', '.');?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="12"></td>
                                                                    <td>$<?php echo number_format($acumulado_monto, 0, ',', '.'); ?></td>
                                                                </tr> 
                                                            </tfoot>
                                                            
                                                            
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

        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("../cotizacion/procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
            }
        });


        $(document).on( "click","#filtro" , function() {
            var_filtro_estado = $('.filtro_estado:checked').val();
            var_condominio = $('#condominio').val();
            var_torre = $('#torre').val();
            var_modelo = $('#modelo').val();
            var_cliente = $('#cliente').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"filtro_estado="+var_filtro_estado+"&condominio="+var_condominio+"&torre="+var_torre+"&modelo="+var_modelo+"&cliente="+var_cliente,
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
            "pageLength": 1000,
            dom:'lfBrtip',
            // success de tabla
            lengthChange: true,
            // exporta a excel solo las visibles
            buttons: [ 'copy', {
                extend: 'excelHtml5',
                exportOptions: {
                	orthogonal: 'export',
                    columns: ':visible'
                }
            }, 'pdf', 'print', 'colvis' ],
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
                null,
                { "sType": "string" },
                { "sType": "string" },
                { "sType": "string" },
                null,
                null,
                null,
                null,
                { "sType": "string" },
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                // arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //20
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
