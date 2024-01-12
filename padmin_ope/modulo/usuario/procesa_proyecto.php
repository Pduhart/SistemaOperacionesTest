<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
$id_uni = $_POST["unidad_usu"];
?>
<div class="box-header">
  <h3 class="box-title"><i class="fa fa-cog" aria-hidden="true"></i> Proyectos</h3>
</div>
<div class="box-body">
	<ul class="list-unstyled list-inline margin-0">		
	<?php
	$consulta = "SELECT * FROM proyecto_proyecto WHERE id_uni = ? AND id_est_pro = 1 ORDER BY nombre_pro ASC";
	$conexion->consulta_form($consulta,array($id_uni));
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
		foreach ($fila_consulta as $fila) {
			$nombre_pro = utf8_encode($fila['nombre_pro']);
			$id_pro = $fila['id_pro'];
			$consulta = "SELECT * FROM usuario_proyecto_unidad WHERE id_usu = ? AND id_pro = ?";
			$conexion->consulta_form($consulta,array($usuario_usu,$id_pro));
			$cantidad_modulo = $conexion->total();
			if($cantidad_modulo > 0){
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="proyecto_<?php echo $id_pro;?>" id="proyecto_<?php echo $id_pro;?>" value="<?php echo $id_pro;?>" checked class="proyecto_usuario check_registro"><label for="proyecto_<?php echo $id_pro;?>"><span></span><?php echo $nombre_pro;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="proyecto_<?php //echo $id_pro;?>" id="proyecto_<?php //echo $id_pro;?>" class="proyecto_usuario" value="<?php //echo $id_pro;?>" checked/>
						<label for="proyecto_<?php //echo $id_pro;?>"><?php //echo $nombre_pro;?></label>
					</div> -->
				</li>
				<?php
			}
			else{
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="proyecto_<?php echo $id_pro;?>" id="proyecto_<?php echo $id_pro;?>" value="<?php echo $id_pro;?>" class="proyecto_usuario check_registro"><label for="proyecto_<?php echo $id_pro;?>"><span></span><?php echo $nombre_pro;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="proyecto_<?php //echo $id_pro;?>" id="proyecto_<?php //echo $id_pro;?>" class="proyecto_usuario" value="<?php //echo $id_pro;?>"/>
						<label for="proyecto_<?php //echo $id_pro;?>"><?php //echo $nombre_pro;?></label>
					</div> -->
				</li>
				<?php
			}
		}
	}
	?>	
	</ul>
</div>
    

