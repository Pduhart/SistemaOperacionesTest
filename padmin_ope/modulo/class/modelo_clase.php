<?php
require "../../config.php";
include("../../class/conexion.php");
class modelo
{
	private $nombre_mod;
	private $numero_cama_mod;
	private $numero_banio_mod;
	private $descripcion_mod;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function modelo_crea($nombre_mod,$numero_cama_mod,$numero_banio_mod,$descripcion_mod){
		$this->nombre_mod = $nombre_mod;
		$this->numero_cama_mod = $numero_cama_mod;
		$this->numero_banio_mod = $numero_banio_mod;
		$this->descripcion_mod = $descripcion_mod;
	}
	//funcion de insercion
	public function modelo_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_mod FROM modelo_modelo WHERE nombre_mod = ?";
		$conexion->consulta_form($consulta,array($this->nombre_mod));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO modelo_modelo VALUES(?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,1,$this->nombre_mod,$this->numero_cama_mod,$this->numero_banio_mod,$this->descripcion_mod));
		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function modelo_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_mod FROM modelo_modelo WHERE id_mod = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_modelo = $fila["nombre_mod"];	
		
		if($this->nombre_mod != $nombre_modelo){
			$consulta = "SELECT nombre_mod FROM modelo_modelo WHERE nombre_mod = ?";
			$conexion->consulta_form($consulta,array($this->nombre_mod));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}
		$consulta = "UPDATE modelo_modelo SET nombre_mod = ?,numero_cama_mod = ?,numero_banio_mod = ?,descripcion_mod = ? WHERE id_mod = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_mod,$this->numero_cama_mod,$this->numero_banio_mod,$this->descripcion_mod,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function modelo_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM modelo_modelo WHERE id_mod = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function modelo_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_mod FROM modelo_modelo WHERE id_mod = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_modelo = $fila["id_est_mod"];	

		if($estado_modelo == 1){
			$consulta = "UPDATE modelo_modelo SET id_est_mod = 2 WHERE id_mod = ?";	
		}
		else{
			$consulta = "UPDATE modelo_modelo SET id_est_mod = 1 WHERE id_mod = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_mod FROM modelo_modelo ORDER BY id_mod DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_mod'];
		$conexion->cerrar();
		return $id;
	}
	//funcion de insercion de servicio
	public function modelo_insert_servicio_modelo($id_modelo, $id_ser){
		$conexion = new conexion();
		
		$consulta="INSERT INTO modelo_servicio_modelo VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_ser,$id_modelo));
		$conexion->cerrar();
	}
	public function modelo_delete_servicio_modelo($id_mod){
		$conexion = new conexion();
		$consulta = "DELETE FROM modelo_servicio_modelo WHERE id_mod = ?";
		$conexion->consulta_form($consulta,array($id_mod));

		$conexion->cerrar();
	}
}
?>
