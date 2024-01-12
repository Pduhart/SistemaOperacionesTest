<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_uf_panel"])){
    header("Location: ../../panel.php");
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../assets/plugins/alert/sweet-alert.css">
<script src="../../assets/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="../../assets/plugins/alert/sweet-alert_adjunto.js"></script>
<?php
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$curretYear = date("Y");

$url = 'http://api.sbif.cl/api-sbifv3/recursos_api/uf/'.$curretYear.'?apikey=115ffc264499cd48344187efe4e665e85a339b4f&formato=json';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$result = curl_exec($curl);
curl_close($curl);
if($result){
  $dailyIndicators = json_decode($result);
  foreach ($dailyIndicators->UFs as $key => $value) {
    $query_fecha = "SELECT fecha_uf FROM uf_uf WHERE fecha_uf = '".$value->Fecha."'";
		$conexion->consulta($query_fecha);
		$nrows = $conexion->total();
		if ($nrows == 0) {
			$uf = str_replace(".", "", $value->Valor); // le saca el punto de los miles
    		$uf = str_replace(",", ".", $uf); // le cambia la coma por punto
			$inserta = "INSERT INTO uf_uf VALUES (0,'".$value->Fecha."','".$uf."')";
			$conexion->consulta($inserta);
		} 
  }
}
else{
  //die("Connection Failure");
  $url = 'https://mindicador.cl/api/uf/'.$curretYear;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  $result = curl_exec($curl);
  curl_close($curl);
  $dailyIndicators = json_decode($result);
  foreach ($dailyIndicators->serie as $key => $value) {
    $fecha = substr($value->fecha, 0, 10);
    $query_fecha = "SELECT fecha_uf FROM uf_uf WHERE fecha_uf = '".$fecha."'";
		$conexion->consulta($query_fecha);
		$nrows = $conexion->total();
		if ($nrows == 0) {
			$inserta = "INSERT INTO uf_uf VALUES (0,'".$fecha."','".$value->valor."')";
			$conexion->consulta($inserta);
		}
  }
}


?>
<script>
$(document).ready(function(){
	swal({
	  title: "Excelente!",
	  text: "UF ingresada con éxito!",
	  type: "success",
	  showCancelButton: false,
	  confirmButtonColor: "#9bde94",
	  confirmButtonText: "Aceptar",
	  closeOnConfirm: false
	},
	function(){
		location.href = "form_select.php";
	});
});
</script>