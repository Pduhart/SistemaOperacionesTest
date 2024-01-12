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
	$aColumns = array( 'des.id_des','con.nombre_con','des.nombre_des','des.monto_des','est_des.nombre_est_des','des.id_est_des');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "des.id_des";
	
	/* DB table to use */
	$sTable = 
		"
		descuento_descuento AS des 
		INNER JOIN condominio_condominio AS con ON con.id_con = des.id_con
		INNER JOIN descuento_estado_descuento AS est_des ON est_des.id_est_des = des.id_est_des
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
	$consulta = 
		"
		SELECT
			id_des
		FROM
			venta_venta
		";
	$conexion->consulta($consulta);
	$fila_consulta_descuento_original = $conexion->extraer_registro();
	$fila_consulta_descuento = array();
	if(is_array($fila_consulta_descuento_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_descuento_original));
        foreach($it as $v) {
            $fila_consulta_descuento[]=$v;
        }
	}
	

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

			$cantidad_eliminar = 0;
			if(in_array($aRow["id_des"],$fila_consulta_descuento)){
                $cantidad_eliminar = 1;	
            }

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_des"].'" class="check_registro" id="'.$aRow["id_des"].'"><label for="'.$aRow["id_des"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_des"].'" class="check_registro" id="'.$aRow["id_des"].'" disabled><label for="'.$aRow["id_des"].'"><span></span></label>';
			}
			//$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_des"].'" class="check_registro" id="'.$aRow["id_des"].'"><label for="'.$aRow["id_des"].'"><span></span></label>';
			
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "des.id_des" || $aColumns[$i] == "des.id_est_des" ) {
					
				}
				else if( $aColumns[$i] == "des.nombre_des") {
					$row[] =  utf8_encode($aRow["nombre_des"]);
				}
				else if( $aColumns[$i] == "con.nombre_con") {
					$row[] =  utf8_encode($aRow["nombre_con"]);
				}
				else if( $aColumns[$i] == "des.monto_des") {
					$row[] = number_format($aRow["monto_des"], 0, ',', '.');
				}
				else if( $aColumns[$i] == "est_des.nombre_est_des") {
					$row[] =  utf8_encode($aRow["nombre_est_des"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
	        $acciones = '<button value="'.$aRow["id_des"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_des"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

	    	if($aRow["id_est_des"] == 1){
				$acciones .= '<button value="'.$aRow["id_des"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_des"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>