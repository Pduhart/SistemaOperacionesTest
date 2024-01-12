<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_usuario_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/usuario_clase.php");
$usuario = new usuario();
$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$apellido1 = isset($_POST["apellido1"]) ? utf8_decode($_POST["apellido1"]) : "";
$apellido2 = isset($_POST["apellido2"]) ? utf8_decode($_POST["apellido2"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$contrasena = isset($_POST["contrasena"]) ? utf8_decode($_POST["contrasena"]) : "";
$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : 0;

$usuario->usuario_crea($rut,1,$nombre,$apellido1,$apellido2,"",$correo,"",$contrasena,$categoria);

$usuario->usuario_update($id);

// if (isset($_POST['unidad'])) {
//     if (is_array($_POST['unidad'])) {
//         foreach ($_POST['unidad'] as $key => $id_uni) {
//         	$usuario->usuario_insert_unidad($id, $id_uni); 
//         }
//     }
// }

$usuario->usuario_delete_condominio($id);

$modulo_condominio = $_POST['modulo_condominio']; 
if (is_array($modulo_condominio)){
	foreach($modulo_condominio as $i){ 
		$usuario->usuario_insert_condominio($id,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>