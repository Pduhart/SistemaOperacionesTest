<?php
include "class/carrophp/clase_carro.php";
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Soriasis, tratamiento eficaz. Tratamiento tópico.</title>
	<?php include 'header_comun.php'; ?>
    <!-- Custom styles for this template -->
<link href="css/page/main.css" rel="stylesheet">
<link href="css/page/vercarro.css" rel="stylesheet">
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
        	<div class="col-sm-12 izquierda">
            <h3>Su Carro de Compras</h3>
            <a href="carro.php" class="btn btn-sm btn-info pull-right" role="button"><i class="fa fa-chevron-left"></i> Volver</a>
            <?php
			if (isset($_SESSION["ocarrito"])){
			?>
                <table class="table table-striped det">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Producto</th>
                        <th>Presentación</th>
                        <th>Precio U.</th>
                        <th>Cantidad</th>
                        <th>Precio Final</th>
                        <th>Actualizar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                <?php
				if(!isset($_SESSION["numero_producto"])){
					$numero=1;
				}
				else{
					$numero = $_SESSION["numero_producto"];
				}
				for ($i=0;$i<$numero;$i++){
					if($_SESSION["ocarrito"]->array_id_prod[$i]!=0){
						$precio=$_SESSION["ocarrito"]->array_precio_prod[$i];
						$cantidad=$_SESSION["ocarrito"]->array_cantidad_prod[$i];
						$cantidad_total = $cantidad_total + $cantidad;
						$total=$cantidad * $precio;
						
						$id_producto=$_SESSION["ocarrito"]->array_id_prod[$i];
						$total_compra= $total_compra + $total;
						$precio= number_format($precio, 0, '', '.');
						$total= number_format($total, 0, '', '.');
						$talla=$_SESSION["ocarrito"]->array_talla_prod[$i];
						
						//$sexo=$_SESSION["ocarrito"]->array_sexo_prod[$i];
						$query = 
							"
							SELECT 
								nombre2_pro
							FROM 
								producto_producto 
							WHERE 
								id_pro = '".$id_producto."'
							";
						$conexion->consulta($query);
						while ($fila = $conexion->extraer_registro()){
							$codigo_pro = $fila["nombre2_pro"];
						}
						$query_talla = 
							"
							SELECT 
								tamanio_tamanio.nombre_tam,
								producto_tamanio.precio_pro_tam
							FROM 
								tamanio_tamanio,
								producto_tamanio 
							WHERE 
								producto_tamanio.id_pro_tam = '".$talla."' AND
								tamanio_tamanio.id_tam = producto_tamanio.id_tam
							";
						$conexion->consulta($query_talla);
						$cantidad_tamanio = $conexion->total();
						if ($cantidad_tamanio<>"0") {
							while ($rmon_talla = $conexion->extraer_registro()){
								$nombre_tamanio = utf8_encode($rmon_talla["nombre_tam"]);
								$precio_chile = utf8_encode($rmon_talla["precio_pro_tam"]);
								$total_chile = $cantidad * $precio_chile;
								$total_chile_formato = number_format($total_chile, 0, '', '.');
								$precio_chile_formato = number_format($precio_chile, 0, '', '.');
							}
						}
						/*
						$this->array_id_prod[$numero]=$id_prod;
						   $this->array_nombre_prod[$numero]=$nombre_prod;
						   $this->array_precio_prod[$numero]=$precio_prod;
						   $this->array_cantidad_prod[$numero]=$cantidad_prod;
						   $this->array_foto_prod[$numero]=$foto_prod;
						   $this->array_talla_prod[$numero]=$talla_prod;
						*/
						$total_compra_chile = $total_compra_chile + $total_chile;
						$total_compra_chile_formato = number_format($total_compra_chile, 0, '', '.');
						?>
                        
                            <tr>
                                <td class="hidden-xs"><img src="archivo/producto/portada/<?php echo $_SESSION["ocarrito"]->array_id_prod[$i];?>/thumbnail/<?php echo $_SESSION["ocarrito"]->array_foto_prod[$i];?>" class="img-responsive" width="100" height="100"></td>
                                <td style="width:18%"><h2><?php echo utf8_encode($_SESSION["ocarrito"]->array_nombre_prod[$i]);?></h2>
                                    <h5>Código: <?php echo $codigo_pro;?></h5>
                                </td>
                                <td><?php echo $nombre_tamanio;?></td>
                                <td>US$ <?php echo $precio;?>.-<br><span style="font-size:.85em">(Fuera de Chile)</span><br>
                                CH$ <?php echo $precio_chile_formato;?>.-<br><span style="font-size:.85em">(Compras en Chile)</span></td>
                                <td><input id="<?php echo $id_producto;?>" name="<?php echo $talla;?>" type="text" size="1" value="<?php echo $cantidad;?>" class="form-control input-sm"></td>
                                <td>US$ <?php echo $total;?>.-<br>CH$ <?php echo $total_chile_formato;?>.-</td>
                                <td style="width:8%"><input class="modificar" type="image" name="<?php echo $id_producto;?>" value="<?php echo $talla;?>" src="img/actualiza.png"></td>
                                <td style="width:8%"><input class="eliminar" type="image" name="<?php echo $id_producto;?>" value="<?php echo $talla;?>"  src="img/elimina.png"></td>
                            </tr>
							<?
                    }
                    else{
                        $eliminado++;
                    }
                }
				?>
                </tbody>
                </table>
                <?php
                $numero_producto = $_SESSION["numero_producto"];
                $total_compra = number_format($total_compra, 0, '', '.');
                if($numero_producto > $eliminado){
                    $numero_producto = $numero_producto - $eliminado;
					?>
                    <table class="table table-condensed total">
                        <tr>
                        <td class="hidden-xs" style="width:50%;"></td>
                        <td class="total-texto">Total Producto:</td>
                        <td class="total"><?=$cantidad_total?></td>
                        </tr>
                        <tr>
                        <td></td>
                        <td class="total-texto">Sub-Total:</td>
                        <td class="total">US$ <?=$total_compra?>.- / CH$ <?=$total_compra_chile_formato?>.-</td>
                        </tr>
					</table>
                    <a href="carro.php" role="button" class="btn btn-success">Seguir Comprando</a> <a href="resumen.php" role="button" class="btn btn-success">Realizar Pago</a>
                    <p style="margin-top:10px; font-size:14px;"><b>Compras en Chile</b>, el costo de envió es por cobrar. Valor <b>fuera de Chile (US$)</b> incluye costo de envió.</p>
					<?
                }
                else{
                    echo "<div class=\"no_hay\" style=\"margin-top:45px;\">No hay productos agregados al carro de compra.</div>";
                }
            }
            else{
                echo "<div class=\"no_hay\" style=\"margin-top:45px;\">No hay productos agregados al carro de compra.</div>";
            }
            ?>
            </div>
        </div>
    </div>
</section>

<footer>
<?php include 'footer_comun.php'; ?>
</footer>
<?php include 'js_comun.php'; ?>
<script src="js/alert/sweet-alert.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		function resultado(data) {
			if(data.error==1){
				swal("Atención!", "Producto existente, cantidad mayor al stock","warning");
				//alert("Producto existente, cantidad mayor al stock");	
			}
			if(data.error==2){
				swal("Atención!", "Producto existente, cantidad mayor al máximo de pedido","warning");
				//alert("Producto existente, cantidad mayor al stock");	
			}
			if(data.ok==1){
				swal({
				  title: "Excelente!",
				  text: "Carro actualizado con éxito!",
				  type: "success",
				  showCancelButton: false,
				  confirmButtonColor: "#9bde94",
				  confirmButtonText: "Aceptar",
				  closeOnConfirm: false
				},
				function(){
					//location.href = "vercarro.php";
					location.reload();	
				});
			}
		}
		$('.modificar').click(function(){
			id=$(this).attr('name');
			talla=$(this).val();
			nombre=$("#"+id).attr('id');
			cantidad=$("input[type='text'][name="+talla+"][id="+id+"]").val();
			$.ajax({
				type: 'POST',
				url: ("actualizar_carro.php"),
				data:"id="+id+"&talla="+talla+"&cantidad="+cantidad,
				dataType:"json",
				success: function(data) {
					resultado(data);
				}
			})
		});
		$('.eliminar').click(function(){
			id=$(this).attr('name');
			talla=$(this).val();
			swal({
				title: "Está Seguro?",
				text: "Desea eliminar el registros seleccionado!",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Aceptar',
				cancelButtonText: "Cancelar",
				closeOnConfirm: false,
				//closeOnCancel: false
			},
			function(){
					$.ajax({
					type: 'POST',
					url: ("eliminar_carro.php"),
					data:"id="+id+"&talla="+talla,
					dataType:"json",
					success: function(data) {
						resultado(data);
					}
				})
			});
			
		});
	});
</script>
  </body>
</html>