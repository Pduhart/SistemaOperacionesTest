<?php
require "../../config.php";
include("../../class/conexion.php");
require '../../class/phpmailer/class.phpmailer.php';
date_default_timezone_set('America/Santiago');
class propietario
{
	private $id_nac;
	private $id_com;
	private $id_reg;
	private $id_sex;
	private $id_civ;
	private $id_est;
	private $id_prof;
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
	private $direccion_pro;
	private $direccion_trabajo_pro;
	private $fecha_nacimiento;
	private $profesion_promesa;

	function __construct(){
		
	}
	//Creacion del objeto propietario
	function propietario_crea($id_nac,$id_reg,$id_sex,$id_civ,$id_est,$id_prof,$id_est_pro,$rut_pro,$pasaporte_pro,$nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$fono_pro,$fono2_pro,$correo_pro,$correo2_pro,$direccion_pro,$direccion_trabajo_pro,$fecha_nacimiento,$id_com,$profesion_promesa){
		$this->id_nac = $id_nac;
		$this->id_com = $id_com;
		$this->id_reg = $id_reg;
		$this->id_sex = $id_sex;
		$this->id_civ = $id_civ;
		
		if ($id_com=='') {
			$id_com = 30;
		}
		
		$this->id_est = $id_est;
		$this->id_prof = $id_prof;
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
		$this->direccion_pro = $direccion_pro;
		$this->direccion_trabajo_pro = $direccion_trabajo_pro;
		$this->fecha_nacimiento = $fecha_nacimiento;
		$this->profesion_promesa = $profesion_promesa;
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

		// $consulta = "SELECT rut_pro FROM propietario_propietario WHERE pasaporte_pro = ? AND pasaporte_pro <> '' ";
		// $conexion->consulta_form($consulta,array($this->pasaporte_pro));
		// $cantidad = $conexion->total();
		// if($cantidad > 0){
		// 	$jsondata['envio'] = 2;
		// 	echo json_encode($jsondata);
		// 	exit();
		// }

		// insert propietario
		$consulta = "INSERT INTO propietario_propietario VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//27
		$conexion->consulta_form($consulta,array(0,$this->id_nac,$this->id_reg,$this->id_sex,$this->id_civ,$this->id_est,$this->id_prof,$this->id_est_pro,$this->rut_pro,$this->pasaporte_pro,$this->nombre_pro,$this->nombre2_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$this->fono_pro,$this->fono2_pro,$this->correo_pro,$this->correo2_pro,$this->direccion_pro,$this->direccion_trabajo_pro,$this->fecha_nacimiento,$this->id_com,0,null,0,null,$this->profesion_promesa));
		$id = $conexion->ultimo_id();


		if($_SESSION["sesion_perfil_panel"] == 4){
			$consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
			$conexion->consulta_form($consulta,array(0,$_SESSION["sesion_id_vend"],$id));	
		}

		// revisar si está en lista sino agregar
		// Agregar cliente a última lista
		$consulta = 
			"
			SELECT 
				id_cor_lis
			FROM
				lista_correo_lista
			WHERE 
				correo_cor_lis = ?
			";
		$conexion->consulta_form($consulta,array($this->correo_pro));
		$ya_estaba = $conexion->total();

		if($ya_estaba==0){
			$consulta = 
				"
				SELECT 
					lis.id_lis,
					(SELECT IFNULL(COUNT(id_cor_lis),0) FROM lista_correo_lista AS b WHERE b.id_lis = lis.id_lis) AS total
				FROM
					lista_lista AS lis
				ORDER BY  
					lis.id_lis 
				DESC
				LIMIT 0,1
				";
			$conexion->consulta($consulta);
			$fila_lista = $conexion->extraer_registro_unico();
			if($fila_lista["total"] < 950){
				$consulta = "INSERT INTO lista_correo_lista VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$fila_lista["id_lis"],$this->correo_pro));		
			}
			else{
				$consulta = "INSERT INTO lista_lista VALUES(?,?)";
				$ultimoIdInsert = $conexion->ultimo_id();
				$conexion->consulta_form($consulta,array(0,"Lista_".$ultimoIdInsert));
				$consulta = "INSERT INTO lista_correo_lista VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$ultimoIdInsert,$this->correo_pro));				
			}
		}
			

		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function propietario_update($id){
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
		
		
		$consulta = "UPDATE propietario_propietario SET id_nac = ?, id_reg = ?, id_sex = ?, id_civ = ?, id_est = ?, id_prof = ?, rut_pro = ?, nombre_pro = ?, nombre2_pro = ?, apellido_paterno_pro = ?, apellido_materno_pro = ?, fono_pro = ?, fono2_pro = ?, correo_pro = ?, correo2_pro = ?, direccion_pro = ?, direccion_trabajo_pro = ?, fecha_nacimiento_pro = ?, id_com = ?, profesion_promesa_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($this->id_nac,$this->id_reg,$this->id_sex,$this->id_civ,$this->id_est,$this->id_prof,$this->rut_pro,$this->nombre_pro,$this->nombre2_pro,$this->apellido_paterno_pro,$this->apellido_materno_pro,$this->fono_pro,$this->fono2_pro,$this->correo_pro,$this->correo2_pro,$this->direccion_pro,$this->direccion_trabajo_pro,$this->fecha_nacimiento,$this->id_com,$this->profesion_promesa,$id));

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
		}
		else{
			$consulta = "UPDATE propietario_propietario SET id_est_pro = 1 WHERE id_pro = ?";
		}
		$conexion->consulta_form($consulta,array($id));
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

    public function recupera_id_campana(){
		$conexion = new conexion();
		$consulta="SELECT id_cam FROM campana_mail_campana ORDER BY id_cam DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_cam'];
		$conexion->cerrar();
		return $id;
    }

	// Método GET para recuperar campaña
	public function getRecuperaIdCampana(){
		$this->recupera_id_campana();
	}

    private function recupera_titulo_campana($codigo){
		$conexion = new conexion();
		$consulta="SELECT titulo_cam_pla FROM campana_plantilla_campana WHERE codigo_cam_pla = ? AND id_est_cam_pla = 1";
		$conexion->consulta_form($consulta,array($codigo));
		$fila = $conexion->extraer_registro_unico();
		$titulo_cam_pla = $fila['titulo_cam_pla'];
		$conexion->cerrar();
		return $titulo_cam_pla;
    }

	// Método GET para recuperar titulo de campaña
	public function getRecuperaTituloCampana($codigo){
		$this->recupera_titulo_campana($codigo);
	}

    public function recupera_email_vendedor($id){
		$conexion = new conexion();
		$consulta = "SELECT correo_vend FROM vendedor_vendedor WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$correo_vend = utf8_decode($fila["correo_vend"]);		
		$conexion->cerrar();
		return $correo_vend;
    }

	// Método GET para recuperar email de vendedor
	public function getRecuperaEmailVendedor($id){
		$this->recupera_email_vendedor($id);
	}

    public function recupera_nombre_vendedor($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_vend, apellido_paterno_vend FROM vendedor_vendedor WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_vend = utf8_decode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);		
		$conexion->cerrar();
		return $nombre_vend;
    }

	// Método GET para recuperar nombre del vendedor
	public function getRecuperaNombreVendedor($id){
		$this->recupera_nombre_vendedor($id);
	}

    private function calcula_cantidad_mensual_mails($cant_a_enviar){
    	// $mes_actual = date("n");
    	// $anio_actual = date("Y");
		// $conexion = new conexion();
		// $consulta = "SELECT SUM(cantidad_cam) AS cant_enviados FROM campana_mail_campana WHERE MONTH(fecha_cam)=".$mes_actual." AND YEAR(fecha_cam) = ".$anio_actual."";
		// $conexion->consulta($consulta);
		// $fila = $conexion->extraer_registro_unico();
		// $cantidad_enviados_mes = $fila["cant_enviados"];
		
		// // busca parámetro cantidad
		// $consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = 109";
		// $conexion->consulta($consulta);
		// $fila = $conexion->extraer_registro_unico();
		// $tope_mensual = $fila["valor_par"];
		// $conexion->cerrar();
		// // calcula
		// $total_envios = $cantidad_enviados_mes + $cant_a_enviar;
		// if($total_envios > $tope_mensual) {
		// 	return $total_envios;
		// } else {
		// 	return 1;
		// }
		return 1;
    }

	// Método GET para calcular cantidad mensual de mails
	public function getCalculaCantidadMensualMails($cantAenviar){
		$this->calcula_cantidad_mensual_mails($cantAenviar);
	}
    
	public function propietario_insert_observacion($id,$id_usu,$fecha,$descripcion){
    	$conexion = new conexion();
		$today = date("Y-m-d");
    	$consulta = "INSERT INTO propietario_observacion_propietario VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$id_usu,$fecha,$descripcion));
		$count = $this->count_observation($id);

		$queryUpdate = "UPDATE propietario_propietario SET cantidad_obs_pro = '".$count."', fecha_obs_pro = '".$today."' WHERE id_pro = ".$id."";

		$conexion->consulta($queryUpdate);

		$conexion->cerrar();
    }

	private function count_observation($id){
		$conexion = new conexion();
		$consulta = "SELECT id_pro FROM propietario_observacion_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$count = $conexion->total();
		return $count;
	}
    //funcion de eliminacion
	public function propietario_delete_obs($id){
		$today = date("Y-m-d");
		$conexion = new conexion();
		$consulta = "DELETE FROM propietario_observacion_propietario WHERE id_obs_pro = $id";
		$conexion->consulta_form($consulta,array($id));

		$count = $this->count_observation($id);
		$queryUpdate = "UPDATE propietario_propietario SET cantidad_obs_pro = '".$count."' WHERE id_pro = ".$id."";
		$conexion->consulta($queryUpdate);

		$conexion->cerrar();
	}

    private function propietario_insert_campana($id_usu,$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$cantidad){
        $conexion = new conexion();
        
        $consulta = "INSERT INTO campana_mail_campana VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_usu,$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$cantidad));
		$conexion->cerrar();
    }

	// Método GET para guardar campaña
	public function getPropietarioInsertCampana($id_usu,$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$cantidad){
		$this->propietario_insert_campana($id_usu,$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$cantidad);
	}

    private function verifica_envio_repetido($id_usu,$fecha,$asunto,$enlace,$plantilla_cam,$cantidad,$id_emp){
    	$conexion = new conexion();
    	$consulta = "SELECT id_cam FROM campana_mail_campana ORDER BY id_cam DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id_cam = $fila["id_cam"];

		$fecha_dia = date("Y-m-d",strtotime($fecha));

        $consulta = "
        		SELECT 
        			cam.id_cam 
        		FROM
        			campana_mail_campana AS cam,
        			campana_destinatario_campana AS cam_des
        		WHERE
        			cam.id_usu = ".$id_usu." AND
        			cam.asunto_cam = '".$asunto."' AND
        			DATE(cam.fecha_cam) = '".$fecha_dia."' AND 
        			cam.cantidad_cam = ".$cantidad." AND
        			cam_des.id_cam = ".$id_cam." AND 
        			cam_des.id_pro IN (".$id_emp.")";
		$conexion->consulta($consulta);
		$existe_anterior = $conexion->total();
		return $existe_anterior;
    }

	//  Método GET para verificar envio repetido
	public function getVerificaEnvioRepetido($id_usu,$fecha,$asunto,$enlace,$plantilla_cam,$cantidad,$id_emp){
		$this->verifica_envio_repetido($id_usu,$fecha,$asunto,$enlace,$plantilla_cam,$cantidad,$id_emp);
	}

    private function propietario_insert_envio_masivo($id_cam,$id_emp,$cantidad,$asunto,$enlace,$plantilla_cam,$nombre_vend,$email_vend,$id_usu,$fecha,$descripcion){
        $conexion = new conexion();

		// die("--->".$consulta." - ".$existe_anterior);

        function contains($needle, $haystack) {
		    return strpos($haystack, $needle) !== false;
		}

        $contador = 0;
		$array_to = '[';



        while($contador <= $cantidad ){
             //      	$consulta = "INSERT INTO campana_destinatario_campana VALUES(?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$id_emp[$contador],$id_cam));

	        //       $consulta = "INSERT INTO propietario_observacion_propietario VALUES(?,?,?,?,?)";
			// $conexion->consulta_form($consulta,array(0,$id_emp[$contador],$id_usu,$fecha,$descripcion));

			$consulta = "SELECT nombre_pro, apellido_paterno_pro, correo_pro FROM propietario_propietario WHERE id_pro = ?";
			$conexion->consulta_form($consulta,array($id_emp[$contador]));
			$fila = $conexion->extraer_registro_unico();
			$nombre_pro = utf8_decode(trim($fila["nombre_pro"])." ".trim($fila["apellido_paterno_pro"]));		
			$email1 = utf8_decode($fila["correo_pro"]);
			$email1 = str_replace(" ","", $email1);
			$email1 = str_replace("\t","", $email1);
			$email1 = str_replace("?","", $email1);
			$email1 = strtolower($email1);
			$email1 = trim($email1);
			if ($email1<>'') {
				if (filter_var($email1, FILTER_VALIDATE_EMAIL)) {
					if (contains($email1, $array_to)) {
						# no hace nada
					} else {
						$nombre_pro_clean = str_replace(",","", $nombre_pro);
						$nombre_pro_clean = str_replace(".","", $nombre_pro_clean);
						$nombre_pro_clean = str_replace("'","", $nombre_pro_clean);

						$array_to .= '{"email": "'.$email1.'", "name": "'.$nombre_pro_clean.'"},';
					}
					// $array_to .= '{"email": "'.$email1.'", "name": "'.$nombre_pro.'"},';
				}
			}

			$contador++;
        }

        $array_to = substr($array_to, 0, -1);

        $array_to .= ']';

        
        // echo "llego aca 2".$array_to;
        $asunto_codificado = utf8_encode($asunto);
		
		// enviar el mail
		$curl = curl_init();


		$fields = '{
		  "personalizations": [
		    {
		      "to": [{"email": "'.$email_vend.'", "name": "'.$nombre_vend.'"}],
		      "bcc": '.$array_to.',
		      "dynamic_template_data": {
		        "subject": "'.$asunto_codificado.'",
		        "link_image": "'.$enlace.'"
		      },
		      "subject": "'.$asunto_codificado.'"
		    }
		  ],
		  "from": {
		    "email": "'.$email_vend.'",
		    "name": "'.$nombre_vend.'"
		  },
		  "reply_to": {
		    "email": "'.$email_vend.'",
		    "name": "'.$nombre_vend.'"
		  },
		  "asm":{
		        "group_id":15609
		  },
		  "template_id": "'.$plantilla_cam.'"
		}';


		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  // CURLOPT_POSTFIELDS => "{\"personalizations\":[{\"to\":[{\"email\":\"brunomailcasa@gmail.com\",\"name\":\"John Doe\"}],\"dynamic_template_data\":{\"verb\":\"HOLA\"\"subject\":\"Asunto Dinámico 1\",\"adjective\":\"CHAU\",\"link_image\":\"https://icpacifico.cl/\"},\"subject\":\"Asunto Dinámico 2\"}],\"from\":{\"email\":\"kmiranda@icpacifico.cl\",\"name\":\"Jeannise\"},\"reply_to\":{\"email\":\"kmiranda@icpacifico.cl\",\"name\":\"Jeannise\"},\"template_id\":\"d-a09b82088125446da5439024687ce688\"}",
		  CURLOPT_POSTFIELDS => $fields,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer "._ACCESS_TOKEN,
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  // echo "---------cURL Error #:" . $err;
			$conexion->cerrar();
		  	return "error:" . $err;
		} else {
			
			if ($response <> '') {
				$consulta = "DELETE FROM campana_mail_campana WHERE id_cam = ?";
				$conexion->consulta_form($consulta,array($id_cam));
				return "3>" . $response;
			} else {
				// aca hace la inserción de la campañas
				$contador = 0;
				$today = date("Y-m-d");
				while($contador <= $cantidad ){
		        	$consulta = "INSERT INTO campana_destinatario_campana VALUES(?,?,?)";
						$conexion->consulta_form($consulta,array(0,$id_emp[$contador],$id_cam));

			      $consulta = "INSERT INTO propietario_observacion_propietario VALUES(?,?,?,?,?)";
						$conexion->consulta_form($consulta,array(0,$id_emp[$contador],$id_usu,$fecha,$descripcion));

						$count = $this->count_observation($id_emp[$contador]);
						$queryUpdate = "UPDATE propietario_propietario SET cantidad_obs_pro = '".$count."', fecha_obs_pro = '".$today."' WHERE id_pro = ".$id_emp[$contador]."";
						$conexion->consulta($queryUpdate);
					$contador++;
		        }

		        $conexion->cerrar();
				return "1>" . $response;
			}
		}
    }

	// Método GET para guardar envio masivo
	public function getPropietarioInsertEnvioMasivo($id_cam,$id_emp,$cantidad,$asunto,$enlace,$plantilla_cam,$nombre_vend,$email_vend,$id_usu,$fecha,$descripcion){
		return $this->propietario_insert_envio_masivo($id_cam,$id_emp,$cantidad,$asunto,$enlace,$plantilla_cam,$nombre_vend,$email_vend,$id_usu,$fecha,$descripcion);
	}
}
?>
