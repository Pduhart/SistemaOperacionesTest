<?php
require "../../config.php";
include("../../class/conexion.php");
class premio
{
	private $nombre_pre;
	private $id_est_pre;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function premio_crea($id_est_pre,$nombre_pre){
		$this->id_est_pre = $id_est_pre;
		$this->nombre_pre = $nombre_pre;
	}
	//funcion de insercion
	public function premio_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_pre FROM premio_premio WHERE nombre_pre = ?";
		$conexion->consulta_form($consulta,array($this->nombre_pre));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO premio_premio VALUES(?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_est_pre,$this->nombre_pre));
		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function premio_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_pre FROM premio_premio WHERE id_pre = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_premio = $fila["nombre_pre"];	
		
		if($this->nombre_pre != $nombre_premio){
			$consulta = "SELECT nombre_pre FROM premio_premio WHERE nombre_pre = ?";
			$conexion->consulta_form($consulta,array($this->nombre_pre));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE premio_premio SET nombre_pre = ? WHERE id_pre = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_pre,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function premio_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM premio_premio WHERE id_pre = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_pre FROM premio_premio ORDER BY id_pre DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_pre'];
		$conexion->cerrar();
		return $id;
	}

	public function premio_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_pre FROM premio_premio WHERE id_pre = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_premio = $fila["id_est_pre"];	

		if($estado_premio == 1){
			$consulta = "UPDATE premio_premio SET id_est_pre = 2 WHERE id_pre = ?";	
		}
		else{
			$consulta = "UPDATE premio_premio SET id_est_pre = 1 WHERE id_pre = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
}
?>
