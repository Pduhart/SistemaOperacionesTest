<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Comisión - Listado</title>
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
          Comisión
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Comisión</li>
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
                                    proceso.opcion_pro = 5 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="vendedor_comision_listado.php">COMISIONES</a></li>
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
                                    proceso.opcion_pro = 13 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="vendedor_historial_liquidacion.php">LIQUIDACIÓN</a></li>
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
                                proceso.opcion_pro = 5 AND
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
                                                    
                                                    <div class="col-sm-5">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="vendedor">Vendedor:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="vendedor" name="vendedor"> 
                                                                    <option value="">Seleccione Vendedor</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_vend, nombre_vend, apellido_paterno_vend  FROM vendedor_vendedor ORDER BY nombre_vend, apellido_paterno_vend";
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
                                                                <label for="periodo">Periodo:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="periodo" name="periodo"> 
                                                                    <option value="">Seleccione Período</option>
                                                                    <?php  
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            cie.id_cie,
                                                                            mes.nombre_mes,
                                                                            cie.anio_cie
                                                                        FROM 
                                                                            cierre_cierre AS cie
                                                                            INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                                        ORDER BY 
                                                                            cie.anio_cie,
                                                                            mes.id_mes
                                                                        ASC
                                                                        ";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_periodo_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_periodo_original)){
                                                                        foreach ($fila_consulta_periodo_original as $fila) {
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_cie'];?>"><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']);?></option>
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
                                                    

                                                    if(isset($_SESSION["sesion_filtro_vendedor_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vendedor_original));
                                                        $fila_consulta_vendedor = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_vendedor[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_vendedor)){
                                                            foreach ($fila_consulta_vendedor as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_vendedor_panel"],$fila_consulta_vendedor)){
                                                                    $key = array_search($_SESSION["sesion_filtro_vendedor_panel"], $fila_consulta_vendedor);
                                                                    $texto_filtro = $fila_consulta_vendedor[$key + 1];
                                                                    $texto_filtro2 = $fila_consulta_vendedor[$key + 2];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro." ".$texto_filtro2);?></span> |  
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_vend = ".$_SESSION["sesion_filtro_vendedor_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> | 
                                                        <?php       
                                                    }
                                                    
                                                    if(isset($_SESSION["sesion_filtro_periodo_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_periodo_original));
                                                        $fila_consulta_periodo = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_periodo[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_periodo)){
                                                            foreach ($fila_consulta_periodo as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_periodo_panel"],$fila_consulta_periodo)){
                                                                    $key = array_search($_SESSION["sesion_filtro_periodo_panel"], $fila_consulta_periodo);
                                                                    $texto_filtro = $fila_consulta_periodo[$key + 1];
                                                                    $texto_filtro2 = $fila_consulta_periodo[$key + 2];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro."/".$texto_filtro2);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND cie.id_cie = ".$_SESSION["sesion_filtro_periodo_panel"];
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
                                                        <h3 class="box-title">Listado Comisiones</h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body no-padding">
                                                        <!-- <table class="table table-striped"> -->
                                                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Vendedor</th>
                                                                    <th>Período</th>
                                                                    <th>N° Ventas</th>
                                                                    <th>Bono</th>
                                                                    <th>Comisión</th>
                                                                    <th>Total</th>
                                                                    
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $total_acumulado = 0;
                                                                
                                                                    
                                                                


                                                                $consulta = 
                                                                    "
                                                                    SELECT 
                                                                        cie.id_cie,
                                                                        mes.nombre_mes,
                                                                        cie.anio_cie,
                                                                        vend.nombre_vend,
                                                                        vend.apellido_paterno_vend,
                                                                        vend.apellido_materno_vend,
                                                                        vend.id_vend,
                                                                        vend.id_usu
                                                                    FROM
                                                                        cierre_cierre AS cie
                                                                        INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
                                                                        INNER JOIN cierre_venta_cierre AS ven_cie ON ven_cie.id_cie = cie.id_cie
                                                                        INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
                                                                        INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                    WHERE
                                                                        cie.id_cie > 0
                                                                        ".$filtro_consulta."
                                                                    GROUP BY
                                                                        vend.id_vend,
                                                                        cie.id_cie,
                                                                        mes.nombre_mes,
                                                                        cie.anio_cie,
                                                                        vend.nombre_vend,
                                                                        vend.apellido_paterno_vend,
                                                                        vend.apellido_materno_vend
                                                                        
                                                                    
                                                                    "; 
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
                                                                        $consulta = 
                                                                            "
                                                                            SELECT
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_monto_comision_ven ELSE 0 END),0) AS promesa_uf,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_monto_comision_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_monto,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_monto_comision_ven ELSE 0 END),0) AS escritura_uf,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_monto_comision_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_monto,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN ven.promesa_bono_precio_ven ELSE 0 END),0) AS promesa_bono_uf,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 4) THEN (ven.promesa_bono_precio_ven * uf.valor_uf) ELSE 0 END),0) AS promesa_bono_monto,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN ven.escritura_bono_precio_ven ELSE 0 END),0) AS escritura_bono_uf,
                                                                                IFNULL(SUM(CASE WHEN (ven_cie.id_est_ven = 6) THEN (ven.escritura_bono_precio_ven * uf.valor_uf) ELSE 0 END),0) AS escritura_bono_monto,
                                                                                COUNT(ven.id_ven) AS cantidad_venta
                                                                            FROM
                                                                                cierre_venta_cierre AS ven_cie
                                                                                INNER JOIN venta_venta AS ven ON ven_cie.id_ven = ven.id_ven
                                                                                INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
                                                                            WHERE
                                                                                ven_cie.id_cie = ".$fila["id_cie"]." AND
                                                                                ven.id_vend = ".$fila["id_vend"]."
                                                                            GROUP BY
                                                                                ven.id_vend
                                                                            "; 
                                                                        $conexion->consulta($consulta);
                                                                        $fila_detalle = $conexion->extraer_registro_unico();
                                                                        $cantidad_venta = $fila_detalle["cantidad_venta"];
                                                                        $promesa_monto = $fila_detalle["promesa_monto"];
                                                                        $escritura_monto = $fila_detalle["escritura_monto"];

                                                                        $promesa_bono_monto = $fila_detalle["promesa_bono_monto"];
                                                                        $escritura_bono_monto = $fila_detalle["escritura_bono_monto"];

                                                                        $total_comision = $promesa_monto + $escritura_monto;
                                                                        

                                                                        $consulta = 
                                                                            "
                                                                            SELECT
                                                                                IFNULL(SUM(bon_cie.monto_bon_cie),0) AS bono_uf,
                                                                                IFNULL(SUM(bon_cie.monto_bon_cie * uf.valor_uf),0) AS bono_monto
                                                                            FROM
                                                                                cierre_bono_cierre AS bon_cie
                                                                                INNER JOIN cierre_cierre AS cie ON cie.id_cie = bon_cie.id_cie
                                                                                INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(cie.fecha_hasta_cie)
                                                                            WHERE
                                                                                bon_cie.id_cie = ".$fila["id_cie"]." AND
                                                                                bon_cie.id_usu = ".$fila["id_usu"]."
                                                                            GROUP BY
                                                                                bon_cie.id_cie,
                                                                                bon_cie.id_usu
                                                                            "; 
                                                                        $conexion->consulta($consulta);
                                                                        $fila_detalle = $conexion->extraer_registro_unico();
                                                                        $monto_bono_uf = $fila_detalle["bono_uf"];
                                                                        
                                                                        $monto_bono = $fila_detalle["bono_monto"] + $promesa_bono_monto + $escritura_bono_monto;

                                                                        $total_monto = 0;
                                                                        if ($fila['fecha_ven'] == '0000-00-00') {
                                                                            $fecha_venta = "";
                                                                        }
                                                                        else{
                                                                            $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
                                                                        }
                                                                        

                                                                        $total_periodo = $total_comision + $monto_bono; 
                                                                        $total_acumulado = $total_acumulado + $total_periodo;
                                                                        ?>
                                                                        <tr>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_mes']."/".$fila['anio_cie']); ?></td>
                                                                            <td><?php echo number_format($cantidad_venta, 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($monto_bono, 0, ',', '.');?></td>
                                                                            
                                                                            <td><?php echo number_format($total_comision, 0, ',', '.');?></td>
                                                                            <td><?php echo number_format($total_periodo, 0, ',', '.');?></td>
                                                                            
                                                                            
                                                                            
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="5"></td>
                                                                    <td>$<?php echo number_format($total_acumulado, 0, ',', '.'); ?></td>
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

        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_periodo = $('#periodo').val();
            var_vendedor = $('#vendedor').val();
            
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"vendedor="+var_vendedor+"&periodo="+var_periodo,
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
