<?php
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();


$consulta_ventas_contado = 
    "
    SELECT 
        ven.id_ven
    FROM 
        venta_venta as ven
    WHERE EXISTS (
    	SELECT
    		ven_eta.id_ven
    	FROM
    		venta_etapa_venta as ven_eta
    	WHERE
    		ven_eta.id_ven = ven.id_ven
    ) AND
    ven.id_for_pag = 2 AND
    ven.id_est_ven > 3
    "; 
$conexion->consulta($consulta_ventas_contado);
$cant_ventas = $conexion->total();

echo $cant_ventas."<----------<br>";

$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
	foreach ($fila_consulta as $fila) {
		$id_ven = $fila['id_ven'];
		echo "------>Venta: ".$id_ven."<br>";

		// falta ver hasta qué etapa analizar
		$consulta_etapa_max_venta = "
			SELECT 
				eta.id_eta,
				eta.numero_real_eta
			FROM 
				etapa_etapa as eta,
				venta_etapa_venta as ven_eta
			WHERE 
				ven_eta.id_ven = ".$id_ven." AND
				eta.id_eta = ven_eta.id_eta
			ORDER BY numero_real_eta DESC LIMIT 0,1";
		$conexion->consulta($consulta_etapa_max_venta);
		$filamax = $conexion->extraer_registro_unico();

		$id_eta_max = $filamax['id_eta'];
		$numero_real_max = $filamax['numero_real_eta'];

		echo "La etapa más actual de la venta es -------------------------------------->".$id_eta_max."---".$numero_real_max."<br>";


		if($id_eta_max == 22) {
			$consulta = "SELECT id_eta_ven FROM venta_etapa_venta WHERE id_ven = ? AND id_eta = 52";
			$conexion->consulta_form($consulta,array($id_ven));
			$hayproximo = $conexion->total();

			echo $id_ven."---entro y hay próximo es ".$hayproximo."<br>";

			if($hayproximo==0) {
				$fecha = null;
				$consulta = "INSERT INTO venta_etapa_venta VALUES(?,?,?,?,?,?,?)";
				$conexion->consulta_form($consulta,array(0,$id_ven,52,3,$fecha,$fecha,""));
			}

			
		}
	}
}

// $total_lista = 950;
// $n_registro = 364;
// $n_lista = 126;

// $fila_consulta = $conexion->extraer_registro();
// if(is_array($fila_consulta)){
// 	foreach ($fila_consulta as $fila) {

// 		$mail_insertar = utf8_encode($fila['correo_pro']);
// 		$email1 = str_replace(" ","", $mail_insertar);
// 		$email1 = str_replace("\t","", $email1);
// 		$email1 = str_replace("?","", $email1);
// 		$email1 = strtolower($email1);
// 		$mail_insertar = trim($email1);

// 		if ($mail_insertar<>'' && filter_var($mail_insertar, FILTER_VALIDATE_EMAIL)) {


// 			$n_registro++;
// 			echo $fila['correo_pro'] . "<br>";

// 			if($n_registro == 1){

// 				$nombre_lis = "Lista_".$n_lista;

// 				// $consulta = "INSERT INTO lista_lista VALUES (?,?)";
// 				// $conexion->consulta_form($consulta,array(0, $nombre_lis));
// 			}

// 			if($n_registro<$total_lista){
// 				// inserta en la lista actual
// 				$consulta = "INSERT INTO lista_correo_lista VALUES (?,?,?)";
// 			$conexion->consulta_form($consulta,array(0,$n_lista,$mail_insertar));

// 			} else if ($n_registro==$total_lista) {
// 				// inserta y sube uno la lista
// 				$consulta = "INSERT INTO lista_correo_lista VALUES (?,?,?)";
// 				$conexion->consulta_form($consulta,array(0,$n_lista,$mail_insertar));

// 				$n_lista++;
// 				$n_registro = 0;

// 			}
// 		}
// 	}
// }



//Lo recorremos
// while (($datos = fgetcsv($archivo, ",")) == true) 
// {
//   $num = count($datos);
//   $n_registro++;
//   echo $datos[0] . "<br>";

//   $mail_insertar = trim($datos[0]);

//   if($n_registro == 1){

//   	$nombre_lis = "Lista_".$n_lista;

//   	$consulta = "INSERT INTO lista_lista VALUES (?,?)";
//     $conexion->consulta_form($consulta,array(0, $nombre_lis));
//   }

//   if($n_registro<$total_lista){
//   	// inserta en la lista actual
//   	$consulta = "INSERT INTO lista_correo_lista VALUES (?,?,?)";
//     $conexion->consulta_form($consulta,array(0,$n_lista,$mail_insertar));

//   } else if ($n_registro==$total_lista) {
//   	// inserta y sube uno la lista
//   	$consulta = "INSERT INTO lista_correo_lista VALUES (?,?,?)";
//     $conexion->consulta_form($consulta,array(0,$n_lista,$mail_insertar));

//     $n_lista++;
//     $n_registro = 0;

//   }

// }
//Cerramos el archivo
// fclose($archivo);


// $consulta = 
//         "
//         SELECT 
//             id_ven,
//             id_ciu
//         FROM 
//             venta_ciudad
//         ORDER BY 
//             id_ven_ciu
//         "; 
//     $conexion->consulta($consulta);
//     $fila_consulta = $conexion->extraer_registro();
//     if(is_array($fila_consulta)){
//         foreach ($fila_consulta as $fila) {
//         	$id_ven = $fila['id_ven'];
//         	$id_ciu = $fila['id_ciu'];


// 			$consulta = "UPDATE venta_campo_venta SET ciudad_notaria_ven = ? WHERE id_ven = ?";    
// 			$conexion->consulta_form($consulta,array($id_ciu,$id_ven));


// 			$consulta_existe_eta = "SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 5 AND id_cam_eta = 67";
// 	        $conexion->consulta_form($consulta_existe_eta,array($id_ven));
// 	        $existe = $conexion->total();
// 	        if ($existe>0) {
// 	        	$filaeta = $conexion->extraer_registro_unico();
// 	        	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta_cam_ven = ?";
// 				$conexion->consulta_form($consulta,array($id_ciu,$id_ven,$filaeta['id_eta_cam_ven']));
// 	        }

// 	        $consulta_existe_eta = "SELECT id_eta_cam_ven FROM venta_etapa_campo_venta WHERE id_ven = ? AND id_eta = 27 AND id_cam_eta = 68";
// 	        $conexion->consulta_form($consulta_existe_eta,array($id_ven));
// 	        $existe = $conexion->total();
// 	        if ($existe>0) {
// 	        	$filaeta = $conexion->extraer_registro_unico();
// 	        	$consulta = "UPDATE venta_etapa_campo_venta SET valor_campo_eta_cam_ven = ? WHERE id_ven = ? AND id_eta_cam_ven = ?";
// 				$conexion->consulta_form($consulta,array($id_ciu,$id_ven,$filaeta['id_eta_cam_ven']));
// 	        }

// 	        echo $id_ven." ".$id_ciu."<br>";
// 		}
// 	}

?>