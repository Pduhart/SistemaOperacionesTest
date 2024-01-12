<?php
session_start();
date_default_timezone_set('America/Santiago');
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/venta_clase.php");
$venta = new venta();
$id = isset($_POST["id"]) ? utf8_encode($_POST["id"]) : 0;

//DETALLE PAGOS
for ($contador = 1; $contador <= $_SESSION["numero_linea"]; $contador++) {	
	$forma_pago = utf8_decode($_POST["forma_pago".$contador]);
	$banco = $_POST["banco".$contador];
	$categoria = $_POST["categoria".$contador];
	$numero_documento = $_POST["numero_documento".$contador];
	// $numero_serie = $_POST["numero_serie".$contador];
	$numero_serie = $_POST["numero_documento".$contador];
	
	$fecha = $_POST["fecha".$contador];
	$fecha = date("Y-m-d",strtotime($fecha));
	// $fecha_real = $_POST["fecha_real".$contador];
	// $fecha_real = date("Y-m-d",strtotime($fecha_real));
	$monto = $_POST["monto".$contador];
	$descripcion = "";
	
	if (!empty($forma_pago) && !empty($categoria) && !empty($fecha) && !empty($monto)) {
		$venta->venta_insert_pago($id,$forma_pago,$banco,$categoria,2,$numero_documento,$numero_serie,$fecha,$monto,$descripcion);
	}
				
}



$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>