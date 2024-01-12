<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: ../../panel.php");
    exit();
}
if(!isset($_POST["nombre"])){
	header("Location: ../../panel.php");
	exit();
}
include("../class/vendedor_clase.php");
include("../class/usuario_vendedor_clase.php");

$usuario = new usuario();
$vendedor = new vendedor();
$perfil = 4;
$categoria = isset($_POST["categoria"]) ? utf8_decode($_POST["categoria"]) : 0;
$nombre = isset($_POST["nombre"]) ? utf8_decode($_POST["nombre"]) : "";
$apellido1 = isset($_POST["apellido1"]) ? utf8_decode($_POST["apellido1"]) : "";
$apellido2 = isset($_POST["apellido2"]) ? utf8_decode($_POST["apellido2"]) : "";
$rut = isset($_POST["rut"]) ? utf8_decode($_POST["rut"]) : "";
$correo = isset($_POST["correo"]) ? utf8_decode($_POST["correo"]) : "";
$fono = isset($_POST["fono"]) ? utf8_decode($_POST["fono"]) : "";


$usuario->usuario_crea($rut,1,$nombre,$apellido1,$apellido2,$perfil,$correo,"","",$categoria);
$usuario->usuario_insert();

$id_usuario = $usuario->recupera_id();


//Vendedor
$vendedor->vendedor_crea($id_usuario,$rut,$categoria,1,$nombre,$apellido1,$apellido2,$fono,$correo);
$vendedor->vendedor_insert();

$id_vendedor = $vendedor->recupera_id();

// cargarle jefe, usa a Manuel
$jefe = 19;
$vendedor->vendedor_insert_jefe($jefe,$id_vendedor);

$modulo_condominio = $_POST['modulo_condominio']; 
if (is_array($modulo_condominio)){
	foreach($modulo_condominio as $i){ 
		$vendedor->vendedor_insert_condominio($id_vendedor,$i);
	} 
}

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>