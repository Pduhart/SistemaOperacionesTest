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
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">

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

    .info-box-number2 {
        display: inline-block;
        font-weight: normal;
        font-size: 15px;
        margin-top: 10px;
    }

    .bg-verde{
        background-color: #DCE7BF;
        text-align:center;
    }

    .bg-verdeclaro{
        background-color: #E6EED2;
    }

    .btn-aqui{
        font-weight: bold;
        text-decoration: underline;
        cursor: pointer;
        font-size: 10px;
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
	
	<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>

    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Fondo de Puesta en Marcha
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Fondo de Puesta en Marcha</li>
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
                                    proceso.opcion_pro = 13 AND
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
                                <li class="active"><a href="venta_fondo_listado.php">FONDO PUESTA EN MARCHA</a></li>
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
                                proceso.opcion_pro = 18 AND
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
                                                                <label for="condominio">Condominio:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="condominio" name="condominio"> 
                                                                    <option value="">Seleccione Condominio</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
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
                                                <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
                                                <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                                  <i>Filtro: 
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $filtro_consulta_cierre = '';
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
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= " AND cie.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
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
                                                        <h3 class="box-title">Fondo Puesta en Marcha</h3>
                                                        <div class="box-tools pull-right">
                                                        	<a href="venta_fondo_listado_exc.php" target="_blank" class="btn btn-default btn-sm" data-toggle="tooltip" title="" data-original-title="Exportar Excel"><i class="fa fa-file-excel-o text-green"></i></a>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body">
														<div class="row">
															<?php 
															if (isset($_SESSION["sesion_filtro_condominio_panel"])) {
															// valor a prorratear
															$consultapar = 
														        "
														        SELECT
														            valor_par
														        FROM
														            parametro_parametro
														        WHERE
														            valor2_par = ? AND
														            id_con = ?
														        ";
														    $conexion->consulta_form($consultapar,array(14,$_SESSION["sesion_filtro_condominio_panel"]));
														    $filapar = $conexion->extraer_registro_unico();
														    $porcentaje_prorrateo = utf8_encode($filapar['valor_par']);
															 ?>
															<div class="col-md-12 table-responsive">
																<table class="table table-bordered tabla">
																	<thead>
																		<tr>
																			<th>Cliente</th>
																			<th>RUT</th>
																			<th>Depto.</th>
                                                                            <th>Fecha de Firma de Escritura.</th>
                                                                            <th>Fecha de Entrega.</th>																			
																			<th>Valor FPM</th>
																			<th>Fecha Pago Cliente</th>
																			<th>Monto Pago Cliente</th>
																			
																			<!-- <th style="width: 10%">Fecha Tentativa Pag. Adm. Condominio</th> -->
																			<th>Fecha Pago Adm. Condominio</th>
																			<th>Monto Pagado Adm.</th>
																			
																		</tr>
																	</thead>
																	<tbody>
																		<?php 
																		$acumulado_monto_pago_cliente = 0;
																		$acumulado_monto_pago_adm = 0;
                                                                        $acumulado_total_prorrateo_depto = 0;
									                                    $consulta = "SELECT 
									                                    				viv.nombre_viv,
									                                    				viv.id_viv,
																						viv.prorrateo_viv
									                                    			FROM
									                                    				vivienda_vivienda as viv,
																						torre_torre as tor
																					WHERE
																						viv.id_tor = tor.id_tor AND
																						tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"]."
									                                    			ORDER BY
									                                    				id_pis ASC, nombre_viv ASC";                                                                                                                                           
			                                                            $conexion->consulta($consulta);
			                                                            $fila_consulta = $conexion->extraer_registro();
                                                                        $id_ven='';
			                                                            if(is_array($fila_consulta)){
			                                                                foreach ($fila_consulta as $fila) {
			                                                                	$consultaventa = "SELECT 
									                                    				pro.nombre_pro,
									                                    				pro.nombre2_pro,
									                                    				pro.apellido_paterno_pro,
									                                    				pro.apellido_materno_pro,
									                                    				pro.rut_pro,
									                                    				ven.id_ven,
									                                    				ven.id_for_pag
									                                    			FROM
									                                    				venta_venta as ven,
																						propietario_propietario as pro
																					WHERE
																						ven.id_viv = ".$fila['id_viv']." AND
																						ven.id_est_ven > 3 AND
																						ven.id_pro = pro.id_pro";                                                                                                                                                                      
                                                                                    $conexion->consulta($consultaventa);
                                                                                    $vendido = $conexion->total();                                                                                    
                                                                                    $filaventa = $conexion->extraer_registro_unico();
                                                                                    if ($vendido>0) {
                                                                                        $id_ven = $filaventa['id_ven'];
                                                                                        $id_for_pag = $filaventa['id_for_pag'];
                                                                                        $nombre_propietario = utf8_encode($filaventa['nombre_pro']." ".$filaventa['nombre2_pro']." ".$filaventa['apellido_paterno_pro']." ".$filaventa['apellido_materno_pro']);
                                                                                        $rut_propietario = $filaventa['rut_pro'];
                                                                                        // lo que paga de prorrateo
                                                                                        $total_prorrateo_depto = ($fila['prorrateo_viv'] * $porcentaje_prorrateo) / 100;
                                                                                        $total_prorrateo_depto = $total_prorrateo_depto*2;
                                                                                        $acumulado_total_prorrateo_depto = $acumulado_total_prorrateo_depto + $total_prorrateo_depto;
                                                                                        $pagado_valor = $total_prorrateo_depto;
                                                                                        $total_prorrateo_depto = number_format($total_prorrateo_depto, 0, ',', '.');																				        
                                                                                        $puedecargar = 0;
                                                                                        $consultafechas = "SELECT 
                                                                                                id_cam_ven
                                                                                            FROM
                                                                                                venta_campo_venta
                                                                                            WHERE
                                                                                                id_ven = ".$id_ven."";
                                                                                        $conexion->consulta($consultafechas);
                                                                                        $hayregistro = $conexion->total();
                                                                                        if ($hayregistro>0) {
                                                                                            $puedecargar = 1;
                                                                                        }

                                                                                    } else {
                                                                                        $nombre_propietario = "--";
                                                                                        $rut_propietario = "--";                                                                                       
                                                                                        $total_prorrateo_depto = ($fila['prorrateo_viv'] * $porcentaje_prorrateo) / 100;
                                                                                        $total_prorrateo_depto = $total_prorrateo_depto*2;
                                                                                        $acumulado_total_prorrateo_depto = $acumulado_total_prorrateo_depto + $total_prorrateo_depto;
                                                                                        $total_prorrateo_depto = number_format($total_prorrateo_depto, 0, ',', '.');
                                                                                        $fecha_cierre = "--";                                                                                        
                                                                                    }
                                                                                    $pagado = 0;
                                                                                ?>
                                                                                <tr>
                                                                                <td><?php echo $nombre_propietario; ?></td>
                                                                                <td><?php echo $rut_propietario; ?></td>
                                                                                <td><?php echo $fila['nombre_viv']; ?></td>                                                                            
                                                                                <td>
                                                                                <?php
                                                                                // Consulta para fecha de escritura  
                                                                                if($id_ven!=''){                                                                            
                                                                                $consultaFirmaEscritura="
                                                                                SELECT
                                                                                    venta.fecha_escritura_ven
                                                                                FROM
                                                                                    venta_venta as venta
                                                                                WHERE
                                                                                    venta.id_ven = ".$id_ven."";                                                                                                                                                                                                                                                 
                                                                                    $conexion->consulta($consultaFirmaEscritura);
                                                                                    $firma = $conexion->extraer_registro_unico();
                                                                                    echo $firma['fecha_escritura_ven'];                                                                                   
                                                                                }else{
                                                                                    echo '--';
                                                                                }
                                                                                ?>
                                                                                </td>
                                                                                <td>
                                                                                <?php                                                                               
                                                                                // consulta fecha de entrega    
                                                                                if($id_ven!=''){                                                                        
                                                                                $consultaEntrega="
                                                                                SELECT 
	                                                                                    ven_eta.fecha_hasta_eta_ven
	                                                                                FROM
	                                                                                    venta_etapa_venta AS ven_eta,
	                                                                                    venta_etapa_campo_venta AS eta_cam_ven
	                                                                                WHERE
	                                                                                    ven_eta.id_ven = ".$id_ven." AND 
	                                                                                    ven_eta.id_eta = 29 AND 
	                                                                                    ven_eta.id_eta_ven = eta_cam_ven.id_eta_ven AND 
	                                                                                    valor_campo_eta_cam_ven <> ''
                                                                                        ";
                                                                                    $conexion->consulta($consultaEntrega);
                                                                                    $filaentrega = $conexion->extraer_registro_unico();
                                                                                    echo $filaentrega['fecha_hasta_eta_ven'];   
                                                                                }else{
                                                                                    echo '--';
                                                                                }                                                                            
                                                                                ?>
                                                                                </td>                                                                               
                                                                                <td><?php echo $total_prorrateo_depto; ?></td>
                                                                                <td>
																				<?php	
                                                                                if($id_ven!=''){																			
																					$consultafechapagocliente = "SELECT 
											                                    				fecha_pago_cliente_fondo_expotacion
											                                    			FROM
											                                    				venta_campo_venta
																							WHERE
																								id_ven = ".$id_ven."";																					
																					$conexion->consulta($consultafechapagocliente);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['fecha_pago_cliente_fondo_expotacion']<>'' && $filareal['fecha_pago_cliente_fondo_expotacion']<> null) {
								                                                        echo $fecha_pago_real = date("d-m-Y",strtotime($filareal['fecha_pago_cliente_fondo_expotacion']));
							                                                        } else {
																						echo $fecha_pago_real = "--";
							                                                        }

																					if ($_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
																						?>
																						<br>
																						<?php 
																						if ($puedecargar==1) {
																						 ?>
																						 <a id="<?php echo $id_ven; ?>" class='btn-aqui carga_fecha'>Cargar Fecha Pago Cliente aquí</a>
																						<?php
																						} else {
																							echo "al ingresar a operaciones se habilita aquí para cargar";
																						}
																					}
                                                                                }else{
                                                                                    echo '--';
                                                                                }
																				
																				?>
                                                                                </td>
                                                                                <td>
																				<?php 	
                                                                                if($id_ven!=''){																			
																					$consultamontopagocliente = "
																						SELECT 
										                                    				monto_pago_fpm_cliente_ven
										                                    			FROM
										                                    				venta_campo_venta
																						WHERE
																							id_ven = ".$id_ven."";
																					$conexion->consulta($consultamontopagocliente);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['monto_pago_fpm_cliente_ven']<>'' && $filareal['monto_pago_fpm_cliente_ven']<> null) {
							                                                        	$acumulado_monto_pago_cliente = $acumulado_monto_pago_cliente + $filareal['monto_pago_fpm_cliente_ven'];
								                                                        echo $monto_pago_cliente = number_format($filareal['monto_pago_fpm_cliente_ven'], 0, ',', '.');
							                                                        } else {
																						echo $monto_pago_cliente = "--";
							                                                        }

							                                                        if ($_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
																						?>
																						<br>
																						<?php 
																						if ($puedecargar==1) {
																						 ?>
																						 <a id="<?php echo $id_ven; ?>" class='btn-aqui carga_fecha'>Cargar Monto Pago Cliente aquí</a>
																						<?php
																						} else {
																							echo "al ingresar a operaciones se habilita aquí para cargar";
																						}
																					}
																				}else{
                                                                                    echo '--';
                                                                                }
																				 ?>
                                                                                </td>                                                                               
                                                                                <td>
																				<?php 
                                                                                    if($id_ven!=''){
																					$consultafechareal = "SELECT 
											                                    				fecha_pago_fondo_expotacion
											                                    			FROM
											                                    				venta_campo_venta
																							WHERE
																								id_ven = ".$id_ven."";																					
																					$conexion->consulta($consultafechareal);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['fecha_pago_fondo_expotacion']<>'' && $filareal['fecha_pago_fondo_expotacion']<> null) {
							                                                        	$hay_pagado = 1;
							                                                        	$acumula_pagado = $acumula_pagado + $pagado_valor;
								                                                        echo $fecha_pago_real = date("d-m-Y",strtotime($filareal['fecha_pago_fondo_expotacion']));
							                                                        } else {
																						echo $fecha_pago_real = "--";
							                                                        }

																					if ($_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
																						?>
																						<br><?php 
																						if ($puedecargar==1) {
																						 ?>
																						 <a id="<?php echo $id_ven; ?>" class='btn-aqui carga_fecha'>Cargar Fecha Real aquí</a>
																						<?php
																						} else {
																							echo "al ingresar a operaciones se habilita aquí para cargar";
																						}
																					}
																				}else{
                                                                                    echo '--';
                                                                                }
																				?>
																			    </td>                                                                                
																			    <td><?php
																				if($id_ven!=''){
																					$consultamontopagoadm = "
																						SELECT 
										                                    				monto_pago_fpm_adm_ven
										                                    			FROM
										                                    				venta_campo_venta
																						WHERE
																							id_ven = ".$id_ven."";
																					$conexion->consulta($consultamontopagoadm);
							                                                        $filareal = $conexion->extraer_registro_unico();
							                                                        if ($filareal['monto_pago_fpm_adm_ven']<>'' && $filareal['monto_pago_fpm_adm_ven']<> null) {
							                                                        	$acumulado_monto_pago_adm = $acumulado_monto_pago_adm + $filareal['monto_pago_fpm_adm_ven'];
								                                                        echo $monto_pago_adm = number_format($filareal['monto_pago_fpm_adm_ven'], 0, ',', '.');
							                                                        } else {
																						echo $monto_pago_adm = "--";
							                                                        }

							                                                        if ($_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
																						?>
																						<br>
																						<?php 
																						if ($puedecargar==1) {
																						 ?>
																						 <a id="<?php echo $id_ven; ?>" class='btn-aqui carga_fecha'>Cargar Monto Pago Adm. aquí</a>
																						<?php
																						} else {
																							echo "al ingresar a operaciones se habilita aquí para cargar";
																						}
																					}
																				}else{
                                                                                    echo '--';
                                                                                }
																			    ?></td>
                                                                            
                                                                                </tr>
                                                                                <?php 
																			}
																		}
																		 ?>
																	</tbody>
																	<tfoot>
																		<tr>
																			<th>Total</th>
																			<th></th>
																			<th></th>
																			<th><?php echo number_format($acumulado_total_prorrateo_depto, 0, ',', '.'); ?></th>
																			<th></th>
																			
																			<!-- <th></th> -->
																			<th><?php echo number_format($acumulado_monto_pago_cliente, 0, ',', '.'); ?></th>
																			<th></th>
																			<th><?php echo number_format($acumulado_monto_pago_adm, 0, ',', '.'); ?></th>
																		</tr>
																	</tfoot>
																</table>
															</div>
															<?php 
															}
															?>
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
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

    	// ver modal
        $(document).on( "click",".carga_fecha" , function() {
            valor = $(this).attr("id");
            // alert(valor);
            $.ajax({
                type: 'POST',
                url: ("form_update_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });


        $(document).on( "click","#filtro" , function() {
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_condominio = $('#condominio').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"condominio="+var_condominio,
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
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });
       
        
    });
</script>
</body>
</html>
