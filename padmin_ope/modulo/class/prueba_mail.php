<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php
// prueba cxon mail

// $cabeceras = 'From: web@motorace.cl' . "\r\n" .
    // 'Reply-To: web@motorace.cl' . "\r\n" .
    // 'X-Mailer: PHP/' . phpversion();
// mail('bruno@proyectarse.com', 'Mi titulo', 'Prueba de mensaje',$cabeceras);
?>
<script>
// alert("listo");
</script>


<?php 
// prueba con mailer
require '../../class/phpmailer/class.phpmailer.php';
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

$mensaje="prueba con mailer";

$mail_automatico->AddAddress("brunomailcasa@gmail.com");
$mail_automatico->Subject = "Administradora Pacífico Registro Contacto";
$mail_automatico->Body = $mensaje;
$mail_automatico->Send();
 ?>
</body>
</html>