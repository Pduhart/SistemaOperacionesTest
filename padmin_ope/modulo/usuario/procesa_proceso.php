<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
$id_mod = $_POST["modulo_usu"];
?>
<div class="box-header">
  <h3 class="box-title"><i class="fa fa-cog" aria-hidden="true"></i> Procesos</h3>
</div>
<div class="box-body">
	<ul class="list-unstyled list-inline margin-0">		
	<?php
	$consulta = "SELECT * FROM usuario_proceso WHERE id_mod = ? ORDER BY nombre_pro ASC";
	$conexion->consulta_form($consulta,array($id_mod));
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
		foreach ($fila_consulta as $fila) {
			$nombre_pro = utf8_encode($fila['nombre_pro']);
			$id_pro = $fila['id_pro'];
			$consulta = "SELECT * FROM usuario_usuario_proceso WHERE id_usu = ? AND id_pro = ?";
			$conexion->consulta_form($consulta,array($usuario_usu,$id_pro));
			$cantidad_modulo = $conexion->total();
			if($cantidad_modulo > 0){
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="proceso_<?php echo $id_pro;?>" id="proceso_<?php echo $id_pro;?>" value="<?php echo $id_pro;?>" checked class="proceso_usuario check_registro"><label for="proceso_<?php echo $id_pro;?>"><span></span><?php echo $nombre_pro;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="proceso_<?php //echo $id_pro;?>" id="proceso_<?php //echo $id_pro;?>" class="proceso_usuario" value="<?php //echo $id_pro;?>" checked/>
						<label for="proceso_<?php //echo $id_pro;?>"><?php //echo $nombre_pro;?></label>
					</div> -->
				</li>
				<?php
			}
			else{
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="proceso_<?php echo $id_pro;?>" id="proceso_<?php echo $id_pro;?>" value="<?php echo $id_pro;?>" class="proceso_usuario check_registro"><label for="proceso_<?php echo $id_pro;?>"><span></span><?php echo $nombre_pro;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="proceso_<?php //echo $id_pro;?>" id="proceso_<?php //echo $id_pro;?>" class="proceso_usuario" value="<?php //echo $id_pro;?>"/>
						<label for="proceso_<?php //echo $id_pro;?>"><?php //echo $nombre_pro;?></label>
					</div> -->
				</li>
				<?php
			}
		}
	}
	?>	
	</ul>
</div>
    

