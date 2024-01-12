<?php
require "../../config.php";
include("../../class/conexion.php");
class vivienda
{
	private $id_tip_viv;
	private $id_tor;
	private $id_mod;
	private $id_ori_viv;
	private $id_est_viv;
	private $id_pis;
	private $nombre_viv;
	private $valor_viv;
	private $metro_viv;
	private $metro_terraza_viv;
	private $metro_total_viv;
	private $bono_viv;
	private $prorrateo_viv;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function vivienda_crea($id_tip_viv,$id_tor,$id_mod,$id_ori_viv,$id_est_viv,$id_pis,$nombre_viv,$valor_viv,$metro_viv,$metro_terraza_viv,$metro_total_viv,$bono_viv,$prorrateo_viv){
		$this->id_tip_viv = $id_tip_viv;
		$this->id_tor = $id_tor;
		$this->id_mod = $id_mod;
		$this->id_ori_viv = $id_ori_viv;
		$this->id_est_viv = $id_est_viv;
		$this->id_pis = $id_pis;
		$this->nombre_viv = $nombre_viv;
		$this->valor_viv = $valor_viv;
		$this->metro_viv = $metro_viv;
		$this->metro_terraza_viv = $metro_terraza_viv;
		$this->metro_total_viv = $metro_total_viv;
		$this->bono_viv = $bono_viv;
		$this->prorrateo_viv = $prorrateo_viv;
	}
	//funcion de insercion
	public function vivienda_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_viv FROM vivienda_vivienda WHERE nombre_viv = ? AND id_tor = ?";
		$conexion->consulta_form($consulta,array($this->nombre_viv,$this->id_tor));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO vivienda_vivienda VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_tip_viv,$this->id_tor,$this->id_mod,$this->id_ori_viv,$this->id_est_viv,$this->id_pis,$this->nombre_viv,$this->valor_viv,$this->metro_viv,$this->metro_terraza_viv,$this->metro_total_viv,$this->bono_viv,$this->prorrateo_viv));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function vivienda_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_viv FROM vivienda_vivienda WHERE id_viv = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_vivienda = $fila["nombre_viv"];	
		
		if($this->nombre_viv != $nombre_vivienda){
			$consulta = "SELECT nombre_viv FROM vivienda_vivienda WHERE nombre_viv = ?";
			$conexion->consulta_form($consulta,array($this->nombre_viv));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE vivienda_vivienda SET id_tip_viv = ?, id_tor = ?, id_mod = ?, id_ori_viv = ?, id_pis = ?, nombre_viv = ?, valor_viv = ?, metro_viv = ?, metro_terraza_viv = ?, metro_total_viv = ?, bono_viv = ?, prorrateo_viv = ? WHERE id_viv = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_tip_viv,$this->id_tor,$this->id_mod,$this->id_ori_viv,$this->id_pis,$this->nombre_viv,$this->valor_viv,$this->metro_viv,$this->metro_terraza_viv,$this->metro_total_viv,$this->bono_viv,$this->prorrateo_viv,$id));


		$conexion->cerrar();
	}
	
	//funcion de propietario
	public function vivienda_insert_propietario($id_vivienda, $propietario){
		$conexion = new conexion();
		
		//PROPIETARIO
		$consulta="INSERT INTO propietario_vivienda_propietario VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$propietario,$id_vivienda));

		$conexion->cerrar();
	}

	//funcion de propietario
	public function vivienda_update_propietario($id_vivienda, $propietario){
		$conexion = new conexion();
		
		$consulta = "UPDATE propietario_vivienda_propietario SET id_pro = ? WHERE id_viv = ?";
		$conexion->consulta_form($consulta,array($propietario,$id_vivienda));

		$conexion->cerrar();
	}

	//funcion de eliminacion
	public function vivienda_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM vivienda_vivienda WHERE id_viv = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_viv FROM vivienda_vivienda ORDER BY id_viv DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_viv'];
		$conexion->cerrar();
		return $id;
	}

	public function vivienda_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_viv FROM vivienda_vivienda WHERE id_viv = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_vivienda = $fila["id_est_viv"];	

		if($estado_vivienda == 1){
			$consulta = "UPDATE vivienda_vivienda SET id_est_viv = 2 WHERE id_viv = ?";	
		}
		else{
			$consulta = "UPDATE vivienda_vivienda SET id_est_viv = 1 WHERE id_viv = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	//funcion de insercion de servicio
	public function vivienda_insert_servicio_vivienda($id_vivienda, $id_ser){
		$conexion = new conexion();
		
		$consulta="INSERT INTO vivienda_servicio_vivienda VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_ser,$id_vivienda));
		$conexion->cerrar();
	}
}
?>
