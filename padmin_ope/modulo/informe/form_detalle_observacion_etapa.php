<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

if(!isset($_POST["id_ven"])){
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
            <h4 class="modal-title">Observaciones </h4>
        </div>
        <div class="modal-body">
            <?php
            $conexion = new conexion();
            $id_ven = $_POST["id_ven"];
            $id_eta = $_POST["id_eta"];
            $id_eta_ven = $_POST["id_eta_ven"];
            
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>obs.</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    $consulta = 
                        "
                        SELECT 
                            a.id_obs_eta_ven, 
                            a.fecha_obs_eta_ven, 
                            a.descripcion_obs_eta_ven, 
                            b.nombre_usu, 
                            b.apellido1_usu
                        FROM 
                            venta_observacion_etapa_venta AS a
                            INNER JOIN usuario_usuario AS b ON a.id_usu = b.id_usu
                        WHERE   
                            a.id_eta = ".$id_eta." AND
                            a.id_ven = ".$id_ven."
                        ORDER BY
                            fecha_obs_eta_ven
                        DESC
                        ";
                    $conexion->consulta($consulta);
                    $cantidad_observacion = $conexion->total();
                    $fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            if($fila["id_cam_eta"] == 2){
                                $clase = "numero";
                            }
                            else if($fila["id_cam_eta"] == 3){
                                $clase = "datepicker";
                            }
                            else{
                                $clase = "";
                            }
                            ?>
                            
                            <tr>
                                <td><?php echo $cantidad_observacion;?></td>
                                <td><?php echo utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]);?></td>
                                <td><?php echo utf8_encode($fila["descripcion_obs_eta_ven"]);?></td>
                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_obs_eta_ven"]));?></td>
                                <td>
                                    <?php  
                                    if($estado_etapa == 2 ){
                                        ?>
                                        <button value="<?php echo $fila["id_obs_eta_ven"];?>" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>
                                        <?php
                                    }
                                    ?>
                                    
                                </td>
                            </tr>
                            <?php
                            $cantidad_observacion--;
                        }
                    }
                    ?>
                </tbody>
            </table>

            <h4 class="modal-title">Documentos</h4>
            <?php  
            $consulta = 
                "
                SELECT 
                    nombre_eta_doc_ven
                FROM 
                    venta_etapa_documento_venta
                WHERE   
                    id_eta_ven = ".$id_eta_ven."
                ORDER BY
                    id_eta_doc_ven
                ";
            $conexion->consulta($consulta);
            $cantidad_observacion = $conexion->total();
            $fila_consulta = $conexion->extraer_registro();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                    ?>
                    <h4><?php echo utf8_encode($fila["nombre_eta_doc_ven"]);?> <a href="../../../archivo/operacion/<?php echo $id_eta;?>/documento/<?php echo $id_ven;?>/<?php echo utf8_encode($fila["nombre_eta_doc_ven"]);?>" class="btn btn-default btn-sm" download><i class="fa fa-download" aria-hidden="true"></i></a></h4>
                    

                    <?php
                }
            }
            ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
