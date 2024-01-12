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
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
<style type="text/css">
    .checkin{
        background-color: #d0eb10;
    }
    .reserva{
        background-color: #27bd1a;
    }
    .bloqueo{
        background-color: #CA1AC4;
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
            <li class="active">informe Visual</li>
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
                            <li class="active"><a href="informe_solicitud.php">RESERVAS</a></li>
                            <!-- <li><a href="alumno_nivel.php">POR NIVEL</a></li> -->
                        </ul>
                        <?php  
                        $consulta = "SELECT id_tor, nombre_tor FROM torre_torre ORDER BY nombre_tor";
                        $conexion->consulta($consulta);
                        $fila_consulta = $conexion->extraer_registro();
                        $fila_consulta_torre_original = $fila_consulta;
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
                                                        <label for="anio">Año:</label>
                                                        <select class="form-control select2" id="anio" name="anio"> 
                                                            <option value="">Seleccione Año</option>
                                                            <?php
                                                            $anio_actual = date("Y") + 1;
                                                            for($inicio=2017;$inicio<=$anio_actual;$inicio++){
                                                                ?>
                                                                <option value="<?php echo $inicio;?>"><?php echo $inicio;?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="mes">Mes:</label>
                                                        <select class="form-control select2" id="mes" name="mes"> 
                                                            <option value="">Seleccione Mes</option>
                                                            <?php  
                                                            $consulta = "SELECT * FROM mes_mes ORDER BY id_mes";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            $fila_consulta_mes_original = $fila_consulta;
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <option value="<?php echo $fila['id_mes'];?>"><?php echo utf8_encode($fila['nombre_mes']);?></option>
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
                                                if(isset($_SESSION["sesion_filtro_anio_panel"])){
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($_SESSION["sesion_filtro_anio_panel"]);?></span> |  
                                                    <?php
                                                    $anio_filtro = $_SESSION["sesion_filtro_anio_panel"];
                                                }
                                                else{
                                                    $_SESSION["sesion_filtro_anio_panel"] = date("Y");
                                                    $anio_filtro = $_SESSION["sesion_filtro_anio_panel"];
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($_SESSION["sesion_filtro_anio_panel"]);?></span> | 
                                                    <?php       
                                                }

                                                if(isset($_SESSION["sesion_filtro_mes_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_mes_original));
                                                    $fila_consulta_mes = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_mes[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_mes)){
                                                        foreach ($fila_consulta_mes as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_mes_panel"],$fila_consulta_mes)){
                                                                $key = array_search($_SESSION["sesion_filtro_mes_panel"], $fila_consulta_mes);
                                                                $texto_filtro = $fila_consulta_mes[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>   
                                                    <?php
                                                    $mes_filtro = $_SESSION["sesion_filtro_mes_panel"];
                                                }
                                                else{
                                                    $_SESSION["sesion_filtro_mes_panel"] = date("n");
                                                    $mes_filtro = $_SESSION["sesion_filtro_mes_panel"];
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_mes_original));
                                                    $fila_consulta_mes = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_mes[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    if(is_array($fila_consulta_mes)){
                                                        foreach ($fila_consulta_mes as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_mes_panel"],$fila_consulta_mes)){
                                                                $key = array_search($_SESSION["sesion_filtro_mes_panel"], $fila_consulta_mes);
                                                                $texto_filtro = $fila_consulta_mes[$key + 1];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
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
                                    // $anio_filtro = 2017;
                                    // $mes_filtro = 7;
                                    
                                    switch ($mes_filtro) {
                                        case 1:
                                            $dia_final_mes = 31;
                                            break;
                                        case 2:
                                            if (($anio_filtro%4==0 && $anio_filtro%100!=0) || $anio_filtro%400==0){
                                                $dia_final_mes = 29;
                                            } 
                                            else { 
                                                $dia_final_mes = 28;
                                            }
                                            break;
                                        case 3:
                                            $dia_final_mes = 31;
                                            break;
                                        case 4:
                                            $dia_final_mes = 30;
                                            break;
                                        case 5:
                                            $dia_final_mes = 31;
                                            break;
                                        case 6:
                                            $dia_final_mes = 30;
                                            break;
                                        case 7:
                                            $dia_final_mes = 31;
                                            break;
                                        case 8:
                                            $dia_final_mes = 31;
                                            break;
                                        case 9:
                                            $dia_final_mes = 30;
                                            break;
                                        case 10:
                                            $dia_final_mes = 31;
                                            break;
                                        case 11:
                                            $dia_final_mes = 30;
                                            break;
                                        case 12:
                                            $dia_final_mes = 31;
                                            break;
                                    }
                                    ?>
                                    <div class="col-md-12">
                                        <div class="row contenedor_tabla" id="contenedor_tabla">
                                            <div class="box">
                                                <div class="box-header">
                                                    <h3 class="box-title">Gráfico de Reservas</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <table class="table table-condensed table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Depto</th>
                                                                <?php  
                                                                for($inicio=1;$inicio<=$dia_final_mes;$inicio++){
                                                                    ?>
                                                                    <th><?php echo $inicio;?></th>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if($mes_filtro < 10){
                                                                $mes_filtro = "0".$mes_filtro;
                                                            }
                                                            $contador_vivienda = 1;
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    id_viv,
                                                                    fecha_desde_res,
                                                                    fecha_hasta_res
                                                                FROM 
                                                                    reserva_reserva
                                                                WHERE
                                                                    (id_est_res = 1 OR id_est_res = 2)
                                                                ";
                                                            
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta_reserva = $conexion->extraer_registro();

                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    id_viv,
                                                                    fecha_desde_fec_viv,
                                                                    fecha_hasta_fec_viv
                                                                FROM 
                                                                    vivienda_fecha_vivienda
                                                                ";
                                                            
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta_fecha = $conexion->extraer_registro();

                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    viv.nombre_viv,
                                                                    viv.id_viv
                                                                FROM 
                                                                    vivienda_vivienda AS viv,
                                                                    torre_torre AS tor,
                                                                    condominio_condominio AS con
                                                                WHERE
                                                                    viv.id_est_viv = 1 AND
                                                                    viv.id_tor = tor.id_tor AND
                                                                    tor.id_con = con.id_con
                                                                    ".$filtro_consulta."
                                                                ORDER BY 
                                                                    viv.nombre_viv
                                                                ASC
                                                                ";
                                                            
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            $cantidad = $conexion->total();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $contador_vivienda;?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_viv']);?></td>
                                                                        <?php  
                                                                        for($inicio=1;$inicio<=$dia_final_mes;$inicio++){
                                                                            $contador_desde_repetido = 0;
                                                                            $contador_hasta_repetido = 0;
                                                                            $contador_reserva = 0;
                                                                            $contador_fecha = 0;
                                                                            if($inicio < 10){
                                                                                $dia = "0".$inicio;
                                                                            }
                                                                            else{
                                                                                $dia = $inicio;
                                                                            }

                                                                            
                                                                            
                                                                            $fecha = $anio_filtro."-".$mes_filtro."-".$dia;
                                                                            if(is_array($fila_consulta_reserva)){
                                                                                foreach ($fila_consulta_reserva as $fila_reserva) {
                                                                                    if($fila_reserva["id_viv"] == $fila["id_viv"] && $fila_reserva["fecha_desde_res"] <= $fecha && $fecha <= $fila_reserva["fecha_hasta_res"]){
                                                                                        $contador_reserva++;

                                                                                        if($fila_reserva["fecha_desde_res"] == $fecha){
                                                                                            $contador_desde_repetido++;
                                                                                        }
                                                                                        if($fila_reserva["fecha_hasta_res"] == $fecha){
                                                                                            $contador_hasta_repetido++;
                                                                                        }
                                                                                        
                                                                                    }
                                                                                }
                                                                            }
                                                                            if($contador_reserva > 0){
                                                                                if($contador_desde_repetido > 0 || $contador_hasta_repetido > 0){
                                                                                    ?>
                                                                                    <td class="checkin"></td>
                                                                                    <?php
                                                                                }
                                                                                else{
                                                                                    ?>
                                                                                    <td class="reserva"></td>
                                                                                    <?php 
                                                                                }
                                                                                
                                                                            }
                                                                            else{
                                                                                // bloqueo de fechas
                                                                                if(is_array($fila_consulta_fecha)){
                                                                                    foreach ($fila_consulta_fecha as $fila_fecha) {
                                                                                        if($fila_fecha["id_viv"] == $fila["id_viv"] && $fila_fecha["fecha_desde_fec_viv"] <= $fecha && $fecha <= $fila_fecha["fecha_hasta_fec_viv"]){
                                                                                            $contador_fecha++;
                                                                                            
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if($contador_fecha > 0){
                                                                                    ?>
                                                                                    <td class="bloqueo"></td>
                                                                                    <?php
                                                                                }
                                                                                else{
                                                                                    ?>
                                                                                    <td></td>
                                                                                    <?php 
                                                                                }
                                                                                
                                                                            }
                                                                            
                                                                            
                                                                            
                                                                        }
                                                                    ?>
                                                                    </tr>
                                                                    <?php
                                                                    $contador_vivienda++;
                                                                }
                                                            }
                                                            ?>
                                                            
                                                        </tbody>
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
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
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
            var_anio = $('#anio').val();
            var_mes = $('#mes').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"anio="+var_anio+"&mes="+var_mes+"&condominio="+var_condominio+"&torre="+var_torre,
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
        
    });
</script>
</body>
</html>
