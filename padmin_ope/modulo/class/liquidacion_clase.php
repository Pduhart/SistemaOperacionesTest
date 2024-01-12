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
		
		$consulta = "UPDATE reserva_reserva SET fecha_deposito_res = ?, documento_comprobante_res = ?, programa_base_res = ? WHERE id_res = ?";
			
		$conexion->consulta_form($consulta,array($this->fecha_deposito_res,$this->documento_comprobante_res,$this->programa_base_res,$this->id_res));


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
	public function liquidacion_delete($id_cierre){
		$conexion = new conexion();
		
		$consulta = 
			"
			SELECT 
				ven_cie.id_ven, 
				ven_cie.id_est_ven,
				ven.cotizacion_ven 
			FROM 
				cierre_venta_cierre AS ven_cie 
				INNER JOIN venta_venta AS ven ON ven.id_ven = ven_cie.id_ven
			WHERE 
				ven_cie.id_cie = ?
			";

		$conexion->consulta_form($consulta,array($id_cierre));
		$fila_consulta = $conexion->extraer_registro(); //debería reversar lo adicional
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
            	// revisa si la venta avanzó de estado
            	if($fila['id_est_ven']==4) {
            		$consulta_avance = 
		                "
		                SELECT
		                    id_est_ven
		                FROM
		                    venta_estado_historial_venta
		                WHERE
		                    id_ven = ? AND id_est_ven = 6
		                ";
		            $conexion->consulta_form($consulta_avance,array($fila['id_ven']));
		            $existe_con_seis = $conexion->total();
		            if($existe_con_seis > 0) {
		            	$estado_actual = 6;
		            } else {
		            	$estado_actual = 4;
		            }
            	} else {
            		$estado_actual = $fila['id_est_ven'];
            	}

            	$consulta = "UPDATE venta_venta SET id_est_ven = ? WHERE id_ven = ?";	
				$conexion->consulta_form($consulta,array($estado_actual,$fila['id_ven']));

				$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";	
				$conexion->consulta_form($consulta,array($estado_actual,$fila['cotizacion_ven']));
				
				$consulta = "DELETE FROM bono_venta_bono WHERE id_ven = ?";
				$conexion->consulta_form($consulta,array($fila['id_ven']));
            }
        }
		

		$consulta = "DELETE FROM cierre_bono_cierre WHERE id_cie = ?";
		$conexion->consulta_form($consulta,array($id_cierre));

		$consulta = "DELETE FROM cierre_venta_cierre WHERE id_cie = ?";
		$conexion->consulta_form($consulta,array($id_cierre));

		$consulta = "DELETE FROM cierre_bono_cierre_venta WHERE id_cie = ?";
		$conexion->consulta_form($consulta,array($id_cierre));

		$consulta = "DELETE FROM cierre_cierre WHERE id_cie = ?";
		$conexion->consulta_form($consulta,array($id_cierre));


		$conexion->cerrar();
	}
	
	
	
}
?>
