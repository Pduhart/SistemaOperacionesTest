<?php
session_start();
require "../../config.php";
include("../../class/conexion.php");
class evento
{
	private $id_cat_eve;
	private $id_est_eve;
	private $nombre_eve;
	private $fecha_eve;
	private $descripcion_eve;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function evento_crea($id_cat_eve,$id_est_eve,$nombre_eve,$fecha_eve,$descripcion_eve,$time_eve,$id_usu){
		$this->id_cat_eve = $id_cat_eve;
		$this->id_est_eve = $id_est_eve;
		$this->nombre_eve = $nombre_eve;
		$this->fecha_eve = $fecha_eve;
		$this->descripcion_eve = $descripcion_eve;
		$this->time_eve = $time_eve;
		$this->id_usu = $id_usu;
	}
	//funcion de insercion
	public function evento_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_eve FROM evento_evento WHERE nombre_eve = ?";
		$conexion->consulta_form($consulta,array($this->nombre_eve));
		$cantidad = $conexion->total();
		// if($cantidad > 0){
		// 	$jsondata['envio'] = 2;
		// 	echo json_encode($jsondata);
		// 	exit();
		// }
		$consulta = "INSERT INTO evento_evento VALUES(?,?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_cat_eve,$this->id_est_eve,$this->nombre_eve,$this->fecha_eve,$this->descripcion_eve,$this->time_eve,0,0,$this->id_usu));
		$conexion->cerrar();
	}
	//funcion de modificacion
	public function evento_update($id){
		$conexion = new conexion();
		$consulta = "SELECT fecha_eve FROM evento_evento WHERE id_eve = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$fecha_eve_actual = $fila["fecha_eve"];	
		
		// if($this->nombre_eve != $nombre_evento){
		// 	$consulta = "SELECT nombre_eve FROM evento_evento WHERE nombre_eve = ?";
		// 	$conexion->consulta_form($consulta,array($this->nombre_eve));
		// 	$cantidad = $conexion->total();
		// 	if($cantidad > 0){
		// 		$jsondata['envio'] = 2;
		// 		echo json_encode($jsondata);
		// 		exit();
		// 	}	
		// }
		$fecha_actual = date("Y-m-d H:i:s");

		$fecha_eve_texto = date("d-m-Y",strtotime($fecha_eve_actual));

		$describe = utf8_decode("EDICIÓN: FECHA ERA ".$fecha_eve_texto);

		// registra la acción
		$consulta = "INSERT INTO evento_accion_evento VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$fecha_actual,$describe,$_SESSION["sesion_id_panel"]));
		

		$consulta = "UPDATE evento_evento SET time_eve = ?, descripcion_eve = ?, nombre_eve = ?, fecha_eve = ? WHERE id_eve = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->time_eve,$this->descripcion_eve,$this->nombre_eve,$this->fecha_eve,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function evento_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM evento_evento WHERE id_eve = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function evento_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_eve FROM evento_evento WHERE id_eve = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_evento = $fila["id_est_eve"];

		$fecha_actual = date("Y-m-d H:i:s");
		if($estado_evento==1){
			$nombre_actual = "ACTIVO";
		} else {
			$nombre_actual = "REALIZADO";
		}
		$describe = utf8_decode("ESTADO: ERA ".$nombre_actual);

		// registra la acción
		$consulta = "INSERT INTO evento_accion_evento VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id,$fecha_actual,$describe,$_SESSION["sesion_id_panel"]));

		if($estado_evento == 1){
			$consulta = "UPDATE evento_evento SET id_est_eve = 2 WHERE id_eve = ?";	
		}
		else{
			$consulta = "UPDATE evento_evento SET id_est_eve = 1 WHERE id_eve = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_eve FROM evento_evento ORDER BY id_eve DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_eve'];
		$conexion->cerrar();
		return $id;
	}
}
?>
