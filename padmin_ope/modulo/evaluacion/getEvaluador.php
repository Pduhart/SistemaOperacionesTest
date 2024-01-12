<?php
require "../../config.php"; 
 include _INCLUDE."class/conexion.php";

   try {
        $conexion = new conexion();
        if(isset($_POST['cargo'])){
        $response = conexion::select("SELECT * FROM usuario_usuario WHERE id_per = ".$_POST['cargo']." and id_est_usu = 1 and id_usu not in(17,18,2)");
        $html='';
        foreach($response as $resp){            
           $html .= utf8_encode($resp['nombre_usu']).' '.utf8_encode($resp['apellido1_usu']).' '.utf8_encode($resp['apellido2_usu']);            
        }   
        echo $html; 
    }else{
        echo 'no hay valor';
     }    
    } 
    catch(\PDOException $e) { echo $e->getMessage();}
    catch(Exception $e){echo $e->getMessage();}    
 
 ?>