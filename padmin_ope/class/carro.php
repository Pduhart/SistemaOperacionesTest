<?php
class carrito {
    
    var $array_id_ite;
    var $array_detalle_ite;
    var $array_valor_ite;

    function __construct() {
		//$_SESSION["numero_producto"]=0;
       	//$this->num_productos=0;
    }

    

    function introduce_item($id_ite,$detalle_ite,$valor_ite){
		if(!isset($_SESSION["numero_item"])){
			$numero=0;
			$_SESSION["numero_item"] = 1;
		}
		else{
			if($_SESSION["numero_item"]==1){
				$numero = $_SESSION["numero_item"];
				$_SESSION["numero_item"] = $_SESSION["numero_item"] + 1;
			}
			else{

				$_SESSION["numero_item"] = $_SESSION["numero_item"] + 1;
				$numero = $_SESSION["numero_item"];
   				$numero = $numero - 1;
			}
			
		}
       
	   $this->array_id_ite[$numero] = $_SESSION["numero_item"];
       $this->array_detalle_ite[$numero] = $detalle_ite;
       
       $this->array_valor_ite[$numero] = $valor_ite; 
    }
	 
    function elimina_item($borrar){
		$borrar=$borrar-1;
		$this->array_id_ite[$borrar]=0;
    }
}
?>