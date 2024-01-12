<?php
session_start();
require "../../config.php";
if (isset($_GET["est"])) {
    // $_SESSION["sesion_filtro_estado_panel"] = $_GET["est"];
    switch ($_GET["est"]) {
    	case 1:
    		// reservas por liquidar
    		$_SESSION["sesion_filtro_reserva_panel"] = 3;
    		header("Location: informe_reserva.php");
    		break;
    	case 2:
    		// reservas con saldo
    		$_SESSION["sesion_filtro_reserva_panel"] = 2;
			header("Location: informe_reserva.php");
    		break;
    	case 3:
    		// ticket sin responder
    		break;
    	case 4:
    		// check in
    		$_SESSION["sesion_filtro_fecha_desde_panel"] = date("Y-m-d");
    		header("Location: informe_reserva.php");
    		break;
    	case 5:
    		// check out
    		$hoy = date("Y-m-d");
    		$fecha = date("Y-m-d", strtotime("$hoy + 1 days"));

    		$_SESSION["sesion_filtro_fecha_hasta_panel"] = $fecha;
    		header("Location: informe_reserva.php");
    		break;
    	case 6:
    		// aseo atrasado
            $hoy = date("Y-m-d");
            $fecha = date("Y-m-d", strtotime("$hoy - 1 days"));

            $_SESSION["sesion_filtro_fecha_hasta_panel"] = $fecha;
            $_SESSION["sesion_filtro_fecha_estado_panel"] = 1;
    		header("Location: informe_aseo.php");
    		break;
    	case 7:
    		// aseo hoy
            $hoy = date("Y-m-d");
            
            $_SESSION["sesion_filtro_fecha_desde_panel"] = $hoy;
            $_SESSION["sesion_filtro_fecha_hasta_panel"] = $hoy;

            $_SESSION["sesion_filtro_fecha_estado_panel"] = 1;
    		header("Location: informe_aseo.php");
    		break;
    }
}
?>