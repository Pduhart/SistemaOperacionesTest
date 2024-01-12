<?php
require "../../config.php";
include("../../class/conexion.php");
date_default_timezone_set('America/Santiago');
class operacion
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
	function operacion_crea($id_viv,$id_mod,$id_vend,$id_pro,$id_can_cot,$id_est_cot,$fecha_cot){
		$this->id_viv = $id_viv;
		$this->id_mod = $id_mod;
		$this->id_vend = $id_vend;
		$this->id_pro = $id_pro;
		$this->id_can_cot = $id_can_cot;
		$this->id_est_cot = $id_est_cot;
		$this->fecha_cot = $fecha_cot;
	}
	//funcion de insercion
	public function operacion_insert(){
		$conexion = new conexion();
		/*$consulta = "SELECT id_pro FROM cotizacion_cotizacion WHERE id_pro = ? ";
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
		$consulta = "INSERT INTO cotizacion_cotizacion VALUES(?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_viv,$this->id_mod,$id_vendedor,$this->id_pro,$this->id_can_cot,$this->id_est_cot,$this->fecha_cot,$fecha_promesa));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function operacion_update($id){
		$conexion = new conexion();

		$consulta = "UPDATE cotizacion_cotizacion SET id_viv = ?, id_mod = ?, id_pro = ?, id_can_cot = ? WHERE id_cot = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_viv,$this->id_mod,$this->id_pro,$this->id_can_cot,$id));


		$conexion->cerrar();
	}
	
	public function operacion_insert_fecha($id,$fecha,$id_eta){
		$conexion = new conexion();
		$consulta = "UPDATE venta_etapa_venta SET id_est_eta_ven = ?, fecha_desde_eta_ven = ? WHERE id_ven = ? AND id_eta = ?";
		$conexion->consulta_form($consulta,array(2,$fecha,$id,$id_eta));
		$conexion->cerrar();

	}
	public function operacion_insert_observacion($id,$usuario,$etapa,$fecha,$observacion){
		$conexion = new conexion();
		$consulta = "INSERT INTO venta_observacion_etapa_venta VALUES(?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$etapa,$usuario,$fecha,$observacion));
		$conexion->cerrar();

	}

	public function operacion_delete_observacion($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM venta_observacion_etapa_venta WHERE id_obs_eta_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();

	}

	public function operacion_delete_rechazo($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM venta_rechazo_etapa_venta WHERE id_rec_eta_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();

	}

	//funcion de propietario
	public function operacion_update_propietario($propietario,$nombre_pro,$nombre2_pro,$apellido_paterno_pro, $apellido_materno_pro,$correo_pro,$fono_pro){
		$conexion = new conexion();
		
		$consulta = "UPDATE propietario_propietario SET nombre_pro = ?, nombre2_pro = ?,apellido_paterno_pro = ?,apellido_materno_pro = ?,correo_pro = ?,fono_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$correo_pro,$fono_pro,$propietario));

		$conexion->cerrar();
	}

	public function operacion_insert_seguimiento($id,$interes,$medio,$descripcion,$fecha){
		$conexion = new conexion();
		$consulta = "INSERT INTO cotizacion_seguimiento_cotizacion VALUES(?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$interes,$medio,$fecha,$descripcion,));

		$consulta = "SELECT id_pro FROM cotizacion_cotizacion WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$id_pro = $fila['id_pro'];

		$consulta = "SELECT cantidad_seg_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id_pro));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_seg_pro = $fila['cantidad_seg_pro'];

		$cantidad_seg_pro = $cantidad_seg_pro + 1;
		$consulta = "UPDATE propietario_propietario SET cantidad_seg_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($cantidad_seg_pro,$id_pro));
		$count = $conexion->total();

		
		$conexion->cerrar();

	}
	public function operacion_insert_promesa($id,$fecha){
		$conexion = new conexion();
		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ?, fecha_promesa_cot = ? WHERE id_cot = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array(4,$fecha,$id));
		$conexion->cerrar();

	}

	public function operacion_insert_venta($id_viv,$id_pro,$id_ban,$id_pie_ven,$id_for_pag,$id_des,$id_pre,$id_pie_abo_ven,$id_tip_pag,$id_est_ven,$fecha_ven,$fecha_promesa_ven,$monto_reserva_ven,$descuento_manual_ven,$descuento_precio_ven,$descuento_adicional_ven,$descuento_ven,$pie_cancelado_ven,$pie_cobrar_ven,$monto_estacionamiento_ven,$monto_bodega_ven,$monto_vivienda_ven,$monto_vivienda_ingreso_ven,$monto_ven,$numero_compra_ven,$cotizacion_ven,$precio_descuento){
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
	public function operacion_insert_desistimiento($id,$fecha,$descripcion){
		$conexion = new conexion();
		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array(3,$id));

		$consulta = "INSERT INTO cotizacion_desistimiento_cotizacion VALUES(?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$fecha,$descripcion));

		$conexion->cerrar();

	}
	//funcion de eliminacion
	public function operacion_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM cotizacion_cotizacion WHERE id_cot = $id";
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

	public function operacion_descuento_adicional($id){
		$conexion = new conexion();
		$consulta="SELECT monto_des FROM descuento_descuento WHERE id_des = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$monto_des = $fila['monto_des'];
		$conexion->cerrar();
		return $monto_des;
	}	
	public function operacion_pie($id){
		$conexion = new conexion();
		$consulta="SELECT valor_pie_ven FROM venta_pie_venta WHERE id_pie_ven = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$valor_pie_ven = $fila['valor_pie_ven'];
		$conexion->cerrar();
		return $valor_pie_ven;
	}	
	public function operacion_estacionamiento($id){
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
	public function operacion_bodega($id){
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
	public function operacion_insert_estacionamiento($id_vivienda,$id_venta,$id_estacionamiento){
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
	public function operacion_insert_bodega($id_vivienda,$id_venta,$id_bodega){
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
	public function operacion_update_estado($id){
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

}
?>
