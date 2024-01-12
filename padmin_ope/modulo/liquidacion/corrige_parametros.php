<?php
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$condo = $_GET["condo"];
if(!isset($_GET["condo"])){
	echo "falta: paramentro --> condo<br>";
	echo "se puede poner: paramentro --> &venta";
	exit;
}

$filtro_consulta = "";
$venta = $_GET["venta"];
if(isset($_GET["venta"])){
	echo "corrige venta: ".$venta;
	$filtro_consulta = " AND ven.id_ven = ".$venta."";
}

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
				viv.id_tor = ".$condo."
				".$filtro_consulta."
				";
	$conexion->consulta($consulta_ventas);
	$cantidad_venta = $conexion->total();
	$fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
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

	        echo "---------------->".$monto_ven_comision."-----".$porcentaje_comision_jefe."<br>";

			$monto_comision_jefe = $monto_ven_comision * ($porcentaje_comision_jefe / 100); 
			$total_comision_jefe = $monto_comision_jefe * $factor_jefe;
			$monto_comision_jefe = $monto_comision_jefe * $factor_jefe;

			echo $factor_jefe."----------".$total_comision_jefe."<br>";

			$monto_promesa_comision_jefe = $monto_comision_jefe * ($porcentaje_promesa / 100); 
			$monto_escritura_comision_jefe = $monto_comision_jefe * ($porcentaje_escritura / 100); 

			// ----------------------------$precio_descuento

			if($monto_vivienda_ingreso_ven == $monto_vivienda_ven && $id_des == 0){
				$total_descuento = ($monto_vivienda_ven * $porcentaje_descuento) / 100;

				$total_descuento_uf = round($total_descuento);

				$total_bono_precio = $total_descuento_uf * ($bono_precio / 100);

				echo "---->".$monto_vivienda_ven." ".$porcentaje_descuento." ".$bono_precio;
				echo "<br>valor viv con desc".$total_descuento_uf."<br>";
				echo "<br>Total bono precio".$total_bono_precio."<br>";


				$promesa_bono_precio_ven = $total_bono_precio * ($porcentaje_promesa / 100);
				$escritura_bono_precio_ven = $total_bono_precio * ($porcentaje_escritura / 100);

				echo "prom: ".$promesa_bono_precio_ven." ecr:".$escritura_bono_precio_ven;

				$promesa_bono_precio_ven = floor($promesa_bono_precio_ven * 1000) / 1000;
				$promesa_bono_precio_ven = round($promesa_bono_precio_ven, 2);

				$escritura_bono_precio_ven = floor($escritura_bono_precio_ven * 1000) / 1000;
				$escritura_bono_precio_ven = round($escritura_bono_precio_ven, 2);

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

			echo $id_ven."<--------".$id_cat_vend."---".
				$porcentaje_comision_vendedor."---".
				$porcentaje_promesa."---".
				$monto_promesa_comision."---".
				$monto_promesa_comision_supervisor."---".
				$monto_promesa_comision_jefe."---".
				$porcentaje_escritura."---".
				$monto_escritura_comision."---".
				$monto_escritura_comision_supervisor."---".
				$monto_escritura_comision_jefe."---".
				$total_comision."---".
				$total_comision_supervisor."---".
				$total_comision_jefe."---".
				$bono_viv."---".
				$bono_precio."---".
				$promesa_bono_precio_ven."---".
				$promesa_bono_precio_supervisor."---".
				$promesa_bono_precio_jefe."---".
				$escritura_bono_precio_ven."---".
				$escritura_bono_precio_supervisor."---".
				$escritura_bono_precio_jefe."---".
				$total_bono_precio."---".
				$total_bono_precio_supervisor."---".
				$total_bono_precio_jefe."<br>";

			$consulta = "UPDATE venta_venta SET factor_categoria_ven = ?,
												porcentaje_comision_ven = ?,
												promesa_porcentaje_comision_reparto_ven = ?,
												promesa_monto_comision_ven = ?,
												promesa_monto_comision_supervisor_ven = ?,
												promesa_monto_comision_jefe_ven = ?,
												escritura_porcentaje_comision_reparto_ven = ?,
												escritura_monto_comision_ven = ?,
												escritura_monto_comision_supervisor_ven = ?,
												escritura_monto_comision_jefe_ven = ?,
												total_comision_ven = ?,
												total_comision_supervisor_ven = ?,
												total_comision_jefe_ven = ?,
												bono_vivienda_ven = ?,
												porcentaje_bono_precio_ven = ?,
												promesa_bono_precio_ven = ?,
												promesa_bono_precio_supervisor_ven = ?,
												promesa_bono_precio_jefe_ven = ?,
												escritura_bono_precio_ven = ?,
												escritura_bono_precio_supervisor_ven = ?,
												escritura_bono_precio_jefe_ven = ?,
												total_bono_precio_ven = ?,
												total_bono_precio_supervisor_ven = ?,
												total_bono_precio_jefe_ven = ?,
												valor_factor_ven = ?,
												id_supervisor_ven = ?,
												id_jefe_ven = ?,
												escritura_monto_comision_operacion_ven = ?
												WHERE id_ven = ?";
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


        }
    }


?>