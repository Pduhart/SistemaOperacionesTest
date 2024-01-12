<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

unset($_SESSION['id_cliente_filtro_ficha_panel']);


?>