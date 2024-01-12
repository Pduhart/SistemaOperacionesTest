<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
// require_once _INCLUDE."head_informe.php";

if(!isset($_SESSION["sesion_filtro_condominio_panel"])){
	$_SESSION["sesion_filtro_condominio_panel"] = 5;
}

$nombre = 'tubo_clientes'.date('d-m-Y');
$exc = $_GET['exc'];

if(isset($_GET['exc'])){
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment;filename=".$nombre.".xls");
}

?>
<!DOCTYPE html>
<html>
<head>
<title>TUBO CLIENTE</title>
<meta charset="utf-8">
<!-- DataTables -->
<!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>plugins/select2/select2.min.css"> -->
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
body{
	font-size: .8rem;
}

table{
	font-size: .75rem;
	border-collapse: collapse;
}

table.table {
	min-width: 140%;
}

table.table tr th{
	border: 1px solid #ccc;
}

table.table tr td{
	border: 1px solid #ebebeb;
}

table.table tfoot tr td{
	border: 1px solid #ccc;
}

.min-col{
	min-width: 90px;
}

</style>
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/datepicker/datepicker3.css"> -->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

if(!isset($_GET['exc'])){
?>
<a class="btn no-print" href="informe_tubo_plano.php?exc=1" target="_blank">Exporar Excel</a>
<?php
}
 ?>

<?php 
if(isset($_GET['exc'])){
 ?>
<table>
	<tr>
		<td></td>
		<td rowspan="6" colspan="3">
			<img src="<?php echo _ASSETS."img/logo-icp.jpg";?>" width="103" height="90">
		</td>
		<td></td>
		<td rowspan="6" colspan="2">
			<?php 
			$consulta = 
                "
                SELECT
                    nombre_doc_con
                FROM 
                    condominio_documento_condominio
                WHERE 
                    id_con = ? AND
                    nombre_doc_con LIKE '%logo%'
                ";
            $conexion->consulta_form($consulta,array($_SESSION["sesion_filtro_condominio_panel"]));
            $haylogo = $conexion->total();
            if ($haylogo==0) {
            	
            	
            } else{
            	$fila = $conexion->extraer_registro_unico();
            	$nombre_doc_con = $fila["nombre_doc_con"];
            	?>
            	<img src="<?php echo _RUTA."archivo/condominio/documento/".$_SESSION['sesion_filtro_condominio_panel']."/".$nombre_doc_con;?>" height="90">
            	<?php
            }
			 ?>
		</td>
	</tr>
</table>

<?php } ?>

<table id="example" class="table" cellspacing="0">
    <thead>
        <tr>
        	<th>N</th>
        	<th>Estado</th>
            <th>Depto</th>
            <th>Precio con Abono</th>
            <th>Precio Inmob.</th>
            <th>Cliente</th>
            <th class="min-col">RUT</th>
            <th>Email</th>
            <th>Fono</th>
            <th>Vendedor</th>
            <th>Vendido</th>
            <th>Banco</th>
            <th>Por Vender</th>
            <th>Crédito SI</th>
            <th>Crédito NO</th>
            <th>Por Escriturar</th>
            <th>Escrituradas</th>
            <th>Notaría</th>
            <th>N Repertorio</th>
            <th class="min-col">Fecha Envío Carta Oferta</th>
            <th>Mes Escrituración</th>
            <th class="min-col">Fecha Escritura</th>
            <th class="min-col">Fecha Entrega</th>
            <th>Ent. Contable</th>
            <th>CR Recepc.</th>
            <th>CR Aprobada</th>
            <th>Obs.</th>
        </tr> 
    </thead>
    <tbody>
        <?php
        $acumulado_monto_inmob = 0;
        $acumulado_monto_lista = 0;
        $contador_depto = 0;
        $contador_vendido_depto = 0;
        $contador_porvend_depto = 0;
        $contador_cre_depto = 0;
        $contador_nocre_depto = 0;
        $contador_escrit = 0;
        $contador_contab = 0;
        $contador_cr_rec = 0;
        $contador_cr_aprob = 0;
        $acumula_por_vender = 0;
        $acumula_uf_por_escriturar=0;
        $acumula_escriturado = 0;

        $consulta = 
            "
            SELECT 
                viv.id_viv,
                viv.nombre_viv,
                viv.valor_viv
            FROM 
                vivienda_vivienda AS viv
            WHERE 
                viv.id_viv > 0 AND
                viv.id_tor = ".$_SESSION["sesion_filtro_condominio_panel"]."
            ORDER BY 
                viv.id_viv
        "; 
        $conexion->consulta($consulta);
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
            	$id_viv = $fila['id_viv'];
            	$nombre_viv = utf8_encode($fila['nombre_viv']);
            	$valor_viv = $fila['valor_viv'];
            	$contador_depto++;

            	$estado_venta = "";
            	$fecha_envio_carta = "";
            	$a_contab = "";
            	$cr_aprobada = "";

            	// va a buscar las venta
            	$consulta_ven = 
                    "
                    SELECT 
                        ven.id_ven,
                        ven.fecha_ven,
                        ven.monto_ven,
                        ven.monto_vivienda_ven,
                        vend.id_vend,
                        vend.nombre_vend,
                        vend.apellido_paterno_vend,
                        vend.apellido_materno_vend,
                        pro.id_pro,
                        pro.nombre_pro,
                        pro.apellido_paterno_pro,
                        pro.apellido_materno_pro,
                        pro.rut_pro,
                        pro.correo_pro,
		                pro.fono_pro,
                        for_pag.id_for_pag,
                        for_pag.nombre_for_pag,
                        ven.descuento_ven,
                        ven.monto_estacionamiento_ven,
		                ven.monto_bodega_ven,
                        ven.monto_credito_ven,
                        ven.monto_credito_real_ven,
                        estado_venta.nombre_est_ven,
                        ven.id_ban,
                        ven.id_tip_pag,
                        ven.id_est_ven,
                        ven.fecha_escritura_ven
                    FROM 
                        venta_venta AS ven
                        INNER JOIN venta_estado_venta AS estado_venta ON estado_venta.id_est_ven = ven.id_est_ven
                        INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
                        INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                        INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                    WHERE 
                        ven.id_viv = ".$id_viv." AND ven.id_est_ven > 3
                    "; 
                // echo $consulta;
                // busca ventas activas de la viv 
                $conexion->consulta($consulta_ven);
                $tiene_venta = $conexion->total();
                if($tiene_venta>0) {
                	$contador_vendido_depto++;
                	$fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila_ven) {
                        	
                        	$notaria = "";

                        	$total_monto_inmob = ($fila_ven["monto_vivienda_ven"] + $fila_ven["monto_estacionamiento_ven"] + $fila_ven["monto_bodega_ven"]) - $fila_ven["descuento_ven"];

                            if ($fila_ven['fecha_escritura_ven']) {
                                $fecha_escritura = date("d-m-Y",strtotime($fila_ven['fecha_escritura_ven']));
                                $mes_escritura = date("n",strtotime($fila_ven['fecha_escritura_ven']));
                                $consulta_mes = 
                                    "
                                    SELECT 
                                        nombre_mes
                                    FROM
                                        mes_mes
                                    WHERE
                                        id_mes = ?";
                                $conexion->consulta_form($consulta_mes,array($mes_escritura));
                                $fila_nomfec = $conexion->extraer_registro_unico();
                                $mes_escritura = utf8_encode($fila_nomfec['nombre_mes']);
                                $estado_venta = "Escriturado";
                                $contador_escrit++;
                                
                                $estado_escritura = 1;
                            }
                            else{
                                $fecha_escritura = "";
                                $mes_escritura = "";
                                $estado_escritura = "";
                            }
                            $emite_cr=0;
                            if ($fila_ven['id_for_pag']==1) { //crédito

                            	// emisión CR
                            	
                            	$consulta_emision_cr = 
                                    "
                                    SELECT 
                                        id_eta_ven
                                    FROM
                                        venta_etapa_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 34 AND id_est_eta_ven = 1";
                                $conexion->consulta_form($consulta_emision_cr,array($fila_ven["id_ven"]));
                                $emite_cr = $conexion->total();
                                if($emite_cr===0){
                                	$emite_cr = "";
                                } else {
                                	$contador_cr_rec++;
                                }

                                // Envío Carta Oferta
			                                                                            	
                            	$consulta_envio_carta = 
                                    "
                                    SELECT 
                                        fecha_hasta_eta_ven
                                    FROM
                                        venta_etapa_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 24 AND id_est_eta_ven = 1";
                                $conexion->consulta_form($consulta_envio_carta,array($fila_ven["id_ven"]));
                                $envia_carta = $conexion->total();
                                if($envia_carta===0){
                                	$fecha_envio_carta = "";
                                } else {
                                	$fila_fecha_car = $conexion->extraer_registro_unico();
                                	$fecha_envio_carta = $fila_fecha_car['fecha_hasta_eta_ven'];
                                	 $fecha_envio_carta = date("d-m-Y",strtotime($fecha_envio_carta));
                                }

                                // Envío Contabilidad
                            	
                            	$consulta_env_contable = 
                                    "
                                    SELECT 
                                        id_eta_ven
                                    FROM
                                        venta_etapa_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 48 AND id_est_eta_ven = 1";
                                $conexion->consulta_form($consulta_env_contable,array($fila_ven["id_ven"]));
                                $a_contab = $conexion->total();
                                if($a_contab===0){
                                	$a_contab = "";
                                } else {
                                	$contador_contab++;
                                }


                                // CR AProbada
			                                                                            	
                            	$consulta_CR_aprobada = 
                                    "
                                    SELECT 
                                        id_eta_ven
                                    FROM
                                        venta_etapa_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 35 AND (id_est_eta_ven = 1 OR id_est_eta_ven = 2 OR id_est_eta_ven = 3)";
                                $conexion->consulta_form($consulta_CR_aprobada,array($fila_ven["id_ven"]));
                                $cr_aprobada = $conexion->total();
                                if($cr_aprobada===0){
                                	$cr_aprobada = "";
                                } else {
                                	$contador_cr_aprob++;
                                }


                            	// fecha entrega
                                $consulta_ent = 
                                    "
                                    SELECT 
                                        ven_eta.fecha_hasta_eta_ven
                                    FROM
                                        venta_etapa_venta AS ven_eta,
	                                    venta_etapa_campo_venta AS eta_cam_ven
                                    WHERE
                                        ven_eta.id_ven = ? AND 
                                        ven_eta.id_eta = 29 AND 
	                                	ven_eta.id_eta_ven = eta_cam_ven.id_eta_ven AND 
	                                    valor_campo_eta_cam_ven <> ''
                                    ";
                                $conexion->consulta_form($consulta_ent,array($fila_ven["id_ven"]));
                                $cantidad_fecha = $conexion->total();
                                if($cantidad_fecha > 0){
                                    $fila_fecha_ent = $conexion->extraer_registro_unico();
                                    if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
                                        $fecha_entrega = "";
                                    }
                                    else{
                                        $fecha_entrega = date("d-m-Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
                                    }
                                    
                                }
                                else{
                                    $fecha_entrega = "";
                                }

                                $consulta_notaria = 
                                    "
                                    SELECT 
                                        valor_campo_eta_cam_ven
                                    FROM
                                        venta_etapa_campo_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 27 AND id_cam_eta = 15";


                                // Repertorio
                                $consulta_repertorio = 
                                    "
                                    SELECT 
                                        valor_campo_eta_cam_ven
                                    FROM
                                        venta_etapa_campo_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 27 AND id_cam_eta = 33";
                            } else {

                            	// fecha entrega
                                $consulta_ent = 
                                    "
                                    SELECT 
                                        ven_eta.fecha_hasta_eta_ven
                                    FROM
                                        venta_etapa_venta AS ven_eta
                                    WHERE
                                        ven_eta.id_ven = ? AND 
                                        ven_eta.id_eta = 10
                                    ";
                                $conexion->consulta_form($consulta_ent,array($fila_ven["id_ven"]));
                                $cantidad_fecha = $conexion->total();
                                if($cantidad_fecha > 0){
                                    $fila_fecha_ent = $conexion->extraer_registro_unico();
                                    if ($fila_fecha_ent['fecha_hasta_eta_ven'] == '0000-00-00' || $fila_fecha_ent['fecha_hasta_eta_ven'] == null) {
                                        $fecha_entrega = "";
                                    }
                                    else{
                                        $fecha_entrega = date("d-m-Y",strtotime($fila_fecha_ent['fecha_hasta_eta_ven']));
                                    }
                                    
                                }
                                else{
                                    $fecha_entrega = "";
                                }

                            	$consulta_notaria = 
                                    "
                                    SELECT 
                                        valor_campo_eta_cam_ven
                                    FROM
                                        venta_etapa_campo_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 5 AND id_cam_eta = 2";

                                // Repertorio
                                $consulta_repertorio = 
                                    "
                                    SELECT 
                                        valor_campo_eta_cam_ven
                                    FROM
                                        venta_etapa_campo_venta
                                    WHERE
                                        id_ven = ? AND id_eta = 6 AND id_cam_eta = 1";
                            }
                            $conexion->consulta_form($consulta_notaria,array($fila_ven["id_ven"]));
                            $hay_not = $conexion->total();

                            $filarep = "";
				            $filanot = "";

				            $notaria = "";

                            if($hay_not){
                            	$filanot = $conexion->extraer_registro_unico();
                            	$notaria = utf8_encode($filanot['valor_campo_eta_cam_ven']);
                            }
                            

                            $conexion->consulta_form($consulta_repertorio,array($fila_ven["id_ven"]));
                            $hay_rep = $conexion->total();
                            $repertorio = "";
                            if($hay_rep){
                            	$filarep = $conexion->extraer_registro_unico();
                            	 $repertorio = utf8_encode($filarep['valor_campo_eta_cam_ven']);
                            }
                            
                           


                            // observaciones
                            $lista_obs = "";
                            $consulta_obs = 
                                    "
                                    SELECT 
                                        ven_obs.descripcion_obs_eta_ven,
                                        ven_obs.fecha_obs_eta_ven,
                                        usu.nombre_usu,
                                        usu.apellido1_usu
                                    FROM
                                        venta_observacion_etapa_venta AS ven_obs,
                                        usuario_usuario AS usu
                                    WHERE
                                        ven_obs.id_ven = ".$fila_ven["id_ven"]." AND
                                        ven_obs.id_usu = usu.id_usu
                                    ORDER BY
                                    	ven_obs.id_eta DESC";
                            $conexion->consulta($consulta_obs);
                            $tiene_obs = $conexion->total();
                            if($tiene_obs>0) {
                            	$fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $filaobs) {
                                    	$fecha_obs = $filaobs['fecha_obs_eta_ven'];
                                    	$fecha_obs = date("d-m-Y",strtotime($fecha_obs));
                                    	$nombre_usu_obs = utf8_encode($filaobs['nombre_usu']);
                                    	$apellido1_usu_obs = utf8_encode($filaobs['apellido1_usu']);
                                    	$descripcion_obs = utf8_encode($filaobs['descripcion_obs_eta_ven']);

                                    	$lista_obs .= "<b>".$fecha_obs."</b>: ".$descripcion_obs." (".$nombre_usu_obs." ".$apellido1_usu_obs.") / ";
                                    }
                                }
                            }

                            if ($fila_ven['id_for_pag']==1) {
								$consulta_banco = 
								  "
								  SELECT 
								    nombre_ban
								  FROM
								    banco_banco
								  WHERE
								  	id_ban = ".$fila_ven["id_ban"]."
								  ";
								$conexion->consulta($consulta_banco);
								$tiene_ban = $conexion->total();
								if($tiene_ban){
									$filaban = $conexion->extraer_registro_unico();
								}
								$nombre_ban = utf8_encode($filaban["nombre_ban"]);

								// ver credito si no
								// buscar si está la fecha aprobación en la etapa 2
								$consulta_aprobacion_cre = 
                                    "
                                    SELECT 
                                        valor_campo_eta_cam_ven
                                    FROM
                                        venta_etapa_campo_venta
                                    WHERE
                                        id_ven = ".$fila_ven["id_ven"]." AND id_eta = 23 AND id_cam_eta = 11";
                                $conexion->consulta($consulta_aprobacion_cre);
                                $tiene_aprob = $conexion->total();
                                if($tiene_aprob){
                                	$filaacre = $conexion->extraer_registro_unico();
                                }
								$fecha_aprobacion_cre = $filaacre["valor_campo_eta_cam_ven"];
								if($fecha_aprobacion_cre) {
									if ($estado_venta==="") {
										$estado_venta = "Aprobado";
										$contador_cre_depto++;
									}
									
									$cre_si = "1";
									
                        	 		$cre_no = "";
								} else {
									if ($estado_venta==="") {
										$estado_venta = "Evaluación";
									}
									$cre_si = "";
                        	 		$cre_no = "1";
                        	 		$contador_nocre_depto++;
								}

							} else {
                        	 	$nombre_ban = "CONTADO";
                        	 	$cre_si = "";
                        	 	$cre_no = "";
                        	 	if ($estado_venta==="") {
									$estado_venta = "Aprobado";
								}
							}



							if ($estado_escritura === 1) {
								$cre_si = "";
                        	 	$cre_no = "";
                        	 	$acumula_escriturado = $acumula_escriturado + $total_monto_inmob;
							} else {
								$acumula_uf_por_escriturar = $acumula_uf_por_escriturar + $total_monto_inmob;
							}

							// $acumulado_monto = $acumulado_monto + $valor_viv;
							$valor_final_inmob = $total_monto_inmob;

							$acumulado_monto_lista = $acumulado_monto_lista + $valor_viv;
							$acumulado_monto_inmob = $acumulado_monto_inmob + $valor_final_inmob;

							if($emite_cr===0){
								$emite_cr="";
							}
                            ?>
                            <tr>
                            	<td><?php echo $contador_depto; ?></td>
                            	<td><?php echo $estado_venta; ?></td>
                                <td><?php echo $nombre_viv; ?></td>
                                <td><?php echo $valor_viv; ?></td>
                                <td><?php echo $valor_final_inmob; ?></td>
                                <td style="text-align: left;"><?php echo utf8_encode($fila_ven['nombre_pro']." ".$fila_ven['apellido_paterno_pro']." ".$fila_ven['apellido_materno_pro']); ?></td>
                                <td><?php echo utf8_encode($fila_ven['rut_pro']); ?></td>
                                <td><?php echo utf8_encode($fila_ven['correo_pro']); ?></td>
		                        <td><?php echo utf8_encode($fila_ven['fono_pro']); ?></td>
                                <td style="text-align: left;"><?php echo utf8_encode($fila_ven['nombre_vend']." ".$fila_ven['apellido_paterno_vend']." ".$fila_ven['apellido_materno_vend']); ?></td>
                                <td>1</td>
                                <td><?php echo $nombre_ban;?></td>
                                <td></td>
                                <td><?php echo $cre_si; ?></td>
                                <td><?php echo $cre_no; ?></td>
                                <td><?php echo $estado_escritura ? '' : 1 ?></td>
                                <td><?php echo $estado_escritura; ?></td>
                                <td><?php echo $notaria ?></td>
                                <td><?php echo $repertorio; ?></td>
                                <td><?php echo $fecha_envio_carta; ?></td>
                                <td><?php echo $mes_escritura; ?></td>
                                <td><?php echo $fecha_escritura; ?></td>
                                <td><?php echo $fecha_entrega; ?></td>
                                <td><?php echo $a_contab; ?></td>
                                <td><?php echo $emite_cr; ?></td>
                                <td><?php echo $cr_aprobada; ?></td>
                                <td><?php echo $lista_obs; ?></td>
                            </tr>
                         <?php
                        }
                    }
                } else {

                	$consulta_par = 
                        "
                        SELECT 
                            valor_par
                        FROM
                            parametro_parametro
                        WHERE
                            id_con = ? AND valor2_par = 4";
                    $conexion->consulta_form($consulta_par,array($_SESSION["sesion_filtro_condominio_panel"]));
                    $fila_par = $conexion->extraer_registro_unico();
                    $descuento_unidad = $fila_par['valor_par'];

                    $valor_depto_novendido = $valor_viv - ($valor_viv * $descuento_unidad/100);

                	// cuando está disponible
                	$acumula_por_vender = $acumula_por_vender + round($valor_depto_novendido);

                	// igual lo suma a columna 5
                	$acumulado_monto_inmob = $acumulado_monto_inmob + round($valor_depto_novendido);

                	$contador_porvend_depto++;
               			?>
                           	<tr>
                           		<td><?php echo $contador_depto; ?></td>
                           		<td>Disponible</td>
                           		<td><?php echo $nombre_viv; ?></td>
                           		<td></td>
                           		<td><?php echo round($valor_depto_novendido); ?></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td>1</td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           		<td></td>
                           	</tr>
               	<?php
                }
            }
        }
        
        ?>   
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><?php //echo $acumulado_monto_lista; ?></td>
            <td><?php echo $acumulado_monto_inmob; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $contador_vendido_depto; ?></td>
            <td></td>
            <td><?php echo $contador_porvend_depto; ?></td>
            <td><?php echo $contador_cre_depto; ?></td>
            <td><?php echo $contador_nocre_depto; ?></td>
            <td><?php echo $contador_depto - $contador_escrit - $contador_porvend_depto; ?></td>
            <td><?php echo $contador_escrit; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo $contador_contab; ?></td>
            <td><?php echo $contador_cr_rec; ?></td>
            <td><?php echo $contador_cr_aprob; ?></td>
            <td></td>
        </tr> 
    </tfoot>

</table>

<br><br>

<!-- <table>
	<tr>
		<td></td>
		<td><?php //echo $acumula_por_vender; ?></td>
		<td>Por Vender</td>
	</tr>
	<tr>
		<td></td>
		<td><?php //echo $acumula_escriturado; ?></td>
		<td>Escriturado</td>
	</tr>
	<tr>
		<td></td>
		<td><?php //echo $acumulado_monto - $acumula_escriturado - $acumula_por_vender; ?></td>
		<td>Por Escriturar</td>
	</tr>
	<tr>
		<td></td>
		<td><?php //echo $acumulado_monto; ?></td>
		<td>Total</td>
	</tr>
</table> -->


<table>
	<tr>
		<td></td>
		<td colspan="6">
			<table style="background-color: #e7e7d4; border: 2px solid #000000; border-collapse: collapse;">
				<tr>
					<td colspan="6" style="background-color: #fff15a; text-align: center; border-bottom: 2px solid #000000;">RESUMEN</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;">Por Vender</td>
					<td colspan="2" style="text-align: center;">Escriturado</td>
					<td colspan="2" style="text-align: center;">Por Escriturar</td>
				</tr>
				<tr>
					<td style="text-align: center;">UF</td>
					<td style="text-align: center;">Unidades</td>
					<td style="text-align: center;">UF</td>
					<td style="text-align: center;">Unidades</td>
					<td style="text-align: center;">UF</td>
					<td style="text-align: center;">Unidades</td>
				</tr>
				<tr>
					<td style="text-align: center;"><?php echo $acumula_por_vender; ?></td>
					<td style="text-align: center;"><?php echo $contador_porvend_depto; ?></td>
					<td style="text-align: center;"><?php echo $acumula_escriturado; ?></td>
					<td style="text-align: center;"><?php echo $contador_escrit; ?></td>
					<td style="text-align: center;"><?php echo $acumula_uf_por_escriturar; ?></td>
					<td style="text-align: center;"><?php echo $contador_depto - $contador_escrit - $contador_porvend_depto; ?></td>
				</tr>
				<tr style="border: 2px solid #000000;">
					<td colspan="5" style="text-align: center;">TOTAL UNIDADES</td>
					<td style="text-align: center;"><?php echo $contador_depto; ?></td>
				</tr>
				<!-- <tr style="border: 2px solid #000000;">
					<td colspan="5" style="text-align: center;">TOTAL UF</td>
					<td style="text-align: center;"><?php //echo $acumulado_monto; ?></td>
				</tr> -->
			</table>
		</td>
	</tr>
</table>

  <!-- /.content-wrapper -->
<!-- .wrapper cierra en el footer -->


</body>
</html>
