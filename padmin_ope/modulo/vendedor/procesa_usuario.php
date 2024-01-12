<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();

$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;

?>
<div class="box-header">
    <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Vendedores</h3>
</div>
<div class="box-body">
    <ul class="list-unstyled list-inline margin-0">
        <?php
        $consulta = "SELECT * FROM vendedor_vendedor WHERE id_vend = 1 ORDER BY nombre_vend, apellido_paterno_vend, apellido_materno_vend ASC";
        $conexion->consulta($consulta);
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
            foreach ($fila_consulta as $fila) {
                $nombre_vend = utf8_encode($fila['nombre_vend']);
                $apellido_paterno_vend = utf8_encode($fila['apellido_paterno_vend']);
                $apellido_materno_vend = utf8_encode($fila['apellido_materno_vend']);
                $id_vend = $fila['id_vend'];
                $consulta = "SELECT * FROM vendedor_supervisor_vendedor WHERE id_usu = ? AND id_vend = ?";
                $conexion->consulta_form($consulta,array($id,$id_vend));
                $cantidad = $conexion->total();
                if($cantidad > 0){
                    ?>
                    <li class="margin-bottom-10 col-sm-4">
                        <input type="checkbox" name="modulo_vendedor[]" id="modulo_<?php echo $id_vend;?>" value="<?php echo $id_vend;?>" checked class="vendedor check_registro"><label for="modulo_<?php echo $id_vend;?>"><span></span><?php echo $nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend;?></label>
                        
                    </li>
                    <?php
                }
                else{
                	$consulta = "SELECT * FROM vendedor_supervisor_vendedor WHERE id_vend = ?";
	                $conexion->consulta_form($consulta,array($id_vend));
	                $cantidad = $conexion->total();
	                if($cantidad > 0){
	                    ?>
	                    <li class="margin-bottom-10 col-sm-4">
	                        <input type="checkbox" name="modulo_<?php echo $id_vend;?>[]" id="modulo_<?php echo $id_vend;?>" value="<?php echo $id_vend;?>" checked disabled class="vendedor check_registro"><label for="modulo_<?php echo $id_vend;?>"><span></span><?php echo $nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend;?></label>
	                        
	                    </li>
	                    <?php
	                }
	                else{
	                	?>
	                    <li class="margin-bottom-10 col-sm-4">
	                        <input type="checkbox" name="modulo_vendedor[]" id="modulo_<?php echo $id_vend;?>" value="<?php echo $id_vend;?>" class="vendedor check_registro"><label for="modulo_<?php echo $id_vend;?>"><span></span><?php echo $nombre_vend." ".$apellido_paterno_vend." ".$apellido_materno_vend;?></label>
	                        
	                    </li>
	                    <?php
	                }
                    
                }
            }
        }
        ?>  
    </ul>
</div>