<?php
require "../../config.php";
include("../../class/conexion.php");
class torre
{
	private $id_con;
	private $id_est_tor;
	private $nombre_tor;
	

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function torre_crea($id_con,$id_est_tor,$nombre_tor){
		$this->id_con = $id_con;
		$this->id_est_tor = $id_est_tor;
		$this->nombre_tor = $nombre_tor;
	}
	//funcion de insercion
	public function torre_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_tor FROM torre_torre WHERE nombre_tor = ? AND id_con = ?";
		$conexion->consulta_form($consulta,array($this->nombre_tor,$this->id_con));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO torre_torre VALUES(?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_con,$this->id_est_tor,$this->nombre_tor));
		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function torre_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_tor, id_con FROM torre_torre WHERE id_tor = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_torre = $fila["nombre_tor"];
		$condominio_torre = $fila["id_con"];	
		
		if($this->nombre_tor != $nombre_torre && $this->id_con != $condominio_torre){
			$consulta = "SELECT nombre_tor FROM torre_torre WHERE nombre_tor = ? AND id_con = ?";
			$conexion->consulta_form($consulta,array($this->nombre_tor,$this->id_con));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE torre_torre SET nombre_tor = ?, id_con = ? WHERE id_tor = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_tor,$this->id_con,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function torre_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM torre_torre WHERE id_tor = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_tor FROM torre_torre ORDER BY id_tor DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_tor'];
		$conexion->cerrar();
		return $id;
	}

	public function torre_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_tor FROM torre_torre WHERE id_tor = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_torre = $fila["id_est_tor"];	

		if($estado_torre == 1){
			$consulta = "UPDATE torre_torre SET id_est_tor = 2 WHERE id_tor = ?";	
		}
		else{
			$consulta = "UPDATE torre_torre SET id_est_tor = 1 WHERE id_tor = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
}
?>
