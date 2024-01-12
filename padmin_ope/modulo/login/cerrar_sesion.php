<?php
session_start();
unset($_SESSION["sesion_usuario_panel"]);
unset($_SESSION["sesion_id_panel"]);
unset($_SESSION["sesion_nombre_perfil_panel"]);
unset($_SESSION["sesion_id_vend"]);
header('Location: ../../index.php');
?>