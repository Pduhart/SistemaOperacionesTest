<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_uf_panel"])){
    header("Location: ../../panel.php");
}
if($_FILES['file_condominio'] == ''){
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

$condominio = isset($_POST["condominio"]) ? utf8_decode($_POST["condominio"]) : "";
$r=array("//","\\","//");
$rr=array("/","/","/");
//echo str_replace($r,$rr,$_POST['file']);
// archivo txt
//die("dddd". $_FILES['file']['tmp_name']);
$destino =  "../../../archivo/condominio/carga/".$_FILES['file_condominio']['name'];
copy($_FILES['file_condominio']['tmp_name'],$destino);
	  

$aCadena = file($destino);
	$resultado = count($aCadena);
	$resultado = $resultado-1; // para eliminar la última línea vacia
    //print_r($aCadena);

    for ($i = 1; $i <= $resultado; $i++) {
    	$dato = explode(";",$aCadena[$i]);

    	$vivienda = $dato[0];
    	$modelo = $dato[1];
    	$metro = $dato[2];
    	$metro_terraza = $dato[3];
    	$metro_total = $dato[4];
    	$orientacion = $dato[5];
    	$estacionamiento = $dato[6];
    	$bodega = $dato[7];
    	$valor = $dato[8];
    	$valor = str_replace('.', '', $valor);
    	$bono_vendedor = $dato[9];
    	$piso = $dato[10];
    	$prorrateo = $dato[11];
    	$prorrateo = str_replace(',', '.', $prorrateo);
    	if (empty($prorrateo)) {
    		$prorrateo = 0;
    	}
    	$rol_vivienda = $dato[12];
    	if (empty($rol_vivienda)) {
    		$rol_vivienda = 0;
    	}
    	$rol_bodega = $dato[13];
    	if (empty($rol_bodega)) {
    		$rol_bodega = 0;
    	}

    	$consulta = 
		    "
		    SELECT
		        id_tor
		    FROM
		        torre_torre
		    WHERE
		        id_con = ".$condominio."
		    ";
		$conexion->consulta($consulta);
		$fila_ban = $conexion->extraer_registro_unico();
		$id_tor	= utf8_encode($fila_ban['id_tor']);


    	//SÓLO BODEGA O ESTACIONAMIENTO
    	if (empty($vivienda) || $vivienda == "") {

    // 		if ($estacionamiento != "") {
    // 			//VERIFICA SI EXISTE ESTACIONAMIENTO
    // 			$query = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE nombre_esta = '".$estacionamiento."' AND id_con = '".$condominio."' ";
				// $conexion->consulta($query);
				// $nrows = $conexion->total();

				// if ($nrows == 0) {
    // 				$inserta = "INSERT INTO estacionamiento_estacionamiento VALUES (0,'".$condominio."',0,1,'".$estacionamiento."','".$valor."')";
				// 	$conexion->consulta($inserta);
				// }
    // 		}

    // 		if ($bodega != "") {
    // 			//VERIFICA SI EXISTE BODEGA
    // 			$query = "SELECT nombre_bod FROM bodega_bodega WHERE nombre_bod = '".$bodega."' AND id_con = '".$condominio."' ";
				// $conexion->consulta($query);
				// $nrows = $conexion->total();
				// if ($nrows == 0) {
    // 				$inserta = "INSERT INTO bodega_bodega VALUES (0,'".$condominio."',0,1,'".$bodega."','".$valor."','".$rol_bod."')";
				// 	$conexion->consulta($inserta);
				// }
				// else{
				// 	//CUANDO EXISTE BODEGA
				// 	$consulta = "UPDATE bodega_bodega SET rol_bod = ? WHERE nombre_bod = ? AND id_con = ? ";
				// 	$conexion->consulta_form($consulta,array($rol_bodega,$bodega,$condominio));
				// }
    // 		}
    	}
    	else{
    		//********************** VERIFICA SI LA VIVIENDA EXISTE
    		

			$query = "SELECT nombre_viv, id_viv FROM vivienda_vivienda WHERE nombre_viv = '".$vivienda."' AND id_tor = '".$id_tor."' ";
			$conexion->consulta($query);
			$nrows = $conexion->total();
			if ($nrows == 0) {
				
				// $consulta = 
				//     "
				//     SELECT
				//         id_mod
				//     FROM
				//         modelo_modelo
				//     WHERE
				//         nombre_mod = '".$modelo."'
				//     ";
				// $conexion->consulta($consulta);
				// $fila_ban = $conexion->extraer_registro_unico();
				// $id_mod	= utf8_encode($fila_ban['id_mod']);

				// $consulta = 
				//     "
				//     SELECT
				//         id_ori_viv
				//     FROM
				//         vivienda_orientacion_vivienda
				//     WHERE
				//         nombre_ori_viv = '".$orientacion."'
				//     ";
				// $conexion->consulta($consulta);
				// $fila_ban = $conexion->extraer_registro_unico();
				// $id_ori_viv	= utf8_encode($fila_ban['id_ori_viv']);

				// $inserta = "INSERT INTO vivienda_vivienda VALUES (0,1,'".$id_tor."','".$id_mod."','".$id_ori_viv."',1,'".$piso."','".$vivienda."','".$valor."','".$metro."','".$metro_terraza."','".$metro_total."','".$bono_vendedor."','".$prorrateo."','".$rol_viv."')";
				// //echo $inserta."<br>";
				// $conexion->consulta($inserta);
	   //  		//echo $fecha_insertar." ".$uf." insertado<br>";

	   //  		$id_viv = $conexion->ultimo_id();
	   //  		if ($estacionamiento != "") {
	   //  			$inserta = "INSERT INTO estacionamiento_estacionamiento VALUES (0,'".$condominio."','".$id_viv."',1,'".$estacionamiento."',0)";
				// 	//echo $inserta."<br>";
				// 	$conexion->consulta($inserta);
	   //  		}
	   //  		if ($bodega != "") {
	   //  			$inserta = "INSERT INTO bodega_bodega VALUES (0,'".$condominio."','".$id_viv."',1,'".$bodega."',0,'".$rol_bod."')";
				// 	//echo $inserta."<br>";
				// 	$conexion->consulta($inserta);
	   //  		}

			}
			else {
				$filaviv = $conexion->extraer_registro_unico();
				$id_viv	= $filaviv['id_viv'];
				//CUANDO EXISTE VIVIENDA
				// $consulta = "UPDATE vivienda_vivienda SET prorrateo_viv = ?, rol_viv = ? WHERE nombre_viv = ? AND id_pis = ? AND id_tor = ? ";
				// $conexion->consulta_form($consulta,array($prorrateo,$rol_vivienda,$vivienda,$piso,$id_tor));
				//CUANDO EXISTE BODEGA
				// $consulta = "UPDATE bodega_bodega SET rol_bod = ? WHERE nombre_bod = ? AND id_con = ? ";
				// $conexion->consulta_form($consulta,array($rol_bodega,$bodega,$condominio));

				$consulta = "UPDATE estacionamiento_estacionamiento SET nombre_esta = ? WHERE id_viv = ? AND id_con = ? ";
				$conexion->consulta_form($consulta,array($estacionamiento,$id_viv,$condominio));
			}
		}
	}
?>
<script>
$(document).ready(function(){
	swal({
	  title: "Excelente!",
	  text: "Estructura de condominio ingresada con éxito!",
	  icon: "success"
		
	}).then(()=>location.href = "form_select.php");
});
</script>