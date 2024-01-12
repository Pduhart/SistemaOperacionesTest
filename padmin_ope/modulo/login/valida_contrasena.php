<?
session_start();
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$correo_usu = $_POST["correo_usu"];
$consulta = 
	"
	SELECT 
		*
	FROM 
		usuario_usuario 
	WHERE 
		correo_usu = '" . $correo_usu . "' AND
		id_est_usu = 1
	";
$conexion->consulta($consulta);
$cantidad = $conexion->total();
if($cantidad > 0){
	while ($fila = $conexion->extraer_registro()) {
    	$nombre_usuario_sesion = $fila["nombre_usu"];
		$id_usuario = $fila["id_usu"];
		$contrasena_usuario = $fila["contrasena_usu"];
	}
	//-------------------------------------------------------------------------------------------------------
	$email_msg1  = '<table align="center" width="90%" cellpadding=3>';
	$email_msg1 .= '<tr><td style="background-color:#e2efd9; border:1px solid #a8d08d; font-family:Arial; font-size:15px; color:#000; font-weight:bold;">';
	$email_msg1 .= '&nbsp;&nbsp;&nbsp;Recuperación de contraseña panel de administración</td></tr>';
	$email_msg1 .= '<tr><td style="background-color:#fff; border:1px solid #88ac70;">';
	//$email_msg1 .= 'Se ha ingresado una nueva solicitud<br>';
	$email_msg1 .= '--------------------------------------------------<br>';
	$email_msg1 .= '<b>Usuario:</b> '.$nombre_usuario_sesion.'<br>';
	$email_msg1 .= '<b>Correo:</b> '.$correo_usu.'<br>';
	$email_msg1 .= '<b>Contraseña:</b> '.$contrasena_usuario.'<br>';
	$email_msg1 .= '--------------------------------------------------<br>';
	$email_msg1 .= '<br><br></td></tr></table>';
	$headers1  = "From: Recuperación de contraseña <rodrigo.gomez.cerda@gmail.com>\r\n";
	$headers1 .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	$headers1 .= "MIME-version: 1.0\r\n";
	//
	//$headers1 .= "Reply-To: <".$_POST['mail'].">\n";
	$asunto1 = 'Recuperación de contraseña panel de administración';
	$para2= $correo_usu;
	require "../../class/phpmailer/class.phpmailer.php";
 	$mail = new phpmailer();
	 
	$mail->PluginDir = "../../class/phpmailer/";
	 
	$mail->Mailer = "smtp";
	 
	//Asignamos a Host el nombre de nuestro servidor smtp
	$mail->Host = "mail.costanortepropiedades.cl";
	 
	$mail->SMTPAuth = true;
	 
	$mail->Username = "prueba@costanortepropiedades.cl";
	 
	$mail->Password = "prueba";
	 
	$mail->From = "prueba@costanortepropiedades.cl";
	
	 
	$mail->FromName = "Panel administración";
	 
	//el valor por defecto 10 de Timeout es un poco escaso dado que voy a usar 
	//una cuenta gratuita, por tanto lo pongo a 30  
	$mail->Timeout=30;
	 
	//Indicamos cual es la dirección de destino del correo
	$mail->AddAddress($para2);
	 
	$mail->Subject = "Recuperación de contraseña panel administración";
	 
	$mail->Body = $email_msg1;
	 
	$exito = $mail->Send();
	$jsondata['envio'] = 1;
	echo json_encode($jsondata);
	exit();
}
else{
	$jsondata['envio'] = 2;
	echo json_encode($jsondata);
	exit();
}
?>