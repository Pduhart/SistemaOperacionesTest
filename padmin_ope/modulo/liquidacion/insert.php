<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_liquidacion_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_SESSION["sesion_fecha_desde_liquidacion_panel"])){
	header("Location: ../../panel.php");
	exit();
}

require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$fecha_desde = date("Y-m-d",strtotime($_SESSION["sesion_fecha_desde_liquidacion_panel"]));
$fecha_hasta = date("Y-m-d",strtotime($_SESSION["sesion_fecha_hasta_liquidacion_panel"]));

$mes_desde_liq = date("m",strtotime($fecha_desde));
$mes_hasta_liq = date("m",strtotime($fecha_hasta));

$fecha_desde_ant = date('Y-m-d',strtotime ( '-1 day' , strtotime ($fecha_desde)));

$fecha_desde_consulta = date("Y-m-d 23:59:59",strtotime($fecha_desde_ant));
$fecha_hasta_consulta = date("Y-m-d 23:59:59",strtotime($fecha_hasta));

// $condo = 6;

$mes = $_SESSION["sesion_mes_liquidacion_panel"];
$anio = $_SESSION["sesion_anio_liquidacion_panel"];
$consulta = "INSERT INTO cierre_cierre VALUES(?,?,?,?,?,?)";
$conexion->consulta_form($consulta,array(0,$_SESSION["sesion_id_panel"],$_SESSION["sesion_mes_liquidacion_panel"],$_SESSION["sesion_anio_liquidacion_panel"],$fecha_desde,$fecha_hasta));
$ultimo_id = $conexion->ultimo_id();

// $consulta = 
//     "
//     SELECT
//         valor2_par,
//         valor_par
//     FROM
//         parametro_parametro
//     WHERE
//         id_con = ? AND
//         valor2_par IN (13,15)
//     ";
// $conexion->consulta_form($consulta,array($condominio));
// $fila_consulta = $conexion->extraer_registro();
// if(is_array($fila_consulta)){
//     foreach ($fila_consulta as $fila) {
//         if($fila['valor2_par'] == 13){
//             $porcentaje_bono_precio_jefe = $fila['valor_uf'];
//         }
//         else{
//             $porcentaje_bono_precio_supervisor = $fila['valor_uf'];
//         }
//     }
// }

//------- INSERCION DE BONOS ---------


$consulta = 
    "
    SELECT
        valor_uf
    FROM
        uf_uf
    WHERE
       fecha_uf = ?
    ";
$conexion->consulta_form($consulta,array($fecha_hasta));
$fila = $conexion->extraer_registro_unico();
$valor_uf = $fila["valor_uf"];

$UF_LIQUIDACION = $valor_uf;

$consulta_condominios = 
    "
    SELECT
        id_con,
        nombre_con
    FROM
        condominio_condominio
    WHERE
        id_est_con = 1 AND
        id_con > 3
    ";
$conexion->consulta($consulta_condominios);
$cantidad_cond = $conexion->total();

$cantidad_venta_totales = 0;

$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila_cond) {

    	$condo = $fila_cond['id_con'];
    	$nombre_con = utf8_encode($fila_cond['nombre_con']);

    	$arreglo_vendedor = array();
		$arreglo_vendedor_promesa = array();

		//------------ VENDEDOR ------------
		$consulta = 
		    "
		    SELECT
		        vend.id_vend,
		        vend.id_usu,
		        vend.nombre_vend,
		        vend.apellido_paterno_vend,
		        vend.apellido_materno_vend,
		        tor.id_con
		    FROM
		        vendedor_vendedor AS vend
		        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		        INNER JOIN venta_estado_historial_venta AS his ON ven.id_ven = his.id_ven
		        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		    WHERE
		        his.id_est_ven = ? AND
		        tor.id_con = ".$condo." AND
		        vend.id_est_vend = 1 AND 
		        ven.fecha_promesa_ven <= '".$fecha_hasta_consulta."' 
		    GROUP BY 
		        vend.id_vend,
		        vend.id_usu,
		        vend.nombre_vend,
		        vend.apellido_paterno_vend,
		        vend.apellido_materno_vend,
		        tor.id_con
		    ";

		 // echo $consulta;
		$conexion->consulta_form($consulta,array(4));
		$cantidad_venta = $conexion->total();
		if($cantidad_venta > 0){
			$fila_consulta = $conexion->extraer_registro();
			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        if(!in_array($fila["id_vend"],$arreglo_vendedor)){
			            $arreglo_vendedor[$fila["id_vend"]] = $fila["id_vend"];
			        }
			        $consulta = 
			            "
			            SELECT
			                ven.id_ven,
			                ven.id_est_ven
			            FROM
			                vendedor_vendedor AS vend
			                INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
			                INNER JOIN venta_estado_historial_venta AS his ON ven.id_ven = his.id_ven
			                INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
			                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			            WHERE
			                tor.id_con = ? AND
			                vend.id_vend = ? AND
			                his.id_est_ven = 4 AND
			                ven.fecha_promesa_ven > '".$fecha_desde_consulta."' AND
			                ven.fecha_promesa_ven <= '".$fecha_hasta_consulta."' AND NOT EXISTS
			                (
			                SELECT
			                    des_ven.id_ven
			                FROM
			                    venta_desestimiento_venta AS des_ven
			                WHERE
			                    des_ven.id_ven = ven.id_ven
			                ) AND NOT EXISTS
                            (
                            SELECT
                                b.id_ven
                            FROM
                                cierre_venta_cierre AS b
                            WHERE
                                b.id_ven = ven.id_ven AND
                                b.id_est_ven = his.id_est_ven
                            )
			                
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_vend']));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        $contador_promesa = 0;
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $contador_promesa++;
			            }
			        }
			        if($contador_promesa > 0){
			        	$arreglo_vendedor_promesa[$fila['id_vend']][$fila['id_con']] = $contador_promesa;
			            $consulta = 
			                "
			                SELECT
			                    desde_bon,
			                    hasta_bon,
			                    monto_bon,
			                    nombre_bon,
			                    id_con
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    desde_bon <= ? AND
			                    hasta_bon >= ? AND
			                    id_tip_bon = 3 AND
			                    id_con = ? AND
			                    id_cat_bon = ?
			                ";
			            $conexion->consulta_form($consulta,array($contador_promesa,$contador_promesa,$fila['id_con'],1));
			            $cantidad_bono = $conexion->total();
			            // $fila_bono = $conexion->extraer_registro_unico();
			            if($cantidad_bono > 0){
			            	$fila_consulta_detalle = $conexion->extraer_registro();
				            if(is_array($fila_consulta_detalle)){
					            foreach ($fila_consulta_detalle as $fila_bono) {
					            	$monto_bono_rango = 0;
				            		$monto_bono_rango_plata = 0;
					                
					                $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
					                $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_bono["nombre_bon"],$fila_bono["desde_bon"],$fila_bono["hasta_bon"],$fila_bono["monto_bon"]));
					            }
					        }
			            }
			        }
			        //---------------------------------------
			        //---- BONO CATEGORIA FECHA SIN MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 3 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],2));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven
			                    FROM
			                        venta_venta AS ven 
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        
			                    WHERE
			                        tor.id_con = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND
			                        ven.id_vend = ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        ) AND NOT EXISTS
					                (
					                SELECT
					                    des_ven.id_ven
					                FROM
					                    venta_desestimiento_venta AS des_ven
					                WHERE
					                    des_ven.id_ven = ven.id_ven
					                )
			                    GROUP BY 
			                        ven.id_ven 
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila['id_vend'],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],2,$fila_venta["id_ven"]));
			                        }
			                    }
								$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }
					//---- BONO CATEGORIA FECHA CON MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 3 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],3));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven
			                    FROM
			                        venta_venta AS ven 
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        
			                    WHERE
			                        tor.id_con = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND
			                        viv.id_mod = ? AND
			                        ven.id_vend = ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        ) AND NOT EXISTS
					                (
					                SELECT
					                    des_ven.id_ven
					                FROM
					                    venta_desestimiento_venta AS des_ven
					                WHERE
					                    des_ven.id_ven = ven.id_ven
					                )
			                    GROUP BY 
			                        ven.id_ven 
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_mod"],$fila['id_vend'],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],3,$fila_venta["id_ven"]));
			                        }
			                    }
								$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }

			        //---- BONO TÉRMINO VENTAS 
			        $no_termino = 0;

					// preguntar si estoy en el mes de cierre venta
					$consulta_termino = 
			            "
			            SELECT
			                valor_par
			            FROM
			                parametro_parametro
			            WHERE
			                valor2_par = ? AND
			                id_con = ? 
			            ";
			        
			        $conexion->consulta_form($consulta_termino,array(26,$fila['id_con']));
			        $fila_term = $conexion->extraer_registro_unico();
			        $fecha_termino = $fila_term['valor_par'];
					$mes_termino = '';
			        if ($fecha_termino<>'' & $fecha_termino<>null) {
			        	$mes_termino = date("n",strtotime($fecha_termino));
			        } else {
			        	$no_termino = 1;
			        }

			        if ($mes_termino<>$mes) {
			        	$no_termino = 1;
			        }

					if ($no_termino==0) {
					// echo $mes_termino." - ".$mes;
						$consulta = 
			                "
			                SELECT
			                    *
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    id_tip_bon = 3 AND
			                    id_con = ? AND
			                    id_cat_bon = ? AND
			                    MONTH(fecha_hasta_bon) = '".$mes."' AND
			                    YEAR(fecha_hasta_bon) = '".$anio."'
			                ";
			            $conexion->consulta_form($consulta,array($fila['id_con'],4));

			            $fila_consulta_detalle = $conexion->extraer_registro();
			            if(is_array($fila_consulta_detalle)){
			                foreach ($fila_consulta_detalle as $fila_det) {
			                	// echo $mes_termino." - ".$mes;
								if ($fila_det["desde_bon"]==0 && $fila_det["hasta_bon"]==0) {
									$consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
				                    $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],4,$fila_venta["id_ven"]));

									$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
				                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));

								} else {
									$consulta = 
					                    "
					                    SELECT
					                        ven.id_ven
					                    FROM
					                        venta_venta AS ven 
					                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
					                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
					                        
					                    WHERE
					                        tor.id_con = ? AND
					                        ven.fecha_promesa_ven >= ? AND
					                        ven.fecha_promesa_ven <= ? AND
					                        ven.id_vend = ? AND NOT
					                        ven.id_est_ven = 3 AND NOT EXISTS
					                        (
					                        SELECT
					                            b.id_ven_bon
					                        FROM
					                            bono_venta_bono AS b
					                        WHERE
					                            b.id_ven = ven.id_ven AND
					                            b.id_bon = ?
					                        ) AND NOT EXISTS
							                (
							                SELECT
							                    des_ven.id_ven
							                FROM
							                    venta_desestimiento_venta AS des_ven
							                WHERE
							                    des_ven.id_ven = ven.id_ven
							                )
					                    GROUP BY 
					                        ven.id_ven 
					                    ";
			                        $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila['id_vend'],$fila_det["id_bon"]));
			                        $cantidad_bono = $conexion->total();
			                        if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                        	$fila_consulta_venta = $conexion->extraer_registro();
					                    if(is_array($fila_consulta_venta)){
					                        foreach ($fila_consulta_venta as $fila_venta) {
					                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
					                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],4,$fila_venta["id_ven"]));
					                        }
					                    }
					                    $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
					                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
				                    }

								}
			                    
			                }
			            }
			        }
			    }
			}


			// print_r($arreglo_vendedor);
			// die();


			//------------ SUPERVISOR ------------
			$consulta = 
			    "
			    SELECT
			        usu.id_usu,
			        con_usu.id_con
			    FROM
			        usuario_usuario AS usu
			        INNER JOIN vendedor_supervisor_vendedor AS sup_ven ON sup_ven.id_usu = usu.id_usu
			        INNER JOIN usuario_condominio_usuario AS con_usu ON con_usu.id_usu = usu.id_usu
			        
			    WHERE
			        usu.id_per = ? AND
			        con_usu.id_con = ".$condo." AND
			        sup_ven.id_vend IN (".implode(',',$arreglo_vendedor).") 
			    GROUP BY 
			        usu.id_usu,
			        con_usu.id_con
			    ";
			$conexion->consulta_form($consulta,array(5));
			$fila_consulta = $conexion->extraer_registro();
			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        $acumulado_venta = 0;
			        $consulta = 
			            "
			            SELECT
			                id_vend
			            FROM
			                vendedor_supervisor_vendedor
			            WHERE
			                id_usu = ?
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_usu']));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_detalle) {
			                $acumulado_venta = $acumulado_venta + $arreglo_vendedor_promesa[$fila_detalle['id_vend']][$fila['id_con']];
			            }
			        }
			        
			        
			        if($acumulado_venta > 0){
			            $consulta = 
			                "
			                SELECT
			                    desde_bon,
			                    hasta_bon,
			                    monto_bon,
			                    nombre_bon,
			                    id_con
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    desde_bon <= ? AND
			                    hasta_bon >= ? AND
			                    id_tip_bon = 2 AND
			                    id_con = ? AND
			                    id_cat_bon = ?
			                ";
			            $conexion->consulta_form($consulta,array($acumulado_venta,$acumulado_venta,$fila['id_con'],1));
			            $cantidad_bono = $conexion->total();
			            if($cantidad_bono > 0){
			            	$fila_consulta_detalle = $conexion->extraer_registro();
				            if(is_array($fila_consulta_detalle)){
					            foreach ($fila_consulta_detalle as $fila_bono) {
						            // $fila_bono = $conexion->extraer_registro_unico();
						            $monto_bono_rango = 0;
						            $monto_bono_rango_plata = 0;
						            // if($cantidad_bono > 0){
						            $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
						            $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_bono["nombre_bon"],$fila_bono["desde_bon"],$fila_bono["hasta_bon"],$fila_bono["monto_bon"]));
						        }
						    }
						}
			        }

			        //---- BONO CATEGORIA FECHA SIN MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 2 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],2));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $monto_bono_rango = 0;
			                $monto_bono_rango_plata = 0;
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven 
			                    FROM
			                        vendedor_vendedor AS vend
			                        INNER JOIN vendedor_supervisor_vendedor AS sup ON sup.id_vend = vend.id_vend
			                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
			                    WHERE
			                        tor.id_con = ? AND
			                        sup.id_usu = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        ) AND NOT EXISTS
					                (
					                SELECT
					                    des_ven.id_ven
					                FROM
					                    venta_desestimiento_venta AS des_ven
					                WHERE
					                    des_ven.id_ven = ven.id_ven
					                )
			                    GROUP BY 
			                        ven.id_ven 
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],2,$fila_venta["id_ven"]));
			                        }
			                    }
								
								$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }


			        //---- BONO CATEGORIA FECHA CON MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 2 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],3));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $monto_bono_rango = 0;
			                $monto_bono_rango_plata = 0;
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven 
			                    FROM
			                        vendedor_vendedor AS vend
			                        INNER JOIN vendedor_supervisor_vendedor AS sup ON sup.id_vend = vend.id_vend
			                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
			                    WHERE
			                        tor.id_con = ? AND
			                        sup.id_usu = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND
			                        viv.id_mod = ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        ) AND NOT EXISTS
					                (
					                SELECT
					                    des_ven.id_ven
					                FROM
					                    venta_desestimiento_venta AS des_ven
					                WHERE
					                    des_ven.id_ven = ven.id_ven
					                )
			                    GROUP BY 
			                        ven.id_ven
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_mod"],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],3,$fila_venta["id_ven"]));
			                        }
			                    }

			                    $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }

			        //---- BONO TÉRMINO VENTAS 
			        $no_termino = 0;

					// preguntar si estoy en el mes de cierre venta
					$consulta_termino = 
			            "
			            SELECT
			                valor_par
			            FROM
			                parametro_parametro
			            WHERE
			                valor2_par = ? AND
			                id_con = ? 
			            ";
			        
			        $conexion->consulta_form($consulta_termino,array(26,$fila['id_con']));
			        $fila_term = $conexion->extraer_registro_unico();
			        $fecha_termino = $fila_term['valor_par'];
			        if ($fecha_termino<>'' & $fecha_termino<>null) {
			        	$mes_termino = date("n",strtotime($fecha_termino));
			        } else {
			        	$no_termino = 1;
			        }

			        if ($mes_termino<>$mes) {
			        	$no_termino = 1;
			        }

					if ($no_termino==0) {
					// echo $mes_termino." - ".$mes;
						$consulta = 
			                "
			                SELECT
			                    *
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    id_tip_bon = 2 AND
			                    id_con = ? AND
			                    id_cat_bon = ? AND
			                    MONTH(fecha_hasta_bon) = '".$mes."' AND
			                    YEAR(fecha_hasta_bon) = '".$anio."'
			                ";
			            $conexion->consulta_form($consulta,array($fila['id_con'],4));

			            $fila_consulta_detalle = $conexion->extraer_registro();
			            if(is_array($fila_consulta_detalle)){
			                foreach ($fila_consulta_detalle as $fila_det) {
			                	// echo $mes_termino." - ".$mes;
								
								if ($fila_det["desde_bon"]==0 && $fila_det["hasta_bon"]==0) {
									// cuando no hay rango, se paga directo
									$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                		$conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));

								} else {
									$monto_bono_rango = 0;
			                		$monto_bono_rango_plata = 0;
			                        $consulta = 
					                    "
					                    SELECT
					                        ven.id_ven 
					                    FROM
					                        vendedor_vendedor AS vend
					                        INNER JOIN vendedor_supervisor_vendedor AS sup ON sup.id_vend = vend.id_vend
					                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
					                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
					                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
					                    WHERE
					                        tor.id_con = ? AND
					                        sup.id_usu = ? AND
					                        ven.fecha_promesa_ven >= ? AND
					                        ven.fecha_promesa_ven <= ? AND NOT
					                        ven.id_est_ven = 3 AND NOT EXISTS
					                        (
					                        SELECT
					                            b.id_ven_bon
					                        FROM
					                            bono_venta_bono AS b
					                        WHERE
					                            b.id_ven = ven.id_ven AND
					                            b.id_bon = ?
					                        ) AND NOT EXISTS
							                (
							                SELECT
							                    des_ven.id_ven
							                FROM
							                    venta_desestimiento_venta AS des_ven
							                WHERE
							                    des_ven.id_ven = ven.id_ven
							                )
					                    GROUP BY 
					                        ven.id_ven 
					                    ";
			                        $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_bon"]));
			                        $cantidad_bono = $conexion->total();
			                        if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                            $fila_consulta_venta = $conexion->extraer_registro();
					                    if(is_array($fila_consulta_venta)){
					                        foreach ($fila_consulta_venta as $fila_venta) {
					                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
					                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],4,$fila_venta["id_ven"]));
					                        }
					                    }
										$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
					                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));

			                        }

								}
			                    
			                }
			            }
			        }
			    }
			}
			// print_r($arreglo_vendedor_promesa);
			//------------ JEFE DE VENTAS ------------
			$consulta = 
			    "
			    SELECT
			        usu.id_usu,
			        con_usu.id_con
			    FROM
			        usuario_usuario AS usu
			        INNER JOIN vendedor_jefe_vendedor AS sup_ven ON sup_ven.id_usu = usu.id_usu
			        INNER JOIN usuario_condominio_usuario AS con_usu ON con_usu.id_usu = usu.id_usu
			    WHERE
			        usu.id_per = ? AND
			        con_usu.id_con = ".$condo." AND
			        sup_ven.id_vend IN (".implode(',',$arreglo_vendedor).") 
			    GROUP BY 
			        usu.id_usu,
			        con_usu.id_con
			    ";
			$conexion->consulta_form($consulta,array(2));
			$fila_consulta = $conexion->extraer_registro();
			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        $acumulado_venta = 0;
			        // $consulta = 
			        //     "
			        //     SELECT
			        //         id_vend
			        //     FROM
			        //         vendedor_jefe_vendedor
			        //     WHERE
			        //         id_usu = ?
			        //     ";
					// echo $consulta;
			        // $conexion->consulta_form($consulta,array($fila['id_usu']));
			        // $fila_consulta_detalle = $conexion->extraer_registro();
			        // if(is_array($fila_consulta_detalle)){
			        //     foreach ($fila_consulta_detalle as $fila_detalle) {
			        //         $acumulado_venta = $acumulado_venta + $arreglo_vendedor_promesa[$fila_detalle['id_vend']][$fila['id_con']];
			        //     }
			        // }
			        

			        
			        if($acumulado_venta > 0){
			            $consulta = 
			                "
			                SELECT
			                    desde_bon,
			                    hasta_bon,
			                    monto_bon,
			                    nombre_bon,
			                    id_con
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    desde_bon <= ? AND
			                    hasta_bon >= ? AND
			                    id_tip_bon = 1 AND
			                    id_con = ? AND
			                    id_cat_bon = ?
			                ";
			            $conexion->consulta_form($consulta,array($acumulado_venta,$acumulado_venta,$fila['id_con'],1));
			            $cantidad_bono = $conexion->total();
			            if($cantidad_bono > 0){

			            	$fila_consulta_detalle = $conexion->extraer_registro();
				            if(is_array($fila_consulta_detalle)){
					            foreach ($fila_consulta_detalle as $fila_bono) {
						            // $fila_bono = $conexion->extraer_registro_unico();
						            $monto_bono_rango = 0;
						            $monto_bono_rango_plata = 0;
						            // if($cantidad_bono > 0){
						            $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
						            $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_bono["nombre_bon"],$fila_bono["desde_bon"],$fila_bono["hasta_bon"],$fila_bono["monto_bon"]));
						        }
						    }
						}
			        }

			        //---- BONO CATEGORIA FECHA SIN MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 1 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],2));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $monto_bono_rango = 0;
			                $monto_bono_rango_plata = 0;
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven 
			                    FROM
			                        vendedor_vendedor AS vend
			                        INNER JOIN vendedor_jefe_vendedor AS sup ON sup.id_vend = vend.id_vend
			                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
			                    WHERE
			                        tor.id_con = ? AND
			                        sup.id_usu = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        ) AND NOT EXISTS
					                (
					                SELECT
					                    des_ven.id_ven
					                FROM
					                    venta_desestimiento_venta AS des_ven
					                WHERE
					                    des_ven.id_ven = ven.id_ven
					                )
			                    GROUP BY 
			                        ven.id_ven 
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],2,$fila_venta["id_ven"]));
			                        }
			                    }
								$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }


			        //---- BONO CATEGORIA FECHA CON MODELO 
			        $consulta = 
			            "
			            SELECT
			                *
			            FROM
			                bono_bono
			            WHERE
			                id_est_bon = 1 AND
			                id_tip_bon = 1 AND
			                id_con = ? AND
			                id_cat_bon = ? AND
			                MONTH(fecha_hasta_bon) = '".$mes."' AND
			                YEAR(fecha_hasta_bon) = '".$anio."'
			            ";
			        $conexion->consulta_form($consulta,array($fila['id_con'],3));
			        $fila_consulta_detalle = $conexion->extraer_registro();
			        if(is_array($fila_consulta_detalle)){
			            foreach ($fila_consulta_detalle as $fila_det) {
			                $monto_bono_rango = 0;
			                $monto_bono_rango_plata = 0;
			                $consulta = 
			                    "
			                    SELECT
			                        ven.id_ven 
			                    FROM
			                        vendedor_vendedor AS vend
			                        INNER JOIN vendedor_jefe_vendedor AS sup ON sup.id_vend = vend.id_vend
			                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
			                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
			                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
			                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
			                    WHERE
			                        tor.id_con = ? AND
			                        sup.id_usu = ? AND
			                        ven.fecha_promesa_ven >= ? AND
			                        ven.fecha_promesa_ven <= ? AND
			                        viv.id_mod = ? AND NOT
			                        ven.id_est_ven = 3 AND NOT EXISTS
			                        (
			                        SELECT
			                            b.id_ven_bon
			                        FROM
			                            bono_venta_bono AS b
			                        WHERE
			                            b.id_ven = ven.id_ven AND
			                            b.id_bon = ?
			                        )
			                    GROUP BY 
			                        ven.id_ven
			                    ";
			                $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_mod"],$fila_det["id_bon"]));
			                $cantidad_bono = $conexion->total();
			                if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                    
			                    $fila_consulta_venta = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_venta)){
			                        foreach ($fila_consulta_venta as $fila_venta) {
			                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
			                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],3,$fila_venta["id_ven"]));
			                        }
			                    }

			                    $consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                }
			            }
			        }

			        //---- BONO TÉRMINO VENTAS 
			        $no_termino = 0;

					// preguntar si estoy en el mes de cierre venta
					$consulta_termino = 
			            "
			            SELECT
			                valor_par
			            FROM
			                parametro_parametro
			            WHERE
			                valor2_par = ? AND
			                id_con = ? 
			            ";
			        
			        $conexion->consulta_form($consulta_termino,array(26,$fila['id_con']));
			        $fila_term = $conexion->extraer_registro_unico();
			        $fecha_termino = $fila_term['valor_par'];
			        if ($fecha_termino<>'' & $fecha_termino<>null) {
			        	$mes_termino = date("n",strtotime($fecha_termino));
			        } else {
			        	$no_termino = 1;
			        }

			        if ($mes_termino<>$mes) {
			        	$no_termino = 1;
			        }

					if ($no_termino==0) {
					// echo $mes_termino." - ".$mes;
						$consulta = 
			                "
			                SELECT
			                    *
			                FROM
			                    bono_bono
			                WHERE
			                    id_est_bon = 1 AND
			                    id_tip_bon = 1 AND
			                    id_con = ? AND
			                    id_cat_bon = ? AND
			                    MONTH(fecha_hasta_bon) = '".$mes."' AND
			                    YEAR(fecha_hasta_bon) = '".$anio."'
			                ";
			            $conexion->consulta_form($consulta,array($fila['id_con'],4));
			            $fila_consulta_detalle = $conexion->extraer_registro();
			            if(is_array($fila_consulta_detalle)){
			                foreach ($fila_consulta_detalle as $fila_det) {
			                	// echo $mes_termino." - ".$mes;
								
								if ($fila_det["desde_bon"]==0 && $fila_det["hasta_bon"]==0) {
									// cuando no hay rango, se paga directo
									$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
			                		$conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));


								} else {
									$monto_bono_rango = 0;
			                		$monto_bono_rango_plata = 0;
			                        $consulta = 
					                    "
					                    SELECT
					                        ven.id_ven 
					                    FROM
					                        vendedor_vendedor AS vend
					                        INNER JOIN vendedor_jefe_vendedor AS sup ON sup.id_vend = vend.id_vend
					                        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
					                        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
					                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
					                        INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
					                    WHERE
					                        tor.id_con = ? AND
					                        sup.id_usu = ? AND
					                        ven.fecha_promesa_ven >= ? AND
					                        ven.fecha_promesa_ven <= ? AND NOT
					                        ven.id_est_ven = 3 AND NOT EXISTS
					                        (
					                        SELECT
					                            b.id_ven_bon
					                        FROM
					                            bono_venta_bono AS b
					                        WHERE
					                            b.id_ven = ven.id_ven AND
					                            b.id_bon = ?
					                        )
					                    GROUP BY 
					                        ven.id_ven 
					                    ";
					                $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_usu'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_bon"]));
			                        $cantidad_bono = $conexion->total();
			                        if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
			                            $fila_consulta_venta = $conexion->extraer_registro();
					                    if(is_array($fila_consulta_venta)){
					                        foreach ($fila_consulta_venta as $fila_venta) {
					                            $consulta = "INSERT INTO bono_venta_bono VALUES(?,?,?,?)";
					                            $conexion->consulta_form($consulta,array(0,$fila_det["id_bon"],4,$fila_venta["id_ven"]));
					                        }
					                    }
										$consulta = "INSERT INTO cierre_bono_cierre VALUES(?,?,?,?,?,?,?,?)";
					                    $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_con"],$fila["id_usu"],$fila_det["nombre_bon"],$fila_det["desde_bon"],$fila_det["hasta_bon"],$fila_det["monto_bon"]));
			                        }

								}
			                    
			                }
			            }
			        }

			    }
			}

			$consulta = 
			    "
			    SELECT
			        ven.id_ven,
			        his.id_est_ven,
			        ven.cotizacion_ven
			    FROM
			        venta_venta AS ven
			        INNER JOIN vivienda_vivienda AS viv ON ven.id_viv = viv.id_viv
			        INNER JOIN venta_estado_historial_venta AS his ON ven.id_ven = his.id_ven
			    WHERE
			        his.id_est_ven IN (4) AND
			        viv.id_tor = ".$condo." AND
			        ven.fecha_promesa_ven > '".$fecha_desde_consulta."' AND
			        ven.fecha_promesa_ven <= '".$fecha_hasta_consulta."' AND 
			        NOT EXISTS
                    (
                    SELECT
                        b.id_ven
                    FROM
                        cierre_venta_cierre AS b
                    WHERE
                        b.id_ven = ven.id_ven AND
                        b.id_est_ven = his.id_est_ven
                    ) AND NOT EXISTS
			        (
			        SELECT
			            des_ven.id_ven
			        FROM
			            venta_desestimiento_venta AS des_ven
			        WHERE
			            des_ven.id_ven = ven.id_ven
			        )
			        
			    ";
			$conexion->consulta($consulta);
			$fila_consulta = $conexion->extraer_registro();

			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        $consulta = "INSERT INTO cierre_venta_cierre VALUES(?,?,?,?)";
			        $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_ven"],$fila["id_est_ven"]));
			        $consulta = "UPDATE venta_venta SET id_est_ven = 5 WHERE id_ven = ?";
			        $conexion->consulta_form($consulta,array($fila["id_ven"]));

			        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
			        $conexion->consulta_form($consulta,array(0,$fila["id_ven"],5));

			        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
			    	$conexion->consulta_form($consulta,array(5,$fila["cotizacion_ven"]));
			        
			    }
			}



			$consulta = 
			    "
			    SELECT
			        ven.id_ven,
			        his.id_est_ven,
			        ven.cotizacion_ven
			    FROM
			        venta_venta AS ven
			        INNER JOIN vivienda_vivienda AS viv ON ven.id_viv = viv.id_viv
			        INNER JOIN venta_estado_historial_venta AS his ON ven.id_ven = his.id_ven
			    WHERE
			        his.id_est_ven IN (6) AND
			        viv.id_tor = ".$condo." AND
			        ven.fecha_escritura_ven > '".$fecha_desde_consulta."' AND
			        ven.fecha_escritura_ven <= '".$fecha_hasta_consulta."' AND 
			        NOT EXISTS
                    (
                    SELECT
                        b.id_ven
                    FROM
                        cierre_venta_cierre AS b
                    WHERE
                        b.id_ven = ven.id_ven AND
                        b.id_est_ven = his.id_est_ven
                    ) AND NOT EXISTS
			        (
			        SELECT
			            des_ven.id_ven
			        FROM
			            venta_desestimiento_venta AS des_ven
			        WHERE
			            des_ven.id_ven = ven.id_ven
			        )
			        
			    ";
			$conexion->consulta($consulta);
			$fila_consulta = $conexion->extraer_registro();

			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        $consulta = "INSERT INTO cierre_venta_cierre VALUES(?,?,?,?)";
			        $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_ven"],$fila["id_est_ven"]));
			        $consulta = "UPDATE venta_venta SET id_est_ven = 7 WHERE id_ven = ?";
			        $conexion->consulta_form($consulta,array($fila["id_ven"]));

			        $consulta = "INSERT INTO venta_estado_historial_venta VALUES(?,?,?)";
			        $conexion->consulta_form($consulta,array(0,$fila["id_ven"],7));

			        $consulta = "UPDATE cotizacion_cotizacion SET id_est_cot = ? WHERE id_cot = ?";    
			        $conexion->consulta_form($consulta,array(7,$fila["cotizacion_ven"]));
			    }
			}

			$consulta = 
			    "
			    SELECT
			        ven.id_ven,
			        ven.id_est_ven
			    FROM
			        venta_venta AS ven
			        INNER JOIN vivienda_vivienda AS viv ON ven.id_viv = viv.id_viv
			        INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
			    WHERE
			        ven.id_est_ven = 3 AND
			        des_ven.id_tip_des = 1 AND 
			        viv.id_tor = ".$condo." AND
			        des_ven.fecha_des_ven > '".$fecha_desde_consulta."' AND
			        des_ven.fecha_des_ven <= '".$fecha_hasta_consulta."' AND NOT 
			        EXISTS(
			            SELECT 
			                ven_cie.id_ven_cie
			            FROM 
			                cierre_venta_cierre AS ven_cie
			            WHERE
			                ven_cie.id_ven = ven.id_ven AND
			                ven_cie.id_est_ven = 3
			        ) AND  
			        EXISTS(
			            SELECT 
			                ven_cie.id_ven_cie
			            FROM 
			                cierre_venta_cierre AS ven_cie
			            WHERE
			                ven_cie.id_ven = ven.id_ven AND
			                (ven_cie.id_est_ven = 4 OR ven_cie.id_est_ven = 6)
			        )
			        
			        
			    ";
			$conexion->consulta($consulta);
			$fila_consulta = $conexion->extraer_registro();
			$contador_promesa = 0;
			if(is_array($fila_consulta)){
			    foreach ($fila_consulta as $fila) {
			        $consulta = "INSERT INTO cierre_venta_cierre VALUES(?,?,?,?)";
			        $conexion->consulta_form($consulta,array(0,$ultimo_id,$fila["id_ven"],$fila["id_est_ven"]));
			    }
			}
		}

		// bonos jefe Operaciones
		$consulta_jefe_op = 
		    "
		    SELECT
		        id_usu
		    FROM
		        usuario_usuario
		    WHERE
		        id_per = 7 AND
		        id_est_usu = 1 
		    ";
		$conexion->consulta($consulta_jefe_op);
		$fila_jefeop = $conexion->extraer_registro_unico();
		$id_usu_jefop = $fila_jefeop['id_usu'];


		$consulta_jo = 
            "
            SELECT
                ven.monto_ven,
                viv.id_mod,
                ven.id_ven,
                ven_liq.fecha_liq_ven,
                ven.fecha_escritura_ven,
                ven.id_for_pag,
                ven_cam.ciudad_notaria_ven
            FROM
                venta_venta AS ven
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                INNER JOIN venta_liquidado_venta AS ven_liq ON ven_liq.id_ven = ven.id_ven
                LEFT JOIN venta_campo_venta AS ven_cam ON ven_cam.id_ven = ven.id_ven
            WHERE
                tor.id_con = ? AND
                (MONTH(ven_liq.fecha_liq_ven) = ".$mes_desde_liq." OR MONTH(ven_liq.fecha_liq_ven) = ".$mes_hasta_liq.") AND
                YEAR(ven_liq.fecha_liq_ven) = ".$_SESSION["sesion_anio_liquidacion_panel"]." AND 
                ven_liq.fecha_liq_ven > '".$fecha_desde_consulta."' AND 
                ven_liq.fecha_liq_ven < '".$fecha_hasta_consulta."' AND 
                ven_liq.monto_liq_uf_ven <> '' AND
                NOT EXISTS
                (
                SELECT
                    cie_bon_ven.id_ven
                FROM
                    cierre_bono_cierre_venta AS cie_bon_ven
                WHERE
                    cie_bon_ven.id_ven = ven.id_ven AND 
                    cie_bon_ven.id_usu = ".$id_usu_jefop."
                )
                
            ";

        $conexion->consulta_form($consulta_jo,array($condo));
        $fila_consulta_joperaciones = $conexion->extraer_registro();
        if(is_array($fila_consulta_joperaciones)){
            foreach ($fila_consulta_joperaciones as $fila_jo) {
            	$id_ven = $fila_jo["id_ven"];
            	$fecha_liq_ven = $fila_jo["fecha_liq_ven"];
            	$fecha_escritura_ven = $fila_jo["fecha_escritura_ven"];
            	$id_for_pag = $fila_jo["id_for_pag"];

            	$ciudad_notaria_ven = $fila_jo["ciudad_notaria_ven"];

            	// $fecha_escritura = date("d/m/Y",strtotime($fecha_escritura_ven));
            	// $fecha_liquidacion = date("d/m/Y",strtotime($fecha_liq_ven));

            	$rango_dias= (strtotime($fecha_liq_ven)-strtotime($fecha_escritura_ven))/86400;
				$rango_dias = abs($rango_dias); 
				$rango_dias = floor($rango_dias);

				$rango_en_semanas = $rango_dias / 7;
				$rango_en_semanas = round($rango_en_semanas);

				// echo $condo." - ".$id_ven." - ".$fecha_liq_ven." - ".$fecha_escritura_ven." - ".$rango_dias." - ".$rango_en_semanas."<br>";

            	// segun forma de pago va a buscar ciudad
            	if ($id_for_pag==1) {
		        	$ciudad_cre = $ciudad_notaria_ven;
		        	// $ciudad_cre="2";
		        	if($ciudad_cre==1) {
		        		// si es Serena
		        		$bono_cat = 5;
		        		$consulta_bono = 
	                        "
	                        SELECT
	                            nombre_bon,
	                            monto_bon,
	                            desde_bon,
			                    hasta_bon,
			                    id_bon
	                        FROM
	                            bono_bono
	                        WHERE
	                            id_est_bon = 1 AND
	                            id_tip_bon = 4 AND
	                            id_con = ? AND
	                            id_cat_bon = ? AND
	                            desde_bon <= ? AND
		                        hasta_bon >= ?
	                        ";
	                    $conexion->consulta_form($consulta_bono,array($condo,$bono_cat,$rango_en_semanas,$rango_en_semanas));
	                    $tiene_bono = $conexion->total();
	                    if ($tiene_bono>0) {
	                    	$fila_conbono = $conexion->extraer_registro_unico();
	                    	$bono_uf = $fila_conbono['monto_bon'];
	                    	
	                    	$consulta = "INSERT INTO cierre_bono_cierre_venta VALUES(?,?,?,?,?,?,?,?,?,?)";
					        $conexion->consulta_form($consulta,array(0,$ultimo_id,$condo,$id_usu_jefop,$fila_conbono["nombre_bon"],$fila_conbono["desde_bon"],$fila_conbono["hasta_bon"],$fila_conbono["monto_bon"],$id_ven,$fila_conbono["id_bon"]));

	                    }
	                    
		        	} else if($ciudad_cre==2) {
		        		// Si es Santiago
		        		$bono_cat = 6;
		        		$consulta_bono = 
	                        "
	                        SELECT
	                            nombre_bon,
	                            monto_bon,
	                            desde_bon,
			                    hasta_bon,
		                    	id_bon
	                        FROM
	                            bono_bono
	                        WHERE
	                            id_est_bon = 1 AND
	                            id_tip_bon = 4 AND
	                            id_con = ? AND
	                            id_cat_bon = ? AND
	                            desde_bon <= ? AND
		                        hasta_bon >= ?
	                        ";
	                    $conexion->consulta_form($consulta_bono,array($condo,$bono_cat,$rango_en_semanas,$rango_en_semanas));
	                    $tiene_bono = $conexion->total();
	                    if ($tiene_bono>0) {
	                    	$fila_conbono = $conexion->extraer_registro_unico();
	                    	$bono_uf = $fila_conbono['monto_bon'];

	                    	$consulta = "INSERT INTO cierre_bono_cierre_venta VALUES(?,?,?,?,?,?,?,?,?,?)";
					        $conexion->consulta_form($consulta,array(0,$ultimo_id,$condo,$id_usu_jefop,$fila_conbono["nombre_bon"],$fila_conbono["desde_bon"],$fila_conbono["hasta_bon"],$fila_conbono["monto_bon"],$id_ven,$fila_conbono["id_bon"]));

	                    }

		        	}
		            
            	} else {
            		// si es contado
            		$bono_cat = 7;
            		$consulta_bono = 
                        "
                        SELECT
                            nombre_bon,
                            monto_bon,
                            desde_bon,
		                    hasta_bon,
		                    id_bon
                        FROM
                            bono_bono
                        WHERE
                            id_est_bon = 1 AND
                            id_tip_bon = 4 AND
                            id_con = ? AND
                            id_cat_bon = ? AND
                            desde_bon <= ? AND
	                        hasta_bon >= ?
                        ";
                    $conexion->consulta_form($consulta_bono,array($condo,$bono_cat,$rango_en_semanas,$rango_en_semanas));
                    $tiene_bono = $conexion->total();
                    if ($tiene_bono>0) {
                    	$fila_conbono = $conexion->extraer_registro_unico();
                    	$bono_uf = $fila_conbono['monto_bon'];

                    	$consulta = "INSERT INTO cierre_bono_cierre_venta VALUES(?,?,?,?,?,?,?,?,?,?)";
					    $conexion->consulta_form($consulta,array(0,$ultimo_id,$condo,$id_usu_jefop,$fila_conbono["nombre_bon"],$fila_conbono["desde_bon"],$fila_conbono["hasta_bon"],$fila_conbono["monto_bon"],$id_ven,$fila_conbono["id_bon"]));

                    }
            	}
            	

            }
        }

		// proceso de loading de espera en construcción 
		// $conexion->consulta('SELECT * FROM process WHERE id_process = 1');
		// 				$row_process = $conexion->extraer_registro();
		// 			    // var_dump($row_process);
		// 				$percentage = round(($row_process[0]['executed'] * 100) / $row_process[0]['total'], 2);
					
		// 				$date_add = new DateTime($row_process[0]['date_add']);
		// 				$date_upd = new DateTime($row_process[0]['date_upd']);
		// 				$diff = $date_add->diff($date_upd);
					
		// 				$execute_time = '';
					
		// 				if ($diff->days > 0) {
		// 					$execute_time .= $diff->days.' dias';
		// 				}
		// 				if ($diff->h > 0) {
		// 					$execute_time .= ' '.$diff->h.' horas';
		// 				}
		// 				if ($diff->i > 0) {
		// 					$execute_time .= ' '.$diff->i.' minutos';
		// 				}
					
		// 				if ($diff->s > 1) {
		// 					$execute_time .= ' '.$diff->s.' segundos';
		// 				} else {
		// 					$execute_time .= ' 1 segundo';
		// 				}
					
		// 				$update_process = 'UPDATE process SET percentage = '.$percentage.', execute_time = "'.(string)$execute_time.'" WHERE id_process = 1';
		// 				$conexion->consulta($update_process);
					
		// 				$row = array(
		// 					'executed' => $row_process[0]['executed'],
		// 					'total' => $row_process[0]['total'],
		// 					'percentage' => round($percentage, 0),
		// 					'execute_time' => $execute_time
		// 				);
		// 				die(json_encode($row));
	}

  

		$bonosc1 = (count($_SESSION['c1'])>0) ? $_SESSION['c1'] : '';	
		$bonos = (count($_SESSION['c2'])>0) ? $_SESSION['c2'] : '';
		$bonoc3 = (count($_SESSION['c3'])>0) ? $_SESSION['c3'] : '';	
		$c1 = array();
		$c2 = array();
		$c3 = array();
		$contenedorc1 = array();
		$contenedor = array();
		$contenedorc3 = array();
		$countc1 = 0;
		$count = 0;
		$countc3 = 0;

		// guardado de bono C2
		if($bonos != ''){			
			foreach ($bonos as $k => $v) 
			{
				foreach ($v as $c => $d) {
					array_push($c2, $d);						
				}
				if(isset($c2[$count])){
					$contenedor[$count]=$c2;
					unset($c2);
					$c2 = array();
					$count+=1;
				}
			}
			if(isset($contenedor[0])){
				$consulta = "INSERT INTO bonos(nombre,porcentaje,monto,id_vendedor,id_cierre,mes) VALUES(?,?,?,?,?,?)";
				for ($i=0; $i < count($contenedor); $i++) { $conexion->consulta_form($consulta,array($contenedor[$i][0] , $contenedor[$i][1], $contenedor[$i][2], $contenedor[$i][3], $ultimo_id, $contenedor[$i][4]));}
			}
		}
		
    	// fin guardado de bono C2

		// guardado de bono c3
		if($bonoc3 != ''){
			foreach ($bonoc3 as $k => $v) 
			{
				foreach ($v as $c => $d) {
					array_push($c3, $d);						
				}
				if(isset($c3[$countc3])){
					$contenedorc3[$countc3]=$c3;
					unset($c3);
					$c3 = array();
					$countc3+=1;
				}
			}
			if(isset($contenedorc3[0])){
				$consultac3 = "INSERT INTO bonos(nombre,porcentaje,monto,id_vendedor,id_cierre,mes) VALUES(?,?,?,?,?,?)";
				for ($i=0; $i < count($contenedorc3); $i++) { $conexion->consulta_form($consultac3,array($contenedorc3[$i][0] , $contenedorc3[$i][1], $contenedorc3[$i][2], $contenedorc3[$i][3], $ultimo_id, $contenedorc3[$i][4]));}
			}
		}

		// fin guardado de bono C3

		// guardado de bono c1	
		if($bonosc1 != ''){
			foreach ($bonosc1 as $k => $v) 
			{
				foreach ($v as $c => $d) {				
						array_push($c1, $d);															
				}
				if(isset($c1[$countc1])){
					$contenedorc1[$countc1]=$c1;
					unset($c1);
					$c1 = array();
					$countc1+=1;
				}								
			}	
			if(isset($contenedorc1[0])){
				$consultac1 = "INSERT INTO bonos(nombre,porcentaje,monto,id_vendedor,id_cierre,mes) VALUES(?,?,?,?,?,?)";
			    for ($i=0; $i < count($contenedorc1); $i++) { $conexion->consulta_form($consultac1,array($contenedorc1[$i][0] , $contenedorc1[$i][1], $contenedorc1[$i][2], $contenedorc1[$i][3], $ultimo_id, $contenedorc1[$i][4]));}
			}		
			
		}
		// fin guardado de bono C1

		

				
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();

?>