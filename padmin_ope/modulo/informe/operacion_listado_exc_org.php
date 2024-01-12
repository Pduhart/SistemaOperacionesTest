<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'Listado_Completo_unidades'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<title>Ventas - Informe</title>
<!-- DataTables -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
</head>
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
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <!-- <div class="row">
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    <div class="col-sm-5"> -->

                                                        <?php  
                                                        $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_condominio_original)){
                                                            foreach ($fila_consulta_condominio_original as $fila) {
                                                             
                                                            }
                                                        }
                                                        ?>
                                                    <!-- </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row"> -->
                                            <!-- <div class="col-sm-12" id="contenedor_filtro"> -->
                                                <!-- <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px"> -->
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
                                                        
                                                        if(is_array($fila_consulta_condominio)){
                                                            foreach ($fila_consulta_condominio as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                                                    $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                                                    $texto_filtro = $fila_consulta_condominio[$key + 1];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span>   -->
                                                        <?php
                                                        $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                        $filtro_consulta_cierre .= " AND cie.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                                                    }
                                                    else{
      
                                                    }
                                                    ?>
                                                    
                                                <!-- </i> -->
                                              <!-- </h6> -->
                                            <!-- </div> -->
                                        <!-- </div> -->
                                        <div class="col-md-12">
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body" style="overflow-x: hidden;">
														<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Condominio</th>
                                                                    <th>Modelo</th>
                                                                    <th>Depto</th>
                                                                    <th>Orientación</th>
                                                                    <th>Vendedor</th>
                                                                    <th>Categoría</th>
                                                                    <th>Cliente</th>
                                                                    <th>Fecha Venta</th>
                                                                    <th>Est. Adic.</th>
                                                                    <th>Forma Pago</th>
                                                                    <th>Banco/Tipo Pago</th>
                                                                    <th>Pie(%)</th>
                                                                    <th>Crédito Hipotecario</th>
                                                                    <th>Saldo a Pagar</th>
                                                                    <th>Estado Venta</th>
                                                                    <th>Etapa Actual</th>
                                                                    <th>Estado Etapa</th>
                                                                    <th>Premio</th>
                                                                    <th>Dcto. (UF)</th>
                                                                    <th>Desistimiento</th>
                                                                    <th>Total</th>
                                                                </tr>    
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $acumulado_monto = 0;
                                                                
                                                                
                                                                $consulta = 
                                                                    "
                                                                    SELECT 
																		ven.id_ven,
																		ven.fecha_ven,
																		ven.monto_ven,
																		viv.id_viv,
																		viv.nombre_viv,
																		ori_viv.nombre_ori_viv,
																		mode.id_mod,
																		mode.nombre_mod,
																		con.id_con,
																		con.nombre_con,
																		tor.id_tor,
																		tor.nombre_tor,
																		vend.id_vend,
																		vend.nombre_vend,
																		vend.apellido_paterno_vend,
																		vend.apellido_materno_vend,
																		pro.id_pro,
																		pro.nombre_pro,
																		pro.apellido_paterno_pro,
																		pro.apellido_materno_pro,
																		esta.id_esta,
																		esta.nombre_esta,
																		for_pag.id_for_pag,
																		for_pag.nombre_for_pag,
																	    ven.id_pie_ven,
																		cat.nombre_cat_vend,
																		ven.descuento_ven,
																		pre.nombre_pre,
																		ven.monto_credito_ven,
																		ven.monto_credito_real_ven,
																		estado_venta.nombre_est_ven,
																		ven.id_ban,
																		ven.id_tip_pag,
																		ban.nombre_ban,
																		tip_pag.nombre_tip_pag,
																		ven.id_est_ven,
																		viv.id_est_viv,
																		viv.valor_viv,
																		viv.id_pis
																	FROM 
																		vivienda_vivienda AS viv
																		LEFT JOIN venta_venta AS ven ON viv.id_viv = ven.id_viv
																		LEFT JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
																		LEFT JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
																		-- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
																		INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
																		INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
																		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
																		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
																		LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
																		LEFT JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
																		LEFT JOIN venta_estacionamiento_venta AS est_ven ON est_ven.id_ven = ven.id_ven
																		LEFT JOIN estacionamiento_estacionamiento AS esta ON esta.id_esta = est_ven.id_esta
																		LEFT JOIN vendedor_categoria_vendedor AS cat ON cat.id_cat_vend = ven.factor_categoria_ven
																		LEFT JOIN premio_premio AS pre ON pre.id_pre = ven.id_pre
																		LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
																		LEFT JOIN pago_tipo_pago AS tip_pag ON tip_pag.id_tip_pag = ven.id_tip_pag
																	WHERE 
																		viv.id_viv > 0
																	ORDER BY 
																		viv.id_pis, viv.nombre_viv ASC"; 
                                                                // echo $consulta;
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta)){
                                                                    foreach ($fila_consulta as $fila) {
                                                                    	$estado_viv = $fila['id_est_viv'];
                                                                    	$fecha_venta = '';
                                                                    	if ($estado_viv==2) {
	                                                                        if ($fila['fecha_ven'] == '0000-00-00') {
	                                                                            $fecha_venta = "";
	                                                                        }
	                                                                        else{
	                                                                            $fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
	                                                                        }
	                                                                        $acumulado_monto = $acumulado_monto + $fila['monto_ven'];
	                                                                        $consulta = 
	                                                                            "
	                                                                            SELECT 
	                                                                                eta_ven.fecha_desde_eta_ven,
	                                                                                eta.duracion_eta,
	                                                                                eta.nombre_eta
	                                                                            FROM
	                                                                                venta_venta AS ven
	                                                                                INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
	                                                                                INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
	                                                                            WHERE
	                                                                                ven.id_ven = ?
	                                                                            ORDER BY 
	                                                                                eta_ven.id_eta_ven
	                                                                            DESC
	                                                                            LIMIT 0,1
	                                                                            ";
                                                                        	$conexion->consulta_form($consulta,array($fila["id_ven"]));
                                                                        	$cantidad_etapa = $conexion->total();
	                                                                        if($cantidad_etapa > 0){
	                                                                            $fila_venta = $conexion->extraer_registro_unico();
	                                                                            $hoy = date("Y-m-d");
	                                                                            $fecha_inicio = $fila_venta["fecha_desde_eta_ven"];
	                                                                            $duracion = $fila_venta["duracion_eta"];
	                                                                            $etapa_nombre = $fila_venta["nombre_eta"];
	                                                                            $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
	                                                                            $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
	                                                                            if($fecha_final > $hoy){
	                                                                                $etapa_estado = "Atrasada";
	                                                                                $etapa = 1;
	                                                                            }
	                                                                            else{
	                                                                                $etapa_estado = "En fecha";
	                                                                                $etapa = 2;
	                                                                            }
	                                                                        }
	                                                                        else{
	                                                                            $etapa_nombre = "--";
	                                                                            $etapa_estado = "No iniciada";
	                                                                            $etapa = 3;
	                                                                        }
	                                                                    } else {
	                                                                    	$etapa_nombre = "";
																			$etapa_estado = "";
	                                                                    }

	                                                                    $nombre_tip_des = "";
																		if ($fila['id_est_ven']==3) {
																			$consulta_des = 
														                        "
														                        SELECT
														                            tip_des.nombre_tip_des
														                        FROM
														                            venta_desestimiento_venta AS des,
														                            desistimiento_tipo_desistimiento AS tip_des
														                        WHERE
														                            des.id_ven = " .$fila["id_ven"]. " AND 
														                            des.id_tip_des = tip_des.id_tip_des
														                        ";
														                    $conexion->consulta($consulta_des);
														                    $fila_des = $conexion->extraer_registro_unico();
														                    $nombre_tip_des = "<br><font style='font-size:11px'>".utf8_encode($fila_des["nombre_tip_des"])."</font>";
																		}
                                                                        
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_mod']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_ori_viv']); ?></td>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_cat_vend']); ?></td>
                                                                            <td style="text-align: left;"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                            <td><?php echo $fecha_venta; ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_esta']); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_for_pag']); ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if ($fila['id_for_pag'] == 1) {
                                                                                    echo utf8_encode($fila['nombre_ban']);
                                                                                }
                                                                                else if ($fila['id_for_pag'] == 2){
                                                                                    echo utf8_encode($fila['nombre_tip_pag']);
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo utf8_encode($fila['id_pie_ven']); ?></td>
                                                                            <?php 
                                                                            $saldo_credito = 0;
                                                                            $saldo_contado = 0;
                                                                            if ($estado_viv==2) {
																				if ($fila['id_for_pag']==2) {
																					$saldo_contado = number_format($fila['monto_credito_ven'], 2, ',', '.');
																					$saldo_credito = 0;
																				} else {
	                                                                            	if ($fila['monto_credito_real_ven']<>'' && $fila['monto_credito_real_ven']<>0) {
	                                                                            		$saldo_credito = number_format($fila['monto_credito_real_ven'], 2, ',', '.');
	                                                                            	} else {
	                                                                            		$saldo_credito = number_format($fila['monto_credito_ven'], 2, ',', '.');
	                                                                            	}
	                                                                            	$saldo_contado = 0;
																				}
																			}
																			?>
                                                                            <td><?php 
                                                                            	echo $saldo_credito;
                                                                            	?>
                                                                            </td>
                                                                            <td><?php 
                                                                            	echo $saldo_contado;
                                                                            	?>
                                                                            </td>
                                                                            <td><?php 
                                                                            if ($estado_viv==2) {
                                                                            	echo utf8_encode($fila['nombre_est_ven']);
                                                                            	echo $nombre_tip_des;
                                                                            } else {
                                                                            	echo "Disponible";
                                                                            }
                                                                            ?></td>
                                                                            <td><?php echo utf8_encode($etapa_nombre); ?></td>
                                                                            <td><?php echo utf8_encode($etapa_estado); ?></td>
                                                                            <td><?php echo utf8_encode($fila['nombre_pre']); ?></td>
                                                                            <td><?php 
                                                                            	if ($estado_viv==2) {
                                                                            		echo number_format($fila['descuento_ven'], 2, ',', '.');
                                                                            	}
                                                                            	?>
                                                                            </td>
                                                                            <?php 
																			if ($fila['id_est_ven']==3) {
																				$monto_desestimiento = $fila['monto_ven'];
																				$monto_ven = 0;
																			} else {
                                                                            	$monto_desestimiento = 0;
                                                                            	$monto_ven = $fila['monto_ven'];
																			}
																			?>
                                                                            <td><?php echo number_format($monto_desestimiento, 2, ',', '.');?></td>
                                                                            <td>
                                                                            	<?php 
                                                                            	if ($estado_viv==2) {
                                                                            		echo number_format($monto_ven, 2, ',', '.');
                                                                            	} else {
                                                                            		echo number_format($fila['valor_viv'], 2, ',', '.');
                                                                            	}
                                                                            	?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        
                                                                    }
                                                                }
                                                                ?>   
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="20"></td>
                                                                    
                                                                    <td>$<?php echo number_format($acumulado_monto, 2, ',', '.'); ?></td>
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

      <!-- Main content -->
   	
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
  <!-- /.content-wrapper -->
<!-- .wrapper cierra en el footer -->
</body>
</html>
