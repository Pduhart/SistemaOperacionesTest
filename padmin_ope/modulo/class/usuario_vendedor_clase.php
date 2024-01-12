<?php
//include("../../class/conexion.php");
class usuario
{
	private $rut_usu;
	private $id_est_usu;
	private $nombre1_usu;
	private $apellido1_usu;
	private $apellido2_usu;
	private $perfil_usu;
	private $correo_usu;
	private $fono_usu;
	private $contrasena_usu;
	private $categoria_usu;

	function __construct(){
		
	}
	//Creacion del objeto usuario
	function usuario_crea($rut_usu,$id_est_usu,$nombre1_usu,$apellido1_usu,$apellido2_usu, $perfil_usu, $correo_usu, $fono_usu,$contrasena_usu,$categoria_usu){
		$this->rut_usu = $rut_usu;
		$this->id_est_usu = $id_est_usu;
		$this->nombre1_usu = $nombre1_usu;
		$this->apellido1_usu = $apellido1_usu;
		$this->apellido2_usu = $apellido2_usu;
		$this->perfil_usu = $perfil_usu;
		$this->correo_usu = $correo_usu;
		$this->fono_usu = $fono_usu;
		$this->contrasena_usu = $contrasena_usu;
		$this->categoria_usu = $categoria_usu;
	}
	//funcion de insercion
	public function usuario_insert(){
		$conexion = new conexion();
		
		$consulta = "SELECT * FROM usuario_usuario WHERE rut_usu = ? AND id_per = ?";
		$conexion->consulta_form($consulta,array($this->rut_usu,$this->perfil_usu));
		$cantidad = $conexion->total();
		if($cantidad > 0){
			$jsondata['envio'] = 2;
			echo json_encode($jsondata);
			exit();
		}
		//REEMPLAZAR CARACTERES ESPECIALES EN APELLIDO PATERNO
		$apellido_paterno = $this->apellido1_usu;
		
		// $nombre_usuario = preg_replace('/\&(.)[^;]*;/', '\\1', $this->nombre1_usu);

		if($this->perfil_usu == 4){
			$letra_nombre = substr($this->nombre1_usu,0,1);
			$usuario = $letra_nombre.$apellido_paterno;
			
			$consulta = "SELECT usuario_usu FROM usuario_usuario WHERE nombre_usu LIKE '".$letra_nombre."%' AND apellido1_usu = '".$this->apellido1_usu."'";
			
			$conexion->consulta($consulta);
			$cantidad = $conexion->total();
			
			if($cantidad > 0){
				$usuario = $usuario.$cantidad;		
			}		

			
	 		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
			$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';  
			$usuario = strtr($usuario, utf8_decode($a), $b);
			$usuario = strtolower($usuario);
			$usuario = str_replace(' ','-',$usuario);

	 		
			$rut = str_replace(".","", $this->rut_usu);
			$rut = substr($rut,0,4);
			$contrasena = $rut;
		
		}
		else{
			$usuario = '';
			$contrasena = '';
		}

		$consulta = "INSERT INTO usuario_usuario VALUES(?,?,?,?,?,?,?,?,?,?,?)";
		$conexion->consulta_form($consulta,array(0,$this->perfil_usu,$this->id_est_usu,$this->rut_usu,$this->nombre1_usu,$this->apellido1_usu,$this->apellido2_usu,$usuario,$contrasena,$this->correo_usu,$this->categoria_usu));
		$ultimo_id = $conexion->ultimo_id();
		
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$ultimo_id.",1)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$ultimo_id.",23)";
		$conexion->consulta($consulta);
		
		$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$ultimo_id.",302)";
		$conexion->consulta($consulta);

		// 	PROCESOS
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,110)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,121)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,122)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,129)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,130)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",1,134)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",23,114)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",23,115)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",302,80)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",302,81)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",500,151)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",500,159)";
		$conexion->consulta($consulta);
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",500,160)";
		$conexion->consulta($consulta);

		$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$ultimo_id.",9,149)";
		$conexion->consulta($consulta);
		
		// if ($this->perfil_usu == 1) {
		// 	//MÓDULOS
		// 	$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id.",4)";
		// 	$conexion->consulta($consulta);

		// 	// //PROCESOS
		// 	$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id.",4,76)";
		// 	$conexion->consulta($consulta);
		// }

		// if ($this->perfil_usu == 2) {
		// 	// MÓDULOS
		// 	$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id.",302)";
		// 	$conexion->consulta($consulta);

		// 	//PROCESOS
		// 	$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id.",302,81)";
		// 	$conexion->consulta($consulta);
		// }

		// if ($this->perfil_usu == 3) {
		// 	// MÓDULOS
		// 	$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id.",309)";
		// 	$conexion->consulta($consulta);

		// 	//PROCESOS
		// 	$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id.",309,94)";
		// 	$conexion->consulta($consulta);		
		// }

		// if ($this->perfil_usu == 4) {
		// 	// MÓDULOS
		// 	$consulta="INSERT INTO usuario_usuario_modulo VALUES(".$id.",309)";
		// 	$conexion->consulta($consulta);

		// 	//PROCESOS
		// 	$consulta="INSERT INTO usuario_usuario_proceso VALUES(".$id.",309,94)";
		// 	$conexion->consulta($consulta);		
		// }


		$conexion->cerrar();
	}
	
	//funcion de modificacion
	public function usuario_update($id){
		$conexion = new conexion();
		
		
		$consulta = "UPDATE usuario_usuario SET nombre_usu = ?, apellido1_usu = ?, apellido2_usu = ?, correo_usu = ?, contrasena_usu = ? WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($this->nombre1_usu,$this->apellido1_usu,$this->apellido2_usu,$this->correo_usu,$this->contrasena_usu,$id));
		
		$conexion->cerrar();
	}
	
	//funcion de eliminacion
	public function usuario_delete($id){
		$conexion = new conexion();
		$consulta = "DELETE FROM usuario_usuario WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));

		$consulta = "DELETE FROM usuario_usuario_modulo WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));

		$consulta = "DELETE FROM usuario_usuario_proceso WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));

		$conexion->cerrar();
	}
	public function recupera_id(){
		$conexion = new conexion();
		$consulta = "SELECT id_usu FROM usuario_usuario ORDER BY id_usu DESC LIMIT 0,1";
		$conexion->consulta($consulta);
		$fila = $conexion->extraer_registro_unico();
		$id = $fila['id_usu'];
		$conexion->cerrar();
		return $id;
	}
	// ------------- MODULO
	
	//funcion de insercion de modulo
	public function usuario_insert_modulo($id_usu, $id_mod){
		$conexion = new conexion();
		
		$consulta = "INSERT INTO usuario_usuario_modulo VALUES(?,?)";
		$conexion->consulta_form($consulta,array($id_usu,$id_mod));
		$conexion->cerrar();
	}
	//funcion de eliminacion de accion
	public function usuario_delete_modulo($id_usu, $id_mod){
		$conexion = new conexion();
		$consulta = "DELETE FROM usuario_usuario_modulo WHERE id_usu = ? AND id_mod = ?";
		$conexion->consulta_form($consulta,array($id_usu,$id_mod));

		$consulta = "DELETE FROM usuario_usuario_proceso WHERE id_usu = ? AND id_mod = ?";
		$conexion->consulta_form($consulta,array($id_usu,$id_mod));

		$conexion->cerrar();
	}
	
	
	
	// ------------- PROCESO
	
	//funcion de insercion de proceso
	public function usuario_insert_proceso($id_usu, $id_mod, $id_pro){
		$conexion = new conexion();
		
		$consulta="INSERT INTO usuario_usuario_proceso VALUES(?,?,?)";
		$conexion->consulta_form($consulta,array($id_usu,$id_mod,$id_pro));
		$conexion->cerrar();
	}
	//funcion de eliminacion de proceso
	public function usuario_delete_proceso($id_usu, $id_mod, $id_pro){
		$conexion = new conexion();
		$consulta="DELETE FROM usuario_usuario_proceso WHERE id_usu = ? AND id_mod = ? AND id_pro = ?";
		$conexion->consulta_form($consulta,array($id_usu,$id_mod,$id_pro));
		$conexion->cerrar();
	}
	
	public function usuario_update_estado($id){
		$conexion = new conexion();
		$consulta = "SELECT id_est_usu FROM usuario_usuario WHERE id_usu = ?";
		$conexion->consulta_form($consulta,array($id));
		$fila = $conexion->extraer_registro_unico();
		$estado_usuario = $fila["id_est_usu"];	

		if($estado_usuario == 1){
			$consulta="UPDATE usuario_usuario SET id_est_usu = 2 WHERE id_usu = ?";	
		}
		else{
			$consulta="UPDATE usuario_usuario SET id_est_usu = 1 WHERE id_usu = ?";
		}
		$conexion->consulta_form($consulta,array($id));
		$conexion->cerrar();
	}
}
?>
