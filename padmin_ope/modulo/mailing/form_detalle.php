<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_mailing_panel"])) {
    header("Location: "._ADMIN."panel.php");
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
$conexion = new conexion();
?>
<div class="modal-dialog modal-center">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-h5="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">Detalle </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <?php
                $id_eve = $_POST["valor"];

                $consulta = 
                    "
                    SELECT 
                        cam.asunto_cam,
                        cam.link_cam,
                        cam.fecha_cam,
                        cam.id_usu,
                        cam.plantilla_cam,
                        cam.cantidad_cam,
                        vend.nombre_vend,
                        vend.apellido_paterno_vend
                    FROM 
                        campana_mail_campana AS cam, 
						vendedor_vendedor AS vend
                    WHERE 
                        cam.id_cam = ? AND
                        cam.id_usu = vend.id_usu

                    ";
                $conexion->consulta_form($consulta,array($id_eve));
                $fila = $conexion->extraer_registro_unico();
                $asunto_cam = utf8_encode($fila['asunto_cam']);
                $link_cam = utf8_encode($fila['link_cam']);
                $cantidad_cam = utf8_encode($fila['cantidad_cam']);
                $plantilla_cam = utf8_encode($fila['plantilla_cam']);
                $fecha_cam = date("d-m-Y H:i",strtotime($fila['fecha_cam']));
                $nombre_vend = utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);
                ?>
                <div class="col-sm-12">
                    <h5><b>Asunto:</b> &nbsp; <?php echo $asunto_cam;?></h5>
                    <h5><b>Fecha:</b> &nbsp;<?php echo $fecha_cam;?></h5>
                    <h5><b>Enlace:</b> &nbsp;<?php echo $link_cam;?></h5>
                    <h5><b>Plantilla:</b> &nbsp; <?php echo $plantilla_cam;?></h5>
                    <h5><b>Cantidad:</b> &nbsp; <?php echo $cantidad_cam;?></h5>
                    <h5><b>Vendedor:</b> &nbsp; <?php echo $nombre_vend;?></h5>
                </div>
                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>



