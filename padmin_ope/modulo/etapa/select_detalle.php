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
	$aColumns = array( 'eta.id_cam_eta','cam_eta.nombre_tip_cam_eta','eta.nombre_cam_eta','eta.id_eta');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "eta.id_cam_eta";
	
	/* DB table to use */
	$sTable = 
		"
		etapa_campo_etapa AS eta 
		INNER JOIN etapa_tipo_campo_etapa AS cam_eta ON cam_eta.id_tip_cam_eta = eta.id_tip_cam_eta
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
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			// $busqueda = utf8_decode($_GET['sSearch']);
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
	
	if($filtro == 1 || $filtro == 2){
		$sWhere .= "AND id_eta = ".$_SESSION["id_etapa_panel"];
	}
	else{
		$sWhere .= "WHERE id_eta = ".$_SESSION["id_etapa_panel"];
	}

	/*
	 * SQL queries
	 * Get data to display
	 */
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
		SELECT COUNT(".$sIndexColumn.")
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
	// $consulta = 
	// 	"
	// 	SELECT
	// 		id_eta
	// 	FROM
	// 		venta_bono_venta
	// 	";
	// $conexion->consulta($consulta);
	// $fila_consulta_torre_original = $conexion->extraer_registro();
	// $fila_consulta_torre = array();
	// if(is_array($fila_consulta_torre_original)){
	// 	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_torre_original));
 //        foreach($it as $v) {
 //            $fila_consulta_torre[]=$v;
 //        }
	// }

	$consulta = 
		"
		SELECT
			id_eta
		FROM
			venta_etapa_venta
		";
	$conexion->consulta($consulta);
	$fila_consulta_detalle_original = $conexion->extraer_registro();
	$fila_consulta_detalle = array();
	if(is_array($fila_consulta_detalle_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_detalle_original));
        foreach($it as $v) {
            $fila_consulta_detalle[]=$v;
        }
	}
	

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

			$cantidad_eliminar = 0;
			if(in_array($aRow["id_eta"],$fila_consulta_detalle)){
                $cantidad_eliminar = 1;	
            }
			/*$cantidad_eliminar = 0;
			if(in_array($aRow["id_eta"],$fila_consulta_torre)){
                $cantidad_eliminar = 1;	
            }

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_eta"].'" class="check_registro" id="'.$aRow["id_eta"].'"><label for="'.$aRow["id_eta"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_eta"].'" class="check_registro" id="'.$aRow["id_eta"].'" disabled><label for="'.$aRow["id_eta"].'"><span></span></label>';
			}*/
			$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_cam_eta"].'" class="check_registro" id="'.$aRow["id_cam_eta"].'"><label for="'.$aRow["id_cam_eta"].'"><span></span></label>';
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "eta.id_cam_eta" || $aColumns[$i] == "eta.id_est_eta" || $aColumns[$i] == "eta.id_eta" ) {
					
				}
				else if( $aColumns[$i] == "cam_eta.nombre_tip_cam_eta") {
					$row[] =  utf8_encode($aRow["nombre_tip_cam_eta"]);
				}
				else if( $aColumns[$i] == "eta.nombre_cam_eta") {
					$row[] =  utf8_encode($aRow["nombre_cam_eta"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
			$acciones = '<button value="'.$aRow["id_cam_eta"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_cam_eta"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>