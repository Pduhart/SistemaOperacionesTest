<?php 
session_start(); 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = $_GET["id"];

// $nombre = 'Desistimiento_Finiquito_'.$id.'-'.date('d-m-Y');

// header('Content-type: application/vnd.ms-excel');
// header("Content-Disposition: attachment;filename=".$nombre.".xls");
// header("Pragma: no-cache");
// header("Expires: 0");

$nombre = 'resciliacion_'.$id.'-'.date('d-m-Y');

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename= $nombre.doc");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Desistimiento</title>
    <meta charset="utf-8">
    <style type="text/css">
    	*{
    		margin:0;
    		padding: 0;
    	}
    	html,body{
    		padding: 5px;
    		margin: 0;
    		font-family: "Bookman Old Style", Arial;
    		font-size: 13px;
    	}
        .sin-borde{
			width: 100%;
			margin-left: auto;
			margin-right: auto;
        }
		.sin-borde h2{
			font-size: 15px;
			margin-bottom: 10px;
			text-align: center;
		}
		.sin-borde h4{
			display: inline;
			font-size: 14px;
		}
		.sin-borde h4 small{
			font-size: 14px;
			font-weight: 400;
		}
		.sin-borde p{
			text-align: justify;
			font-size: 16px;
        }
		.sin-borde .hoy{
			width: 100%;
			border: 1px solid #000000;
			padding: 6px;
		}
		.sin-borde .periodo{
			width: 100%;
			padding: 6px;
		}

		@media print
		{    
		    .no-print, .no-print *
		    {
		        display: none !important;
		    }

		    table{
		    	font-size: 11px;
		    }
		}

		@page {
		   size: 21.59cm 33.02cm;
		   margin: 27mm 16mm 27mm 16mm;
		}

    </style>
</head>
<body>
    <?php  

    $consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
    $conexion->consulta_form($consulta,array(16));
    $fila = $conexion->extraer_registro_unico();
    $nombre_representante_inmobiliaria = $fila["valor_par"];

    $consulta = "SELECT valor_par FROM parametro_parametro WHERE id_par = ?";
    $conexion->consulta_form($consulta,array(17));
    $fila = $conexion->extraer_registro_unico();
    $rut_representante_inmobiliaria = $fila["valor_par"];

    $consulta = 
        "
        SELECT 
            ven.id_viv,
            ven.fecha_ven,
            viv.nombre_viv,
            tor.id_con, 
            mode.nombre_mod, 
            con.nombre_con, 
            pro.nombre_pro, 
            pro.nombre2_pro, 
            pro.apellido_paterno_pro, 
            pro.apellido_materno_pro,
            pro.rut_pro,
            pro.direccion_pro,
            pro.direccion_trabajo_pro,
            pro.correo_pro,
            prof.nombre_prof, 
            com.nombre_com, 
            civ.nombre_civ,
            naci.nombre_nac,
            ven.monto_ven
        FROM 
            venta_venta AS ven
            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
            INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
            LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
            LEFT JOIN nacionalidad_nacionalidad AS naci ON naci.id_nac = pro.id_nac
            LEFT JOIN comuna_comuna AS com ON com.id_com = pro.id_com
            LEFT JOIN civil_civil AS civ ON civ.id_civ = pro.id_civ
        WHERE 
            ven.id_ven = ?
        ";
    $conexion->consulta_form($consulta,array($id));
    $fila = $conexion->extraer_registro_unico();
    $id_viv = $fila["id_viv"];
    $id_con = $fila["id_con"];
    $fecha_ven = $fila["fecha_ven"];
    $nombre_pro = $fila["nombre_pro"];
    $nombre2_pro = $fila["nombre2_pro"];
    $apellido_paterno_pro = $fila["apellido_paterno_pro"];
    $apellido_materno_pro = $fila["apellido_materno_pro"];
    $rut_pro = $fila["rut_pro"];
    $direccion_pro = $fila["direccion_pro"];
    $correo_pro = $fila["correo_pro"];
    $nombre_prof = $fila["nombre_prof"];
    $comuna = $fila["nombre_com"];
    $nombre_civ = $fila["nombre_civ"];
    $condominio = $fila["nombre_con"];
    $nombre_viv = $fila["nombre_viv"];
    $nombre_mod = $fila["nombre_mod"];
    $nombre_nac = $fila["nombre_nac"];
    $monto_ven = $fila["monto_ven"];
    $direccion_trabajo_pro = $fila["direccion_trabajo_pro"];


    // $mes = date("n",strtotime($fecha_desistimiento));
    // $dia = date("d",strtotime($fecha_desistimiento));
    // $anio = date("Y",strtotime($fecha_desistimiento));


    // switch ($mes_venta) {
    //     case 1:
    //         $nombre_mes_venta = "Enero";
    //         break;
        
    //     case 2:
    //         $nombre_mes_venta = "Febrero";
    //         break;
    //     case 3:
    //         $nombre_mes_venta = "Marzo";
    //         break;
    //     case 4:
    //         $nombre_mes_venta = "Abril";
    //         break;
    //     case 5:
    //         $nombre_mes_venta = "Mayo";
    //         break;
    //     case 6:
    //         $nombre_mes_venta = "Junio";
    //         break;
    //     case 7:
    //         $nombre_mes_venta = "Julio";
    //         break;
    //     case 8:
    //         $nombre_mes_venta = "Agosto";
    //         break;
    //     case 9:
    //         $nombre_mes_venta = "Septiembre";
    //         break;
    //     case 10:
    //         $nombre_mes_venta = "Octubre";
    //         break;
    //     case 11:
    //         $nombre_mes_venta = "Noviembre";
    //         break;
    //     case 12:
    //         $nombre_mes_venta = "Diciembre";
    //         break;
    // }


    // switch ($mes) {
    //     case 1:
    //         $nombre_mes = "Enero";
    //         break;
        
    //     case 2:
    //         $nombre_mes = "Febrero";
    //         break;
    //     case 3:
    //         $nombre_mes = "Marzo";
    //         break;
    //     case 4:
    //         $nombre_mes = "Abril";
    //         break;
    //     case 5:
    //         $nombre_mes = "Mayo";
    //         break;
    //     case 6:
    //         $nombre_mes = "Junio";
    //         break;
    //     case 7:
    //         $nombre_mes = "Julio";
    //         break;
    //     case 8:
    //         $nombre_mes = "Agosto";
    //         break;
    //     case 9:
    //         $nombre_mes = "Septiembre";
    //         break;
    //     case 10:
    //         $nombre_mes = "Octubre";
    //         break;
    //     case 11:
    //         $nombre_mes = "Noviembre";
    //         break;
    //     case 12:
    //         $nombre_mes = "Diciembre";
    //         break;
    // }


    $consulta = "SELECT fecha_des_ven FROM venta_desestimiento_venta WHERE id_ven = ?";
    $conexion->consulta_form($consulta,array($id));
    $fila = $conexion->extraer_registro_unico();
    $fecha_desistimiento = $fila["fecha_des_ven"];
    $mes = date("n",strtotime($fecha_desistimiento));
    $dia = date("d",strtotime($fecha_desistimiento));
    $anio = date("Y",strtotime($fecha_desistimiento));

	// cambia a fecha reserva

    $mes_venta = date("n",strtotime($fecha_ven));
    $dia_venta = date("d",strtotime($fecha_ven));
    $anio_venta = date("Y",strtotime($fecha_ven));

    switch ($mes) {
        case 1:
            $nombre_mes = "Enero";
            break;
        
        case 2:
            $nombre_mes = "Febrero";
            break;
        case 3:
            $nombre_mes = "Marzo";
            break;
        case 4:
            $nombre_mes = "Abril";
            break;
        case 5:
            $nombre_mes = "Mayo";
            break;
        case 6:
            $nombre_mes = "Junio";
            break;
        case 7:
            $nombre_mes = "Julio";
            break;
        case 8:
            $nombre_mes = "Agosto";
            break;
        case 9:
            $nombre_mes = "Septiembre";
            break;
        case 10:
            $nombre_mes = "Octubre";
            break;
        case 11:
            $nombre_mes = "Noviembre";
            break;
        case 12:
            $nombre_mes = "Diciembre";
            break;
    }

    switch ($mes_venta) {
        case 1:
            $nombre_mes_venta = "Enero";
            break;
        
        case 2:
            $nombre_mes_venta = "Febrero";
            break;
        case 3:
            $nombre_mes_venta = "Marzo";
            break;
        case 4:
            $nombre_mes_venta = "Abril";
            break;
        case 5:
            $nombre_mes_venta = "Mayo";
            break;
        case 6:
            $nombre_mes_venta = "Junio";
            break;
        case 7:
            $nombre_mes_venta = "Julio";
            break;
        case 8:
            $nombre_mes_venta = "Agosto";
            break;
        case 9:
            $nombre_mes_venta = "Septiembre";
            break;
        case 10:
            $nombre_mes_venta = "Octubre";
            break;
        case 11:
            $nombre_mes_venta = "Noviembre";
            break;
        case 12:
            $nombre_mes_venta = "Diciembre";
            break;
    }

    $estacionamiento = '';
    $bodega = '';
    $consulta = "SELECT nombre_esta FROM estacionamiento_estacionamiento WHERE id_viv = ? ORDER BY nombre_esta";
    $conexion->consulta_form($consulta,array($id_viv));
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
            $estacionamiento .= $fila['nombre_esta']." - ";
        }
    }

    $consulta = "SELECT nombre_bod FROM bodega_bodega WHERE id_viv = ? ORDER BY nombre_bod";
    $conexion->consulta_form($consulta,array($id_viv));
    $fila_consulta = $conexion->extraer_registro();
    if(is_array($fila_consulta)){
        foreach ($fila_consulta as $fila) {
            $bodega .= $fila['nombre_bod']." - ";
        }
    }

    if(!empty($estacionamiento)){
        $estacionamiento = substr($estacionamiento, 0, -3);
    }
    if(!empty($bodega)){
        $bodega = substr($bodega, 0, -3);
    }

    if ($id_con==1) {
    	$logo = "logo-empresa.jpg";
    	$nombre_empresa = "Inmobiliaria Cordillera SPA";
    } else {
    	$logo = "logo-icp.jpg";
    	$nombre_empresa = "Inmobiliaria Costanera Pacífico";
    }
    ?>
    <table class="sin-borde">
        <tr>
        	<td style="width: 190px"><img src="http://00ppsav.cl/padmin_ope/assets/img/<?php echo $logo;?>" width="103" height="108"></td>
    		<td style="text-align: center"><?php 
				$consulta = 
                    "
                    SELECT
                        nombre_doc_con
                    FROM 
                        condominio_documento_condominio
                    WHERE 
                        id_con = ? AND
                        (nombre_doc_con = 'logo.jpg' OR nombre_doc_con = 'logo.png')
                    ";
                $contador = 1;
                $conexion->consulta_form($consulta,array($id_con));
                $haylogo = $conexion->total();
                if ($haylogo>0) {
                	$fila = $conexion->extraer_registro_unico();
                	$nombre_doc_con = $fila["nombre_doc_con"];
                	?>
					<img src="http://00ppsav.cl/archivo/condominio/documento/<?php echo $id_con; ?>/<?php echo $nombre_doc_con; ?>" width="190" height="70" style="margin-right: 190px">

                	<?php
                }
				 ?></td>
        </tr>
        <tr>
        	<td colspan="2">
        		<br>
        		<h2>RESCILIACIÓN DE PROMESA DE COMPRAVENTA</h2>
        		<h2>INMOBILIARIA COSTANERA PACIFICO S.p.A.</h2>
        		<h2>Y</h2>
        		<h2><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></h2>
				<p>En La Serena, <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?> entre <b>INMOBILIARIA COSTANERA PACIFICO S.p.A</b>, del giro de su denominación, rol único tributario número 76.866.075-1, representada, según se acreditará, por don <b><?php echo utf8_encode($nombre_representante_inmobiliaria);?></b>, chileno, casado, arquitecto, cédula nacional de identidad número <?php echo utf8_encode($rut_representante_inmobiliaria);?>, con domicilio en calle Av. Costanera número 2800, Comuna de La Serena,  y  don <b><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></b>, de nacionalidad <?php echo utf8_encode($nombre_nac); ?>, estado civil <?php echo utf8_encode($nombre_civ);?>, Profesión <?php echo utf8_encode($nombre_prof);?>, cedula de identidad <?php echo utf8_encode($rut_pro);?>, con domicilio en <?php echo utf8_encode($direccion_pro);?>, ciudad de <?php echo utf8_encode($comuna);?>, representada según se acreditará por el anterior compareciente, se ha convenido lo siguiente:</p>
				<p><b>PRIMERO:</b> Por instrumento privado de promesa de compraventa de fecha <?php echo utf8_encode($dia_venta);?> de <?php echo utf8_encode($nombre_mes_venta);?> de <?php echo utf8_encode($anio_venta);?>, cuyas firmas fueron autorizadas en la Notaría de La Serena de doña Elena Leyton, Inmobiliaria Costanera Pacifico SpA. prometió vender, ceder y transferir a don <?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?>, el inmueble consistente en el departamento n° <?php echo utf8_encode($nombre_viv);?>, <?php if(!empty($estacionamiento)){ echo utf8_encode(" el estacionamiento Nro. ".$estacionamiento.","); }?> y la <?php if(!empty($estacionamiento)){ echo utf8_encode(" bodega Nro. ".$bodega.""); }?> del proyecto Condominio <b><?php echo utf8_encode($condominio);?></b>, quien se comprometió a comprar, aceptar y adquirir para sí el inmueble antes singularizado. <b>SEGUNDO:</b> El precio de la compraventa prometida sería la suma de <?php echo number_format($monto_ven, 2, ',', '.');?> Unidades de Fomento que se pagarían en la forma indicada en la cláusula Séptima del contrato de promesa precitado precedentemente. <b>TERCERO:</b> A la fecha del presente instrumento, la promitente compradora se encuentra en mora de cumplir lo pactado relativo a las obligaciones establecidas en la cláusula <u>séptimo</u> del contrato de promesa antes individualizado. <b>CUARTO:</b> Por este acto, las partes contratantes representadas en la forma señalada en la comparecencia del presente instrumento vienen en resciliar la promesa de compraventa de fecha <?php echo utf8_encode($dia_venta);?> de <?php echo utf8_encode($nombre_mes_venta);?> de <?php echo utf8_encode($anio_venta);?>, cuyas firmas fueron autorizadas en esta misma notaría. <b>QUINTO:</b> Por este mismo acto, se establece que <b>50 unidades de fomento</b> que la promitente compradora entregó al promitente vendedor a título de Reserva y /o Pie, en la fecha señalada en el contrato de promesa precitado, se abonarán a la multa que a título de evaluación anticipada de los perjuicios causados por el incumplimiento se establece en el mismo contrato antes citado. <b>SEXTO:</b> Por este mismo acto, se faculta a un representante de Inmobiliaria Costanera Pacifico SpA., para que realice todos los actos y trámites tendientes a dejar sin efecto la póliza de seguro consecuencia de la garantía establecida en la cláusula decima del contrato de promesa de fecha <?php echo utf8_encode($dia_venta);?> de <?php echo utf8_encode($nombre_mes_venta);?> del presente año. <b>SÉPTIMO:</b> Para todos los efectos derivados del presente contrato, las partes fijan domicilio en La Serena.- </p>
				<p><b>PERSONERÍA:</b> La personería de don SEBASTIAN ARAYA VARELA, para representar a INMOBILIARIA COSTANERA PACIFICO SpA., consta de la escritura pública de fecha 07/09/2018 otorgada en la Notaria de La Serena de doña Elena Leyton Carvajal y para representar a don <?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?> consta en la cláusula Décimo Séptimo del contrato de promesa de fecha <?php echo utf8_encode($dia_venta);?> de <?php echo utf8_encode($nombre_mes_venta);?> de <?php echo utf8_encode($anio_venta);?>, cuyas fueron autorizadas en la Notaría de La Serena de doña Elena Leyton Carvajal.</p>
    			<br><br><br><br><br>
        	</td>
        </tr>
        <tr>
        	<td colspan="2" style="text-align:center">
        		<br><br><br>
        		<div class="firma">
        			________________________________________________
        			<h4><?php echo utf8_encode($nombre_representante_inmobiliaria);?><br><small>RUT: <?php echo utf8_encode($rut_representante_inmobiliaria);?><br>pp. Inmobiliaria COSTANERA PACIFICO S.P.A</small></h4>
        		</div>
        	</td>
        </tr>
    </table>
</body>
</html>