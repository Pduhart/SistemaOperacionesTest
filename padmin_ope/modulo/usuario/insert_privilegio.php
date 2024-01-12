<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_usuario_panel"])){
    header("Location: ../../panel.php");
}
if(!isset($_POST["modulo_usuario"])){
	?>
	<script type="text/javascript">
		window.loproion="../../index.php";
	</script>
	<?
	exit();
}
include '../../class/conexion.php';
$conexion = new conexion();
$check = $_POST["modulo_usuario"];
$id = $_POST["id_usuario"];
$cantidad = $_POST["cantidad"];
$modulo_usuario = explode(',',$check);
$contador = 0;
$consulta = "DELETE FROM usuario_usuario_modulo WHERE id_usu = ".$id."";
$conexion->consulta($consulta);
while($contador <= $cantidad){
	$consulta = "INSERT INTO usuario_usuario_modulo VALUES(".$id.",".$modulo_usuario[$contador].")";
	$conexion->consulta($consulta);
	$contador++;
}
unset($_SESSION["modulo_proyecto_panel"]);
unset($_SESSION["modulo_cliente_panel"]);
unset($_SESSION["modulo_usuario_panel"]);
unset($_SESSION["modulo_categoria_panel"]);
unset($_SESSION["modulo_subcategoria_panel"]);
unset($_SESSION["modulo_contacto_panel"]);
unset($_SESSION["modulo_ingreso_panel"]);
unset($_SESSION["modulo_egreso_panel"]);
unset($_SESSION["modulo_noticia_panel"]);
unset($_SESSION["modulo_seccion_panel"]);
unset($_SESSION["modulo_producto_panel"]);
unset($_SESSION["modulo_galeria_panel"]);
unset($_SESSION["modulo_documento_panel"]);
unset($_SESSION["modulo_evento_panel"]);
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>