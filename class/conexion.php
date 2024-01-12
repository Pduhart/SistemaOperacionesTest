<?php
class conexion
{
	private static $conexionDB;
	private static $ejecutar;
	private $servidor;
	private $usuario;
	private $pass;
	private $base_datos;
	private $descriptor;
	private $close;
	private $sql;
	
	//-------PARAMETROS DE CONEXION
	function __construct(){
		$this->servidor = "mysql-db";
		$this->usuario = "dbuser";
		$this->pass = "dbpass";
		$this->base_datos = "ppsavcl_ssoopp_digital";
		$this->conectar_base_datos();
	}
	//-------CONEXION A UNA BASE DE DATOS
	private function conectar_base_datos(){
		try {
		    // Abriendo la conexion
		    self::$conexionDB = new PDO("mysql:host=$this->servidor;dbname=$this->base_datos", $this->usuario, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

		    // Informenos de todos los errores
		    self::$conexionDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    //return self::$conexion;
		} 
		catch (PDOException $e) {
		    // En caso de que algo saliera mal con nuestro intento de conexión, el mensaje se imprime
		    $jsondata['envio'] = 3;
		    $jsondata['error_consulta'] = $e->getMessage();
			echo json_encode($jsondata);
			//echo $e->getMessage();
			exit();
		}
		
	}
	//-------CIERRA UNA CONEXION
	public function cerrar(){
		self::$conexionDB = null;
	}
	//-------EJECUTA UNA CONSULTA A LA BASE DE DATOS
	public function consulta_form($consulta,$valor){
		try {
			self::$ejecutar = self::$conexionDB->prepare(htmlspecialchars_decode($consulta));
		    // Creamos un diccionario con todos los parámetros
		    self::$ejecutar->execute(htmlspecialchars_decode($valor));
		    //$jsondata['consulta'] = self::$ejecutar->debugDumpParams();
		}
		catch(PDOException $e){
			$jsondata['envio'] = 3;
		    $jsondata['error_consulta'] = $e->getMessage();
		    echo json_encode($jsondata);
			exit();
		}
	}
	//-------EJECUTA UNA CONSULTA A LA BASE DE DATOS DE PRUEBA
	public function consulta_form_prueba($consulta,$valor){
		try {
			self::$ejecutar = self::$conexionDB->prepare($consulta);
		    // Creamos un diccionario con todos los parámetros
		    self::$ejecutar->execute($valor);
		    //self::$ejecutar->debugDumpParams();
		}
		catch(PDOException $e){
		    echo $e->getMessage();
		}
	}
	//-------EJECUTA UNA CONSULTA A LA BASE DE DATOS SIN PARAMETRO
	public function consulta($consulta){
		try {
			self::$ejecutar = self::$conexionDB->query($consulta);
		}
		catch(PDOException $e){
			$jsondata['envio'] = 3;
		    $jsondata['error_consulta'] = $e->getMessage();
		    echo json_encode($jsondata);
			exit();
		}
	}
	//-------EJECUTA UNA CONSULTA A LA BASE DE DATOS SIN PARAMETRO DE PRUEBA
	public function consulta_prueba($consulta){
		try {
			self::$ejecutar = self::$conexionDB->query($consulta);
			//self::$ejecutar->debugDumpParams();
		}
		catch(PDOException $e){
			echo $e->getMessage();
		    
		}
	}
	//-------EXTRAE LOS REGISTROS DE UNA TABLA
	public function extraer_registro(){
		if ($fila = self::$ejecutar->fetchAll(PDO::FETCH_ASSOC)){
			return $fila;
		} 
		else {
			return false;
		}
	}
	//-------EXTRAE LOS REGISTROS DE UNA TABLA
	public function extraer_registro_unico(){
		if ($fila = self::$ejecutar->fetch()){
			return $fila;
		} 
		else {
			return false;
		}
	}
	//-------CANTIDAD DE REGISTROS DE UNA CONSULTA
	public function total(){
		return self::$ejecutar->rowCount();
	}
	//-------DEVUELVE EL ULTIMO ID DESPUES DE UNA INSERCION
	public function ultimo_id(){
		$ultimo_id = self::$conexionDB->lastInsertId();
		return $ultimo_id;
	}
}
?>
