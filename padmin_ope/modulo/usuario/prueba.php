<?php  


$nombre = "María Jóse";
$apellido_paterno = "Yáñez";
// $nombre_usuario = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre);

$letra_nombre = substr($nombre,0,1);
$usuario = $letra_nombre.$apellido_paterno;

// $usuario = strtolower($usuario); 
// echo $usuario;
$usuario = preg_replace('/\&(.)[^;]*;/', '\\1', $usuario);

$usuario = str_replace("ñ","n",$usuario);
$usuario = str_replace("Ñ","N",$usuario);

$usuario = preg_replace("/[áàâãª]/","a",$usuario);
$usuario = preg_replace("/[ÁÀÂÃ]/","A",$usuario);
$usuario = preg_replace("/[ÍÌÎ]/","I",$usuario);
$usuario = preg_replace("/[íìî]/","i",$usuario);
$usuario = preg_replace("/[éèê]/","e",$usuario);
$usuario = preg_replace("/[ÉÈÊ]/","E",$usuario);
$usuario = preg_replace("/[óòôõº]/","o",$usuario);
$usuario = preg_replace("/[ÓÒÔÕ]/","O",$usuario);
$usuario = preg_replace("/[úùû]/","u",$usuario);
$usuario = preg_replace("/[ÚÙÛ]/","U",$usuario);
$usuario = str_replace("ç","c",$usuario);
$usuario = str_replace("Ç","C",$usuario);
$usuario = strtolower($usuario); 
echo $usuario;
?>