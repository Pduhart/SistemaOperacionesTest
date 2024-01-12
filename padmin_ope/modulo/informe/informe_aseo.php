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
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> </div>
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
          Aseo
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Aseo</li>
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
                            
                            <li class="active"><a href="informe_solicitud.php">Aseo</a></li>
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
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="personal">Personal:</label>
                                                        <select class="form-control select2" id="personal" name="personal"> 
                                                            <option value="">Seleccione Personal</option>
                                                            <?php  
                                                            $consulta = "SELECT id_usu,nombre_usu, apellido1_usu, apellido2_usu FROM usuario_usuario WHERE id_est_usu = 1 AND id_per = 4 ORDER BY nombre_usu";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            $fila_consulta_personal_original = $fila_consulta;
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <option value="<?php echo $fila['id_usu'];?>"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']);?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
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
                                                if(isset($_SESSION["id_personal_filtro_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_personal_original));
                                                    $fila_consulta_personal = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_personal[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_personal)){
                                                        foreach ($fila_consulta_personal as $fila) {
                                                            if(in_array($_SESSION["id_personal_filtro_panel"],$fila_consulta_personal)){
                                                                $key = array_search($_SESSION["id_personal_filtro_panel"], $fila_consulta_personal);
                                                                $texto_filtro = $fila_consulta_personal[$key + 1]." ".$fila_consulta_personal[$key + 2]." ".$fila_consulta_personal[$key + 3];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                                                    <?php
                                                    $filtro_consulta .= " AND ase.id_usu = ".$_SESSION["id_personal_filtro_panel"];
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                                                if(isset($_SESSION["sesion_filtro_fecha_desde_panel"])){
                                                    $elimina_filtro = 1;
                                                    $fecha = date("d-m-Y",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                    ?>
                                                    <span class="label label-primary"><?php echo $fecha;?></span> |
                                                    <?php
                                                    if(isset($_SESSION["sesion_filtro_fecha_estado_panel"]) && $_SESSION["sesion_filtro_fecha_estado_panel"] == 1){
                                                        $filtro_consulta .= " AND ase.id_est_ase = 2";
                                                    }
                                                    $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                    $filtro_consulta .= " AND ase.fecha_ase >= '".$fecha."'";
                                                }
                                                else{
                                                    ?>
                                                    <span class="label label-default">Sin filtro</span> | 
                                                    <?php       
                                                }
                        
                        
                                                if(isset($_SESSION["sesion_filtro_fecha_hasta_panel"])){
                                                    $elimina_filtro = 1;
                                                    $fecha = date("d-m-Y",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));
                                                    ?>
                                                    <span class="label label-primary"><?php echo $fecha;?></span> 
                                                    <?php
                                                    if(isset($_SESSION["sesion_filtro_fecha_estado_panel"]) && $_SESSION["sesion_filtro_fecha_estado_panel"] == 1){
                                                        $filtro_consulta .= " AND ase.id_est_ase = 2";
                                                    }
                                                    $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));
                                                    $filtro_consulta .= " AND ase.fecha_ase <= '".$fecha."'";
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
                                                        <th>Condominio</th>
                                                        <th>Torre</th>
                                                        <th>Departamento</th>
                                                        <th>Tipo</th>
                                                        <th>Personal</th>
                                                        <th>Fecha</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php  
                                                    $consulta = 
                                                        "
                                                        SELECT
                                                            ase.id_ase,
                                                            ase.id_usu,
                                                            con.nombre_con,
                                                            tor.nombre_tor,
                                                            viv.nombre_viv,
                                                            tip_ase.nombre_tip_ase,
                                                            usu.nombre_usu,
                                                            usu.apellido1_usu,
                                                            usu.apellido2_usu,
                                                            ase.fecha_ase,
                                                            est_ase.nombre_est_ase,
                                                            ase.id_est_ase
                                                        FROM
                                                            aseo_aseo AS ase
                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ase.id_viv
                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                            INNER JOIN aseo_tipo_aseo AS tip_ase ON tip_ase.id_tip_ase = ase.id_tip_ase
                                                            INNER JOIN aseo_estado_aseo AS est_ase ON est_ase.id_est_ase = ase.id_est_ase
                                                            LEFT JOIN usuario_usuario AS usu ON usu.id_usu = ase.id_usu
                                                        WHERE
                                                            ase.id_ase > 0
                                                            ".$filtro_consulta."
                                                        ";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <tr>
                                                                <!-- <td><?php echo $fila["id_res"];?></td> -->
                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_tip_ase"]);?></td>

                                                                <td><?php echo utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]." ".$fila["apellido2_usu"]);?></td>

                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_ase"]));?></td>
                                                                
                                                                <td><?php echo utf8_encode($fila["nombre_est_ase"]);?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($fila["id_usu"] == 0) {
                                                                        ?>
                                                                        <button data-target="#myModal" style="font-size: 10px;padding: 3px 5px;" value="<?php echo utf8_encode($fila['id_ase']);?>" type="button" class="btn btn-sm btn-icon btn-success personal" data-toggle="tooltip" data-original-title="Asignar Personal"><i class="fa fa-user"></i></button></td>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    
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

        $(document).on( "click",".personal" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_insert_personal.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#myModal').html(data);
                     $('#myModal').modal('show');
                }
            })
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
            var_personal = $('#personal').val();
            var_procedencia = $('#procedencia').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&condominio="+var_condominio+"&torre="+var_torre+"&vivienda="+var_vivienda+"&personal="+var_personal+"&procedencia="+var_procedencia,
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
                // data:"fecha="+var_fecha+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&vehiculo="+var_vehiculo,
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
                null,
                null,
                null,
                null,
                null,
                { "sType": "date-uk" },
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
