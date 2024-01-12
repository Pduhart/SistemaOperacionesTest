<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_propietario_panel"])){
    header("Location: ../../panel.php");
}
if(!isset($_POST["valor"])){
	?>
	<script type="text/javascript">
		window.location="../../index.php";
	</script>
	<?php
	exit();
}
include _INCLUDE."class/conexion.php";
?>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Detalle de la Venta</h4>
        </div>
        <div class="modal-body">
            <?php
            $conexion = new conexion();
            $id = $_POST["valor"];
            
            $consulta = 
                "
                SELECT
                    ven.id_pre,
                    ven.id_ven,
                    ven.id_viv,
                    ven.monto_reserva_ven,
                    ven.descuento_manual_ven,
                    ven.descuento_precio_ven,
                    ven.descuento_adicional_ven,
                    ven.descuento_ven,
                    ven.pie_cancelado_ven,
                    ven.pie_cobrar_ven,
                    ven.monto_estacionamiento_ven,
                    ven.monto_bodega_ven,
                    ven.monto_vivienda_ven,
                    ven.monto_vivienda_ingreso_ven,
                    ven.monto_ven,
                    ven.factor_categoria_ven,
                    ven.valor_factor_ven,
                    ven.porcentaje_comision_ven,
                    ven.promesa_porcentaje_comision_reparto_ven,
                    ven.promesa_monto_comision_ven,
                    ven.escritura_porcentaje_comision_reparto_ven,
                    ven.escritura_monto_comision_ven,
                    ven.total_comision_ven,
                    ven.bono_vivienda_ven,
                    ven.porcentaje_bono_precio_ven,
                    ven.promesa_bono_precio_ven,
                    ven.escritura_bono_precio_ven,
                    ven.total_bono_precio_ven,
                    ven.numero_compra_ven,

                    con.nombre_con,
                    tor.nombre_tor,
                    viv.nombre_viv,
                    pro.rut_pro,
                    pro.correo_pro,
                    pro.fono_pro,
                    pro.nombre_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    vend.nombre_vend,
                    vend.apellido_paterno_vend,
                    vend.apellido_materno_vend,
                    ven.fecha_ven,
                    ven.monto_ven,
                    est_ven.nombre_est_ven,
                    ven.id_est_ven,
                    for_pag.nombre_for_pag,
                    pie_abo.nombre_pie_abo_ven,
                    tip_pag.nombre_tip_pag,
                    ven.monto_credito_ven,
                    ven.monto_credito_real_ven,
                    ven.id_for_pag,
                    ven.id_pie_abo_ven,
                    ven.id_ban
                FROM
                    venta_venta AS ven 
                    INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                    INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
                    LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                    INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
                    INNER JOIN venta_pie_abono_venta AS pie_abo ON pie_abo.id_pie_abo_ven = ven.id_pie_abo_ven
                    LEFT JOIN pago_tipo_pago AS tip_pag ON tip_pag.id_tip_pag = ven.id_tip_pag
                WHERE
                    ven.id_ven = ?
                ";
            $conexion->consulta_form($consulta,array($id));
            $fila = $conexion->extraer_registro_unico();
            $id_pre = utf8_encode($fila['id_pre']);
            $id_con = utf8_encode($fila['id_con']);
            $nombre_con = utf8_encode($fila['nombre_con']);
            $id_viv = utf8_encode($fila['id_viv']);
            $nombre_viv = utf8_encode($fila['nombre_viv']);
            $rut_pro = utf8_encode($fila['rut_pro']);
            $id_pro = utf8_encode($fila['id_pro']);
            $nombre_pro = utf8_encode($fila['nombre_pro']);
            $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
            $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
            $correo_pro = utf8_encode($fila['correo_pro']);
            $fono_pro = utf8_encode($fila['fono_pro']);
            $id_tor = utf8_encode($fila['id_tor']);
            $nombre_tor = utf8_encode($fila['nombre_tor']);
            $id_mod = utf8_encode($fila['id_mod']);
            $fecha_ven = date("d-m-Y",strtotime($fila['fecha_ven']));
            $nombre_mod = utf8_encode($fila['nombre_mod']);
            $nombre_vend = utf8_encode($fila['nombre_vend']);
            $apellido_paterno_vend = utf8_encode($fila['apellido_paterno_vend']);
            $apellido_materno_vend = utf8_encode($fila['apellido_materno_vend']);
            $nombre_for_pag = utf8_encode($fila['nombre_for_pag']);
            $nombre_pie_abo_ven = utf8_encode($fila['nombre_pie_abo_ven']);
            $nombre_tip_pag = utf8_encode($fila['nombre_tip_pag']);
            $monto_reserva_ven = number_format($fila['monto_reserva_ven'], 2, ',', '.');
            $descuento_manual_ven = number_format($fila['descuento_manual_ven'], 2, ',', '.');
            $descuento_precio_ven = number_format($fila['descuento_precio_ven'], 2, ',', '.');
            $descuento_adicional_ven = number_format($fila['descuento_adicional_ven'], 2, ',', '.');
            $descuento_ven = number_format($fila['descuento_ven'], 2, ',', '.');
            $pie_cancelado_ven = number_format($fila['pie_cancelado_ven'], 2, ',', '.');
            $pie_cobrar_ven = number_format($fila['pie_cobrar_ven'], 2, ',', '.');
            $monto_estacionamiento_ven = number_format($fila['monto_estacionamiento_ven'], 2, ',', '.');
            $monto_bodega_ven = number_format($fila['monto_bodega_ven'], 2, ',', '.');
            $monto_vivienda_ven = number_format($fila['monto_vivienda_ven'], 2, ',', '.');
            $monto_vivienda_ingreso_ven = number_format($fila['monto_vivienda_ingreso_ven'], 2, ',', '.');
            $monto_ven = number_format($fila['monto_ven'], 2, ',', '.');
            $factor_categoria_ven = number_format($fila['valor_factor_ven'], 2, ',', '.');
            $porcentaje_comision_ven = number_format($fila['porcentaje_comision_ven'], 2, ',', '.');
            $promesa_porcentaje_comision_reparto_ven = number_format($fila['promesa_porcentaje_comision_reparto_ven'], 0, ',', '.');
            $promesa_monto_comision_ven = number_format($fila['promesa_monto_comision_ven'], 2, ',', '.');
            $escritura_porcentaje_comision_reparto_ven = number_format($fila['escritura_porcentaje_comision_reparto_ven'], 0, ',', '.');
            $escritura_monto_comision_ven = number_format($fila['escritura_monto_comision_ven'], 2, ',', '.');
            $total_comision_ven = number_format($fila['total_comision_ven'], 2, ',', '.');
            $bono_vivienda_ven = number_format($fila['bono_vivienda_ven'], 2, ',', '.');
            $porcentaje_bono_precio_ven = number_format($fila['porcentaje_bono_precio_ven'], 2, ',', '.');
            $promesa_bono_precio_ven = number_format($fila['promesa_bono_precio_ven'], 2, ',', '.');
            $escritura_bono_precio_ven = number_format($fila['escritura_bono_precio_ven'], 2, ',', '.');
            $total_bono_precio_ven = number_format($fila['total_bono_precio_ven'], 2, ',', '.');
            $numero_compra_ven = number_format($fila['numero_compra_ven'], 0, ',', '.');
            $monto_credito_ven = number_format($fila['monto_credito_ven'], 2, ',', '.');
            $monto_credito_real_ven = number_format($fila['monto_credito_real_ven'], 2, ',', '.');
            $id_for_pag = $fila['id_for_pag'];
            $id_pie_abo_ven = $fila['id_pie_abo_ven'];
            $id_ban = $fila['id_ban'];

            $total_monto_inmob = ($fila["monto_vivienda_ven"] + $fila["monto_estacionamiento_ven"] + $fila["monto_bodega_ven"]) - $fila["descuento_ven"];
           
            if ($id_pre > 0) {
                $consulta = 
                    "
                    SELECT
                        nombre_pre
                    FROM
                        premio_premio
                    WHERE
                        id_pre = ?
                    ";
                $conexion->consulta_form($consulta,array($id_pre));
                $fila = $conexion->extraer_registro_unico();
                $nombre_pre = utf8_encode($fila['nombre_pre']);
            }

            $consulta_esta = 
                "
                SELECT
                    nombre_esta
                FROM
                    estacionamiento_estacionamiento
                WHERE
                    id_viv = ?
                ";
            $conexion->consulta_form($consulta_esta,array($id_viv));
            $fila_consulta = $conexion->extraer_registro();
            $cantidad = $conexion->total();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                	$nombre_esta .= utf8_encode($fila['nombre_esta'])." - ";
                }
            }

            $consulta_bod = 
                "
                SELECT
                    nombre_bod
                FROM
                    bodega_bodega
                WHERE
                    id_viv = ?
                ";
            $conexion->consulta_form($consulta_bod,array($id_viv));
            $fila_consulta = $conexion->extraer_registro();
            $cantidad = $conexion->total();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                	$nombre_bod .= utf8_encode($fila['nombre_bod'])." - ";
                }
            }

			$nombre_bod = substr($nombre_bod, 0, -2);
			$nombre_esta = substr($nombre_esta, 0, -2);
            
            ?>
            <div class="col-sm-12" style="margin-top:0px;">
                <h4 class="mt-0">Datos generales</h4>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-6"><label class="negrita_detalle">Fecha Venta: </label><label>&nbsp; <?php echo $fecha_ven;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Condominio: </label><label>&nbsp; <?php echo $nombre_con;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Torre: </label><label>&nbsp; <?php echo $nombre_tor;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Departamento: </label><label>&nbsp; <?php echo $nombre_viv;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Estacionamiento: </label><label>&nbsp; <?php echo $nombre_esta;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Bodega: </label><label>&nbsp; <?php echo $nombre_bod;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Rut Cliente: </label><label>&nbsp; <?php echo $rut_pro;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Nombre Cliente: </label><label>&nbsp; <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Correo: </label><label>&nbsp; <?php echo $correo_pro;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Fono: </label><label>&nbsp; <?php echo $fono_pro;?></label></div>
                <div class="col-sm-6"><label class="negrita_detalle">Nombre Vendedor: </label><label>&nbsp; <?php echo $nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend;?></label></div>
            </div>
            
            <div class="col-sm-12" style="margin-top: 10px;">
                <h4 class="mt-0">Datos de la venta</h4>
            </div>
            <div class="col-sm-12 cajas">
            	<?php 
				if ($_SESSION["sesion_perfil_panel"]<>6 && $_SESSION["sesion_perfil_panel"]<>3) { //cuando no es contabilidad
            	 ?>
	                <div class="col-sm-3" style="border: 1px solid #ccc;">
	                    <h5>F. Pago</h5><h5 style="font-weight: bold;"><?php echo $nombre_for_pag;?></h5>
	                </div>
	                <?php
	                if ($id_pre > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Premio</h5><h5 style="font-weight: bold;"><?php echo $nombre_pre;?></h5>
	                    </div>     
	                    <?php
	                }
	                ?>
	                
	                <div class="col-sm-3" style="border: 1px solid #ccc;">
	                    <h5>Dcto Aplica Pie</h5><h5><?php echo $nombre_pie_abo_ven;?></h5>
	                </div>
	                <?php
	                if ($id_tip_pag > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Tipo Pago</h5><h5 style="font-weight: bold;"><?php echo $nombre_tip_pag;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_reserva_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Reserva</h5><h5 style="font-weight: bold;">UF <?php echo $monto_reserva_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($descuento_manual_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Dcto Manual</h5><h5 style="font-weight: bold;">UF <?php echo $descuento_manual_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($descuento_precio_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Dcto Precio</h5><h5 style="font-weight: bold;"><?php echo $descuento_precio_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($descuento_adicional_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Dcto Adicional</h5><h5 style="font-weight: bold;">UF <?php echo $descuento_adicional_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($descuento_ven > 0 && $id_pie_abo_ven == 1) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Abono al Pie</h5><h5 style="font-weight: bold;">UF <?php echo $descuento_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($pie_cancelado_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>PIE Cancelado</h5><h5 style="font-weight: bold;">UF <?php echo $pie_cancelado_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($pie_cobrar_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>PIE A Cobrar</h5><h5 style="font-weight: bold;">UF <?php echo $pie_cobrar_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_estacionamiento_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Estac. Adicional</h5><h5 style="font-weight: bold;"><?php echo $monto_estacionamiento_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_bodega_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Bodega Adicional</h5><h5 style="font-weight: bold;"><?php echo $monto_bodega_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_vivienda_ven > 0 && $id_pie_abo_ven == 1) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Precio Lista Dpto.</h5><h5 style="font-weight: bold;">UF <?php echo $monto_vivienda_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_vivienda_ingreso_ven > 0 && $descuento_manual_ven > 0) {
	                    ?>
	                    <!-- <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Valor Depto Ingresado</h5><h5 style="font-weight: bold;">UF <?php //echo $monto_vivienda_ingreso_ven;?></h5>
	                    </div>    --> 
	                    <?php
	                }
	                if ($monto_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Valor Final Venta</h5><h5 style="font-weight: bold;">UF <?php echo number_format($total_monto_inmob, 2, ',', '.');?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($factor_categoria_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Factor Categoría</h5><h5 style="font-weight: bold;"><?php echo $factor_categoria_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($porcentaje_comision_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>% Comisión</h5><h5 style="font-weight: bold;"><?php echo $porcentaje_comision_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($promesa_porcentaje_comision_reparto_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>% Reparto Promesa</h5><h5 style="font-weight: bold;"><?php echo $promesa_porcentaje_comision_reparto_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($promesa_monto_comision_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Comisión Promesa</h5><h5 style="font-weight: bold;"><?php echo $promesa_monto_comision_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($escritura_porcentaje_comision_reparto_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>% Reparto Escritura</h5><h5 style="font-weight: bold;"><?php echo $escritura_porcentaje_comision_reparto_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($escritura_monto_comision_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Comisión Escritura</h5><h5 style="font-weight: bold;"><?php echo $escritura_monto_comision_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($total_comision_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Total Comisión</h5><h5 style="font-weight: bold;"><?php echo $total_comision_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($bono_vivienda_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Bono Vivienda</h5><h5 style="font-weight: bold;"><?php echo $bono_vivienda_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($porcentaje_bono_precio_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>% Bono Precio</h5><h5 style="font-weight: bold;"><?php echo $porcentaje_bono_precio_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($promesa_bono_precio_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Promesa Bono Precio</h5><h5 style="font-weight: bold;"><?php echo $promesa_bono_precio_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                 if ($escritura_bono_precio_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Escritura Bono Precio</h5><h5 style="font-weight: bold;"><?php echo $escritura_bono_precio_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($total_bono_precio_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Total Bono Precio</h5><h5 style="font-weight: bold;"><?php echo $total_bono_precio_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_credito_ven > 0 && $monto_credito_real_ven == 0 && $id_for_pag == 1) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Crédito Tentativo</h5><h5 style="font-weight: bold;"><?php echo $monto_credito_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_credito_real_ven > 0 && $id_for_pag == 1) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Crédito Final</h5><h5 style="font-weight: bold;"><?php echo $monto_credito_real_ven;?></h5>
	                    </div>    
	                    <?php
	                }
					if ($id_ban > 0) {
						$consulta_banco = 
			                "
			                SELECT
			                    nombre_ban
			                FROM
			                    banco_banco
			                WHERE
			                    id_ban = ?
			                ";
			            $conexion->consulta_form($consulta_banco,array($id_ban));
			            $fila_ban = $conexion->extraer_registro_unico();
			            $nombre_ban = utf8_encode($fila_ban['nombre_ban']);

	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Banco Crédito</h5><h5 style="font-weight: bold;"><?php echo $nombre_ban;?></h5>
	                    </div>    
	                    <?php
	                }
				} else {
				?>
				<div class="col-sm-3" style="border: 1px solid #ccc;">
                    <h5>F. Pago</h5><h5 style="font-weight: bold;"><?php echo $nombre_for_pag;?></h5>
                </div>
				
				<?php
					if ($monto_reserva_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Monto Reserva</h5><h5 style="font-weight: bold;">UF <?php echo $monto_reserva_ven;?></h5>
	                    </div>    
	                    <?php
	                }
	                if ($monto_ven > 0) {
	                    ?>
	                    <div class="col-sm-3" style="border: 1px solid #ccc;">
	                        <h5>Valor Final Venta</h5><h5 style="font-weight: bold;">UF <?php echo number_format($total_monto_inmob, 2, ',', '.');?></h5>
	                    </div>    
	                    <?php
	                }
				}
                ?>
                <!-- <div class="col-sm-3" style="border: 1px solid #ccc;">
                    <h5>N° Bien Inscrito</h5><h5 style="font-weight: bold;"><?php //echo $numero_compra_ven;?></h5>
                </div> -->
            </div>

            <br></br></br></br>

            <div class="col-sm-12" style="margin-top: 20px;">
                <h4>Desistimiento</h4>
            </div>
                
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = 
                        "
                        SELECT
                            des.fecha_des_ven,
                            des.descripcion_des_ven,
                            tip_des.nombre_tip_des
                        FROM
                            venta_desestimiento_venta AS des,
                            desistimiento_tipo_desistimiento AS tip_des
                        WHERE
                            des.id_ven = " . $id . " AND 
                            des.id_tip_des = tip_des.id_tip_des
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $fecha_des_ven = utf8_encode($fila["fecha_des_ven"]);
                            $descripcion_des_ven = utf8_encode($fila["descripcion_des_ven"]);
                            $nombre_tip_des = utf8_encode($fila["nombre_tip_des"]);
                            ?>
                            <tr>
                                <td><?php echo date("d-m-Y",strtotime($fecha_des_ven));?></td>
                                <td><?php echo $descripcion_des_ven;?></td>
                                <td><?php echo $nombre_tip_des;?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
