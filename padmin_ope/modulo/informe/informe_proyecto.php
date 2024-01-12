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
          Proyecto
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Proyecto</li>
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
                            // $consulta = 
                            //     "
                            //     SELECT 
                            //         usu.id_mod 
                            //     FROM 
                            //         usuario_usuario_proceso AS usu,
                            //         usuario_proceso AS proceso
                            //     WHERE 
                            //         usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                            //         usu.id_mod = 95 AND
                            //         proceso.opcion_pro = 1 AND
                            //         proceso.id_pro = usu.id_pro AND
                            //         proceso.id_mod = 95
                            //     ";
                            // $conexion->consulta($consulta);
                            // $cantidad_opcion = $conexion->total();
                            // if($cantidad_opcion > 0){
                            ?>
                            <li class="active"><a href="informe_solicitud.php">PROYECTOS</a></li>
                            <!-- <li><a href="alumno_nivel.php">POR NIVEL</a></li> -->
                        </ul>
                        <?php  
                        $filtro_unidad = '';
                        $filtro_unidad2 = '';
                        $consulta = 
                          "
                          SELECT
                            id_uni
                          FROM
                            usuario_unidad_usuario
                          WHERE
                            id_usu = ".$_SESSION["sesion_id_panel"]."
                          ";
                        $conexion->consulta($consulta);
                        $cantidad = $conexion->total();
                        $fila_consulta = $conexion->extraer_registro();

                        if(is_array($fila_consulta)){
                          foreach ($fila_consulta as $fila) {
                            $filtro_unidad .= " AND id_uni = ".$fila["id_uni"];
                            $filtro_unidad2 .= " AND pro.id_uni = ".$fila["id_uni"];
                          }
                          
                        }
                        if($cantidad == 0){
                            $filtro_unidad .= " AND id_uni = 0";
                            $filtro_unidad2 .= " AND pro.id_uni = 0";
                        }
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
                                                        <label for="busqueda_proyecto">Buscador Proyecto:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right" name="busqueda_proyecto" id="busqueda_proyecto">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fecha_desde">Fecha Desde:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
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
                                                        <label for="unidad">Unidad:</label>
                                                        <div class="input-group">
                                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="unidad" name="unidad"> 
                                                                <option value="">Seleccione unidad</option>
                                                                <?php  
                                                                if($_SESSION["sesion_perfil_panel"] == 2 || $_SESSION["sesion_perfil_panel"] == 3){
                                                                    $consulta = "SELECT id_uni,nombre_uni,codigo_uni FROM unidad_unidad WHERE id_est_uni = 1 ".$filtro_unidad." ORDER BY id_uni";
                                                                }
                                                                else{
                                                                    $consulta = "SELECT id_uni,nombre_uni,codigo_uni FROM unidad_unidad WHERE id_est_uni = 1 ORDER BY id_uni";
                                                                }
                                                    
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_unidad_original = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_unidad_original)){
                                                                    foreach ($fila_consulta_unidad_original as $fila) {
                                                                        ?>
                                                                        <option value="<?php echo $fila['id_uni'];?>"><?php echo utf8_encode($fila['nombre_uni']." - ".$fila['codigo_uni']);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div id="resultado" class="text-center"></div>
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
                                            <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                              <i>Filtro: 
                                                <?php 
                                                $elimina_filtro = 0;
                                                if(isset($_SESSION["id_proyecto_filtro_panel"])){
                                                    $elimina_filtro = 1;
                                                    $consulta = "SELECT nombre_pro, codigo_pro FROM proyecto_proyecto WHERE id_pro = ".$_SESSION["id_proyecto_filtro_panel"]."";
                                                    $conexion->consulta($consulta);
                                                    $fila = $conexion->extraer_registro_unico();
                                                    $texto_filtro = utf8_encode($fila['nombre_pro']." - ".$fila['codigo_pro']);
                                                    ?>
                                                    <span class="label label-primary"><?php echo $texto_filtro;?></span> | 
                                                    <?php
                                                    $filtro_consulta = " AND pro.id_pro = ".$_SESSION["id_proyecto_filtro_panel"];
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
                                                    $filtro_consulta .= " AND pro.fecha_inicio_pro >= '".$fecha."'";
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
                                                    $filtro_consulta .= " AND pro.fecha_inicio_pro <= '".$fecha."'";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> |
                                                    <?php       
                                                }

                                                if(isset($_SESSION["sesion_filtro_unidad_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_unidad_original));
                                                    $fila_consulta_unidad = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_unidad[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_unidad)){
                                                        foreach ($fila_consulta_unidad as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_unidad_panel"],$fila_consulta_unidad)){
                                                                $key = array_search($_SESSION["sesion_filtro_unidad_panel"], $fila_consulta_unidad);
                                                                $texto_filtro = $fila_consulta_unidad[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> 
                                                    <?php
                                                    $filtro_consulta .= " AND pro.id_uni = ".$_SESSION["sesion_filtro_unidad_panel"];
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
                                        <div class="row contenedor_tabla" id="contenedor_tabla">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Listado de Proyectos</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Cód. Proyecto</th>
                                                                <th>Proyecto</th>
                                                                <th>Cód. Unidad</th>
                                                                <th>Unidad</th>
                                                                <th>Cod. Cuenta</th>
                                                                <th>Cuenta</th>
                                                                <th>Cod. SubCuenta</th>
                                                                <th>SubCuenta</th>
                                                                <th>Presupuesto Original</th>
                                                                <th>Remesas</th>
                                                                <th>Presupuesto Actual</th>
                                                                <th>Aprobada</th>
                                                                <th>Emitada</th>
                                                                <th>Rechazada</th>
                                                                <th>Sol. Información</th>
                                                                <th>En elaboración</th>
                                                                <th>Estado</th>
                                                            </tr>    
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    id_pro,
                                                                    IFNULL(SUM(
                                                                        CASE
                                                                        WHEN (id_est_sol = 1) THEN
                                                                            monto_sol
                                                                        ELSE
                                                                            0
                                                                        END
                                                                    ),0) primer,
                                                                    IFNULL(SUM(
                                                                        CASE
                                                                        WHEN (id_est_sol = 2) THEN
                                                                            monto_sol
                                                                        ELSE
                                                                            0
                                                                        END
                                                                    ),0) segundo,
                                                                    IFNULL(SUM(
                                                                        CASE
                                                                        WHEN (id_est_sol = 3) THEN
                                                                            monto_sol
                                                                        ELSE
                                                                            0
                                                                        END
                                                                    ),0) tercero,
                                                                    IFNULL(SUM(
                                                                        CASE
                                                                        WHEN (id_est_sol = 4) THEN
                                                                            monto_sol
                                                                        ELSE
                                                                            0
                                                                        END
                                                                    ),0) cuarto,
                                                                    IFNULL(SUM(
                                                                        CASE
                                                                        WHEN (id_est_sol = 5) THEN
                                                                            monto_sol
                                                                        ELSE
                                                                            0
                                                                        END
                                                                    ),0) quinto
                                                                FROM 
                                                                    solicitud_solicitud
                                                                GROUP BY 
                                                                    id_pro
                                                                ";
                                                            $conexion->consulta($consulta);
                                                            $total_consulta_estado = $conexion->total();
                                                            $fila_consulta_estado = $conexion->extraer_registro();


                                                            
                                                            
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    pro.id_pro,
                                                                    pro.codigo_pro,
                                                                    pro.nombre_pro,
                                                                    pro.monto_original_pro,
                                                                    pro.monto_remesa_pro,
                                                                    pro.monto_actual_pro,
                                                                    pro.id_est_pro,
                                                                    uni.codigo_uni,
                                                                    uni.nombre_uni,
                                                                    cue.codigo_cue,
                                                                    cue.nombre_cue,
                                                                    sub.codigo_sub,
                                                                    sub.nombre_sub,
                                                                    est_pro.nombre_est_pro
                                                                FROM 
                                                                    proyecto_proyecto AS pro,
                                                                    unidad_unidad AS uni,
                                                                    proyecto_estado_proyecto AS est_pro,
                                                                    cuenta_cuenta AS cue,
                                                                    subcuenta_subcuenta AS sub
                                                                WHERE 
                                                                    uni.id_uni = pro.id_uni AND
                                                                    est_pro.id_est_pro = pro.id_est_pro AND
                                                                    cue.id_cue = pro.id_cue AND
                                                                    sub.id_sub = pro.id_sub 
                                                                    ".$filtro_consulta."
                                                                    ".$filtro_unidad2."
                                                                ORDER BY 
                                                                    pro.codigo_pro
                                                                "; 
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            $acumulado_estado1 = 0;
                                                            $acumulado_estado2 = 0;
                                                            $acumulado_estado3 = 0;
                                                            $acumulado_estado4 = 0;
                                                            $acumulado_estado5 = 0;
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    $acumulado_original = $acumulado_original + $fila['monto_original_pro'];
                                                                    $acumulado_remesa = $acumulado_remesa + $fila['monto_remesa_pro'];
                                                                    $acumulado_actual = $acumulado_actual + $fila['monto_actual_pro'];
                                                                    $monto_estado1 = 0;
                                                                    $monto_estado2 = 0;
                                                                    $monto_estado3 = 0;
                                                                    $monto_estado4 = 0;
                                                                    $monto_estado5 = 0;
                                                                    if(is_array($fila_consulta_estado)){
                                                                        foreach ($fila_consulta_estado as $fila_estado) {
                                                                            if($fila_estado["id_pro"] == $fila["id_pro"]){
                                                                                $monto_estado1 = $fila_estado["primer"];
                                                                                $monto_estado2 = $fila_estado["segundo"];
                                                                                $monto_estado3 = $fila_estado["tercero"];
                                                                                $monto_estado4 = $fila_estado["cuarto"];
                                                                                $monto_estado5 = $fila_estado["quinto"];
                                                                                $acumulado_estado1 = $acumulado_estado1 +  $fila_estado["primer"];
                                                                                $acumulado_estado2 = $acumulado_estado2 +  $fila_estado["segundo"];
                                                                                $acumulado_estado3 = $acumulado_estado3 +  $fila_estado["tercero"];
                                                                                $acumulado_estado4 = $acumulado_estado4 +  $fila_estado["cuarto"];
                                                                                $acumulado_estado5 = $acumulado_estado5 +  $fila_estado["quinto"];
                                                                            }

                                                                        }
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $fila['codigo_pro']; ?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_pro']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['codigo_uni']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_uni']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['codigo_cue']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_cue']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['codigo_sub']); ?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_sub']); ?></td>
                                                                        <td><?php echo number_format($fila['monto_original_pro'], 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($fila['monto_remesa_pro'], 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($fila['monto_actual_pro'], 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_estado1, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_estado2, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_estado3, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_estado4, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_estado5, 0, ',', '.');?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_est_pro']); ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>   
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="8" style="text-align: right;">TOTALES</td>
                                                                <td>$<?php echo number_format($acumulado_original, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_remesa, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_actual, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_estado1, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_estado2, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_estado3, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_estado4, 0, ',', '.'); ?></td>
                                                                <td>$<?php echo number_format($acumulado_estado5, 0, ',', '.'); ?></td>
                                                                <td></td>
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

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&unidad="+var_unidad,
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
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&unidad="+var_unidad+"&proyecto="+valor,
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
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&unidad="+var_unidad+"&proveedor="+valor,
                success: function(data) {
                    location.reload();
                }       
            })
        });
        
    });
</script>
</body>
</html>
