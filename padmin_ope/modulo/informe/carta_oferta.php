<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'carta_oferta'.date('d-m-Y');
$id_ven = $_GET['id'];
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
<style>
	@media print
		{    
		    .no-print, .no-print *
		    {
		        display: none !important;
		    }
		}
</style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
	<a class="btn no-print" href="carta_oferta_exc.php?id=<?php echo $id_ven; ?>" target="_blank">Excel</a>
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

                                        <div class="col-md-12">
                                            <div class="row" id="contenedor_tabla">
                                                <div class="box">                                                    
                                                    <!-- /.box-header -->
                                                    <div class="box-body" style="overflow-x: hidden;">
                                                    	<?php
														$consulta = 
                                                            "
                                                            SELECT 
                                                                ven.id_ven,
                                                                ven.fecha_ven,
                                                                ven.monto_ven,
                                                                ven.id_for_pag,
                                                                ven.id_ban,
                                                                ven.id_tip_pag,
                                                                ven.descuento_ven,
                                                                ven.monto_credito_ven,
                                                                ven.monto_credito_real_ven,
                                                                ven.monto_vivienda_ingreso_ven,
                                                                viv.id_viv,
                                                                viv.nombre_viv,
                                                                viv.rol_viv,
                                                                mode.id_mod,
                                                                mode.nombre_mod,
                                                                con.id_con,
                                                                con.nombre_con,
                                                                pro.id_pro,
                                                                pro.nombre_pro,
                                                                pro.apellido_paterno_pro,
                                                                pro.apellido_materno_pro,
                                                                pro.rut_pro,
                                                                -- pie.valor_pie_ven,
                                                                estado_venta.nombre_est_ven,
                                                                ban.nombre_ban,
                                                                ven.id_pie_abo_ven
                                                            FROM 
                                                                venta_venta AS ven
                                                                INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
                                                                -- LEFT JOIN venta_pie_venta AS pie ON pie.id_pie_ven = ven.id_pie_ven
                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                                                                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                                LEFT JOIN banco_banco AS ban ON ban.id_ban = ven.id_ban
                                                            WHERE 
                                                                ven.id_ven = ".$id_ven."
                                                            "; 
                                                        $conexion->consulta($consulta);
                                                        $fila = $conexion->extraer_registro_unico();
                                                        $nombre_cliente = utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);
                                                        $rut_pro = utf8_encode($fila['rut_pro']);
                                                        $nombre_con = utf8_encode($fila['nombre_con']);
                                                        $nombre_viv = utf8_encode($fila['nombre_viv']);
                                                        $rol_viv = utf8_encode($fila['rol_viv']);
														$fecha_venta = date("d/m/Y",strtotime($fila['fecha_ven']));
														$id_for_pag = $fila['id_for_pag'];
														$id_viv = $fila['id_viv'];
														$id_con = $fila['id_con'];
														$monto_credito_ven = $fila['monto_credito_ven'];
														$monto_credito_real_ven = $fila['monto_credito_real_ven'];
														$monto_ven = $fila['monto_ven'];
														$monto_vivienda_ingreso_ven = $fila['monto_vivienda_ingreso_ven'];
														$id_pie_abo_ven = $fila['id_pie_abo_ven'];

														if ($monto_credito_real_ven<>null && $monto_credito_real_ven<>0) {
															$monto_pie = $monto_ven - $monto_credito_real_ven;
														} else {
															$monto_pie = $monto_ven - $monto_credito_ven;
															$monto_credito_real_ven = $monto_credito_ven;
														}

														// if ($id_pie_abo_ven==1) {
														// 	$monto_ven = $monto_ven + $fila["descuento_ven"];
														// 	$monto_pie = $monto_pie + $fila["descuento_ven"];
														// }

														$consulta_alzante = 
													        "
													        SELECT
													            valor_par
													        FROM
													            parametro_parametro
													        WHERE
													            valor2_par = ? AND
													            id_con = ?
													        ";
													    $conexion->consulta_form($consulta_alzante,array(28,$id_con));
													    $filapar = $conexion->extraer_registro_unico();
													    $banco_alzante_nom = utf8_encode($filapar['valor_par']);
														// echo $banco_alzante_nom;
													    $consulta_direccion = 
													        "
													        SELECT
													            valor_par
													        FROM
													            parametro_parametro
													        WHERE
													            valor2_par = ? AND
													            id_con = ?
													        ";
													    $conexion->consulta_form($consulta_direccion,array(27,$id_con));
													    $filapar = $conexion->extraer_registro_unico();
													    $direccion_con = utf8_encode($filapar['valor_par']);
														


														if ($banco_alzante_nom<>'') {
															$banco_alzante_no = "";
															$banco_alzante = $banco_alzante_nom;
														} else {
															$banco_alzante_no = "X";
															$banco_alzante = "";
														}

														$consulta_esta = 
											                "
											                SELECT
											                    nombre_esta,
											                    valor_esta
											                FROM
											                    estacionamiento_estacionamiento
											                WHERE
											                    id_viv = ?
											                ";
											            $conexion->consulta_form($consulta_esta,array($id_viv));
											            $fila_consulta = $conexion->extraer_registro();
											            $cantidad = $conexion->total();
											            if(is_array($fila_consulta)){
											                foreach ($fila_consulta as $filaest) {
											                	$nombre_esta .= utf8_encode($filaest['nombre_esta'])." Y ";
											                	$valor_esta .= $filaest['valor_esta'];
											                }
											            }

											            $consulta_bod = 
											                "
											                SELECT
											                    nombre_bod,
											                    valor_bod
											                FROM
											                    bodega_bodega
											                WHERE
											                    id_viv = ?
											                ";
											            $conexion->consulta_form($consulta_bod,array($id_viv));
											            $fila_consulta = $conexion->extraer_registro();
											            $cantidad = $conexion->total();
											            if(is_array($fila_consulta)){
											                foreach ($fila_consulta as $filabod) {
											                	$nombre_bod .= utf8_encode($filabod['nombre_bod'])." Y ";
											                	$valor_bod .= $filabod['valor_bod'];
											                }
											            }

											            $consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
														$conexion->consulta_form($consulta,array($n_valor_esta,$id_con));
														$fila = $conexion->extraer_registro_unico();
														$valor_esta_inc = $fila["valor_par"];

														$consulta = "SELECT valor_par FROM parametro_parametro WHERE valor2_par = ? AND id_con = ?";
														$conexion->consulta_form($consulta,array($n_valor_bod,$id_con));
														$fila = $conexion->extraer_registro_unico();
														$valor_bod_inc = $fila["valor_par"];

														$valor_bod = $valor_bod + $valor_bod_inc;
														$valor_esta = $valor_esta + $valor_esta_inc;

														$monto_vivienda_ingreso_ven = $monto_vivienda_ingreso_ven - $valor_bod_inc - $valor_esta_inc;
														$monto_ven_ven = $monto_ven - $valor_bod_inc - $valor_esta_inc;

											            $consulta_rol_bod = 
											                "
											                SELECT
											                    rol_bod
											                FROM
											                    bodega_bodega
											                WHERE
											                    id_viv = ? AND
											                    valor_bod = 0
											                ";
											            $conexion->consulta_form($consulta_rol_bod,array($id_viv));
											            $filarolbod = $conexion->extraer_registro_unico();
														$rol_bod = utf8_encode($filarolbod['rol_bod']);

														$nombre_bod = substr($nombre_bod, 0, -2);
														$nombre_esta = substr($nombre_esta, 0, -2);
														$valor_esta = number_format($valor_esta, 2, ',', '.');
														$valor_bod = number_format($valor_bod, 2, ',', '.');
														$monto_credito_real_ven = number_format($monto_credito_real_ven, 2, ',', '.');
														$monto_ven = number_format($monto_ven, 2, ',', '.');
														$monto_pie = number_format($monto_pie, 2, ',', '.');
														$monto_vivienda_ingreso_ven = number_format($monto_vivienda_ingreso_ven, 2, ',', '.');
														$monto_ven_ven = number_format($monto_ven_ven, 2, ',', '.');

														if ($id_for_pag==2) { //contado
                                                    		$consulta_escr = 
                                                                "
                                                                SELECT 
                                                                    eta_cam.valor_campo_eta_cam_ven
                                                                FROM
                                                                    venta_etapa_campo_venta AS eta_cam
                                                                WHERE
                                                                    eta_cam.id_ven = ? AND 
                                                                    eta_cam.id_eta = 5 AND 
                                                                    eta_cam.id_cam_eta = 2
                                                                ";
                                                            $conexion->consulta_form($consulta_escr,array($id_ven));
                                                            $cantidad_not = $conexion->total();
                                                            if($cantidad_not > 0){
                                                                $fila_not = $conexion->extraer_registro_unico();
                                                                $notaria = utf8_encode($fila_not['valor_campo_eta_cam_ven']);
                                                                
                                                            }
                                                            else{
                                                                $notaria = "";
                                                            }
                                                    	} else { // si es crédito

                                                    		$consulta_escr = 
                                                                "
                                                                SELECT 
                                                                    eta_cam.valor_campo_eta_cam_ven
                                                                FROM
                                                                    venta_etapa_campo_venta AS eta_cam
                                                                WHERE
                                                                    eta_cam.id_ven = ? AND 
                                                                    eta_cam.id_eta = ".$n_etacr_firma_esc_cliente." AND 
                                                                    eta_cam.id_cam_eta = ".$n_cam_etacr_notaria_etafirma."
                                                                ";
                                                            $conexion->consulta_form($consulta_escr,array($id_ven));
                                                            $cantidad_not = $conexion->total();
                                                            if($cantidad_not > 0){
                                                                $fila_not = $conexion->extraer_registro_unico();
                                                                $notaria = utf8_encode($fila_not['valor_campo_eta_cam_ven']);
                                                                
                                                            }
                                                            else{
                                                            	$consulta_escr = 
	                                                                "
	                                                                SELECT 
	                                                                    eta_cam.valor_campo_eta_cam_ven
	                                                                FROM
	                                                                    venta_etapa_campo_venta AS eta_cam
	                                                                WHERE
	                                                                    eta_cam.id_ven = ? AND 
	                                                                    eta_cam.id_eta = ".$n_etacr_etapa_1." AND 
	                                                                    eta_cam.id_cam_eta = ".$n_cam_etacr_notaria_eta1."
	                                                                ";
		                                                            $conexion->consulta_form($consulta_escr,array($id_ven));
		                                                            $cantidad_not = $conexion->total();
		                                                            if($cantidad_not > 0){
		                                                                $fila_not = $conexion->extraer_registro_unico();
		                                                                $notaria = utf8_encode($fila_not['valor_campo_eta_cam_ven']);
		                                                                
		                                                            } else {
		                                                            	$notaria = "";
		                                                            }
                                                            }

                                                        } 

                                                        if ($id_con==1) {
													    	$logo = "logo-empresa.jpg";
													    	$nombre_empresa = "Inmobiliaria Cordillera SPA";
													    } else {
													    	$logo = "logo-icp.jpg";
													    	$nombre_empresa = "Inmobiliaria Costanera Pacífico SpA";
													    }

                                                        ?>

														<table>
															<tr>
																<td>Nombre Titular del Crédito</td>
																<td>:</td>
																<td colspan="4"><?php echo $nombre_cliente; ?></td>
															</tr>
															<tr>
																<td>RUT</td>
																<td>:</td>
																<td colspan="4"><?php echo $rut_pro; ?></td>
															</tr>
															<tr>
																<td colspan="7"></td>
															</tr>
															<tr>
																<td>PROYECTO</td>
																<td>:</td>
																<td colspan="4"><?php echo $nombre_con; ?></td>
															</tr>
															<tr>
																<td>ETAPA</td>
																<td>:</td>
																<?php 
																if ($id_con==5) {
																	?>
																	<td colspan="4">ETAPA II</td>
																	<?php
																} 
																else if ($id_con == 6) {
																	?>
																	<td colspan="4">ETAPA I</td>
																	<?php
																}
																else if ($id_con == 7) {
																	?>
																	<td colspan="4">ETAPA II</td>
																	<?php
																}
																else if ($id_con == 8) {
																	?>
																	<td colspan="4">ETAPA I</td>
																	<?php
																}
																else {
																 ?>
																 <td colspan="4">NO APLICA</td>
																<?php
																 } ?>
																
															</tr>
															<tr>
																<td>DIRECCIÓN DE INMUEBLE</td>
																<td>:</td>
																<td colspan="4"><?php echo $direccion_con; ?> LA SERENA</td>
															</tr>
															<tr>
																<td colspan="6"></td>
															</tr>
															<tr>
																<td>RAZÓN SOCIAL INMOBILIARIA</td>
																<td>:</td>
																<td colspan="4"><?php echo $nombre_empresa; ?></td>
															</tr>
															<tr>
																<td>RUT</td>
																<td>:</td>
																<td colspan="4">76.866.075-1</td>
															</tr>
															<tr>
																<td>BANCO ALZANTE</td>
																<td>:</td>
																<td>SI: <?php echo $banco_alzante; ?></td>
																<td></td>
																<td>NO: <?php echo $banco_alzante_no; ?></td>
																<td></td>
															</tr>
															<tr>
																<td colspan="6" style="background-color: #FFFABC;">De no haber Banco Acreedor informar donde se debe efectuar el pago de este credito</td>
															</tr>
															<tr>
																<td style="background-color: #FFFABC;">Banco a depositar</td>
																<td style="background-color: #FFFABC;">:</td>
																<td style="background-color: #FFFABC;"></td>
																<td style="background-color: #FFFABC;">Cta.Cte.N°</td>
																<td style="background-color: #FFFABC;">:</td>
																<td style="background-color: #FFFABC;"></td>
															</tr>
															<tr>
																<td>Vivienda Social</td>
																<td>:</td>
																<td>SI:</td>
																<td></td>
																<td>NO:</td>
																<td>NO</td>
															</tr>
															<tr>
																<td>NRO. DEPARTAMENTO Y/O CASA</td>
																<td>:</td>
																<td><?php echo $nombre_viv; ?></td>
																<td>UF Depto. o Casa</td>
																<td>:</td>
																<td><?php echo $monto_ven_ven; ?></td>
															</tr>
															<tr>
																<td>ROL</td>
																<td>:</td>
																<td></td>
																<td><?php echo $rol_viv; ?></td>
																<td></td>
																<td></td>
															</tr>
															<tr>
																<td>NRO. ESTACIONAMIENTO</td>
																<td>:</td>
																<td><?php echo $nombre_esta; ?></td>
																<td>UF Etac.</td>
																<td>:</td>
																<td><?php echo $valor_esta; ?></td>
															</tr>
															<tr>
																<td>ROL</td>
																<td>:</td>
																<td colspan="4">USO Y GOCE</td>
															</tr>
															<tr>
																<td>NRO. BODEGA</td>
																<td>:</td>
																<td><?php echo $nombre_bod; ?></td>
																<td>UF Bodega</td>
																<td>:</td>
																<td><?php echo $valor_bod; ?></td>
															</tr>
															<tr>
																<td>ROL</td>
																<td>:</td>
																<td></td>
																<td><?php echo $rol_bod; ?></td>
																<td></td>
																<td></td>
															</tr>
															<tr>
																<td style="background-color: #FFFABC;">PRECIO DE VENTA</td>
																<td style="background-color: #FFFABC;">:</td>
																<td style="background-color: #FFFABC;">UF</td>
																<td style="background-color: #FFFABC;" colspan="3"><?php echo $monto_ven; ?></td>
															</tr>
															<tr>
																<td>RECURSOS PROPIOS(PIE)</td>
																<td>:</td>
																<td>UF</td>
																<td colspan="3"><?php echo $monto_pie; ?></td>
															</tr>
															<tr>
																<td>SUBSIDIO</td>
																<td>:</td>
																<td>UF</td>
																<td colspan="3">0</td>
															</tr>
															<tr>
																<td>AHORRO PREVIO</td>
																<td>:</td>
																<td>UF</td>
																<td colspan="3">0</td>
															</tr>
															<tr>
																<td>MONTO CREDITO</td>
																<td>:</td>
																<td>UF</td>
																<td colspan="3"><?php echo $monto_credito_real_ven; ?></td>
															</tr>
															<tr>
																<td colspan="6"></td>
															</tr>
															<tr>
																<td>QUIEN CANCELA GASTOS OPERACIONALES</td>
																<td>:</td>
																<td colspan="4"><?php echo $nombre_cliente; ?></td>
															</tr>
															<tr>
																<td style="background-color: #FFFABC;">EN QUE NOTARIA FIRMA ESCRITURA</td>
																<td style="background-color: #FFFABC;">:</td>
																<td style="background-color: #FFFABC;" colspan="4"><?php echo $notaria; ?></td>
															</tr>
															<tr>
																<td>OBSERVACIONES</td>
																<td>:</td>
																<td colspan="4">Proyecto Afecto a IVA, incluido en el precio.</td>
															</tr>
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
