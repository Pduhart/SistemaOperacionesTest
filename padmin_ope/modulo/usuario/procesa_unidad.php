<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
?>
<div class="box-header">
  <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Unidades</h3>
</div>
<div class="box-body">
	<ul class="list-unstyled list-inline margin-0">
	<?php
	$consulta = "SELECT * FROM unidad_unidad ORDER BY nombre_uni ASC";
	$conexion->consulta($consulta);
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
		foreach ($fila_consulta as $fila) {
			$nombre_uni = utf8_encode($fila['nombre_uni']);
			$id_uni = $fila['id_uni'];
			$consulta = "SELECT * FROM usuario_unidad_usuario WHERE id_usu = ? AND id_uni = ?";
			$conexion->consulta_form($consulta,array($usuario_usu,$id_uni));
			$cantidad_modulo = $conexion->total();
			if($cantidad_modulo > 0){
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="unidad_<?php echo $id_uni;?>" id="unidad_<?php echo $id_uni;?>" value="<?php echo $id_uni;?>" checked class="unidad_usuario check_registro"><label for="unidad_<?php echo $id_uni;?>"><span></span><?php echo $nombre_uni;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="unidad_<?php //echo $id_uni;?>" id="unidad_<?php //echo $id_uni;?>" class="unidad_usuario" value="<?php //echo $id_uni;?>" checked/>
						<label for="unidad_<?php//echo $id_uni;?>"><?php //echo $nombre_uni;?></label>
					</div> -->
				</li>
				<?php
			}
			else{
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="unidad_<?php echo $id_uni;?>" id="unidad_<?php echo $id_uni;?>" value="<?php echo $id_uni;?>" class="unidad_usuario check_registro"><label for="unidad_<?php echo $id_uni;?>"><span></span><?php echo $nombre_uni;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="unidad_<?php// echo $id_uni;?>" id="unidad_<?php// echo $id_uni;?>" class="unidad_usuario" value="<?php// echo $id_uni;?>"/>
						<label for="unidad_<?php// echo $id_uni;?>"><?php// echo $nombre_uni;?></label>
					</div> -->
				</li>
				<?php
			}
		}
	}
	?>	
	</ul>
</div>
    

