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
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="busqueda_proyecto">Buscador Proyecto:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right" name="busqueda_proyecto" id="busqueda_proyecto">
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
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <div style="min-height: 240px">
                                                        <div id="grafico_venta" style="width: 95%; margin-top: 0px; margin-left: auto; margin-right: auto; height: 250px"></div>
                                                    </div>
                                                    <!-- graficos highchart -->
                                                    <script src="http://code.highcharts.com/highcharts.js"></script>
                                                    <script src="http://code.highcharts.com/modules/exporting.js"></script>
                                                    <script>
                                                        $(function () {
                                                            Highcharts.setOptions({
                                                                lang: {
                                                                    decimalPoint: ',',
                                                                    thousandsSep: '.'
                                                                },
                                                                credits: {
                                                                    enabled: false
                                                                }
                                                            });
                                                            $('#grafico_venta').highcharts({
                                                                // chart: {
                                                                //     type: 'column'
                                                                // },
                                                                title: {
                                                                    text: 'Ingresos Mensuales'
                                                                },
                                                                // subtitle: {
                                                                //     text: 'Source: WorldClimate.com'
                                                                // },
                                                                xAxis: {
                                                                    categories: [
                                                                        'Ene',
                                                                        'Feb',
                                                                        'Mar',
                                                                        'Abr',
                                                                        'May',
                                                                        'Jun',
                                                                        'Jul',
                                                                        'Ago',
                                                                        'Sep',
                                                                        'Oct',
                                                                        'Nov',
                                                                        'Dic'
                                                                    ],
                                                                    crosshair: true
                                                                },
                                                                yAxis: {
                                                                    min: 0,
                                                                    tickPixelInterval: 50,
                                                                    title: {
                                                                        text: 'Pesos'
                                                                    },
                                                                    labels: {
                                                                            format: '{value:.,0f}'
                                                                        }
                                                                },
                                                                tooltip: {
                                                                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                                                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                                                        '<td style="padding:0"><b>$ {point.y:,.0f}</b></td></tr>',
                                                                    footerFormat: '</table>',
                                                                    shared: true,
                                                                    useHTML: true
                                                                },
                                                                series: [{
                                                                    name: 'Aprobada',
                                                                    data: [
                                                                        <?php
                                                                        //$fecha_venta = "01-01-".date("Y");
                                                                        for($i=1;$i<=12;$i++){
                                                                            $fecha_mes = date("m",strtotime($fecha_venta));
                                                                            //-------   APROBADAS
                                                                            if($_SESSION["sesion_perfil_panel"] == 2 || $_SESSION["sesion_perfil_panel"] == 3){
                                                                              $consulta = 
                                                                                "
                                                                                SELECT
                                                                                  IFNULL(SUM(monto_sol),0) AS total_suma
                                                                                FROM
                                                                                  solicitud_solicitud
                                                                                WHERE
                                                                                  MONTH(fecha_sol) = '".$i."' AND
                                                                                  (id_est_sol = 1)
                                                                                  ".$filtro_unidad." ".$filtro_consulta."
                                                                                ";
                                                                            }
                                                                            else{
                                                                              $consulta = 
                                                                                "
                                                                                SELECT
                                                                                  IFNULL(SUM(monto_sol),0) AS total_suma
                                                                                FROM
                                                                                  solicitud_solicitud
                                                                                WHERE
                                                                                  MONTH(fecha_sol) = '".$i."' AND
                                                                                  (id_est_sol = 1)
                                                                                ";
                                                                            }
                                                                            

                                                                            $conexion->consulta($consulta);
                                                                            $fila = $conexion->extraer_registro_unico();
                                                                            $total_mes_grafico = utf8_encode($fila["total_suma"]);
                                                                            // $total_mes_grafico = 20;
                                                                            if($i < 12){
                                                                              echo $total_mes_grafico.","; 
                                                                              //echo "200,";
                                                                            }
                                                                            else{
                                                                              echo $total_mes_grafico;
                                                                              //echo "100";
                                                                            }
                                                                        }
                                                                       
                                                                        ?>
                                                                    ]
                                                                },
                                                                {name: 'Rechazadas',
                                                                    data: [
                                                                        <?php
                                                                        //$fecha_venta = "01-01-".date("Y");
                                                                        for($i=1;$i<=12;$i++){
                                                                            //-------   Rechazada
                                                                            if($_SESSION["sesion_perfil_panel"] == 2 || $_SESSION["sesion_perfil_panel"] == 3){
                                                                              $consulta = 
                                                                                "
                                                                                SELECT
                                                                                    IFNULL(SUM(monto_sol),0) AS total_suma
                                                                                FROM
                                                                                    solicitud_solicitud
                                                                                WHERE
                                                                                    MONTH(fecha_sol) = '".$i."' AND
                                                                                    id_est_sol = 3
                                                                                    ".$filtro_unidad." ".$filtro_consulta."
                                                                                ";
                                                                            }
                                                                            else{
                                                                              $consulta = 
                                                                                "
                                                                                SELECT
                                                                                    IFNULL(SUM(monto_sol),0) AS total_suma
                                                                                FROM
                                                                                    solicitud_solicitud
                                                                                WHERE
                                                                                    MONTH(fecha_sol) = '".$i."' AND
                                                                                    id_est_sol = 3
                                                                                ";
                                                                            }
                                                                            $conexion->consulta($consulta);
                                                                            $fila = $conexion->extraer_registro_unico();
                                                                            $total_mes_grafico = utf8_encode($fila["total_suma"]);
                                                                            // $total_mes_grafico = 10;
                                                                            if($i < 12){
                                                                              echo $total_mes_grafico.","; 
                                                                            }
                                                                            else{
                                                                              echo $total_mes_grafico;
                                                                              
                                                                            }
                                                                            //$fecha_venta = date('Y-m-d',strtotime ('+1 month' , strtotime ( $fecha_venta)));    
                                                                        }
                                                                       
                                                                        ?>
                                                                    
                                                                    ]
                                                                }
                                                                ]
                                                            });
                                                        });
                                                    </script>    
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
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"unidad="+var_unidad,
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
            var_unidad = $('#unidad').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"unidad="+var_unidad+"&proyecto="+valor,
                success: function(data) {
                    location.reload();
                }       
            })
        });
        
    });
</script>
</body>
</html>
