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
	$aColumns = array('id_pro','id_uni','id_tip_pro','id_bon_pro','id_com_pro','nombre_pro','codigo_pro','id_est_pro');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id_pro";
	
	/* DB table to use */
	$sTable = "proyecto_proyecto";
	
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
		foreach ($fila_consulta as $aRow) {
			$row = array();
			$consulta = 
				"
				SELECT
					id_res
				FROM
					reserva_reserva
				WHERE
					id_pro = ".$aRow[$aColumns[0]]."
				";
			$conexion->consulta($consulta);
			$cantidad_eliminar = $conexion->total();

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'"><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'" disabled><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			}
			
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "id_pro") {
				}
				else if( $aColumns[$i] == "codigo_pro") {
					$row[] = $codigo_uni."-".$aRow[$aColumns[$i]];
				}
				else if( $aColumns[$i] == "id_est_pro") {
					$consulta_estado = 
						"
						SELECT
							nombre_est_pro
						FROM
							proyecto_estado_proyecto
						WHERE
							id_est_pro = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_est_pro"]);
					
				}
				else if( $aColumns[$i] == "id_tip_pro") {
					$consulta_estado = 
						"
						SELECT
							nombre_tip_pro
						FROM
							proyecto_tipo_proyecto
						WHERE
							id_tip_pro = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_tip_pro"]);
					
				}
				else if( $aColumns[$i] == "id_bon_pro") {
					$consulta_estado = 
						"
						SELECT
							nombre_bon_pro
						FROM
							proyecto_bono_proyecto
						WHERE
							id_bon_pro = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_bon_pro"]);
				}
				else if( $aColumns[$i] == "id_com_pro") {
					$consulta_estado = 
						"
						SELECT
							nombre_com_pro
						FROM
							proyecto_comentario_proyecto
						WHERE
							id_com_pro = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_com_pro"]);
					
				}
				else if( $aColumns[$i] == "id_uni") {
					$consulta_estado = 
						"
						SELECT
							nombre_uni,
							codigo_uni
						FROM
							unidad_unidad
						WHERE
							id_uni = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();
					$codigo_uni =  utf8_encode($fila["codigo_uni"]);				
					$row[] =  utf8_encode($fila["nombre_uni"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			if($aRow[$aColumns[2]] != 1){
				$acciones .= '<button value="'.$aRow[$aColumns[0]].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver"><i class="fa fa-search"></i></button>';
			}
			
	        $acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';

	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

	        if($aRow[$aColumns[7]] == 1){

	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }

			
		 	$row[] = $acciones; 
		 	$acciones = '';                                      
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>