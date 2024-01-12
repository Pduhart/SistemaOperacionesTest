<?php
	session_start();

	date_default_timezone_set('America/Santiago');

	include '../../class/class_fecha.php';
	require "../../config.php";
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$cantidad_eliminar = 0;
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'bon.id_bon','con.nombre_con','tipo_bono.nombre_tip_bon','mode.nombre_mod','bon.nombre_bon','bon.monto_bon','bon.desde_bon','bon.hasta_bon','bon.fecha_desde_bon','bon.fecha_hasta_bon','est_esta.nombre_est_bon','bon.id_est_bon');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "bon.id_bon";
	
	/* DB table to use */
	$sTable = 
		"
		bono_bono AS bon 
		INNER JOIN condominio_condominio AS con ON con.id_con = bon.id_con
		INNER JOIN bono_tipo_bono AS tipo_bono ON tipo_bono.id_tip_bon = bon.id_tip_bon
		INNER JOIN bono_estado_bono AS est_esta ON est_esta.id_est_bon = bon.id_est_bon
		LEFT JOIN modelo_modelo AS mode ON mode.id_mod = bon.id_mod
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
	// 		id_bon
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
			if(in_array($aRow["id_bon"],$fila_consulta_torre)){
                $cantidad_eliminar = 1;	
            }

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_bon"].'" class="check_registro" id="'.$aRow["id_bon"].'"><label for="'.$aRow["id_bon"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_bon"].'" class="check_registro" id="'.$aRow["id_bon"].'" disabled><label for="'.$aRow["id_bon"].'"><span></span></label>';
			}*/
			$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_bon"].'" class="check_registro" id="'.$aRow["id_bon"].'"><label for="'.$aRow["id_bon"].'"><span></span></label>';
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "bon.id_bon" || $aColumns[$i] == "bon.id_est_bon" ) {
				}
				else if( $aColumns[$i] == "con.nombre_con") {
					$row[] =  utf8_encode($aRow["nombre_con"]);
				}
				else if( $aColumns[$i] == "tipo_bono.nombre_tip_bon") {
					$row[] =  utf8_encode($aRow["nombre_tip_bon"]);
				}
				else if( $aColumns[$i] == "mode.nombre_mod") {
					$row[] =  utf8_encode($aRow["nombre_mod"]);
				}
				else if( $aColumns[$i] == "bon.nombre_bon") {
					$row[] =  utf8_encode($aRow["nombre_bon"]);
				}
				else if( $aColumns[$i] == "bon.desde_bon") {
					$row[] =  utf8_encode($aRow["desde_bon"]);
				}
				else if( $aColumns[$i] == "bon.hasta_bon") {
					$row[] =  utf8_encode($aRow["hasta_bon"]);
				}
				else if( $aColumns[$i] == "bon.monto_bon") {
					$row[] = number_format($aRow["monto_bon"], 1, ',', '.');
				}
				else if( $aColumns[$i] == "bon.fecha_desde_bon") {
					if(!empty($aRow["fecha_desde_bon"])){
						$fecha_desde_bon = date("d/m/Y",strtotime($aRow["fecha_desde_bon"]));
						$row[] = utf8_encode($fecha_desde_bon);
					}
					else{
						$row[] = "";
					}
					
				}
				else if( $aColumns[$i] == "bon.fecha_hasta_bon") {
					if(!empty($aRow["fecha_hasta_bon"])){
						$fecha_hasta_bon = date("d/m/Y",strtotime($aRow["fecha_hasta_bon"]));
						$row[] = utf8_encode($fecha_hasta_bon);
					}
					else{
						$row[] = "";
					}
					
				}
				else if( $aColumns[$i] == "est_esta.nombre_est_bon") {
					$row[] =  utf8_encode($aRow["nombre_est_bon"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
	        $acciones = '<button value="'.$aRow["id_bon"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_bon"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

	    	if($aRow["id_est_bon"] == 1){
				$acciones .= '<button value="'.$aRow["id_bon"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_bon"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>