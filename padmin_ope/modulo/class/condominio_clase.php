<?php
require "../../config.php";
include("../../class/conexion.php");
class condominio
{
	private $nombre_con;
	private $alias_con;
	private $fecha_venta_con;
	private $id_est_con;

	function __construct(){
		
	}
	//Creacion del objeto proyecto
	function condominio_crea($id_est_con,$nombre_con,$alias_con,$fecha_venta_con){
		$this->id_est_con = $id_est_con;
		$this->nombre_con = $nombre_con;
		$this->alias_con = $alias_con;
		$this->fecha_venta_con = $fecha_venta_con;
	}
	//funcion de insercion
	public function condominio_insert(){
		$conexion = new conexion();
		$consulta = "SELECT nombre_con FROM condominio_condominio WHERE nombre_con = ?";
		$conexion->consulta_form($consulta,array($this->nombre_con));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}

		$consulta = "INSERT INTO condominio_condominio VALUES(?,?,?,?,?)";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/
		$conexion->consulta_form($consulta,array(0,$this->id_est_con,$this->nombre_con,$this->alias_con,$this->fecha_venta_con));
		
		$id_condominio = $conexion->ultimo_id();
		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Junior Vendedor","1",1));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Advance Vendedor","1.5",2));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Senior Vendedor","2.1",3));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Precio Descuento Depto","2.27",4));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Bono Precio %","3",5));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Promesa","60",6));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Escritura","40",7));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Comision Vendedor","0.3",8));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Comision Supervisor","0.13",9));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Comision Jefe Ventas","0.13",10));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Monto Escritura Operaciones (UF)","1",11));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Monto Reserva","10",12));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Bono al Precio Jefe de Ventas","30",13));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Monto a Prorratear","0",14));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"% Bono al Precio Supervisor","30",15));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Monto a Recuperar","30000",16));
	
		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Fecha Recuperación","12-12-2018",17));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Valor Bodega","150",18));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Valor Estacionamiento","200",19));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Junior Jefe Ventas","1",20));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Advance Jefe Ventas","1.5384",21));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Senior Jefe Ventas","2.0769",22));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Junior Supervisor","1",23));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Advance Supervisor","1.5",24));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Factor Senior Supervisor","2",25));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Fecha Término Venta",null,26));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Dirección Condominio",null,27));

		$consulta = "INSERT INTO parametro_parametro VALUES(?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$id_condominio,"Banco Alzante",null,28));

		// crear los bonos del nuevo condominio
		$consulta_ultimo = "SELECT id_con FROM condominio_condominio WHERE id_con < ? ORDER BY id_con DESC LIMIT 0,1";
		$conexion->consulta_form($consulta_ultimo,array($id_condominio));
		$fila_ult = $conexion->extraer_registro_unico();
		$id_ult_con = $fila_ult['id_con'];

		$consulta_bonos = "SELECT * FROM bono_bono WHERE id_con = ?";
		$conexion->consulta_form($consulta_bonos,array($id_ult_con));
		$fila_consulta = $conexion->extraer_registro();
		if(is_array($fila_consulta)){
        	foreach ($fila_consulta as $fila) {
    	 		$consulta = "INSERT INTO bono_bono VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
				$conexion->consulta_form($consulta,array(0,$id_condominio,$fila['id_tip_bon'],$fila['id_mod'],$fila['id_cat_bon'],$fila['id_est_bon'],$fila['nombre_bon'],$fila['desde_bon'],$fila['hasta_bon'],$fila['monto_bon'],$fila['fecha_desde_bon'],$fila['fecha_hasta_bon']));
			}
		}


		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function condominio_update($id){
		$conexion = new conexion();
		$consulta = "SELECT nombre_con FROM condominio_condominio WHERE id_con = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$nombre_condominio = $fila["nombre_con"];	
		
		if($this->nombre_con != $nombre_condominio){
			$consulta = "SELECT nombre_con FROM condominio_condominio WHERE nombre_con = ?";
			$conexion->consulta_form($consulta,array($this->nombre_con));
			$cantidad = $conexion->total();
			if($cantidad > 0){
				$jsondata['envio'] = 2;
				echo json_encode($jsondata);
				exit();
			}	
		}

		$consulta = "UPDATE condominio_condominio SET nombre_con = ?, alias_con = ?, fecha_venta_con = ? WHERE id_con = ?";
		/*$jsondata['envio'] = $consulta;
		echo json_encode($jsondata);
		exit();*/	
		$conexion->consulta_form($consulta,array($this->nombre_con, $this->alias_con, $this->fecha_venta_con,$id));
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function condominio_delete($id){
		$conexion = new conexion();

		$consulta="DELETE FROM parametro_parametro WHERE id_con = $id";
		$conexion->consulta_form($consulta,array($id));

		$consulta="DELETE FROM condominio_condominio WHERE id_con = $id";
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta="SELECT id_con FROM condominio_condominio ORDER BY id_con DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_con'];
		$conexion->cerrar();
		return $id;
	}

	public function condominio_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_con FROM condominio_condominio WHERE id_con = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_condominio = $fila["id_est_con"];	

		if($estado_condominio == 1){
			$consulta = "UPDATE condominio_condominio SET id_est_con = 2 WHERE id_con = ?";	
		}
		else{
			$consulta = "UPDATE condominio_condominio SET id_est_con = 1 WHERE id_con = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
}
?>
