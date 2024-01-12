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
		concat_ws(' ', nombre_prov, rut_prov) as pro, id_prov
		FROM proveedor_proveedor
		WHERE (concat_ws(' ', nombre_prov, rut_prov)
		LIKE '%".utf8_decode($b[0])."%'
		AND
		concat_ws(' ', nombre_prov, rut_prov)
		LIKE '%".utf8_decode($b[1])."%'
		AND
		concat_ws(' ', nombre_prov, rut_prov)
		LIKE '%".utf8_decode($b[2])."%')
		OR
		(REPLACE(rut_prov,  '.',  '')
		LIKE '%".utf8_decode($b[0])."%')
		AND id_est_prov = 1
		";
	
	
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
				$id_prov = $fila['id_prov'];
				?>
				<li class="busqueda_proyecto" id="<?php echo $id_prov;?>"><?php echo $nombre;?></li>
				<?php
				 
		  }
		  ?>
		  </ul>
		  </div>
		  <?php
	}
}
       
?>