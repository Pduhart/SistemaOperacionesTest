<?php
 session_start();
$unidad = $_POST['unidad'];
$buscar = $_POST['b'];
$buscar = trim($buscar);
$buscar1 = explode(" ",$buscar); 

if(!empty($buscar)) {
	buscar($buscar1,$buscar,$unidad);
}

function buscar($b,$buscar,$unidad) {
	require "../../config.php";
	include ("../../class/conexion.php");	
	$conexion = new conexion();
	if(!empty($unidad)){
		$consulta = "SELECT
		concat_ws(' ', nombre_pro, codigo_pro) as pro, id_pro
		FROM proyecto_proyecto
		WHERE (concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[0])."%'
		AND
		concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[1])."%'
		AND
		concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[2])."%')
		AND id_est_pro = 1
		AND id_uni = ".$unidad."
		";
	}
	else{
		$consulta = "SELECT
		concat_ws(' ', nombre_pro, codigo_pro) as pro, id_pro
		FROM proyecto_proyecto
		WHERE (concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[0])."%'
		AND
		concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[1])."%'
		AND
		concat_ws(' ', nombre_pro, codigo_pro)
		LIKE '%".utf8_decode($b[2])."%')
		AND id_est_pro = 1
		";	
	}
	
	$conexion->consulta($consulta);
	$fila_consulta = $conexion->extraer_registro();
	$cantidad = $conexion->total();
	 
	if($cantidad == 0){
		  echo "No se han encontrado resultados para '<b>".$buscar."</b>'.<br>";
	}else{
		?>
		<div id="res">
		<ul style="padding-left:0; box-shadow:2px 2px 2px #252525; background-color: aliceblue;max-height: 270px;overflow-y: scroll;">
		<?php
		  foreach ($fila_consulta as $fila) {
				$nombre = utf8_encode(strtoupper(html_entity_decode($fila['pro'])));
				$id_pro = $fila['id_pro'];
				?>
				<li class="busqueda_proyecto" id="<?php echo $id_pro;?>"><?php echo $nombre;?></li>
				<?php
				 
		  }
		  ?>
		  </ul>
		  </div>
		  <?php
	}
}
       
?>