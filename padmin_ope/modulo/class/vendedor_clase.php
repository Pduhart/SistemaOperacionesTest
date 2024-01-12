<?php
require "../../config.php";
include("../../class/conexion.php");
class vendedor
{
	private $id_usu;
	private $rut_vend;
	private $id_cat_vend;
	private $id_est_vend;
	private $nombre1_vend;
	private $apellido_paterno_vend;
	private $apellido_materno_vend;
	private $fono_vend;
	private $correo_vend;

	function __construct(){
		
	}

	//Creacion del objeto vendedor
	function vendedor_crea($id_usu,$rut_vend,$id_cat_vend,$id_est_vend,$nombre1_vend,$apellido_paterno_vend,$apellido_materno_vend, $fono_vend, $correo_vend){
		$this->id_usu = $id_usu;
		$this->rut_vend = $rut_vend;
		$this->id_cat_vend = $id_cat_vend;
		$this->id_est_vend = $id_est_vend;
		$this->nombre1_vend = $nombre1_vend;
		$this->apellido_paterno_vend = $apellido_paterno_vend;
		$this->apellido_materno_vend = $apellido_materno_vend;
		$this->fono_vend = $fono_vend;
		$this->correo_vend = $correo_vend;
	}
	//funcion de insercion
	public function vendedor_insert(){
		$conexion = new conexion();
		
		$consulta = "SELECT * FROM vendedor_vendedor WHERE rut_vend = ?";
		$conexion->consulta_form($consulta,array($this->rut_vend));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}
		
		

		$consulta = "INSERT INTO vendedor_vendedor VALUES(?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$this->id_cat_vend,$this->id_usu,$this->id_est_vend,$this->rut_vend,$this->nombre1_vend,$this->apellido_paterno_vend,$this->apellido_materno_vend,$this->correo_vend,$this->fono_vend));
		$ultimo_id = $conexion->ultimo_id();
		
		$id = $conexion->ultimo_id();
		
		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function vendedor_update($id){
		$conexion = new conexion();
		
		
		$consulta = "UPDATE vendedor_vendedor SET id_cat_vend = ?, nombre_vend = ?, apellido_paterno_vend = ?, apellido_materno_vend = ?, correo_vend = ?, fono_vend = ? WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($this->id_cat_vend,$this->nombre1_vend,$this->apellido_paterno_vend,$this->apellido_materno_vend,$this->correo_vend,$this->fono_vend,$id));
		
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function vendedor_delete($id){
		$conexion = new conexion();

		//Usuario
		$consulta = "SELECT id_usu FROM vendedor_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$id_usu = $fila["id_usu"];

		$consulta = "DELETE FROM usuario_usuario WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id_usu));

		$consulta = "DELETE FROM usuario_usuario_modulo WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id_usu));

		$consulta = "DELETE FROM usuario_usuario_proceso WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id_usu));


		$consulta = "DELETE FROM vendedor_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));

		$consulta = "DELETE FROM vendedor_supervisor_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function vendedor_delete_supervisor($id){
		$conexion = new conexion();

		$consulta = "DELETE FROM vendedor_supervisor_vendedor WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function vendedor_insert_supervisor($supervisor,$vendedor){
		$conexion = new conexion();

		$consulta = "INSERT INTO vendedor_supervisor_vendedor VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$vendedor,$supervisor));
		$conexion->cerrar();
	}


	public function vendedor_delete_jefe($id){
		$conexion = new conexion();

		$consulta = "DELETE FROM vendedor_jefe_vendedor WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function vendedor_insert_jefe($jefe,$vendedor){
		$conexion = new conexion();

		$consulta = "INSERT INTO vendedor_jefe_vendedor VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$vendedor,$jefe));
		$conexion->cerrar();
	}



	public function vendedor_delete_cliente($id){
		$conexion = new conexion();

		$consulta = "DELETE FROM vendedor_propietario_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}

	public function vendedor_delete_cliente_asig($vendedor,$cliente){
		$conexion = new conexion();

		$consulta = "DELETE FROM vendedor_propietario_vendedor WHERE id_vend = ? AND id_pro = ?";
		$conexion->consulta_form($consulta,array($vendedor,$cliente));

		$conexion->cerrar();
	}

	public function vendedor_delete_lista_asig($vendedor,$cliente){
		$conexion = new conexion();

		$consulta = "DELETE FROM lista_vendedor_lista WHERE id_vend = ? AND id_lis = ?";
		$conexion->consulta_form($consulta,array($vendedor,$cliente));

		$conexion->cerrar();
	}


	public function vendedor_insert_cliente($vendedor,$cliente){
		$conexion = new conexion();

		// echo $cliente."<br>";
		$consulta = "SELECT id_pro FROM vendedor_propietario_vendedor WHERE id_pro = ?";
		$conexion->consulta_form($consulta,array($cliente));
		$no_esta=$conexion->total();
		if($no_esta===0) {
			$consulta = "INSERT INTO vendedor_propietario_vendedor VALUES(?,?,?)";
			$conexion->consulta_form($consulta,array(0,$vendedor,$cliente));
		}
		
		$conexion->cerrar();
	}

	public function vendedor_insert_lista($vendedor,$cliente){
		$conexion = new conexion();

		// echo $cliente."<br>";
		if ($cliente<>'') {
			$consulta = "SELECT id_lis FROM lista_vendedor_lista WHERE id_lis = ?";
			$conexion->consulta_form($consulta,array($cliente));
			$no_esta=$conexion->total();
			if($no_esta==0) {
				$consulta = "INSERT INTO lista_vendedor_lista VALUES(?,?)";
				$conexion->consulta_form($consulta,array($cliente,$vendedor));
			}
		}
		
		
		$conexion->cerrar();
	}


	public function vendedor_delete_condominio($id){
		$conexion = new conexion();

		$consulta = "DELETE FROM vendedor_condominio_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function vendedor_insert_condominio($vendedor,$condominio){
		$conexion = new conexion();

		$consulta = "INSERT INTO vendedor_condominio_vendedor VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array(0,$vendedor,$condominio));
		$conexion->cerrar();
	}	
	
	
	public function vendedor_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_vend FROM vendedor_vendedor WHERE id_vend = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_vendedor = $fila["id_est_vend"];	

		if($estado_vendedor == 1){
			$consulta="UPDATE vendedor_vendedor SET id_est_vend = 2 WHERE id_vend = ?";	
		}
		else{
			$consulta="UPDATE vendedor_vendedor SET id_est_vend = 1 WHERE id_vend = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}

	public function recupera_id(){
		$conexion = new conexion();
		$consulta = "SELECT id_vend FROM vendedor_vendedor ORDER BY id_vend DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_vend'];
		$conexion->cerrar();
		return $id;
	}
}
?>
