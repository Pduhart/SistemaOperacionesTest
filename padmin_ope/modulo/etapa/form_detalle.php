<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_etapa_panel"])){
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
                    *
                FROM 
                    etapa_etapa
                WHERE 
                    id_eta = ?
                ";
            $conexion->consulta_form($consulta,array($id));
            $fila = $conexion->extraer_registro_unico();
            $nombre_eta = utf8_encode($fila['nombre_eta']);
            $alias_eta = utf8_encode($fila['alias_eta']);
            
            ?>
            <h5>Datos generales</h5>
            <label class="negrita_detalle">Nombre: </label><label>&nbsp; <?php echo $nombre_eta;?></label></br>
            <label class="negrita_detalle">Alias: </label><label>&nbsp; <?php echo $alias_eta;?></label></br>
            
            <br>
            <h5>Campos Extras</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo Campo</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    $consulta = 
                        "
                        SELECT
                            tip_cam_eta.nombre_tip_cam_eta,
                            cam_eta.nombre_cam_eta
                        FROM
                            etapa_campo_etapa AS cam_eta
                            INNER JOIN etapa_tipo_campo_etapa AS tip_cam_eta ON tip_cam_eta.id_tip_cam_eta = cam_eta.id_tip_cam_eta
                        WHERE
                            id_eta = ?
                        ";
                    $conexion->consulta_form($consulta,array($id));
                    $fila_consulta = $conexion->extraer_registro();
                    $cantidad = $conexion->total();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            
                            $nombre_tip_cam_eta = utf8_encode($fila["nombre_tip_cam_eta"]);
                            $nombre_cam_eta = utf8_encode($fila["nombre_cam_eta"]);
                            ?>
                            <tr>
                                <td><?php echo $nombre_tip_cam_eta; ?></td>
                                <td><?php echo $nombre_cam_eta;?></td>
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



