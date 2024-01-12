<?php
require "../../config.php";
include("../../class/conexion.php");
class estacionamiento
{
	private $id_con;
	private $id_viv;
	private $id_est_esta;
	private $nombre_esta;
	private $valor_esta;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function estacionamiento_crea($id_con,$id_viv,$id_est_esta,$nombre_esta,$valor_esta){
		$this->id_con = $id_con;
		$this->id_viv = $id_viv;
		$this->id_est_esta = $id_est_esta;
		$this->nombre_esta = $nombre_esta;
		$this->valor_esta = $valor_esta;
	}
	//funcion de insercion
	public function estacionamiento_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE nombre_esta = ? AND id_esta = ?";
		$conexion->consulta_form($consulta,array($this->nombre_esta,$this->id_esta));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO estacionamiento_estacionamiento VALUES(?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_con,$this->id_viv,$this->id_est_esta,$this->nombre_esta,$this->valor_esta));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function estacionamiento_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE id_esta = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_estacionamiento = $fila["nombre_esta"];	
		
		if($this->nombre_esta != $nombre_estacionamiento){
			$consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE nombre_esta = ?";
			$conexion->consulta_form($consulta,array($this->nombre_esta));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE estacionamiento_estacionamiento SET id_con = ?, id_viv = ?, valor_esta = ?, nombre_esta = ? WHERE id_esta = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_con,$this->id_viv,$this->valor_esta,$this->nombre_esta,$id));


		$conexion->cerrar();
	}
	
	/*//funcion de propietario
	public function estacionamiento_insert_propietario($id_estacionamiento, $propietario){
		$conexion = new conexion();
		
		//PROPIETARIO
		$consulta="INSERT INTO propietario_estacionamiento_propietario VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$propietario,$id_estacionamiento));

		$conexion->cerrar();
	}

	//funcion de propietario
	public function estacionamiento_update_propietario($id_estacionamiento, $propietario){
		$conexion = new conexion();
		
		$consulta = "UPDATE propietario_estacionamiento_propietario SET id_pro = ? WHERE id_esta = ?";
		$conexion->consulta_form($consulta,array($propietario,$id_estacionamiento));

		$conexion->cerrar();
	}*/

	//funcion de eliminacion
	public function estacionamiento_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM estacionamiento_estacionamiento WHERE id_esta = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_esta FROM estacionamiento_estacionamiento ORDER BY id_esta DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_esta'];
		$conexion->cerrar();
		return $id;
	}

	public function estacionamiento_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_esta FROM estacionamiento_estacionamiento WHERE id_esta = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_estacionamiento = $fila["id_est_esta"];	

		if($estado_estacionamiento == 1){
			$consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = 2 WHERE id_esta = ?";	
		}
		else{
			$consulta = "UPDATE estacionamiento_estacionamiento SET id_est_esta = 1 WHERE id_esta = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	
}
?>
