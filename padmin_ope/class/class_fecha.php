<?php
date_default_timezone_set('America/Santiago');

class fecha {
	private $fecha1;
	// private $fecha2;
	
	//parametros de conexion
	function __construct(){
		$this->fecha1 = date("Y-m-d");
	}
	//abrir una coneccion
	private function sumar_hora($hora1,$hora2){
		//$hora1 = "01:20:20";
		//$hora2 = "05:50:20";
		$h =  strtotime($hora1);
		$h2 = strtotime($hora2);
		$minute = date("i", $h2);
		$second = date("s", $h2);
		$hour = date("H", $h2);
		$convert = strtotime("+$minute minutes", $h);
		$convert = strtotime("+$second seconds", $convert);
		$convert = strtotime("+$hour hours", $convert);
		$new_time = date('H:i:s', $convert);
		return $new_time;
	}
	//------- OBTIENE LA CANTIDAD DE DIAS ENTRE DOS FECHAS
	public function saca_dia($fecha){
		//$fecha_desde=date("Y-m-d");
		//$fecha_hasta="2012-10-10";
		$dias= (strtotime($this->fecha1)-strtotime($fecha))/86400;
		$dias = abs($dias); 
		$dias = floor($dias);
		return  $dias;
	}
	//------- SUMA HORAS A UNA HORA
	public function hora($hora){
		$nueva_hora = date('g:i a',strtotime ( '+3 hour' , strtotime ( $hora)));
		return $nueva_hora;
	}
	//------- FORMATO FECHAS A MYSQL
	public function formato_mysql($fecha){
		$fecha = date("Y-m-d",strtotime($fecha));
		return $fecha;
	}
	//------- FORMATO FECHAS A NORMAL
	public function formato_normal($fecha){
		$fecha = date("d/m/Y",strtotime($fecha));
		return $fecha;
	}
	public function suma_dia($fecha){
		$fecha= date("Y-m-d", strtotime("$fecha + 10 days"));
		return $fecha;
	}
	public function suma_dia_horario($fecha , $contador){
		$fecha= date("Y-m-d", strtotime("$fecha + $contador days"));
		return $fecha;
	}
	public function suma_horas_fecha($fecha){
		$fecha_hora = date('Y-m-d H:i:s',strtotime ( '+24 hours' , strtotime ( $fecha)));
		return $fecha_hora;
	}
	public function suma_horas_fecha_validacion($fecha){
		$fecha_hora = date('Y-m-d',strtotime ( '+24 hours' , strtotime ( $fecha)));
		return $fecha_hora;
	}
	public function suma_horas_hora($fecha){
		$fecha_hora = date('H:i:s',strtotime ( '+24 hours' , strtotime ( $fecha)));
		return $fecha_hora;
	}
	public function suma_mes_fecha($fecha){
		$fecha_mes = date('Y-m-d H:i:s',strtotime ( '+1 month' , strtotime ( $fecha)));
		return $fecha_mes;
	}
	public function suma_mes_suma($fecha){
		$fecha_mes = date('Y-m-d',strtotime ( '+1 month' , strtotime ( $fecha)));
		return $fecha_mes;
	}
}
?>