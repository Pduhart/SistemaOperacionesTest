<?
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_SESSION["modulo_uf_panel"])){
    header("Location: ../../panel.php");
}
if($_FILES['file_uf']==''){
	?>
	<script type="text/javascript">
		window.location="../../index.php";
	</script>
	<?
	exit();
}
// include("../class/uf_clase.php");
// $isapre= new isapre();
// $nombre_afp_ins = utf8_decode($_POST["nombre_afp_ins"]);
// $codigo_afp_ins = $_POST["codigo_afp_ins"];

// $isapre->isapre_crea($nombre_afp_ins,$codigo_afp_ins);
// $isapre->isapre_insert();

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../plugin/alert/sweet-alert.css">
<script type="text/javascript" src="../../plugin/ui/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../plugin/ui/js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="../../plugin/alert/sweet-alert_adjunto.js"></script>
<?php

include '../../class/conexion.php';
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
	$resultado = $resultado-2; // para eliminar la última línea vacia
    //print_r($aCadena);
    //echo $resultado."<br>"; //cantidad de arrays
    //echo $aCadena[2];
    //echo $aCadena[3]; // esta es la primer fecha, descarta títulos
    for ($i = 3; $i <= $resultado; $i++) {
    	$datos = explode(";",$aCadena[$i]);
    	/*********arma la fecha***********/
    	$fecha = explode("/",$datos[0]);
    	$fecha_insertar = $fecha[2]."-".$fecha[1]."-".$fecha[0];

    	$uf = str_replace(".", "", $datos[1]); // le saca el punto de los miles
    	$uf = str_replace(",", ".", $uf); // le cambia la coma por punto

    	//echo $fecha_uf."<br>";
    	//echo $uf."<br>";

    	/***************ver si la fecha ya existe**********************/

		$query_fecha = "select fecha_uf from uf_uf where fecha_uf = '".$fecha_insertar."'";
		//die ($query);
		/*$existe = mysql_query($query_fecha) or die(mysql_error());
		$nrows = mysql_num_rows($existe);*/
		$conexion->consulta($query_fecha);
		$nrows = $conexion->total();
		if ($nrows==0) {
			$inserta="insert into uf_uf values (0,'".$fecha_insertar."','".$uf."')";
			$conexion->consulta($inserta);
    		//echo $fecha_insertar." ".$uf." insertado<br>";
		} else {
			//echo "La fecha ya existe";
		}
    	
	}


//$id_cuenta = $afp->recupera_id();
$_SESSION["insercion_panel_ok"] = 1;
/*$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();*/
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
		location.href = "uf_form_select.php";
	});
});
</script>
