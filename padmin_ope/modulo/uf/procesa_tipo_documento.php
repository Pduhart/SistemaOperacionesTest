<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_tip_doc = $_POST["valor"];
//include_once _INCLUDE."js_comun.php";
?>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#rut').Rut({
            });
    });
</script>
<?php
// comprobante de recaudacion
if($id_tip_doc == 2){
	?>
	<div class="form-group">
        <label for="rut">Rut:</label>
        <input type="text" name="rut" class="form-control rut" id="rut"/>
    </div>
    <div class="form-group">
        <label for="nombre_cliente">Nombre Cliente:</label>
        <input type="text" name="nombre_cliente" class="form-control" id="nombre_cliente" />
    </div>
	<?php
}
// facturacion
if($id_tip_doc == 3){
	?>
	<div class="form-group">
        <label for="rut">Rut:</label>
        <input type="text" name="rut" class="form-control rut" id="rut"/>
    </div>
    <div class="form-group">
        <label for="nombre_cliente">Nombre Cliente:</label>
        <input type="text" name="nombre_cliente" class="form-control" id="nombre_cliente" />
    </div>
    <div class="form-group">
        <label for="giro">Giro:</label>
        <input type="text" name="giro" class="form-control" id="giro" />
    </div>
    <div class="form-group">
        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" class="form-control" id="ciudad" />
    </div>
	<div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" class="form-control" id="direccion" />
    </div>
	<?php
}

?>

