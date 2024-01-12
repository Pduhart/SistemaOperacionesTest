<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_pro = $_POST["valor"];

$consulta = 
    "
    SELECT 
        * 
    FROM 
        propietario_propietario
    WHERE
        id_pro = ?
    ";//echo $consulta;
$conexion->consulta_form($consulta,array($id_pro));
$fila = $conexion->extraer_registro_unico();
?>
<div class="col-sm-4">
    <div class="form-group">
        <label for="rut">Rut:</label>
        <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $fila['rut_pro'];?>" disabled />
    </div>
    <div class="form-group">
        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo utf8_encode($fila['apellido_materno_pro']);?>"/>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo utf8_encode($fila['nombre_pro']);?>"/>
    </div>
    <div class="form-group">
        <label for="correo">Correo:</label>
        <input type="text" name="correo" class="form-control" id="correo" value="<?php echo utf8_encode($fila['correo_pro']);?>"/>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" value="<?php echo utf8_encode($fila['apellido_paterno_pro']);?>"/>
    </div>
    
    <div class="form-group">
        <label for="fono">Fono:</label>
        <input type="text" name="fono" class="form-control numero" id="fono" value="<?php echo utf8_encode($fila['fono_pro']);?>"/>
    </div>
</div>
<h5>* Puede modificar la información básica del cliente seleccionado</h5>
<?php

?>

