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
                    pro.correo_pro,
                    pro.fono_pro,
                    cot.fecha_cot
                FROM
                    cotizacion_cotizacion AS cot 
                    INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                    INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                    INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                    INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
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
            
            ?>
            <h5>Datos generales</h5>
            <label class="negrita_detalle">Condominio: </label><label>&nbsp; <?php echo $nombre_con;?></label></br>
            <label class="negrita_detalle">Torre: </label><label>&nbsp; <?php echo $nombre_tor;?></label></br>
            <label class="negrita_detalle">Depto: </label><label>&nbsp; <?php echo $nombre_viv;?></label></br>
            <label class="negrita_detalle">Canal: </label><label>&nbsp; <?php echo $nombre_can_cot;?></label></br>
            <label class="negrita_detalle">Rut Cliente: </label><label>&nbsp; <?php echo $rut_pro;?></label></br>
            <label class="negrita_detalle">Nombre Cliente: </label><label>&nbsp; <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></label></br>
            <label class="negrita_detalle">Correo: </label><label>&nbsp; <?php echo $correo_pro;?></label></br>
            <label class="negrita_detalle">Fono: </label><label>&nbsp; <?php echo $fono_pro;?></label></br>
            
            <br>
            <h5>Seguimientos</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Interés</th>
                        <th>Medio</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
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
                            seg.id_cot = " . $id . "
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $nombre_seg_int_cot = utf8_encode($fila["nombre_seg_int_cot"]);
                            $nombre_med_cot = utf8_encode($fila["nombre_med_cot"]);
                            $descripcion_seg_cot = utf8_encode($fila["descripcion_seg_cot"]);
                            ?>
                            <tr>
                                <td><?php echo $contador; ?></td>
                                <td><?php echo $nombre_seg_int_cot;?></td>
                                <td><?php echo $nombre_med_cot;?></td>
                                <td><?php echo $descripcion_seg_cot;?></td>
                            </tr>
                            <?php
                        }
                    }
                ?>
                </tbody>
            </table>


            <h5>Desistimiento</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = 
                        "
                        SELECT
                            fecha_des_cot,
                            descripcion_des_cot
                        FROM
                            cotizacion_desistimiento_cotizacion
                        WHERE
                            id_cot = " . $id . "
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $fecha_des_cot = utf8_encode($fila["fecha_des_cot"]);
                            $descripcion_des_cot = utf8_encode($fila["descripcion_des_cot"]);
                            ?>
                            <tr>
                                <td><?php echo date("d-m-Y",strtotime($fecha_des_cot));?></td>
                                <td><?php echo $descripcion_des_cot;?></td>
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
