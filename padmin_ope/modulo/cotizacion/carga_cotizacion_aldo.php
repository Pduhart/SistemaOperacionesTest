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
        $fila['dependencia'];
        
        $array_nombre = explode(" ", $fila['cliente']);
        $cantidad_dependencia = count($array_dependencia);
        $cantidad_nombre = count($array_nombre);
        $nombre1 = '';
        $nombre2 = '';
        $apellido1 = '';
        $apellido2 = '';
        $cantidad_dependencia--;
        $modelo = $fila['modelo'];
        if($modelo == 'A'){
            $id_modelo = 1;
        }
        else{
            $id_modelo = 2;
        }
        
        
            $id_canal = 0;
        

        $query = "SELECT id_com FROM comuna_comuna WHERE nombre_com = '".$fila['region_cliente']."'";

        $conexion->consulta($query);
        $nrows = $conexion->total();
        if($nrows > 0){
            $fila_comuna = $conexion->extraer_registro_unico();
            $id_comuna = $fila_comuna["id_com"];
        }
        else{
            $id_comuna = 0;
        }


        if($fila['vendedor'] == 'FELIPE REYES TRIGO'){
            $id_vendedor = 1;
        }
        else if($fila['vendedor'] == 'LISETTE RIQUELME'){
            $id_vendedor = 2;
        }
        else if($fila['vendedor'] == 'MANUEL ALVAREZ CORTES'){
            $id_vendedor = 3;
        }
        else if($fila['vendedor'] == 'NATALIA PALOMINOS VASQUEZ'){
            $id_vendedor = 4;
        }
        $id_vivienda = 0;
        $query = "SELECT id_viv FROM vivienda_vivienda WHERE nombre_viv = '".$fila['dependencia']."' AND id_tor = 1 ";
                    
                    
        $conexion->consulta($query);
        $nrows = $conexion->total();
        if($nrows > 0){
            $fila_dependencia = $conexion->extraer_registro_unico();
            $id_vivienda = $fila_dependencia["id_viv"];
        }
        


        if($id_vivienda > 0){
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


            if(!empty($fila['rut'])){
                $primera_cadena = substr($fila['rut'], 0, 1);
                if($primera_cadena == '0'){
                    $rut = substr($fila['rut'], 1);
                }
                else{
                    $rut = $fila['rut'];
                }

                $query = "SELECT id_pro FROM propietario_propietario WHERE rut_pro = '".$rut."'";
                $conexion->consulta($query);
                $cantidad_cliente = $conexion->total();
                if($cantidad_cliente == 0){
                    $consulta = "INSERT INTO propietario_propietario VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//21
                    $conexion->consulta_form($consulta,array(0,174,$id_comuna,1,1,5,1,1,$rut,'',$nombre1,$nombre2,$apellido1,$apellido2,$fila['fono'],'',$fila['correo'],'','','',null));
                    $id_propietario = $conexion->ultimo_id();

                    $query = "SELECT id_pro_vend FROM vendedor_propietario_vendedor WHERE id_pro = '".$id_propietario."'";
                    $conexion->consulta($query);
                    $cantidad_cliente = $conexion->total();
                    if($cantidad_cliente == 0){
                        $consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
                        $conexion->consulta_form($consulta,array(0,$id_vendedor,$id_propietario));  
                    }
                    else{
                        $consulta = "DELETE FROM vendedor_propietario_vendedor WHERE id_pro = ?";
                        $conexion->consulta_form($consulta,array($id_propietario));

                        $consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
                        $conexion->consulta_form($consulta,array(0,$id_vendedor,$id_propietario));
                    }

                }
                else{
                    // $fila_cliente = $conexion->extraer_registro_unico();
                    // $id_propietario = $fila_cliente["id_pro"];

                }

            }

            $fecha_promesa = "0000-00-00 00:00:00";
            $consulta = "INSERT INTO cotizacion_cotizacion VALUES(?,?,?,?,?,?,?,?,?)";
            /*$jsondata['envio'] = $consulta;
            echo json_encode($jsondata);
            exit();*/

            if(!empty($fila['fecha'])){
                $fecha_cot = $fila['fecha'];
                $fecha_cot = date("Y-m-d",strtotime($fecha_cot));
                $fecha_cot = $fecha_cot." ".date("H:i:s");
            }
            else{
                $fecha_cot = null;
            }

            // $conexion->consulta_form($consulta,array(0,$id_vivienda,$id_modelo,$id_vendedor,$id_propietario,$id_canal,1,$fecha_cot,null));

            
            
        
        }
        
    }
}
?>

