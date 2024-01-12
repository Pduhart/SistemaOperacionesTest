<?php
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();
$usuario_usu = $_POST["usuario_usu"];
?>
<div class="box-header">
  <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Módulos</h3>
</div>
<div class="box-body">
	<ul class="list-unstyled list-inline margin-0">
	<?php
	$consulta = "SELECT * FROM usuario_modulo ORDER BY nombre_mod ASC";
	$conexion->consulta($consulta);
	$fila_consulta = $conexion->extraer_registro();
	if(is_array($fila_consulta)){
		foreach ($fila_consulta as $fila) {
			$nombre_mod = utf8_encode($fila['nombre_mod']);
			$id_mod = $fila['id_mod'];
			$consulta = "SELECT * FROM usuario_usuario_modulo WHERE id_usu = ? AND id_mod = ?";
			$conexion->consulta_form($consulta,array($usuario_usu,$id_mod));
			$cantidad_modulo = $conexion->total();
			if($cantidad_modulo > 0){
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="modulo_<?php echo $id_mod;?>" id="modulo_<?php echo $id_mod;?>" value="<?php echo $id_mod;?>" checked class="modulo_usuario check_registro"><label for="modulo_<?php echo $id_mod;?>"><span></span><?php echo $nombre_mod;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="modulo_<?php //echo $id_mod;?>" id="modulo_<?php //echo $id_mod;?>" class="modulo_usuario" value="<?php //echo $id_mod;?>" checked/>
						<label for="modulo_<?php //echo $id_mod;?>"><?php //echo $nombre_mod;?></label>
					</div> -->
				</li>
				<?php
			}
			else{
				?>
				<li class="margin-bottom-10 col-sm-4">
					<input type="checkbox" name="modulo_<?php echo $id_mod;?>" id="modulo_<?php echo $id_mod;?>" value="<?php echo $id_mod;?>" class="modulo_usuario check_registro"><label for="modulo_<?php echo $id_mod;?>"><span></span><?php echo $nombre_mod;?></label>
					<!-- <div class="checkbox-custom checkbox-warning label-sm">
						<input type="checkbox" name="modulo_<?php //echo $id_mod;?>" id="modulo_<?php //echo $id_mod;?>" class="modulo_usuario" value="<?php //echo $id_mod;?>"/>
						<label for="modulo_<?php //echo $id_mod;?>"><?php //echo $nombre_mod;?></label>
					</div> -->
				</li>
				<?php
			}
		}
	}
	?>	
	</ul>
</div>
    

