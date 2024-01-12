<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}


$nombre = 'Historico_Recuperacion_'.date('d-m-Y');


header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");

$ANIO_ACTUAL = date("Y");


?>
<title>Ventas - Informe</title>
<!-- DataTables -->
<meta charset="utf-8">
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

 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->


    <section class="content">
        <div class="col-sm-12">
            <!-- general form elements -->
            <div class="row">
                <div class="col-md-12">
                	<?php
                    if(isset($_SESSION["sesion_filtro_condominio_panel"]) && isset($_SESSION["sesion_filtro_mes_cot_panel"])){ 

                    $filtro_consulta .= " AND MONTH(fecha_his) = ".$_SESSION["sesion_filtro_mes_cot_panel"]; 

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
							<tr style="background-color: #e7e7d4;">
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
						<br>
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
								<tr style="background-color: #e7e7d4;">
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
						 <br>
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
								<tr style="background-color: #e7e7d4;">
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
					<br><br>
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
                    <?php
                    }  
                   	?>
                	</div>
                </div>
        	</div>
    </section>

      <!-- Main content -->
   	
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>

</body>
</html>
