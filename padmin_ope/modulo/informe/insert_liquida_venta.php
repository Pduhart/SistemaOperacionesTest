<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
try{
$id_ven = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$monto_liq_uf = isset($_POST["monto_liq_uf"]) ? utf8_decode($_POST["monto_liq_uf"]) : 0;
$monto_liq_pesos = isset($_POST["monto_liq_pesos"]) ? utf8_decode($_POST["monto_liq_pesos"]) : 0;
$fecha_liq = isset($_POST["fecha_liq"]) ? utf8_decode($_POST["fecha_liq"]) : null;
$insert = isset($_POST["insert"]) ? utf8_decode($_POST["insert"]) : 0;
$val_factura = isset($_POST["val_factura"]) ? utf8_decode($_POST["val_factura"]) : 0;
$num_factura = isset($_POST["num_factura"]) ? utf8_decode($_POST["num_factura"]) : 0;
$ciudad_notaria = isset($_POST["ciudad_notaria"]) ? $_POST["ciudad_notaria"] : 0;

$val_ncredito = isset($_POST["val_ncredito"]) ? utf8_decode($_POST["val_ncredito"]) : 0;
$num_ncredito = isset($_POST["num_ncredito"]) ? utf8_decode($_POST["num_ncredito"]) : 0;
$val_cre = isset($_POST["val_cre"]) ? utf8_decode($_POST["val_cre"]) : 0;
$fecha_alzamiento_ven = isset($_POST["fecha_alzamiento_ven"]) ? utf8_decode($_POST["fecha_alzamiento_ven"]) : null;
$fecha_cargo_301_ven = isset($_POST["fecha_cargo_301_ven"]) ? utf8_decode($_POST["fecha_cargo_301_ven"]) : null;
$fecha_abono_330_ven = isset($_POST["fecha_abono_330_ven"]) ? utf8_decode($_POST["fecha_abono_330_ven"]) : null;

$fecha_liq_com = isset($_POST["fecha_liq_com"]) ? ($_POST["fecha_liq_com"]) : null;

$conexion->consulta_form("SELECT ven.id_for_pag FROM venta_venta as ven WHERE ven.id_ven = ?",array($id_ven));
$filafor = $conexion->extraer_registro_unico();
$id_for_pag = utf8_encode($filafor['id_for_pag']);


if ($fecha_liq<>null && $fecha_liq<>'') {
	$fecha_liq = date("Y-m-d",strtotime($fecha_liq));
} else {
	$fecha_liq = null;
}

if ($monto_liq_uf=='') $monto_liq_uf = null;
if ($monto_liq_pesos=='') $monto_liq_pesos = null;
if ($fecha_liq_com<>null && $fecha_liq_com<>'') {
	$fecha_liq_com = date("Y-m-d",strtotime($fecha_liq_com));
	$consulta = "UPDATE venta_venta SET fecha_promesa_ven = ? WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_liq_com,$id_ven));
} else {
	$fecha_liq_com = null;
}

if ($insert==0) {
	$consulta = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	$conexion->consulta_form($consulta,array(0,$id_ven,$fecha_liq,$monto_liq_uf,$monto_liq_pesos));
} else {
	$consulta = "UPDATE venta_liquidado_venta SET fecha_liq_ven = ?, monto_liq_uf_ven = ?, monto_liq_pesos_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_liq,$monto_liq_uf,$monto_liq_pesos,$id_ven));
}

// revisa si están los de 38
$conexion->consulta_form("SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 38 AND id_cam_eta = 51",array($id_ven));
$insert_pesos38p = $conexion->total();
if ($insert_pesos38p>0) $conexion->consulta_form("UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 38 AND id_cam_eta = 51",array($monto_liq_pesos,$id_ven));
// revisa si están los de 38
$conexion->consulta_form("SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 38 AND id_cam_eta = 52 ",array($id_ven));
$insert_uf38 = $conexion->total();
if ($insert_uf38>0) $conexion->consulta_form("UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 38 AND id_cam_eta = 52",array($monto_liq_uf,$id_ven));
// revisa si están los de 47
$consultahay_pesos47p = 
    "
    SELECT
        id_eta_cam_ven
    FROM
        venta_etapa_campo_venta
    WHERE 
        id_ven = ? AND 
        id_eta = 47 AND
        id_cam_eta = 49
    ";
$conexion->consulta_form($consultahay_pesos47p,array($id_ven));
$insert_pesos47p = $conexion->total();

if ($insert_pesos47p>0) {
	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 47 AND id_cam_eta = 49";
	$conexion->consulta_form($consulta,array($monto_liq_pesos,$id_ven));
}

// revisa si están los de 47
$consultahay_uf47u = 
    "
    SELECT
        id_eta_cam_ven
    FROM
        venta_etapa_campo_venta
    WHERE 
        id_ven = ? AND 
        id_eta = 47 AND
        id_cam_eta = 50
    ";
$conexion->consulta_form($consultahay_uf47u,array($id_ven));
$insert_uf47 = $conexion->total();

if ($insert_uf47>0) {
	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 47 AND id_cam_eta = 50";
	$conexion->consulta_form($consulta,array($monto_liq_uf,$id_ven));
}


// campos extras
// VALOR FACTURA
if ($val_factura<>0 && $val_factura<>'') {
	$consulta = "UPDATE venta_campo_venta SET monto_factura_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($val_factura,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 61
		$consultahay_61 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 39 AND
		        id_cam_eta = 61
		    ";
		$conexion->consulta_form($consultahay_61,array($id_ven));
		$actualiza_61 = $conexion->total();

		if ($actualiza_61>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 39 AND id_cam_eta = 61";
			$conexion->consulta_form($consulta,array($val_factura,$id_ven));
		}
	} else {
		// revisa si esta el campo 62
		$consultahay_62 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 16 AND
		        id_cam_eta = 62
		    ";
		$conexion->consulta_form($consultahay_62,array($id_ven));
		$actualiza_62 = $conexion->total();

		if ($actualiza_62>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 16 AND id_cam_eta = 62";
			$conexion->consulta_form($consulta,array($val_factura,$id_ven));
		}
	}
}

// NUMERO FACTURA
if ($num_factura<>0 && $num_factura<>'') {
	$consulta = "UPDATE venta_campo_venta SET numero_factura_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($num_factura,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 61
		$consultahay_53 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 39 AND
		        id_cam_eta = 53
		    ";
		$conexion->consulta_form($consultahay_53,array($id_ven));
		$actualiza_53 = $conexion->total();

		if ($actualiza_53>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 39 AND id_cam_eta = 53";
			$conexion->consulta_form($consulta,array($num_factura,$id_ven));
		}
	} else {
		// revisa si esta el campo 26
		$consultahay_26 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 16 AND
		        id_cam_eta = 26
		    ";
		$conexion->consulta_form($consultahay_26,array($id_ven));
		$actualiza_26 = $conexion->total();

		if ($actualiza_26>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 16 AND id_cam_eta = 26";
			$conexion->consulta_form($consulta,array($num_factura,$id_ven));
		}
	}
}

// VALOR NC
if ($val_ncredito<>0 && $val_ncredito<>'') {
	$consulta = "UPDATE venta_campo_venta SET monto_nc_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($val_ncredito,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 65
		$consultahay_65 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 39 AND
		        id_cam_eta = 65
		    ";
		$conexion->consulta_form($consultahay_65,array($id_ven));
		$actualiza_65 = $conexion->total();

		if ($actualiza_65>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 39 AND id_cam_eta = 65";
			$conexion->consulta_form($consulta,array($val_ncredito,$id_ven));
		}
	} else {
		// revisa si esta el campo 63
		$consultahay_63 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 16 AND
		        id_cam_eta = 63
		    ";
		$conexion->consulta_form($consultahay_63,array($id_ven));
		$actualiza_63 = $conexion->total();

		if ($actualiza_63>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 16 AND id_cam_eta = 63";
			$conexion->consulta_form($consulta,array($val_ncredito,$id_ven));
		}
	}
}

// NUMERO NC
if ($num_ncredito<>0 && $num_ncredito<>'') {
	$consulta = "UPDATE venta_campo_venta SET numero_nc_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($num_ncredito,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 64
		$consultahay_64 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 39 AND
		        id_cam_eta = 64
		    ";
		$conexion->consulta_form($consultahay_64,array($id_ven));
		$actualiza_64 = $conexion->total();

		if ($actualiza_64>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 39 AND id_cam_eta = 64";
			$conexion->consulta_form($consulta,array($num_ncredito,$id_ven));
		}
	} else {
		// revisa si esta el campo 27
		$consultahay_27 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 16 AND
		        id_cam_eta = 27
		    ";
		$conexion->consulta_form($consultahay_27,array($id_ven));
		$actualiza_27 = $conexion->total();

		if ($actualiza_27>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 16 AND id_cam_eta = 27";
			$conexion->consulta_form($consulta,array($num_ncredito,$id_ven));
		}
	}
}

// VALOR CRE
if ($val_cre<>0 && $val_cre<>'') {
	$consulta = "UPDATE venta_campo_venta SET valor_cre_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($val_cre,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 35
		$consultahay_35 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 33 AND
		        id_cam_eta = 35
		    ";
		$conexion->consulta_form($consultahay_35,array($id_ven));
		$actualiza_35 = $conexion->total();

		if ($actualiza_35>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 33 AND id_cam_eta = 35";
			$conexion->consulta_form($consulta,array($val_cre,$id_ven));
		}
	} else {
		// revisa si esta el campo 26
		$consultahay_25 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 13 AND
		        id_cam_eta = 25
		    ";
		$conexion->consulta_form($consultahay_25,array($id_ven));
		$actualiza_25 = $conexion->total();

		if ($actualiza_25>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 13 AND id_cam_eta = 25";
			$conexion->consulta_form($consulta,array($val_cre,$id_ven));
		}
	}
}

// FECHA ALZAMIENTO
if ($fecha_alzamiento_ven<>null && $fecha_alzamiento_ven<>'') {
	$fecha_alzamiento_ven = date("Y-m-d",strtotime($fecha_alzamiento_ven));
	$consulta = "UPDATE venta_campo_venta SET fecha_alzamiento_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_alzamiento_ven,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 42
		$consultahay_42 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 35 AND
		        id_cam_eta = 42
		    ";
		$conexion->consulta_form($consultahay_42,array($id_ven));
		$actualiza_42 = $conexion->total();

		if ($actualiza_42>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 35 AND id_cam_eta = 42";
			$conexion->consulta_form($consulta,array($fecha_alzamiento_ven,$id_ven));
		}
	} else {
		// revisa si esta el campo 46
		$consultahay_46 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 14 AND
		        id_cam_eta = 46
		    ";
		$conexion->consulta_form($consultahay_46,array($id_ven));
		$actualiza_46 = $conexion->total();

		if ($actualiza_46>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 14 AND id_cam_eta = 46";
			$conexion->consulta_form($consulta,array($fecha_alzamiento_ven,$id_ven));
		}
	}
}

// FECHA CARGO 301
if ($fecha_cargo_301_ven<>null && $fecha_cargo_301_ven<>'') {
	$fecha_cargo_301_ven = date("Y-m-d",strtotime($fecha_cargo_301_ven));
	$consulta = "UPDATE venta_campo_venta SET fecha_cargo_301_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_cargo_301_ven,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 43
		$consultahay_43 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 35 AND
		        id_cam_eta = 43
		    ";
		$conexion->consulta_form($consultahay_43,array($id_ven));
		$actualiza_43 = $conexion->total();

		if ($actualiza_43>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 35 AND id_cam_eta = 43";
			$conexion->consulta_form($consulta,array($fecha_cargo_301_ven,$id_ven));
		}
	} else {
		// revisa si esta el campo 47
		$consultahay_47 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 14 AND
		        id_cam_eta = 47
		    ";
		$conexion->consulta_form($consultahay_47,array($id_ven));
		$actualiza_47 = $conexion->total();

		if ($actualiza_47>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 14 AND id_cam_eta = 47";
			$conexion->consulta_form($consulta,array($fecha_cargo_301_ven,$id_ven));
		}
	}
}

// FECHA ABONO 330
if ($fecha_abono_330_ven<>null && $fecha_abono_330_ven<>'') {
	$fecha_abono_330_ven = date("Y-m-d",strtotime($fecha_abono_330_ven));
	$consulta = "UPDATE venta_campo_venta SET fecha_abono_330_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($fecha_abono_330_ven,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 44
		$consultahay_44 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 35 AND
		        id_cam_eta = 44
		    ";
		$conexion->consulta_form($consultahay_44,array($id_ven));
		$actualiza_44 = $conexion->total();

		if ($actualiza_44>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 35 AND id_cam_eta = 44";
			$conexion->consulta_form($consulta,array($fecha_abono_330_ven,$id_ven));
		}
	} else {
		// revisa si esta el campo 48
		$consultahay_48 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 14 AND
		        id_cam_eta = 48
		    ";
		$conexion->consulta_form($consultahay_48,array($id_ven));
		$actualiza_48 = $conexion->total();

		if ($actualiza_48>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 14 AND id_cam_eta = 48";
			$conexion->consulta_form($consulta,array($fecha_abono_330_ven,$id_ven));
		}
	}
}

// CIUDAD NOTARIA
if ($ciudad_notaria<>0 && $ciudad_notaria<>'') {
	$consulta = "UPDATE venta_campo_venta SET ciudad_notaria_ven = ?  WHERE id_ven = ?";
	$conexion->consulta_form($consulta,array($ciudad_notaria,$id_ven));

	// ver forma de pago
	if ($id_for_pag==1) {
		// revisa si esta el campo 68
		$consultahay_68 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 27 AND
		        id_cam_eta = 68
		    ";
		$conexion->consulta_form($consultahay_68,array($id_ven));
		$actualiza_68 = $conexion->total();

		if ($actualiza_68>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 27 AND id_cam_eta = 68";
			$conexion->consulta_form($consulta,array($ciudad_notaria,$id_ven));
		}
	} else {
		// revisa si esta el campo 26
		$consultahay_67 = 
		    "
		    SELECT
		        id_eta_cam_ven
		    FROM
		        venta_etapa_campo_venta
		    WHERE 
		        id_ven = ? AND 
		        id_eta = 5 AND
		        id_cam_eta = 67
		    ";
		$conexion->consulta_form($consultahay_67,array($id_ven));
		$actualiza_67 = $conexion->total();

		if ($actualiza_67>0) {
			$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta = 5 AND id_cam_eta = 67";
			$conexion->consulta_form($consulta,array($ciudad_notaria,$id_ven));
		}
	}
}
}catch(Exception $e){
	$jsondata['envio'] = $e->getMessage();
    echo json_encode($jsondata);
}catch(PDOException $e){
	$jsondata['envio'] = $e->getMessage();
    echo json_encode($jsondata);
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>