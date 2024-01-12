<?php
require "../../config.php";
include("../../class/conexion.php");
class bono
{
	private $id_con;
	private $id_tip_bon;
	private $id_mod;
	private $id_cat_bon;
	private $id_est_bon;
	private $nombre_bon;
	private $desde_bon;
	private $hasta_bon;
	private $monto_bon;
	private $fecha_desde_bon;
	private $fecha_hasta_bon;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function bono_crea($id_con,$id_tip_bon,$id_mod,$id_cat_bon,$id_est_bon,$nombre_bon,$desde_bon,$hasta_bon,$monto_bon,$fecha_desde_bon,$fecha_hasta_bon){
		$this->id_con = $id_con;
		$this->id_tip_bon = $id_tip_bon;
		$this->id_mod = $id_mod;
		$this->id_cat_bon = $id_cat_bon;
		$this->id_est_bon = $id_est_bon;
		$this->nombre_bon = $nombre_bon;
		$this->desde_bon = $desde_bon;
		$this->hasta_bon = $hasta_bon;
		$this->monto_bon = $monto_bon;
		$this->fecha_desde_bon = $fecha_desde_bon;
		$this->fecha_hasta_bon = $fecha_hasta_bon;
	}
	//funcion de insercion
	public function bono_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_bon FROM bono_bono WHERE nombre_bon = ? AND id_con = ?";
		$conexion->consulta_form($consulta,array($this->nombre_bon,$this->id_con));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}


		//VALIDACIONES DE CATEGORÍA
		//categoria 2
		if ($this->id_cat_bon == 2) {
			$consulta = "SELECT id_bon FROM bono_bono WHERE id_cat_bon = ? AND id_tip_bon = ? AND id_con = ? AND fecha_desde_bon >= ? AND fecha_hasta_bon <= ?";
			$conexion->consulta_form($consulta,array(2,$this->id_tip_bon,$this->id_con,$this->fecha_desde_bon,$this->fecha_hasta_bon));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 4;
				echo json_encode($jsondata);
				exit();
			}	
		}
		//categoria 3
		if ($this->id_cat_bon == 3) {
			$consulta = "SELECT id_bon FROM bono_bono WHERE id_cat_bon = ? AND id_con = ? AND id_mod = ? AND fecha_desde_bon >= ? AND fecha_hasta_bon <= ?";
			$conexion->consulta_form($consulta,array(3,$this->id_con,$this->id_mod,$this->fecha_desde_bon,$this->fecha_hasta_bon));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 4;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "INSERT INTO bono_bono VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_con,$this->id_tip_bon,$this->id_mod,$this->id_cat_bon,$this->id_est_bon,$this->nombre_bon,$this->desde_bon,$this->hasta_bon,$this->monto_bon,$this->fecha_desde_bon,$this->fecha_hasta_bon));
		$conexion->cerrar();

	}
	
	//funcion de modificacion
	public function bono_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_bon,id_tip_bon, id_con,fecha_desde_bon,fecha_hasta_bon FROM bono_bono WHERE id_bon = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_bono = $fila["nombre_bon"];
		$id_tip_bono = $fila["id_tip_bon"];	
		$id_condominio = $fila["id_con"];	
		$fecha_desde_bono = $fila["fecha_desde_bon"];	
		$fecha_hasta_bono = $fila["fecha_hasta_bon"];	
		
		if($this->nombre_bon != $nombre_bono){
			$consulta = "SELECT nombre_bon FROM bono_bono WHERE nombre_bon = ? AND id_tip_bon = ? AND id_con = ?";
			$conexion->consulta_form($consulta,array($this->nombre_bon,$this->id_tip_bon,$this->id_con));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		if($this->id_tip_bon != $id_tip_bono || $this->id_con != $id_condominio || $this->fecha_desde_bon != $fecha_desde_bono || $this->fecha_hasta_bon != $fecha_hasta_bono){

			//VALIDACIONES DE CATEGORÍA
			//categoria 2
			if ($this->id_cat_bon == 2) {
				$consulta = "SELECT id_bon FROM bono_bono WHERE id_cat_bon = ? AND id_tip_bon = ? AND id_con = ? AND desde_bon >= ? AND hasta_bon <= ?";
				$conexion->consulta_form($consulta,array(2,$this->id_tip_bon,$this->id_con,$this->desde_bon,$this->hasta_bon));
				$cantidad = $conexion->total();
				if($cantidad > 0){
					$jsondata['envio'] = 4;
					echo json_encode($jsondata);
					exit();
				}	
			}
			//categoria 3
			if ($this->id_cat_bon == 3) {
				$consulta = "SELECT id_bon FROM bono_bono WHERE id_cat_bon = ? AND id_con = ? AND id_mod = ? AND desde_bon >= ? AND hasta_bon <= ?";
				$conexion->consulta_form($consulta,array(3,$this->id_con,$this->id_mod,$this->desde_bon,$this->hasta_bon));
				$cantidad = $conexion->total();
				if($cantidad > 0){
					$jsondata['envio'] = 4;
					echo json_encode($jsondata);
					exit();
				}	
			}
			
		}

		

		$consulta = "UPDATE bono_bono SET id_con = ?, id_tip_bon = ?, id_mod = ?, desde_bon = ?, nombre_bon = ?, hasta_bon = ?, monto_bon = ?, fecha_desde_bon = ?, fecha_hasta_bon = ? WHERE id_bon = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_con,$this->id_tip_bon,$this->id_mod,$this->desde_bon,$this->nombre_bon,$this->hasta_bon,$this->monto_bon,$this->fecha_desde_bon,$this->fecha_hasta_bon,$id));


		$conexion->cerrar();
	}
	

	//funcion de eliminacion
	public function bono_delete($id){
		$conexion = new conexion();
		$consulta="DELETE FROM bono_bono WHERE id_bon = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_bon FROM bono_bono ORDER BY id_bon DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_bon'];
		$conexion->cerrar();
		return $id;
	}

	public function bono_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_bon FROM bono_bono WHERE id_bon = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_bono = $fila["id_est_bon"];	

		if($estado_bono == 1){
			$consulta = "UPDATE bono_bono SET id_est_bon = 2 WHERE id_bon = ?";	
		}
		else{
			$consulta = "UPDATE bono_bono SET id_est_bon = 1 WHERE id_bon = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	
}
?>
