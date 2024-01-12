<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
include("../class/vendedor_clase.php");
include("../class/usuario_vendedor_clase.php");
$usuario = new usuario();
$vendedor = new vendedor();

$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;
$id_usu = isset($_POST["id_usu"]) ? utf8_decode($_POST["id_usu"]) : 0;
$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$apellido1 = isset($_POST["apellido1"]) ? utf8_decode($_POST["apellido1"]) : "";
$apellido2 = isset($_POST["apellido2"]) ? utf8_decode($_POST["apellido2"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$fono = isset($_POST["fono"]) ? utf8_decode($_POST["fono"]) : "";
$contrasena = isset($_POST["contrasena"]) ? utf8_decode($_POST["contrasena"]) : "";




$usuario->usuario_crea($rut,1,$nombre,$apellido1,$apellido2,"",$correo,"",$contrasena,$categoria);
$usuario->usuario_update($id_usu);


//Vendedor
$vendedor->vendedor_crea($id,$rut,$categoria,1,$nombre,$apellido1,$apellido2,$fono,$correo);
$vendedor->vendedor_update($id);


$vendedor->vendedor_delete_condominio($id);

$modulo_condominio = $_POST['modulo_condominio']; 
if (is_array($modulo_condominio)){
	foreach($modulo_condominio as $i){ 
		$vendedor->vendedor_insert_condominio($id,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>