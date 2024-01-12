<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_for_pag = $_POST["valor"];
if ($id_for_pag == 1) {
?>
<div class="col-sm-6">
    <div class="form-group">
        <label for="banco">Banco:</label>
        <select class="form-control" id="banco" name="banco"> 
            <option value="">Seleccione Banco</option>
            <?php  
            $consulta = "SELECT * FROM banco_banco ORDER BY nombre_ban";
            $conexion->consulta($consulta);
            $fila_consulta = $conexion->extraer_registro();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                    ?>
                    <option value="<?php echo $fila['id_ban'];?>"><?php echo utf8_encode($fila['nombre_ban']);?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
</div>
<?php
}
else if ($id_for_pag == 2){
?>
<div class="col-sm-6">
    <div class="form-group">
        <label for="tipo_pago">Tipo de Pago:</label>
        <select class="form-control" id="tipo_pago" name="tipo_pago"> 
            <option value="">Seleccione Tipo de Pago</option>
            <?php  
            $consulta = "SELECT * FROM pago_tipo_pago ORDER BY nombre_tip_pag";
            $conexion->consulta($consulta);
            $fila_consulta = $conexion->extraer_registro();
            if(is_array($fila_consulta)){
                foreach ($fila_consulta as $fila) {
                    ?>
                    <option value="<?php echo $fila['id_tip_pag'];?>"><?php echo utf8_encode($fila['nombre_tip_pag']);?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>
</div>
<?php
}

