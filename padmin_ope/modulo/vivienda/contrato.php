<?php
session_start(); 
require_once dirname(__FILE__).'/PHPWord-master/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

use PhpOffice\PhpWord\TemplateProcessor;

$templateWord = new TemplateProcessor('contrato/contrato.docx');
require "../../config.php";
include "../../class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

$consulta = 
    "
    SELECT
        propietario_propietario.rut_pro,
        propietario_propietario.nombre_pro,
        propietario_propietario.apellido_paterno_pro,
        propietario_propietario.apellido_materno_pro,
        propietario_propietario.fono_pro,
        propietario_propietario.correo_pro,
        propietario_propietario.direccion_pro,
        vivienda_vivienda.nombre_viv,
        vivienda_vivienda.estacionamiento_viv,
        vivienda_vivienda.bodega_viv,
        propietario_propietario.numero_cuenta_pro,
        propietario_propietario.id_ban,
        propietario_propietario.id_com
    FROM
        propietario_vivienda_propietario,
        propietario_propietario,
        vivienda_vivienda
    WHERE
        vivienda_vivienda.id_viv = ? AND
        propietario_vivienda_propietario.id_viv = vivienda_vivienda.id_viv AND
        propietario_vivienda_propietario.id_pro = propietario_propietario.id_pro
    ";
$conexion->consulta_form($consulta,array($id));
$fila = $conexion->extraer_registro_unico();
$rut_pro				= utf8_encode($fila['rut_pro']);
$nombre_pro				= utf8_encode($fila['nombre_pro']);
$apellido_paterno_pro	= utf8_encode($fila['apellido_paterno_pro']);
$apellido_materno_pro	= utf8_encode($fila['apellido_materno_pro']);
$fono_pro				= utf8_encode($fila['fono_pro']);
$correo_pro             = utf8_encode($fila['correo_pro']);
$direccion_pro			= utf8_encode($fila['direccion_pro']);
$nombre_viv				= utf8_encode($fila['nombre_viv']);
$estacionamiento_viv	= utf8_encode($fila['estacionamiento_viv']);
$bodega_viv	= utf8_encode($fila['bodega_viv']);
$numero_cuenta_pro		= utf8_encode($fila['numero_cuenta_pro']);
$id_ban                 = utf8_encode($fila['id_ban']);
$id_com					= utf8_encode($fila['id_com']);

$consulta_ban = 
    "
    SELECT
        nombre_ban
    FROM
        banco_banco
    WHERE
        id_ban = ".$id_ban."
    ";
$conexion->consulta($consulta_ban);
$fila_ban = $conexion->extraer_registro_unico();

$nombre_ban	= utf8_encode($fila_ban['nombre_ban']);

$consulta_com = 
    "
    SELECT
        nombre_com
    FROM
        comuna_comuna
    WHERE
        id_com = ".$id_com."
    ";
$conexion->consulta($consulta_com);
$fila_com = $conexion->extraer_registro_unico();

$nombre_com = utf8_encode($fila_com['nombre_com']);

$nombre_propie = $nombre_pro.' '.$apellido_paterno_pro.' '.$apellido_materno_pro;
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$dia = date("d");
$mes = $meses[date('n')-1];
$anio = date("Y");
// --- Asignamos valores a la plantilla
$templateWord->setValue('dia',$dia);
$templateWord->setValue('mes',$mes);
$templateWord->setValue('anio',$anio);
$templateWord->setValue('nombre',$nombre_propie);
$templateWord->setValue('rut',$rut_pro);
$templateWord->setValue('celular',$fono_pro);
$templateWord->setValue('email',$correo_pro);
$templateWord->setValue('depto',$nombre_viv);
$templateWord->setValue('estacionamiento',$estacionamiento_viv);
$templateWord->setValue('bodega',$bodega_viv);
$templateWord->setValue('banco',$nombre_ban);
$templateWord->setValue('cuenta',$numero_cuenta_pro);
$templateWord->setValue('direccion',$direccion_pro);
$templateWord->setValue('ciudad',$nombre_com);


// --- Guardamos el documento
$templateWord->saveAs('prueba1'.$nombre.'.docx');

header("Content-Disposition: attachment; filename=prueba1.docx; charset=iso-8859-1");
echo file_get_contents('prueba1'.$nombre.'.docx');     
?>