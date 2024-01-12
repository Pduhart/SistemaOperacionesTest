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
if(!isset($_POST["vendedor"])){
	header("Location: ../../panel.php");
	exit();
}
include("../class/vendedor_clase.php");

$vendedor = new vendedor();

$id_vend = isset($_POST["vendedor"]) ? utf8_decode($_POST["vendedor"]) : "";



// $vendedor->vendedor_delete_cliente($id_vend);

$clientes_nuevos = $_POST['clientes_nuevos']; 
$cliente_actuales = $_POST['clientes_actuales']; 
// echo "hola:".$cliente_actuales;

// $cliente_actuales = explode(",", $cliente_actuales);
$clientes_nuevos = explode(",", $clientes_nuevos);
$cliente_actuales_arr = explode(",", $cliente_actuales);

// print_r($cliente_actuales_arr);


// echo "----------------------------------";

// print_r($clientes_nuevos);

// echo "nuevos_cliente-->".is_array($clientes_nuevos);

// echo "<br>cliente_actuales-->".is_array($cliente_actuales);
// // $mod_cliente = explode(",", $nuevos_cliente);

// print_r($cliente_actuales);

// echo "---------------------cantidad nuevos:";
// echo count($clientes_nuevos);

// echo "<br>----------------------------cantidad actuales:";
// echo count($cliente_actuales);
// echo "----------------------------------";
// echo "------------------Los que salieron----------------";

$egresos = array_diff($cliente_actuales_arr, $clientes_nuevos);

// echo "------------Los que entraron----------------------";

if (is_array($egresos)){
	foreach($egresos as $i){ 
		// echo $i."<br>";
		$vendedor->vendedor_delete_lista_asig($id_vend,$i);
	} 
}


$ingresos = array_diff($clientes_nuevos, $cliente_actuales_arr);

// $mod_cliente_act = explode(",", $cliente_actuales);
// echo is_array($modulo_cliente) ? 'Array' : 'No es un array';


if (is_array($ingresos)){
	foreach($ingresos as $i){ 
		// echo $i."<br>";
		$vendedor->vendedor_insert_lista($id_vend,$i);
	} 
}

// if (is_array($mod_cliente)){
// 	foreach($mod_cliente as $i){ 
// 		// echo $i."<br>";
// 		$vendedor->vendedor_insert_cliente($id_vend,$i);
// 	} 
// }

$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?>