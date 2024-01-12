<?php
require "../../config.php"; 
 include _INCLUDE."class/conexion.php";

   try {
        $conexion = new conexion();
        if(isset($_POST['vendedor'])){
            $response = conexion::select("SELECT * FROM matriz_desarrollo WHERE id_vendedor = ".$_POST['vendedor']." ORDER BY id desc LIMIT 1");
            response($response,true);
        }else{
            response('no es vendedor',false);
        }    
    } 
    catch(\PDOException $e) { response($e->getMessage(),false); } 
    catch(Exception $e){ response($e->getMessage(),false); }    
 
    function response($message,$state){
        $objJson['data']=$message;
        $objJson['state']=$state;
        echo json_encode($objJson);
    }
 ?>