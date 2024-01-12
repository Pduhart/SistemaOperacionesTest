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

<div class="modal-dialog modal-center">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Detalle </h4>
        </div>
        <div class="modal-body" id="imprime">
            <?php
            $conexion = new conexion();
            $id = $_POST["valor"];
            
            $consulta = 
                "
                SELECT
                    con.nombre_con,
                    con.id_con,
                    viv.id_viv,
                    viv.nombre_viv,
                    can_cot.id_can_cot,
                    can_cot.nombre_can_cot,
                    tor.id_tor,
                    tor.nombre_tor,
                    mode.id_mod,
                    mode.nombre_mod,
                    pro.rut_pro,
                    pro.id_pro,
                    pro.nombre_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    com.nombre_com,
                    reg.descripcion_reg,
                    nac.nombre_nac,
                    pro.correo_pro,
                    pro.fono_pro,
                    cot.fecha_cot,
                    pre_cot.id_pre_cot,
                	pre_cot.nombre_pre_cot
                FROM
                    cotizacion_cotizacion AS cot 
                    INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                    INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                    INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                    INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                	INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                	INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                    INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                    INNER JOIN cotizacion_preaprobacion_cotizacion AS pre_cot ON pre_cot.id_pre_cot = cot.id_pre_cot
                WHERE
                    cot.id_cot = ?
                ";
            $conexion->consulta_form($consulta,array($id));
            $fila = $conexion->extraer_registro_unico();
            $id_con = utf8_encode($fila['id_con']);
            $nombre_con = utf8_encode($fila['nombre_con']);
            $id_viv = utf8_encode($fila['id_viv']);
            $nombre_viv = utf8_encode($fila['nombre_viv']);
            $id_can_cot = utf8_encode($fila['id_can_cot']);
            $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
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
            $nombre_mod = utf8_encode($fila['nombre_mod']);
            $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));
            $id_vend = $fila['id_vend'];
            $id_pre_cot = utf8_encode($fila['id_pre_cot']);
        	$nombre_pre_cot = utf8_encode($fila['nombre_pre_cot']);
        	$nombre_com = utf8_encode($fila['nombre_com']);
        	$descripcion_reg = utf8_encode($fila['descripcion_reg']);
        	$nombre_nac = utf8_encode($fila['nombre_nac']);
            
            ?>
            <h5>Datos generales</h5>
            <label class="negrita_detalle">Condominio: </label><label>&nbsp; <?php echo $nombre_con;?></label></br>
            <label class="negrita_detalle">Torre: </label><label>&nbsp; <?php echo $nombre_tor;?></label></br>
            <label class="negrita_detalle">Depto: </label><label>&nbsp; <?php echo $nombre_viv;?></label></br>
            <label class="negrita_detalle">Canal: </label><label>&nbsp; <?php echo $nombre_can_cot;?></label></br>
            <label class="negrita_detalle">Pre Aprobación Crédito: </label><label>&nbsp; <?php echo $nombre_pre_cot;?></label></br>
            <label class="negrita_detalle">Rut Cliente: </label><label>&nbsp; <?php echo $rut_pro;?></label></br>
            <label class="negrita_detalle">Nombre Cliente: </label><label>&nbsp; <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></label></br>
            <label class="negrita_detalle">Correo: </label><label>&nbsp; <?php echo $correo_pro;?></label></br>
            <label class="negrita_detalle">Fono: </label><label>&nbsp; <?php echo $fono_pro;?></label></br>
            <label class="negrita_detalle">Nacionalidad: </label><label>&nbsp; <?php echo $nombre_nac;?></label></br>
            <label class="negrita_detalle">Región / Comuna: </label><label>&nbsp; <?php echo $descripcion_reg;?> / <?php echo $nombre_com;?></label></br>
            
            <?php 
			$consulta_doc = 
                "
                SELECT
                    doc_pro.nombre_doc_pro
                FROM
                    documento_propietario_documento AS doc_pro
                WHERE
                    doc_pro.id_pro = " . $id_pro . "
                ";
            $conexion->consulta($consulta_doc);
            $cantidad_docs = $conexion->total();
            $fila_consulta = $conexion->extraer_registro();
            if ($cantidad_docs>0) {
            	?>
            	<h5 style="font-weight: bold; text-decoration: underline;">Adjuntos</h5>
            	<?php 
				if(is_array($fila_consulta)){
                	foreach ($fila_consulta as $fila) {
            	 	?>
            		- <a href="../../../archivo/propietario/documento/<?php echo $id_pro; ?>/<?php echo utf8_encode($fila['nombre_doc_pro']); ?>" target="_blank"><?php echo utf8_encode($fila['nombre_doc_pro']); ?></a><br>
            		<?php 
					}
				}
			}
             ?>
            <br>
            
            <br>
            <h5 style="font-weight: bold; text-decoration: underline;">Seguimientos</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>ID Cot.</th>
                        <th>Vendedor</th>
                        <th>Fecha</th>
                        <th>Interés</th>
                        <th>Medio</th>
                        <th>Pre aprobación Cré.</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // $contador = 1;
                    // $consulta = 
                    //     "
                    //     SELECT
                    //         seg.fecha_seg_cot,
                    //         seg.descripcion_seg_cot,
                    //         inte.nombre_seg_int_cot,
                    //         med.nombre_med_cot
                    //     FROM
                    //         cotizacion_seguimiento_cotizacion AS seg 
                    //         INNER JOIN cotizacion_seguimiento_interes_cotizacion AS inte ON inte.id_seg_int_cot = seg.id_seg_int_cot
                    //         INNER JOIN cotizacion_medio_cotizacion AS med ON med.id_med_cot = seg.id_med_cot
                    //     WHERE
                    //         seg.id_cot = " . $id . "
                    //     ";
                    // $conexion->consulta_form($consulta,array($id));
                    // $fila_consulta = $conexion->extraer_registro();
                    // $cantidad = $conexion->total();
                    // if(is_array($fila_consulta)){
                    //     foreach ($fila_consulta as $fila) {
                    //         $nombre_seg_int_cot = utf8_encode($fila["nombre_seg_int_cot"]);
                    //         $nombre_med_cot = utf8_encode($fila["nombre_med_cot"]);
                    //         $descripcion_seg_cot = utf8_encode($fila["descripcion_seg_cot"]);
                    //         $fecha_seg_cot = date("d-m-Y H:i",strtotime($fila["fecha_seg_cot"]));
                            ?>
                            <!-- <tr>
                                <td><?php //echo $contador; ?></td>
                                <td><?php //echo $fecha_seg_cot; ?></td>
                                <td><?php //echo $nombre_seg_int_cot;?></td>
                                <td><?php //echo $nombre_med_cot;?></td>
                                <td><?php //echo $descripcion_seg_cot;?></td>
                            </tr> -->
                            <?php
                    //     }
                    // }
                ?>
                <?php 
                // nuevo
                $contador = 1;
				$consulta_cotizaciones = 
                        "
                        SELECT
                            cot.id_cot,
                            vend.nombre_vend,
                            vend.apellido_paterno_vend
                        FROM
                            cotizacion_cotizacion AS cot,
                            vendedor_vendedor AS vend
                        WHERE
                            cot.id_pro = " . $id_pro . " AND
                            cot.id_vend = vend.id_vend
                        ORDER BY
                        	cot.fecha_cot ASC
                        ";
                    $conexion->consulta($consulta_cotizaciones);
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila_cot) {
                        	$id_cot = $fila_cot["id_cot"];
                        	$nombre_vend = utf8_encode($fila_cot["nombre_vend"]." ".$fila_cot["apellido_paterno_vend"]);
		                    $consulta = 
		                        "
		                        SELECT
		                            seg.fecha_seg_cot,
		                            seg.descripcion_seg_cot,
		                            inte.nombre_seg_int_cot,
		                            med.nombre_med_cot,
		                            pre.nombre_pre_cot
		                        FROM
		                            cotizacion_seguimiento_cotizacion AS seg 
		                            INNER JOIN cotizacion_seguimiento_interes_cotizacion AS inte ON inte.id_seg_int_cot = seg.id_seg_int_cot
		                            INNER JOIN cotizacion_medio_cotizacion AS med ON med.id_med_cot = seg.id_med_cot
		                            INNER JOIN cotizacion_preaprobacion_cotizacion AS pre ON pre.id_pre_cot = seg.id_pre_cot
		                        WHERE
		                            seg.id_cot = " . $id_cot . "
		                        ORDER BY
		                        	seg.fecha_seg_cot DESC
		                        ";
		                    $conexion->consulta_form($consulta,array($id));
		                    $fila_consulta = $conexion->extraer_registro();
		                    $cantidad = $conexion->total();
		                    if(is_array($fila_consulta)){
		                        foreach ($fila_consulta as $fila) {
		                            $nombre_seg_int_cot = utf8_encode($fila["nombre_seg_int_cot"]);
		                            $nombre_med_cot = utf8_encode($fila["nombre_med_cot"]);
		                            $nombre_pre_cot = utf8_encode($fila["nombre_pre_cot"]);
		                            $descripcion_seg_cot = utf8_encode($fila["descripcion_seg_cot"]);
		                            $fecha_seg_cot = date("d-m-Y H:i",strtotime($fila["fecha_seg_cot"]));
		                            ?>
		                            <tr>
		                                <td><?php echo $contador; ?></td>
		                                <td><?php echo $id_cot; ?></td>
		                                <td><?php echo $nombre_vend; ?></td>
		                                <td><?php echo $fecha_seg_cot; ?></td>
		                                <td><?php echo $nombre_seg_int_cot;?></td>
		                                <td><?php echo $nombre_med_cot;?></td>
		                                <td><?php echo $nombre_pre_cot;?></td>
		                                <td><?php echo $descripcion_seg_cot;?></td>
		                            </tr>
		                            <?php
		                            $contador++;
		                        }
		                    }
                        }
                    }
			
                 ?>
                </tbody>
            </table>

            <br>
            <h5 style="font-weight: bold; text-decoration: underline;">Eventos</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>fecha</th>
                        <th>Hora</th>
                        <th>Nombre</th>
                        <th>descripción</th>
                        <th>Estado</th>
                        <th>Vendedor</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                // nuevo
                $contador = 1;
				$consulta_eventos = 
                    "
                    SELECT
                    	eve.id_eve,
                    	eve.nombre_eve,
                    	eve.descripcion_eve,
                    	eve.fecha_eve,
                    	eve.time_eve,
                    	eve.id_est_eve,
                    	eve.id_usu,
                    	eve_est.nombre_est_eve,
                        vend.nombre_vend,
                        vend.apellido_paterno_vend
                    FROM
                    	evento_evento AS eve,
                    	cotizacion_cotizacion AS cot,
                        vendedor_vendedor AS vend,
                        evento_estado_evento AS eve_est
                    WHERE
                        eve.id_cot = " . $id . " AND
                        eve.id_usu = vend.id_usu AND
                        eve.id_cot = cot.id_cot AND
                        eve.id_est_eve = eve_est.id_est_eve
                    ORDER BY
                    	eve.fecha_eve ASC, eve.time_eve ASC
                    ";
                $conexion->consulta($consulta_eventos);
                $fila_consulta = $conexion->extraer_registro();
                $cantidad = $conexion->total();
                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila_eve) {
                    	$id_est_eve = $fila_eve["id_est_eve"];
                    	$nombre_est_eve = utf8_encode($fila_eve["nombre_est_eve"]);
                    	$nombre_vend = utf8_encode($fila_eve["nombre_vend"]." ".$fila_eve["apellido_paterno_vend"]);
	                	$time_eve = utf8_encode($fila_eve["time_eve"]);
	                    $nombre_eve = utf8_encode($fila_eve["nombre_eve"]);
	                    $descripcion_eve = utf8_encode($fila_eve["descripcion_eve"]);
	                    $fecha_eve = date("d-m-Y",strtotime($fila_eve["fecha_eve"]));
                        ?>
                        <tr>
                            <td><?php echo $fecha_eve; ?></td>
                            <td><?php echo $time_eve; ?></td>
                            <td><?php echo $nombre_eve; ?></td>
                            <td><?php echo $descripcion_eve; ?></td>
                            <td><?php echo $nombre_est_eve;?></td>
                            <td><?php echo $nombre_vend;?></td>
                        </tr>
                        <?php
                        $contador++;
	                }
                }
			
                 ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
        	<button type="button" class="btn btn-default" style="float: left" onclick="javascript:CallPrint('imprime')">Imprimir</button>
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    function CallPrint(strid) {
        var prtContent = document.getElementById(strid);
        var WinPrint = window.open('', '', 'letf=0,top=0,width=600,height=400,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write('<style type="text/css">table{border:1px solid #000000;width:100%;border-collapse:collapse}table td{border:1px solid #999999}</style>');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }
</script>