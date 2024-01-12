<?php
 require "../../config.php"; 
 include _INCLUDE."class/conexion.php";
 $conexion = new conexion();
 
    try {
        if(isset($_POST['vendedor']) && $_POST['opt'] == 1){
            $resp = conexion::select("SELECT id_mes as mes,valor_met_ven as meta FROM vendedor_meta_vendedor WHERE id_vend = ".$_POST['vendedor']." and anio_mes =".date('Y'));
            if(is_array($resp)){
                if(count($resp)>0){
                    response($resp,true);
                }else{
                    response('sin registros',false);
                }            
            }else{
                response('sin data',false);
            }    
        }else if(isset($_POST['vendedor']) && $_POST['opt'] == 2){
            $cierres = conexion::select("SELECT id_cie as id,id_mes as mes FROM cierre_cierre WHERE anio_cie=".date('Y'));
            $resp = [];
            $data = [];
            foreach ($cierres as $cierre ) {
                $meta = conexion::select("SELECT COUNT(*) as metaAlcanzada FROM venta_venta WHERE id_vend=".$_POST['vendedor']." and id_ven in(SELECT id_ven FROM cierre_venta_cierre WHERE id_cie=".$cierre['id']." and id_est_ven>3)");                
                $data['mes'] = $cierre['mes'];
                $data['meta'] = $meta[0]['metaAlcanzada'];                
                array_push($resp,$data);
            }            
            response($resp,true);
        }else{
            response('falta el vendedor',false);
        }   
        
    } 
    catch(\PDOException $e) {response($e->getMessage(),false);}
    catch(Exception $e){response($e->getMessage(),false);}   
    
    function response($message,$state){
        $objJson['data']=$message;
        $objJson['state']=$state;
        echo json_encode($objJson);
    }
 