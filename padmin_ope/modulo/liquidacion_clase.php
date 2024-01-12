<?php
require "../../config.php";
include("../../class/conexion.php");
require '../../class/phpmailer/class.phpmailer.php';
class liquidacion
{
	private $id_res;
	private $id_est_res;
	private $fecha_deposito_res;
	private $documento_comprobante_res;
	private $programa_base_res;
	

	function __construct(){
		
	}
	//Creacion del objeto reserva
	function liquidacion_crea($id_res,$id_est_res,$fecha_deposito_res,$documento_comprobante_res,$programa_base_res){
		$this->id_res = $id_res;
		$this->id_est_res = $id_est_res;
		$this->fecha_deposito_res = $fecha_deposito_res;
		$this->documento_comprobante_res = $documento_comprobante_res;
		$this->programa_base_res = $programa_base_res;
	}
	//funcion de insercion
	public function liquidacion_insert(){
		$conexion = new conexion();
		
		$consulta = "UPDATE reserva_reserva SET fecha_deposito_res = ?, documento_comprobante_res = ?, id_est_res = ?, programa_base_res = ? WHERE id_res = ?";
			
		$conexion->consulta_form($consulta,array($this->fecha_deposito_res,$this->documento_comprobante_res,$this->id_est_res,$this->programa_base_res,$this->id_res));


            // $consulta = 
            //     "
            //     SELECT 
            //         pro.correo_pro,
            //         pro.nombre_pro,
            //         pro.apellido_materno_pro,
            //         pro.apellido_paterno_pro
            //     FROM
            //         propietario_propietario AS pro,
            //         propietario_vivienda_propietario AS pro_viv,
            //         reserva_reserva AS res
            //     WHERE 
            //         pro.id_pro = pro_viv.id_pro AND
            //         pro_viv.id_viv = res.id_viv AND
            //         res.id_res = ".$this->id_res."
            //     ";
            // $conexion->consulta($consulta);
            // $fila = $conexion->extraer_registro_unico();
            // $correo_pro = utf8_encode($fila["correo_pro"]);
            // $nombre_pro = utf8_encode($fila["nombre_pro"]);
            // $apellido_materno_pro = utf8_encode($fila["apellido_materno_pro"]);
            // $apellido_paterno_pro = utf8_encode($fila["apellido_paterno_pro"]);
            // $nombre_mail = $nombre_pro.' '.$apellido_paterno_pro.' '.$apellido_materno_pro;
		
		// $automatico="
		// <table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
		//   <tr>
		//     <td align='left'><img src='http://www.administradorapacifico.cl/img/logo-top.png'></td>
		//   </tr>
		//   <tr>
		//     <td style='padding:10px; line-height:20px; font-size:13px;'>
		//     Estimado ".$nombre_mail.",<br>
		//     Hemos generado una liquidación de arriendo de su propiedad.<br>Puede ingresar a revisarla en:<br>
		//     <a href='http://www.administradorapacifico.cl/plataforma' target='_blank' style='color: #FFF'>Ingreso Plataforma</a>
		//     </td>
		//   </tr>
		//   <tr height='28'>
		//     <td style='font-size:11px; background-color:#0071bc; color:#CCC; text-align:center;'>Administradora Pacífico <a href='http://www.administradorapacifico.cl' target='_blank' style='color: #FFF'>www.administradorapacifico.cl</a></td>
		//   </tr>
		// </table>
		// ";
  //       //-------------------   OBJETOS CORREO

		// $mail_automatico = new phpmailer();
		// $mail_automatico->CharSet = 'UTF-8';

		// $mail_automatico->PluginDir = "../../class/phpmailer/";
		// $mail_automatico->Mailer = "smtp";
		// $mail_automatico->Host = "mail.administradorapacifico.cl";
		// $mail_automatico->SMTPAuth = true;
		// $mail_automatico->Username = "web@administradorapacifico.cl";
		// $mail_automatico->Password = "web2015,";
		// $mail_automatico->From = "web@administradorapacifico.cl";
		// $mail_automatico->FromName = "Administradora Pacífico";
		// $mail_automatico->Timeout=60;


		// $correo_empresa = "adebia@administradorapacifico.cl";


	 //    $mail_automatico->AddAddress($correo_pro);
	 //    $mail_automatico->AddCC($correo_empresa);
	 //    $mail_automatico->Subject = "Administradora Pacífico - Liquidación Arriendo";
	 //    $mail_automatico->Body = $automatico;
	 //    $mail_automatico->AddReplyTo($correo_empresa);
	    // $mail_automatico->Send();

		$conexion->cerrar();
	}
	
	
	
	//funcion de eliminacion
	public function liquidacion_delete($id_reserva){
		$conexion = new conexion();
		
		$valor_servicio_acumulado = 0;

		$consulta = "SELECT cantidad_dia_res, cantidad_pasajero_res, monto_total_res, monto_total_base_res, monto_comision_res, monto_comision_base_res FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_dia_res = $fila["cantidad_dia_res"];
		$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
		$monto_total_res = $fila["monto_total_res"];
		$monto_total_base_res = $fila["monto_total_base_res"];
		$monto_comision_res = $fila["monto_comision_res"];
		$monto_comision_base_res = $fila["monto_comision_base_res"];

		$consulta = "SELECT id_tip_cob, valor_ser_int_res FROM reserva_servicio_interno_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila_consulta = $conexion->extraer_registro(); //debería reversar lo adicional
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
            	$id_tip_cob = $fila['id_tip_cob'];
				$valor_ser_int_res = $fila['valor_ser_int_res'];

				switch ($fila['id_tip_cob']) {
					case 1:
						// unico
						$valor_servicio = $valor_ser_int_res;
						break;
					case 2:
						// dia 
						$valor_servicio = $valor_ser_int_res * $cantidad_dia_res;
						break;
					case 3:
						// persona
						$valor_servicio = $valor_ser_int_res * $cantidad_pasajero_res;
						break;
					case 4:
						// dia/persona
						$valor_cantidad_persona = $valor_ser_int_res * $cantidad_pasajero_res;
						$valor_servicio = $valor_cantidad_persona * $cantidad_dia_res;
						break;
					
				}
				$valor_servicio_acumulado = $valor_servicio_acumulado + $valor_servicio;
            }
        }
		
		$monto_total_res = $monto_total_res + $valor_servicio_acumulado;
		$monto_total_base_res = $monto_total_base_res + $valor_servicio_acumulado;
		
		$monto_total_res = $monto_total_res + $monto_comision_res;
		$monto_total_base_res = $monto_total_base_res + $monto_comision_base_res;

		$consulta = "UPDATE reserva_reserva SET id_est_res = ?, monto_interno_res = ?, monto_total_res = ?, monto_total_base_res = ? WHERE id_res = ?";	
		$conexion->consulta_form($consulta,array(2,0,$monto_total_res,$monto_total_base_res,$id_reserva));

		$consulta = "DELETE FROM reserva_servicio_interno_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		
		$conexion->cerrar();
	}
	
	
	public function liquidacion_insert_servicio($id_reserva, $id_ser){
		$conexion = new conexion();
		
		$consulta = "SELECT id_tip_cob, valor_alto_int_ser, valor_medio_int_ser, valor_bajo_int_ser, nombre_int_ser FROM servicio_interno_servicio WHERE id_int_ser = ?";
		$conexion->consulta_form($consulta,array($id_ser));
		$fila = $conexion->extraer_registro_unico();
		$id_tip_cob = $fila['id_tip_cob'];

		$valor_alto_int_ser = $fila['valor_alto_int_ser'];
		$valor_medio_int_ser = $fila['valor_medio_int_ser'];
		$valor_bajo_int_ser = $fila['valor_bajo_int_ser'];
		$nombre_int_ser = $fila['nombre_int_ser'];

		$consulta = "SELECT id_tip_res, cantidad_dia_res, cantidad_pasajero_res FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_dia_res = $fila["cantidad_dia_res"];
		$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
		$id_tip_res = $fila["id_tip_res"];

		if($id_tip_res == 1){
			$valor_int_ser = $valor_alto_int_ser;
		}
		else if($id_tip_res == 2){
			$valor_int_ser = $valor_medio_int_ser;
		}
		else{
			$valor_int_ser = $valor_bajo_int_ser;
		}


		switch ($id_tip_cob) {
			case 1:
				// unico
				$valor_servicio = $valor_int_ser;
				break;
			case 2:
				// dia 
				$valor_servicio = $valor_int_ser * $cantidad_dia_res;
				break;
			case 3:
				// persona
				$valor_servicio = $valor_int_ser * $cantidad_pasajero_res;
				break;
			case 4:
				// dia/persona
				$valor_cantidad_persona = $valor_int_ser * $cantidad_pasajero_res;
				$valor_servicio = $valor_cantidad_persona * $cantidad_dia_res;
				break;
			
		}
		
		$valor_servicio = $valor_int_ser;

		$consulta="INSERT INTO reserva_servicio_interno_reserva VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_reserva,$id_ser,$id_tip_cob,$valor_servicio,0,$nombre_int_ser));
		$conexion->cerrar();
	}
	
	public function liquidacion_insert_servicio_carro($id_reserva, $id_ser,$valor,$detalle){
		$conexion = new conexion();
		$id_tip_cob = 1;
		$nombre_int_ser = $detalle;
		$valor_servicio = $valor;
		$consulta="INSERT INTO reserva_servicio_interno_reserva VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_reserva,$id_ser,$id_tip_cob,$valor_servicio,1,$nombre_int_ser));
		$conexion->cerrar();
	}

	public function liquidacion_update_monto($id_reserva){
		$conexion = new conexion();
		$valor_servicio_acumulado = 0;

		$consulta = "SELECT cantidad_dia_res, cantidad_pasajero_res, monto_total_res, monto_total_base_res, monto_comision_res, monto_comision_base_res  FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_dia_res = $fila["cantidad_dia_res"];
		$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
		$monto_total_res = $fila["monto_total_res"];
		$monto_total_base_res = $fila["monto_total_base_res"];
		$monto_comision_res = $fila["monto_comision_res"];
		$monto_comision_base_res = $fila["monto_comision_base_res"];

		$consulta = "SELECT id_tip_cob, valor_ser_int_res FROM reserva_servicio_interno_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
            	$id_tip_cob = $fila['id_tip_cob'];
				$valor_ser_int_res = $fila['valor_ser_int_res'];

				switch ($fila['id_tip_cob']) {
					case 1:
						// unico
						$valor_servicio = $valor_ser_int_res;
						break;
					case 2:
						// dia 
						$valor_servicio = $valor_ser_int_res * $cantidad_dia_res;
						break;
					case 3:
						// persona
						$valor_servicio = $valor_ser_int_res * $cantidad_pasajero_res;
						break;
					case 4:
						// dia/persona
						$valor_cantidad_persona = $valor_ser_int_res * $cantidad_pasajero_res;
						$valor_servicio = $valor_cantidad_persona * $cantidad_dia_res;
						break;
					
				}
				$valor_servicio_acumulado = $valor_servicio_acumulado + $valor_servicio;
            }
        }
		
		$monto_total_res = $monto_total_res - $valor_servicio_acumulado;
		$monto_total_base_res = $monto_total_base_res - $valor_servicio_acumulado;

		$monto_total_res = $monto_total_res - $monto_comision_res;
		$monto_total_base_res = $monto_total_base_res - $monto_comision_base_res;

		$consulta = "UPDATE reserva_reserva SET monto_interno_res = ?, monto_total_res = ?, monto_total_base_res = ? WHERE id_res = ?";	
		$conexion->consulta_form($consulta,array($valor_servicio_acumulado,$monto_total_res,$monto_total_base_res,$id_reserva));
		
		$conexion->cerrar();
	}
}
?>
