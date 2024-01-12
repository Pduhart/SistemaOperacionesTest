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
	$aColumns = array( 'id_uni','nombre_uni','codigo_uni');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id_uni";
	
	/* DB table to use */
	$sTable = "unidad_unidad";
	
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
			$sWhere .= $aColumns[$i]." LIKE '%".htmlentities($_GET['sSearch'], ENT_QUOTES, "UTF-8")."%' OR ";
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
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	$consulta = 
		"
		SELECT
			id_uni
		FROM
			solicitud_solicitud
		";
	$conexion->consulta($consulta);
	$fila_consulta_solicitud_original = $conexion->extraer_registro();
	$fila_consulta_solicitud = array();
	if(is_array($fila_consulta_solicitud_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_solicitud_original));
        foreach($it as $v) {
            $fila_consulta_solicitud[]=$v;
        }
	}

	$consulta = 
		"
		SELECT
			id_uni
		FROM
			proyecto_proyecto
		";
	$conexion->consulta($consulta);
	$fila_consulta_remesa_original = $conexion->extraer_registro();
	$fila_consulta_remesa = array();
	if(is_array($fila_consulta_remesa_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_remesa_original));
        foreach($it as $v) {
            $fila_consulta_proyecto[]=$v;
        }
	}
	if(is_array($fila_consulta)){
		foreach ($fila_consulta as $aRow) {
			$row = array();
			$cantidad_eliminar = 0;
			if(in_array($aRow["id_uni"],$fila_consulta_solicitud)){
                $cantidad_eliminar = 1;	
            }

            if(in_array($aRow["id_uni"],$fila_consulta_proyecto)){
                $cantidad_eliminar = 1;	
                
            }

			if($cantidad_eliminar == 0){
				// $row[] = '<label><input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="flat-red check_registro"></label>';
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'"><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			}
			else{
				// $row[] = '<label><input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="flat-red check_registro" disabled></label>';
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'" disabled><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			}

			// $row[] ="<div class=\"checkbox-custom checkbox-warning\"> <input type=\"checkbox\" value=\"".$aRow[$aColumns[0]]."\" name=\"check".$aRow[$aColumns[0]]."\" class=\"check_registro\" /><label></label></div>";
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "id_uni") {

				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
	        $acciones = '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';

	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>