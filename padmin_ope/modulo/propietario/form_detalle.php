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
        <div class="modal-body">
            <?php
            $conexion = new conexion();
            $id = $_POST["valor"];
            
            $consulta = 
                "
                SELECT 
                    pro.id_pro,
                    pro.nombre_pro,
                    pro.nombre2_pro,
                    pro.apellido_paterno_pro,
                    pro.apellido_materno_pro,
                    pro.rut_pro,
                    pro.pasaporte_pro,
                    pro.fono_pro,
                    pro.fono2_pro,
                    pro.direccion_pro,
                    pro.direccion_trabajo_pro,
                    pro.correo_pro,
                    pro.correo2_pro,
                    nac.nombre_nac,
                    com.nombre_com,
                    reg.descripcion_reg,
                    sex.nombre_sex,
                    civ.nombre_civ,
                    est.nombre_est,
                    prof.nombre_prof
                FROM 
                    propietario_propietario AS pro
                    INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                    INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                    INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                    INNER JOIN sexo_sexo  AS sex ON sex.id_sex = pro.id_sex
                    INNER JOIN civil_civil  AS civ ON civ.id_civ = pro.id_civ
                    LEFT JOIN estudio_estudio  AS est ON est.id_est = pro.id_est
                    LEFT JOIN profesion_profesion  AS prof ON prof.id_prof = pro.id_prof
                    
                WHERE 
                    pro.id_pro = ?
                ";
            $conexion->consulta_form($consulta,array($id));
            $fila = $conexion->extraer_registro_unico();
            $nombre_pro = utf8_encode($fila['nombre_pro']." ".$fila['nombre2_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);
            $rut_pro = utf8_encode($fila['rut_pro']);
            $pasaporte_pro = utf8_encode($fila['pasaporte_pro']);
            $fono_pro = utf8_encode($fila['fono_pro']);
            $fono2_pro = utf8_encode($fila['fono2_pro']);
            $direccion_pro = utf8_encode($fila['direccion_pro']);
            $direccion_trabajo_pro = utf8_encode($fila['direccion_trabajo_pro']);
            $correo_pro = utf8_encode($fila['correo_pro']);
            $correo2_pro = utf8_encode($fila['correo2_pro']);

            $nombre_com = utf8_encode($fila['nombre_com']);
            $descripcion_reg = utf8_encode($fila['descripcion_reg']);
            $nombre_nac = utf8_encode($fila['nombre_nac']);
            
            $nombre_sex = utf8_encode($fila['nombre_sex']);
            $nombre_civ = utf8_encode($fila['nombre_civ']);
            $nombre_est = utf8_encode($fila['nombre_est']);
            $nombre_prof = utf8_encode($fila['nombre_prof']);
            
            ?>
            <h5>Datos generales</h5>
            <label class="negrita_detalle">Nombre: </label><label>&nbsp; <?php echo $nombre_pro;?></label></br>
            <label class="negrita_detalle">Rut: </label><label>&nbsp; <?php echo $rut_pro;?></label></br>
            <label class="negrita_detalle">Nacionalidad: </label><label>&nbsp; <?php echo $nombre_nac;?></label></br>
            <label class="negrita_detalle">Región: </label><label>&nbsp; <?php echo $descripcion_reg;?></label></br>
            <label class="negrita_detalle">Comuna: </label><label>&nbsp; <?php echo $nombre_com;?></label></br>
            <label class="negrita_detalle">Sexo: </label><label>&nbsp; <?php echo $nombre_sex;?></label></br>
            <label class="negrita_detalle">Estado Civil: </label><label>&nbsp; <?php echo $nombre_civ;?></label></br>
            <label class="negrita_detalle">Estudio: </label><label>&nbsp; <?php echo $nombre_est;?></label></br>
            <label class="negrita_detalle">Fono: </label><label>&nbsp; <?php echo $fono_pro;?></label></br>
            <label class="negrita_detalle">Fono 2: </label><label>&nbsp; <?php echo $fono2_pro;?></label></br>
            <label class="negrita_detalle">Dirección: </label><label>&nbsp; <?php echo $direccion_pro;?></label></br>
            <label class="negrita_detalle">Dirección Trabajo: </label><label>&nbsp; <?php echo $direccion_trabajo_pro;?></label></br>
            <label class="negrita_detalle">Correo: </label><label>&nbsp; <?php echo $correo_pro;?></label></br>
            <label class="negrita_detalle">Correo 2: </label><label>&nbsp; <?php echo $correo2_pro;?></label></br>
            
            <?php 
			$consulta_doc = 
                "
                SELECT
                    doc_pro.nombre_doc_pro
                FROM
                    documento_propietario_documento AS doc_pro
                WHERE
                    doc_pro.id_pro = " . $id . "
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
            		- <a href="../../../archivo/propietario/documento/<?php echo $id; ?>/<?php echo utf8_encode($fila['nombre_doc_pro']); ?>" target="_blank"><?php echo utf8_encode($fila['nombre_doc_pro']); ?></a><br>
            		<?php 
					}
				}
			}
             ?>
            <br>
            <h5 style="font-weight: bold; text-decoration: underline;">Departamentos</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Condominio</th>
                        <th>Torre</th>
                        <th>Depto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    $consulta = 
                        "
                        SELECT
                            con.nombre_con,
                            tor.nombre_tor,
                            viv.nombre_viv
                        FROM
                            vivienda_vivienda AS viv 
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                            INNER JOIN propietario_vivienda_propietario AS prop ON prop.id_viv = viv.id_viv
                        WHERE
                            prop.id_pro = " . $id . "
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $nombre_con = utf8_encode($fila["nombre_con"]);
                            $nombre_tor = utf8_encode($fila["nombre_tor"]);
                            $nombre_viv = utf8_encode($fila["nombre_viv"]);
                            
                            ?>
                            <tr>
                                <td><?php echo $contador; ?></td>
                                <td><?php echo $nombre_con;?></td>
                                <td><?php echo $nombre_tor;?></td>
                                <td><?php echo $nombre_viv;?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody>
            </table>
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
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                // nuevo
                $id_pro = $id;
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
		                            med.nombre_med_cot
		                        FROM
		                            cotizacion_seguimiento_cotizacion AS seg 
		                            INNER JOIN cotizacion_seguimiento_interes_cotizacion AS inte ON inte.id_seg_int_cot = seg.id_seg_int_cot
		                            INNER JOIN cotizacion_medio_cotizacion AS med ON med.id_med_cot = seg.id_med_cot
		                        WHERE
		                            seg.id_cot = " . $id_cot . "
		                        ";
		                    $conexion->consulta_form($consulta,array($id));
		                    $fila_consulta = $conexion->extraer_registro();
		                    $cantidad = $conexion->total();
		                    if(is_array($fila_consulta)){
		                        foreach ($fila_consulta as $fila) {
		                            $nombre_seg_int_cot = utf8_encode($fila["nombre_seg_int_cot"]);
		                            $nombre_med_cot = utf8_encode($fila["nombre_med_cot"]);
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
            <h5 style="font-weight: bold; text-decoration: underline;">Observaciones</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Observación</th>
                        <?php 
						if ($_SESSION["sesion_perfil_panel"]<>4) {
	                     ?>
                        <th>Acción</th>
                        <?php 
                        } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    $consulta = 
                        "
                        SELECT
                            pro.fecha_obs_pro,
                            pro.descripcion_obs_pro,
                            usu.nombre_usu,
                            usu.apellido1_usu,
                            usu.apellido2_usu,
                            pro.id_obs_pro
                        FROM
                            propietario_observacion_propietario AS pro 
                            INNER JOIN usuario_usuario AS usu ON usu.id_usu = pro.id_usu
                        WHERE
                            pro.id_pro = " . $id . "
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $fecha_obs_pro = date("d-m-Y H:i",strtotime($fila["fecha_obs_pro"]));
                            $descripcion_obs_pro = utf8_encode($fila["descripcion_obs_pro"]);
                            $id_obs_pro = $fila["id_obs_pro"];
                            $nombre_usuario = utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]." ".$fila["apellido2_usu"]);
                            ?>
                            <tr>
                                <td><?php echo $contador; ?></td>
                                <td><?php echo $nombre_usuario;?></td>
                                <td><?php echo $fecha_obs_pro;?></td>
                                <td><?php echo $descripcion_obs_pro;?></td>
                                <?php 
								if ($_SESSION["sesion_perfil_panel"]<>4) {
                                 ?>
                                <td><button data-pro="<?php echo $id; ?>" value="<?php echo $id_obs_pro; ?>" type="button" class="btn btn-sm btn-icon btn-danger eliminar_observacion" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button></td>
                                <?php 
                                } ?>
                            </tr>
                            <?php
                            $contador = $contador + 1;
                        }
                        
                    }
                ?>
                </tbody>
            </table>
            <br>
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
                    	eve_est.nombre_est_eve,
                        vend.nombre_vend,
                        vend.apellido_paterno_vend
                    FROM
                    	evento_evento AS eve,
                    	cotizacion_cotizacion AS cot,
                        vendedor_vendedor AS vend,
                        evento_estado_evento AS eve_est
                    WHERE
                        eve.id_pro = " . $id_pro . " AND
                        cot.id_vend = vend.id_vend AND
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
            <br>
            <h5 style="font-weight: bold; text-decoration: underline;">Campañas Enviadas</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>fecha</th>
                        <th>Plantilla</th>
                        <th>Asunto</th>
                        <th>Vendedor</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                // nuevo
                $contador = 1;
				$consulta_envios = 
                    "
                    SELECT
                    	cam.fecha_cam,
                    	cam.asunto_cam,
                    	cam.plantilla_cam,
                    	vend.nombre_vend,
                        vend.apellido_paterno_vend
                    FROM
                    	campana_mail_campana AS cam,
                    	campana_destinatario_campana AS cam_des,
                        vendedor_vendedor AS vend
                    WHERE
                        cam_des.id_pro = " . $id_pro . " AND
                        cam_des.id_cam = cam.id_cam AND
                        cam.id_usu = vend.id_usu
                    ORDER BY
                    	cam.fecha_cam ASC, vend.apellido_paterno_vend ASC
                    ";
                $conexion->consulta($consulta_envios);
                $fila_consulta = $conexion->extraer_registro();
                $cantidad = $conexion->total();
                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila_cam) {
                    	$asunto_cam = utf8_encode($fila_cam["asunto_cam"]);
                    	$nombre_vend = utf8_encode($fila_cam["nombre_vend"]." ".$fila_cam["apellido_paterno_vend"]);
	                    $plantilla_cam = utf8_encode($fila_cam["plantilla_cam"]);
	                    $fecha_cam = date("d-m-Y",strtotime($fila_cam["fecha_cam"]));
                        ?>
                        <tr>
                            <td><?php echo $fecha_cam; ?></td>
                            <td><?php echo $plantilla_cam; ?></td>
                            <td><?php echo $asunto_cam; ?></td>
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
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
