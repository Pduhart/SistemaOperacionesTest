<?php 
//---------------------- TOTAL ESCRITURACIÓN
// condo 1
$consulta_ventas_inciadas_oopp_1 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 1 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_1);
$total_unidades_en_oopp_1 = $conexion->total();

// condo 4
$consulta_ventas_inciadas_oopp_4 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 4 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_4);
$total_unidades_en_oopp_4 = $conexion->total();

// condo 5
$consulta_ventas_inciadas_oopp_5 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 5 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_5);
$total_unidades_en_oopp_5 = $conexion->total();

// condo 6
$consulta_ventas_inciadas_oopp_6 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 6 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_6);
$total_unidades_en_oopp_6 = $conexion->total();

// condo 7
$consulta_ventas_inciadas_oopp_7 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 7 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_7);
$total_unidades_en_oopp_7 = $conexion->total();

// condo 8
$consulta_ventas_inciadas_oopp_8 = 
  "
  SELECT 
    ven.id_ven
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 8 AND EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )";
$conexion->consulta($consulta_ventas_inciadas_oopp_8);
$total_unidades_en_oopp_8 = $conexion->total();

// unidades que pasaron etapa de factura
$consulta_facturadas = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    venta_etapa_venta as ven_eta
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_ven = ven_eta.id_ven AND
    ((ven_eta.id_eta=".$n_etaco_fact_ven." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_fact_ven." AND ven_eta.id_est_eta_ven=1))
  ";
$conexion->consulta($consulta_facturadas);
$total_unidades_facturadas = $conexion->total();

//---------------------------- TOTAL RECUPERADAS
// Ventas que entraron a operaciones
// CONDO 1
// $consulta_ventas_liquidadas_oopp_ch_1 = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 1 AND
//     ven.id_for_pag = 1 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";


$HOY = date("Y-m-d");
$consulta_ventas_liquidadas_oopp_ch_1 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 1 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";
$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_1);
$total_unidades_en_liquidadas_ch_1 = $conexion->total();

// echo $consulta_ventas_liquidadas_oopp_ch_1;

// $consulta_ventas_liquidadas_oopp_co_1 = 
//   "
//   SELECT 
//     DISTINCT ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 1 AND
//     ven.id_for_pag = 2 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_co_1 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 1 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

$conexion->consulta($consulta_ventas_liquidadas_oopp_co_1);
$total_unidades_en_liquidadas_co_1 = $conexion->total();

$liquidadas_total_condo_1 = $total_unidades_en_liquidadas_ch_1 + $total_unidades_en_liquidadas_co_1;


// CONDO 4

// $consulta_ventas_liquidadas_oopp_ch_4 = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 4 AND
//     ven.id_for_pag = 1 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_ch_4 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 4 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";
// echo $consulta_ventas_liquidadas_oopp_ch_4;

$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_4);
$total_unidades_en_liquidadas_ch_4 = $conexion->total();

// $consulta_ventas_liquidadas_oopp_co_4 = 
//   "
//   SELECT 
//     DISTINCT ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 4 AND
//     ven.id_for_pag = 2 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_co_4 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 4 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

// echo $consulta_ventas_liquidadas_oopp_co_4;
$conexion->consulta($consulta_ventas_liquidadas_oopp_co_4);
$total_unidades_en_liquidadas_co_4 = $conexion->total();

$liquidadas_total_condo_4 = $total_unidades_en_liquidadas_ch_4 + $total_unidades_en_liquidadas_co_4;


// CONDO 5

// $consulta_ventas_liquidadas_oopp_ch_5 = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 5 AND
//     ven.id_for_pag = 1 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_ch_5 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 5 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";


$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_5);
$total_unidades_en_liquidadas_ch_5 = $conexion->total();

// $consulta_ventas_liquidadas_oopp_co_5 = 
//   "
//   SELECT 
//     DISTINCT ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 5 AND
//     ven.id_for_pag = 2 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_co_5 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 5 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

$conexion->consulta($consulta_ventas_liquidadas_oopp_co_5);
$total_unidades_en_liquidadas_co_5 = $conexion->total();

$liquidadas_total_condo_5 = $total_unidades_en_liquidadas_ch_5 + $total_unidades_en_liquidadas_co_5;

// CONDO 6

// $consulta_ventas_liquidadas_oopp_ch_6 = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 6 AND
//     ven.id_for_pag = 1 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";


$consulta_ventas_liquidadas_oopp_ch_6 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 6 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";


$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_6);
$total_unidades_en_liquidadas_ch_6 = $conexion->total();

// $consulta_ventas_liquidadas_oopp_co_6 = 
//   "
//   SELECT 
//     DISTINCT ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 6 AND
//     ven.id_for_pag = 2 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_co_6 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 6 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

$conexion->consulta($consulta_ventas_liquidadas_oopp_co_6);
$total_unidades_en_liquidadas_co_6 = $conexion->total();

$liquidadas_total_condo_6 = $total_unidades_en_liquidadas_ch_6 + $total_unidades_en_liquidadas_co_6;


// CONDO 7

// $consulta_ventas_liquidadas_oopp_ch_7 = 
//   "
//   SELECT 
//     ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 7 AND
//     ven.id_for_pag = 1 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_ch_7 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 7 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";


$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_7);
$total_unidades_en_liquidadas_ch_7 = $conexion->total();

// $consulta_ventas_liquidadas_oopp_co_7 = 
//   "
//   SELECT 
//     DISTINCT ven.id_viv
//   FROM
//     venta_venta as ven,
//     venta_etapa_venta as ven_eta,
//     vivienda_vivienda as viv
//   WHERE
//     ven.id_est_ven > 3 AND
//     ven.id_viv = viv.id_viv AND
//     viv.id_tor = 7 AND
//     ven.id_for_pag = 2 AND
//     ven.id_ven = ven_eta.id_ven AND
//     (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1)) AND EXISTS(SELECT ven_liq.id_ven FROM venta_liquidado_venta AS ven_liq WHERE ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> '')
//   ";

$consulta_ventas_liquidadas_oopp_co_7 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 7 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

$conexion->consulta($consulta_ventas_liquidadas_oopp_co_7);
$total_unidades_en_liquidadas_co_7 = $conexion->total();

$liquidadas_total_condo_7 = $total_unidades_en_liquidadas_ch_7 + $total_unidades_en_liquidadas_co_7;

// ------------CONDO 8
$consulta_ventas_liquidadas_oopp_ch_8 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 8 AND
    ven.id_for_pag = 1 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";


$conexion->consulta($consulta_ventas_liquidadas_oopp_ch_8);
$total_unidades_en_liquidadas_ch_8 = $conexion->total();


$consulta_ventas_liquidadas_oopp_co_8 = 
  "
  SELECT 
    ven.id_viv
  FROM
    venta_venta as ven,
    vivienda_vivienda as viv
  WHERE
    ven.id_est_ven > 3 AND
    ven.id_viv = viv.id_viv AND
    viv.id_tor = 8 AND
    ven.id_for_pag = 2 AND
    EXISTS(
    	SELECT ven_liq.id_ven 
    	FROM venta_liquidado_venta AS ven_liq 
    	WHERE ven_liq.id_ven = ven.id_ven AND 
    	ven_liq.fecha_liq_ven <> '' AND
    	ven_liq.fecha_liq_ven < '".$HOY."' AND
    	ven_liq.monto_liq_uf_ven <> '')
  ";

$conexion->consulta($consulta_ventas_liquidadas_oopp_co_8);
$total_unidades_en_liquidadas_co_8 = $conexion->total();

$liquidadas_total_condo_8 = $total_unidades_en_liquidadas_ch_8 + $total_unidades_en_liquidadas_co_8;

//------------------------------- TOTAL PROMESAS
// condo 1
$consulta_ventas_promesa_1 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
   	AND viv.id_tor = 1
  ";
// echo $consulta_ventas_promesa;
$conexion->consulta($consulta_ventas_promesa_1);
$total_ventas_promesa_1 = $conexion->total();

// condo 4
$consulta_ventas_promesa_4 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
    AND viv.id_tor = 4
  ";
// echo $consulta_ventas_promesa;
$conexion->consulta($consulta_ventas_promesa_4);
$total_ventas_promesa_4 = $conexion->total();

// condo 5
$consulta_ventas_promesa_5 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
    AND viv.id_tor = 5
  ";
// echo $consulta_ventas_promesa;
$conexion->consulta($consulta_ventas_promesa_5);
$total_ventas_promesa_5 = $conexion->total();

// condo 6
$consulta_ventas_promesa_6 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
    AND viv.id_tor = 6
  ";
// echo $consulta_ventas_promesa_6;
$conexion->consulta($consulta_ventas_promesa_6);
$total_ventas_promesa_6 = $conexion->total();

// condo 7
$consulta_ventas_promesa_7 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
    AND viv.id_tor = 7
  ";
// echo $consulta_ventas_promesa_7;
$conexion->consulta($consulta_ventas_promesa_7);
$total_ventas_promesa_7 = $conexion->total();

// condo 8
$consulta_ventas_promesa_8 = 
  "
  SELECT 
    ven.id_ven
  FROM
    torre_torre AS tor
    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
    INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv AND ven.id_est_ven <> 3
  WHERE NOT
    EXISTS(
        SELECT 
            ven_eta.id_ven
        FROM
            venta_etapa_venta AS ven_eta
        WHERE
            ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
    )
    AND viv.id_tor = 8
  ";
// echo $consulta_ventas_promesa_7;
$conexion->consulta($consulta_ventas_promesa_8);
$total_ventas_promesa_8 = $conexion->total();


 ?>

<table style="border: none; width: 100%">
      	<tr>
      		<td style="width: 30%"></td>
      		<!-- <td style="text-align: center; font-weight: bold">Pacífico 3</td> -->
      		<td style="text-align: center; font-weight: bold">Pacífico 2800 - 1</td>
      		<td style="text-align: center; font-weight: bold">Pacífico 2800 - 2</td>
      		<td style="text-align: center; font-weight: bold">Pacífico 3100 - 1</td>
      		<td style="text-align: center; font-weight: bold">Pacífico 3100 - 2</td>
      		<td style="text-align: center; font-weight: bold">D.Verde - 1</td>
      	</tr>
          
        <tr>
          <td width="30%"><h3 class="text-muted text-left" style="line-height: 18px">Total promesas<br><small>(operaciones que están promesadas y aún No cuentan con su CH aprobado y en contado aun No pagan provisión de fondos) *no pasan la Etapa 2</small></h3></td>
          <!-- <td width="16%"><h3 class="text-muted text-right" style="color: #f56954;"><?php //echo number_format($total_ventas_promesa_1, 0, ',', '.');?> Unid.</h3></td> -->
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_ventas_promesa_4, 0, ',', '.');?> Unid.</h3></td>

          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_ventas_promesa_5, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_ventas_promesa_6, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_ventas_promesa_7, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_ventas_promesa_8, 0, ',', '.');?> Unid.</h3></td>
        </tr>

        <tr>
          <td width="30%"><h3 class="text-muted text-left" style="line-height: 18px">Unidades proceso de Escrituración<br><small>(esto debe reflejar las unidades desde que están aprobados sus CH + las OOPP de contado que han pagado su provisión de fondos) *pasaron la etapa 2</small></h3></td>
          <!-- <td width="16%"><h3 class="text-muted text-right" style="color: #f56954;"><?php //echo number_format($total_unidades_en_oopp_1, 0, ',', '.');?> Unid.</h3></td> -->
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_en_oopp_4, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_en_oopp_5, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_en_oopp_6, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_en_oopp_7, 0, ',', '.');?> Unid.</h3></td>
          <td width="14%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_en_oopp_8, 0, ',', '.');?> Unid.</h3></td>
        </tr>
        
        <tr>
            <td><h3 class="text-muted text-left" style="line-height: 18px">Total Unidades Recuperadas<br><small>(es el total de operaciones que se les ingresó fecha liquidación y monto liquidación)</small></h3></td>
            <!-- <td><h3 class="text-muted text-right" style="color: #f56954"><?php //echo number_format($liquidadas_total_condo_1, 0, ',', '.');?> Unid.</h3></td> -->
            <?php 
            if ($_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==3 || $_SESSION["sesion_perfil_panel"]==7) {
             ?>
            <td><a href="modulo/informe/recuperadas_listado.php?tor=4" target="_blank"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_4, 0, ',', '.');?> Unid.</h3></a></td>
            <td><a href="modulo/informe/recuperadas_listado.php?tor=5" target="_blank"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_5, 0, ',', '.');?> Unid.</h3></a></td>
            <td><a href="modulo/informe/recuperadas_listado.php?tor=6" target="_blank"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_6, 0, ',', '.');?> Unid.</h3></a></td>
            <td><a href="modulo/informe/recuperadas_listado.php?tor=7" target="_blank"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_7, 0, ',', '.');?> Unid.</h3></a></td>
            <td><a href="modulo/informe/recuperadas_listado.php?tor=7" target="_blank"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_8, 0, ',', '.');?> Unid.</h3></a></td>
            <?php 
        	 } else {
        	  ?>
        	<td><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_4, 0, ',', '.');?> Unid.</h3></td>
            <td><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_5, 0, ',', '.');?> Unid.</h3></td>
            <td><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_6, 0, ',', '.');?> Unid.</h3></td>
            <td><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_7, 0, ',', '.');?> Unid.</h3></td>
            <td><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($liquidadas_total_condo_8, 0, ',', '.');?> Unid.</h3></td>
        	 <?php 
        	 }
             ?>
        </tr>
      </table>