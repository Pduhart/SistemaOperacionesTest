<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_usuario_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["perfil"])){
	header("Location: ../../panel.php");
	exit();
}

include("../class/usuario_clase.php");
$usuario = new usuario();
$perfil = isset($_POST["perfil"]) ? utf8_decode($_POST["perfil"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$apellido1 = isset($_POST["apellido1"]) ? utf8_decode($_POST["apellido1"]) : "";
$apellido2 = isset($_POST["apellido2"]) ? utf8_decode($_POST["apellido2"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";


$usuario->usuario_crea($rut,1,$nombre,$apellido1,$apellido2,$perfil,$correo,"","");

$usuario->usuario_insert();

$id_usuario = $usuario->recupera_id();

// if (isset($_POST['unidad'])) {
//     if (is_array($_POST['unidad'])) {
//         foreach ($_POST['unidad'] as $key => $id_uni) {
//         	$usuario->usuario_insert_unidad($id_usuario, $id_uni); 
//         }
//     }
// }

$modulo_condominio = $_POST['modulo_condominio']; 
if (is_array($modulo_condominio)){
	foreach($modulo_condominio as $i){ 
		$usuario->usuario_insert_condominio($id_usuario,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>