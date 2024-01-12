<?php 
require "../../config.php";
include "../../class/conexion.php";
include "../../parametros.php";
$conexion = new conexion();

$nombre = 'Listado_cotrol_'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");

function nombre_depto($id_viv){
	$conexion = new conexion();
	$consulta_nombre_viv = 
	    "
	    SELECT
	        nombre_viv
	    FROM
	        vivienda_vivienda
	    WHERE
	        id_viv = ".$id_viv."
	    ";
    $conexion->consulta($consulta_nombre_viv);
    $fila = $conexion->extraer_registro_unico();
    $nombre_viv = $fila['nombre_viv'];

    return $nombre_viv;
}


function nombre_propietario($id_pro){
	$conexion = new conexion();
	$consulta_nombre_pro = 
	    "
	    SELECT
	        nombre_pro,
	        apellido_paterno_pro
	    FROM
	        propietario_propietario
	    WHERE
	        id_pro = ".$id_pro."
	    ";
    $conexion->consulta($consulta_nombre_pro);
    $fila = $conexion->extraer_registro_unico();
    $nombre_pro = utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']);

    return $nombre_pro;
}

function rut_propietario($id_pro){
	$conexion = new conexion();
	$consulta_nombre_pro = 
	    "
	    SELECT
	        rut_pro
	    FROM
	        propietario_propietario
	    WHERE
	        id_pro = ".$id_pro."
	    ";
    $conexion->consulta($consulta_nombre_pro);
    $fila = $conexion->extraer_registro_unico();
    $rut_pro = utf8_encode($fila['rut_pro']);

    return $rut_pro;
}
 ?>
<meta charset="utf-8">

<a href="listados_control_grafico_exc.php" target="_blank">Exportar a Excel</a>

<table style="width: 100%">
	<tr>
		<td>N°</td>
		<td>Tipo</td>
		<td>Condominio</td>
		<td>Cod. Venta</td>
		<td>Depto</td>
		<td>Propietario</td>
		<td>RUT</td>
	</tr>
<?php
    $consulta_con = 
	    "
	    SELECT
	        DISTINCT con.alias_con,
	        con.id_con,
	        con.nombre_con
	    FROM
	        condominio_condominio as con,
	        vivienda_vivienda as viv,
	        torre_torre as tor
	    WHERE
	        con.id_est_con = 1 AND
	        viv.id_tor = tor.id_tor AND
	        tor.id_con = con.id_con AND
	        con.id_con <> 1
	    ";
	$conexion->consulta($consulta_con);
	$cantidad_condo = $conexion->total();
	$conta_condo = 0;
	$fila_consulta_condo = $conexion->extraer_registro();
	if(is_array($fila_consulta_condo)){
	    foreach ($fila_consulta_condo as $fila_condo) {
	    	// promesas
	    	$consulta_ventas_promesa_condo = 
			  "
			  SELECT 
			    ven.id_ven,
			    ven.id_viv,
			    ven.id_pro
			  FROM
			    venta_venta ven,
	        	vivienda_vivienda as viv,
	        	torre_torre as tor
			  WHERE
			    (ven.id_est_ven <> 3) AND
				ven.id_viv = viv.id_viv AND
				viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$fila_condo["id_con"]." AND NOT
                EXISTS(
                    SELECT 
                        ven_eta.id_ven
                    FROM
                        venta_etapa_venta AS ven_eta
                    WHERE
                        ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND ven_eta.id_est_eta_ven=1) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND ven_eta.id_est_eta_ven=1))
                )
			  ";
			$conexion->consulta($consulta_ventas_promesa_condo);
			$total_ventas_promesa_condo = $conexion->total();
			$conta_prom = 1;
			$fila_consulta_prom = $conexion->extraer_registro();
			if(is_array($fila_consulta_prom)){
			    foreach ($fila_consulta_prom as $fila_prom) {
			        $id_ven = $fila_prom["id_ven"];
			        $id_viv = $fila_prom["id_viv"];
			        $id_pro = $fila_prom["id_pro"];
			        ?>
			        <tr>
			        	<td><?php echo $conta_prom; ?></td>
			        	<td>Promesas</td>
			        	<td><?php echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php echo $id_ven; ?></td>
			        	<td><?php echo nombre_depto($id_viv); ?></td>
			        	<td><?php echo nombre_propietario($id_pro); ?></td>
			        	<td><?php echo rut_propietario($id_pro); ?></td>
			        </tr>
			        <?php
			        $conta_prom++;
			    }
			}


			// ventas
	    	$consulta_ventas_escri_condo = 
			  "
			  SELECT 
			    ven.id_ven,
			    ven.id_viv,
			    ven.id_pro
			  FROM
			    venta_venta ven,
	        	vivienda_vivienda as viv,
	        	torre_torre as tor
			  WHERE
			    (ven.id_est_ven = 6 or ven.id_est_ven = 7) AND
				ven.id_viv = viv.id_viv AND
				viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$fila_condo["id_con"]."
			  ";
			// ventas en proceso de escrituración
			 
			$consulta_ventas_escri_condo = 
			  "
			  SELECT 
			    ven.id_ven,
			    ven.id_viv,
			    ven.id_pro
			  FROM
			    venta_venta ven,
	        	vivienda_vivienda as viv,
	        	torre_torre as tor
			  WHERE
			    (ven.id_est_ven > 3) AND
				ven.id_viv = viv.id_viv AND
				viv.id_tor = tor.id_tor AND
			    tor.id_con = ".$fila_condo["id_con"]." AND 
                EXISTS(
                    SELECT 
                        ven_eta.id_ven
                    FROM
                        venta_etapa_venta AS ven_eta
                    WHERE
                        ven_eta.id_ven = ven.id_ven AND ((ven_eta.id_eta=".$n_etaco_segunda_eta." AND (ven_eta.id_est_eta_ven=1)) OR (ven_eta.id_eta=".$n_etacr_segunda_eta." AND (ven_eta.id_est_eta_ven=1)))
                ) AND 
                NOT EXISTS(
                    SELECT 
                        ven_liq.id_ven
                    FROM
                        venta_liquidado_venta AS ven_liq
                    WHERE
                        ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                )
			  ";
			$conexion->consulta($consulta_ventas_escri_condo);
			$total_ventas_escri_condo = $conexion->total();
			$conta_escr = 1;
			$fila_consulta_escri = $conexion->extraer_registro();
			if(is_array($fila_consulta_escri)){
			    foreach ($fila_consulta_escri as $fila_escri) {
			        $id_ven = $fila_escri["id_ven"];
			        $id_viv = $fila_escri["id_viv"];
			        $id_pro = $fila_escri["id_pro"];
			        ?>
			        <tr>
			        	<td><?php echo $conta_escr; ?></td>
			        	<td>Escrituras</td>
			        	<td><?php echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php echo $id_ven; ?></td>
			        	<td><?php echo nombre_depto($id_viv); ?></td>
			        	<td><?php echo nombre_propietario($id_pro); ?></td>
			        	<td><?php echo rut_propietario($id_pro); ?></td>
			        </tr>
			        <?php
			        $conta_escr++;
			    }
			}

			// ventas y con liquidación credito
			$consulta_ventas_liqui_condo_cr = 
			  "
			  SELECT 
			    ven.id_ven,
			    ven.id_viv,
			    ven.id_pro
			  FROM
			    venta_venta ven,
	        	vivienda_vivienda as viv,
	        	torre_torre as tor
			  WHERE
			    (ven.id_est_ven > 3) AND
				ven.id_viv = viv.id_viv AND
				viv.id_tor = tor.id_tor AND
				ven.id_for_pag = 1 AND
			    tor.id_con = ".$fila_condo["id_con"]." AND 
                EXISTS(
                    SELECT 
                        ven_eta.id_ven
                    FROM
                        venta_etapa_venta AS ven_eta
                    WHERE
                        ven_eta.id_ven = ven.id_ven AND (ven_eta.id_eta=".$n_etacr_min_etapa_liquidacion." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1))
                ) AND 
                EXISTS(
                    SELECT 
                        ven_liq.id_ven
                    FROM
                        venta_liquidado_venta AS ven_liq
                    WHERE
                        ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                )
			  ";
			$conexion->consulta($consulta_ventas_liqui_condo_cr);
			$total_ventas_liqui_condo_cr = $conexion->total();
			$conta_liq_cre = 1;
			$fila_consulta_lic_cre = $conexion->extraer_registro();
			if(is_array($fila_consulta_lic_cre)){
			    foreach ($fila_consulta_lic_cre as $fila_lic_cre) {
			        $id_ven = $fila_lic_cre["id_ven"];
			        $id_viv = $fila_lic_cre["id_viv"];
			        $id_pro = $fila_lic_cre["id_pro"];
			        ?>
			        <tr>
			        	<td><?php echo $conta_liq_cre; ?></td>
			        	<td>Liquidadas Crédito</td>
			        	<td><?php echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php echo $id_ven; ?></td>
			        	<td><?php echo nombre_depto($id_viv); ?></td>
			        	<td><?php echo nombre_propietario($id_pro); ?></td>
			        	<td><?php echo rut_propietario($id_pro); ?></td>
			        </tr>
			        <?php
			        $conta_liq_cre++;
			    }
			}

			$consulta_ventas_liqui_condo_co = 
			  "
			  SELECT 
			    ven.id_ven,
			    ven.id_viv,
			    ven.id_pro
			  FROM
			    venta_venta ven,
	        	vivienda_vivienda as viv,
	        	torre_torre as tor
			  WHERE
			    (ven.id_est_ven > 3) AND
				ven.id_viv = viv.id_viv AND
				viv.id_tor = tor.id_tor AND
				ven.id_for_pag = 2 AND
			    tor.id_con = ".$fila_condo["id_con"]." AND 
                EXISTS(
                    SELECT 
                        ven_eta.id_ven
                    FROM
                        venta_etapa_venta AS ven_eta
                    WHERE
                        ven_eta.id_ven = ven.id_ven AND (ven_eta.id_eta=".$n_etaco_saldo_inmob." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1) OR ven_eta.id_eta=".$n_etaco_copia_esc." AND (ven_eta.id_est_eta_ven=2 OR ven_eta.id_est_eta_ven=1))
                ) AND 
                EXISTS(
                    SELECT 
                        ven_liq.id_ven
                    FROM
                        venta_liquidado_venta AS ven_liq
                    WHERE
                        ven_liq.id_ven = ven.id_ven AND ven_liq.fecha_liq_ven <> ''
                )
			  ";
			$conexion->consulta($consulta_ventas_liqui_condo_co);
			$total_ventas_liqui_condo_co = $conexion->total();
			$conta_liq_con = 1;
			$fila_consulta_lic_cont = $conexion->extraer_registro();
			if(is_array($fila_consulta_lic_cont)){
			    foreach ($fila_consulta_lic_cont as $fila_lic_cont) {
			        $id_ven = $fila_lic_cont["id_ven"];
			        $id_viv = $fila_lic_cont["id_viv"];
			        $id_pro = $fila_lic_cont["id_pro"];
			        ?>
			        <tr>
			        	<td><?php echo $conta_liq_con; ?></td>
			        	<td>Liquidadas Contado</td>
			        	<td><?php echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php echo $id_ven; ?></td>
			        	<td><?php echo nombre_depto($id_viv); ?></td>
			        	<td><?php echo nombre_propietario($id_pro); ?></td>
			        	<td><?php echo rut_propietario($id_pro); ?></td>
			        </tr>
			        <?php
			        $conta_liq_con++;
			    }
			}

			// desistimiento
	  //   	$consulta_ventas_desis_condo = 
			//   "
			//   SELECT 
			//     ven.id_ven,
			//     ven.id_viv,
			//     ven.id_pro
			//   FROM
			//     venta_venta ven,
	  //       	vivienda_vivienda as viv,
	  //       	torre_torre as tor
			//   WHERE
			//     ven.id_est_ven = 3 AND
			// 	ven.id_viv = viv.id_viv AND
			// 	viv.id_tor = tor.id_tor AND
			//     tor.id_con = ".$fila_condo["id_con"]."
			//   ";
			// $conexion->consulta($consulta_ventas_desis_condo);
			// $total_ventas_desis_condo = $conexion->total();
			// $conta_disis = 1;
			// $fila_consulta_desis = $conexion->extraer_registro();
			// if(is_array($fila_consulta_desis)){
			//     foreach ($fila_consulta_desis as $fila_desis) {
			//         $id_ven = $fila_desis["id_ven"];
			//         $id_viv = $fila_desis["id_viv"];
			//         $id_pro = $fila_desis["id_pro"];
			        ?>
			       <!--  <tr>
			        	<td><?php //echo $conta_disis; ?></td>
			        	<td>Desistimientos</td>
			        	<td><?php //echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php //echo $id_ven; ?></td>
			        	<td><?php //echo nombre_depto($id_viv); ?></td>
			        	<td><?php //echo nombre_propietario($id_pro); ?></td>
			        </tr> -->
			        <?php
			//         $conta_disis++;
			//     }
			// }

			// disponibles
			$consulta_disp_condo = 
                "
                SELECT
                    viv.id_viv
                FROM
                    torre_torre AS tor
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
                WHERE
                    tor.id_con = ? AND
                    viv.id_est_viv = 1 AND NOT
                    EXISTS(
                        SELECT 
                            ven.id_ven
                        FROM
                            venta_venta AS ven
                        WHERE
                            ven.id_viv = viv.id_viv AND 
                            ven.id_est_ven <> 3
                    )
                ";
            $conexion->consulta_form($consulta_disp_condo,array($fila_condo["id_con"]));
            $total_vivienda_disponible_condo = $conexion->total();
            $conta_disp = 1;
			$fila_consulta_desis = $conexion->extraer_registro();
			if(is_array($fila_consulta_desis)){
			    foreach ($fila_consulta_desis as $fila_desis) {
			        $id_ven = $fila_desis["id_ven"];
			        $id_viv = $fila_desis["id_viv"];
			        $id_pro = $fila_desis["id_pro"];
			        ?>
			        <tr>
			        	<td><?php echo $conta_disp; ?></td>
			        	<td>Disponibles</td>
			        	<td><?php echo utf8_encode($fila_condo["nombre_con"]); ?></td>
			        	<td><?php echo $id_ven; ?></td>
			        	<td><?php echo nombre_depto($id_viv); ?></td>
			        	<td>--</td>
			        	<td>--</td>
			        </tr>
			        <?php
			        $conta_disp++;
			    }
			}	        
	    }
	}
    ?>
</table>