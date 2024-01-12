<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Medical PV - Tratamiento contra la psoriasis. Psoricream.</title>
	<?php include 'header_comun.php'; ?>
    <!-- Custom styles for this template -->
<link href="css/page/main.css" rel="stylesheet">
<link href="css/page/carro.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" href="css/alert/sweet-alert.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?php
require "../../config.php";
include 'class/conexion.php';
$conexion = new conexion();
?>
</head>

<body>

<!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
<header id="main_header"> <!-- area de menu -->
	<?php include 'nav_comun.php';?>
</header>

<section id="main-content">
    <div class="container">
        <div class="row">
        	<div class="col-sm-6 izquierda">
				<?php
                // aca va el ciclo del producto
                $consulta = 
					"
					SELECT 
						*
					FROM 
						producto_producto
					WHERE
						id_est_pro = 1 
					";
				$conexion->consulta($consulta);
				$nquery = $conexion->total();
				while ($fila = $conexion->extraer_registro()) {
					$id_producto = utf8_encode($fila["id_pro"]);
					$nombre_pro = utf8_encode($fila["nombre_pro"]);
					$descripcion_pro = utf8_encode($fila["descripcion_pro"]);
					$codigo_pro = $fila["nombre2_pro"];
					$portada_pro = $fila["portada_pro"];
					?>
					<div class="row ficha">
                	<div class="col-xs-12 col-sm-4">
                    <a class="fancybox" rel="example_group" href="archivo/producto/portada/<?php echo $fila["id_pro"];?>/<?php echo $portada_pro;?>" title="Psoricream"><img src="archivo/producto/portada/<?php echo $fila["id_pro"];?>/thumbnail/<?php echo $portada_pro;?>" class="img-thumbnail" title="Click para Agrandar" data-toggle="tooltip" data-placement="bottom"></a>
                    </div>
                	<div class="col-xs-12 col-sm-8">
                        <h2><?php echo $nombre_pro;?></h2>
                        <h5>Código: <?php echo $codigo_pro;?></h5>
                        <hr>
                        <p><?php echo $descripcion_pro;?></p>
                        <h3 id="precio<?php echo $id_producto;?>">Precio: -</h3>
                        <div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Presentación:</span>
                          <select name="<?php echo $id_producto;?>" class="form-control presentacion_clase" id="presentacion<?php echo $id_producto;?>">
                          		<option value="">Seleccionar Presentación</option>
                          		<?php
									$consulta = 
										"
										SELECT 
											tamanio_tamanio.nombre_tam,
											producto_tamanio.id_pro_tam
										FROM 
											tamanio_tamanio,
											producto_tamanio
										WHERE
											producto_tamanio.id_pro = ".$id_producto." AND
											producto_tamanio.id_tam = tamanio_tamanio.id_tam
										";
									$conexion->consulta2($consulta);
									$nquery = $conexion->total2();
									while ($fila_tamanio = $conexion->extraer_registro2()) {
										$nombre = utf8_encode($fila_tamanio["nombre_tam"]);
										$id_pro_tam = $fila_tamanio["id_pro_tam"];
										?>
                              			<option value="<?php echo $id_pro_tam;?>"><?php echo $nombre;?></option>          
                                        <?php
									}	
								?>
                            </select>
                        </div>
                        <div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Cantidad:</span>
                          <input type="text" class="form-control numero" placeholder="Ingrese Cantidad" aria-describedby="basic-addon1" id="cantidad<?php echo $id_producto;?>">
                        </div>
                        <button id="agregar<?php echo $id_producto;?>" value="<?php echo $id_producto;?>" class="btn btn-success agrega_carro">Agregar <i class="fa fa-shopping-cart"></i></button>
                        
                    </div>
                </div>          
				<?php
				}
                ?>
                
                
            </div>
            <div class="col-sm-5 col-sm-offset-1 derecha">
				<?php
                    $consulta = 
                    "
                    SELECT 
                        *
                    FROM 
                        seccion_administracion_seccion
                    WHERE
                        id_adm_sec = 2
                    ";
                    $conexion->consulta($consulta);
                    $nquery = $conexion->total();
                    while ($fila = $conexion->extraer_registro()) {
                        $nombre = utf8_encode($fila["nombre_adm_sec"]);
                        $contenido = utf8_encode($fila["contenido_adm_sec"]);
                    }	
                ?>
                <h2><?php echo $nombre; ?></h2>
                <p><?php echo $contenido; ?></p>
                <hr>
                <div class="btn-group" role="group" aria-label="...">
                <!-- si no está la sesión -->
                <?php
				if(isset($_SESSION["sesion_cliente_panel"])){
					?>
                    <a href="editaperfil.php" class="btn btn-sm btn-info" role="button"><i class="fa fa-pencil-square-o"></i> Ver / Editar mis datos</a>
                    <a href="transacciones.php" class="btn btn-sm btn-info" role="button"><i class="fa fa-bars"></i> Mis transacciones</a>
                    <a href="vercarro.php" class="btn btn-sm btn-info" role="button"><i class="fa fa-shopping-cart"></i> Mi Carro</a>
                     <a href="cerrarsesion.php" class="btn btn-sm btn-info" role="button"><i class="fa fa-power-off"></i></a>
                    <?php
				}
				else{
					?>
                    <a href="login.php" class="btn btn-sm btn-info" role="button">Iniciar Sesión</a>
                	<a href="register.php" class="btn btn-sm btn-info" role="button">Registrarse</a>
                    <!-- siempre -->
                    <a href="vercarro.php" class="btn btn-sm btn-info" role="button"><i class="fa fa-shopping-cart"></i> Mi Carro</a>
                    <?php
				}
                ?>
                
                
                <!-- si está la sesión -->
                
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
<?php include 'footer_comun.php'; ?>
</footer>
<?php include 'js_comun.php'; ?>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.4"></script>
<script src="js/alert/sweet-alert.js"></script>
<script type="text/javascript">
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})
		$(document).ready(function() {
			//$('.numero').numeric();
			$('.fancybox').fancybox({
			padding: 4,
				openEffect : 'elastic',
				openSpeed  : 150,
				closeEffect : 'elastic',
				closeSpeed  : 150,
				closeClick : true,
				helpers : {
					title : {
						type : 'over'
					}
				}
			});
			function resultado(data) {
				if(data.error==1){
					swal("Atención!", "Producto existente, cantidad mayor al stock","warning");
					//alert("Producto existente, cantidad mayor al stock");	
				}
				if(data.error==2){
					swal("Atención!", "Producto existente, cantidad mayor al máximo de pedido","warning");
					//alert("Producto existente, cantidad mayor al stock");	
				}
				/*if(data.error != ''){
					alert("hola");
					alert(data.error);	
				}*/
				if(data.ok==1){
					swal({
					  title: "Excelente!",
					  text: "Producto añadido con éxito!",
					  type: "success",
					  showCancelButton: false,
					  confirmButtonColor: "#9bde94",
					  confirmButtonText: "Aceptar",
					  closeOnConfirm: false
					},
					function(){
						location.href = "vercarro.php";
					});
				}
				if(data.nuevo==1){
					swal({
					  title: "Excelente!",
					  text: "Producto añadido con éxito!",
					  type: "success",
					  showCancelButton: false,
					  confirmButtonColor: "#9bde94",
					  confirmButtonText: "Aceptar",
					  closeOnConfirm: false
					},
					function(){
						location.href = "vercarro.php";
					});
				}
			}
			$('.agrega_carro').click(function(){
				id_producto=$(this).val();
				var_presentacion = $('#presentacion'+id_producto).val();
				if(var_presentacion != ""){
					//stock=<? echo $stock_prueba;?>;
					/*stock=10;
					if ( stock < $('#cantidad'+id_producto).val()){
						swal({
						  title: "Atención!",
						  text: "La cantidad no puede ser mayor que el stock!",
						  type: "warning",
						  showCancelButton: false,
						  confirmButtonColor: "#9bde94",
						  confirmButtonText: "Aceptar",
						  closeOnConfirm: true
						},
						function(){
							//location.href = "vercarrito.php";
						});
					}
					else{*/
						if ($('#cantidad'+id_producto).val() == ''){ 
							swal({
							  title: "Atención!",
							  text: "Debe ingresar una cantidad!",
							  type: "warning",
							  showCancelButton: false,
							  confirmButtonColor: "#9bde94",
							  confirmButtonText: "Aceptar",
							  closeOnConfirm: true
							},
							function(){
								//location.href = "vercarrito.php";
							});
						}
						else{
							if ($('#cantidad'+id_producto).val() == 0){
								cantidad=1;
							}
							else{
								cantidad=$('#cantidad'+id_producto).val();
							}
							
							$.ajax({
								type: 'POST',
								url: ("agrega_carro.php"),
								data:"producto="+id_producto+"&cantidad="+cantidad+"&presentacion="+var_presentacion,
								dataType:'json',
								success: function(data) {
									//location.href='vercarrito.php';
									resultado(data);
								}
							})
						}
					//}
				}
				else{
					swal({
					  title: "Atención!",
					  text: "Debe seleccionar una presentación del producto!",
					  type: "warning",
					  showCancelButton: false,
					  confirmButtonColor: "#9bde94",
					  confirmButtonText: "Aceptar",
					  closeOnConfirm: true
					},
					function(){
						//location.href = "vercarrito.php";
					});
				}
			});
			$('.presentacion_clase').change(function() {
				var_presentacion = $(this).val();
				var_id_producto = $(this).attr('name');
				$('#precio'+var_id_producto).html('<img src="img/ajax-loader.gif">');
				$.ajax({
					type: 'POST',
					url: ("procesa_precio.php"),
					data:"presentacion="+var_presentacion,
					success: function(data) {
						$('#precio'+var_id_producto).html(data)
					}
				})
			});
		});
</script>
  </body>
</html>
