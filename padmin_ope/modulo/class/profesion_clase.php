<?php
require "../../config.php";
include("../../class/conexion.php");
class profesion
{
	private $nombre_pro;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function profesion_crea($nombre_pro){
		$this->nombre_pro = $nombre_pro;
	}
	//funcion de insercion
	public function profesion_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_prof FROM profesion_profesion WHERE nombre_prof = ?";
		$conexion->consulta_form($consulta,array($this->nombre_pro));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO profesion_profesion VALUES(?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->nombre_pro));
		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function profesion_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_prof FROM profesion_profesion WHERE id_prof = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_profesion = $fila["nombre_prof"];	
		
		if($this->nombre_pro != $nombre_profesion){
			$consulta = "SELECT nombre_prof FROM profesion_profesion WHERE nombre_prof = ?";
			$conexion->consulta_form($consulta,array($this->nombre_pro));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}
		$consulta = "UPDATE profesion_profesion SET nombre_prof = ? WHERE id_prof = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_pro,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function profesion_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM profesion_profesion WHERE id_prof = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function profesion_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_seg FROM profesion_profesion WHERE id_prof = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_profesion = $fila["id_est_seg"];	

		if($estado_profesion == 1){
			$consulta = "UPDATE profesion_profesion SET id_est_seg = 2 WHERE id_pro = ?";	
		}
		else{
			$consulta = "UPDATE profesion_profesion SET id_est_seg = 1 WHERE id_pro = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_prof FROM profesion_profesion ORDER BY id_prof DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_pro'];
		$conexion->cerrar();
		return $id;
	}
}
?>
