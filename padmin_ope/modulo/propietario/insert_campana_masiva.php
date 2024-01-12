<?php
session_start();
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_propietario_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["enlace_imagen"])){
	header("Location: "._ADMIN."index.php");
	exit();
}


include("../class/propietario_clase.php");
$propietario = new propietario();
$ids = isset($_POST["ids"]) ? utf8_encode($_POST["ids"]) : 0;
$ids_cant = isset($_POST["ids_cant"]) ? utf8_encode($_POST["ids_cant"]) : 0;

$asunto = isset($_POST["asunto"]) ? utf8_decode($_POST["asunto"]) : "";
$enlace_imagen = isset($_POST["enlace_imagen"]) ? utf8_decode($_POST["enlace_imagen"]) : "";
$tipo_mail = isset($_POST["tipo_mail"]) ? utf8_decode($_POST["tipo_mail"]) : "";

$fecha = date("Y-m-d H:i:s");

// valida cantidad envíos mensuales
// $cantidad_mes_actual = $propietario->getCalculaCantidadMensualMails($ids_cant);
$cantidad_mes_actual = 1;

if($cantidad_mes_actual == 1) {
	$titulo_plantilla = $propietario->getRecuperaTituloCampana($tipo_mail);

	$describe_masiva = utf8_decode("Envió masivo mail ".utf8_encode($titulo_plantilla));

	$valida_repetido = $propietario->getVerificaEnvioRepetido($_SESSION["sesion_id_panel"],$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$ids_cant,$ids);


	if($valida_repetido==0) {
		$id_emp = explode(',',$ids);
		$cantidad = $ids_cant - 1;
		$contador = 0;
		$propietario->getPropietarioInsertCampana($_SESSION["sesion_id_panel"],$fecha,$asunto,$enlace_imagen,$titulo_plantilla,$ids_cant);

		$ultimo_id = $propietario->recupera_id_campana();

		$email_vendedor = $propietario->recupera_email_vendedor($_SESSION["sesion_id_panel"]);

		$nombre_vendedor = $propietario->recupera_nombre_vendedor($_SESSION["sesion_id_panel"]);
		
		$ejecucion_masiva = $propietario->getPropietarioInsertEnvioMasivo($ultimo_id,$id_emp,$cantidad,$asunto,$enlace_imagen,$tipo_mail,$nombre_vendedor,$email_vendedor,$_SESSION["sesion_id_panel"],$fecha,$describe_masiva);

		// while($contador <= $cantidad ){
		// 	$propietario->propietario_insert_envio($ultimo_id,$id_emp[$contador],$asunto,$enlace_imagen,$tipo_mail,$nombre_vendedor,$email_vendedor);

		// 	$propietario->propietario_insert_observacion($id_emp[$contador],$_SESSION["sesion_id_panel"],$fecha,$describe_masiva);
		// 	$contador++;
		// }

		$respuesta_sendgrid = explode(">", $ejecucion_masiva);

		if ($respuesta_sendgrid[0]==1) {
			$jsondata['envio'] = $respuesta_sendgrid[0];
		} else {
			$jsondata['envio'] = 5;
			$jsondata['respuesta'] = $respuesta_sendgrid[1];
		}
	} else {
		$jsondata['envio'] = 6;
	}
	

	echo json_encode($jsondata);
	exit();
} else {
	$jsondata['envio'] = 4;
	$jsondata['supera'] = $cantidad_mes_actual;
	echo json_encode($jsondata);
	exit();
}


?>