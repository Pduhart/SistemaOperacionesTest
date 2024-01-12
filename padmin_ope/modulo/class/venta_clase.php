<?php
require "../../config.php";
include("../../class/conexion.php");
date_default_timezone_set('America/Santiago');
class venta
{
	private $id_viv;
	private $id_mod;
	private $id_vend;
	private $id_pro;
	private $id_can_cot;
	private $id_est_cot;
	private $fecha_cot;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function venta_crea($id_viv,$id_mod,$id_vend,$id_pro,$id_can_cot,$id_est_cot,$fecha_cot){
		$this->id_viv = $id_viv;
		$this->id_mod = $id_mod;
		$this->id_vend = $id_vend;
		$this->id_pro = $id_pro;
		$this->id_can_cot = $id_can_cot;
		$this->id_est_cot = $id_est_cot;
		$this->fecha_cot = $fecha_cot;
	}
	//funcion de insercion
	public function venta_insert(){
		$conexion = new conexion();
		/*$consulta = "SELECT id_pro FROM venta_venta WHERE id_pro = ? ";
		$conexion->consulta_form($consulta,array($this->id_pro));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}*/
		//Vendedor
		$consulta="SELECT id_vend FROM vendedor_vendedor WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($this->id_vend));
		$total_consulta = $conexion->total();
		if ($total_consulta > 0) {
			$fila = $conexion->extraer_registro_unico();
			$id_vendedor = $fila['id_vend'];
		}
		else{
			$id_vendedor = 0;
		}
		$fecha_promesa = "0000-00-00 00:00:00";
		$consulta = "INSERT INTO venta_venta VALUES(?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_viv,$this->id_mod,$id_vendedor,$this->id_pro,$this->id_can_cot,$this->id_est_cot,$this->fecha_cot,$fecha_promesa));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function venta_update($id){
		$conexion = new conexion();

		$consulta = "UPDATE venta_venta SET id_viv = ?, id_mod = ?, id_pro = ?, id_can_cot = ? WHERE id_cot = ?";	
		$conexion->consulta_form($consulta,array($this->id_viv,$this->id_mod,$this->id_pro,$this->id_can_cot,$id));


		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function venta_update_sin_clase($id,$id_pre,$id_for_pag,$numero_compra_ven,$id_ban,$id_tip_pag,$monto_credito_real_ven,$id_pie_abo_ven,$propietario,$fecha_ven){
		$conexion = new conexion();

		$consulta = "SELECT pie_cancelado_ven,monto_reserva_ven,monto_credito_ven,monto_ven,id_pie_abo_ven,descuento_ven,id_pro,id_viv FROM venta_venta WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$pie_cancelado_ven = $fila['pie_cancelado_ven'];
		$monto_reserva_ven = $fila['monto_reserva_ven'];
		$monto_credito_ven = $fila['monto_credito_ven'];
		$monto_ven = $fila['monto_ven'];
		$id_pro = $fila['id_pro'];
		$id_viv = $fila['id_viv'];
		// $id_pie_abo_ven = $fila['id_pie_abo_ven'];
		$descuento_ven = $fila['descuento_ven'];

		if ($id_pie_abo_ven==1) {
			$pie_original = $pie_cancelado_ven + $monto_reserva_ven + $descuento_ven;
		} else {
			$pie_original = $pie_cancelado_ven + $monto_reserva_ven;
		}
		
		$pie_real = $monto_ven - $monto_credito_real_ven;
		$pie_por_cobrar = $pie_real - $pie_original;

		// $pie_original = $pie_cancelado_ven + $monto_reserva_ven;
		// $pie_real = $monto_ven - $monto_credito_real_ven;
		// $pie_por_cobrar = $pie_real - $pie_original;
		$pie_por_cobrar = number_format($pie_por_cobrar, 2, '.', '');
		$pie_total = $pie_original + $pie_por_cobrar;
		$porcentaje_pie_real = ($pie_total * 100) / $monto_ven;
		$porcentaje_pie_real = number_format($porcentaje_pie_real, 2, '.', '');

		if ($id_pre=='') {
			$id_pre = 0;
		}

		if ($id_pro<>$propietario) {
			$consulta = "UPDATE propietario_vivienda_propietario SET id_pro = ? WHERE id_viv = ?";	
			$conexion->consulta_form($consulta,array($propietario,$id_viv));
		}

		// $consulta = "UPDATE venta_venta SET id_pre = ?, id_for_pag = ?, numero_compra_ven = ?, id_ban = ?, id_tip_pag = ?, monto_credito_real_ven = ?, pie_real_ven = ?, pie_cobrar_ven = ?, id_pie_abo_ven = ?, id_pro = ?, fecha_ven = ?, fecha_promesa_ven = ? WHERE id_ven = ?";	
		$consulta = "UPDATE venta_venta SET id_pre = ?, id_for_pag = ?, numero_compra_ven = ?, id_ban = ?, id_tip_pag = ?, monto_credito_real_ven = ?, pie_real_ven = ?, pie_cobrar_ven = ?, id_pie_abo_ven = ?, id_pro = ? WHERE id_ven = ?";	
		$conexion->consulta_form($consulta,array($id_pre,$id_for_pag,$numero_compra_ven,$id_ban,$id_tip_pag,$monto_credito_real_ven,$porcentaje_pie_real,$pie_por_cobrar,$id_pie_abo_ven,$propietario,$id));

		if ($id_for_pag==2) {
			$consulta = "UPDATE venta_venta SET monto_credito_real_ven = ? WHERE id_ven = ?";	
			$conexion->consulta_form($consulta,array(0,$id));
		}

		// validar si ya tiene etapa y si es de la misma forma de pago
		if ($id_for_pag==2) {
			$consulta = "SELECT id_eta_ven FROM venta_etapa_venta WHERE id_ven = ? AND id_eta = 51 AND id_est_eta_ven = 3";
	        $conexion->consulta_form($consulta,array($id_ven));
	        $total_eta1_cre = $conexion->total();
	        if($total_eta1_cre>0) {
	        	$filaeta = $conexion->extraer_registro();
	        	$id_eta_ven = $filaeta['id_eta_ven'];
	        	$consulta = "UPDATE venta_etapa_venta SET id_eta = ? WHERE id_ven = ? AND id_eta_ven = ?";	
				$conexion->consulta_form($consulta,array(1,$id_ven,$id_eta_ven));
	        }
	        
	    } else {
	    	$consulta = "SELECT id_eta_ven FROM venta_etapa_venta WHERE id_ven = ? AND id_eta = 1 AND id_est_eta_ven = 3";
	        $conexion->consulta_form($consulta,array($id_ven));
	        $total_eta1_con = $conexion->total();
	        if($total_eta1_con>0) {
	        	$filaeta = $conexion->extraer_registro();
	        	$id_eta_ven = $filaeta['id_eta_ven'];
	        	$consulta = "UPDATE venta_etapa_venta SET id_eta = ? WHERE id_ven = ? AND id_eta_ven = ?";	
				$conexion->consulta_form($consulta,array(51,$id_ven,$id_eta_ven));
	        }
	    }


		$conexion->cerrar();
	}

	public function venta_insert_desistimiento($id,$fecha,$descripcion,$motivo){
		$conexion = new conexion();

		$consulta="SELECT cotizacion_ven FROM venta_venta WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$total_consulta = $conexion->total();
		if ($total_consulta > 0) {
			$fila = $conexion->extraer_registro_unico();
			$cotizacion_ven = $fila['cotizacion_ven'];

			$consulta_update = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";
			$conexion->consulta_form($consulta_update,array(3,$cotizacion_ven));
		}

		$consulta="SELECT id_viv FROM venta_venta WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$id_viv = $fila['id_viv'];

		$consulta = "INSERT INTO venta_desestimiento_venta VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$fecha,$descripcion,$motivo));

		$consulta = "UPDATE venta_venta SET id_est_ven = ? WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array(3,$id));

		$consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
       	$conexion->consulta_form($consulta,array(0,$id,3));

       	// aca va a pasar la vivienda a NO vendida
		$id_ven = $id;
		$consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(1,$id_viv));

        $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(1,$id_viv));

        $consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(1,$id_viv));
        // adicionales eliminados
        $consulta = "SELECT id_esta FROM venta_estacionamiento_venta WHERE id_ven = ?";
        $conexion->consulta_form($consulta,array($id_ven));
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                // los extra sin vivienda                
                $consulta = "UPDATE estacionamiento_estacionamiento SET id_viv = ? WHERE id_esta = ? AND valor_esta <> 0";
                $conexion->consulta_form($consulta,array(0,$fila["id_esta"]));
            }
        }

        $consulta = "SELECT id_bod FROM venta_bodega_venta WHERE id_ven = ?";
        $conexion->consulta_form($consulta,array($id_ven));
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                 // los extra sin vivienda           
                $consulta = "UPDATE bodega_bodega SET id_viv = ? WHERE id_bod = ? AND valor_bod <> 0";    
                $conexion->consulta_form($consulta,array(0,$fila["id_bod"]));
            }
        }

        // borra
        $consulta="DELETE FROM propietario_vivienda_propietario WHERE id_viv = ?";
		$conexion->consulta_form($consulta,array($id_viv));

		$conexion->cerrar();

	}

	//funcion de propietario
	public function venta_update_propietario($propietario,$nombre_pro,$apellido_paterno_pro, $apellido_materno_pro,$correo_pro,$fono_pro){
		$conexion = new conexion();
		
		$consulta = "UPDATE propietario_propietario SET nombre_pro = ?,apellido_paterno_pro = ?,apellido_materno_pro = ?,correo_pro = ?,fono_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($nombre_pro,$apellido_paterno_pro,$apellido_materno_pro,$correo_pro,$fono_pro,$propietario));

		$conexion->cerrar();
	}

	public function venta_insert_operacion($id){
		$conexion = new conexion();
		
		$consulta = "SELECT id_for_pag, id_viv, id_pro FROM venta_venta WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$id_for_pag = $fila['id_for_pag'];
		$id_viv = $fila['id_viv'];
		$id_pro = $fila['id_pro'];

		$consulta = "SELECT id_eta FROM etapa_etapa WHERE id_for_pag = ? AND id_est_eta = 1 ORDER BY numero_real_eta ASC LIMIT 0,1";
		$conexion->consulta_form($consulta,array($id_for_pag));
		$fila = $conexion->extraer_registro_unico();
		$id_eta = $fila['id_eta'];
		$fecha = null;
		$descripcion = "";
		$consulta = "INSERT INTO venta_etapa_venta VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$id_eta,3,$fecha,$fecha,$descripcion));

		// aca va a pasar la vivienda a vendida
		$id_ven = $id;
		$consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));

        $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));

        $consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));
        // adicionales
        $consulta = "SELECT id_esta FROM venta_estacionamiento_venta WHERE id_ven = ?";
        $conexion->consulta_form($consulta,array($id_ven));
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                
                $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ?, id_viv = ? WHERE id_esta = ?";    
                $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_esta"]));
            }
        }

        $consulta = "SELECT id_bod FROM venta_bodega_venta WHERE id_ven = ?";
        $conexion->consulta_form($consulta,array($id_ven));
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                
                $consulta = "UPDATE bodega_bodega SET id_est_bod = ?, id_viv = ? WHERE id_bod = ?";    
                $conexion->consulta_form($consulta,array(2,$id_viv,$fila["id_bod"]));
            }
        }

		$consulta_existe_pro = "SELECT id_pro FROM propietario_vivienda_propietario WHERE id_viv = ?";
        $conexion->consulta_form($consulta_existe_pro,array($id_viv));
        $existe = $conexion->total();
        if ($existe==0) {
        	$consulta = "INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
			$conexion->consulta_form($consulta,array(0,$id_pro,$id_viv));
        }

        

        $consulta_existe_cam_ven = "SELECT id_cam_ven FROM venta_campo_venta WHERE id_ven = ?";
        $conexion->consulta_form($consulta_existe_cam_ven,array($id_ven));
        $existe_cam = $conexion->total();
        if ($existe_cam==0) {
        	// campos extras fuera de etapa
        	$consulta = "INSERT INTO venta_campo_venta (id_ven) VALUES (?)";
        	$conexion->consulta_form($consulta,array($id_ven));
        }

		$conexion->cerrar();
	}

	public function venta_insert_pago($id,$id_for_pag,$id_ban,$id_cat_pag,$id_est_pag,$numero_documento_pag,$numero_serie_pag,$fecha_pag,$monto_pag,$descripcion_protesto_pag){
		$conexion = new conexion();

		if ($id_ban=='') {
			$id_ban = 0;
		}

		$consulta = "INSERT INTO pago_pago VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_for_pag,$id_ban,$id,$id_cat_pag,$id_est_pag,$numero_documento_pag,$numero_serie_pag,$fecha_pag,null,$monto_pag,$descripcion_protesto_pag));

		$conexion->cerrar();

	}

	public function venta_update_pago($id,$id_for_pag,$id_ban,$id_cat_pag,$numero_documento_pag,$numero_serie_pag,$fecha_pag,$fecha_real_pag,$monto_pag){
		$conexion = new conexion();

		$consulta = "UPDATE pago_pago SET id_for_pag = ?,id_ban = ?,id_cat_pag = ?,numero_documento_pag = ?,numero_serie_pag = ?,fecha_pag = ?,fecha_real_pag = ?,monto_pag = ? WHERE id_pag = ?";
		$conexion->consulta_form($consulta,array($id_for_pag,$id_ban,$id_cat_pag,$numero_documento_pag,$numero_serie_pag,$fecha_pag,$fecha_real_pag,$monto_pag,$id));

		$conexion->cerrar();

	}

	public function venta_insert_protestar($id,$descripcion_protesto_pag){
		$conexion = new conexion();

		$consulta = "UPDATE pago_pago SET descripcion_protesto_pag = ?, id_est_pag = ? WHERE id_pag = ?";
		$conexion->consulta_form($consulta,array($descripcion_protesto_pag,3,$id));

		$conexion->cerrar();

	}

	public function cotizacion_insert_venta($id_viv,$id_pro,$id_ban,$id_pie_ven,$id_for_pag,$id_des,$id_pre,$id_pie_abo_ven,$id_tip_pag,$id_est_ven,$fecha_ven,$fecha_promesa_ven,$monto_reserva_ven,$descuento_manual_ven,$descuento_precio_ven,$descuento_adicional_ven,$descuento_ven,$pie_cancelado_ven,$pie_cobrar_ven,$monto_estacionamiento_ven,$monto_bodega_ven,$monto_vivienda_ven,$monto_vivienda_ingreso_ven,$monto_ven,$numero_compra_ven,$cotizacion_ven,$precio_descuento){
		$conexion = new conexion();

		//Vendedor
		$consulta = 
			"
			SELECT 
				tor.id_con,
				viv.bono_viv
			FROM
				vivienda_vivienda AS viv 
				INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			WHERE 
				viv.id_viv = ?
			";
		$conexion->consulta_form($consulta,array($id_viv));
		$total_consulta = $conexion->total();
		if ($total_consulta > 0) {
			$fila = $conexion->extraer_registro_unico();
			$id_con = $fila['id_con'];
			$bono_viv = $fila['bono_viv'];
		}
		else{
			$id_con = 0;
			$bono_viv = 0;
		}

		//Vendedor
		$consulta = 
			"
			SELECT 
				vend.id_cat_vend,
				vend.id_vend
			FROM
				cotizacion_cotizacion AS cot 
				INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
			WHERE 
				cot.id_cot = ?
			";
		$conexion->consulta_form($consulta,array($cotizacion_ven));
		$total_consulta = $conexion->total();
		if ($total_consulta > 0) {
			$fila = $conexion->extraer_registro_unico();
			$id_vend = $fila['id_vend'];
			$id_cat_vend = $fila['id_cat_vend'];
		}
		else{
			$id_vend = 0;
		}

		$consulta = "SELECT valor2_par, valor_par FROM parametro_parametro WHERE valor2_par >= 1 AND valor2_par <= 10 AND id_con = ".$id_con." ";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
				if($fila['valor2_par'] == 1){
					$factor_junior = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 2){
					$factor_advance = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 3){
					$factor_senior = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 4){
					$porcentaje_descuento = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 5){
					$bono_precio = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 6){
					$porcentaje_promesa = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 7){
					$porcentaje_escritura = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 8){
					$porcentaje_comision_vendedor = $fila['valor_par']; 
				}
            }
        }	
		if($id_cat_vend == 1){
            $factor = $factor_junior;	
        }
        else if($id_cat_vend == 1){
        	$factor = $factor_advance;
        }
        else{
        	$factor = $factor_senior;
        }
		
		$monto_comision = $monto_ven * ($porcentaje_comision_vendedor / 100); 
		$monto_promesa_comision = $monto_comision * ($porcentaje_promesa / 100); 
		$monto_escritura_comision = $monto_comision * ($porcentaje_escritura / 100); 

		$total_comision = $monto_comision * $factor;

		if($monto_vivienda_ingreso_ven == $monto_vivienda_ven && $id_des == 0 && $precio_descuento == 2){
			$total_descuento = ($monto_vivienda_ven * $porcentaje_descuento) / 100;
			$total_bono_precio = $total_descuento * ($bono_precio / 100);

			$promesa_bono_precio_ven = $total_bono_precio * ($porcentaje_promesa / 100);
			$escritura_bono_precio_ven = $total_bono_precio * ($porcentaje_escritura / 100);
		}
		else{
			$total_bono_precio = 0;
			$bono_precio = 0;
			$promesa_bono_precio_ven = 0;
			$escritura_bono_precio_ven = 0;
		}

		$consulta = "INSERT INTO venta_venta VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//40
		$conexion->consulta_form($consulta,array(0,$id_viv,$id_vend,$id_pro,$id_ban,$id_pie_ven,$id_for_pag,$id_des,$id_pre,$id_pie_abo_ven,$id_tip_pag,$id_est_ven,$fecha_ven,$fecha_promesa_ven,$monto_reserva_ven,$descuento_manual_ven,$descuento_precio_ven,$descuento_adicional_ven,$descuento_ven,$pie_cancelado_ven,$pie_cobrar_ven,$monto_estacionamiento_ven,$monto_bodega_ven,$monto_vivienda_ven,$monto_vivienda_ingreso_ven,$monto_ven,$factor,$porcentaje_comision_vendedor,$porcentaje_promesa,$monto_promesa_comision,$porcentaje_escritura,$monto_escritura_comision,$total_comision,$bono_viv,$bono_precio,$promesa_bono_precio_ven,$escritura_bono_precio_ven,$total_bono_precio,$numero_compra_ven,$cotizacion_ven));

		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array(5,$cotizacion_ven));

		$conexion->cerrar();

	}
	
	//funcion de eliminacion
	public function venta_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM venta_venta WHERE id_ven = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	//funcion de eliminacion
	public function venta_delete_detalle($id){
		$conexion = new conexion();
		$consulta="DELETE FROM pago_pago WHERE id_pag = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_cot FROM cotizacion_cotizacion ORDER BY id_cot DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_cot'];
		$conexion->cerrar();
		return $id;
	}

	public function recupera_venta_id(){
		$conexion = new conexion();
		$consulta="SELECT id_ven FROM venta_venta ORDER BY id_ven DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_ven'];
		$conexion->cerrar();
		return $id;
	}

	public function cotizacion_descuento_adicional($id){
		$conexion = new conexion();
		$consulta="SELECT monto_des FROM descuento_descuento WHERE id_des = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$monto_des = $fila['monto_des'];
		$conexion->cerrar();
		return $monto_des;
	}	
	public function cotizacion_pie($id){
		$conexion = new conexion();
		$consulta="SELECT valor_pie_ven FROM venta_pie_venta WHERE id_pie_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$valor_pie_ven = $fila['valor_pie_ven'];
		$conexion->cerrar();
		return $valor_pie_ven;
	}	
	public function cotizacion_estacionamiento($id){
		$conexion = new conexion();
		$consulta = 
	        "
	        SELECT 
	            IFNULL(SUM(valor_esta),0) AS suma
	        FROM 
	            estacionamiento_estacionamiento
	        WHERE 
	            id_esta IN (".implode(',',$id).") 
	        ";
	    $conexion->consulta($consulta);
	    $fila = $conexion->extraer_registro_unico();
	    $monto_estacionamiento = $fila["suma"];
		$conexion->cerrar();
		return $monto_estacionamiento;
	}
	public function cotizacion_bodega($id){
		$conexion = new conexion();
		$consulta = 
	        "
	        SELECT 
	            IFNULL(SUM(valor_bod),0) AS suma
	        FROM 
	            bodega_bodega
	        WHERE 
	            id_bod IN (".implode(',',$id).") 
	        ";
	    $conexion->consulta($consulta);
	    $fila = $conexion->extraer_registro_unico();
	    $monto_bodega = $fila["suma"];
		$conexion->cerrar();
		return $monto_bodega;
	}
	public function cotizacion_insert_estacionamiento($id_vivienda,$id_venta,$id_estacionamiento){
		$conexion = new conexion();
		$consulta = 
	        "
	        SELECT 
	            id_esta
	        FROM 
	            estacionamiento_estacionamiento
	        WHERE 
	            id_esta IN (".implode(',',$id_estacionamiento).") 
	        ";
	    $conexion->consulta($consulta);
	    $fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                $consulta = "INSERT INTO venta_estacionamiento_venta VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$id_venta,$fila["id_esta"]));

				$consulta = "UPDATE estacionamiento_estacionamiento SET id_viv = ? WHERE id_esta = ?";
				$conexion->consulta_form($consulta,array($id_vivienda,$fila["id_esta"]));
            }
        }
		$conexion->cerrar();
	}
	public function cotizacion_insert_bodega($id_vivienda,$id_venta,$id_bodega){
		$conexion = new conexion();
		$consulta = 
	        "
	        SELECT 
	            id_bod
	        FROM 
	            bodega_bodega
	        WHERE 
	            id_bod IN (".implode(',',$id_bodega).") 
	        ";
	    $conexion->consulta($consulta);
	    $fila_consulta = $conexion->extraer_registro();
	    if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                $consulta = "INSERT INTO venta_bodega_venta VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$id_venta,$fila["id_bod"]));

				$consulta = "UPDATE bodega_bodega SET id_viv = ? WHERE id_bod = ?";
				$conexion->consulta_form($consulta,array($id_vivienda,$fila["id_bod"]));
            }
        }
		$conexion->cerrar();
	}
	public function cotizacion_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_cot FROM cotizacion_cotizacion WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_cotizacion = $fila["id_est_cot"];	

		if($estado_cotizacion == 1){
			$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = 2 WHERE id_cot = ?";	
		}
		else{
			$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = 1 WHERE id_cot = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function venta_update_estado_detalle($id){
		$conexion = new conexion();
		$consulta = "
			SELECT 
				id_est_pag, 
				id_ven, 
				id_cat_pag, 
				fecha_real_pag, 
				monto_pag, 
				id_for_pag
			FROM 
				pago_pago 
			WHERE 
				id_pag = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_pago = $fila["id_est_pag"];	
		$id_ven = $fila["id_ven"];	
		$id_cat_pag = $fila["id_cat_pag"];
		$fecha_real_pag = $fila["fecha_real_pag"];
		$monto_pag = $fila["monto_pag"];
		$id_for_pag_pago = $fila["id_for_pag"];


		// SI EL PAGO ES CONTADO Y TIPO 3, ACTUALIZA UF LIQUIDADAS EN VENTA LIQUIDADO
		// NO SE USA POR AHORA, SE DEBE CARGAR LOS UF LIQUIDADOS CUANDO ESTÉ RECIBIDO EL ÚLTIMO PAGO SALDO CONTADO
		// if($id_cat_pag == 3) {
		// 	$consulta_venta = "SELECT id_for_pag FROM venta_venta WHERE id_ven = ?";
		// 	$conexion->consulta_form($consulta_venta,array($id_ven));
		// 	$fila_for = $conexion->extraer_registro_unico();
		// 	$id_for_pag_venta = $fila_for["id_for_pag"];	
		// 	if($id_for_pag_venta == 2) { //SI ES CONTADO
		// 		$consulta = 
		// 		"
		// 		    SELECT
		// 		        valor_uf
		// 		    FROM
		// 		        uf_uf
		// 		    WHERE
		// 		        fecha_uf = ?
		// 		    ";
		// 		$conexion->consulta_form($consulta,array($fecha_real_pag));
		// 		$cantidad_uf = $conexion->total();
		// 		if($cantidad_uf > 0){ // si hay valor UF
		// 			$filauf = $conexion->extraer_registro_unico();
		// 			$valor_uf_efectivo = $filauf['valor_uf'];
		// 			if ($id_for_pag_pago==6) { // si es pago contra escritura UF
		// 				$abono_uf = $monto_pag;
		// 			} else {
		// 				$abono_uf = $monto_pag / $valor_uf_efectivo;
		// 			}

		// 			$consulta_liq = "SELECT id_liq_ven, monto_liq_uf_ven FROM venta_liquidado_venta WHERE id_ven = ?";
		// 			$conexion->consulta_form($consulta_liq,array($id_ven));
		// 			$cantidad_reg = $conexion->total();
		// 			if($cantidad_reg > 0){
		// 				$filaliq = $conexion->extraer_registro_unico();
		// 				$monto_liq_uf_ven = $filaliq['monto_liq_uf_ven'];

		// 				if($estado_pago == 1){ // ya está pagado y pasa a no pagado
		// 					$nuevo_monto_liquidado_uf_ven = $monto_liq_uf_ven - $abono_uf;

		// 				} else { // no estaba pagado y ahora si
		// 					$nuevo_monto_liquidado_uf_ven = $monto_liq_uf_ven + $abono_uf;
		// 				}

		// 				$consulta_actualiza_liq = "UPDATE venta_liquidado_venta SET monto_liq_uf_ven = ? WHERE id_ven = ?";
		// 				$conexion->consulta_form($consulta_actualiza_liq,array($nuevo_monto_liquidado_uf_ven,$id_ven));

		// 			} else { // si aun no tiene registro de liquidación

		// 				if($estado_pago == 1){ // ya está pagado y pasa a no pagado

		// 				} else { // no estaba pagado y ahora si
		// 					$nuevo_monto_liquidado_uf_ven = $abono_uf;

		// 					$consulta_inserta_liq = "INSERT INTO venta_liquidado_venta VALUES(?,?,?,?,?)";
	 //            			$conexion->consulta_form($consulta_inserta_liq,array(0,$id_ven,null,$nuevo_monto_liquidado_uf_ven,0));
		// 				}

		// 			}
		// 		}
		// 	}
		// }


		if($estado_pago == 1){
			$consulta = "UPDATE pago_pago SET id_est_pag = 2 WHERE id_pag = ?";	
		}
		else{
			$consulta = "UPDATE pago_pago SET id_est_pag = 1 WHERE id_pag = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	// RECALCULA VENTA
	public function venta_recalcula_venta($id){
		$conexion = new conexion();

		$consulta_ventas = "
			SELECT 
				ven.id_viv,
				ven.id_ven,
				tor.id_con,
				viv.bono_viv,
				ven.id_vend,
				vend.id_cat_vend,
				ven.id_pie_abo_ven,
				ven.monto_ven,
				ven.descuento_ven,
				ven.monto_vivienda_ingreso_ven,
				ven.monto_vivienda_ven,
				ven.id_des
			FROM
				venta_venta AS ven
				INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
				INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
				INNER JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
			WHERE 
				ven.id_ven = ".$id."
				";
		$conexion->consulta($consulta_ventas);
		$cantidad_venta = $conexion->total();
		$fila = $conexion->extraer_registro_unico();

    	$id_viv = $fila["id_viv"];
    	$id_ven = $fila["id_ven"];
    	$id_con = $fila["id_con"];
    	$bono_viv = $fila["bono_viv"];
    	$id_vend = $fila["id_vend"];
    	$id_cat_vend = $fila["id_cat_vend"];
    	$id_pie_abo_ven = $fila["id_pie_abo_ven"];
    	$monto_ven = $fila["monto_ven"];
    	$descuento_ven = $fila["descuento_ven"];
    	$monto_vivienda_ingreso_ven = $fila["monto_vivienda_ingreso_ven"];
    	$monto_vivienda_ven = $fila["monto_vivienda_ven"];
    	$id_des = $fila["id_des"];


    	$consulta = "SELECT id_usu FROM vendedor_supervisor_vendedor WHERE id_vend = ".$id_vend." ";
		$conexion->consulta($consulta);
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$fila = $conexion->extraer_registro_unico();
			$id_supervisor = $fila["id_usu"];
			$consulta = "SELECT id_cat_vend FROM usuario_usuario WHERE id_usu = ".$id_supervisor." ";
			$conexion->consulta($consulta);
			$fila = $conexion->extraer_registro_unico();
			$id_cat_vend_supervisor = $fila["id_cat_vend"];
		}


    	$consulta = "SELECT id_usu FROM vendedor_jefe_vendedor WHERE id_vend = ".$id_vend." ";
		$conexion->consulta($consulta);
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$fila = $conexion->extraer_registro_unico();
			$id_jefe = $fila["id_usu"];
			$consulta = "SELECT id_cat_vend FROM usuario_usuario WHERE id_usu = ".$id_jefe." ";
			$conexion->consulta($consulta);
			$fila = $conexion->extraer_registro_unico();
			$id_cat_vend_jefe = $fila["id_cat_vend"];
		}

		$consulta = "SELECT valor2_par, valor_par FROM parametro_parametro WHERE valor2_par >= 1 AND valor2_par <= 25 AND id_con = ".$id_con." ";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
				if($fila['valor2_par'] == 1){
					$factor_junior = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 2){
					$factor_advance = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 3){
					$factor_senior = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 4){
					$porcentaje_descuento = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 5){
					$bono_precio = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 6){
					$porcentaje_promesa = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 7){
					$porcentaje_escritura = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 8){
					$porcentaje_comision_vendedor = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 9){
					$porcentaje_comision_supervisor = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 10){
					$porcentaje_comision_jefe = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 11){
					$monto_escritura_operacion = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 13){
					$bono_precio_jefe = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 15){
					$bono_precio_supervisor = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 20){
					$factor_junior_jefe = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 21){
					$factor_advance_jefe = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 22){
					$factor_senior_jefe = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 23){
					$factor_junior_supervisor = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 24){
					$factor_advance_supervisor = $fila['valor_par']; 
				}
				if($fila['valor2_par'] == 25){
					$factor_senior_supervisor = $fila['valor_par']; 
				}

            }
        }	
		if($id_cat_vend == 1){
            $factor = $factor_junior;	
        }
        else if($id_cat_vend == 2){
        	$factor = $factor_advance;
        }
        else{
        	$factor = $factor_senior;
        }

		// revisar valor de la venta
        if ($id_pie_abo_ven==1) { //si aplica desceunto a pie
        	$monto_ven_comision = $monto_ven - $descuento_ven;
        } else {
        	$monto_ven_comision = $monto_ven;
        }

        // -------- VENDEDOR ---------

		$monto_comision = $monto_ven_comision * ($porcentaje_comision_vendedor / 100); 
		$total_comision = $monto_comision * $factor;
		$monto_comision = $monto_comision * $factor;

		$monto_promesa_comision = $monto_comision * ($porcentaje_promesa / 100); 
		$monto_escritura_comision = $monto_comision * ($porcentaje_escritura / 100); 

		// -------- SUPERVISOR ---------
		if($id_cat_vend_supervisor == 1){
            $factor_supervisor = $factor_junior_supervisor;	
        }
        else if($id_cat_vend_supervisor == 2){
        	$factor_supervisor = $factor_advance_supervisor;
        }
        else if($id_cat_vend_supervisor == 3){
        	$factor_supervisor = $factor_senior_supervisor;
        }


        $monto_comision_supervisor = $monto_ven_comision * ($porcentaje_comision_supervisor / 100); 
		$total_comision_supervisor = $monto_comision_supervisor * $factor_supervisor;
		$monto_comision_supervisor = $monto_comision_supervisor * $factor_supervisor;
		

		$monto_promesa_comision_supervisor = $monto_comision_supervisor * ($porcentaje_promesa / 100); 
		$monto_escritura_comision_supervisor = $monto_comision_supervisor * ($porcentaje_escritura / 100); 

		// -------- JEFE DE VENTAS ---------
		if($id_cat_vend_jefe == 1){
            $factor_jefe = $factor_junior_jefe;	
        }
        else if($id_cat_vend_jefe == 2){
        	$factor_jefe = $factor_advance_jefe;
        }
        else if($id_cat_vend_jefe == 3){
        	$factor_jefe = $factor_senior_jefe;
        }

        // echo "---------------->".$monto_ven_comision."-----".$porcentaje_comision_jefe."<br>";

		$monto_comision_jefe = $monto_ven_comision * ($porcentaje_comision_jefe / 100); 
		$total_comision_jefe = $monto_comision_jefe * $factor_jefe;
		$monto_comision_jefe = $monto_comision_jefe * $factor_jefe;

		// echo $factor_jefe."----------".$total_comision_jefe."<br>";

		$monto_promesa_comision_jefe = $monto_comision_jefe * ($porcentaje_promesa / 100); 
		$monto_escritura_comision_jefe = $monto_comision_jefe * ($porcentaje_escritura / 100); 

		// ----------------------------$precio_descuento

		if($monto_vivienda_ingreso_ven == $monto_vivienda_ven && $id_des == 0){
			$total_descuento = ($monto_vivienda_ven * $porcentaje_descuento) / 100;

			// $total_descuento_uf = round($total_descuento);

			// $total_bono_precio = $total_descuento_uf * ($bono_precio / 100);
			$total_bono_precio = $total_descuento * ($bono_precio / 100);

			// echo "---->".$monto_vivienda_ven." ".$porcentaje_descuento." ".$bono_precio;
			// echo "<br>valor viv con desc".$total_descuento_uf."<br>";
			// echo "<br>Total bono precio".$total_bono_precio."<br>";


			$promesa_bono_precio_ven = $total_bono_precio * ($porcentaje_promesa / 100);
			$escritura_bono_precio_ven = $total_bono_precio * ($porcentaje_escritura / 100);

			// echo "prom: ".$promesa_bono_precio_ven." ecr:".$escritura_bono_precio_ven;

			// $promesa_bono_precio_ven = floor($promesa_bono_precio_ven * 1000) / 1000;
			// $promesa_bono_precio_ven = round($promesa_bono_precio_ven, 6);

			// $escritura_bono_precio_ven = floor($escritura_bono_precio_ven * 1000) / 1000;
			// $escritura_bono_precio_ven = round($escritura_bono_precio_ven, 6);

			// -------- SUPERVISOR ---------
			$total_bono_precio_supervisor = $total_bono_precio * ($bono_precio_supervisor / 100);
			$promesa_bono_precio_supervisor = $total_bono_precio_supervisor * ($porcentaje_promesa / 100);
			$escritura_bono_precio_supervisor = $total_bono_precio_supervisor * ($porcentaje_escritura / 100);

			// -------- JEFE DE VENTAS ---------
			$total_bono_precio_jefe = $total_bono_precio * ($bono_precio_jefe / 100);
			$promesa_bono_precio_jefe = $total_bono_precio_jefe * ($porcentaje_promesa / 100);
			$escritura_bono_precio_jefe = $total_bono_precio_jefe * ($porcentaje_escritura / 100);
		}
		else{
			$total_bono_precio = 0;
			$bono_precio = 0;
			$promesa_bono_precio_ven = 0;
			$escritura_bono_precio_ven = 0;

			$total_bono_precio_supervisor = 0;
			$promesa_bono_precio_supervisor = 0;
			$escritura_bono_precio_supervisor = 0;

			$total_bono_precio_jefe = 0;
			$promesa_bono_precio_jefe = 0;
			$escritura_bono_precio_jefe = 0;
		}

		// si se le hizo descuento al pie, pie cancelado no es el total del pie, hay que sumarle el descuento
		if ($id_pie_abo_ven==1) {
			$monto_credito_ven = $monto_ven - ($pie_cancelado_ven + $descuento_ven + $monto_reserva_ven);
		} else {
			$monto_credito_ven = $monto_ven - ($pie_cancelado_ven + $monto_reserva_ven);
		}

		// echo $id_ven."<--------".$id_cat_vend."---".
		// 	$porcentaje_comision_vendedor."---".
		// 	$porcentaje_promesa."---".
		// 	$monto_promesa_comision."---".
		// 	$monto_promesa_comision_supervisor."---".
		// 	$monto_promesa_comision_jefe."---".
		// 	$porcentaje_escritura."---".
		// 	$monto_escritura_comision."---".
		// 	$monto_escritura_comision_supervisor."---".
		// 	$monto_escritura_comision_jefe."---".
		// 	$total_comision."---".
		// 	$total_comision_supervisor."---".
		// 	$total_comision_jefe."---".
		// 	$bono_viv."---".
		// 	$bono_precio."---".
		// 	$promesa_bono_precio_ven."---".
		// 	$promesa_bono_precio_supervisor."---".
		// 	$promesa_bono_precio_jefe."---".
		// 	$escritura_bono_precio_ven."---".
		// 	$escritura_bono_precio_supervisor."---".
		// 	$escritura_bono_precio_jefe."---".
		// 	$total_bono_precio."---".
		// 	$total_bono_precio_supervisor."---".
		// 	$total_bono_precio_jefe."<br>";

		$consulta = "UPDATE venta_venta SET factor_categoria_ven = ?, porcentaje_comision_ven = ?, promesa_porcentaje_comision_reparto_ven = ?, promesa_monto_comision_ven = ?, promesa_monto_comision_supervisor_ven = ?, promesa_monto_comision_jefe_ven = ?, escritura_porcentaje_comision_reparto_ven = ?, escritura_monto_comision_ven = ?, escritura_monto_comision_supervisor_ven = ?, escritura_monto_comision_jefe_ven = ?, total_comision_ven = ?, total_comision_supervisor_ven = ?, total_comision_jefe_ven = ?, bono_vivienda_ven = ?, porcentaje_bono_precio_ven = ?, promesa_bono_precio_ven = ?, promesa_bono_precio_supervisor_ven = ?, promesa_bono_precio_jefe_ven = ?, escritura_bono_precio_ven = ?, escritura_bono_precio_supervisor_ven = ?, escritura_bono_precio_jefe_ven = ?, total_bono_precio_ven = ?, total_bono_precio_supervisor_ven = ?, total_bono_precio_jefe_ven = ?, valor_factor_ven = ?, id_supervisor_ven = ?, id_jefe_ven = ?, escritura_monto_comision_operacion_ven = ? WHERE id_ven = ?";
		$conexion->consulta_form($consulta,array(
		$id_cat_vend,
		$porcentaje_comision_vendedor,
		$porcentaje_promesa,
		$monto_promesa_comision,
		$monto_promesa_comision_supervisor,
		$monto_promesa_comision_jefe,
		$porcentaje_escritura,
		$monto_escritura_comision,
		$monto_escritura_comision_supervisor,
		$monto_escritura_comision_jefe,
		$total_comision,
		$total_comision_supervisor,
		$total_comision_jefe,
		$bono_viv,
		$bono_precio,
		$promesa_bono_precio_ven,
		$promesa_bono_precio_supervisor,
		$promesa_bono_precio_jefe,
		$escritura_bono_precio_ven,
		$escritura_bono_precio_supervisor,
		$escritura_bono_precio_jefe,
		$total_bono_precio,
		$total_bono_precio_supervisor,
		$total_bono_precio_jefe,
		$factor,
		$id_supervisor,
		$id_jefe,
		$monto_escritura_operacion,
		$id_ven));
		// $conexion->cerrar();
		$conexion->cerrar();

	}

}
?>
