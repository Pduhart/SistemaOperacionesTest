<?php
session_start();                      				// HTTP/1.0
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

sleep(1); 
    /*
    You will get 'pk', 'name' and 'value' in $_POST array.
    */
$pk = $_POST['pk'];
$name_not = $_POST['name'];
$value_not = $_POST['value'];

// die($value_not.$pk);

// print_r($_POST);

$promedio = '';
settype($promedio,'float');
$div = 0;

	if ($value_not != "") {
		$id = explode('-',$name_not);
		$id_registro = intval($id[0]);
		$campo = $id[1];
		$valor = str_replace(',','.',$value_not);
		
	    $valor = sprintf("%.1f",preg_replace("/[^.0-9]/i","",$valor));
		if (($valor > 7) or ($valor < 1)) {
			// die('Ingrese solo notas entre 1-7.');
			header('HTTP/1.0 400 Bad Request', true, 400);
        	echo "Ingrese solo notas entre 1-7.";
		} 

		$sql = "update notas set ".$campo." = '".$valor."', not_estado = 'No Validado' where not_id = ".$id_registro;
		$conexion->consulta($sql);
//	if ($content < 4.0) { $color = '#FF0000'; } elseif ($content >= 4.0) { $color = '#0000FF'; }
//	$content = '<span style="color:'.$color.';" id="'.$_POST["id"].'">'.$content.'</span>';
		// print $valor;
	
		$sql = "select not_nota1,not_nota2,not_nota3,not_nota4,not_nota5,not_nota6,not_nota7,not_nota8,not_nota9,not_nota10,not_nota11,not_nota12,not_nota13,not_nota14,not_nota15,not_nota16,not_nota17,not_nota18 from notas where not_id = ".$id_registro;
	
		$conexion->consulta($sql);
		$row = $conexion->extraer_registro();
	
		for ($i = 0; $i <= 17; $i++) { 
			if ( ($row[$i] != '') and ($row[$i] != NULL) and (!empty($row[$i])) and ($row[$i] != 'NULL') and (!is_nan($row[$i])) ) {
				$sumanotas = ( (float)$sumanotas + (float)$row[$i] );
				$div++;
			}
		} 

		if ($div != 0) {	
			$promedio = ((float)$sumanotas / $div);
			$sql = "update notas set not_promedioperiodo = '".round($promedio,1)."' where not_id = ".$id_registro;
		} else {	
			$sql = "update notas set not_promedioperiodo = NULL where not_id = ".$id_registro;
		}
		$conexion->consulta($sql);

	} else {
		$id = explode('-',$name_not);
		$id_registro = intval($id[0]);
		$campo = $id[1];
		$valor = str_replace(',','.',$value_not);	

		$sql = "update notas set ".$campo." = NULL where not_id = ".$id_registro;
		$conexion->consulta($sql);

	/*	$sql = "select ".$campo." from notas where not_id = ".$id_registro;
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);  */

		// print(htmlspecialchars('X')); 

		$sql = "select not_nota1,not_nota2,not_nota3,not_nota4,not_nota5,not_nota6,not_nota7,not_nota8,not_nota9,not_nota10,not_nota11,not_nota12,not_nota13,not_nota14,not_nota15,not_nota16,not_nota17,not_nota18 from notas where not_id = ".$id_registro;
	
		$conexion->consulta($sql);
		$row = $conexion->extraer_registro();
	
		for ($i = 0; $i <= 17; $i++) { 
			if ( ($row[$i] != '') and ($row[$i] != NULL) and (!empty($row[$i])) and ($row[$i] != 'NULL') and (!is_nan($row[$i])) ) {
				$sumanotas = ( (float)$sumanotas + (float)$row[$i] );
				$div++;
			}	
		} 

		if ($div != 0) {	
			$promedio = ((float)$sumanotas / $div);
			$sql = "update notas set not_promedioperiodo = '".round($promedio,1)."' where not_id = ".$id_registro;
		} else {	
			$sql = "update notas set not_promedioperiodo = NULL where not_id = ".$id_registro;
		}
		
		$conexion->consulta($sql);
		//print(htmlspecialchars($promedio));	
	}

?>
