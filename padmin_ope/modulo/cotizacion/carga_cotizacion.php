<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id_pro = $_POST["valor"];
//-------- LOS MODELOS SON SOLO A y B
$consulta = 
    "
    SELECT 
        * 
    FROM 
        carga_carga
    ";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        $fila['nombre_pro'];
        $array_dependencia = explode("-", $fila['dependencia']);
        $array_modelo = explode("/", $fila['modelo']);
        $array_nombre = explode(" ", $fila['cliente']);
        $cantidad_dependencia = count($array_dependencia);
        $cantidad_nombre = count($array_nombre);
        $nombre1 = '';
        $nombre2 = '';
        $apellido1 = '';
        $apellido2 = '';
        if($cantidad_dependencia > 0){
            $primera_letra = substr($array_dependencia[0], 0, 1);
            if($primera_letra == 'D'){
                // buscar id de vivienda 
            }
        }
        


        

        switch ($cantidad_nombre) {
            case 1:
                $nombre1 = $array_nombre[0];
                break;
            case 2:
                $nombre1 = $array_nombre[0];
                $apellido1 = $array_nombre[1];
                break;
            case 3:
                $nombre1 = $array_nombre[0];
                $apellido1 = $array_nombre[1];
                $apellido2 = $array_nombre[2];
                break;
            case 4:
                $nombre1 = $array_nombre[0];
                $nombre2 = $array_nombre[1];
                $apellido1 = $array_nombre[2];
                $apellido2 = $array_nombre[3];
                break;
            case 5:
                $nombre1 = $array_nombre[0];
                $nombre2 = $array_nombre[1]." ".$array_nombre[2];
                $apellido1 = $array_nombre[3];
                $apellido2 = $array_nombre[4];
                break;
            case 6:
                $nombre1 = $array_nombre[0];
                $nombre2 = $array_nombre[1]." ".$array_nombre[2]." ".$array_nombre[3];
                $apellido1 = $array_nombre[4];
                $apellido2 = $array_nombre[5];
                break;
            
        }

        $consulta = "INSERT INTO propietario_propietario VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//21
        $conexion->consulta_form($consulta,array(0,174,$id_com,1,1,5,1,1,$fila['rut'],'',$nombre1,$nombre2,$apellido1,$apellido2,$fila['fono'],'',$fila['correo'],'','','','0000-00-00'));
        $id = $conexion->ultimo_id();

        $fecha_promesa = "0000-00-00 00:00:00";
        $consulta = "INSERT INTO cotizacion_cotizacion VALUES(?,?,?,?,?,?,?,?,?)";
        /*$jsondata['envio'] = $consulta;
        echo json_encode($jsondata);
        exit();*/
        $fecha_cot = $fila['fecha'];

        $conexion->consulta_form($consulta,array(0,$id_viv,$fila['modelo'],$id_vendedor,$id,$fila['canal'],1,$fecha_cot,$fecha_promesa));


        if($_SESSION["sesion_perfil_panel"] == 4){
            $consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
            $conexion->consulta_form($consulta,array(0,$_SESSION["sesion_id_panel"],$id));  
        }
    }
}
?>

