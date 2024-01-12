<?php
	session_start();
	include '../../class/class_fecha.php';
	require "../../config.php";
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'res.id_res','con.nombre_con','tor.nombre_tor','mode.nombre_mod','viv.nombre_viv','prog.nombre_prog','res.fecha_desde_res','res.fecha_hasta_res','res.cantidad_dia_res','res.cantidad_pasajero_res','tip_res.nombre_tip_res','res.monto_total_res','res.monto_interno_res','res.monto_adicional_res','est_res.nombre_est_res','res.id_est_res');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "res.id_res";
	
	/* DB table to use */
	$sTable = 
		"
		reserva_reserva AS res
		INNER JOIN reserva_tipo_reserva AS tip_res ON tip_res.id_tip_res = res.id_tip_res
		INNER JOIN reserva_estado_reserva AS est_res ON est_res.id_est_res = res.id_est_res
		INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
		INNER JOIN programa_programa AS prog ON prog.id_prog = res.id_prog
		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
		";
	
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".
			$_GET['iDisplayLength'];
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] )-1 ]."
				 	".$_GET['sSortDir_'.$i].", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$filtro = 1;
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".utf8_decode($_GET['sSearch'])."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			$filtro = 2;
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}
	// $filtro_consulta = "";
	// if (isset($_SESSION["sesion_filtro_numero_reserva_panel"])) {
	// 	$filtro_consulta .= " AND id_res = '".$_SESSION["sesion_filtro_numero_reserva_panel"]."'";
	// }

	// $filtro_informe = 0;
	// //ADMINISTRADOR o CHOFER
	// if ($_SESSION["sesion_perfil_panel"] == 1 || $_SESSION["sesion_perfil_panel"] == 3) {
	// 	if($filtro == 1){
	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$filtro_informe = 1;
	// 			$sWhere .= "AND id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"] ."'".$filtro_consulta;
	// 		}
	// 	}
	// 	else{
			
	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$filtro_informe = 1;
	// 			$sWhere .= "WHERE id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"]."'".$filtro_consulta;
	// 		}
	// 	}
	// }
	// //FUNCIONARIO
	// if ($_SESSION["sesion_perfil_panel"] == 2) {
	// 	if($filtro == 1){
	// 		$sWhere .= "AND id_usu = '".$_SESSION["sesion_id_panel"] ."'".$filtro_consulta;

	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$filtro_informe = 1;
	// 			$sWhere .= "AND id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"] ."'".$filtro_consulta;
	// 		}
	// 	}
	// 	else{
	// 		$filtro_informe = 1;
	// 		$sWhere .= "WHERE id_usu = '".$_SESSION["sesion_id_panel"] ."' ".$filtro_consulta;

	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$sWhere .= "AND id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"] ."' ".$filtro_consulta;
	// 		}
	// 	}
	// }
	//CHOFER
	// if ($_SESSION["sesion_perfil_panel"] == 3) {
	// 	if($filtro == 1){
	// 		$filtro_informe = 1;
	// 		$sWhere .= "AND usuario_chofer_id = '".$_SESSION["sesion_id_panel"] ."' ".$filtro_consulta;
	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$sWhere .= "AND id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"] ."' ".$filtro_consulta;
	// 		}
	// 	}
	// 	else{
	// 		$filtro_informe = 1;
	// 		$sWhere .= "WHERE usuario_chofer_id = '".$_SESSION["sesion_id_panel"] ."' ".$filtro_consulta;
	// 		if (isset($_SESSION["sesion_filtro_reserva_panel"])) {
	// 			$sWhere .= "AND id_est_res = '".$_SESSION["sesion_filtro_reserva_panel"] ."' ".$filtro_consulta;
	// 		}
	// 	}
	// }

	
	// if($filtro_informe == 0){
	// 	if (isset($_SESSION["sesion_filtro_numero_reserva_panel"])) {
	// 		if($filtro == 1){
	// 			$sWhere .= " AND id_res = '".$_SESSION["sesion_filtro_numero_reserva_panel"]."'";
	// 		}
	// 		else{
	// 			$sWhere .= " WHERE id_res = '".$_SESSION["sesion_filtro_numero_reserva_panel"]."'";
	// 		}
			
	// 	}
	// }
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	//------------ 	consultas ---------------

	// $consulta = "SELECT id_tac FROM tac_tac ORDER BY id_tac DESC LIMIT 0,1";
	
	// $conexion->consulta($consulta);
	// $cantidad_tac = $conexion->total();
	// $fila_tac = $conexion->extraer_registro_unico();
	
	// if($cantidad_tac > 0){
	// 	$consulta = "SELECT id_res FROM tac_detalle_tac WHERE id_tac = ".$fila_tac["id_tac"]." ";
	// 	$conexion->consulta($consulta);
	// 	$cantidad_detalle = $conexion->total();
	// 	$consulta_detalle_tac_original = $conexion->extraer_registro();
		
	// 	if($cantidad_detalle > 0){
	// 	  	$array_minimo = new RecursiveIteratorIterator(new RecursiveArrayIterator($consulta_detalle_tac_original));
	// 	  	$consulta_detalle_tac = array();
	// 	  	foreach($array_minimo as $v) {
	// 			$consulta_detalle_tac[]=$v;
	// 	  	}
	// 	}
	// }
	// else{
	// 	$cantidad_detalle = 0;
	// }
	


	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$conexion->consulta($sQuery);

	$fila_consulta = $conexion->extraer_registro();
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$conexion->consulta($sQuery);
	$fila_consulta2 = $conexion->extraer_registro_unico();

	
	$iFilteredTotal = $fila_consulta2[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.") AS suma
		FROM   $sTable
	";
	$conexion->consulta($sQuery);
	$fila_consulta3 = $conexion->extraer_registro_unico();

	$iTotal = $fila_consulta3[0];
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	
	if(is_array($fila_consulta)) {
		$consulta = 
			"
			SELECT
				id_res
			FROM
				ingreso_reserva_ingreso
			";
		$conexion->consulta($consulta);
		$fila_consulta_ingreso_original = $conexion->extraer_registro();
		$fila_consulta_ingreso = array();
		if(is_array($fila_consulta_ingreso_original)){
			$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_ingreso_original));
	        foreach($it as $v) {
	            $fila_consulta_ingreso[]=$v;
	        }
		}   
		foreach ($fila_consulta as $aRow) {
			$row = array();
			if(in_array($aRow["id_res"],$fila_consulta_ingreso)){
                $cantidad_eliminar = 1;	
            }
			
			// if ($_SESSION["sesion_perfil_panel"] == 1) {
			// 	if($cantidad_eliminar == 0){
			// 		$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_res"].'" class="check_registro" id="'.$aRow["id_res"].'"><label for="'.$aRow["id_res"].'"><span></span></label>';
			// 	}
			// 	else{
			// 		$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_res"].'" class="check_registro" id="'.$aRow["id_res"].'" disabled><label for="'.$aRow["id_res"].'"><span></span></label>';
			// 	}
			// }
			// else{
			// 	$row[] = '';
			// }
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if($aColumns[$i] == "res.id_est_res" || $aColumns[$i] == "res.monto_interno_res" || $aColumns[$i] == "res.monto_adicional_res") {
					
				}
				else if( $aColumns[$i] == "res.id_res") {
					$row[] =  utf8_encode($aRow["id_res"]);
				}
				else if( $aColumns[$i] == "est_res.nombre_est_res") {
					$row[] =  utf8_encode($aRow["nombre_est_res"]);
				}
				else if( $aColumns[$i] == "con.nombre_con") {
					$row[] =  utf8_encode($aRow["nombre_con"]);
				}
				else if( $aColumns[$i] == "tor.nombre_tor") {
					$row[] =  utf8_encode($aRow["nombre_tor"]);
				}
				else if( $aColumns[$i] == "mode.nombre_mod") {
					$row[] =  utf8_encode($aRow["nombre_mod"]);
				}
				else if( $aColumns[$i] == "viv.nombre_viv") {
					$row[] =  utf8_encode($aRow["nombre_viv"]);
				}
				else if( $aColumns[$i] == "prog.nombre_prog") {
					$row[] =  utf8_encode($aRow["nombre_prog"]);
				}
				else if( $aColumns[$i] == "tip_res.nombre_tip_res") {
					$row[] =  utf8_encode($aRow["nombre_tip_res"]);
				}
				else if( $aColumns[$i] == "res.cantidad_dia_res") {
					$row[] = number_format($aRow["cantidad_dia_res"], 0, ',', '.');
				}
				else if( $aColumns[$i] == "res.cantidad_pasajero_res") {
					$row[] = number_format($aRow["cantidad_pasajero_res"], 0, ',', '.');
				}
				else if( $aColumns[$i] == "res.monto_total_res") {
					$monto = $aRow["monto_total_res"];
					$row[] = number_format($monto, 0, ',', '.');
				}
				else if( $aColumns[$i] == "res.fecha_desde_res") {
					if($aRow["fecha_desde_res"] != "0000-00-00"){
						$row[] = date("d/m/Y",strtotime($aRow["fecha_desde_res"]));
					}
					else{
						$row[] = "";
					}
					
				}
				else if( $aColumns[$i] == "res.fecha_hasta_res") {
					if($aRow["fecha_hasta_res"] != "0000-00-00"){
						$row[] = date("d/m/Y",strtotime($aRow["fecha_hasta_res"]));
					}
					else{
						$row[] = "";
					}
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			$acciones = '<button value="'.$aRow["id_res"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver"><i class="fa fa-search"></i></button>';
			if($aRow["id_est_res"] == 2){
				if ($_SESSION["sesion_perfil_panel"] == 1 || $_SESSION["sesion_perfil_panel"] == 3) {
		        	$acciones .= '<button value="'.$aRow["id_res"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
		        }
		        else{
		        	$hoy = date("Y-m-d");
					if($aRow["fecha_desde_res"] < $hoy){
						$acciones .= '<button value="'.$aRow["id_res"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
					}
		        }

		        //SÓLO ADMIN Y CHOFER PUEDE ELIMINAR
				if ($_SESSION["sesion_perfil_panel"] == 1 || $_SESSION["sesion_perfil_panel"] == 3) {
					if($cantidad_eliminar == 0){
			        	$acciones .= '<button value="'.$aRow["id_res"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
			    	}	
				}
				else{
					$hoy = date("Y-m-d");
					if($aRow["fecha_desde_res"] < $hoy){
						$acciones .= '<button value="'.$aRow["id_res"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
					}
				}
			}
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>