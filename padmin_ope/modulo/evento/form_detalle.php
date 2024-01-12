<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_evento_panel"])) {
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
                        eve.descripcion_eve,
                        eve.nombre_eve,
                        eve.time_eve,
                        eve.id_cot,
                        eve.fecha_eve,
                        vend.nombre_vend,
                        vend.apellido_paterno_vend
                    FROM 
                        evento_evento AS eve, 
						vendedor_vendedor AS vend
                    WHERE 
                        eve.id_eve = ? AND
                        eve.id_usu = vend.id_usu

                    ";
                $conexion->consulta_form($consulta,array($id_eve));
                $fila = $conexion->extraer_registro_unico();
                $descripcion_eve = utf8_encode($fila['descripcion_eve']);
                $nombre_eve = utf8_encode($fila['nombre_eve']);
                $time_eve = utf8_encode($fila['time_eve']);
                $id_cot = $fila['id_cot'];
                $fecha_eve = date("d-m-Y",strtotime($fila['fecha_eve']));
                $nombre_vend = utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);
                ?>
                <div class="col-sm-12">
                    <h5><b>Evento:</b> &nbsp; <?php echo $nombre_eve;?></h5>
                    <h5><b>Fecha:</b> &nbsp;<?php echo $fecha_eve;?></h5>
                    <h5><b>Hora:</b> &nbsp;<?php echo $time_eve;?></h5>
                    <h5><b>Título:</b> &nbsp;<?php echo $nombre_eve;?></h5>
                    <h5><b>Descripción:</b> &nbsp; <?php echo $descripcion_eve;?></h5>
                    <h5><b>ID Cotización:</b> &nbsp; <?php echo $id_cot;?></h5>
                    <h5><b>Vendedor:</b> &nbsp; <?php echo $nombre_vend;?></h5>
                </div>
                <!-- <h5 class="negrita_detalle">Total: </h5><h5>&nbsp; <?php echo(utf8_encode(number_format($total_ven, 0, '', '.')));?></h5></br> -->
                <?php 
				if($_SESSION["sesion_perfil_panel"] <> 4){
            	 ?>
            	 <div class="col-sm-12" style="margin-top: 20px">
	            	 <table class="table table-sm">
	            	 	<tr>
	            	 		<td colspan="2"><b>Historial del Evento</b></td>
	            	 	</tr>
				<?php 
				$consulta = 
                    "
                    SELECT 
                        *
                    FROM 
                        evento_accion_evento
                    WHERE 
                        id_eve = ?
                    ORDER BY
                    	fecha_acc_eve ASC

                    ";
                $conexion->consulta_form($consulta,array($id_eve));
                $fila_consulta = $conexion->extraer_registro();
                $cantidad = $conexion->total();
                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila_acc) {
			 		?>
						<tr>
							<td><?php echo utf8_encode($fila_acc['descripcion_acc_eve']); ?></td>
							<td><?php echo date("d-m-Y H:i",strtotime($fila_acc['fecha_acc_eve'])); ?></td>
						</tr>
			 		<?php 
					}
				}
			 	?>
            	 	</table>
            	</div>
            	 <?php 
				}
            	  ?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>



