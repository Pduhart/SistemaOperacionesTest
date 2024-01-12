<?php
 session_start();
$buscar = $_POST['b'];
$buscar = trim($buscar);
$buscar1 = explode(" ",$buscar); 

if(!empty($buscar)) {
	buscar($buscar1,$buscar);
}

function buscar($b,$buscar) {
	require "../../config.php";
	include ("../../class/conexion.php");	
	$conexion = new conexion();
	
		$consulta = "SELECT
		concat_ws(' ', nombre_pro, apellido_paterno_pro, apellido_materno_pro, rut_pro) as pro, id_pro
		FROM propietario_propietario
		WHERE (concat_ws(' ', nombre_pro, apellido_paterno_pro, apellido_materno_pro, rut_pro)
		LIKE '%".utf8_decode($b[0])."%'
		AND
		concat_ws(' ', nombre_pro, apellido_paterno_pro, apellido_materno_pro, rut_pro)
		LIKE '%".utf8_decode($b[1])."%'
		AND
		concat_ws(' ', nombre_pro, apellido_paterno_pro, apellido_materno_pro, rut_pro)
		LIKE '%".utf8_decode($b[2])."%')
		OR
		(REPLACE(rut_pro,  '.',  '')
		LIKE '%".utf8_decode($b[0])."%')
		";
	
	// crear sesión de RUT buscado
	$_SESSION["sesion_rut_buscado_ficha"] = $buscar;
	
	$conexion->consulta($consulta);
	$fila_consulta = $conexion->extraer_registro();
	$cantidad = $conexion->total();
	 
	if($cantidad == 0){
		  echo "No se han encontrado resultados para '<b>".$buscar."</b>'.<br>";
	}else{
		?>
		<div id="res">
			<ul class="list-group">
				<?php
		  		foreach ($fila_consulta as $fila) {
					$nombre = utf8_encode(strtoupper(html_entity_decode($fila['pro'])));
					$id_pro = $fila['id_pro'];
					?>
					<li class="busqueda list-group-item" id="<?php echo $id_pro;?>"><?php echo $nombre;?></li>
					<?php
				}
		  		?>
		  	</ul>
		</div>
		  <?php
	}
}
       
?>