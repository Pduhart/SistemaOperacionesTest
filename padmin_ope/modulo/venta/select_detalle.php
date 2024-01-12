<?php
	session_start();
	require "../../config.php";
	include '../../class/class_fecha.php';
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'pag.id_pag','cat_pag.nombre_cat_pag','ban.nombre_ban','for_pag.nombre_for_pag','pag.fecha_pag','pag.fecha_real_pag','pag.numero_documento_pag','pag.numero_serie_pag','pag.monto_pag','est_pag.nombre_est_pag','pag.id_est_pag');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pag.id_pag";
	
	/* DB table to use */
	$sTable = 
		"
		pago_pago AS pag 
		INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
		INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
		LEFT JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
		INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
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
		$sWhere .= "AND pag.id_ven = ".$_SESSION["id_venta_panel"];
	}
	else{
		$sWhere .= "WHERE pag.id_ven = ".$_SESSION["id_venta_panel"];
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
	

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

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
			$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pag"].'" class="check_registro" id="'.$aRow["id_pag"].'"><label for="'.$aRow["id_pag"].'"><span></span></label>';
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "pag.id_pag" || $aColumns[$i] == "pag.id_est_pag" ) {
					
				}
				else if( $aColumns[$i] == "cat_pag.nombre_cat_pag") {
					$row[] =  utf8_encode($aRow["nombre_cat_pag"]);
				}
				else if( $aColumns[$i] == "for_pag.nombre_for_pag") {
					$row[] =  utf8_encode($aRow["nombre_for_pag"]);
				}
				else if( $aColumns[$i] == "est_pag.nombre_est_pag") {
					$row[] =  utf8_encode($aRow["nombre_est_pag"]);
				}
				else if( $aColumns[$i] == "pag.fecha_pag") {
					$row[] =  date("d/m/Y",strtotime($aRow["fecha_pag"]));
				}
				else if( $aColumns[$i] == "pag.fecha_real_pag") {
					if(!empty($aRow["fecha_real_pag"]) && $aRow["fecha_real_pag"] != '0000-00-00'){
						$row[] =  date("d/m/Y",strtotime($aRow["fecha_real_pag"]));
					}
					else{
						$row[] = "";
					}
					
				}
				else if( $aColumns[$i] == "pag.numero_documento_pag") {
					$row[] =  utf8_encode($aRow["numero_documento_pag"]);
				}
				else if( $aColumns[$i] == "pag.numero_serie_pag") {
					// $row[] =  utf8_encode($aRow["numero_serie_pag"]);
				}
				else if( $aColumns[$i] == "pag.monto_pag") {
					$row[] = number_format($aRow["monto_pag"], 0, ',', '.');
 				}
				else if( $aColumns[$i] == "ban.nombre_ban") {
					$row[] =  utf8_encode($aRow["nombre_ban"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
			$acciones = '<button value="'.$aRow["id_pag"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_pag"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}
	    	
	    	if($aRow["id_est_pag"] == 1){
				$acciones .= '<button value="'.$aRow["id_pag"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Dejar Pendiente Pago"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_pag"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Pagar"><i class="fa fa-check-square-o"></i></button>';
	        	
	        	$acciones .= '<button value="'.$aRow["id_pag"].'" type="button" class="btn btn-sm btn-icon btn-danger protestar" data-toggle="tooltip" data-original-title="Protestar"><i class="fa fa-gavel"></i></button>';
	        }
			
		 	$row[] = $acciones;                   
			$output['aaData'][] = $row;
			$row[] = ''; 
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>