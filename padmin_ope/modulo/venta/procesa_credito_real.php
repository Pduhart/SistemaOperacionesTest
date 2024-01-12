<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_for_pag = $_POST["valor"];
$monto_credito_original = $_POST["monto_credito_original"];
if ($id_for_pag == 1) {
?>
<div class="form-group">
    <label for="credito_real">Monto Crédito Real:</label>
    <input type="text" name="credito_real" class="form-control numero" id="credito_real" value="<?php echo $monto_credito_original;?>"/>
</div>
<?php
}
else if ($id_for_pag == 2){
?>

<?php
}

