<?php
require "../../config.php";
include("../../class/conexion.php");
class banco
{
	private $nombre_ban;
	private $convenio_ban;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function banco_crea($nombre_ban,$convenio_ban){
		$this->nombre_ban = $nombre_ban;
		$this->convenio_ban = $convenio_ban;
	}
	//funcion de insercion
	public function banco_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_ban FROM banco_banco WHERE nombre_ban = ?";
		$conexion->consulta_form($consulta,array($this->nombre_ban));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO banco_banco VALUES(?,?,?)";
		// $jsondata['envio'] = $consulta;
		// echo json_encode($jsondata);
		// exit();
		$conexion->consulta_form($consulta,array(0,$this->nombre_ban,$this->convenio_ban));
		$conexion->cerrar();
	}


	//funcion de modificacion
	public function banco_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_ban FROM banco_banco WHERE id_ban = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_banco = $fila["nombre_ban"];	
		
		if($this->nombre_ban != $nombre_banco){
			$consulta = "SELECT nombre_ban FROM banco_banco WHERE nombre_ban = ?";
			$conexion->consulta_form($consulta,array($this->nombre_ban));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}
		


		$consulta = "UPDATE banco_banco SET nombre_ban = ?, convenio_ban = ? WHERE id_ban = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_ban,$this->convenio_ban,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function banco_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM banco_banco WHERE id_ban = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_ban FROM banco_banco ORDER BY id_ban DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_ban'];
		$conexion->cerrar();
		return $id;
	}
}
?>
