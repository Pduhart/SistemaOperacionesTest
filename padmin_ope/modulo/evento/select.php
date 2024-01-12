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
	$aColumns = array( 'id_eve','nombre_eve','id_cot','fecha_eve','time_eve','id_usu','id_est_eve');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id_eve";
	
	/* DB table to use */
	$sTable = "evento_evento";
	
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

	if($_SESSION["sesion_perfil_panel"] == 4){
		if($filtro == 1 || $filtro == 2){
			$sWhere .= " AND id_usu = ".$_SESSION["sesion_id_panel"]." ";
		}
		else{
			$sWhere .= " WHERE id_usu = ".$_SESSION["sesion_id_panel"]." ";
		}
	}
	
	// if ($_SESSION["sesion_perfil_panel_intranet"] == 3) {
	// 	if($filtro == 1){
	// 		$sWhere .= "AND id_usuario_cho = ".$_SESSION["sesion_id_panel_intranet"]."";
	// 	}
	// 	else{
	// 		$sWhere .= "WHERE id_usuario_cho =".$_SESSION["sesion_id_panel_intranet"]." ";
	// 	}
	// }
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
	// $prueba_consulta = $sQuery;
	// $sQuery = "
	// 	SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
	// 	FROM   $sTable
		
	// 	$sOrder
	// 	$sLimit
	// ";
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

			$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[0]].'" class="fx check_registro" id="'.$aRow[$aColumns[0]].'"><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "id_eve") {

				}
				else if( $aColumns[$i] == "fecha_eve") {
					$fecha_eve = date("d/m/Y",strtotime($aRow[$aColumns[$i]]));
					$row[] = $fecha_eve; 
				}
				else if( $aColumns[$i] == "id_cot") {
					$consulta = 
						"
						SELECT
							pro.nombre_pro,
							pro.apellido_paterno_pro,
							pro.apellido_materno_pro
						FROM
							propietario_propietario AS pro,
							cotizacion_cotizacion AS cot
						WHERE
							cot.id_cot = ".$aRow[$aColumns[$i]]." AND
							cot.id_pro = pro.id_pro
						";
					$conexion->consulta($consulta);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_pro"]." ".$fila["apellido_paterno_pro"]." ".$fila["apellido_materno_pro"]);
				}
				else if( $aColumns[$i] == "id_est_eve") {
					$consulta = 
						"
						SELECT
							nombre_est_eve
						FROM
							evento_estado_evento
						WHERE
							id_est_eve = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_est_eve"]);
				}

				else if( $aColumns[$i] == "id_usu") {
					$consulta = 
						"
						SELECT
							nombre_vend,
							apellido_paterno_vend
						FROM
							vendedor_vendedor
						WHERE
							id_usu = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}

			$acciones = '<button value="'.$aRow[$aColumns[0]].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button> ';
			
	        $acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';

	        
		        
	    	//ESTADO
		    if($aRow[$aColumns[4]] == 1){

	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Reactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Pasar a Realizado"><i class="fa fa-check-square-o"></i></button>';
	        }

			if($_SESSION["sesion_perfil_panel"] <> 4){
	        	$acciones .= '<button value="'.$aRow[$aColumns[0]].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';	
		    }
	        

			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>