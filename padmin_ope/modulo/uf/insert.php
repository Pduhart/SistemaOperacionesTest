<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_uf_panel"])){
    header("Location: ../../panel.php");
}
if($_FILES['file_uf'] == ''){
	header("Location: "._ADMIN."index.php");
	exit();
}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../assets/plugins/alert/sweet-alert.css">
<script src="../../assets/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="../../assets/plugins/alert/sweet-alert_adjunto.js"></script>
<?php

include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$r=array("//","\\","//");
$rr=array("/","/","/");
//echo str_replace($r,$rr,$_POST['file']);
// archivo txt
//die("dddd". $_FILES['file']['tmp_name']);
$destino =  "../../../archivo/uf/".$_FILES['file_uf']['name'];
copy($_FILES['file_uf']['tmp_name'],$destino);
	  

$aCadena = file($destino);
	$resultado = count($aCadena);
	$resultado = $resultado-1; // para eliminar la última línea vacia
    // print_r($aCadena);
    // echo $resultado."<br>"; //cantidad de arrays
    // echo $aCadena[2];
    // echo $aCadena[3]; // esta es la primer fecha, descarta títulos
    // echo "--->".$aCadena[4]."<br>"; // esta es la primer fecha, descarta títulos
    // echo "--->".$aCadena[$resultado]."<br>"; // esta es la última fecha
    // ahora I parte en 4 (12-04-2021)
    for ($i = 4; $i <= $resultado; $i++) {
    	$datos = explode(";",$aCadena[$i]);
    	/*********arma la fecha***********/
    	$cadena_fecha = str_replace('/', '-', $datos[0]);
    	$cadena_fecha = str_replace('.', '-', $datos[0]);
    	
    	$fecha = explode("-",$cadena_fecha);

    	// echo print_r($fecha);

    	if (strlen($fecha[1]) > 2) {
    		// echo "es mas largo".strlen($fecha[1])."<br>";

    		switch ($fecha[1]) {
    			case 'ene':
    				$fecha_conv = '01';
    				break;
    			case 'feb':
    				$fecha_conv = '02';
    				break;
    			case 'mar':
    				$fecha_conv = '03';
    				break;
    			case 'abr':
    				$fecha_conv = '04';
    				break;
    			case 'may':
    				$fecha_conv = '05';
    				break;
    			case 'jun':
    				$fecha_conv = '06';
    				break;
    			case 'jul':
    				$fecha_conv = '07';
    				break;
    			case 'ago':
    				$fecha_conv = '08';
    				break;
    			case 'sep':
    				$fecha_conv = '09';
    				break;
    			case 'oct':
    				$fecha_conv = '10';
    				break;
    			case 'nov':
    				$fecha_conv = '11';
    				break;
    			case 'dic':
    				$fecha_conv = '12';
    				break;
    			
    			default:
    				$fecha_conv = '01';
    				break;
    		}

    		$fecha_insertar = $fecha[2]."-".$fecha_conv."-".$fecha[0];

    	} else {
    		$fecha_insertar = $fecha[2]."-".$fecha[1]."-".$fecha[0];
    	}

    	

    	$uf = str_replace(".", "", $datos[1]); // le saca el punto de los miles
    	$uf = str_replace(",", ".", $uf); // le cambia la coma por punto

    	// echo $fecha_insertar."<br>";
    	// echo $uf."<br>";

    	/***************ver si la fecha ya existe**********************/

		$query_fecha = "SELECT fecha_uf FROM uf_uf WHERE fecha_uf = '".$fecha_insertar."'";
		//die ($query);
		/*$existe = mysql_query($query_fecha) or die(mysql_error());
		$nrows = mysql_num_rows($existe);*/
		$conexion->consulta($query_fecha);
		$nrows = $conexion->total();
		if ($nrows==0) {
			$inserta = "INSERT INTO uf_uf VALUES (0,'".$fecha_insertar."','".$uf."')";
			// echo $inserta."<br>";
			$conexion->consulta($inserta);
    		//echo $fecha_insertar." ".$uf." insertado<br>";
		} else {
			//echo "La fecha ya existe";
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