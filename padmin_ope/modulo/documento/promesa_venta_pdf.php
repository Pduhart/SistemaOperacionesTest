<?php 
ob_start();//Enables Output Buffering
session_start(); 
date_default_timezone_set('Chile/Continental');
include 'mpdf/mpdf.php';
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

// $nombre = 'liquidacion_reserva_'.$id_res.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

$html .= '
<!DOCTYPE html>
<html>
<head>
    <title>Promesa CompraVenta</title>
    <meta charset="utf-8">
    <style type="text/css">
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: Arial;
    		font-size: 13px;
    	}
        .sin-borde{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
        }
		.sin-borde h2{
			font-size: 1.4rem;
			margin-bottom: 10px;
		}
		.sin-borde h6{
			font-size: 1rem;
			margin-top: 10px;
		}
		.sin-borde .hoy{
			width: 100%;
			border: 1px solid #000000;
			padding: 6px;
		}
		.sin-borde .periodo{
			width: 100%;
			padding: 6px;
		}
		.liquida{
			width: 95%;
			margin-left: auto;
			margin-right: auto;
			border-collapse: collapse;
		}
		.liquida td{
			padding-bottom: .2rem;
			padding-top: .2rem;
		}
		.liquida .cabecera td{
			vertical-align: top;
			text-align: center;
		}
		.liquida .separa td{
			font-weight: bold;
			border-bottom: 1px solid #000000;
			border-top: 1px solid #000000;
		}
		.liquida .separa.total td{
			border-top: 2px solid #000000;
		}
		.derecha{
			text-align: right;
		}
		.centro{
			text-align: center;
		}
		.liquida .bl-1{
			border-left: 1px solid #000000;
		}

		.conborde td{
			border: 1px solid #000000;
			padding: 2px 3px;
		}
		.bold td{
			font-weight: bold;
		}

		.borde-top{
			border-top: 1px solid #000000;
		}
    </style>
</head>
<body>';


$html .= '
<div style="text-align: justify">
	En La Serena, a  04 de Agosto de 2021, entre “INMOBILIARIA COSTANERA PACÍFICO SpA.”, persona jurídica de derecho privado, del giro de su denominación, rol único tributario número setenta y seis millones ochocientos sesenta y seis mil setenta y cinco guión uno, representada legalmente, según se acreditará, por don SEBASTIAN RODRIGO ARAYA VARELA - , chileno, casado, arquitecto, cédula nacional de identidad número once millones seiscientos diez mil ciento ochenta guion siete (o doña CECILIA MARGARITA DEBIA GARCÍA, chilena, soltera, técnico en construcción, cédula de identidad número cinco millones novecientos sesenta y seis mil novecientos cincuenta y nueve guion uno), con domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente
	domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente
	domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente
	domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
	<p>domicilio en La Serena, calle Avenida Pacífico número dos mil ochocientos, en adelante la “Promitente Vendedora” y/o “La Inmobiliaria”, y por la otra parte don (ña) MARÍA JOSEFA AIQUEL GUZMÁN, cédula nacional de identidad número 10.026.615-6, estado civil Casada, de profesión u oficio Médico Pediatra, con domicilio en calle GERÓNIMO MENDEZ 1501, comuna de COQUIMBO, IV REGIÓN DE COQUIMBO, número de celular 56995409939, correo electrónico MJAIQUELG@GMAIL.COM, lugar de trabajo Hospital de Coquimbo,  comuna de COQUIMBO, IV REGIÓN DE COQUIMBO. en adelante indistintamente, el “Promitente Comprador”, se ha convenido lo siguiente</p>
</div>
';

$mpdf = new mPDF('c','A4'); 
// $mpdf->charset_in='UTF-8';
// $mpdf->allow_charset_conversion=true;
$mpdf->writeHTML($html);
// $mpdf->AddPage();
// $mpdf->WriteHTML($html2);
$nombre = 'documentos/cierre_negocio_'.date('dmYHi').'.pdf';
ob_end_clean();//End Output Buffering
// $fecha = date('Y-m-d H:i:s');
$pdf = $mpdf->output($nombre ,'I');

?>