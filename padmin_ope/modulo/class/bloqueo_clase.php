<?php
require "../../config.php";
include("../../class/conexion.php");
class bloqueo
{
	private $id_viv;
	private $fecha_desde_fec_viv;
	private $fecha_hasta_fec_viv;

	function __construct(){
		
	}
	//Creacion del objeto bloqueo
	function bloqueo_crea($id_viv,$fecha_desde_fec_viv,$fecha_hasta_fec_viv){
		$this->id_viv = $id_viv;
		$this->fecha_desde_fec_viv = $fecha_desde_fec_viv;
		$this->fecha_hasta_fec_viv = $fecha_hasta_fec_viv;
	}
	//funcion de insercion
	public function bloqueo_insert(){
		$conexion = new conexion();
		//----- validacion disponibilidad
		$consulta = "SELECT * FROM vivienda_fecha_vivienda WHERE id_viv = ?";
		$conexion->consulta_form($consulta,array($this->id_viv));

		$fecha_desde_fec_viv = $this->fecha_desde_fec_viv;
		$fecha_hasta_fec_viv = $this->fecha_hasta_fec_viv;

		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila) {	
		    	$fecha_desde = date("Y-m-d",strtotime($fila["fecha_desde_fec_viv"]));
		    	$fecha_hasta = date("Y-m-d",strtotime($fila["fecha_hasta_fec_viv"]));
		        
		        
		        if($fecha_desde_fec_viv > $fecha_desde && $fecha_desde_fec_viv < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		    	if($fecha_hasta_fec_viv > $fecha_desde && $fecha_hasta_fec_viv < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		        if($fecha_desde_fec_viv < $fecha_desde && $fecha_hasta_fec_viv > $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
			}
		}

		// validación de reserva misma fecha
		//----- validacion disponibilidad
		$consulta = "SELECT * FROM reserva_reserva WHERE (id_est_res = 1 OR id_est_res = 2) AND id_viv = ".$this->id_viv."";
		$conexion->consulta($consulta);
		$fila_consulta = $conexion->extraer_registro();
		

		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila) {	
		    	$fecha_desde = date("Y-m-d",strtotime($fila["fecha_desde_res"]));
		    	$fecha_hasta = date("Y-m-d",strtotime($fila["fecha_hasta_res"]));
		        

		        if($fecha_desde_fec_viv >= $fecha_desde && $fecha_desde_fec_viv < $fecha_hasta){
                    $jsondata['envio'] = 4;
					echo json_encode($jsondata);
					exit();

                }
                if($fecha_hasta_fec_viv > $fecha_desde && $fecha_hasta_fec_viv <= $fecha_hasta){
                    $jsondata['envio'] = 4;
					echo json_encode($jsondata);
					exit();
                    
                }
                if($fecha_desde_fec_viv <= $fecha_desde && $fecha_hasta_fec_viv >= $fecha_hasta){
                    $jsondata['envio'] = 4;
					echo json_encode($jsondata);
					exit();

                }
			}
		}

		$consulta = "INSERT INTO vivienda_fecha_vivienda VALUES(?,?,?,?)";
		
		$conexion->consulta_form($consulta,array(0,$this->id_viv,$this->fecha_desde_fec_viv,$this->fecha_hasta_fec_viv));
		
		

		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function bloqueo_update($id){
		$conexion = new conexion();
		//----- validacion disponibilidad
		
		$consulta = "SELECT fecha_desde_fec_viv, fecha_hasta_fec_viv FROM vivienda_fecha_vivienda WHERE id_viv = ? AND NOT id_fec_viv = ?";
		$conexion->consulta_form($consulta,array($this->id_viv,$id));
		
		$fecha_desde_fec_viv = $this->fecha_desde_fec_viv;
		$fecha_hasta_fec_viv = $this->fecha_hasta_fec_viv;

		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
		    foreach ($fila_consulta as $fila) {	
		    	$fecha_desde = date("Y-m-d",strtotime($fila["fecha_desde_fec_viv"]));
		    	$fecha_hasta = date("Y-m-d",strtotime($fila["fecha_hasta_fec_viv"]));
		        
		        
		        if($fecha_desde_fec_viv > $fecha_desde && $fecha_desde_fec_viv < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		    	if($fecha_hasta_fec_viv > $fecha_desde && $fecha_hasta_fec_viv < $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
		        if($fecha_desde_fec_viv < $fecha_desde && $fecha_hasta_fec_viv > $fecha_hasta){
		            $jsondata['envio'] = 2;
					echo json_encode($jsondata);
					exit();
		        }
			}
		}
		

		$fecha_desde_fec_viv = $this->fecha_desde_fec_viv;
		$fecha_hasta_fec_viv = $this->fecha_hasta_fec_viv;


		$consulta = "UPDATE vivienda_fecha_vivienda SET id_viv = ?, fecha_desde_fec_viv = ?, fecha_hasta_fec_viv = ? WHERE id_fec_viv = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->id_viv,$this->fecha_desde_fec_viv,$this->fecha_hasta_fec_viv,$id));

		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function bloqueo_delete($id){
		$conexion = new conexion();
		
		$consulta = "DELETE FROM vivienda_fecha_vivienda WHERE id_fec_viv = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_fec_viv FROM vivienda_fecha_vivienda ORDER BY id_fec_viv DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_fec_viv'];
		$conexion->cerrar();
		return $id;
	}
}
?>
