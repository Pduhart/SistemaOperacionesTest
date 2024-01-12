<?php 
// sesiones
session_start();
// archivo de configuracion y variables importantes 
require "../../config.php"; 
// archivo de base de datos
include _INCLUDE."class/conexion.php";
// instanciación de objeto de base de datos
$conexion = new conexion();
// SUELDO BASE
$sueldoBase = 400000;

$cont = 0;
// fechas desde y hasta para calcular cierre y mes y año
$fecha_desde = $_POST["fecha_desde"];
$fecha_hasta = $_POST["fecha_hasta"];
$mes = $_POST["mes"];
$anio = $_POST["anio"];
$vendedores = array();
$C1 = array();
$C2 = array();
$C3 = array();
$contendorBonosC1 = array();
$contendorBonos = array();
$contendorBonosC3 = array();
$condominio = (isset($_POST["condominio"]))?$_POST["condominio"]:0;
$_SESSION["sesion_fecha_desde_liquidacion_panel"] = $fecha_desde;
$_SESSION["sesion_fecha_hasta_liquidacion_panel"] = $fecha_hasta;
$_SESSION["sesion_mes_liquidacion_panel"] = $mes;
$_SESSION["sesion_anio_liquidacion_panel"] = $anio;
// $_SESSION["sesion_condominio_liquidacion_panel"] = $condominio;


$fecha_uf = date("Y-m-d",strtotime($fecha_hasta));

$mes_desde_liq = date("m",strtotime($fecha_desde));
$mes_hasta_liq = date("m",strtotime($fecha_hasta));
function formateoFecha($fecha){
    // ejemplo de entrada 26-03-2022
    // ejemplo de salida  2022-05-25
    $fecha_init = explode("-",$fecha);
    return $fecha_init[2].'-'.$fecha_init[1].'-'.$fecha_init[0];
  }
function get_uf_disistimiento($id_ven){
	$conexion = new conexion();
	// echo $id_ven;
	$consulta = 
	    "
	    SELECT
	        id_cie
	    FROM
	        cierre_venta_cierre
	    WHERE
	        id_ven = ?
	    ";
	$conexion->consulta_form($consulta,array($id_ven));
	$fila = $conexion->extraer_registro_unico();
	$id_cie = $fila["id_cie"];

	$consulta = 
	    "
	    SELECT
	        fecha_hasta_cie
	    FROM
	        cierre_cierre
	    WHERE
	        id_cie = ?
	    ";
	$conexion->consulta_form($consulta,array($id_cie));
	$fila = $conexion->extraer_registro_unico();
	$fecha_hasta_cie = $fila["fecha_hasta_cie"];

	$consulta = 
	    "
	    SELECT
	        valor_uf
	    FROM
	        uf_uf
	    WHERE
	        fecha_uf = ?
	    ";
	$conexion->consulta_form($consulta,array($fecha_hasta_cie));
	$fila = $conexion->extraer_registro_unico();
	$valor_uf_desistimiento = utf8_encode($fila['valor_uf']);

	return $valor_uf_desistimiento;

}

$consulta = 
    "
    SELECT
        valor_uf
    FROM
        uf_uf
    WHERE
        fecha_uf = ?
    ";
$conexion->consulta_form($consulta,array($fecha_uf));
$cantidad_uf = $conexion->total();
$fila = $conexion->extraer_registro_unico();
$valor_uf = utf8_encode($fila['valor_uf']);
if($cantidad_uf == 0){
    ?>
    <div class="col-xs-12">
        <h4>
            UF no se encuentra cargada en la fecha de cierre seleccionada
        </h4>
    </div>
    <?php
    exit();
}

$UF_LIQUIDACION = $valor_uf;
?>
<div class="col-xs-12">
    <h2 class="page-header">
        <i class="fa fa-cog"></i> Liquidación Ventas
        <small class="pull-right">Período: <?php echo $mes;?>/<?php echo $anio;?> | Desde: <?php echo date("d-m-Y",strtotime($fecha_desde));?> Hasta: <?php echo date("d-m-Y",strtotime($fecha_hasta));?> | Valor UF: <span style="color:#3c8dbc; font-weight: bold; cursor:pointer;">$<?php echo number_format($valor_uf, 2, ',', '.');?></span> | Sueldo Base : <span style="color:#3c8dbc; font-weight: bold; cursor:pointer;">$<?php echo number_format($sueldoBase,0,',','.');?></span> </small>
    </h2>
</div>

<?php 
$consulta = 
    "
    SELECT
        id_cie
    FROM
        cierre_cierre
    WHERE
        id_mes = ? AND
        anio_cie = ? 
    ";
$conexion->consulta_form($consulta,array($mes,$anio));
$cantidad_cierre = $conexion->total();
if($cantidad_cierre > 0){
    ?>
    <div class="col-xs-12">
        <h4>
            Período se encuentra cerrado
        </h4>
    </div>
    <?php
    exit();
}
$arreglo_vendedor = array();

//--------- VENDEDOR ---------

$fecha_desde_ant = date('Y-m-d',strtotime ( '-1 day' , strtotime ($fecha_desde)));
$fecha_desde_consulta = date("Y-m-d 23:59:59",strtotime($fecha_desde_ant));
$fecha_hasta_consulta = date("Y-m-d 23:59:59",strtotime($fecha_hasta));
$arreglo_vendedores_operacion = array();

$consulta_vend_activos = 
    "
    SELECT
        id_vend
    FROM
        vendedor_vendedor
    WHERE
        id_est_vend = 1 and
		id_vend != 3
    ";
$conexion->consulta($consulta_vend_activos);
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        if(!in_array($fila["id_vend"],$arreglo_vendedor)){
            $arreglo_vendedores_operacion[$fila["id_vend"]] = $fila["id_vend"];
        }
    }
}

$consulta_condominios = 
    "
    SELECT
        id_con,
        nombre_con
    FROM
        condominio_condominio
    WHERE
        id_est_con = 1 AND
        id_con > 6
    ";
$conexion->consulta($consulta_condominios);
$cantidad_cond = $conexion->total();

$cantidad_venta_totales = 0;

$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila_cond) {

    	$condo = ($fila_cond['id_con'] != 4)?$fila_cond['id_con']:0;
    	$nombre_con = utf8_encode($fila_cond['nombre_con']);

    	$consulta = 
		    "
		    SELECT
		        vend.id_vend,
		        vend.nombre_vend,
		        vend.apellido_paterno_vend,
		        vend.apellido_materno_vend,
		        tor.id_con,
		        con.nombre_con,
		        cat_vend.id_cat_vend,
		        cat_vend.nombre_cat_vend
		    FROM
		        vendedor_vendedor AS vend
		        INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		        INNER JOIN vendedor_categoria_vendedor AS cat_vend ON cat_vend.id_cat_vend = vend.id_cat_vend
		        INNER JOIN venta_estado_historial_venta AS his ON his.id_ven = ven.id_ven
		    WHERE
		    	ven.fecha_promesa_ven <= '".$fecha_hasta_consulta."' AND
		    	con.id_con = ".$condo." AND
				vend.id_vend NOT IN(5,3,14) AND
		    	vend.id_est_vend = 1 AND 
		        his.id_est_ven IN (4,6) AND NOT EXISTS
		        (
		        SELECT
		            b.id_ven
		        FROM
		            cierre_venta_cierre AS b
		        WHERE
		            b.id_ven = ven.id_ven AND
		            b.id_est_ven = his.id_est_ven
		        )
		    GROUP BY 
		        vend.id_vend,
		        vend.nombre_vend,
		        vend.apellido_paterno_vend,
		        vend.apellido_materno_vend,
		        con.nombre_con,
		        cat_vend.id_cat_vend,
		        tor.id_con
		    ";			  
		$conexion->consulta($consulta);
		$cantidad_venta = $conexion->total();

		 //--------- VENDEDORES ---------

		if($cantidad_venta > 0){
			$cantidad_venta_totales++;
		    

		    $fila_consulta = $conexion->extraer_registro();
			
		    if(is_array($fila_consulta)){
					?>
					<div class="col-sm-12">
						<h4>Vendedores</h4>
					</div>
					<?php
		        foreach ($fila_consulta as $fila) {
		            if(!in_array($fila["id_vend"],$arreglo_vendedor)){
		                $arreglo_vendedor[$fila["id_vend"]] = $fila["id_vend"];
		            }
		            $consulta = 
		                "
		                SELECT
		                    valor2_par,
		                    valor_par
		                FROM
		                    parametro_parametro
		                WHERE
		                    id_con = ? AND
		                    valor2_par IN (1,2,3,8)
		                ";
		            $conexion->consulta_form($consulta,array($fila['id_con']));
		            $fila_consulta = $conexion->extraer_registro();
		            if(is_array($fila_consulta)){
		                foreach ($fila_consulta as $fila_par) {
		                    if($fila_par['valor2_par'] == 8){
		                        $porcentaje_comision_usuario = $fila_par['valor_par'];
		                    }
		                    else{
		                        if($fila_par['valor2_par'] == $fila['id_cat_vend']){
		                            $porcentaje_multiplo_categoria = $fila_par['valor_par'];
		                        }
		                    }
		                }
		            }
		            $porcentaje_comision_usuario = $porcentaje_comision_usuario * $porcentaje_multiplo_categoria;
		            ?>
		            <div class="col-sm-12 table-responsive">
		                <table class="table table-striped table-bordered">
		                    <tr>
		                        <!-- <td colspan="12"><?php echo $nombre_con; ?> - <?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']);?> - <?php echo utf8_encode($fila['nombre_cat_vend']); ?> - <?php echo number_format($porcentaje_comision_usuario, 3, ',', '.');?></td> -->
		                        <td colspan="12"><?php echo $nombre_con; ?> - <?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']);?></td>
		                    </tr>
		                    <tr class="font-weight-bold">
		                        <td colspan="3"></td>
		                        <td colspan="5" class="text-center">Comisiones</td>
		                        <td colspan="3" class="text-center">Bono al Precio</td>
		                        <td></td>
		                    </tr>
		                    <tr class="active font-weight-bold">
		                        <td>Unidad</td>
		                        <td>Cliente</td>
		                        <td>Valor Venta</td>
		                        <td>Comisión</td>
		                        <td>Comisión Promesa UF</td>
		                        <td>Comisión Promesa $</td>
		                        <td>Comisión Escritura UF</td>
		                        <td>Comisión Escritura $</td>
		                        <td>B. Precio</td>
		                        <td>B. Precio Promesa</td>
		                        <td>B. Precio Escritura</td>
		                        <td>Desistimiento</td>
		                    </tr>
		                    <?php 
		                    $arreglo_modelo = array(); 
		                    $monto_acumulado_a_pagar = 0;
		                    // PROMESAS
		                    $consulta = 
		                        "
		                        SELECT
		                            vend.id_vend,
		                            vend.nombre_vend,
		                            vend.apellido_paterno_vend,
		                            vend.apellido_materno_vend,
		                            his.id_est_ven,
		                            ven.monto_ven,
		                            CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente, 
		                            ven.promesa_monto_comision_ven,
		                            ven.escritura_monto_comision_ven,
		                            ven.total_comision_ven,
		                            ven.promesa_bono_precio_ven,
		                            ven.escritura_bono_precio_ven,
		                            ven.total_bono_precio_ven,
		                            viv.id_mod,
		                            viv.nombre_viv,
		                            uf.valor_uf,
		                            ven.id_pie_abo_ven,
		                            ven.descuento_ven,
		                            ven.id_ven
		                        FROM
		                            vendedor_vendedor AS vend
		                            INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		                            INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
		                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                            INNER JOIN venta_estado_historial_venta AS his ON his.id_ven = ven.id_ven
		                        WHERE
		                            tor.id_con = ? AND
		                            vend.id_vend = ? AND
		                            ven.fecha_promesa_ven > '".$fecha_desde_consulta."' AND
		                            ven.fecha_promesa_ven <= '".$fecha_hasta_consulta."' AND
		                            his.id_est_ven IN (4) AND NOT EXISTS
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
		                    $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_vend']));
		                    $fila_consulta_detalle = $conexion->extraer_registro();
		                    $contador_promesa = 0;									
		                    if($fila_consulta_detalle !=''){
								
		                        foreach ($fila_consulta_detalle as $fila_det) {
		                            // revisar valor de la venta									
							        if ($fila_det['id_pie_abo_ven']==1) { //si aplica desceunto a pie
							        	$monto_ven_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
							        } else {
							        	$monto_ven_comision = $fila_det['monto_ven'];
							        }
									

							        $comision_promesa_red = floor($fila_det['promesa_monto_comision_ven'] * 1000) / 1000;
							        $comision_promesa_red = round($comision_promesa_red, 2);

							        $monto_comision_promesa = round($comision_promesa_red * $UF_LIQUIDACION);


		                            // $monto_comision_escritura = round($fila_det['escritura_monto_comision_ven'] * $fila_det['valor_uf']);
		                            $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $UF_LIQUIDACION);
		                            // $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $fila_det['valor_uf']);

									$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_promesa;
		                            $contador_promesa++;
		                            $arreglo_modelo[$fila_det['id_mod']] = isset($arreglo_modelo[$fila_det['id_mod']]) + 1;
									$monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_promesa;
									
							        ?>
		                            <tr>
		                                <td><?php echo utf8_encode($fila_det['nombre_viv']);?> <?php echo $fila_det['id_ven']; ?></td>
		                                <td><?php echo utf8_encode($fila_det['cliente']);?></td>
		                                <td>UF <?php echo number_format($monto_ven_comision, 3, ',', '.');?></td>
		                                <td><?php echo number_format($fila_det['total_comision_ven'], 3, ',', '.');?></td>
		                               
		                                <?php  
		                                
		                                

		                                ?>
		                                <td><?php echo $comision_promesa_red;?></td>
		                                <td><?php echo number_format($monto_comision_promesa, 0, ',', '.');?></td>
		                                <td>0,00</td>
		                                <td>0</td>
		                                    
		                                
		                                <td><?php echo number_format($fila_det['total_bono_precio_ven'], 3, ',', '.');?></td>
		                                <?php  
		                                
		                                ?>
		                                <td><?php echo number_format($fila_det['promesa_bono_precio_ven'], 3, ',', '.');?> ($<?php echo number_format($monto_bono_promesa, 0, ',', '.');?>.-)</td>
		                                <td>0,00 ($0.-)</td>
		                                <td>---</td>
		                            </tr>
		                            <?php
									
		                        }
		                   
							}

		                    // ESCRITURAS
		                    $consulta = 
		                        "
		                        SELECT
		                            vend.id_vend,
		                            vend.nombre_vend,
		                            vend.apellido_paterno_vend,
		                            vend.apellido_materno_vend,
		                            his.id_est_ven,
		                            ven.monto_ven,
		                            CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente, 
		                            ven.promesa_monto_comision_ven,
		                            ven.escritura_monto_comision_ven,
		                            ven.total_comision_ven,
		                            ven.promesa_bono_precio_ven,
		                            ven.escritura_bono_precio_ven,
		                            ven.total_bono_precio_ven,
		                            viv.id_mod,
		                            viv.nombre_viv,
		                            uf.valor_uf,
		                            ven.id_pie_abo_ven,
		                            ven.descuento_ven,
		                            ven.id_ven
		                        FROM
		                            vendedor_vendedor AS vend
		                            INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		                            INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
		                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                            INNER JOIN venta_estado_historial_venta AS his ON his.id_ven = ven.id_ven
		                        WHERE
		                            tor.id_con = ? AND
		                            vend.id_vend = ? AND
		                            ven.fecha_escritura_ven > '".$fecha_desde_consulta."' AND
		                            ven.fecha_escritura_ven <= '".$fecha_hasta_consulta."' AND
		                            his.id_est_ven IN (6) AND NOT EXISTS
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
		                    

		                    $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_vend']));
		                    $fila_consulta_detalle = $conexion->extraer_registro();
		                    
		                    if(is_array($fila_consulta_detalle)){
		                        foreach ($fila_consulta_detalle as $fila_det) {
		                            // revisar valor de la venta
							        if ($fila_det['id_pie_abo_ven']==1) { //si aplica desceunto a pie
							        	$monto_ven_comision = $fila_det['monto_ven'] - $fila_det['descuento_ven'];
							        } else {
							        	$monto_ven_comision = $fila_det['monto_ven'];
							        }
							        $comision_escritura_red = floor($fila_det['escritura_monto_comision_ven'] * 1000) / 1000;
							        $comision_escritura_red = round($comision_escritura_red, 2);

							        // $monto_comision_promesa = round($fila_det['promesa_monto_comision_ven'] * $fila_det['valor_uf']);
		                            $monto_comision_escritura = round($comision_escritura_red * $UF_LIQUIDACION);
		                            // $monto_bono_promesa = round($fila_det['promesa_bono_precio_ven'] * $fila_det['valor_uf']);
		                            $monto_bono_escritura = round($fila_det['escritura_bono_precio_ven'] * $UF_LIQUIDACION);
		                            ?>

		                            <tr>
		                                <td><?php echo utf8_encode($fila_det['nombre_viv']);?> <?php echo $fila_det['id_ven']; ?></td>
		                                <td><?php echo utf8_encode($fila_det['cliente']);?></td>
		                                <td>UF <?php echo number_format($monto_ven_comision, 3, ',', '.');?></td>
		                                <td><?php echo number_format($fila_det['total_comision_ven'], 3, ',', '.');?></td>
		                                <?php  
		                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_escritura;
		                                ?>
		                                <td>0,00</td>
		                                <td>0</td>
		                                <td><?php echo $comision_escritura_red;?></td>
		                                <td><?php echo number_format($monto_comision_escritura, 0, ',', '.');?></td>
		                                
		                                
		                                <td><?php echo number_format($fila_det['total_bono_precio_ven'], 3, ',', '.');?></td>
		                                <?php  
		                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_escritura;
		                                ?>
		                                <td>0,00 ($0.-)</td>
		                                <td><?php echo number_format($fila_det['escritura_bono_precio_ven'], 3, ',', '.');?> ($<?php echo number_format($monto_bono_escritura, 0, ',', '.');?>.-)</td>
		                                <td>---</td>
		                            </tr>
		                            <?php
		                        }
		                    }


		                    // DESISTIMIENTOS
		                    ?>
		                    
		                    
		                    <tr>
		                        <td class="active" colspan="12"><b>Desistimiento</b></td>
		                    </tr>							
		                    <?php  
		                    $total_desistimiento_acumulado = 0;
		                    // $consulta = 
		                    //     "
		                    //     SELECT
		                    //         vend.id_vend,
		                    //         vend.nombre_vend,
		                    //         vend.apellido_paterno_vend,
		                    //         vend.apellido_materno_vend,
		                    //         ven.id_ven,
		                    //         CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente, 
		                    //         ven.id_est_ven,
		                    //         ven.monto_ven,
		                    //         ven.promesa_monto_comision_ven,
		                    //         ven.escritura_monto_comision_ven,
		                    //         ven.total_comision_ven,
		                    //         ven.promesa_bono_precio_ven,
		                    //         ven.escritura_bono_precio_ven,
		                    //         ven.total_bono_precio_ven,
		                    //         viv.nombre_viv,
		                    //         uf.valor_uf
		                    //     FROM
		                    //         vendedor_vendedor AS vend
		                    //         INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		                    //         INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		                    //         INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                    //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		                    //         INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
		                    //         -- INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
		                    //         INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
		                    //     WHERE								    
		                    //         tor.id_con = ? AND
		                    //         vend.id_vend = ? AND
		                    //         des_ven.fecha_des_ven > '".$fecha_desde_consulta."' AND
		                    //         des_ven.fecha_des_ven <= '".$fecha_hasta_consulta."' AND
		                    //         des_ven.id_tip_des = 1 AND
		                    //         ven.id_est_ven = 3 AND NOT 
		                    //         EXISTS(
		                    //             SELECT 
		                    //                 ven_cie.id_ven_cie
		                    //             FROM 
		                    //                 cierre_venta_cierre AS ven_cie
		                    //             WHERE
		                    //                 ven_cie.id_ven = ven.id_ven AND
		                    //                 ven_cie.id_est_ven = 3
		                    //         ) AND 
		                    //         EXISTS(
		                    //             SELECT 
		                    //                 ven_cie.id_ven_cie
		                    //             FROM 
		                    //                 cierre_venta_cierre AS ven_cie
		                    //             WHERE
		                    //                 ven_cie.id_ven = ven.id_ven AND
		                    //                 (ven_cie.id_est_ven = 4 OR ven_cie.id_est_ven = 6)
		                    //         )

		                            
		                    //     ";
								$consulta = 
		                        "
		                        SELECT
		                            vend.id_vend,
		                            vend.nombre_vend,
		                            vend.apellido_paterno_vend,
		                            vend.apellido_materno_vend,
		                            ven.id_ven,
		                            CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente, 
		                            ven.id_est_ven,
		                            ven.monto_ven,
		                            ven.promesa_monto_comision_ven,
		                            ven.escritura_monto_comision_ven,
		                            ven.total_comision_ven,
		                            ven.promesa_bono_precio_ven,
		                            ven.escritura_bono_precio_ven,
		                            ven.total_bono_precio_ven,
		                            viv.nombre_viv,
		                            uf.valor_uf
		                        FROM
		                            vendedor_vendedor AS vend
		                            INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		                            INNER JOIN venta_desestimiento_venta AS des_ven ON des_ven.id_ven = ven.id_ven
		                            -- INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(des_ven.fecha_des_ven)
		                            INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(ven.fecha_ven)
		                        WHERE								    
		                            tor.id_con = ? AND
		                            vend.id_vend = ? AND
		                            des_ven.fecha_des_ven > '".$fecha_desde_consulta."' AND
		                            des_ven.fecha_des_ven <= '".$fecha_hasta_consulta."' AND
		                            des_ven.id_tip_des = 1 AND
		                            ven.id_est_ven = 3 AND 
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
		                    $conexion->consulta_form($consulta,array($fila['id_con'],$fila['id_vend']));
		                    $fila_consulta_detalle = $conexion->extraer_registro();
		                    // $contador_promesa = 0;
		                    if(is_array($fila_consulta_detalle)){
		                        foreach ($fila_consulta_detalle as $fila_det) {
									//  var_dump($fila_det);
		                        	$UF_DESISTIMIENTO_VENTA = 0;

		                        	$UF_DESISTIMIENTO_VENTA =  get_uf_disistimiento($fila_det['id_ven']);
									$num_promesa_monto_ = (float) $fila_det['promesa_monto_comision_ven'];
									$num_escritura_monto_ = (float) $fila_det['escritura_monto_comision_ven'];
		                            // $monto_comision_promesa = round(round($num_promesa_monto_,2) *  (float) $valor_uf);
		                            $monto_comision_promesa = round(round($num_promesa_monto_,2) *  (float)$UF_DESISTIMIENTO_VENTA);
		                            // $monto_comision_escritura = round(round($num_escritura_monto_,2) * (float) $valor_uf);
		                            $monto_comision_escritura = round(round($num_escritura_monto_,2) * (float)$UF_DESISTIMIENTO_VENTA);									
		                            $total_desistimiento = 0;		                            
		                            $conexion->consulta_form("SELECT id_est_ven FROM cierre_venta_cierre WHERE id_ven = ? AND id_est_ven = ?",array($fila_det['id_ven'],4));
		                            $cantidad_estado_promesa = $conexion->total();		                           
		                            $conexion->consulta_form("SELECT id_est_ven FROM cierre_venta_cierre WHERE id_ven = ? AND id_est_ven = ?",array($fila_det['id_ven'],6));
		                            $cantidad_estado_escritura = $conexion->total();
		                            ?>
		                            <tr>
		                                <td><?php echo utf8_encode($fila_det['nombre_viv']);?> <?php echo $fila_det['id_ven']; ?></td>
		                                <td><?php echo utf8_encode($fila_det['cliente']);?></td>
		                                <td></td>
		                                <td></td>
		                                <?php  
		                                if($cantidad_estado_promesa > 0){
		                                    $total_desistimiento = $total_desistimiento + $monto_comision_promesa;
		                                    ?>
		                                    <td><?php echo number_format($fila_det['promesa_monto_comision_ven'], 2, ',', '.');?></td>
		                                    <td><?php echo number_format($monto_comision_promesa, 0, ',', '.');?></td>
		                                    <?php
		                                }
		                                if($cantidad_estado_escritura > 0){
		                                    $total_desistimiento = $total_desistimiento + $monto_comision_escritura;
		                                    ?>
		                                    <td><?php echo number_format($fila_det['escritura_monto_comision_ven'], 2, ',', '.');?></td>
		                                    <td><?php echo number_format($monto_comision_escritura, 0, ',', '.');?></td>

		                                    <?php
		                                }
		                                else{
		                                    ?>
		                                    <td>0,00</td>
		                                    <td>0</td>
		                                    <?php
		                                }
		                                ?>
		                                <td></td>
		                                <td></td>
		                                <td></td>
		                                <td><?php echo number_format($total_desistimiento, 0, ',', '.');?>.-</td>
		                            </tr>
		                            <?php
		                            $total_desistimiento_acumulado = $total_desistimiento_acumulado + $total_desistimiento;
		                        }
		                    }
		                    ?>
		                    
		                    <?php  
		                    // bono rango 1
		                    $consulta = 
		                        "
		                        SELECT
		                            monto_bon,
		                            nombre_bon
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
							$arreglo_vendedor_bono_uf = [];
							$arreglo_vendedor_bono_plata = [];
		                    // echo $cantidad_bono;
		                    if($cantidad_bono > 0){
			                    // $fila_bono = $conexion->extraer_registro_unico();
			                    $fila_consulta_detalle = $conexion->extraer_registro();
			                    if(is_array($fila_consulta_detalle)){
			                        foreach ($fila_consulta_detalle as $fila_bono) {
					                    $monto_bono_rango = 0;
					                    $monto_bono_rango_plata = 0;
			                    

				                        $nombre_bon = utf8_encode($fila_bono["nombre_bon"]);
				                        $monto_bono_rango = $fila_bono["monto_bon"];
				                        $monto_bono_rango_plata = $fila_bono["monto_bon"] * $valor_uf;
				                        $arreglo_vendedor_bono_uf[$fila["id_vend"]] = $monto_bono_rango;
				                        $arreglo_vendedor_bono_plata[$fila["id_vend"]] = $monto_bono_rango_plata;
				                        $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_rango_plata;
			                    		?>
					                    <tr>
					                        <td class="active" colspan="9"><b><?php echo $nombre_bon ?></b></td>
					                        <td style="background-color: #FFFFFF">UF <?php echo number_format($monto_bono_rango, 2, ',', '.');?></td>
					                        <td style="background-color: #FFFFFF">$ <?php echo number_format($monto_bono_rango_plata, 0, ',', '.');?>.-</td>
					                        <td></td>
					                    </tr>
			                    	<?php 
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
		                                    )
		                                GROUP BY 
		                                    ven.id_ven 
		                                ";
		                            $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila['id_vend'],$fila_det["id_bon"]));
		                            $cantidad_bono = $conexion->total();
		                            if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
		                                $monto_bono_rango = $fila_det["monto_bon"];
		                                $monto_bono_rango_plata = $fila_det["monto_bon"] * $valor_uf;
		                                $arreglo_vendedor_bono_uf[$fila["id_vend"]] = $arreglo_vendedor_bono_uf[$fila["id_vend"]] + $monto_bono_rango;
		                                $arreglo_vendedor_bono_plata[$fila["id_vend"]] = $arreglo_vendedor_bono_plata[$fila["id_vend"]] + $monto_bono_rango_plata;
		                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_rango_plata;
		                                ?>
		                                <tr>
		                                    <td class="active" colspan="9"><b><?php echo utf8_encode($fila_det['nombre_bon']);?></b></td>
		                                    <td style="background-color: #FFFFFF">UF <?php echo number_format($monto_bono_rango, 2, ',', '.');?></td>
		                                    <td style="background-color: #FFFFFF">$ <?php echo number_format($monto_bono_rango_plata, 0, ',', '.');?>.-</td>
		                                    <td></td>
		                                </tr>
		                                <?php

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
		                                    )
		                                GROUP BY 
		                                    ven.id_ven 
		                                ";
		                            $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila_det["id_mod"],$fila['id_vend'],$fila_det["id_bon"]));
		                            $cantidad_bono = $conexion->total();
		                            if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
		                                $monto_bono_rango = $fila_det["monto_bon"];
		                                $monto_bono_rango_plata = $fila_det["monto_bon"] * $valor_uf;
		                                $arreglo_vendedor_bono_uf[$fila["id_vend"]] = $arreglo_vendedor_bono_uf[$fila["id_vend"]] + $monto_bono_rango;
		                                $arreglo_vendedor_bono_plata[$fila["id_vend"]] = $arreglo_vendedor_bono_plata[$fila["id_vend"]] + $monto_bono_rango_plata;
		                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_rango_plata;
		                                ?>
		                                <tr>
		                                    <td class="active" colspan="9"><b><?php echo utf8_encode($fila_det['nombre_bon']);?></b></td>
		                                    <td style="background-color: #FFFFFF">UF <?php echo number_format($monto_bono_rango, 2, ',', '.');?></td>
		                                    <td style="background-color: #FFFFFF">$ <?php echo number_format($monto_bono_rango_plata, 0, ',', '.');?>.-</td>
		                                    <td></td>
		                                </tr>
		                                <?php

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
								// para dejarlo perfecto, se podría separar ir a buscar los sin rango con la fecha
								// y los con rango sin la fecha
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
											// cuando no hay rango, se paga directo
											$monto_bono_rango = $fila_det["monto_bon"];
			                                $monto_bono_rango_plata = $fila_det["monto_bon"] * $valor_uf;
			                                $arreglo_vendedor_bono_uf[$fila["id_vend"]] = $arreglo_vendedor_bono_uf[$fila["id_vend"]] + $monto_bono_rango;
			                                $arreglo_vendedor_bono_plata[$fila["id_vend"]] = $arreglo_vendedor_bono_plata[$fila["id_vend"]] + $monto_bono_rango_plata;
			                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_rango_plata;

											?>
			                                <tr>
			                                    <td class="active" colspan="9"><b><?php echo utf8_encode($fila_det['nombre_bon']);?></b></td>
			                                    <td style="background-color: #FFFFFF">UF <?php echo number_format($monto_bono_rango, 2, ',', '.');?></td>
			                                    <td style="background-color: #FFFFFF">$ <?php echo number_format($monto_bono_rango_plata, 0, ',', '.');?>.-</td>
			                                    <td></td>
			                                </tr>
			                                <?php

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
				                                    )
				                                GROUP BY 
				                                    ven.id_ven 
				                                ";
				                            $conexion->consulta_form($consulta,array($fila['id_con'],$fila_det["fecha_desde_bon"],$fila_det["fecha_hasta_bon"],$fila['id_vend'],$fila_det["id_bon"]));
				                            $cantidad_bono = $conexion->total();
				                            if($cantidad_bono >= $fila_det["desde_bon"] && $cantidad_bono <= $fila_det["hasta_bon"]){
				                                $monto_bono_rango = $fila_det["monto_bon"];
				                                $monto_bono_rango_plata = $fila_det["monto_bon"] * $valor_uf;
				                                $arreglo_vendedor_bono_uf[$fila["id_vend"]] = $arreglo_vendedor_bono_uf[$fila["id_vend"]] + $monto_bono_rango;
				                                $arreglo_vendedor_bono_plata[$fila["id_vend"]] = $arreglo_vendedor_bono_plata[$fila["id_vend"]] + $monto_bono_rango_plata;
				                                $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_bono_rango_plata;
				                                ?>
				                                <tr>
				                                    <td class="active" colspan="9"><b><?php echo utf8_encode($fila_det['nombre_bon']);?></b></td>
				                                    <td style="background-color: #FFFFFF">UF <?php echo number_format($monto_bono_rango, 2, ',', '.');?></td>
				                                    <td style="background-color: #FFFFFF">$ <?php echo number_format($monto_bono_rango_plata, 0, ',', '.');?>.-</td>
				                                    <td></td>
				                                </tr>
				                                <?php

				                            }

										}
			                            
			                        }
			                    }
			                }

		                    $monto_acumulado_a_pagar = $monto_acumulado_a_pagar - $total_desistimiento_acumulado;
		                    ?>
							<!-- 

							aqui empieza el bono c2

						    -->
							<?php							
								$mess = explode("-", $fecha_hasta);												
								$meta = '
									SELECT 
									valor_met_ven
									FROM vendedor_meta_vendedor
									WHERE id_vend = '.$fila["id_vend"].' and
									anio_mes = '.$mess[2].' and
									id_mes = '.$mess[1].'
								';     							         							
								$conexion->consulta($meta);
								$consulta_meta = $conexion->extraer_registro();
								// verifico que tenga metas asignadas
								if(is_array($consulta_meta)){
										$numero_meta = $consulta_meta[0]['valor_met_ven'];										
										$query = '
										SELECT 
										COUNT(DISTINCT(venta.id_ven)) as numeroVentas
										FROM vendedor_vendedor as vende
										INNER JOIN vendedor_meta_vendedor as meta ON vende.id_vend = meta.id_vend
										INNER JOIN venta_venta as venta ON venta.id_vend = vende.id_vend
										WHERE vende.id_vend = '.$fila["id_vend"].' and
										vende.id_est_vend = 1 and                                              
										DATE(venta.fecha_promesa_ven) >= "'.formateoFecha( $fecha_desde ).'" and
										DATE(venta.fecha_promesa_ven) <= "'.formateoFecha( $fecha_hasta ).'" and 
										venta.id_est_ven <> 3
										';  	
															          
										$conexion->consulta($query);
										$consulta_ventas = $conexion->extraer_registro(); 										
										$numeroVentas = $consulta_ventas[0]['numeroVentas'];
										$resultado = ($numeroVentas / $numero_meta) * 100;										
										$bonoC2 = 0;										
										// if($resultado>=100){
											switch ($resultado) {
												case ($resultado<100):
													$bonoC2 = 0;
													break;
												case ($resultado==100):
													$bonoC2 = ($sueldoBase * 15) / 100;
													break;
												case ($resultado>=101 && $resultado<=150):
													$bonoC2 = ($sueldoBase * 20) / 100;
													break;
												case ($resultado>=151):
													$bonoC2 = ($sueldoBase * 25) / 100;
													break;
												default:
													# code...
													break;
											}
											?>
											<tr>
											<td class="active" colspan="2"><b>Bono C2	:</b></td>
											<td colspan="1">Cumplimiento : <?php echo $resultado?> %</td>	
											<?php 
											$consulta_mes = "
											SELECT nombre_mes
											FROM mes_mes
											WHERE id_mes = ".$mes."										
											";
											$conexion->consulta($consulta_mes);
											$nombre_mes = $conexion->extraer_registro(); 

											?>							
											<td colspan="1" class="text-center">Mes : <?php echo $nombre_mes[0]['nombre_mes']?> </td>
											<td colspan="1" class="text-left">Total $<?php if($resultado==0){ echo '0'; $bonoC2 = 0;}else{ echo $bonoC2;}?> </td>
											<td colspan="7"></td>
											</tr>
										
											<?php 
										
										     if(count($contendorBonos)>0){
												for ($i=0; $i < count($contendorBonos); $i++) {																											
															$C2 = array(
																'nombre' => 'Bono C2',
																'porcentaje' => $resultado,
																'monto' => $bonoC2,
																'id_vendedor' => $fila["id_vend"],
																'mes' =>  $nombre_mes[0]['nombre_mes']
															 );															
												}
											 }else{
												$C2 = array(
													'nombre' => 'Bono C2',
													'porcentaje' => $resultado,
													'monto' => $bonoC2,
													'id_vendedor' => $fila["id_vend"],
													'mes' =>  $nombre_mes[0]['nombre_mes']
												 );
											 }
								}
							
							/*

							aqui termina el bono c2

						    */

							/**************************/

							// Comienzo de bono c3

							// definir total de metas y ventas según el mes en progreso
							// definir porcentajes a regir en el resultado de total de metas y ventas
							// si el mes es 6 sacar total de metas y ventas desde el 1 al 6, si el mes es 12 sacar el total desde el 7 al 12 
							$messc3 = explode("-", $fecha_hasta);
							$metac3="";
							$numVentas = "";
							$bonoC3 = 0;
							if($messc3[1]==6 || $messc3[1]==12){
								if($messc3[1]==6){
									$metac3 = '
										SELECT 
										SUM(valor_met_ven) as totalMeta
										FROM vendedor_meta_vendedor
										WHERE id_vend = '.$fila["id_vend"].' and
										anio_mes = '.$messc3[2].' and
										id_mes >= 1 and id_mes <= 6 
									'; 
									$numVentas = '
									SELECT 
									COUNT(DISTINCT(venta.id_ven)) as numeroVentas
									FROM vendedor_vendedor as vende
									INNER JOIN vendedor_meta_vendedor as meta ON vende.id_vend = meta.id_vend
									INNER JOIN venta_venta as venta ON venta.id_vend = vende.id_vend
									WHERE vende.id_vend = '.$fila["id_vend"].' and
									vende.id_est_vend = 1 and                                              
									DATE(venta.fecha_promesa_ven) >= "'.$messc3[2].'-01-01" and
									DATE(venta.fecha_promesa_ven) <= "'.formateoFecha( $fecha_hasta ).'" and 
									venta.id_est_ven <> 3
									';


								}else if($messc3[1]==12){
									$metac3 = '
										SELECT 
										SUM(valor_met_ven) as totalMeta
										FROM vendedor_meta_vendedor
										WHERE id_vend = '.$fila["id_vend"].' and
										anio_mes = '.$messc3[2].' and
										id_mes >= 7 and id_mes <= 12
									'; 
									$numVentas = '
									SELECT 
									COUNT(DISTINCT(venta.id_ven)) as numeroVentas
									FROM vendedor_vendedor as vende
									INNER JOIN vendedor_meta_vendedor as meta ON vende.id_vend = meta.id_vend
									INNER JOIN venta_venta as venta ON venta.id_vend = vende.id_vend
									WHERE vende.id_vend = '.$fila["id_vend"].' and
									vende.id_est_vend = 1 and                                              
									DATE(venta.fecha_promesa_ven) >= "'.$messc3[2].'-07-01" and
									DATE(venta.fecha_promesa_ven) <= "'.formateoFecha( $fecha_hasta ).'" and 
									venta.id_est_ven <> 3
									';
								}										
																										
									$conexion->consulta($metac3);
									$consulta_meta_total = $conexion->extraer_registro();
									// total de metas de los últimos 6 meses dependiendo de si es junio o diciembre
									$total_metas = $consulta_meta_total[0]['totalMeta'];

									$conexion->consulta($numVentas);
									$consulta_ventas_total = $conexion->extraer_registro(); 
									// total de ventas de los últimos 6 meses dependiendo de si es junio o diciembre
									$total_ventas = $consulta_ventas_total[0]['numeroVentas'];

									$resultadoc3 = ($total_ventas / $total_metas) * 100;														
										// if($resultadoc3>=100){
											switch ($resultadoc3) {
												case ($resultadoc3<100):
													$bonoC3 = 0;
													break;
												case ($resultadoc3==100):
													$bonoC3 = ($sueldoBase * 12) / 100;
													break;
												case ($resultadoc3>=101 && $resultadoc3<=150):
													$bonoC3 = ($sueldoBase * 16) / 100;
													break;
												case ($resultadoc3>=151):
													$bonoC3 = ($sueldoBase * 17) / 100;
													break;
												default:
													# code...
													break;
											}
								
										    ?>
											<tr>
											<td class="active" colspan="2"><b>Bono C3	:</b></td>
											<td colspan="1">Cumplimiento : <?php echo round($resultadoc3,0)?> %</td>	
											<?php 
											$nombre_mes_c3 = "";
											$consulta_mesc3 = "
											SELECT nombre_mes
											FROM mes_mes
											WHERE id_mes = ".$mes."										
											";
											$conexion->consulta($consulta_mesc3);
											$nombre_mes_c3 = $conexion->extraer_registro(); 

											?>							
											<td colspan="1" class="text-center">Mes : <?php echo $nombre_mes_c3[0]['nombre_mes']?> </td>
											<td colspan="1" class="text-left">Total $<?php echo $bonoC3?> </td>
											<td colspan="7"></td>
											</tr>
										
											<?php
											
											if(count($contendorBonosC3)>0){
												for ($i=0; $i < count($contendorBonosC3); $i++) { 
													
														
															$C3 = array(
																'nombre' => 'Bono C3',
																'porcentaje' => $resultadoc3,
																'monto' => $bonoC3,
																'id_vendedor' => $fila["id_vend"],
																'mes' =>  $nombre_mes_c3[0]['nombre_mes']
															 );
															
												}
											 }else{
												$C3 = array(
													'nombre' => 'Bono C3',
													'porcentaje' => $resultadoc3,
													'monto' => $bonoC3,
													'id_vendedor' => $fila["id_vend"],
													'mes' =>  $nombre_mes_c3[0]['nombre_mes']
												 );
											 } 
										// }

							}else{
								?>
								<tr>
									<td class="active" colspan="2"><b>Bono C3	:</b></td>
									<td colspan="1">Cumplimiento : 0 %</td>	
									<?php 
									$nombre_mes_c3 = "";
									$consulta_mesc3 = "
									SELECT nombre_mes
									FROM mes_mes
									WHERE id_mes = ".$mes."										
									";
									$conexion->consulta($consulta_mesc3);
									$nombre_mes_c3 = $conexion->extraer_registro(); 

									?>							
									<td colspan="1" class="text-center">Mes : <?php echo $nombre_mes_c3[0]['nombre_mes']?> </td>
									<td colspan="1" class="text-left">Total $<?php echo $bonoC3?> </td>
									<td colspan="7"></td>
								</tr>

							<?php 
							$C3 = array(
								'nombre' => 'Bono C3',
								'porcentaje' => 0,
								'monto' => $bonoC3,
								'id_vendedor' => $fila["id_vend"],
								'mes' =>  $nombre_mes_c3[0]['nombre_mes']
							 );
							}

							// Comienzo de bono c1
							
							$messc1 = explode("-", $fecha_hasta);
							$bonoC1 = 0;
							$cumple="<p style='color:#E74C3C;'>No cumple con evaluación de desempeño sobresaliente.</p>";											
							if($messc1[1]==3 || $messc1[1]==6 || $messc1[1]==9 || $messc1[1]==12){
								// if($fila["id_vend"] == 13 || $fila["id_vend"] == 15){																								
									
									if($fila["id_vend"] == 13 || $fila["id_vend"] == 15){
										$bonoC1 = ($sueldoBase * 0.50)+$sueldoBase;
										$cumple="<p style='color:#229954;'>Cumple con evaluación de desempeño sobresaliente.</p>";
									}else{
										$bonoC1 = 0;
										$cumple="<p style='color:#E74C3C;'>No cumple con evaluación de desempeño sobresaliente.</p>";
									}
										?>
											<tr>
											<td class="active" colspan="2"><b>Bono C1	:</b></td>
											<td colspan="2"><?php echo $cumple;?></td>	
											<?php 
											$nombre_mes_c1 = "";
											$consulta_mesc1 = "
											SELECT nombre_mes
											FROM mes_mes
											WHERE id_mes = ".$mes."										
											";
											$conexion->consulta($consulta_mesc1);
											$nombre_mes_c1 = $conexion->extraer_registro(); 
											
											?>							
											<td colspan="1" class="text-center">Mes : <?php echo $nombre_mes_c1[0]['nombre_mes']?> </td>
											<td colspan="1" class="text-left">Total $<?php echo $bonoC1?> </td>
											<td colspan="6"></td>
											</tr>
										
											<?php
											
											if(count($contendorBonosC1)>0){
												for ($i=0; $i < count($contendorBonosC1); $i++) { 
													
														
															$C1 = array(
																'nombre' => 'Bono C1',
																'porcentaje' => 50,
																'monto' => $bonoC1,
																'id_vendedor' => $fila["id_vend"],
																'mes' =>  $nombre_mes_c1[0]['nombre_mes']
															 );
															
												}
											 }else{
												$C1 = array(
													'nombre' => 'Bono C1',
													'porcentaje' => 50,
													'monto' => $bonoC1,
													'id_vendedor' => $fila["id_vend"],
													'mes' =>  $nombre_mes_c1[0]['nombre_mes']
												 );
											 } 
											
								// }
							}else{
							?>
							<tr>
								<td class="active" colspan="2"><b>Bono C1	:</b></td>
								<td colspan="2"><?php echo $cumple;?></td>	
								<?php 
								$nombre_mes_c1 = "";
								$consulta_mesc1 = "
								SELECT nombre_mes
								FROM mes_mes
								WHERE id_mes = ".$mes."										
								";
								$conexion->consulta($consulta_mesc1);
								$nombre_mes_c1 = $conexion->extraer_registro(); 
								
								?>							
								<td colspan="1" class="text-center">Mes : <?php echo $nombre_mes_c1[0]['nombre_mes']?> </td>
								<td colspan="1" class="text-left">Total $<?php echo $bonoC1?> </td>
								<td colspan="6"></td>
							</tr>
							<?php 
							$C1 = array(
								'nombre' => 'Bono C1',
								'porcentaje' => 0,
								'monto' => $bonoC1,
								'id_vendedor' => $fila["id_vend"],
								'mes' =>  $nombre_mes_c1[0]['nombre_mes']
							 );
							}?>
		                    <tr class="success">
		                        <td colspan="2"><b>Total a Pagar</b></td>
		                        <td colspan="2"><?php echo number_format($monto_acumulado_a_pagar, 0, ',', '.');?>.-</td>
		                        <td colspan="8"></td>
		                    </tr>
		                </table>
		               </div>
		            <?php
				
				 
		          $contendorBonosC1[$cont]= $C1;				 
		          $contendorBonos[$cont]= $C2;				 
		          $contendorBonosC3[$cont]= $C3;				 
				  $cont += 1;
				  
			   }  
		    }

			$contendorC1 = array_unique($contendorBonosC1, SORT_REGULAR);
			$contendor = array_unique($contendorBonos, SORT_REGULAR);
			$contendorc3 = array_unique($contendorBonosC3, SORT_REGULAR);
			$_SESSION["c1"]=$contendorC1;
			$_SESSION["c2"]=$contendor;
			$_SESSION["c3"]=$contendorc3;
			//--------- VENDEDORES FIN ---------

		    //--------- OPERACIONES ---------
		    $arreglo_vendedor_operacion = array();
		    
		    $monto_acumulado_a_pagar = 0;
		    $total_desistimiento = 0;
		    $total_desistimiento_acumulado = 0;

		    // selecciona vendedores activos, un nuevo array de vendedores, con todos los activos

		    $consulta = 
		        "
		        SELECT
		            usu.id_usu,
		            usu.nombre_usu,
		            usu.apellido1_usu,
		            usu.apellido2_usu,
		            con.id_con,
		            con.nombre_con
		        FROM
		            usuario_usuario AS usu
		            INNER JOIN venta_venta AS ven ON ven.id_operacion_ven = usu.id_usu
		            INNER JOIN usuario_condominio_usuario AS con_usu ON con_usu.id_usu = usu.id_usu
		            INNER JOIN condominio_condominio AS con ON con.id_con = con_usu.id_con
		            INNER JOIN venta_estado_historial_venta AS his ON his.id_ven = ven.id_ven
		        WHERE
		            usu.id_per = ? AND
		            ven.id_vend IN (".implode(',',$arreglo_vendedores_operacion).") AND
		            con.id_con = ".$condo." AND
		            his.id_est_ven = 6
		        GROUP BY 
		            usu.id_usu,
		            usu.nombre_usu,
		            usu.apellido1_usu,
		            usu.apellido2_usu,
		            con.nombre_con,
		            con.id_con
		        ";
		    // echo $consulta;
		    $conexion->consulta_form($consulta,array(3));
		    $cantidad_venta_oopp = $conexion->total();
		    if($cantidad_venta_oopp > 0){
		        ?>
		        <div class="col-sm-12">
		            <h4>Operaciones</h4>
		        </div>
		        <?php
		    }
		    $fila_consulta = $conexion->extraer_registro();
		    if(is_array($fila_consulta)){
		        foreach ($fila_consulta as $fila) {
		            // $monto_bono_jefe_acumulado_uf = 0;
		            // $monto_bono_jefe_acumulado_plata = 0;
		            
		            $consulta = 
		                "
		                SELECT
		                    DISTINCT(ven.id_vend)
		                FROM
		                    usuario_usuario AS usu
		                    INNER JOIN venta_venta AS ven ON ven.id_operacion_ven = usu.id_usu 
		                    INNER JOIN usuario_condominio_usuario AS con_usu ON con_usu.id_usu = usu.id_usu
		                WHERE
		                    usu.id_usu = ? AND
		                    con_usu.id_con = ? AND
		                    ven.id_vend IN (".implode(',',$arreglo_vendedores_operacion).") 
		                ";
		            // echo $consulta;
		            $conexion->consulta_form($consulta,array($fila["id_usu"],$fila["id_con"]));
		            $fila_consulta_usuario = $conexion->extraer_registro();
		            if(is_array($fila_consulta_usuario)){
		                foreach ($fila_consulta_usuario as $fila_usu) {
		                    
		                    if(!in_array($fila_usu["id_vend"],$arreglo_vendedor_operacion)){
		                        $arreglo_vendedor_operacion[$fila_usu["id_vend"]] = $fila_usu["id_vend"];
		                    }
		                }
		            }
		            $monto_acumulado_a_pagar = 0;
		            $consulta = 
		                "
		                SELECT
		                    vend.id_vend,
		                    vend.nombre_vend,
		                    vend.apellido_paterno_vend,
		                    vend.apellido_materno_vend,
		                    his.id_est_ven,
		                    ven.monto_ven,
		                    ven.promesa_monto_comision_jefe_ven,
		                    ven.escritura_monto_comision_operacion_ven,
		                    ven.total_comision_jefe_ven,
		                    CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente, 
		                    ven.promesa_bono_precio_jefe_ven,
		                    ven.escritura_bono_precio_jefe_ven,
		                    ven.total_bono_precio_jefe_ven,
		                    viv.nombre_viv,
		                    eta_cam_ven.valor_campo_eta_cam_ven,
		                    uf.valor_uf,
		                    ven.id_ven
		                FROM
		                    vendedor_vendedor AS vend
		                    INNER JOIN venta_venta AS ven ON ven.id_vend = vend.id_vend
		                    INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
		                    INNER JOIN venta_etapa_campo_venta AS eta_cam_ven ON eta_cam_ven.id_ven = ven.id_ven
		                    INNER JOIN uf_uf AS uf ON uf.fecha_uf = DATE(eta_cam_ven.valor_campo_eta_cam_ven)
		                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		                    INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
		                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		                    INNER JOIN venta_estado_historial_venta AS his ON his.id_ven = ven.id_ven
		                WHERE
		                    tor.id_con = ? AND
		                    ven.fecha_escritura_ven > '".$fecha_desde_consulta."' AND
		                    ven.fecha_escritura_ven <= '".$fecha_hasta_consulta."' AND
		                    his.id_est_ven IN (6) AND
		                    ((eta_ven.id_eta = 27 AND eta_ven.id_est_eta_ven = 1) OR (eta_ven.id_eta = 6 AND eta_ven.id_est_eta_ven = 1)) AND
		                    (eta_cam_ven.id_cam_eta = 32 OR eta_cam_ven.id_cam_eta = 5)
		                    
		                ";


		            // echo $consulta;
		                // fecha firma
		                // contado es id_cam_eta = 5
		                // credito es id_cam_eta = 32
		            $conexion->consulta_form($consulta,array($fila["id_con"]));
		            $cantidad_consulta = $conexion->total();
		            $fila_consulta_detalle = $conexion->extraer_registro();
		            $contador_promesa = 0;
		            if($cantidad_consulta > 0){
		                ?>
		                <div class="col-sm-12 table-responsive">
		                    <table class="table table-striped table-bordered">
		                        <tr>
		                            <td colspan="12"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']);?></td>
		                        </tr>
		                        <tr class="font-weight-bold">
		                            <td colspan="3"></td>
		                            <td colspan="5" class="text-center">Comisiones</td>
		                            <td colspan="3" class="text-center">Bono al Precio</td>
		                            <td></td>
		                        </tr>
		                        <tr class="active font-weight-bold">
		                            <td>Unidad</td>
		                            <td>Cliente</td>
		                            <td>Valor Venta</td>
		                            <td>Comisión</td>
		                            <td>Comisión Promesa UF</td>
		                            <td>Comisión Promesa $</td>
		                            <td>Comisión Escritura UF</td>
		                            <td>Comisión Escritura $</td>
		                            <td>B. Precio</td>
		                            <td>B. Precio Promesa</td>
		                            <td>B. Precio Escritura</td>
		                            <td>Desistimiento</td>
		                        </tr>
		                        <?php  
		                        
		                        if(is_array($fila_consulta_detalle)){
		                            foreach ($fila_consulta_detalle as $fila_det) {
		                                $monto_comision_promesa = round($fila_det['promesa_monto_comision_jefe_ven'] * $fila_det['valor_uf']);
		                                $monto_comision_escritura = round($fila_det['escritura_monto_comision_operacion_ven'] * $fila_det['valor_uf']);
		                                $monto_bono_promesa = round($fila_det['promesa_bono_precio_jefe_ven'] * $fila_det['valor_uf']);
		                                $monto_bono_escritura = round($fila_det['escritura_bono_precio_jefe_ven'] * $fila_det['valor_uf']);
		                                ?>
		                                <tr>
		                                    <td><?php echo utf8_encode($fila_det['nombre_viv']);?> - id venta: <?php echo $fila_det['id_ven']; ?></td>
		                                    <td><?php echo utf8_encode($fila_det['cliente']);?></td>
		                                    <td>UF <?php echo number_format($fila_det['monto_ven'], 2, ',', '.');?></td>
		                                    <td></td>
		                                    <?php  
		                                    $monto_acumulado_a_pagar = $monto_acumulado_a_pagar + $monto_comision_escritura;
		                                    ?>
		                                    <td>0,00</td>
		                                    <td>0</td>
		                                    <td><?php echo number_format($fila_det['escritura_monto_comision_operacion_ven'], 2, ',', '.');?></td>
		                                    <td><?php echo number_format($monto_comision_escritura, 0, ',', '.');?></td>
		                                        
		                                    
		                                    <td></td>
		                                    
		                                    <td>0,00 ($0.-)</td>
		                                    <td>0,00 ($0.-)</td>
		                                        
		                                    
		                                    <td>---</td>
		                                </tr>
		                                <?php
		                            }
		                        }
		                        ?>
		                        
		                        
		                        
		                        <?php  
		                        
		                          
		                        // $monto_acumulado_a_pagar = $monto_acumulado_a_pagar - $total_desistimiento_acumulado;
		                        ?>
		                        <tr class="success">
		                            <td colspan="2"><b>Total a Pagar</b></td>
		                            <td colspan="2"><?php echo number_format($monto_acumulado_a_pagar, 0, ',', '.');?>.-</td>
		                            <td colspan="8"></td>
		                        </tr>
		                    </table>
		                </div>
		                <?php
		            }     
		        }
		    }

		}
		else{
			if($nombre_con != "Condominio Pacífico 2800 ETAPA I"){
		    ?>
		    <div class="col-sm-12">
		        <h4>Vendedores</h4>
		    </div>
		    <div class="col-xs-12">
		        <h4 style="font-size: 14px">
		            <?php echo $nombre_con; ?>: <b>No se encuentra ninguna venta para liquidar</b>
		        </h4>
		        <hr>
		    </div>
		    <?php
			}
		}

		// JEFE OPERACIONES
		$consulta_jefe_op = 
		    "
		    SELECT
		        CONCAT(nombre_usu,' ', apellido1_usu) as jefe_ope,
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
		?>
		<div class="col-sm-12">
	        <h4>Jefe de Operaciones</h4>
	    </div>
		<div class="col-sm-12 table-responsive">
            <table class="table table-striped table-bordered">
                <tr>
                    <td colspan="9"><?php echo$nombre_con; ?> - <?php echo utf8_encode($fila_jefeop['jefe_ope']);?></td>
                </tr>
                <tr class="active font-weight-bold">
                    <td>Unidad</td>
                    <td>Cliente</td>
                    <td>Forma de Pago</td>
                    <td>Fecha Escritura</td>
                    <td>Fecha Liquidación</td>
                    <td>Semanas</td>
                    <td>Nombre Bono</td>
                    <td>Monto UF</td>
                    <td>Monto $</td>
                </tr>

			<?php

			$acumula_monto_uf_bono = 0;
			$acumula_monto_pesos_bono = 0;

			$consulta_jo = 
	            "
	            SELECT
	                ven.monto_ven,
	                CONCAT(pro.nombre_pro,' ', pro.apellido_paterno_pro) as cliente,
	                viv.id_mod,
	                viv.nombre_viv,
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
	                ven_liq.fecha_liq_ven >= '".$fecha_desde_consulta."' AND 
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
           
	        $conexion->consulta_form($consulta_jo,array(isset($fila['id_con'])?$fila['id_con']:0));
	        $fila_consulta_joperaciones = $conexion->extraer_registro();
	        $contador_jo = 0;
	        if(is_array($fila_consulta_joperaciones)){
	            foreach ($fila_consulta_joperaciones as $fila_jo) {
	            	$cliente = utf8_encode($fila_jo["cliente"]);
	            	$nombre_viv = utf8_encode($fila_jo["nombre_viv"]);
	            	$id_ven = $fila_jo["id_ven"];
	            	
	            	$fecha_liq_ven = $fila_jo["fecha_liq_ven"];
	            	$fecha_escritura_ven = $fila_jo["fecha_escritura_ven"];
	            	$id_for_pag = $fila_jo["id_for_pag"];

	            	$ciudad_notaria_ven = $fila_jo["ciudad_notaria_ven"];

	            	$fecha_escritura = date("d/m/Y",strtotime($fecha_escritura_ven));
	            	$fecha_liquidacion = date("d/m/Y",strtotime($fecha_liq_ven));

	            	$rango_dias= (strtotime($fecha_liq_ven)-strtotime($fecha_escritura_ven))/86400;
					$rango_dias = abs($rango_dias); 
					$rango_dias = floor($rango_dias);



					$rango_en_semanas = $rango_dias / 7;
					$rango_en_semanas = round($rango_en_semanas);

					// echo $id_ven."---".$rango_dias."- -".$rango_en_semanas." - ".$ciudad_notaria_ven."<br>";

					// echo $condo." - ".$id_ven." - ".$fecha_liq_ven." - ".$fecha_escritura_ven." - ".$rango_dias." - ".$rango_en_semanas."<br>";

	            	// segun forma de pago va a buscar ciudad, cambiar cuando se cambie lugar CIUDAD
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
		                            monto_bon
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
		                    	$acumula_monto_uf_bono = $acumula_monto_uf_bono + $bono_uf;
		                    	$bono_pesos = $UF_LIQUIDACION * $fila_conbono['monto_bon'];
		                    	$acumula_monto_pesos_bono = $acumula_monto_pesos_bono + $bono_pesos;
		                    	$bono_pesos = number_format($bono_pesos, 0, ',', '.');
		                    	?>
		                    	<tr>
		                    		<td><?php echo $nombre_viv; ?> - <?php echo $id_ven; ?></td>
		                    		<td><?php echo $cliente; ?></td>
		                    		<td>Crédito</td>
		                    		<td><?php echo $fecha_escritura; ?></td>
		                    		<td><?php echo $fecha_liquidacion; ?></td>
		                    		<td><?php echo $rango_en_semanas; ?></td>
		                    		<td><?php echo utf8_encode($fila_conbono['nombre_bon']); ?></td>
		                    		<td><?php echo $bono_uf; ?></td>
		                    		<td><?php echo $bono_pesos; ?></td>
		                    	</tr>
		                    	<?php

		                    }
		                    
			        	} else if($ciudad_cre==2) {
			        		// Si es Santiago
			        		$bono_cat = 6;
			        		$consulta_bono = 
		                        "
		                        SELECT
		                            nombre_bon,
		                            monto_bon
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
		                    	$acumula_monto_uf_bono = $acumula_monto_uf_bono + $bono_uf;
		                    	$bono_pesos = $UF_LIQUIDACION * $fila_conbono['monto_bon'];
		                    	$acumula_monto_pesos_bono = $acumula_monto_pesos_bono + $bono_pesos;
		                    	$bono_pesos = number_format($bono_pesos, 0, ',', '.');
		                    	?>
		                    	<tr>
		                    		<td><?php echo $nombre_viv; ?></td>
		                    		<td><?php echo $cliente; ?></td>
		                    		<td>Crédito</td>
		                    		<td><?php echo $fecha_escritura; ?></td>
		                    		<td><?php echo $fecha_liquidacion; ?></td>
		                    		<td><?php echo $rango_en_semanas; ?></td>
		                    		<td><?php echo utf8_encode($fila_conbono['nombre_bon']); ?></td>
		                    		<td><?php echo $bono_uf; ?></td>
		                    		<td><?php echo $bono_pesos; ?></td>
		                    	</tr>
		                    	<?php

		                    }

			        	} else {
			        		// no cargaron ciudad
			        		?>
	                    	<tr>
	                    		<td><?php echo $nombre_viv; ?></td>
	                    		<td><?php echo $cliente; ?></td>
	                    		<td>Crédito</td>
	                    		<td><?php echo $fecha_escritura; ?></td>
		                    	<td><?php echo $fecha_liquidacion; ?></td>
		                    	<td><?php echo $rango_en_semanas; ?></td>
	                    		<td colspan="3">NO TIENE CARGADA CIUDAD</td>
	                    	</tr>
	                    	<?php
			        	}
			            
	            	} else {
	            		// si es contado
	            		$bono_cat = 7;
	            		$consulta_bono = 
	                        "
	                        SELECT
	                            nombre_bon,
	                            monto_bon
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
	                    	$acumula_monto_uf_bono = $acumula_monto_uf_bono + $bono_uf;
	                    	$bono_pesos = $UF_LIQUIDACION * $fila_conbono['monto_bon'];

	                    	$acumula_monto_pesos_bono = $acumula_monto_pesos_bono + $bono_pesos;

	                    	$bono_pesos = number_format($bono_pesos, 0, ',', '.');
	                    	?>
	                    	<tr>
	                    		<td><?php echo $nombre_viv; ?> - <?php echo $id_ven; ?></td>
	                    		<td><?php echo $cliente; ?></td>
	                    		<td>Contado</td>
	                    		<td><?php echo $fecha_escritura; ?></td>
	                    		<td><?php echo $fecha_liquidacion; ?></td>
	                    		<td><?php echo $rango_en_semanas; ?></td>
	                    		<td><?php echo utf8_encode($fila_conbono['nombre_bon']); ?></td>
	                    		<td><?php echo $bono_uf; ?></td>
	                    		<td><?php echo $bono_pesos; ?></td>
	                    	</tr>
	                    	<?php

	                    }
	            	}
	            	

	            }
	        }


	    ?>
	    		<tr class="success">
                    <td colspan="2"><b>Total a Pagar</b></td>
                    <td colspan="2"><?php echo number_format($acumula_monto_pesos_bono, 0, ',', '.');?>.-</td>
                    <td colspan="5"></td>
                </tr>
	    	</table>
	    </div>


	    <?php

    }
}

if($cantidad_venta_totales > 0){
    ?>
    <div class="col-sm-1" style="padding-top: 20px;">
        <button type="button" class="btn btn-primary pull-center" id="guarda">Cerrar Mes</button>
    </div>	
    <?php
}

?>





<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        $(function () {
          $('[data-toggle="popover"]').popover()
        });

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });


        
    });    
</script>