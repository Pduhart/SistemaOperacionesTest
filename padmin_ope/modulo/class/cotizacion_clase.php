<?php
require "../../config.php";
include("../../class/conexion.php");
class cotizacion
{
	private $id_viv;
	private $id_mod;
	private $id_vend;
	private $id_pro;
	private $id_can_cot;
	private $id_pre_cot;
	private $id_est_cot;
	private $fecha_cot;
	private $porcentaje_credito_cot;
	private $numero_cot;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function cotizacion_crea($id_viv,$id_mod,$id_vend,$id_pro,$id_can_cot,$id_est_cot,$fecha_cot,$id_pre_cot,$id_seg_int_cot,$id_ren_cot,$porcentaje_credito_cot,$numero_cot){
		$this->id_viv = $id_viv;
		$this->id_mod = $id_mod;
		$this->id_vend = $id_vend;
		$this->id_pro = $id_pro;
		$this->id_can_cot = $id_can_cot;
		$this->id_est_cot = $id_est_cot;
		$this->fecha_cot = $fecha_cot;
		$this->id_pre_cot = $id_pre_cot;
		$this->id_seg_int_cot = $id_seg_int_cot;
		$this->id_ren_cot = $id_ren_cot;
		$this->porcentaje_credito_cot = $porcentaje_credito_cot;
		$this->numero_cot = $numero_cot;
	}
	//funcion de insercion
	public function cotizacion_insert(){
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
		// $fecha_promesa = "0000-00-00 00:00:00";
		$fecha_promesa = null;
		$consulta = "INSERT INTO cotizacion_cotizacion VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/

		$conexion->consulta_form($consulta,array(0,$this->id_viv,$this->id_mod,$id_vendedor,$this->id_pro,$this->id_can_cot,$this->id_est_cot,$this->fecha_cot,$fecha_promesa,$this->id_pre_cot,$this->id_seg_int_cot,$this->id_ren_cot,$this->porcentaje_credito_cot,$this->numero_cot));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function cotizacion_update($id){
		$conexion = new conexion();

		$consulta = "UPDATE cotizacion_cotizacion SET id_viv = ?, id_mod = ?, id_pro = ?, id_can_cot = ?, id_pre_cot = ?, fecha_cot = ?, id_seg_int_cot = ?, id_ren_cot = ?, porcentaje_credito_cot = ? WHERE id_cot = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_viv,$this->id_mod,$this->id_pro,$this->id_can_cot,$this->id_pre_cot,$this->fecha_cot,$this->id_seg_int_cot,$this->id_ren_cot,$this->porcentaje_credito_cot,$id));


		$conexion->cerrar();
	}
	

	//funcion de propietario
	public function cotizacion_update_propietario($rut,$propietario,$nombre_pro,$nombre2_pro,$apellido_paterno_pro, $apellido_materno_pro,$correo_pro,$fono_pro,$profesion_pro,$direccion_trabajo_pro,$sexo_pro,$region_pro,$nacionalidad_pro,$comuna_pro){

		$conexion = new conexion();

		if(empty($propietario)){
			$consulta = 
				"
				SELECT 
					id_pro
				FROM
					propietario_propietario
				WHERE 
					rut_pro = ?
				";
			$conexion->consulta_form($consulta,array($rut));
			$total_consulta = $conexion->total();

			if($total_consulta == 0){
				$consulta = "INSERT INTO propietario_propietario VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//21
				$conexion->consulta_form($consulta,array(0,$nacionalidad_pro,$region_pro,$sexo_pro,1,1,$profesion_pro,1,$rut,'',$nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$fono_pro,0,$correo_pro,'','',$direccion_trabajo_pro,null,$comuna_pro,0,null,0,null,null));
				$propietario = $conexion->ultimo_id();
				$consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
				$conexion->consulta_form($consulta,array(0,$_SESSION["sesion_id_vend"],$propietario));


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
				$conexion->consulta_form($consulta,array($correo_pro));
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
						$conexion->consulta_form($consulta,array(0,$fila_lista["id_lis"],$correo_pro));		
					}
					else{
						$consulta = "INSERT INTO lista_lista VALUES(?,?)";
						$ultimoIdInsert = $conexion->ultimo_id();
						$conexion->consulta_form($consulta,array(0,"Lista_".$ultimoIdInsert));
						$consulta = "INSERT INTO lista_correo_lista VALUES(?,?,?)";
						$conexion->consulta_form($consulta,array(0,$ultimoIdInsert,$correo_pro));				
					}
				}

			}
			else{
				$fila = $conexion->extraer_registro_unico();
				$id_pro = $fila['id_pro'];
				$consulta = 
					"
					SELECT 
						id_vend
					FROM
						vendedor_propietario_vendedor
					WHERE 
						id_pro = ?
					";
				$conexion->consulta_form($consulta,array($id_pro));
				$total_consulta2 = $conexion->total();

				if($total_consulta2 > 0){
					$fila = $conexion->extraer_registro_unico();
					if($fila['id_vend'] != $_SESSION["sesion_id_vend"]){
						$jsondata['envio'] = 4;
						echo json_encode($jsondata);
						exit();
					}
				}
				else{
					$consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
					$conexion->consulta_form($consulta,array(0,$_SESSION["sesion_id_vend"],$propietario));
				}

				$consulta = "UPDATE propietario_propietario SET nombre_pro = ?, nombre2_pro = ?,apellido_paterno_pro = ?,apellido_materno_pro = ?,correo_pro = ?,fono_pro = ?, id_prof = ?, direccion_trabajo_pro = ?, id_sex = ?, id_reg = ?, id_nac = ?, id_com = ? WHERE id_pro = ?";
				$conexion->consulta_form($consulta,array($nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$correo_pro,$fono_pro,$profesion_pro,$direccion_trabajo_pro,$sexo_pro,$region_pro,$nacionalidad_pro,$comuna_pro,$propietario));
			}
			


		}
		else{
			$consulta = "UPDATE propietario_propietario SET nombre_pro = ?, nombre2_pro = ?,apellido_paterno_pro = ?,apellido_materno_pro = ?,correo_pro = ?,fono_pro = ?, id_prof = ?, direccion_trabajo_pro = ?, id_sex = ?, id_reg = ?, id_nac = ?, id_com = ? WHERE id_pro = ?";
			$conexion->consulta_form($consulta,array($nombre_pro,$nombre2_pro,$apellido_paterno_pro,$apellido_materno_pro,$correo_pro,$fono_pro,$profesion_pro,$direccion_trabajo_pro,$sexo_pro,$region_pro,$nacionalidad_pro,$comuna_pro,$propietario));
		}
		$conexion->cerrar();
		return $propietario;
	}


	private function update_count_tracing($id){
		$conexion = new conexion();

		$today = date("Y-m-d");
		
		$consulta = "SELECT cantidad_seg_pro FROM propietario_propietario WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$cantidad_seg_pro = $fila['cantidad_seg_pro'];

		$cantidad_seg_pro_inc = $cantidad_seg_pro + 1;

		$consulta = "UPDATE propietario_propietario SET cantidad_seg_pro = ?, fecha_seg_pro = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($cantidad_seg_pro_inc,$today,$id));
		$count = $conexion->total();
		return true;
	}

	public function update_numero_cotizacion(){
		$conexion = new conexion();
	
		$consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = 166";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$numero_cot = $fila['valor_par'];

		$numero_cot_prox = $numero_cot + 1;

		$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE id_par = 166";
		$conexion->consulta_form($consulta,array($numero_cot_prox));
		return $numero_cot;
	}

	public function cotizacion_insert_seguimiento($id,$interes,$medio,$descripcion,$fecha,$id_pre_cot,$nombre2_pro,$apellido_materno_pro,$id_nac,$id_reg,$id_com,$id_prof,$direccion_trabajo_pro,$id_pro){
		$conexion = new conexion();
		$consulta = "INSERT INTO cotizacion_seguimiento_cotizacion VALUES(?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$interes,$medio,$fecha,$descripcion,$id_pre_cot));

		$state = $this->update_count_tracing($id_pro);

		$consulta = "UPDATE cotizacion_cotizacion SET id_pre_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array($id_pre_cot,$id));

		$consulta = "UPDATE cotizacion_cotizacion SET id_seg_int_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array($interes,$id));

		$consulta = "UPDATE propietario_propietario SET nombre2_pro = ?,apellido_materno_pro = ?, id_prof = ?, direccion_trabajo_pro = ?, id_reg = ?, id_nac = ?, id_com = ? WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($nombre2_pro,$apellido_materno_pro,$id_prof,$direccion_trabajo_pro,$id_reg,$id_nac,$id_com,$id_pro));

		$conexion->cerrar();

	}

	public function cotizacion_insert_evento($id,$id_pro,$categoria,$fecha,$time,$descripcion,$nombre,$id_usu){
		$conexion = new conexion();
		$consulta = "INSERT INTO evento_evento VALUES(?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$categoria,1,$nombre,$fecha,$descripcion,$time,$id_pro,$id,$id_usu));
		$conexion->cerrar();

	}

	public function cotizacion_insert_promesa($id,$fecha){
		$conexion = new conexion();
		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ?, fecha_promesa_cot = ? WHERE id_cot = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array(4,$fecha,$id));
		$conexion->cerrar();

	}

	public function valida_venta_unica($id_viv){
		$conexion = new conexion();
		$consulta_tiene_promesa = 
				"
				SELECT
					ven.id_ven
				FROM
					venta_venta AS ven
				WHERE
					ven.id_viv = ".$id_viv." AND
					ven.id_est_ven <> 3
				";
		$conexion->consulta($consulta_tiene_promesa);
		$tiene_promesa = $conexion->total();
		$conexion->cerrar();
		return $tiene_promesa;
	}

	public function cotizacion_insert_venta($id_viv,
											$id_pro,
											$id_ban,
											$id_pie_ven,
											$id_for_pag,
											$id_des,
											$id_pre,
											$id_pie_abo_ven,
											$id_tip_pag,
											$id_est_ven,
											$fecha_ven,
											$fecha_promesa_ven,
											$monto_reserva_ven,
											$descuento_manual_ven,
											$descuento_precio_ven,
											$descuento_adicional_ven,
											$descuento_ven,
											$pie_cancelado_ven,
											$pie_cobrar_ven,
											$monto_estacionamiento_ven,
											$monto_bodega_ven,
											$monto_vivienda_ven,
											$monto_vivienda_ingreso_ven,
											$monto_ven,
											$cotizacion_ven,
											$precio_descuento
											)
	{
		$conexion = new conexion();
		$id_cat_vend_supervisor = 0;
		$id_supervisor = 0;

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
		$factor_supervisor = 0;
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

		$monto_comision_jefe = $monto_ven_comision * ($porcentaje_comision_jefe / 100); 
		$total_comision_jefe = $monto_comision_jefe * $factor_jefe;
		$monto_comision_jefe = $monto_comision_jefe * $factor_jefe;
		

		$monto_promesa_comision_jefe = $monto_comision_jefe * ($porcentaje_promesa / 100); 
		$monto_escritura_comision_jefe = $monto_comision_jefe * ($porcentaje_escritura / 100); 

		

		if($monto_vivienda_ingreso_ven == $monto_vivienda_ven && $id_des == 0 && $precio_descuento == 2){
			$total_descuento = ($monto_vivienda_ven * $porcentaje_descuento) / 100;
			$total_bono_precio = $total_descuento * ($bono_precio / 100);

			$promesa_bono_precio_ven = $total_bono_precio * ($porcentaje_promesa / 100);
			$escritura_bono_precio_ven = $total_bono_precio * ($porcentaje_escritura / 100);

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

		// aca debería limpiar monto_credito_ven cuando es contado y guardarlo como pie por pagar
		// if ($id_for_pag==2) {
		// 	$pie_cobrar_ven = $monto_credito_ven;
		// 	$monto_credito_ven = 0;
		// }

		$fecha_escritura_ven = null;
		
		$consulta = "INSERT INTO venta_venta VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//60
		$conexion->consulta_form($consulta,array(0,
												$id_viv,
												$id_vend,
												$id_pro,
												$id_ban,
												$id_pie_ven,
												$id_for_pag,
												$id_des,
												$id_pre,
												$id_pie_abo_ven,
												$id_tip_pag,
												$id_est_ven,
												$fecha_ven,
												$fecha_promesa_ven,
												$monto_reserva_ven,
												$descuento_manual_ven,
												$descuento_precio_ven,
												$descuento_adicional_ven,
												$descuento_ven,
												$pie_cancelado_ven,
												$pie_cobrar_ven,
												$monto_estacionamiento_ven,
												$monto_bodega_ven,
												$monto_vivienda_ven,
												$monto_vivienda_ingreso_ven,
												$monto_ven,
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
												$bono_viv,$bono_precio,
												$promesa_bono_precio_ven,
												$promesa_bono_precio_supervisor,
												$promesa_bono_precio_jefe,
												$escritura_bono_precio_ven,
												$escritura_bono_precio_supervisor,
												$escritura_bono_precio_jefe,
												$total_bono_precio,
												$total_bono_precio_supervisor,
												$total_bono_precio_jefe,
												1,
												$cotizacion_ven,
												$monto_credito_ven,
												0,
												0,
												$factor,
												$id_supervisor,
												$id_jefe,
												0,
												$monto_escritura_operacion,
												$fecha_escritura_ven));

		$id_venta = $conexion->ultimo_id();
		
		$consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
        $conexion->consulta_form($consulta,array(0,$id_venta,4));

		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array(4,$cotizacion_ven));

		// cambio de estados vivienda y registro propietario
		// aca va a pasar la vivienda a vendida
		$id_ven = $id_venta;
		$propietario = $id_pro;
		$consulta = "UPDATE vivienda_vivienda SET id_est_viv = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));

        $consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));

        $consulta = "UPDATE bodega_bodega SET id_est_bod = ? WHERE id_viv = ?";    
        $conexion->consulta_form($consulta,array(2,$id_viv));

        // consulta para ver si fue la última venta
        $consulta_termino = "SELECT id_viv FROM vivienda_vivienda WHERE id_est_viv = 1";
		$conexion->consulta($consulta_termino);
        $queda_viv = $conexion->total();
		if ($queda_viv==0) {
			$fecha_termino = date("d-m-Y",strtotime($fecha_ven));
			// registra la fecha término de venta
			$consulta = "UPDATE parametro_parametro SET valor_par = ? WHERE valor2_par = ? AND id_con = ?";    
        	$conexion->consulta_form($consulta,array($fecha_termino,26,$id_con));
		}


        // fin revisión

		$consulta = "INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$propietario,$id_viv));

		$conexion->cerrar();

	}

	public function cotizacion_insert_desistimiento($id,$fecha,$descripcion){
		$conexion = new conexion();
		$consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";
		$conexion->consulta_form($consulta,array(3,$id));

		$consulta = "INSERT INTO cotizacion_desistimiento_cotizacion VALUES(?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$fecha,$descripcion));

		$conexion->cerrar();

	}
	//funcion de eliminacion
	public function cotizacion_delete($id){
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

				$consulta = "UPDATE estacionamiento_estacionamiento SET id_viv = ?, id_est_esta = ? WHERE id_esta = ?";
				$conexion->consulta_form($consulta,array($id_vivienda,2,$fila["id_esta"]));
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

				$consulta = "UPDATE bodega_bodega SET id_viv = ?, id_est_bod = ? WHERE id_bod = ?";
				$conexion->consulta_form($consulta,array($id_vivienda,2,$fila["id_bod"]));
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

}
?>
