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
	$aColumns = array('lis.id_lis','lis.nombre_lis');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "lis.id_lis";
	
	/* DB table to use */
	$sTable = 
		"
		lista_lista AS lis
		LEFT JOIN lista_vendedor_lista AS ven_lis ON ven_lis.id_lis = lis.id_lis
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
	$filtro = 0;
	$acciones = 0;
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
			$sWhere .= " AND ven_lis.id_vend = ".$_SESSION["sesion_id_vend"]." ";
		}
		else{
			$sWhere .= " WHERE ven_lis.id_vend = ".$_SESSION["sesion_id_vend"]." ";
			$filtro = 2;
		}
	}



	// if ($_SESSION["sesion_perfil_panel"] == 3) {
	// 	if($filtro == 1){
	// 		$sWhere .= "AND usu.id_usu = ".$_SESSION["sesion_id_panel"]." ";
	// 	}
	// 	else{
	// 		$sWhere .= "WHERE usu.id_usu = ".$_SESSION["sesion_id_panel"]."";
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

	// echo $sQuery."<<----";
	// $sQuery = "SELECT SQL_CALC_FOUND_ROWS pro.id_pro, CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName, viv.id_viv, pro.rut_pro, pro.nombre_pro, pro.nombre2_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro, pro.fono_pro, pro.direccion_pro, pro.correo_pro, prof.nombre_prof, est_pro.nombre_est_pro, pro.id_est_pro FROM propietario_propietario AS pro INNER JOIN propietario_estado_propietario AS est_pro ON est_pro.id_est_pro = pro.id_est_pro INNER JOIN vendedor_propietario_vendedor AS ven_pro ON ven_pro.id_pro = pro.id_pro INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof LEFT JOIN propietario_vivienda_propietario AS viv ON viv.id_pro = pro.id_pro WHERE (pro.id_pro LIKE '%jara al%' OR MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('jara al' IN BOOLEAN MODE) OR viv.id_viv LIKE '%jara al%' OR pro.rut_pro LIKE '%jara al%' OR pro.nombre_pro LIKE '%jara al%' OR pro.nombre2_pro LIKE '%jara al%' OR pro.apellido_paterno_pro LIKE '%jara al%' OR pro.apellido_materno_pro LIKE '%jara al%' OR pro.fono_pro LIKE '%jara al%' OR pro.direccion_pro LIKE '%jara al%' OR pro.correo_pro LIKE '%jara al%' OR prof.nombre_prof LIKE '%jara al%' OR est_pro.nombre_est_pro LIKE '%jara al%' OR pro.id_est_pro LIKE '%jara al%' ) ORDER BY fullName desc LIMIT 0, 50";

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
			$cantidad_eliminar = 1;

			if($_SESSION["sesion_perfil_panel"]==1){
				if($cantidad_eliminar == 0){
					$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_lis"].'" class="check_registro" id="'.$aRow["id_lis"].'"><label for="'.$aRow["id_lis"].'"><span></span></label>';
				}
				else{
					$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_lis"].'" class="check_registro" id="'.$aRow["id_lis"].'" disabled><label for="'.$aRow["id_lis"].'"><span></span></label>';
				}
			}

			if($_SESSION["sesion_perfil_panel"]==4){
				// $row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pro"].'" class="check_registro_obs" id="'.$aRow["id_pro"].'"><label for="'.$aRow["id_pro"].'"><span></span></label><br><input type="checkbox" name="check_mail" value="'.$aRow["id_pro"].'" class="check_registro_mail" id="mail_'.$aRow["id_pro"].'"><label for="mail_'.$aRow["id_pro"].'"><span></span></label>';
				$row[] = '<input type="checkbox" name="check_mail" value="'.$aRow["id_lis"].'" class="check_registro_mail" id="mail_'.$aRow["id_lis"].'"><label for="mail_'.$aRow["id_lis"].'"><span></span></label>';

			}

			if($_SESSION["sesion_perfil_panel"] <> 4 && $_SESSION["sesion_perfil_panel"] <> 1) {
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_lis"].'" class="check_registro" id="'.$aRow["id_lis"].'" disabled><label for="'.$aRow["id_lis"].'"><span></span></label>';
			}

			// $aColumns = array('pro.id_pro','pro.nombre_pro','pro.rut_pro','pro.fono_pro','pro.correo_pro','est_pro.nombre_est_pro','pro.id_est_pro');
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if($aColumns[$i] == "lis.id_lis") {
				}
				else if( $aColumns[$i] == "lis.nombre_lis") {
					$row[] =  utf8_encode($aRow["nombre_lis"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			

	        
	    	if ($_SESSION["sesion_perfil_panel"] != 3 && $_SESSION["sesion_perfil_panel"] != 6) {

		    	if($cantidad_eliminar == 0){
		        	$acciones .= '<button value="'.$aRow["id_lis"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
		    	}       
                
                
	    	}
			
		 	$row[] = $acciones; 
		 	$acciones = '';                                      
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>