<?php
require "../../config.php";
include("../../class/conexion.php");
class etapa
{
	private $id_cat_eta;
	private $id_for_pag;
	private $id_est_eta;
	private $nombre_eta;
	private $alias_eta;
	private $numero_eta;
	private $duracion_eta;
	

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function etapa_crea($id_cat_eta,$id_for_pag,$id_est_eta,$nombre_eta,$alias_eta,$numero_eta,$duracion_eta){
		$this->id_cat_eta = $id_cat_eta;
		$this->id_for_pag = $id_for_pag;
		$this->id_est_eta = $id_est_eta;
		$this->nombre_eta = $nombre_eta;
		$this->alias_eta = $alias_eta;
		$this->numero_eta = $numero_eta;
		$this->duracion_eta = $duracion_eta;
	}
	//funcion de insercion
	public function etapa_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_eta FROM etapa_etapa WHERE nombre_eta = ? AND id_eta = ? AND id_for_pag = ?";
		$conexion->consulta_form($consulta,array($this->nombre_eta,$this->id_eta,$this->id_for_pag));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO etapa_etapa VALUES(?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_cat_eta,$this->id_for_pag,$this->id_est_eta,$this->nombre_eta,$this->alias_eta,$this->numero_eta,$this->duracion_eta));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function etapa_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_eta, id_for_pag FROM etapa_etapa WHERE id_eta = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_etapa = $fila["nombre_eta"];	
		
		if($this->nombre_eta != $nombre_etapa){
			$consulta = "SELECT nombre_eta FROM etapa_etapa WHERE nombre_eta = ? AND id_for_pag = ?";
			$conexion->consulta_form($consulta,array($this->nombre_eta,$fila["id_for_pag"]));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE etapa_etapa SET id_cat_eta = ?, id_for_pag = ?, nombre_eta = ?, alias_eta = ?, numero_eta = ?, duracion_eta = ? WHERE id_eta = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_cat_eta,$this->id_for_pag,$this->nombre_eta,$this->alias_eta,$this->numero_eta,$this->duracion_eta,$id));


		$conexion->cerrar();
	}


	//funcion de modificacion
	public function etapa_update_detalle($id,$tipo,$nombre){
		$conexion = new conexion();
		$consulta = "UPDATE etapa_campo_etapa SET id_tip_cam_eta = ?, nombre_cam_eta = ? WHERE id_cam_eta = ?";	
		$conexion->consulta_form($consulta,array($tipo,$nombre,$id));
		$conexion->cerrar();
	}
	

	//funcion de eliminacion
	public function etapa_delete($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM etapa_etapa WHERE id_eta = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function etapa_delete_detalle($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM etapa_campo_etapa WHERE id_cam_eta = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_eta FROM etapa_etapa ORDER BY id_eta DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_eta'];
		$conexion->cerrar();
		return $id;
	}

	public function etapa_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_eta FROM etapa_etapa WHERE id_eta = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_etapa = $fila["id_est_eta"];	

		if($estado_etapa == 1){
			$consulta = "UPDATE etapa_etapa SET id_est_eta = 2 WHERE id_eta = ?";	
		}
		else{
			$consulta = "UPDATE etapa_etapa SET id_est_eta = 1 WHERE id_eta = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function etapa_campo_insert($etapa,$tipo,$nombre){
		$conexion = new conexion();

		if ($nombre<>'') {		
			$consulta = "SELECT nombre_cam_eta FROM etapa_campo_etapa WHERE nombre_cam_eta = ? AND id_eta = ? AND id_tip_cam_eta = ?";
			$conexion->consulta_form($consulta,array(utf8_decode($nombre),$etapa,$tipo));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}

			$consulta = "INSERT INTO etapa_campo_etapa VALUES(?,?,?,?)";
			/*$jsondata['envio'] = $consulta;
			echo json_encode($jsondata);
			exit();*/
			
			$conexion->consulta_form($consulta,array(0,$etapa,$tipo,utf8_decode($nombre)));
		}
		$conexion->cerrar();

	}


}
?>
