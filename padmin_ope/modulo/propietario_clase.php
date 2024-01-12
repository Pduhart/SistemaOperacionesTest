<?php
require "../../config.php";
include("../../class/conexion.php");
require '../../class/phpmailer/class.phpmailer.php';
class propietario
{
	private $id_nac;
	private $id_ban;
	private $id_tip_cue;
	private $id_com;
	private $id_est_pro;
	private $rut_pro;
	private $pasaporte_pro;
	private $nombre_pro;
	private $nombre2_pro;
	private $apellido_paterno_pro;
	private $apellido_materno_pro;
	private $fono_pro;
	private $fono2_pro;
	private $correo_pro;
	private $correo2_pro;
	private $numero_cuenta_pro;
	private $nombre_cuenta_pro;
	private $direccion_pro;

	function __construct(){
		
	}
	//Creacion del objeto propietario
	function propietario_crea($id_nac,$id_ban,$id_tip_cue,$id_com,$id_est_pro,$rut_pro,$pasaporte_pro,$nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$fono_pro,$fono2_pro,$correo_pro,$correo2_pro,$numero_cuenta_pro,$nombre_cuenta_pro,$direccion_pro){
		$this->id_nac = $id_nac;
		$this->id_ban = $id_ban;
		$this->id_tip_cue = $id_tip_cue;
		$this->id_com = $id_com;
		$this->id_est_pro = $id_est_pro;
		$this->rut_pro = $rut_pro;
		$this->pasaporte_pro = $pasaporte_pro;
		$this->nombre_pro = $nombre_pro;
		$this->nombre2_pro = $nombre2_pro;
		$this->apellido_paterno_pro = $apellido_paterno_pro;
		$this->apellido_materno_pro = $apellido_materno_pro;
		$this->fono_pro = $fono_pro;
		$this->fono2_pro = $fono2_pro;
		$this->correo_pro = $correo_pro;
		$this->correo2_pro = $correo2_pro;
		$this->numero_cuenta_pro = $numero_cuenta_pro;
		$this->nombre_cuenta_pro = $nombre_cuenta_pro;
		$this->direccion_pro = $direccion_pro;
	}
	//funcion de insercion
	public function propietario_insert(){
		$conexion = new conexion();

		$consulta = "SELECT rut_pro FROM propietario_propietario WHERE rut_pro = ? AND rut_pro <> '' ";
		$conexion->consulta_form($consulta,array($this->rut_pro));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "SELECT rut_pro FROM propietario_propietario WHERE pasaporte_pro = ? AND pasaporte_pro <> '' ";
		$conexion->consulta_form($consulta,array($this->pasaporte_pro));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		// insert propietario
		$consulta = "INSERT INTO propietario_propietario VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//16
		$conexion->consulta_form($consulta,array(0,$this->id_nac,$this->id_ban,$this->id_tip_cue,$this->id_com,$this->id_est_pro,$this->rut_pro,$this->pasaporte_pro,$this->nombre_pro,$this->nombre2_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$this->fono_pro,$this->fono2_pro,$this->correo_pro,$this->correo2_pro,$this->numero_cuenta_pro,$this->nombre_cuenta_pro,$this->direccion_pro));
		$id = $conexion->ultimo_id();

		if (!empty($this->rut_pro)) {
			$rut_usu = $this->rut_pro;
		}
		else{
			$rut_usu = $this->pasaporte_pro;
		}
		//INSERT USUARIO

		//usuario conrtraseña
		$letra_nombre = substr($this->nombre_pro,0,1);
		$usuario = $letra_nombre.$this->apellido_paterno_pro;
		$usuario = trim($usuario);
		
		$consulta = "SELECT usuario_usu FROM usuario_usuario WHERE nombre_usu LIKE '".$letra_nombre."%' AND apellido1_usu = '".$this->apellido_paterno_pro."'";
		
		$conexion->consulta($consulta);
		$cantidad = $conexion->total();
		
		if($cantidad > 0){
			$usuario = $usuario.$cantidad;		
		}		

		
 		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';  
		$usuario = strtr($usuario, utf8_decode($a), $b);
		$usuario = strtolower($usuario);
		$usuario = str_replace(' ','-',$usuario);

 		
		$rut = str_replace(".","", $rut_usu);
		$rut = substr($rut,0,4);
		$contrasena = $rut;
		//
		$consulta = "INSERT INTO usuario_usuario VALUES(?,?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,3,1,$rut_usu,$this->nombre_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$usuario,$contrasena,$this->correo_pro));
		$id_usuario = $conexion->ultimo_id();

		//MÓDULOS
		//propietario
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id_usuario.",302)";
		$conexion->consulta($consulta);
		//ticket
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id_usuario.",312)";
		$conexion->consulta($consulta);
		//bloqueo fechas
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id_usuario.",313)";
		$conexion->consulta($consulta);
		//Liquidación
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id_usuario.",310)";
		$conexion->consulta($consulta);

		// //PROCESOS
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",302,81)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",312,100)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",312,101)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",313,102)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",313,103)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id_usuario.",310,97)";
		$conexion->consulta($consulta);

		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function propietario_update($id,$contrasena){
		$conexion = new conexion();
		//VALIDACIÓN RUT
		$consulta = "SELECT rut_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$rut_propietario = $fila["rut_pro"];		
		
		if($this->rut_pro != $rut_propietario){
			$consulta = "SELECT rut_pro FROM propietario_propietario WHERE rut_pro = ?";
			$conexion->consulta_form($consulta,array($this->rut_propietario));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}
		//VALIDACIÓN PASAPORTE
		$consulta = "SELECT pasaporte_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$pasaporte_propietario = $fila["pasaporte_pro"];		
		
		if($this->pasaporte_pro != $pasaporte_propietario){
			$consulta = "SELECT pasaporte_pro FROM propietario_propietario WHERE pasaporte_pro = ?";
			$conexion->consulta_form($consulta,array($this->pasaporte_propietario));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}
		
		$consulta = "UPDATE propietario_propietario SET id_nac = ?, id_ban = ?, id_tip_cue = ?, id_com = ?, nombre_pro = ?, nombre2_pro = ?, apellido_paterno_pro = ?, apellido_materno_pro = ?, fono_pro = ?, fono2_pro = ?, correo_pro = ?, correo2_pro = ?, numero_cuenta_pro = ?, nombre_cuenta_pro = ?, rut_pro = ?, pasaporte_pro = ?, direccion_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($this->id_nac,$this->id_ban,$this->id_tip_cue,$this->id_com,$this->nombre_pro,$this->nombre2_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$this->fono_pro,$this->fono2_pro,$this->correo_pro,$this->correo2_pro,$this->numero_cuenta_pro,$this->nombre_cuenta_pro,$this->rut_pro,$this->pasaporte_pro,$this->direccion_pro,$id));

		$consulta = "UPDATE usuario_usuario SET nombre_usu = ?, apellido1_usu = ?, apellido2_usu = ?, contrasena_usu = ?, correo_usu = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($this->nombre_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$contrasena,$this->correo_pro,$id));

		$automatico="
		<table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
		  <tr>
		    <td align='left'><img src='http://www.administradorapacifico.cl/img/logo-top.png'></td>
		  </tr>
		  <tr>
		    <td style='padding:10px; line-height:20px; font-size:13px;'>
		    Estimado(a),<br>
		    El propietario:<br>
		    ".$this->nombre_pro." ".$this->nombre2_pro." ".$this->apellido_paterno_pro." ".$this->apellido_materno_pro.", ha modifcado su perfil. Revíselo en la plataforma<br>
		    <a href='http://www.administradorapacifico.cl/plataforma' target='_blank' style='color: #FFF'>Ingreso Plataforma</a>
		    </td>
		  </tr>
		  <tr height='28'>
		    <td style='font-size:11px; background-color:#0071bc; color:#CCC; text-align:center;'>Administradora Pacífico <a href='http://www.administradorapacifico.cl' target='_blank' style='color: #FFF'>www.administradorapacifico.cl</a></td>
		  </tr>
		</table>
		";
        //-------------------   OBJETOS CORREO
		if ($_SESSION["sesion_id_propietario_panel"]==$id) {
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


		    $mail_automatico->AddAddress($correo_pro);
		    $mail_automatico->AddCC($correo_empresa);
		    $mail_automatico->Subject = "Administradora Pacífico - Modificación Datos";
		    $mail_automatico->Body = $automatico;
		    $mail_automatico->AddReplyTo($correo_empresa);
		    // $mail_automatico->Send();
		}

		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function propietario_delete($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM propietario_propietario WHERE id_pro = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function propietario_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_propietario = $fila["id_est_pro"];	

		if($estado_propietario == 1){
			$consulta = "UPDATE propietario_propietario SET id_est_pro = 2 WHERE id_pro = ?";	
			$consulta_usu = "UPDATE usuario_usuario SET id_est_usu = 2 WHERE id_pro = ?";	
		}
		else{
			$consulta = "UPDATE propietario_propietario SET id_est_pro = 1 WHERE id_pro = ?";
			$consulta_usu = "UPDATE usuario_usuario SET id_est_usu = 1 WHERE id_pro = ?";	
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->consulta_form($consulta_usu,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_pro FROM propietario_propietario ORDER BY id_pro DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_pro'];
		$conexion->cerrar();
		return $id;
	}

	public function envia_datos(){
		$conexion = new conexion();
		$consulta = "SELECT usuario_usu, contrasena_usu, nombre_usu, apellido1_usu FROM usuario_usuario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$usuario_usu = utf8_encode($fila["usuario_usu"]);
		$contrasena_usu = utf8_encode($fila["contrasena_usu"]);
		$nombre_usu = utf8_encode($fila["nombre_usu"]);
		$apellido1_usu = utf8_encode($fila["apellido1_usu"]);

		$automatico="
		<table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
		  <tr>
		    <td align='left'><img src='http://www.administradorapacifico.cl/img/logo-top.png'></td>
		  </tr>
		  <tr>
		    <td style='padding:10px; line-height:20px; font-size:13px;'>
		    Estimado(a) ".$nombre_usu." ".$apellido1_usu.",<br>
		    Sus datos de acceso a la plataforma de Administradora Pacífico son:<br>
		    Usuario: ".$usuario_usu."<br>
		    Contraseña: ".$contrasena_usu."<br>
		    <a href='http://www.administradorapacifico.cl/plataforma' target='_blank' style='color: #FFF'>Ingreso Plataforma</a>
		    </td>
		  </tr>
		  <tr height='28'>
		    <td style='font-size:11px; background-color:#0071bc; color:#CCC; text-align:center;'>Administradora Pacífico <a href='http://www.administradorapacifico.cl' target='_blank' style='color: #FFF'>www.administradorapacifico.cl</a></td>
		  </tr>
		</table>
		";
        //-------------------   OBJETOS CORREO
		$consulta = "SELECT correo_pro, correo2_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$correo_pro = utf8_encode($fila["correo_pro"]);
		$correo2_pro = utf8_encode($fila["correo2_pro"]);

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


		$correo_cliente = $correo_pro;
		$correo_cliente2 = $correo2_pro;

		$correo_empresa = "adebia@administradorapacifico.cl";

	    $mail_automatico->AddAddress($correo_cliente);
	    if ($correo_cliente2<>'') {
	    	$mail_automatico->AddCC($correo_cliente2);
	    }
	    $mail_automatico->Subject = "Administradora Pacífico - Datos Acceso Plataforma";
	    $mail_automatico->Body = $automatico;
	    $mail_automatico->AddReplyTo($correo_empresa);
		$mail_automatico->Send();

		$conexion->cerrar();	
	}
}
?>
