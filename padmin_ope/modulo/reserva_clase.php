<?php
require "../../config.php";
include("../../class/conexion.php");
class reserva
{
	private $id_viv;
	private $id_prog;
	private $id_tip_res;
	private $id_arr;
	private $id_proc;
	private $id_usu;
	private $id_est_res;
	private $id_est_pag_res;
	private $fecha_res;
	private $fecha_desde_res;
	private $fecha_hasta_res;
	private $rut_propietario_res;
	private $nombre_propietario_res;
	private $apellido_paterno_propietario_res;
	private $apellido_materno_propietario_res;
	private $monto_total_res;
	private $monto_total_base_res;
	private $monto_dia_res;
	private $monto_dia_base_res;
	private $monto_interno_res;
	private $monto_adicional_res;
	private $monto_comision_res;
	private $monto_comision_base_res;
	private $cantidad_pasajero_res;
	private $cantidad_dia_res;
	private $documento_comprobante_res;
	private $documento_inventario_res;
	private $fecha_deposito_res;
	private $programa_base_res;
	private $descripcion_res;

	function __construct(){
		
	}
	//Creacion del objeto reserva
	function reserva_crea($id_viv,$id_prog,$id_tip_res,$id_arr,$id_proc,$id_usu,$id_est_res,$id_est_pag_res,$fecha_res,$fecha_desde_res,$fecha_hasta_res,$rut_propietario_res,$nombre_propietario_res,$apellido_paterno_propietario_res,$apellido_materno_propietario_res,$monto_total_res,$monto_total_base_res,$monto_dia_res,$monto_dia_base_res,$monto_interno_res,$monto_adicional_res,$monto_comision_res,$monto_comision_base_res,$cantidad_pasajero_res,$cantidad_dia_res,$documento_comprobante_res,$documento_inventario_res,$fecha_deposito_res,$programa_base_res,$descripcion_res){
		$this->id_viv = $id_viv;
		$this->id_prog = $id_prog;
		$this->id_tip_res = $id_tip_res;
		$this->id_arr = $id_arr;
		$this->id_proc = $id_proc;
		$this->id_usu = $id_usu;
		$this->id_est_res = $id_est_res;
		$this->id_est_pag_res = $id_est_pag_res;
		$this->fecha_res = $fecha_res;
		$this->fecha_desde_res = $fecha_desde_res;
		$this->fecha_hasta_res = $fecha_hasta_res;
		$this->rut_propietario_res = $rut_propietario_res;
		$this->nombre_propietario_res = $nombre_propietario_res;
		$this->apellido_paterno_propietario_res = $apellido_paterno_propietario_res;
		$this->apellido_materno_propietario_res = $apellido_materno_propietario_res;
		$this->monto_total_res = $monto_total_res;
		$this->monto_total_base_res = $monto_total_res;
		$this->monto_dia_res = $monto_dia_res;
		$this->monto_dia_base_res = $monto_dia_res;
		$this->monto_interno_res = $monto_interno_res;
		$this->monto_adicional_res = $monto_adicional_res;
		$this->monto_comision_res = $monto_comision_res;
		$this->monto_comision_base_res = $monto_comision_res;
		$this->cantidad_pasajero_res = $cantidad_pasajero_res;
		$this->cantidad_dia_res = $cantidad_dia_res;
		$this->documento_comprobante_res = $documento_comprobante_res;
		$this->documento_inventario_res = $documento_inventario_res;
		$this->fecha_deposito_res = $fecha_deposito_res;
		$this->programa_base_res = $programa_base_res;
		$this->descripcion_res = $descripcion_res;

	}
	//funcion de insercion
	public function reserva_insert(){
		$conexion = new conexion();
		//----- validacion disponibilidad
		$consulta = "SELECT * FROM reserva_reserva WHERE (id_est_res = 1 OR id_est_res = 2) AND id_viv = ".$this->id_viv."";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();

		$consulta = "SELECT id_viv, fecha_desde_fec_viv, fecha_hasta_fec_viv FROM vivienda_fecha_vivienda";
	    $conexion->consulta($consulta);
	    $fila_consulta_fecha = $conexion->extraer_registro();

		$fecha_desde_res = $this->fecha_desde_res;
		$fecha_hasta_res = $this->fecha_hasta_res;

		
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila) {	
		    	$fecha_desde = date("Y-m-d",strtotime($fila["fecha_desde_res"]));
		    	$fecha_hasta = date("Y-m-d",strtotime($fila["fecha_hasta_res"]));
		        

		        if($fecha_desde_res >= $fecha_desde && $fecha_desde_res < $fecha_hasta){
                    $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();

                }
                if($fecha_hasta_res > $fecha_desde && $fecha_hasta_res <= $fecha_hasta){
                    $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
                    
                }
                if($fecha_desde_res <= $fecha_desde && $fecha_hasta_res >= $fecha_hasta){
                    $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();

                }
			}
		}
		
		if(is_array($fila_consulta_fecha)){
            foreach ($fila_consulta_fecha as $fila_sub) { 
                if($fila["id_viv"] == $fila_sub["id_viv"]){
                    $fecha_desde = date("Y-m-d",strtotime($fila_sub["fecha_desde_fec_viv"]));
                    $fecha_hasta = date("Y-m-d",strtotime($fila_sub["fecha_hasta_fec_viv"]));
                    
                    if($fecha_desde_res >= $fecha_desde && $fecha_desde_res <= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                    if($fecha_hasta_res >= $fecha_desde && $fecha_hasta_res <= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                    if($fecha_desde_res <= $fecha_desde && $fecha_hasta_res >= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                }
            }
        }

		$consulta = 
			"
			SELECT 
				pro.pasaporte_pro,
				pro.rut_pro, 
				pro.nombre_pro, 
				pro.nombre2_pro, 
				pro.apellido_paterno_pro,
				pro.apellido_materno_pro
			FROM 
				propietario_propietario AS pro,
				propietario_vivienda_propietario AS viv_pro 
			WHERE 
				viv_pro.id_viv = ? AND
				viv_pro.id_pro = pro.id_pro
			";
		$conexion->consulta_form($consulta,array($this->id_viv));
		$fila_propietario = $conexion->extraer_registro_unico();
		$pasaporte_pro = $fila_propietario["pasaporte_pro"];
		$rut_pro = $fila_propietario["rut_pro"];
		$nombre_pro = $fila_propietario["nombre_pro"];
		$nombre2_pro = $fila_propietario["nombre2_pro"];
		$apellido_paterno_pro = $fila_propietario["apellido_paterno_pro"];
		$apellido_materno_pro = $fila_propietario["apellido_materno_pro"];
		if(!empty($pasaporte_pro)){
			$this->rut_propietario_res = $pasaporte_pro;
		}
		else{
			$this->rut_propietario_res = $rut_pro;
		}
		$this->nombre_propietario_res = $nombre_pro." ".$nombre2_pro;
		$this->apellido_paterno_propietario_res = $apellido_paterno_pro;
		$this->apellido_materno_propietario_res = $apellido_materno_pro;

		$consulta = "SELECT * FROM parametro_parametro WHERE id_par = ?";
		$conexion->consulta_form($consulta,array(1));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_par = $fila_programa["valor_par"];

		$valor_comision = $valor_par / 100;
		
		//------- PROGRAMA BASE ---------
		$consulta = "SELECT * FROM programa_programa WHERE id_prog = ?";
		$conexion->consulta_form($consulta,array(1));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_alto_prog_base = $fila_programa["valor_alto_prog"];
		$valor_medio_prog_base = $fila_programa["valor_medio_prog"];
		$valor_bajo_prog_base = $fila_programa["valor_bajo_prog"];
		if($this->id_tip_res == 1){
			$valor_noche_base = $valor_alto_prog_base;
		}
		else if($this->id_tip_res == 2){
			$valor_noche_base = $valor_medio_prog_base;
		}
		else{
			$valor_noche_base = $valor_bajo_prog_base;
		}
		//-------------------------------

		$consulta = "SELECT * FROM programa_programa WHERE id_prog = ?";
		$conexion->consulta_form($consulta,array($this->id_prog));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_alto_prog = $fila_programa["valor_alto_prog"];
		$valor_medio_prog = $fila_programa["valor_medio_prog"];
		$valor_bajo_prog = $fila_programa["valor_bajo_prog"];
		if($this->id_tip_res == 1){
			$valor_noche = $valor_alto_prog;
		}
		else if($this->id_tip_res == 2){
			$valor_noche = $valor_medio_prog;
		}
		else{
			$valor_noche = $valor_bajo_prog;
		}

		$fecha_desde_res = $this->fecha_desde_res;
		$fecha_hasta_res = $this->fecha_hasta_res;

		$this->monto_total_res = $valor_noche * $this->cantidad_dia_res;
		$this->monto_total_base_res = $valor_noche_base * $this->cantidad_dia_res;

		$this->monto_dia_res = $valor_noche;
		$this->monto_dia_base_res = $valor_noche_base;

		

		$this->monto_comision_res = $this->monto_total_res * $valor_comision;
		$this->monto_comision_base_res = $this->monto_total_base_res * $valor_comision;

		$this->monto_comision_res = $this->monto_comision_res * 1.19;
		$this->monto_comision_base_res = $this->monto_comision_base_res * 1.19;

		$consulta = "INSERT INTO reserva_reserva VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"; //31
		
		$conexion->consulta_form($consulta,array($_SESSION["codigo_reserva_panel"],$this->id_viv,$this->id_prog,$this->id_tip_res,$this->id_arr,$this->id_proc,$this->id_usu,$this->id_est_res,$this->id_est_pag_res,$this->fecha_res,$this->fecha_desde_res,$this->fecha_hasta_res,$this->rut_propietario_res,$this->nombre_propietario_res,$this->apellido_paterno_propietario_res,$this->apellido_materno_propietario_res,$this->monto_total_res,$this->monto_total_base_res,$this->monto_dia_res,$this->monto_dia_base_res,$this->monto_interno_res,$this->monto_adicional_res,$this->monto_comision_res,$this->monto_comision_base_res,$this->cantidad_pasajero_res,$this->cantidad_dia_res,$this->documento_comprobante_res,$this->documento_inventario_res,$this->fecha_deposito_res,$this->programa_base_res,$this->descripcion_res));
		$ultimoid = $conexion->ultimo_id();
		

        // $fecha_desde_res = date("d-m-Y",strtotime($this->fecha_desde_res));
        // $fecha_hasta_res = date("d-m-Y",strtotime($this->fecha_hasta_res));

        // $fecha_desde_res_mail = date("Y-m-d",strtotime($this->fecha_desde_res));
        // $fecha_hasta_res_mail = date("Y-m-d",strtotime($this->fecha_hasta_res));

        // boton Google
        // date_default_timezone_set('America/Santiago');
        // $fecha_desde = $fecha_desde_res_mail;
        // $fecha_hasta = $fecha_hasta_res_mail;

		// $start = new DateTime($fecha_desde.' '.$hora_desde.' '.date_default_timezone_get()); 
		// $end = new DateTime($fecha_hasta.' '.$hora_hasta.' '.date_default_timezone_get());
		// $dates = urlencode($start->format("Ymd\THis")) . "/" . urlencode($end->format("Ymd\THis"));

		// $id_reserva = $ultimoid;

        // $boton_google = "<a href='http://www.google.com/calendar/event?action=TEMPLATE&text=N Reserva: ".$id_reserva." - Patente: ".$patente_veh." - ".$marca_veh." - ".$modelo_veh.". Chofer: ".$reserva_chofer."&dates=".$dates."&details=".$codigo_uni."-".$codigo_pro."&location=".$this->rut_propietario_res."- Reservado por: ".$reserva_user."&trp=false&ctz=America/Santiago' target='_blank' rel='nofollow'><img src='https://i1.wp.com/www.google.com/calendar/images/ext/gc_button6_es.gif' alt='' border='0'></a>";

		// envio mail
		// $mensaje="
		// 	<table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
		// 	  <tr>
		// 	    <td align='left' colspan='2'><img src='assets/img/logo-grande.jpg' width='75'></td>
		// 	  </tr>
		// 	  <tr>
		// 	    <td colspan='2' style='padding:10px; line-height:20px; font-size:13px;'>
		// 		    <b>Reserva registrada en el Sistema de Vehículos</b><br />
		// 		    <hr>
		// 	    </td>
		// 	  </tr>
		// 	  <tr>
		// 	    <td style='padding:10px; line-height:20px; font-size:13px;' width='50%'>
		// 		    <b>Datos Reserva</b><br>
		// 		    <b>Nº Reserva:</b> ".$id_reserva."<br />  
		// 		    <b>Nombre:</b> ".$reserva_user."<br />  
		// 		    <b>Unidad / Proyecto:</b> ".$codigo_uni."-".$codigo_pro."<br />  
		// 		    <b>Vehículo:</b> ".$patente_veh." - ".$marca_veh." - ".$modelo_veh."<br />  
		// 		    <b>Fecha desde:</b> ".$fecha_desde_res." | <b>Hora desde:</b> ".$this->hora_desde_res." hrs.<br />
		// 		    <b>Fecha hasta:</b> ".$fecha_hasta_res." | <b>Hora hasta:</b> ".$this->hora_hasta_res." hrs.<br />
		// 		    <b>Chofer:</b> ".$reserva_chofer."<br>
		// 		    <b>Destino:</b> ".$this->rut_propietario_res."<br>
		// 	    </td>
		// 	    <td style='padding:10px; line-height:20px; font-size:13px;' valign='top'>
		// 		    <b>Traslada a:</b> ".$this->apellido_paterno_propietario_res."<br /><br />
		// 		    <b>".$boton_google."</b>
		// 	    </td>
		// 	  </tr>
		// 	  <tr height='28'>
		// 	    <td colspan='2' style='font-size:11px; background-color:#cc7f2a; color:#FFF; text-align:center;'>Sistema Vehículos | Universidad Católica del Norte</td>
		// 	  </tr>
		// 	</table>
		// 	";
		// $headers  = "From: Facultad de Cs. del Mar - Sistema Vehículos<web@ucn.cl>\r\n";
		// $headers .= "Content-Type: text/html; charset=UTF-8\n";
		// $asunto = 'Registro de Reserva Vehículo';

		// $mail = new PHPMailer;
		// //Tell PHPMailer to use SMTP
		// $mail->isSMTP();
		// //Enable SMTP debugging
		// // 0 = off (for production use)
		// // 1 = client messages
		// // 2 = client and server messages
		// $mail->SMTPDebug = 0;
		// //Ask for HTML-friendly debug output
		// $mail->Debugoutput = 'html';
		// //Set the hostname of the mail server
		// $mail->Host = 'smtp.gmail.com';
		// $mail->Port = 587;
		// //Set the encryption system to use - ssl (deprecated) or tls
		// $mail->SMTPSecure = 'tls';
		// //Whether to use SMTP authentication
		// $mail->SMTPAuth = true;
		// //Username to use for SMTP authentication - use full email address for gmail
		// $mail->Username = "web@proyectarse.com";
		// //Password to use for SMTP authentication
		// $mail->Password = "pass2014";
		// //Set who the message is to be sent from
		// $mail->setFrom('web@proyectarse.com', 'Sistema de Vehículos FCM');
		// //Set an alternative reply-to address
		// $mail->addReplyTo('bruno@proyectarse.com', 'Sistema de Vehículos FCM');
		// //Set who the message is to be sent to
		// if ($correo_usu<>'') {
		// 	$mail->addAddress($correo_usu);
		// }

		// if ($correo_chofer<>'') {
		// 	$mail->addAddress($correo_chofer);
		// }

		// $correo_domingo = "bruno@proyectarse.com";
		// $mail->addAddress($correo_domingo);

		// $mail->Subject = $asunto;

		// $mail->Body = $mensaje;

		// $mail->send();

		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function reserva_update($id){
		$conexion = new conexion();
		//----- validacion disponibilidad
		
		$consulta = "SELECT fecha_desde_res, fecha_hasta_res FROM reserva_reserva WHERE (id_est_res = 1 OR id_est_res = 2) AND id_viv = ".$this->id_viv." AND NOT id_res = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila_consulta = $conexion->extraer_registro();

		$fecha_desde_res = $this->fecha_desde_res;
		$fecha_hasta_res = $this->fecha_hasta_res;

		

		$consulta = "SELECT id_viv, fecha_desde_fec_viv, fecha_hasta_fec_viv FROM vivienda_fecha_vivienda";
	    $conexion->consulta($consulta);
	    $fila_consulta_fecha = $conexion->extraer_registro();

		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila) {	
		    	$fecha_desde = date("Y-m-d",strtotime($fila["fecha_desde_res"]));
		    	$fecha_hasta = date("Y-m-d",strtotime($fila["fecha_hasta_res"]));
		        
		        
		        if($fecha_desde_res > $fecha_desde && $fecha_desde_res < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		    	if($fecha_hasta_res > $fecha_desde && $fecha_hasta_res < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		        if($fecha_desde_res < $fecha_desde && $fecha_hasta_res > $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
			}
		}
		
		if(is_array($fila_consulta_fecha)){
            foreach ($fila_consulta_fecha as $fila_sub) { 
                if($fila["id_viv"] == $fila_sub["id_viv"]){
                    $fecha_desde = date("Y-m-d",strtotime($fila_sub["fecha_desde_fec_viv"]));
                    $fecha_hasta = date("Y-m-d",strtotime($fila_sub["fecha_hasta_fec_viv"]));
                    
                    if($fecha_desde_res >= $fecha_desde && $fecha_desde_res <= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                    if($fecha_hasta_res >= $fecha_desde && $fecha_hasta_res <= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                    if($fecha_desde_res <= $fecha_desde && $fecha_hasta_res >= $fecha_hasta){
                        $jsondata['envio'] = 2;
						echo json_encode($jsondata);
						exit();
                    }
                }
            }
        }

		$consulta = 
			"
			SELECT 
				pro.pasaporte_pro,
				pro.rut_pro, 
				pro.nombre_pro, 
				pro.nombre2_pro, 
				pro.apellido_paterno_pro,
				pro.apellido_materno_pro
			FROM 
				propietario_propietario AS pro,
				propietario_vivienda_propietario AS viv_pro 
			WHERE 
				viv_pro.id_viv = ? AND
				viv_pro.id_pro = pro.id_pro
			";
		$conexion->consulta_form($consulta,array($this->id_viv));
		$fila_propietario = $conexion->extraer_registro_unico();
		$pasaporte_pro = $fila_propietario["pasaporte_pro"];
		$rut_pro = $fila_propietario["rut_pro"];
		$nombre_pro = $fila_propietario["nombre_pro"];
		$nombre2_pro = $fila_propietario["nombre2_pro"];
		$apellido_paterno_pro = $fila_propietario["apellido_paterno_pro"];
		$apellido_materno_pro = $fila_propietario["apellido_materno_pro"];
		if(!empty($pasaporte_pro)){
			$this->rut_propietario_res = $pasaporte_pro;
		}
		else{
			$this->rut_propietario_res = $rut_pro;
		}
		$this->nombre_propietario_res = $nombre_pro." ".$nombre2_pro;
		$this->apellido_paterno_propietario_res = $apellido_paterno_pro;
		$this->apellido_materno_propietario_res = $apellido_materno_pro;

		//------- PROGRAMA BASE ---------
		$consulta = "SELECT * FROM programa_programa WHERE id_prog = ?";
		$conexion->consulta_form($consulta,array(1));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_alto_prog_base = $fila_programa["valor_alto_prog"];
		$valor_medio_prog_base = $fila_programa["valor_medio_prog"];
		$valor_bajo_prog_base = $fila_programa["valor_bajo_prog"];
		if($this->id_tip_res == 1){
			$valor_noche_base = $valor_alto_prog_base;
		}
		else if($this->id_tip_res == 2){
			$valor_noche_base = $valor_medio_prog_base;
		}
		else{
			$valor_noche_base = $valor_bajo_prog_base;
		}
		//-------------------------------

		$consulta = "SELECT * FROM parametro_parametro WHERE id_par = ?";
		$conexion->consulta_form($consulta,array(1));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_par = $fila_programa["valor_par"];

		$valor_comision = $valor_par / 100;

		$consulta = "SELECT * FROM programa_programa WHERE id_prog = ?";
		$conexion->consulta_form($consulta,array($this->id_prog));
		$fila_programa = $conexion->extraer_registro_unico();
		$valor_alto_prog = $fila_programa["valor_alto_prog"];
		$valor_medio_prog = $fila_programa["valor_medio_prog"];
		$valor_bajo_prog = $fila_programa["valor_bajo_prog"];
		if($this->id_tip_res == 1){
			$valor_noche = $valor_alto_prog;
		}
		else if($this->id_tip_res == 2){
			$valor_noche = $valor_medio_prog;
		}
		else{
			$valor_noche = $valor_bajo_prog;
		}

		$fecha_desde_res = $this->fecha_desde_res;
		$fecha_hasta_res = $this->fecha_hasta_res;

		$this->monto_total_res = $valor_noche * $this->cantidad_dia_res;
		$this->monto_total_base_res = $valor_noche_base * $this->cantidad_dia_res;

		$this->monto_dia_res = $valor_noche;
		$this->monto_dia_base_res = $valor_noche_base;

		$this->monto_comision_res = $this->monto_total_res * $valor_comision;
		$this->monto_comision_base_res = $this->monto_total_base_res * $valor_comision;

		$this->monto_comision_res = $this->monto_comision_res * 1.19;
		$this->monto_comision_base_res = $this->monto_comision_base_res * 1.19;


        // boton Google
  //       date_default_timezone_set('America/Santiago');
  //       $fecha_desde = $fecha_desde_res_mail;
  //       $hora_desde = $this->hora_desde_res;
  //       $fecha_hasta = $fecha_hasta_res_mail;
  //       $hora_hasta = $this->hora_hasta_res;

		// $start = new DateTime($fecha_desde.' '.$hora_desde.' '.date_default_timezone_get()); 
		// $end = new DateTime($fecha_hasta.' '.$hora_hasta.' '.date_default_timezone_get());
		// $dates = urlencode($start->format("Ymd\THis")) . "/" . urlencode($end->format("Ymd\THis"));

  //       $boton_google = "<a href='http://www.google.com/calendar/event?action=TEMPLATE&text=N Reserva: ".$id_reserva." - Patente: ".$patente_veh." - ".$marca_veh." - ".$modelo_veh.". Chofer: ".$reserva_chofer."&dates=".$dates."&details=".$codigo_uni."-".$codigo_pro."&location=".$this->rut_propietario_res."- Reservado por: ".$reserva_user."&trp=false&ctz=America/Santiago' target='_blank' rel='nofollow'><img src='https://i1.wp.com/www.google.com/calendar/images/ext/gc_button6_es.gif' alt='' border='0'></a>";

		// envio mail
		// $mensaje="
		// 	<table width='90%' border='0' style='margin:auto; font-family:Verdana, Geneva, sans-serif;'>
		// 	  <tr>
		// 	    <td align='left' colspan='2'><img src='assets/img/logo-grande.jpg' width='75'></td>
		// 	  </tr>
		// 	  <tr>
		// 	    <td colspan='2' style='padding:10px; line-height:20px; font-size:13px;'>
		// 		    <b>Reserva registrada en el Sistema de Vehículos</b><br />
		// 		    <hr>
		// 	    </td>
		// 	  </tr>
		// 	  <tr>
		// 	    <td style='padding:10px; line-height:20px; font-size:13px;' width='50%'>
		// 		    <b>Datos Reserva</b><br>
		// 		    <b>Nº:</b> ".$id."<br />  
		// 		    <b>Nombre:</b> ".$reserva_user."<br />  
		// 		    <b>Unidad / Proyecto:</b> ".$codigo_uni."-".$codigo_pro."<br />  
		// 		    <b>Vehículo:</b> ".$patente_veh." - ".$marca_veh." - ".$modelo_veh."<br />  
		// 		    <b>Fecha desde:</b> ".$fecha_desde_res." | <b>Hora desde:</b> ".$this->hora_desde_res." hrs.<br />
		// 		    <b>Fecha hasta:</b> ".$fecha_hasta_res." | <b>Hora hasta:</b> ".$this->hora_hasta_res." hrs.<br />
		// 		    <b>Chofer:</b> ".$reserva_chofer."<br>
		// 		    <b>Destino:</b> ".$this->rut_propietario_res."<br>
		// 	    </td>
		// 	    <td style='padding:10px; line-height:20px; font-size:13px;' valign='top'>
		// 		    <b>Traslada a:</b> ".$this->apellido_paterno_propietario_res."<br /><br />
		// 		    <b>".$boton_google."</b>
		// 	    </td>
		// 	  </tr>
		// 	  <tr height='28'>
		// 	    <td colspan='2' style='font-size:11px; background-color:#cc7f2a; color:#FFF; text-align:center;'>Sistema Vehículos | Universidad Católica del Norte</td>
		// 	  </tr>
		// 	</table>
		// 	";
		// $asunto = 'Registro de Reserva Vehículo - Edición';

		// $mail = new PHPMailer;
		// //Tell PHPMailer to use SMTP
		// $mail->isSMTP();
		// //Enable SMTP debugging
		// // 0 = off (for production use)
		// // 1 = client messages
		// // 2 = client and server messages
		// $mail->SMTPDebug = 0;
		// //Ask for HTML-friendly debug output
		// $mail->Debugoutput = 'html';
		// //Set the hostname of the mail server
		// $mail->Host = 'smtp.gmail.com';
		// $mail->Port = 587;
		// //Set the encryption system to use - ssl (deprecated) or tls
		// $mail->SMTPSecure = 'tls';
		// //Whether to use SMTP authentication
		// $mail->SMTPAuth = true;
		// //Username to use for SMTP authentication - use full email address for gmail
		// $mail->Username = "web@proyectarse.com";
		// //Password to use for SMTP authentication
		// $mail->Password = "pass2014";
		// //Set who the message is to be sent from
		// $mail->setFrom('web@proyectarse.com', 'Sistema de Vehículos FCM');
		// //Set an alternative reply-to address
		// $mail->addReplyTo('bruno@proyectarse.com', 'Sistema de Vehículos FCM');
		// //Set who the message is to be sent to
		// if ($correo_usu<>'') {
		// 	$mail->addAddress($correo_usu);
		// }

		// if ($correo_chofer<>'') {
		// 	$mail->addAddress($correo_chofer);
		// }

		// $correo_domingo = "bruno@proyectarse.com";
		// $mail->addAddress($correo_domingo);

		// $mail->Subject = $asunto;

		// $mail->Body = $mensaje;

		// $mail->send();

		// fin mail


		$consulta = "UPDATE reserva_reserva SET id_viv = ?, id_prog = ?, id_tip_res = ?, id_arr = ?, id_proc = ?, id_usu = ?, fecha_desde_res = ?, fecha_hasta_res = ?, rut_propietario_res = ?, nombre_propietario_res = ?, apellido_paterno_propietario_res = ?, apellido_materno_propietario_res = ?, monto_total_res = ?, monto_total_base_res = ?, monto_dia_res = ?, monto_dia_base_res = ?, monto_interno_res = ?, monto_adicional_res = ?, monto_comision_res = ?, monto_comision_base_res = ?, cantidad_pasajero_res = ?, cantidad_dia_res = ?, descripcion_res = ? WHERE id_res = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_viv,$this->id_prog,$this->id_tip_res,$this->id_arr,$this->id_proc,$this->id_usu,$this->fecha_desde_res,$this->fecha_hasta_res,$this->rut_propietario_res,$this->nombre_propietario_res,$this->apellido_paterno_propietario_res,$this->apellido_materno_propietario_res,$this->monto_total_res,$this->monto_total_base_res,$this->monto_dia_res,$this->monto_dia_base_res,$this->monto_interno_res,$this->monto_adicional_res,$this->monto_comision_res,$this->monto_comision_base_res,$this->cantidad_pasajero_res,$this->cantidad_dia_res,$this->descripcion_res,$id));

		$consulta = "DELETE FROM reserva_servicio_adicional_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function reserva_delete($id){
		$conexion = new conexion();
		// $consulta = "DELETE FROM arriendo_arriendo WHERE id_res = ?";
		// $conexion->consulta_form($consulta,array($id));
		
		$consulta="UPDATE reserva_reserva SET id_est_res = 3 WHERE id_res = ?";	
		$conexion->consulta_form($consulta,array($id));
		// $consulta = "DELETE FROM reserva_cobro_reserva WHERE id_res = ?";
		// $conexion->consulta_form($consulta,array($id));

		// $consulta = "DELETE FROM reserva_servicio_interno_reserva WHERE id_res = ?";
		// $conexion->consulta_form($consulta,array($id));

		// $consulta = "DELETE FROM reserva_servicio_adicional_reserva WHERE id_res = ?";
		// $conexion->consulta_form($consulta,array($id));

		// $consulta = "DELETE FROM reserva_reserva WHERE id_res = ?";
		// $conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
	public function reserva_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_res FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_reserva = $fila["id_est_res"];	

		if($estado_reserva == 1){
			$consulta="UPDATE reserva_reserva SET id_est_res = 2 WHERE id_res = ?";	
		}
		else{
			$consulta="UPDATE reserva_reserva SET id_est_res = 1 WHERE id_res = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_res FROM reserva_reserva ORDER BY id_res DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_res'];
		$conexion->cerrar();
		return $id;
	}
	public function reserva_insert_abono($abono,$fecha_ing,$id_for_pag,$valor_dolar_dia_res_ing,$id_res,$id_viv,$id_prog){
		$conexion = new conexion();
		
		$consulta = "INSERT INTO ingreso_ingreso VALUES(?,?,?,?,?,?)";//6
		$conexion->consulta_form($consulta,array(0,$id_for_pag,1,1,$fecha_ing,""));
		$id_ing = $conexion->ultimo_id();

		$consulta = "INSERT INTO ingreso_reserva_ingreso VALUES(?,?,?,?,?,?,?,?,?,?)";//10
		$conexion->consulta_form($consulta,array(0,$id_ing,$id_res,$id_viv,$id_prog,$id_for_pag,1,$abono,$cargo,$valor_dolar_dia_res_ing));
		
		$consulta = "SELECT * FROM reserva_reserva AS res WHERE id_res = ".$id_res."";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		if(is_array($fila)){
		
	    	$total_reserva = $fila["monto_total_res"];

			if($total_reserva == $abono){
				$consulta = "UPDATE reserva_reserva SET id_est_pag_res = 1 WHERE id_res = ".$id_res."";
				$conexion->consulta($consulta);
				
			}
		}
		$conexion->cerrar();
	}
	public function reserva_insert_servicio($id_reserva, $id_ser){
		$conexion = new conexion();
		
		$consulta = "SELECT id_tip_cob, valor_alto_adi_ser, valor_medio_adi_ser, valor_bajo_adi_ser, nombre_adi_ser FROM servicio_adicional_servicio WHERE id_adi_ser = ?";
		$conexion->consulta_form($consulta,array($id_ser));
		$fila = $conexion->extraer_registro_unico();
		$id_tip_cob = $fila['id_tip_cob'];
		$nombre_adi_ser = $fila['nombre_adi_ser'];
		$valor_alto_adi_ser = $fila['valor_alto_adi_ser'];
		$valor_medio_adi_ser = $fila['valor_medio_adi_ser'];
		$valor_bajo_adi_ser = $fila['valor_bajo_adi_ser'];

		$consulta = "SELECT id_tip_res, cantidad_dia_res, cantidad_pasajero_res FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_dia_res = $fila["cantidad_dia_res"];
		$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
		$id_tip_res = $fila["id_tip_res"];
		if($id_tip_res == 1){
			$valor_adi_ser = $valor_alto_adi_ser;
		}
		else if($id_tip_res == 2){
			$valor_adi_ser = $valor_medio_adi_ser;
		}
		else{
			$valor_adi_ser = $valor_bajo_adi_ser;
		}

		switch ($id_tip_cob) {
			case 1:
				// unico
				$valor_servicio = $valor_adi_ser;
				break;
			case 2:
				// dia 
				$valor_servicio = $valor_adi_ser * $cantidad_dia_res;
				break;
			case 3:
				// persona
				$valor_servicio = $valor_adi_ser * $cantidad_pasajero_res;
				break;
			case 4:
				// dia/persona
				$valor_cantidad_persona = $valor_adi_ser * $cantidad_pasajero_res;
				$valor_servicio = $valor_cantidad_persona * $cantidad_dia_res;
				break;
			
		}
		
		$valor_servicio = $valor_adi_ser;
		$consulta="INSERT INTO reserva_servicio_adicional_reserva VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_reserva,$id_ser,$id_tip_cob,$valor_servicio,0,$nombre_adi_ser));
		$conexion->cerrar();
	}
	public function reserva_update_monto($id_reserva){
		$conexion = new conexion();

		$valor_servicio_acumulado = 0;

		$consulta = "SELECT cantidad_dia_res, cantidad_pasajero_res, monto_total_res, monto_total_base_res FROM reserva_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_dia_res = $fila["cantidad_dia_res"];
		$cantidad_pasajero_res = $fila["cantidad_pasajero_res"];
		$monto_total_res = $fila["monto_total_res"];
		$monto_total_base_res = $fila["monto_total_base_res"];


		$consulta = "SELECT id_tip_cob, valor_ser_adi_res FROM reserva_servicio_adicional_reserva WHERE id_res = ?";
		$conexion->consulta_form($consulta,array($id_reserva));
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
            	$id_tip_cob = $fila['id_tip_cob'];
				$valor_ser_adi_res = $fila['valor_ser_adi_res'];

				switch ($fila['id_tip_cob']) {
					case 1:
						// unico
						$valor_servicio = $valor_ser_adi_res;
						break;
					case 2:
						// dia 
						$valor_servicio = $valor_ser_adi_res * $cantidad_dia_res;
						break;
					case 3:
						// persona
						$valor_servicio = $valor_ser_adi_res * $cantidad_pasajero_res;
						break;
					case 4:
						// dia/persona
						$valor_cantidad_persona = $valor_ser_adi_res * $cantidad_pasajero_res;
						$valor_servicio = $valor_cantidad_persona * $cantidad_dia_res;
						break;
					
				}
				$valor_servicio_acumulado = $valor_servicio_acumulado + $valor_servicio;
            }
        }
	
		$monto_total_res = $monto_total_res + $valor_servicio_acumulado;
		$monto_total_base_res = $monto_total_base_res + $valor_servicio_acumulado;

		$consulta = "UPDATE reserva_reserva SET monto_adicional_res = ?, monto_total_res = ?, monto_total_base_res = ? WHERE id_res = ?";	
		$conexion->consulta_form($consulta,array($valor_servicio_acumulado,$monto_total_res,$monto_total_base_res,$id_reserva));
		
		$conexion->cerrar();
	}

	public function reserva_insert_aseo($id_viv,$id_tip_ase,$id_usu,$id_est_ase,$fecha_ase){
		$conexion = new conexion();
		
		$consulta = "INSERT INTO aseo_aseo VALUES(?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$id_viv,$id_tip_ase,$id_usu,$id_est_ase,$fecha_ase));
		$conexion->cerrar();
	}

	public function reserva_update_reprogramar($origen,$id_reserva,$destino){
		$conexion = new conexion();
		$consulta = "UPDATE reserva_reserva SET id_viv = ? WHERE id_res = ?";	
		$conexion->consulta_form($consulta,array($destino,$id_reserva));
		
		$conexion->cerrar();
	}
}
?>
