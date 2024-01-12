<?php
require "../../config.php";
include("../../class/conexion.php");
class descuento
{
	private $id_con;
	private $id_est_des;
	private $nombre_des;
	private $monto_des;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function descuento_crea($id_con,$id_est_des,$nombre_des,$monto_des){
		$this->id_con = $id_con;
		$this->id_est_des = $id_est_des;
		$this->nombre_des = $nombre_des;
		$this->monto_des = $monto_des;
	}
	//funcion de insercion
	public function descuento_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_des FROM descuento_descuento WHERE nombre_des = ? ";
		$conexion->consulta_form($consulta,array($this->nombre_des));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO descuento_descuento VALUES(?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_con,$this->id_est_des,$this->nombre_des,$this->monto_des));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function descuento_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_des FROM descuento_descuento WHERE id_des = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_descuento = $fila["nombre_des"];	
		
		if($this->nombre_des != $nombre_descuento){
			$consulta = "SELECT nombre_des FROM descuento_descuento WHERE nombre_des = ? AND id_con = ?";
			$conexion->consulta_form($consulta,array($this->nombre_des,$this->id_con));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE descuento_descuento SET id_con = ?, monto_des = ?, nombre_des = ? WHERE id_des = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_con,$this->monto_des,$this->nombre_des,$id));


		$conexion->cerrar();
	}
	

	//funcion de eliminacion
	public function descuento_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM descuento_descuento WHERE id_des = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_des FROM descuento_descuento ORDER BY id_des DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_des'];
		$conexion->cerrar();
		return $id;
	}

	public function descuento_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_des FROM descuento_descuento WHERE id_des = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_descuento = $fila["id_est_des"];	

		if($estado_descuento == 1){
			$consulta = "UPDATE descuento_descuento SET id_est_des = 2 WHERE id_des = ?";	
		}
		else{
			$consulta = "UPDATE descuento_descuento SET id_est_des = 1 WHERE id_des = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	
}
?>
