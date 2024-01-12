<?php
	session_start();
	date_default_timezone_set('America/Santiago');
	include '../../class/class_fecha.php';
	require "../../config.php";
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'eta.id_eta','cat_eta.nombre_cat_eta','for_pag.nombre_for_pag','eta.nombre_eta','eta.alias_eta','eta.numero_eta','eta.duracion_eta','est_eta.nombre_est_eta','eta.id_est_eta');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "eta.id_eta";
	
	/* DB table to use */
	$sTable = 
		"
		etapa_etapa AS eta 
		INNER JOIN etapa_categoria_etapa AS cat_eta ON cat_eta.id_cat_eta = eta.id_cat_eta
		INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = eta.id_for_pag
		INNER JOIN etapa_estado_etapa AS est_eta ON est_eta.id_est_eta = eta.id_est_eta
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
		// "sEcho" => intval($_GET['sEcho']),
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
			etapa_campo_etapa
		";
	$conexion->consulta($consulta);
	$fila_consulta_etapa_original = $conexion->extraer_registro();
	$fila_consulta_etapa = array();
	if(is_array($fila_consulta_etapa_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original));
        foreach($it as $v) {
            $fila_consulta_etapa[]=$v;
        }
	}
	

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

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_eta"].'" class="check_registro" id="'.$aRow["id_eta"].'"><label for="'.$aRow["id_eta"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_eta"].'" class="check_registro" id="'.$aRow["id_eta"].'" disabled><label for="'.$aRow["id_eta"].'"><span></span></label>';
			}


			$cantidad_detalle = 0;
			if(in_array($aRow["id_eta"],$fila_consulta_etapa)){
                $cantidad_detalle = 1;	
            }

			/*$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_eta"].'" class="check_registro" id="'.$aRow["id_eta"].'"><label for="'.$aRow["id_eta"].'"><span></span></label>';*/
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "eta.id_eta" || $aColumns[$i] == "eta.id_est_eta" ) {
					
				}
				else if( $aColumns[$i] == "cat_eta.nombre_cat_eta") {
					$row[] =  utf8_encode($aRow["nombre_cat_eta"]);
				}
				else if( $aColumns[$i] == "eta.nombre_eta") {
					$row[] =  utf8_encode($aRow["nombre_eta"]);
				}
				else if( $aColumns[$i] == "eta.alias_eta") {
					$row[] =  utf8_encode($aRow["alias_eta"]);
				}
				else if( $aColumns[$i] == "eta.numero_eta") {
					$row[] =  utf8_encode($aRow["numero_eta"]);
				}
				else if( $aColumns[$i] == "eta.duracion_eta") {
					$row[] =  utf8_encode($aRow["duracion_eta"]);
				}
				else if( $aColumns[$i] == "for_pag.nombre_for_pag") {
					$row[] =  utf8_encode($aRow["nombre_for_pag"]);
				}
				else if( $aColumns[$i] == "est_eta.nombre_est_eta") {
					$row[] =  utf8_encode($aRow["nombre_est_eta"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
			$acciones = '<button value="'.$aRow["id_eta"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver"><i class="fa fa-search"></i></button>';
	        $acciones .= '<button value="'.$aRow["id_eta"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_eta"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

	    	if ($cantidad_detalle > 0) {
	    		$acciones .= '<button value="'.$aRow["id_eta"].'" class="btn btn-sm btn-icon btn-info listado_detalle" data-toggle="tooltip" data-original-title="Detalle"><i class="fa fa-list"></i></button>';
	    	}
	    	

	    	if($aRow["id_est_eta"] == 1){
				$acciones .= '<button value="'.$aRow["id_eta"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_eta"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>