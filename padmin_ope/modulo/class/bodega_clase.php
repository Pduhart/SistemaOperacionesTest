<?php
require "../../config.php";
include("../../class/conexion.php");
class bodega
{
	private $id_con;
	private $id_viv;
	private $id_est_bod;
	private $nombre_bod;
	private $valor_bod;
	private $rol_bod;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function bodega_crea($id_con,$id_viv,$id_est_bod,$nombre_bod,$valor_bod,$rol_bod){
		$this->id_con = $id_con;
		$this->id_viv = $id_viv;
		$this->id_est_bod = $id_est_bod;
		$this->nombre_bod = $nombre_bod;
		$this->valor_bod = $valor_bod;
		$this->rol_bod = $rol_bod;
	}
	//funcion de insercion
	public function bodega_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_bod FROM bodega_bodega WHERE nombre_bod = ? AND id_bod = ?";
		$conexion->consulta_form($consulta,array($this->nombre_bod,$this->id_bod));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO bodega_bodega VALUES(?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_con,$this->id_viv,$this->id_est_bod,$this->nombre_bod,$this->valor_bod,$this->rol_bod));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function bodega_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_bod FROM bodega_bodega WHERE id_bod = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_bodega = $fila["nombre_bod"];	
		
		if($this->nombre_bod != $nombre_bodega){
			$consulta = "SELECT nombre_bod FROM bodega_bodega WHERE nombre_bod = ?";
			$conexion->consulta_form($consulta,array($this->nombre_bod));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE bodega_bodega SET id_con = ?, id_viv = ?, valor_bod = ?, nombre_bod = ? WHERE id_bod = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_con,$this->id_viv,$this->valor_bod,$this->nombre_bod,$id));


		$conexion->cerrar();
	}
	
	/*//funcion de propietario
	public function bodega_insert_propietario($id_bodega, $propietario){
		$conexion = new conexion();
		
		//PROPIETARIO
		$consulta="INSERT INTO propietario_bodega_propietario VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$propietario,$id_bodega));

		$conexion->cerrar();
	}

	//funcion de propietario
	public function bodega_update_propietario($id_bodega, $propietario){
		$conexion = new conexion();
		
		$consulta = "UPDATE propietario_bodega_propietario SET id_pro = ? WHERE id_bod = ?";
		$conexion->consulta_form($consulta,array($propietario,$id_bodega));

		$conexion->cerrar();
	}*/

	//funcion de eliminacion
	public function bodega_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM bodega_bodega WHERE id_bod = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_bod FROM bodega_bodega ORDER BY id_bod DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_bod'];
		$conexion->cerrar();
		return $id;
	}

	public function bodega_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_bod FROM bodega_bodega WHERE id_bod = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_bodega = $fila["id_est_bod"];	

		if($estado_bodega == 1){
			$consulta = "UPDATE bodega_bodega SET id_est_bod = 2 WHERE id_bod = ?";	
		}
		else{
			$consulta = "UPDATE bodega_bodega SET id_est_bod = 1 WHERE id_bod = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	
}
?>
