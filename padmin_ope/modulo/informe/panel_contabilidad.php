<!-- Small boxes (Stat box) -->
<style>
  .widget-user-2 .widget-user-header{
        /* padding: 1px; */
     border-top-right-radius: 0; 
     border-top-left-radius: 0; 
    padding-top: 5px;
    padding-bottom: 1px;
    padding-left: 15px;
  }

  .box{
    margin-bottom: 4px;
  }

  .content{
    padding-top: 0px;
  }

  .box h3{
    margin-top: 4px;
  }
    h3{
  	line-height: 21px;
  }

  h3 small{
  	font-size: 1.3rem;
  	line-height: 8px;
  }
</style>
<?php 
include _INCLUDE."class/dias.php"; 
$hoy = date("Y-m-d");
$mes_actual = date("m");
$anio_actual = date("Y");


$cheque_numero_mes = 0;
$cheque_numero_hoy = 0;

$departamento_numero = 0;
$departamento_numero_vendido = 0;
$estacionamiento_numero = 0;
$estacionamiento_numero_vendido = 0;
$bodega_numero = 0;
$bodega_numero_vendido = 0;

$monto_total_venta = 0;
$acumulado_atrasado_etapa = 0;



$fila_consulta_ingreso = array();

$consulta = 
  "
  SELECT 
    id_viv
  FROM
    vivienda_vivienda
  WHERE
    id_est_viv = 2
  ";
$conexion->consulta($consulta);
$total_unidades_vendidas = $conexion->total();

//---------------------- TOTAL ESCRITURACIÓN
// condo 1
// $consulta_ventas_inciadas_oopp_1 = 
//   "
//   SELECT 
//     ven.id_ven
//   FROM
//     venta_venta as ven,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 1 AND EXISTS(
//         SELECT 
//             ven_eta.id_ven
//         FROM
//             venta_etapa_venta AS ven_eta
//         WHERE
//             ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
//     )";
// $conexion->consulta($consulta_ventas_inciadas_oopp_1);
// $total_unidades_en_oopp_1 = $conexion->total();

// // condo 4
// $consulta_ventas_inciadas_oopp_4 = 
//   "
//   SELECT 
//     ven.id_ven
//   FROM
//     venta_venta as ven,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 4 AND EXISTS(
//         SELECT 
//             ven_eta.id_ven
//         FROM
//             venta_etapa_venta AS ven_eta
//         WHERE
//             ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
//     )";
// $conexion->consulta($consulta_ventas_inciadas_oopp_4);
// $total_unidades_en_oopp_4 = $conexion->total();

// // condo 5
// $consulta_ventas_inciadas_oopp_5 = 
//   "
//   SELECT 
//     ven.id_ven
//   FROM
//     venta_venta as ven,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 5 AND EXISTS(
//         SELECT 
//             ven_eta.id_ven
//         FROM
//             venta_etapa_venta AS ven_eta
//         WHERE
//             ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
//     )";
// $conexion->consulta($consulta_ventas_inciadas_oopp_5);
// $total_unidades_en_oopp_5 = $conexion->total();

// total escrituras
$consulta_ventas_escrituras = 
  "
  SELECT 
    id_ven
  FROM
    venta_venta
  WHERE
    (id_est_ven = 6 or id_est_ven = 7)
  ";
$conexion->consulta($consulta_ventas_escrituras);
$total_ventas_escrituras = $conexion->total();

// unidades que pasaron etapa de factura
// $consulta_facturadas = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_ven = ven_eta.id_ven AND
//     ((ven_eta.id_eta=16 AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=39 AND ven_eta.id_est_eta_ven=1))
//   ";
// $conexion->consulta($consulta_facturadas);
// $total_unidades_facturadas = $conexion->total();




//------ TOTALES ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(monto_ven),0) AS total
  FROM
    venta_venta
  WHERE
    id_est_ven IN (6,7)
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta = $fila["total"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(monto_ven),0) AS total
  FROM
    venta_venta
  WHERE
    id_est_ven IN (6,7) AND
    MONTH(fecha_ven) = '".$mes_actual."' AND
    YEAR(fecha_ven) = '".$anio_actual."'
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta_mes_actual = $fila["total"];



$consulta = 
  "
  SELECT 
    IFNULL(SUM(valor_viv),0) AS total
  FROM
    cotizacion_cotizacion AS cot
    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
  WHERE
    cot.id_est_cot IN (1,4,5,6,7)
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_cotizacion = $fila["total"];

//------ CHEQUES ------
// $consulta = 
//   "
//   SELECT 
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 1) THEN pag.monto_pag ELSE 0 END),0) AS realizado,
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 2) THEN pag.monto_pag ELSE 0 END),0) AS pendiente,
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 3) THEN pag.monto_pag ELSE 0 END),0) AS protestado  
//   FROM
//     pago_pago AS pag,
//     venta_venta AS ven
//   WHERE
//   	pag.id_ven = ven.id_ven AND
//   	ven.id_est_ven <> 3 AND
//     (pag.id_for_pag = 4 OR
//     pag.id_for_pag = 8)
//   ";
// $conexion->consulta($consulta);
// $fila = $conexion->extraer_registro_unico();
// $cheque_realizado = $fila["realizado"];
// $cheque_pendiente = $fila["pendiente"];
// $cheque_protestado = $fila["protestado"];

// $consulta = 
//   "
//   SELECT 
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 1) THEN pag.monto_pag ELSE 0 END),0) AS realizado,
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 2) THEN pag.monto_pag ELSE 0 END),0) AS pendiente,
//     IFNULL(SUM(CASE WHEN (pag.id_est_pag = 3) THEN pag.monto_pag ELSE 0 END),0) AS protestado  
//   FROM
//     pago_pago AS pag,
//     venta_venta AS ven
//   WHERE
//   	pag.id_ven = ven.id_ven AND
//   	ven.id_est_ven <> 3 AND
//     pag.id_for_pag = 3
//   ";
// $conexion->consulta($consulta);
// $fila = $conexion->extraer_registro_unico();
// $transferencia_realizado = $fila["realizado"];
// $transferencia_pendiente = $fila["pendiente"];
// $transferencia_protestado = $fila["protestado"];


$consulta = 
  "
  SELECT 
    pag.id_pag
  FROM
    pago_pago AS pag,
    venta_venta AS ven
  WHERE
  	pag.id_ven = ven.id_ven AND
  	ven.id_est_ven <> 3 AND
    pag.id_for_pag = 4 AND
    pag.id_est_pag = 2 AND
    MONTH(pag.fecha_pag) = '".$mes_actual."' AND
    YEAR(pag.fecha_pag) = '".$anio_actual."'
  ";
$conexion->consulta($consulta);
$cheque_numero_mes = $conexion->total();


$consulta = 
  "
  SELECT 
    pag.id_pag
  FROM
    pago_pago AS pag,
    venta_venta AS ven
  WHERE
  	pag.id_ven = ven.id_ven AND
  	ven.id_est_ven <> 3 AND
    pag.id_for_pag = 4 AND
    pag.id_est_pag = 2 AND
    pag.fecha_pag = ".$hoy."
  ";
$conexion->consulta($consulta);
$cheque_numero_hoy = $conexion->total();

//------ DEPARTAMENTO / ESTACIONAMIENTO / BODEGA ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_viv > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_viv = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    vivienda_vivienda
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$departamento_numero = $fila["total"];
$departamento_numero_vendido = $fila["total_vendido"];


$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_esta > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_esta = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    estacionamiento_estacionamiento
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$estacionamiento_numero = $fila["total"];
$estacionamiento_numero_vendido = $fila["total_vendido"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_bod > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_bod = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    bodega_bodega
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$bodega_numero = $fila["total"];
$bodega_numero_vendido = $fila["total_vendido"];


//------- OPERACION --------
$consulta = 
    "
    SELECT
        viv.id_viv,
        eta_ven.fecha_desde_eta_ven,
        eta.duracion_eta
    FROM
        torre_torre AS tor
        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
        INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv
        INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
        INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
    WHERE
        eta_ven.id_est_eta_ven = ? AND
        ven.id_est_ven IN (1,4,5,6)
    ";
$conexion->consulta_form($consulta,array(2));
$fila_consulta_estado = $conexion->extraer_registro();
if(is_array($fila_consulta_estado)){
    foreach ($fila_consulta_estado as $fila_estado) {
        $fecha_inicio = $fila_estado["fecha_desde_eta_ven"];
        $duracion = $fila_estado["duracion_eta"];
        $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
        // $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
        $fecha_final = add_business_days($fecha_inicio,$duracion,$holidays,'Y-m-d');
        if($fecha_final < $hoy){
            $acumulado_atrasado_etapa = $acumulado_atrasado_etapa + 1;
        }
    }
}

// cantidad de operaciones contado en etapa fondos
$consulta_gop = 
  "
  SELECT 
    ven_eta.id_ven
  FROM
    venta_etapa_venta AS ven_eta,
    venta_venta AS ven
  WHERE
    ven_eta.id_eta = ".$n_etaco_fondos_gastos." AND
    ven_eta.id_est_eta_ven = 2 AND
    (ven_eta.fecha_hasta_eta_ven is null OR ven_eta.fecha_hasta_eta_ven = '') AND 
    ven_eta.id_ven = ven.id_ven AND 
    ven.id_est_ven > 3";
$conexion->consulta($consulta_gop);
$acumulado_etapa_gastos = $conexion->total();

// cantidad de operaciones en alzamiento
$consulta_alza = 
  "
  SELECT 
    id_ven
  FROM
    venta_etapa_venta
  WHERE
    (id_eta = 35 or id_eta = 14) AND
    id_est_eta_ven = 2 AND
    (fecha_hasta_eta_ven is null or fecha_hasta_eta_ven = '')
  ";
$conexion->consulta($consulta_alza);
$acumulado_etapa_alzamiento = $conexion->total();

// cantidad de operaciones en alzamiento
$consulta_CRE = 
  "
  SELECT 
    id_ven
  FROM
    venta_etapa_venta
  WHERE
    (id_eta = 33) AND
    id_est_eta_ven = 2 AND
    (fecha_hasta_eta_ven is null or fecha_hasta_eta_ven = '')
  ";
$conexion->consulta($consulta_CRE);
$acumulado_etapa_CRE = $conexion->total();

// cantidad de operaciones en preinforme
$consulta_preinf = 
  "
  SELECT 
    id_ven
  FROM
    venta_etapa_venta
  WHERE
    (id_eta = 38) AND
    (id_est_eta_ven = 3 or id_est_eta_ven = 2)
  ";
$conexion->consulta($consulta_preinf);
$acumulado_etapa_preinforme = $conexion->total();


// cantidad de operaciones en registro n° factura
$consulta_factura = 
  "
  SELECT 
    id_ven
  FROM
    venta_etapa_venta
  WHERE
    (id_eta = 39 or id_eta = 16) AND
    (id_est_eta_ven = 2)
  ";
$conexion->consulta($consulta_factura);
$acumulado_etapa_factura = $conexion->total();
?>
<div class="row">

  <div class="col-md-9">
    <div class="box box-solid" style="height: 480px">
     <!--  <div class="box-header with-border">
        <h3 class="box-title">Progress Bars Different Sizes</h3>
      </div>  -->           
      <div class="box-body">             
        <!-- <table style="border: none; width: 100%;">
          <tbody>
            <tr>
              <td>
                
              </td>
              <td width="50%"> 
                <dl>
                  <dt class="text-muted">UNIDADES MES ACTUAL</dt>
                  <dd class="text-muted" style="font-size:25px;color: #f56954">
                    <?php 
                    //echo number_format($total_unidades_vendidas_mes_actual, 0, ',', '.');
                    ?>
                  </dd>
                </dl>
               
               </td>
            </tr>
          </tbody>
        </table>    -->
        <div style="min-height: 240px">
        	<h4 class="text-center">Unidades por Condominio</h4>
            <div id="grafico_venta" style="width: 98%; margin-top: 0px; margin-left: auto; margin-right: auto; height: 100%"></div>
        </div>
        <!-- graficos highchart -->
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
				$('#grafico_venta').highcharts({
				    chart: {
				        type: 'column'
				    },
				    title: {
				        text: ''
				    },
				    xAxis: {
				        categories: [
				        <?php
                $tvpc = '';
                $tvec = '';
                $tvlc = '';
                $tvdc = '';
                $tvdic = '';
				        $consulta_con = 
						    "
						    SELECT
						        DISTINCT con.alias_con,
						        con.id_con
						    FROM
						        condominio_condominio as con,
						        vivienda_vivienda as viv,
						        torre_torre as tor
						    WHERE
						        con.id_est_con = 1 AND
						        viv.id_tor = tor.id_tor AND
						        tor.id_con = con.id_con AND
						        con.id_con <> 1
						    ";
						$conexion->consulta($consulta_con);
						$cantidad_condo = $conexion->total();
						$conta_condo = 0;
						$fila_consulta_condo = $conexion->extraer_registro();
						if(is_array($fila_consulta_condo)){
						    foreach ($fila_consulta_condo as $fila_condo) {
						    	// promesas
						    	$consulta_ventas_promesa_condo = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    (ven.id_est_ven <> 3) AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
								    tor.id_con = ".$fila_condo["id_con"]." AND NOT
                                    EXISTS(
                                        SELECT 
                                            ven_eta.id_ven
                                        FROM
                                            venta_etapa_venta AS ven_eta
                                        WHERE
                                            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
                                    )
								  ";
								$conexion->consulta($consulta_ventas_promesa_condo);
								$total_ventas_promesa_condo = $conexion->total();
								$tvpc .= $total_ventas_promesa_condo.",";


								// ventas
						    	$consulta_ventas_escri_condo = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    (ven.id_est_ven = 6 or ven.id_est_ven = 7) AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
								    tor.id_con = ".$fila_condo["id_con"]."
								  ";
								// ventas en proceso de escrituración
								 
								$consulta_ventas_escri_condo = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    (ven.id_est_ven > 3) AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
								    tor.id_con = ".$fila_condo["id_con"]." AND 
                                    EXISTS(
                                        SELECT 
                                            ven_eta.id_ven
                                        FROM
                                            venta_etapa_venta AS ven_eta
                                        WHERE
                                            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND (ven_eta.id_est_eta_ven=1)) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND (ven_eta.id_est_eta_ven=1)))
                                    ) AND 
                                    NOT EXISTS(
                                        SELECT 
                                            ven_liq.id_ven
                                        FROM
                                            venta_liquidado_venta AS ven_liq
                                        WHERE
                                            ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                                    )
								  ";
								$conexion->consulta($consulta_ventas_escri_condo);
								$total_ventas_escri_condo = $conexion->total();
								$tvec .= $total_ventas_escri_condo.",";

								// ventas y con liquidación credito
								$consulta_ventas_liqui_condo_cr = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    (ven.id_est_ven > 3) AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
									ven.id_for_pag = 1 AND
								    tor.id_con = ".$fila_condo["id_con"]." AND 
                                    EXISTS(
                                        SELECT 
                                            ven_eta.id_ven
                                        FROM
                                            venta_etapa_venta AS ven_eta
                                        WHERE
                                            ven_eta.id_ven = ven.id_ven AND (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1))
                                    ) AND 
                                    EXISTS(
                                        SELECT 
                                            ven_liq.id_ven
                                        FROM
                                            venta_liquidado_venta AS ven_liq
                                        WHERE
                                            ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                                    )
								  ";
								$conexion->consulta($consulta_ventas_liqui_condo_cr);
								$total_ventas_liqui_condo_cr = $conexion->total();

								$consulta_ventas_liqui_condo_co = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    (ven.id_est_ven > 3) AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
									ven.id_for_pag = 2 AND
								    tor.id_con = ".$fila_condo["id_con"]." AND 
                                    EXISTS(
                                        SELECT 
                                            ven_eta.id_ven
                                        FROM
                                            venta_etapa_venta AS ven_eta
                                        WHERE
                                            ven_eta.id_ven = ven.id_ven AND (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1))
                                    ) AND 
                                    EXISTS(
                                        SELECT 
                                            ven_liq.id_ven
                                        FROM
                                            venta_liquidado_venta AS ven_liq
                                        WHERE
                                            ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                                    )
								  ";
								$conexion->consulta($consulta_ventas_liqui_condo_co);
								$total_ventas_liqui_condo_co = $conexion->total();

								$tvlc .= $total_ventas_liqui_condo_cr + $total_ventas_liqui_condo_co.",";

								// desistimiento
						    	$consulta_ventas_desis_condo = 
								  "
								  SELECT 
								    ven.id_ven
								  FROM
								    venta_venta ven,
						        	vivienda_vivienda as viv,
						        	torre_torre as tor
								  WHERE
								    ven.id_est_ven = 3 AND
									ven.id_viv = viv.id_viv AND
									viv.id_tor = tor.id_tor AND
								    tor.id_con = ".$fila_condo["id_con"]."
								  ";
								$conexion->consulta($consulta_ventas_desis_condo);
								$total_ventas_desis_condo = $conexion->total();
								$tvdc .= $total_ventas_desis_condo.",";

								// disponibles
								$consulta_disp_condo = 
                                    "
                                    SELECT
                                        viv.id_viv
                                    FROM
                                        torre_torre AS tor
                                        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                                    WHERE
                                        tor.id_con = ? AND
                                        viv.id_est_viv = 1 AND NOT
                                        EXISTS(
                                            SELECT 
                                                ven.id_ven
                                            FROM
                                                venta_venta AS ven
                                            WHERE
                                                ven.id_viv = viv.id_viv AND 
                                                ven.id_est_ven <> 3
                                        )
                                    ";
                                $conexion->consulta_form($consulta_disp_condo,array($fila_condo["id_con"]));
                                $total_vivienda_disponible_condo = $conexion->total();
								$tvdic .= $total_vivienda_disponible_condo.",";

						        $alias_con = $fila_condo["alias_con"];
						        $conta_condo++;
						        if ($conta_condo==$cantidad_condo) {
									echo "'".$alias_con."'";
						        } else {
									echo "'".$alias_con."',";
						        }					        
						    }
						}
				        ?>
				    	]
				    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Unidades'
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
				        align: 'right',
				        x: -30,
				        verticalAlign: 'top',
				        y: -10,
				        floating: true,
				        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				        borderColor: '#CCC',
				        borderWidth: 1,
				        shadow: false
				    },
				    tooltip: {
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
				    },
				    plotOptions: {
				        column: {
				            stacking: 'normal',
				            dataLabels: {
				                enabled: true,
				                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				            }
				        }
				    },
				    colors: ['#fac090', '#64f164', '#00af50', '#50612a'],
				    series: [{
				        name: 'Disp.',
				        data: [
						<?php
						echo substr($tvdic, 0, -1);
						?>
				        ]
				    }, {
				        name: 'Prom.',
				        data: [
						<?php
						echo substr($tvpc, 0, -1);
						?>
				        ]
				    }, {
				        name: 'Proc. Escrit.',
				        data: [
						<?php
						echo substr($tvec, 0, -1);
						?>
				        ]
				    }, {
				        name: 'Liquidadas',
				        data: [
						<?php
						echo substr($tvlc, 0, -1);
						?>
				        ]
				    }]
				});
            });
        </script>                                                         
      </div>            
    </div>
  </div> 

  <div class="col-md-3">          
    <div class="box box-widget widget-user-2">            
      <div class="widget-user-header bg-red disabled color-palette">             
      <p class="">Alertas</p>             
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
          
          <li><a href="<?php echo _MODULO?>informe/venta_pago_venta_cheques.php" target="_blank">Cheques por Cobrar (mes) <span class="pull-right badge bg-red"><?php echo number_format($cheque_numero_mes, 0, ',', '.');?></span></a></li>
          <li><a href="<?php echo _MODULO?>informe/venta_pago_venta.php" target="_blank">Cheques para hoy <span class="pull-right badge bg-red"><?php echo number_format($cheque_numero_hoy, 0, ',', '.');?></span></a></li>
          <!-- <li><a href="<?php //echo _MODULO?>informe/operacion_etapa_listado.php" target="_blank">Etapas Atrasadas<span class="pull-right badge bg-red"><?php //echo number_format($acumulado_atrasado_etapa, 0, ',', '.');?></span></a></li> -->
          <li><a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank">Ope. Contado en Registro de Fondo Gastos OOPP<span class="pull-right badge bg-red"><?php echo number_format($acumulado_etapa_gastos, 0, ',', '.');?></span></a>
          </li>
          <li><a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank">Operaciones para Ingreso CRE<span class="pull-right badge bg-red"><?php echo number_format($acumulado_etapa_CRE, 0, ',', '.');?></span></a>
          </li>
          <li><a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank">Operaciones en Alzamiento<span class="pull-right badge bg-red"><?php echo number_format($acumulado_etapa_alzamiento, 0, ',', '.');?></span></a>
          </li>
          <li><a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank">Operaciones en Preinforme para pago<span class="pull-right badge bg-red"><?php echo number_format($acumulado_etapa_preinforme, 0, ',', '.');?></span></a>
          </li>
          <li><a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank">Operaciones en Ingreso Factura<span class="pull-right badge bg-red"><?php echo number_format($acumulado_etapa_factura, 0, ',', '.');?></span></a>
          </li>
          <li><a href="#">Deptos Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($departamento_numero_vendido, 0, ',', '.');?></span></a></li>
          <li><a href="#">Estac. Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($estacionamiento_numero_vendido, 0, ',', '.');?></span></a></li>
          <li><a href="#">Bodegas Vendidas <span class="pull-right badge bg-aqua"><?php echo number_format($bodega_numero_vendido, 0, ',', '.');?></span></a></li>
			<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php" target="_blank">Exportar Escrituras</a></li>
        </ul>
      </div>
    </div>          
  </div>                                
</div>
<div class="row">
  <div class="col-md-12">          
    <div class="box box-solid">                       
      <div class="box-body">
      	<?php 
      	include "tabla_estados.php";
      	 ?>

      </div>
    </div>          
  </div>        
</div>
<div class="row">
  
  <div class="col-md-6">          
        
           
  </div>
</div>
<div class="row">
  <div class="col-md-3">          
    
    <div class="info-box">
      <a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa ion-wrench"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Operacion / Etapas</span>
          <!-- <span class="info-box-number"><?php echo number_format($cantidad_apoderado, 0, ',', '.');?></span> -->
        </div> 
      </a>          
    </div>
  </div>
  <div class="col-md-3">  
    <div class="info-box">
      <a href="<?php echo _MODULO?>informe/condominio_departamento_listado.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa fa-building"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Condominio</span>
          <!-- <span class="info-box-number"><?php echo number_format($monto_moroso, 0, ',', '.');?></span> -->
        </div> 
      </a>          
    </div>
  </div>
  <div class="col-md-3"> 
    <div class="info-box">
      <a href="<?php echo _MODULO?>informe/operacion_listado.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Venta / Cotización</span>
          <!-- <span class="info-box-number"><?php echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
        </div>  
      </a>         
    </div>           
  </div>   
</div>