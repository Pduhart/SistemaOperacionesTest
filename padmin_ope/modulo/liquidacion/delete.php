<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_liquidacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
if(!isset($_POST["valor"])){
	?>
	<script type="text/javascript">
		window.location="<?php echo _ADMIN?>index.php";
	</script>
	<?
	exit();
}
include("../class/liquidacion_clase.php");
$liquidacion = new liquidacion();
$id_emp = $_POST["valor"];
$liquidacion->liquidacion_delete($id_emp);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>