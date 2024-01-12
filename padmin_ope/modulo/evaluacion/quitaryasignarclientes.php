<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}

// conexiones
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();




try {

    /**
     * 
     *   Proceso de desasignar clientes y reasignarlos a los ejecutivos de ventas respectivos
     *   este proceso se debe modificar cada vez que se requiera
     *   para empezar se guardan todos los clientes por rut(containerRut) y por id(containerId)
     *
     */
     #Acumular
 

    // // id del vendedor al cual se le quitaran y reasignaran los clientes
    $id = 10; //Maria Jose Callejas
    $idis = conexion::select("SELECT id_pro FROM vendedor_propietario_vendedor WHERE id_vend=".$id);
    $ruts = conexion::select("SELECT rut_pro as rut FROM vendedor_rutpropietario_vendedor WHERE id_vend=".$id);
    $count = 0;
    $containerRut = array();
    $containerId = array();

    foreach($ruts as $rut){
        array_push($containerRut,$rut['rut']);                          
    }    
    foreach($idis as $ID)
    {       
        array_push($containerId,$ID['id_pro']);                          
    }    
    

    # 10-11-2022 
    # vendedores disponibles para traspaso de clientes
    # Kriss - Jeannisse y Erica

    /**
     * 
     * EL siguiente proceso es dividir la cartera de clientes por el total de ejecutivos 
     * a los cuales se les reasignaran los clientes
     * 
     */


    #repartir ruts

    $total = COUNT($containerRut);    
    $division = round($total / 3);
    $division2 = $division*2;

    # primer tercio de clientes
    # 0 -> primerTercio
    # kriss -> 15      

    for ($i=0; $i < $division ; $i++) { 
        #borrar registro   
        $conexion->consulta_form('DELETE FROM vendedor_rutpropietario_vendedor WHERE rut_pro=?',array($containerRut[$i]));
        #reasignar
        $conexion->consulta_form('INSERT INTO vendedor_rutpropietario_vendedor(id_vend,rut_pro)VALUES(?,?)',array(15,$containerRut[$i]));
    }

    # segundo tercio de clientes
    # primerTercio -> segundoTercio
    # jeannisse -> 13

    for ($i=$division; $i < $division2 ; $i++) { 
        #borrar registro   
        $conexion->consulta_form('DELETE FROM vendedor_rutpropietario_vendedor WHERE rut_pro=?',array($containerRut[$i]));
        #reasignar
        $conexion->consulta_form('INSERT INTO vendedor_rutpropietario_vendedor(id_vend,rut_pro)VALUES(?,?)',array(13,$containerRut[$i]));
    }

    # tercer tercio de clientes
    # segundoTercio -> el total
    # erica -> 20

    for ($i=$division2; $i < $total ; $i++) { 
        #borrar registro   
        $conexion->consulta_form('DELETE FROM vendedor_rutpropietario_vendedor WHERE rut_pro=?',array($containerRut[$i]));
        #reasignar
        $conexion->consulta_form('INSERT INTO vendedor_rutpropietario_vendedor(id_vend,rut_pro)VALUES(?,?)',array(20,$containerRut[$i]));
    }


    #repartir aidis 


    $totalID = COUNT($containerId);   
    $divisionID = round($totalID / 3);
    $divisionID2 = $divisionID*2;

    # primer tercio de clientes
    # 0 -> primerTercio
    # kriss -> 15

    for ($i=0; $i < $divisionID ; $i++) { 
        #borrar registro   
        $conexion->consulta_form('DELETE FROM vendedor_propietario_vendedor WHERE id_pro=?',array($containerId[$i]));
        #reasignar
        $conexion->consulta_form('INSERT INTO vendedor_propietario_vendedor(id_vend,id_pro)VALUES(?,?)',array(15,$containerId[$i]));
    }

    # segundo tercio de clientes
    # primerTercio -> segundoTercio
    # jeannisse -> 13

    for ($i=$divisionID; $i < $divisionID2 ; $i++) { 
       #borrar registro   
       $conexion->consulta_form('DELETE FROM vendedor_propietario_vendedor WHERE id_pro=?',array($containerId[$i]));
       #reasignar
       $conexion->consulta_form('INSERT INTO vendedor_propietario_vendedor(id_vend,id_pro)VALUES(?,?)',array(13,$containerId[$i]));
    }

    # tercer tercio de clientes
    # segundoTercio -> el total
    # erica ->20

    for ($i=$divisionID2; $i < $totalID ; $i++) { 
       #borrar registro   
       $conexion->consulta_form('DELETE FROM vendedor_propietario_vendedor WHERE id_pro=?',array($containerId[$i]));
       #reasignar
       $conexion->consulta_form('INSERT INTO vendedor_propietario_vendedor(id_vend,id_pro)VALUES(?,?)',array(20,$containerId[$i]));
    }
    

} catch (\Throwable $th) {
    throw $th;
} catch(Exception $e){
    echo $e->getMessage();
} catch (PDOException $e){
    echo $e->getMessage();
}

// echo (count($containerRut) + count($containerId)).' registros modificados!';
echo 'vendedor_rutpropietario_vendedor modificado!';

?>