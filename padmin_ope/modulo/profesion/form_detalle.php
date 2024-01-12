<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_proyecto_panel"])){
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
                    proyecto_proyecto AS pro,
                    proyecto_detalle_proyecto AS det
                WHERE 
                    pro.id_pro = ? AND
                    pro.id_pro = det.id_pro
                ";
            $conexion->consulta_form($consulta,array($id));
            $fila = $conexion->extraer_registro_unico();
            $nombre_pro = utf8_encode($fila['nombre_pro']);
            $id_tip_pro = utf8_encode($fila['id_tip_pro']);

            $nombre_cliente_det_pro = utf8_encode($fila['nombre_cliente_det_pro']);
            $direccion_det_pro = utf8_encode($fila['direccion_det_pro']);
            $ciudad_det_pro = utf8_encode($fila['ciudad_det_pro']);
            $rut_det_pro = utf8_encode($fila['rut_det_pro']);
            $giro_det_pro = utf8_encode($fila['giro_det_pro']);

            if($id_tip_pro == 2){
                ?>
                <label class="negrita_detalle">Nombre: </label><label>&nbsp; <?php echo $nombre_pro;?></label></br>
                <label class="negrita_detalle">Rut: </label><label>&nbsp; <?php echo $rut_det_pro;?></label></br>
                <label class="negrita_detalle">Nombre Cliente: </label><label>&nbsp; <?php echo $nombre_cliente_det_pro;?></label></br>
                <?php
            }
            if($id_tip_pro == 3){
                ?>
                <label class="negrita_detalle">Nombre: </label><label>&nbsp; <?php echo $nombre_pro;?></label></br>
                <label class="negrita_detalle">Rut: </label><label>&nbsp; <?php echo $rut_det_pro;?></label></br>
                <label class="negrita_detalle">Nombre Cliente: </label><label>&nbsp; <?php echo $nombre_cliente_det_pro;?></label></br>
                <label class="negrita_detalle">Ciudad: </label><label>&nbsp; <?php echo $ciudad_det_pro;?></label></br>
                <label class="negrita_detalle">Dirección: </label><label>&nbsp; <?php echo $direccion_det_pro;?></label></br>
                <label class="negrita_detalle">Giro: </label><label>&nbsp; <?php echo $giro_det_pro;?></label></br>
                <?php
            }
            ?>

            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>



