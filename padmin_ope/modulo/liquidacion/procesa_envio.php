<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
require '../../class/phpmailer/class.phpmailer.php';
$conexion = new conexion();

$idres = $_POST["idres"];


$consulta =  
    " 
    SELECT
        pro.correo_pro,
        pro.correo2_pro,
        pro.nombre_pro,
        pro.apellido_paterno_pro,
        pro.apellido_materno_pro
    FROM
        reserva_reserva AS res,
        propietario_vivienda_propietario AS pro_viv,
        propietario_propietario AS pro
    WHERE
        res.id_res = ".$idres." AND
        res.id_viv = pro_viv.id_viv AND
        pro_viv.id_pro = pro.id_pro
    "; 
$conexion->consulta($consulta); 
$fila_consulta = $conexion->extraer_registro_unico(); 

$correo_pro = $fila_consulta['correo_pro'];
$correo2_pro = $fila_consulta['correo2_pro'];
$nombre_pro = utf8_encode($fila_consulta["nombre_pro"]);
$apellido_materno_pro = utf8_encode($fila_consulta["apellido_materno_pro"]);
$apellido_paterno_pro = utf8_encode($fila_consulta["apellido_paterno_pro"]);
$nombre_mail = $nombre_pro.' '.$apellido_paterno_pro.' '.$apellido_materno_pro;

$automatico="
<table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
  <tr>
    <td align='left'><img src='http://www.administradorapacifico.cl/img/logo-top.png'></td>
  </tr>
  <tr>
    <td style='padding:10px; line-height:20px; font-size:13px;'>
    Estimado ".$nombre_mail.",<br>
    Hemos generado una liquidación de arriendo de su propiedad.<br>Puede ingresar a revisarla en:<br>
    <a href='http://www.administradorapacifico.cl/plataforma' target='_blank' style='color: #000000'>Ingreso Plataforma</a>
    </td>
  </tr>
  <tr height='28'>
    <td style='font-size:11px; background-color:#0071bc; color:#CCC; text-align:center;'>Administradora Pacífico <a href='http://www.administradorapacifico.cl' target='_blank' style='color: #FFF'>www.administradorapacifico.cl</a></td>
  </tr>
</table>
";
//-------------------   OBJETOS CORREO

$mail_automatico = new phpmailer();
$mail_automatico->CharSet = 'UTF-8';

$mail_automatico->PluginDir = "../../class/phpmailer/";
$mail_automatico->Mailer = "smtp";
$mail_automatico->Host = "mail.administradorapacifico.cl";
$mail_automatico->SMTPAuth = true;
$mail_automatico->Username = "web@administradorapacifico.cl";
$mail_automatico->Password = "web2015,";
$mail_automatico->From = "web@administradorapacifico.cl";
$mail_automatico->FromName = "Administradora Pacífico";
$mail_automatico->Timeout=60;


$correo_empresa = "adebia@administradorapacifico.cl";
// $correo_empresa = "brunomailcasa@gmail.com";


$mail_automatico->AddAddress($correo_pro);
$mail_automatico->AddAddress($correo2_pro);
$mail_automatico->AddCC($correo_empresa);
$mail_automatico->Subject = "Administradora Pacífico - Liquidación Arriendo";
$mail_automatico->Body = $automatico;
$mail_automatico->AddReplyTo($correo_empresa);
$mail_automatico->Send();

$conexion->cerrar();

?> 
