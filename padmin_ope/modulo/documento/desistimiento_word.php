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
$nombre = 'desistimiento_'.$id.'-'.date('d-m-Y');

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
    		font-family: Arial;
    		font-size: 13px;
    	}
        .sin-borde{
			width: 100%;
			margin-left: auto;
			margin-right: auto;
        }
		.sin-borde h2{
			font-size: 1rem;
			margin-bottom: 10px;
		}
		.firmas h4{
			border-top: 1px solid #000000;
			display: inline-block;
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
            civ.nombre_civ 
        FROM 
            venta_venta AS ven
            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
            INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
            LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
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
    <table class="sin-borde" width="100%">
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
        		<h2 style="font-size: 15px">DESESTIMIENTO Y FINIQUITO DE PROMESA DE COMPRAVENTA: <?php echo $nombre_empresa; ?> a SR. (A) <?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></h2>
        		<p style="text-align: justify;">En La Serena, a <?php echo utf8_encode($dia);?> de <?php echo utf8_encode($nombre_mes);?> <?php echo utf8_encode($anio);?>, entre don <?php echo utf8_encode($nombre_representante_inmobiliaria);?>, Cédula de Identidad N°<?php echo utf8_encode($rut_representante_inmobiliaria);?>, en representación, según se acreditará de <b><?php echo $nombre_empresa; ?></b>, Rol Único Tributario N° 76.368.795-3, ambos domiciliados en AVENIDA PACIFICO Nro. 2800, en la Comuna de La Serena, en adelante la prometiente vendedora, y por la otra parte don (ña) <b><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></b> con domicilio en <?php echo utf8_encode($direccion_pro);?>, ciudad de <?php echo utf8_encode($comuna);?>, RUT <?php echo utf8_encode($rut_pro);?>, Estado Civil <?php echo utf8_encode($nombre_civ);?>, de profesión u oficio <?php echo utf8_encode($nombre_prof);?>, lugar de trabajo <?php echo utf8_encode($direccion_trabajo_pro);?>, mail <?php echo utf8_encode($correo_pro);?>, en adelante el prometiente comprador, se ha convenido lo siguiente:</p><br>
    			<p style="text-align: justify;"><b><u>PRIMERO:</u></b> Por el presente instrumento don (ña) <b><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?></b>, viene en desistirse de la PROMESA DE COMPRAVENTA, suscrita con <b><?php echo $nombre_empresa; ?></b>, con fecha <?php echo utf8_encode($dia_venta);?> de <?php echo utf8_encode($nombre_mes_venta);?> <?php echo utf8_encode($anio_venta);?>, respecto al Depto. Nro. <?php echo utf8_encode($nombre_viv);?>, tipo <?php echo utf8_encode($nombre_mod);?>, <?php if(!empty($estacionamiento)){ echo utf8_encode(" el Estacionamiento Nro. ".$estacionamiento.","); }?>  <?php if(!empty($estacionamiento)){ echo utf8_encode(" Bodega Nro. ".$bodega.","); }?> del proyecto <b><?php echo utf8_encode($condominio);?></b> de La Serena.</p>
    			<p style="text-align: justify;"><b><u>SEGUNDO:</u></b> <b><?php echo $nombre_empresa; ?></b>, representada en la forma indicada en la comparecencia, acepta dicho desistimiento.</p>
    			<p style="text-align: justify;"><b><u>TERCERO:</u></b> En atención a lo expresado en las cláusulas precedentes, las partes se otorgan recíprocamente total finiquito respecto de la promesa de compraventa indicada, declarando expresamente no tener cargo alguno que formularse.</p>
    			<br><br><br><br><br>
        	</td>
        </tr>
    </table>
    <p style="text-align: center;"><table class="firmas" style="width: 60%; margin-left: 20%; text-align: center;" width="60%">
        <tr>
        	<td style="text-align:center">
        		<h4 style="font-size: 13px"><?php echo utf8_encode($nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro);?><br>RUT: <?php echo utf8_encode($rut_pro);?></h4>
        	</td>
        </tr>
        <tr>
        	<td style="text-align:center">
        		<br><br><br><br><br>
        		<h4 style="font-size: 13px"><?php echo utf8_encode($nombre_representante_inmobiliaria);?><br>RUT: <?php echo utf8_encode($rut_representante_inmobiliaria);?><br>En representación de: <?php echo $nombre_empresa; ?></h4>
        	</td>
        </tr>
    </table></p>
</body>
</html>