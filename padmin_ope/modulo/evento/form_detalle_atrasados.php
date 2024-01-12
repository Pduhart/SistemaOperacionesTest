<?php 
session_start(); 

date_default_timezone_set('America/Santiago');

require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
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
            <h4 class="modal-title">Detalle Atrasados </h4>
        </div>
        <div class="modal-body">
            <div class="row">
            	<div class="col-md-12">
            		<table class="table table-striped">
		                <thead>
		                    <tr>
		                        <th>fecha</th>
		                        <th>Hora</th>
		                        <th>Nombre</th>
		                        <th>descripción</th>
		                        <th>ID Cot</th>
		                        <th>Vendedor</th>
		                    </tr>
		                </thead>
		                <tbody>

		                <?php
		                $id_per = $_POST["valor"];
		                

		                $fecha_actual = date("Y-m-d");
						$hora_actual = date("H:i:00");
						$consulta_pasados = 
						  "
						  SELECT 
						    *
						  FROM
						    evento_evento as eve,
		                	cotizacion_cotizacion AS cot,
		                    vendedor_vendedor AS vend
						  WHERE
						    eve.fecha_eve < '".$fecha_actual."' AND
						    eve.id_est_eve = 1 AND
		                    eve.id_usu = vend.id_usu AND
		                    eve.id_cot = cot.id_cot
						  ";

						if ($id_per==4) {
		                	$consulta_pasados .= " AND eve.id_usu = ".$_SESSION["sesion_id_panel"]."";
		                }


						$conexion->consulta($consulta_pasados);
						$fila_consulta = $conexion->extraer_registro();
						if(is_array($fila_consulta)){
		                    foreach ($fila_consulta as $fila_eve) {
		                    	$nombre_vend = utf8_encode($fila_eve["nombre_vend"]." ".$fila_eve["apellido_paterno_vend"]);
			                	$time_eve = utf8_encode($fila_eve["time_eve"]);
			                    $time_eve_format = date("H:i",strtotime($time_eve));
			                	$id_cot = utf8_encode($fila_eve["id_cot"]);
			                    $nombre_eve = utf8_encode($fila_eve["nombre_eve"]);
			                    $descripcion_eve = utf8_encode($fila_eve["descripcion_eve"]);
			                    $fecha_eve = date("d-m-Y",strtotime($fila_eve["fecha_eve"]));
			                    ?>
								<tr>
		                            <td><?php echo $fecha_eve; ?></td>
		                            <td><?php echo $time_eve_format; ?></td>
		                            <td><?php echo $nombre_eve; ?></td>
		                            <td><?php echo $descripcion_eve; ?></td>
		                            <td><?php echo $id_cot; ?></td>
		                            <td><?php echo $nombre_vend;?></td>
		                        </tr>

			                    <?php
		                    }
		                }

						$consulta_atrasados = 
						  "
						  SELECT 
						    *
						  FROM
						    evento_evento as eve,
		                	cotizacion_cotizacion AS cot,
		                    vendedor_vendedor AS vend
						  WHERE
						    eve.fecha_eve = '".$fecha_actual."' AND
						    eve.time_eve < '".$hora_actual."' AND
						    eve.id_est_eve = 1 AND
		                    eve.id_usu = vend.id_usu AND
		                    eve.id_cot = cot.id_cot
						  ";

						if ($id_per==4) {
		                	$consulta_atrasados .= " AND eve.id_usu = ".$_SESSION["sesion_id_panel"]."";
		                }

						$conexion->consulta($consulta_atrasados);
						$fila_consulta = $conexion->extraer_registro();
						if(is_array($fila_consulta)){
		                    foreach ($fila_consulta as $fila_eve) {
								$nombre_vend = utf8_encode($fila_eve["nombre_vend"]." ".$fila_eve["apellido_paterno_vend"]);
			                	$time_eve = utf8_encode($fila_eve["time_eve"]);
			                	$time_eve_format = date("H:i",strtotime($time_eve));
			                	$id_cot = utf8_encode($fila_eve["id_cot"]);
			                    $nombre_eve = utf8_encode($fila_eve["nombre_eve"]);
			                    $descripcion_eve = utf8_encode($fila_eve["descripcion_eve"]);
			                    $fecha_eve = date("d-m-Y",strtotime($fila_eve["fecha_eve"]));
			                    ?>
								<tr>
		                            <td><?php echo $fecha_eve; ?></td>
		                            <td><?php echo $time_eve_format; ?></td>
		                            <td><?php echo $nombre_eve; ?></td>
		                            <td><?php echo $descripcion_eve; ?></td>
		                            <td><?php echo $id_cot; ?></td>
		                            <td><?php echo $nombre_vend;?></td>
		                        </tr>

			                    <?php
		                    }
		                }

						if ($id_per==2) {
			                $consulta_pasados_jefe = 
							  "
							  SELECT 
							    *
							  FROM
							    evento_evento as eve,
			                    usuario_usuario AS usu
							  WHERE
							    eve.fecha_eve < '".$fecha_actual."' AND
							    eve.id_est_eve = 1 AND
							    eve.id_cat_eve = 2 AND
			                    eve.id_usu = usu.id_usu
							  ";


							$conexion->consulta($consulta_pasados_jefe);
							$fila_consulta = $conexion->extraer_registro();
							if(is_array($fila_consulta)){
			                    foreach ($fila_consulta as $fila_eve) {
			                    	$nombre_usu = utf8_encode($fila_eve["nombre_usu"]." ".$fila_eve["apellido1_usu"]);
				                	$time_eve = utf8_encode($fila_eve["time_eve"]);
				                    $time_eve_format = date("H:i",strtotime($time_eve));
				                	// $id_cot = utf8_encode($fila_eve["id_cot"]);
				                    $nombre_eve = utf8_encode($fila_eve["nombre_eve"]);
				                    $descripcion_eve = utf8_encode($fila_eve["descripcion_eve"]);
				                    $fecha_eve = date("d-m-Y",strtotime($fila_eve["fecha_eve"]));
				                    ?>
									<tr>
			                            <td><?php echo $fecha_eve; ?></td>
			                            <td><?php echo $time_eve_format; ?></td>
			                            <td><?php echo $nombre_eve; ?></td>
			                            <td><?php echo $descripcion_eve; ?></td>
			                            <td>--</td>
			                            <td><?php echo $nombre_usu;?></td>
			                        </tr>

				                    <?php
			                    }
			                }

			                $consulta_atrasados_jefe = 
							  "
							  SELECT 
							    *
							  FROM
							    evento_evento as eve,
			                    usuario_usuario AS usu
							  WHERE
							    eve.fecha_eve = '".$fecha_actual."' AND
							    eve.time_eve < '".$hora_actual."' AND
							    eve.id_est_eve = 1 AND
							    eve.id_cat_eve = 2 AND
			                    eve.id_usu = usu.id_usu
							  ";

							$conexion->consulta($consulta_atrasados_jefe);
							$fila_consulta = $conexion->extraer_registro();
							if(is_array($fila_consulta)){
			                    foreach ($fila_consulta as $fila_eve) {
									$nombre_usu = utf8_encode($fila_eve["nombre_usu"]." ".$fila_eve["apellido1_usu"]);
				                	$time_eve = utf8_encode($fila_eve["time_eve"]);
				                	$time_eve_format = date("H:i",strtotime($time_eve));
				                	// $id_cot = utf8_encode($fila_eve["id_cot"]);
				                    $nombre_eve = utf8_encode($fila_eve["nombre_eve"]);
				                    $descripcion_eve = utf8_encode($fila_eve["descripcion_eve"]);
				                    $fecha_eve = date("d-m-Y",strtotime($fila_eve["fecha_eve"]));
				                    ?>
									<tr>
			                            <td><?php echo $fecha_eve; ?></td>
			                            <td><?php echo $time_eve_format; ?></td>
			                            <td><?php echo $nombre_eve; ?></td>
			                            <td><?php echo $descripcion_eve; ?></td>
			                            <td>--</td>
			                            <td><?php echo $nombre_usu;?></td>
			                        </tr>

				                    <?php
			                    }
			                }
			            }
		                ?>
	                	</tbody>
            		</table>              
	           	</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default margin-0" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>



