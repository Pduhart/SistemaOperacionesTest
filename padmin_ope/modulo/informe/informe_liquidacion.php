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
            <li class="active">informe Liquidaciones</li>
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
                            <li class="active"><a href="informe_solicitud.php">Liquidaciones</a></li>
                            <!-- <li><a href="alumno_nivel.php">POR NIVEL</a></li> -->
                        </ul>
                        <?php  
                        $consulta = "SELECT id_res FROM reserva_reserva ORDER BY id_res";
                        $conexion->consulta($consulta);
                        $fila_consulta = $conexion->extraer_registro();
                        $fila_consulta_condominio_original = $fila_consulta;
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
                                                        <label for="condominio">Desde Reserva N°:</label>
                                                        <input type="text" name="numero_reserva" id="numero_reserva" class="form-control numero">
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
                                                $filtro_consulta = "";
                                                $elimina_filtro = 0;
                                                if(isset($_SESSION["sesion_filtro_numero_reserva_panel"])){
                                                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                                                    $fila_consulta_condominio = array();
                                                    foreach($it as $v) {
                                                        $fila_consulta_condominio[]=$v;
                                                    }
                                                    $elimina_filtro = 1;
                                                    
                                                    if(is_array($fila_consulta_condominio)){
                                                        foreach ($fila_consulta_condominio as $fila) {
                                                            if(in_array($_SESSION["sesion_filtro_numero_reserva_panel"],$fila_consulta_condominio)){
                                                                $key = array_search($_SESSION["sesion_filtro_numero_reserva_panel"], $fila_consulta_condominio);
                                                                $texto_filtro = $fila_consulta_condominio[$key];
                                                                
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                    <?php
                                                    $filtro_consulta .= " AND res.id_res >= ".$_SESSION["sesion_filtro_numero_reserva_panel"];
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
                                                    <h3 class="box-title">Liquidaciones</h3>
                                                    <div class="box-tools">
                                                        <a href="informe_liquidacion_exp.php" class="btn btn-xs btn-primary">Exportar Excel</a>
                                                    </div>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body no-padding">
                                                    <table class="table table-condensed table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>N°</th>
                                                                <th>Condominio</th>
                                                                <!-- <th>Torre</th> -->
                                                                <!-- <th>Modelo</th> -->
                                                                <th>Depto.</th>
                                                                <th>Propietario</th>
                                                                <th>Programa</th>
                                                                <th>Check In</th>
                                                                <th>Check Out</th>
                                                                <th>Cant. Noche</th>
                                                                <th>Cant. Pasaj.</th>
                                                                <th>Temporada</th>
                                                                <th>Ingresos Total</th>
                                                                <th>Comisión Adm.</th>
                                                                <?php
                                                                $consulta = "SELECT id_int_ser, nombre_int_ser FROM servicio_interno_servicio WHERE id_est_int_ser = 1 AND id_int_ser <> 5 ORDER BY nombre_int_ser";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_servicio = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_servicio)){
                                                                    foreach ($fila_consulta_servicio as $fila_servicio) {
                                                                        ?>
                                                                        <th><?php echo utf8_encode($fila_servicio['nombre_int_ser']);?></th>
                                                                        <?php
                                                                    }
                                                                }  
                                                                ?>
                                                                <th>Extras</th>
                                                                <th>Monto Liquidado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $consulta = 
                                                                "
                                                                SELECT
                                                                    res.programa_base_res,
                                                                    res.id_res,
                                                                    con.nombre_con,
                                                                    tor.nombre_tor,
                                                                    mode.nombre_mod,
                                                                    viv.nombre_viv,
                                                                    prog.nombre_prog,
                                                                    res.fecha_desde_res,
                                                                    res.fecha_hasta_res,
                                                                    res.cantidad_dia_res,
                                                                    res.cantidad_pasajero_res,
                                                                    tip_res.nombre_tip_res,
                                                                    res.monto_interno_res,
                                                                    res.monto_comision_res,
                                                                    res.monto_comision_base_res,
                                                                    res.monto_dia_res,
                                                                    res.monto_dia_base_res,
                                                                    res.monto_adicional_res,
                                                                    res.nombre_propietario_res,
                                                                    res.apellido_paterno_propietario_res,
                                                                    res.apellido_materno_propietario_res,
                                                                    est_res.nombre_est_res,
                                                                    res.id_est_res
                                                                FROM
                                                                    reserva_reserva AS res
                                                                    INNER JOIN reserva_tipo_reserva AS tip_res ON tip_res.id_tip_res = res.id_tip_res
                                                                    INNER JOIN reserva_estado_reserva AS est_res ON est_res.id_est_res = res.id_est_res
                                                                    INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
                                                                    INNER JOIN programa_programa AS prog ON prog.id_prog = res.id_prog
                                                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                    INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                WHERE
                                                                    res.id_est_res = 1
                                                                    ".$filtro_consulta."
                                                                ORDER BY 
                                                                    res.id_res
                                                                DESC
                                                                ";

                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();

                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    $programa_base = $fila["programa_base_res"];
                                                                    $cantidad_dia_res = $fila["cantidad_dia_res"];
                                                                    $cantidad_pasajero_res = $fila["cantidad_pasajero_res"];

                                                                    if($programa_base == 1){
                                                                        $monto_total_res = $fila["monto_total_base_res"];
                                                                        $monto_dia_res = $fila["monto_dia_base_res"];
                                                                        $monto_comision_res = $fila["monto_comision_base_res"];
                                                                        
                                                                    }   
                                                                    else{
                                                                        $monto_total_res = $fila["monto_total_res"];
                                                                        $monto_dia_res = $fila["monto_dia_res"];
                                                                        $monto_comision_res = $fila["monto_comision_res"];
                                                                    }

                                                                    $monto_total_res =   ($monto_dia_res * $cantidad_dia_res);

                                                                    

                                                                    if($fila["fecha_desde_res"] != "0000-00-00"){
                                                                        $fecha_desde = date("d/m/Y",strtotime($fila["fecha_desde_res"]));
                                                                    }
                                                                    else{
                                                                        $fecha_desde = "";
                                                                    }
                                                                    
                                                                
                                                                    if($fila["fecha_hasta_res"] != "0000-00-00"){
                                                                        $fecha_hasta = date("d/m/Y",strtotime($fila["fecha_hasta_res"]));
                                                                    }
                                                                    else{
                                                                        $fecha_hasta = "";
                                                                    }


                                                                    if($fila["programa_base_res"] == 2){
                                                                        $monto = ($fila["monto_dia_res"] * $fila["cantidad_dia_res"]) - $fila["monto_comision_res"] - $fila["monto_interno_res"];
                                                                    }
                                                                
                                                                    if($fila["programa_base_res"] == 1){
                                                                        $monto = ($fila["monto_dia_base_res"] * $fila["cantidad_dia_res"]) - $fila["monto_comision_base_res"] - $fila["monto_interno_res"];
                                                                    }
                                                                    
                                                                    
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo utf8_encode($fila['id_res']);?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_con']);?></td>
                                                                        <!--<td><?php //echo utf8_encode($fila['nombre_tor']);?></td>
                                                                        <td><?php //echo utf8_encode($fila['nombre_mod']);?></td> -->
                                                                        <td><?php echo utf8_encode($fila['nombre_viv']);?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_propietario_res']." ".$fila['apellido_paterno_propietario_res']." ".$fila['apellido_materno_propietario_res']);?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_prog']);?></td>
                                                                        <td><?php echo utf8_encode($fecha_desde);?></td>
                                                                        <td><?php echo utf8_encode($fecha_hasta);?></td>
                                                                        <td><?php echo utf8_encode($fila['cantidad_dia_res']);?></td>
                                                                        <td><?php echo utf8_encode($fila['cantidad_pasajero_res']);?></td>
                                                                        <td><?php echo utf8_encode($fila['nombre_tip_res']);?></td>

                                                                        <td><?php echo number_format($monto_total_res, 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto_comision_res, 0, ',', '.');?></td>
                                                                        
                                                                        <?php
                                                                        
                                                                        if(is_array($fila_consulta_servicio)){
                                                                            foreach ($fila_consulta_servicio as $fila_servicio) {

                                                                                $consulta = 
                                                                                    "
                                                                                    SELECT 
                                                                                        int_res.id_ser_int_res,
                                                                                        int_res.nombre_ser_int_res,
                                                                                        int_res.valor_ser_int_res,
                                                                                        tip_cob.id_tip_cob,
                                                                                        tip_cob.nombre_tip_cob
                                                                                    FROM
                                                                                        reserva_servicio_interno_reserva AS int_res,
                                                                                        cobro_tipo_cobro AS tip_cob
                                                                                    WHERE 
                                                                                        int_res.id_tip_cob = tip_cob.id_tip_cob AND
                                                                                        int_res.id_res = ".$fila["id_res"]." AND
                                                                                        int_res.id_int_ser = ".$fila_servicio["id_int_ser"]."
                                                                                    ORDER BY 
                                                                                        int_res.id_int_ser 
                                                                                    ASC
                                                                                    ";
                                                                                $conexion->consulta($consulta);
                                                                                $fila_consulta_servicio_reserva = $conexion->extraer_registro();
                                                                                if(is_array($fila_consulta_servicio_reserva)){
                                                                                    foreach ($fila_consulta_servicio_reserva as $fila_servicio_reserva) {
                                                                                        $valor = $fila_servicio_reserva["valor_ser_int_res"];
                                                                                        
                                                                                        switch ($fila_servicio_reserva["id_tip_cob"]) {
                                                                                            case 1:
                                                                                                $cantidad_servicio_dia = '';
                                                                                                $cantidad_servicio_persona = '';
                                                                                                $valor_total = $valor;
                                                                                                break;
                                                                                            case 2:
                                                                                                $cantidad_servicio_dia = $cantidad_dia_res;
                                                                                                $cantidad_servicio_persona = '';
                                                                                                $valor_total = $valor * $cantidad_dia_res;
                                                                                                break;
                                                                                            case 3:
                                                                                                $cantidad_servicio_dia = '';
                                                                                                $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                                                                $valor_total = $valor * $cantidad_pasajero_res;
                                                                                                break;
                                                                                            case 4:
                                                                                                $cantidad_servicio_dia = $cantidad_dia_res;
                                                                                                $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                                                                $valor_total = ($valor * $cantidad_pasajero_res) * $cantidad_dia_res;
                                                                                                break;
                                                                                        }
                                                                                        $valor_total_acumulado = $valor_total_acumulado + $valor_total;
                                                                                        
                                                                                        ?>
                                                                                        <td>$ <?php echo number_format($valor_total, 0, '', '.');?></td>
                                                                                        <?php
                                                                                     }
                                                                                }
                                                                                else{
                                                                                    ?>
                                                                                    <td></td>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        } 
                                                                        $consulta = 
                                                                            "
                                                                            SELECT 
                                                                                IFNULL(SUM(int_res.valor_ser_int_res),0) AS valor
                                                                            FROM
                                                                                reserva_servicio_interno_reserva AS int_res
                                                                            WHERE 
                                                                                int_res.id_res = ".$fila["id_res"]." AND
                                                                                int_res.extra_ser_int_res = 1 AND  int_res.id_int_ser <> 5
                                                                            ";
                                                                        $conexion->consulta($consulta);
                                                                        $fila_servicio = $conexion->extraer_registro_unico(); 
                                                                        ?>

                                                                        <td><?php echo number_format($fila_servicio["valor"], 0, ',', '.');?></td>
                                                                        <td><?php echo number_format($monto, 0, ',', '.');?></td>

                                                                        
                                                                    </tr>
                                                                    <?php
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
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
    	$('.numero').numeric();
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        // $('#condominio').change(function () {
        //     valor = $(this).val();
        //     $.ajax({
        //         type: 'POST',
        //         url: ("procesa_condominio.php"),
        //         data:"valor="+valor,
        //         success: function(data) {
        //             $('#torre').html(data);
        //         }
        //     })
        // });
        // $('#torre').change(function () {
        //     valor = $(this).val();
        //     $.ajax({
        //         type: 'POST',
        //         url: ("procesa_torre.php"),
        //         data:"valor="+valor,
        //         success: function(data) {
        //             $('#vivienda').html(data);
        //         }
        //     })
        // });

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            // alert("sss");
            var_numero_reserva = $('#numero_reserva').val();
            
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"numero_reserva="+var_numero_reserva,
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
