<?php
require "../../config.php"; 
 include _INCLUDE."class/conexion.php";
 $conexion = new conexion();
 
    try {
        $response = conexion::select("SELECT * FROM vendedor_vendedor WHERE id_est_vend=1 and id_vend not in(5,3)");
        $html='<option value="" selected>Seleccione persona a evaluar</option>';
        foreach($response as $resp){            
           $html .= '<option value="'.$resp['id_vend'].'">'.utf8_encode($resp['nombre_vend']).' '.utf8_encode($resp['apellido_paterno_vend']).' '.utf8_encode($resp['apellido_materno_vend']).'</option>';            
        }   
        echo $html;     
    } 
    catch(\PDOException $e) { echo $e->getMessage();}
    catch(Exception $e){echo $e->getMessage();}    
 
 ?>