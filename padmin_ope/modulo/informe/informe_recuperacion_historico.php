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

$ANIO_ACTUAL = date("Y");

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
                                    proceso.opcion_pro = 25 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/informe_tubo.php">TUBO CLIENTES</a></li>
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
                                    proceso.opcion_pro = 26 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/venta_flujo_banco.php">INFORME POR BANCOS</a></li>
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
                                    proceso.opcion_pro = 27 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/venta_recuperacion_bancos.php">RESUMEN BANCOS Y CUADRO RECUPERACIÓN</a></li>
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
                                    proceso.opcion_pro = 28 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="../informe/venta_recuperacion_historico.php">HISTÓRICO RECUPERACIÓN</a></li>
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
                                                    <div class="col-sm-3">
                                                        
                                                        <div class="col-sm-12">
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
                                                                        	if($_SESSION["sesion_filtro_condominio_panel"] == $fila['id_con']){
                                                                        		$sel_con = "selected";
                                                                        	} else {
                                                                        		$sel_con = "";
                                                                        	}
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_con'];?>" <?php echo $sel_con; ?>><?php echo utf8_encode($fila['nombre_con']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label for="mes_inf">Mes:</label>
                                                                  <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                <select class="form-control chico" id="mes_inf" name="mes_inf"> 
                                                                    <option value="">Seleccione Mes</option>
                                                                    <?php  
                                                                    $consulta = "SELECT id_mes, nombre_mes FROM mes_mes ORDER BY id_mes";
                                                                    $conexion->consulta($consulta);
                                                                    $fila_consulta_mes_original = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta_mes_original)){
                                                                        foreach ($fila_consulta_mes_original as $fila) {
                                                                        	if($_SESSION["sesion_filtro_mes_cot_panel"] == $fila['id_mes']){
                                                                        		$sel_mes = "selected";
                                                                        	} else {
                                                                        		$sel_mes = "";
                                                                        	}
                                                                            ?>
                                                                            <option value="<?php echo $fila['id_mes'];?>" <?php echo $sel_mes; ?>><?php echo utf8_encode($fila['nombre_mes']);?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2">
													  <div class="checkbox">
													    <label>
													      <input type="checkbox" id="trim" name="trim"> Ver Trimestre
													    </label>
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
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        // $filtro_consulta .= " AND tor.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_mes_cot_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_mes_original));
                                                        $fila_consulta_mes = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_mes[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_mes)){
                                                            foreach ($fila_consulta_mes as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_mes_cot_panel"],$fila_consulta_mes)){
                                                                    $key = array_search($_SESSION["sesion_filtro_mes_cot_panel"], $fila_consulta_mes);
                                                                    $texto_filtro = $fila_consulta_mes[$key + 1];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span>  
                                                        <?php
                                                        $filtro_consulta .= " AND MONTH(fecha_his) = ".$_SESSION["sesion_filtro_mes_cot_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span> 
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_trimestre_panel"])) {
                                                    	?>
                                                    	<span class="label label-primary">Trimiestre</span>  
                                                    	<?php
                                                    } else {
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
                                        if(isset($_SESSION["sesion_filtro_condominio_panel"]) && isset($_SESSION["sesion_filtro_mes_cot_panel"])){  
	                                        ?>
	                                        <div class="col-md-12">
	                                            <div class="row" id="contenedor_tabla">
	                                                <div class="box">
	                                                    <div class="box-header">
	                                                        <h3 class="box-title">Recuperación | <a class="btn btn-info" href="informe_recuperacion_historico_exc.php" target="_blank">Exporar Excel</a></h3>
	                                                    </div>
	                                                    
	                                                    <!-- /.box-header -->
	                                                    <div class="box-body no-padding">
															<div class="row">
																
																<div class="col-md-12">

																	<?php 
																	$consulta_mes = "SELECT nombre_mes FROM mes_mes WHERE id_mes = ".$_SESSION["sesion_filtro_mes_cot_panel"]."";
                                                                    $conexion->consulta($consulta_mes);
                                                                    $filames = $conexion->extraer_registro_unico();
                                                                    $nombre_mes = $filames['nombre_mes'];
																	 ?>

																	<!-- TABLA NUEVO MES -->
																	<table class="table table-bordered">
																		<thead>
																			<tr class="active">
																				<th colspan="7" class="text-center">Recuperación Histórica <?php echo $nombre_mes; ?> - <?php echo $ANIO_ACTUAL; ?></th>
																			</tr>
																			<tr class="active">
																				<th></th>
																				<th colspan="3" class="text-center">Recuperados</th>
																				<th colspan="3" class="text-center">Por Recuperar</th>
																			</tr>
																			<tr>
																				<td>Día</td>
																				<td>Pie</td>
																				<td>CH</td>
																				<td>Contado</td>
																				<td>Pie</td>
																				<td>CH</td>
																				<td>Contado</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php 
																			$acumulado_pie_recup = 0;
																			$acumulado_contado_recup = 0;
																			$acumulado_ch_recup = 0;
																			$acumulado_pie_por_recup = 0;
																			$acumulado_contado_por_recup = 0;
																			$acumulado_ch_por_recup = 0;
																			$consulta = 
						                                                        "
						                                                        SELECT 
						                                                            fecha_his,
						                                                            contado_recuperado_his,
						                                                            pie_recuperado_his,
						                                                            ch_recuperado_his,
						                                                            pie_por_recup_his,
						                                                            ch_por_recup_his,
						                                                            contado_por_recup_his
						                                                        FROM 
						                                                            historico_recuperacion_historico_diario
						                                                        WHERE
						                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." ";

						                                                    $consulta .= $filtro_consulta;

						                                                    $consulta .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL."";

						                                                    $consulta .= " ORDER BY fecha_his ASC";
																			// echo $consulta;
						                                                    $conexion->consulta($consulta);
																			$pie_por_recup_his = 0;
																			$contado_por_recup_his = 0;
																			$ch_por_recup_his = 0;
						                                                    $fila_consulta = $conexion->extraer_registro();
																			if(is_array($fila_consulta)){
						                                                        foreach ($fila_consulta as $fila) {
						                                                        	$fecha_his = $fila['fecha_his'];
						                                                        	$fecha_his_for = date("d-m-Y",strtotime($fecha_his));
						                                                        	$dia_his_for = date("d",strtotime($fecha_his));

						                                                        	$contado_recuperado_his = $fila['contado_recuperado_his'];
						                                                        	$contado_recuperado_his_for = number_format($contado_recuperado_his, 3, ',', '.');
						                                                        	$pie_recuperado_his = $fila['pie_recuperado_his'];
						                                                        	$pie_recuperado_his_for = number_format($pie_recuperado_his, 3, ',', '.');
						                                                        	$ch_recuperado_his = $fila['ch_recuperado_his'];
						                                                        	$ch_recuperado_his_for = number_format($ch_recuperado_his, 3, ',', '.');

						                                                        	$pie_por_recup_his = $fila['pie_por_recup_his'];
						                                                        	$pie_por_recup_his_for = number_format($pie_por_recup_his, 3, ',', '.');
						                                                        	$ch_por_recup_his = $fila['ch_por_recup_his'];
						                                                        	$ch_por_recup_his_for = number_format($ch_por_recup_his, 3, ',', '.');
						                                                        	$contado_por_recup_his = $fila['contado_por_recup_his'];
						                                                        	$contado_por_recup_his_for = number_format($contado_por_recup_his, 3, ',', '.');
						                                                        	?>
																					<tr>
																						<td><?php echo $dia_his_for; ?></td>
																						<td><?php echo $pie_recuperado_his_for; ?></td>
																						<td><?php echo $ch_recuperado_his_for; ?></td>
																						<td><?php echo $contado_recuperado_his_for; ?></td>
																						<td><?php echo $pie_por_recup_his_for; ?></td>
																						<td><?php echo $ch_por_recup_his_for; ?></td>
																						<td><?php echo $contado_por_recup_his_for; ?></td>
																					</tr>
																					<?php 
																					$acumulado_pie_recup = $acumulado_pie_recup + $pie_recuperado_his;
																					$acumulado_contado_recup = $acumulado_contado_recup + $contado_recuperado_his;
																					$acumulado_ch_recup = $acumulado_ch_recup + $ch_recuperado_his;
																					
																				}
																			}
																			$acumulado_pie_por_recup = $pie_por_recup_his;
																			$acumulado_contado_por_recup = $contado_por_recup_his;
																			$acumulado_ch_por_recup = $ch_por_recup_his;
																			?>
																			<tr class="info">
																				<td></td>
																				<td><?php echo number_format($acumulado_pie_recup, 3, ',', '.');?></td>
																				<td><?php echo number_format($acumulado_ch_recup, 3, ',', '.');?></td>
																				<td><?php echo number_format($acumulado_contado_recup, 3, ',', '.');?></td>
																				<td><?php echo number_format($acumulado_pie_por_recup, 3, ',', '.');?></td>
																				<td><?php echo number_format($acumulado_ch_por_recup, 3, ',', '.');?></td>
																				<td><?php echo number_format($acumulado_contado_por_recup, 3, ',', '.');?></td>
																			</tr>
																		</tbody>
																	</table>

																	<!-- revisa si es trimestre -->
																	<?php 
																	if(isset($_SESSION["sesion_filtro_trimestre_panel"])) {

																		if($_SESSION["sesion_filtro_mes_cot_panel"]==1)	{
																			$mes_dos = 12;
																			$mes_tres = 11;
																			$ANIO_ACTUAL_dos = $ANIO_ACTUAL - 1;
																			$ANIO_ACTUAL_tres = $ANIO_ACTUAL - 1;
																		} else if ($_SESSION["sesion_filtro_mes_cot_panel"]==2) {
																			$mes_dos = $_SESSION["sesion_filtro_mes_cot_panel"] - 1;
																			$mes_tres = 12;
																			$ANIO_ACTUAL_dos = $ANIO_ACTUAL;
																			$ANIO_ACTUAL_tres = $ANIO_ACTUAL - 1;
																		} else {
																			$mes_dos = $_SESSION["sesion_filtro_mes_cot_panel"] - 1;
																			$mes_tres = $_SESSION["sesion_filtro_mes_cot_panel"] - 2;
																			$ANIO_ACTUAL_dos = $ANIO_ACTUAL;
																			$ANIO_ACTUAL_tres = $ANIO_ACTUAL;
																		}
																		
																		$consulta_mes_dos = "SELECT nombre_mes FROM mes_mes WHERE id_mes = ".$mes_dos."";
                                                                    	$conexion->consulta($consulta_mes_dos);
                                                                    	$filames = $conexion->extraer_registro_unico();
                                                                    	$nombre_mes_dos = $filames['nombre_mes'];

                                                                    	$consulta_mes_tres = "SELECT nombre_mes FROM mes_mes WHERE id_mes = ".$mes_tres."";
                                                                    	$conexion->consulta($consulta_mes_tres);
                                                                    	$filames = $conexion->extraer_registro_unico();
                                                                    	$nombre_mes_tres = $filames['nombre_mes'];
																		?>
																		 <!-- TABLA NUEVO MES DOS-->
																		<table class="table table-bordered">
																			<thead>
																				<tr class="active">
																					<th colspan="7" class="text-center">Recuperación Histórica <?php echo $nombre_mes_dos; ?> - <?php echo $ANIO_ACTUAL_dos; ?></th>
																				</tr>
																				<tr class="active">
																					<th></th>
																					<th colspan="3" class="text-center">Recuperados</th>
																					<th colspan="3" class="text-center">Por Recuperar</th>
																				</tr>
																				<tr>
																					<td>Día</td>
																					<td>Pie</td>
																					<td>CH</td>
																					<td>Contado</td>
																					<td>Pie</td>
																					<td>CH</td>
																					<td>Contado</td>
																				</tr>
																			</thead>
																			<tbody>
																				<?php 
																				$acumulado_pie_recup = 0;
																				$acumulado_contado_recup = 0;
																				$acumulado_ch_recup = 0;
																				$acumulado_pie_por_recup = 0;
																				$acumulado_contado_por_recup = 0;
																				$acumulado_ch_por_recup = 0;
																				$pie_por_recup_his = 0;
																				$contado_por_recup_his = 0;
																				$ch_por_recup_his = 0;
																				$consulta = 
							                                                        "
							                                                        SELECT 
							                                                            fecha_his,
							                                                            contado_recuperado_his,
							                                                            pie_recuperado_his,
							                                                            ch_recuperado_his,
							                                                            pie_por_recup_his,
							                                                            ch_por_recup_his,
							                                                            contado_por_recup_his
							                                                        FROM 
							                                                            historico_recuperacion_historico_diario
							                                                        WHERE
							                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." ";

							                                                    $consulta .= " AND MONTH(fecha_his) = ".$mes_dos;

							                                                    $consulta .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL_dos."";

							                                                    $consulta .= " ORDER BY fecha_his ASC";
							                                                    $conexion->consulta($consulta);
							                                                    $fila_consulta = $conexion->extraer_registro();
																				if(is_array($fila_consulta)){
							                                                        foreach ($fila_consulta as $fila) {
							                                                        	$fecha_his = $fila['fecha_his'];
							                                                        	$fecha_his_for = date("d-m-Y",strtotime($fecha_his));
							                                                        	$dia_his_for = date("d",strtotime($fecha_his));

							                                                        	$contado_recuperado_his = $fila['contado_recuperado_his'];
							                                                        	$contado_recuperado_his_for = number_format($contado_recuperado_his, 3, ',', '.');
							                                                        	$pie_recuperado_his = $fila['pie_recuperado_his'];
							                                                        	$pie_recuperado_his_for = number_format($pie_recuperado_his, 3, ',', '.');
							                                                        	$ch_recuperado_his = $fila['ch_recuperado_his'];
							                                                        	$ch_recuperado_his_for = number_format($ch_recuperado_his, 3, ',', '.');

							                                                        	$pie_por_recup_his = $fila['pie_por_recup_his'];
							                                                        	$pie_por_recup_his_for = number_format($pie_por_recup_his, 3, ',', '.');
							                                                        	$ch_por_recup_his = $fila['ch_por_recup_his'];
							                                                        	$ch_por_recup_his_for = number_format($ch_por_recup_his, 3, ',', '.');
							                                                        	$contado_por_recup_his = $fila['contado_por_recup_his'];
							                                                        	$contado_por_recup_his_for = number_format($contado_por_recup_his, 3, ',', '.');
							                                                        	?>
																						<tr>
																							<td><?php echo $dia_his_for; ?></td>
																							<td><?php echo $pie_recuperado_his_for; ?></td>
																							<td><?php echo $ch_recuperado_his_for; ?></td>
																							<td><?php echo $contado_recuperado_his_for; ?></td>
																							<td><?php echo $pie_por_recup_his_for; ?></td>
																							<td><?php echo $ch_por_recup_his_for; ?></td>
																							<td><?php echo $contado_por_recup_his_for; ?></td>
																						</tr>
																						<?php 
																						$acumulado_pie_recup = $acumulado_pie_recup + $pie_recuperado_his;
																						$acumulado_contado_recup = $acumulado_contado_recup + $contado_recuperado_his;
																						$acumulado_ch_recup = $acumulado_ch_recup + $ch_recuperado_his;
																						
																					}
																				}
																				$acumulado_pie_por_recup = $pie_por_recup_his;
																				$acumulado_contado_por_recup = $contado_por_recup_his;
																				$acumulado_ch_por_recup = $ch_por_recup_his;
																				?>
																				<tr class="info">
																					<td></td>
																					<td><?php echo number_format($acumulado_pie_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_ch_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_contado_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_pie_por_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_ch_por_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_contado_por_recup, 3, ',', '.');?></td>
																				</tr>
																			</tbody>
																		</table>


																		 <!-- TABLA NUEVO MES TRES-->
																		<table class="table table-bordered">
																			<thead>
																				<tr class="active">
																					<th colspan="7" class="text-center">Recuperación Histórica <?php echo $nombre_mes_tres; ?> - <?php echo $ANIO_ACTUAL_tres; ?></th>
																				</tr>
																				<tr class="active">
																					<th></th>
																					<th colspan="3" class="text-center">Recuperados</th>
																					<th colspan="3" class="text-center">Por Recuperar</th>
																				</tr>
																				<tr>
																					<td>Día</td>
																					<td>Pie</td>
																					<td>CH</td>
																					<td>Contado</td>
																					<td>Pie</td>
																					<td>CH</td>
																					<td>Contado</td>
																				</tr>
																			</thead>
																			<tbody>
																				<?php 
																				$acumulado_pie_recup = 0;
																				$acumulado_contado_recup = 0;
																				$acumulado_ch_recup = 0;
																				$acumulado_pie_por_recup = 0;
																				$acumulado_contado_por_recup = 0;
																				$acumulado_ch_por_recup = 0;
																				$pie_por_recup_his = 0;
																				$contado_por_recup_his = 0;
																				$ch_por_recup_his = 0;
																				$consulta = 
							                                                        "
							                                                        SELECT 
							                                                            fecha_his,
							                                                            contado_recuperado_his,
							                                                            pie_recuperado_his,
							                                                            ch_recuperado_his,
							                                                            pie_por_recup_his,
							                                                            ch_por_recup_his,
							                                                            contado_por_recup_his
							                                                        FROM 
							                                                            historico_recuperacion_historico_diario
							                                                        WHERE
							                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." ";

							                                                    $consulta .= " AND MONTH(fecha_his) = ".$mes_tres;

							                                                    $consulta .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL_tres."";

							                                                    $consulta .= " ORDER BY fecha_his ASC";
							                                                    $conexion->consulta($consulta);
							                                                    $fila_consulta = $conexion->extraer_registro();
																				if(is_array($fila_consulta)){
							                                                        foreach ($fila_consulta as $fila) {
							                                                        	$fecha_his = $fila['fecha_his'];
							                                                        	$fecha_his_for = date("d-m-Y",strtotime($fecha_his));
							                                                        	$dia_his_for = date("d",strtotime($fecha_his));

							                                                        	$contado_recuperado_his = $fila['contado_recuperado_his'];
							                                                        	$contado_recuperado_his_for = number_format($contado_recuperado_his, 3, ',', '.');
							                                                        	$pie_recuperado_his = $fila['pie_recuperado_his'];
							                                                        	$pie_recuperado_his_for = number_format($pie_recuperado_his, 3, ',', '.');
							                                                        	$ch_recuperado_his = $fila['ch_recuperado_his'];
							                                                        	$ch_recuperado_his_for = number_format($ch_recuperado_his, 3, ',', '.');

							                                                        	$pie_por_recup_his = $fila['pie_por_recup_his'];
							                                                        	$pie_por_recup_his_for = number_format($pie_por_recup_his, 3, ',', '.');
							                                                        	$ch_por_recup_his = $fila['ch_por_recup_his'];
							                                                        	$ch_por_recup_his_for = number_format($ch_por_recup_his, 3, ',', '.');
							                                                        	$contado_por_recup_his = $fila['contado_por_recup_his'];
							                                                        	$contado_por_recup_his_for = number_format($contado_por_recup_his, 3, ',', '.');
							                                                        	?>
																						<tr>
																							<td><?php echo $dia_his_for; ?></td>
																							<td><?php echo $pie_recuperado_his_for; ?></td>
																							<td><?php echo $ch_recuperado_his_for; ?></td>
																							<td><?php echo $contado_recuperado_his_for; ?></td>
																							<td><?php echo $pie_por_recup_his_for; ?></td>
																							<td><?php echo $ch_por_recup_his_for; ?></td>
																							<td><?php echo $contado_por_recup_his_for; ?></td>
																						</tr>
																						<?php 
																						$acumulado_pie_recup = $acumulado_pie_recup + $pie_recuperado_his;
																						$acumulado_contado_recup = $acumulado_contado_recup + $contado_recuperado_his;
																						$acumulado_ch_recup = $acumulado_ch_recup + $ch_recuperado_his;
																						
																					}
																				}
																				$acumulado_pie_por_recup = $pie_por_recup_his;
																				$acumulado_contado_por_recup = $contado_por_recup_his;
																				$acumulado_ch_por_recup = $ch_por_recup_his;
																				?>
																				<tr class="info">
																					<td></td>
																					<td><?php echo number_format($acumulado_pie_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_ch_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_contado_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_pie_por_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_ch_por_recup, 3, ',', '.');?></td>
																					<td><?php echo number_format($acumulado_contado_por_recup, 3, ',', '.');?></td>
																				</tr>
																			</tbody>
																		</table>
																	<?php
																	}
																	?>

																</div>
																<!-- TABLA RESUMEN -->
																<div class="col-md-12">
																	<!-- TABLA MENSUAL RECUPERACIÓN -->
																	<table class="table table-bordered">
																		<thead>
																			<tr class="active">
																				<th colspan="7" class="text-center">Resumen Mensual</th>
																			</tr>
																			<tr class="active">
																				<th></th>
																				<th colspan="3" class="text-center">Recuperados</th>
																				<th colspan="3" class="text-center">Por Recuperar</th>
																			</tr>
																			<tr>
																				<td>Mes</td>
																				<td>Pie</td>
																				<td>CH</td>
																				<td>Contado</td>
																				<td>Pie</td>
																				<td>CH</td>
																				<td>Contado</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php 
																			// ciclo meses
																			$consulta = "SELECT id_mes, nombre_mes FROM mes_mes ORDER BY id_mes";
		                                                                    $conexion->consulta($consulta);
		                                                                    $fila_mes = $conexion->extraer_registro();
		                                                                    if(is_array($fila_mes)){
		                                                                        foreach ($fila_mes as $fila) {
		                                                                        	$nombre_mes = utf8_encode($fila['nombre_mes']);
																					$consulta = 
								                                                        "
								                                                        SELECT 
								                                                            SUM(contado_recuperado_his) AS cont_recup,
								                                                            SUM(pie_recuperado_his) AS pie_recup,
								                                                            SUM(ch_recuperado_his) AS ch_recup
								                                                        FROM 
								                                                            historico_recuperacion_historico_diario
								                                                        WHERE
								                                                            id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." AND MONTH(fecha_his) = ".$fila['id_mes']."";
								                                                    $consulta .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL."";


								                                                    $conexion->consulta($consulta);
								                                                    $fila_resumen = $conexion->extraer_registro_unico();
								                                                    $cont_recup = $fila_resumen['cont_recup'];
						                                                        	$cont_recup_for = number_format($cont_recup, 3, ',', '.');
						                                                        	$pie_recup = $fila_resumen['pie_recup'];
						                                                        	$pie_recup_for = number_format($pie_recup, 3, ',', '.');
						                                                        	$ch_recup = $fila_resumen['ch_recup'];
						                                                        	$ch_recup_for = number_format($ch_recup, 3, ',', '.');

						                                                        	$consulta_por_recuperar = "
						                                                        		SELECT 
						                                                        			max(fecha_his),
						                                                        			ch_por_recup_his, 
						                                                        			pie_por_recup_his, 
						                                                        			contado_por_recup_his
						                                                        		FROM 
						                                                        			historico_recuperacion_historico_diario
						                                                        		WHERE 
						                                                        			MONTH(fecha_his) = ".$fila['id_mes']." AND
						                                                        			id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."";
						                                                        	$consulta_por_recuperar .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL."";

						                                                        	// echo $consulta_por_recuperar;

						                                                        	$conexion->consulta($consulta_por_recuperar);
								                                                    $fila_resumen = $conexion->extraer_registro_unico();
						                                                        	$pie_p_recup = $fila_resumen['pie_por_recup_his'];
						                                                        	$pie_p_recup_for = number_format($pie_p_recup, 3, ',', '.');
						                                                        	$ch_p_recup = $fila_resumen['ch_por_recup_his'];
						                                                        	$ch_p_recup_for = number_format($ch_p_recup, 3, ',', '.');
						                                                        	$cont_p_recup = $fila_resumen['contado_por_recup_his'];
						                                                        	$cont_p_recup_for = number_format($cont_p_recup, 3, ',', '.');
																					?>
																					<tr>
																						<td><?php echo $nombre_mes; ?></td>
																						<td><?php echo $pie_recup_for; ?></td>
																						<td><?php echo $ch_recup_for; ?></td>
																						<td><?php echo $cont_recup_for; ?></td>
																						<td><?php echo $pie_p_recup_for; ?></td>
																						<td><?php echo $ch_p_recup_for; ?></td>
																						<td><?php echo $cont_p_recup_for; ?></td>
																					</tr>
																					<?php 
																				}
																			}
																			?>
																		</tbody>
																	</table>
																</div>

																<!-- GRÁFICOS RESUMEN -->
																<div class="col-md-12">
																	<div style="min-height: 450px">
																		<h4 class="text-center">Montos Recuperados</h4>
																	    <div id="grafico_recup" style="width: 80%; margin-top: 0px; margin-left: auto; margin-right: auto; height: 100%"></div>
																	</div>
																</div>
																<div class="col-md-12">
																	<div style="min-height: 450px">
																		<h4 class="text-center">Montos Por Recuperar</h4>
																	    <div id="grafico_por_recup" style="width: 80%; margin-top: 0px; margin-left: auto; margin-right: auto; height: 100%"></div>
																	</div>
																</div>
							                            	</div>
	                                                    </div>
	                                                    <!-- /.box-body -->
	                                                </div>
	                                            </div>
	                                        </div>
	                                    	<?php
	                                    }  
	                                   	?>
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
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script> -->
<script type="text/javascript">

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
            var_condominio = $('#condominio').val();
            var_mes = $('#mes_inf').val();
            var_trim = $('#trim').prop('checked');

            if (var_trim) {
				var_trim = 1;
            } else {
            	var_trim = 0;
            }

            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"condominio="+var_condominio+"&mes="+var_mes+"&trimestre="+var_trim,
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
       
    });
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
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
		$('#grafico_recup').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: ''
		    },
		    xAxis: {
		    	labels: {
	                style: {
	                    fontSize:'14px'
	                }
	            },
		        categories: [
		        <?php
	        	$consulta = "SELECT id_mes, nombre_mes FROM mes_mes ORDER BY id_mes";
                $conexion->consulta($consulta);
                $fila_mes = $conexion->extraer_registro();
                if(is_array($fila_mes)){
                    foreach ($fila_mes as $fila) {
                    	$nombre_mes = utf8_encode($fila['nombre_mes']);
						$consulta = 
                            "
                            SELECT 
                                SUM(contado_recuperado_his) AS cont_recup,
                                SUM(pie_recuperado_his) AS pie_recup,
                                SUM(ch_recuperado_his) AS ch_recup
                            FROM 
                                historico_recuperacion_historico_diario
                            WHERE
                                id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]." AND MONTH(fecha_his) = ".$fila['id_mes']."";
                        $consulta .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL."";
                        // echo $consulta;
                        $conexion->consulta($consulta);
                        $fila_resumen = $conexion->extraer_registro_unico();
                        $cont_recup = $fila_resumen['cont_recup'];
                    	$cont_recup_for = number_format($cont_recup, 2, ',', '.');
                    	$pie_recup = $fila_resumen['pie_recup'];
                    	$pie_recup_for = number_format($pie_recup, 2, ',', '.');
                    	$ch_recup = $fila_resumen['ch_recup'];
                    	$ch_recup_for = number_format($ch_recup, 2, ',', '.');
		        
						$c_cr .= $cont_recup ? round($cont_recup,2)."," : "0,";
						$c_pr .= $pie_recup ? round($pie_recup,2)."," : "0,";
						$c_chr .= $ch_recup ? round($ch_recup,2)."," : "0,";

				        if ($fila['id_mes']==12) {
							echo "'".$nombre_mes."'";
				        } else {
							echo "'".$nombre_mes."',";
				        }					        
				    }
				}
		        ?>
		    	]
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Monto'
		        },
		        stackLabels: {
		            enabled: true,
		            style: {
		                fontWeight: 'normal',
		                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		            }
		        }
		    },
		    legend: {
		        align: 'center',
		        x: 0,
		        verticalAlign: 'bottom',
		        // y: 30,
		        floating: false,
		        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
		        borderColor: '#CCC',
		        borderWidth: 1,
		        shadow: false
		    },
		    tooltip: {
		        headerFormat: '<b>{point.x}</b><br/>',
		        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}<br/>({point.percentage:.0f}%)'
		    },
		    plotOptions: {
		        column: {
		            stacking: 'amount',
		            dataLabels: {
		                enabled: true,
		                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
		            }
		        }
		    },
		    colors: ['#fac090', '#3a86c2', '#00af50'],
		    series: [{
		        name: 'Pie Recuperado',
		        data: [
				<?php
				echo substr($c_pr, 0, -1);
				?>
		        ]
		    }, {
		        name: 'CH Recuper.',
		        data: [
				<?php
				echo substr($c_chr, 0, -1);
				?>
		        ]
		    }, {
		        name: 'Contados Recup.',
		        data: [
				<?php
				echo substr($c_cr, 0, -1);
				?>
		        ]
		    }]
		});


		$('#grafico_por_recup').highcharts({
		    chart: {
		        type: 'column'
		    },
		    title: {
		        text: ''
		    },
		    xAxis: {
		    	labels: {
	                style: {
	                    fontSize:'14px'
	                }
	            },
		        categories: [
		        <?php
	        	$consulta = "SELECT id_mes, nombre_mes FROM mes_mes ORDER BY id_mes";
                $conexion->consulta($consulta);
                $fila_mes = $conexion->extraer_registro();
                if(is_array($fila_mes)){
                    foreach ($fila_mes as $fila) {
                    	$nombre_mes = utf8_encode($fila['nombre_mes']);
						$consulta_por_recuperar = "
                    		SELECT 
                    			max(fecha_his),
                    			ch_por_recup_his, 
                    			pie_por_recup_his, 
                    			contado_por_recup_his
                    		FROM 
                    			historico_recuperacion_historico_diario
                    		WHERE 
                    			MONTH(fecha_his) = ".$fila['id_mes']." AND
                    			id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."";
                    	$consulta_por_recuperar .= " AND YEAR(fecha_his) = ".$ANIO_ACTUAL."";

                        // echo $consulta;
                        $conexion->consulta($consulta_por_recuperar);
                        $fila_resumen = $conexion->extraer_registro_unico();
                        $cont_p_recup = $fila_resumen['contado_por_recup_his'];
                    	// $cont_p_recup_for = number_format($cont_p_recup, 2, ',', '.');
                    	$pie_p_recup = $fila_resumen['pie_por_recup_his'];
                    	// $pie_p_recup_for = number_format($pie_p_recup, 2, ',', '.');
                    	$ch_p_recup = $fila_resumen['ch_por_recup_his'];
                    	// $ch_p_recup_for = number_format($ch_p_recup, 2, ',', '.');
		        
						$c_c_pr .= round($cont_p_recup,2).",";
						$c_p_pr .= round($pie_p_recup,2).",";
						$c_ch_pr .= round($ch_p_recup,2).",";

				        if ($fila['id_mes']==12) {
							echo "'".$nombre_mes."'";
				        } else {
							echo "'".$nombre_mes."',";
				        }					        
				    }
				}
		        ?>
		    	]
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Monto'
		        },
		        stackLabels: {
		            enabled: true,
		            style: {
		                fontWeight: 'normal',
		                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
		            }
		        }
		    },
		    legend: {
		        align: 'center',
		        x: 0,
		        verticalAlign: 'bottom',
		        // y: 30,
		        floating: false,
		        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
		        borderColor: '#CCC',
		        borderWidth: 1,
		        shadow: false
		    },
		    tooltip: {
		        headerFormat: '<b>{point.x}</b><br/>',
		        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}<br/>({point.percentage:.0f}%)'
		    },
		    plotOptions: {
		        column: {
		            stacking: 'amount',
		            dataLabels: {
		                enabled: true,
		                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
		            }
		        }
		    },
		    colors: ['#fac090', '#3a86c2', '#00af50'],
		    series: [{
		        name: 'Pie Por Recuperar',
		        data: [
				<?php
				echo substr($c_p_pr, 0, -1);
				?>
		        ]
		    }, {
		        name: 'CH Por Recuper.',
		        data: [
				<?php
				echo substr($c_ch_pr, 0, -1);
				?>
		        ]
		    }, {
		        name: 'Contados Por Recup.',
		        data: [
				<?php
				echo substr($c_c_pr, 0, -1);
				?>
		        ]
		    }]
		});
    });
</script>

</body>
</html>
