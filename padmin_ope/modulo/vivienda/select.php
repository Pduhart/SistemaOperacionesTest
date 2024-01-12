<?php
	session_start();
	require "../../config.php";
	include '../../class/class_fecha.php';
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$acciones = '';
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'viv.id_viv','con.nombre_con','tor.nombre_tor','modelo.nombre_mod','piso.nombre_pis','pro.nombre_pro','pro.apellido_paterno_pro','pro.apellido_materno_pro','ori_viv.nombre_ori_viv','viv.nombre_viv','viv.valor_viv','viv.metro_viv','viv.metro_terraza_viv','viv.bono_viv','viv.prorrateo_viv','est_viv.nombre_est_viv','viv.id_est_viv');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "viv.id_viv";
	
	/* DB table to use */
	$sTable = 
		"
		vivienda_vivienda AS viv 
		INNER JOIN vivienda_estado_vivienda AS est_viv ON est_viv.id_est_viv = viv.id_est_viv
		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		LEFT JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
		LEFT JOIN piso_piso AS piso ON piso.id_pis = viv.id_pis
		LEFT JOIN propietario_vivienda_propietario AS prop ON prop.id_viv = viv.id_viv
		LEFT JOIN propietario_propietario AS pro ON pro.id_pro = prop.id_pro
		LEFT JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
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
	
	$filtro = 0;
	if (isset($_SESSION["sesion_filtro_condominio_panel"])) {
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"]." )";
		}
		else{
			$sWhere .= "WHERE (con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"]." )";
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
			id_viv
		FROM
			venta_venta
		";
	$conexion->consulta($consulta);
	$fila_consulta_torre_original = $conexion->extraer_registro();
	$fila_consulta_torre = array();
	if(is_array($fila_consulta_torre_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_torre_original));
        foreach($it as $v) {
            $fila_consulta_torre[]=$v;
        }
	}
	

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

			$cantidad_eliminar = 0;
			if(in_array($aRow["id_viv"],$fila_consulta_torre)){
                $cantidad_eliminar = 1;	
            }

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_viv"].'" class="check_registro" id="'.$aRow["id_viv"].'"><label for="'.$aRow["id_viv"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_viv"].'" class="check_registro" id="'.$aRow["id_viv"].'" disabled><label for="'.$aRow["id_viv"].'"><span></span></label>';
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "viv.id_viv" || $aColumns[$i] == "viv.id_est_viv" || $aColumns[$i] == "pro.apellido_paterno_pro" || $aColumns[$i] == "pro.apellido_materno_pro") {
					
				}
				else if( $aColumns[$i] == "con.nombre_con") {
					$row[] =  utf8_encode($aRow["nombre_con"]);
				}
				else if( $aColumns[$i] == "tor.nombre_tor") {
					$row[] =  utf8_encode($aRow["nombre_tor"]);
				}
				else if( $aColumns[$i] == "pro.nombre_pro") {
					$row[] =  utf8_encode($aRow["nombre_pro"]." ".$aRow["apellido_paterno_pro"]." ".$aRow["apellido_materno_pro"]);
				}
				else if( $aColumns[$i] == "modelo.nombre_mod") {
					$row[] =  utf8_encode($aRow["nombre_mod"]);
				}
				else if( $aColumns[$i] == "piso.nombre_pis") {
					$row[] =  utf8_encode($aRow["nombre_pis"]);
				}
				else if( $aColumns[$i] == "viv.nombre_viv") {
					$row[] =  utf8_encode($aRow["nombre_viv"]);
				}
				else if( $aColumns[$i] == "ori_viv.nombre_ori_viv") {
					$row[] =  utf8_encode($aRow["nombre_ori_viv"]);
				}
				else if( $aColumns[$i] == "viv.valor_viv") {
					$row[] = number_format($aRow["valor_viv"], 2, ',', '.');
				}
				else if( $aColumns[$i] == "viv.metro_viv") {
					$row[] = number_format($aRow["metro_viv"], 2, ',', '.');
				}
				else if( $aColumns[$i] == "viv.bono_viv") {
					$row[] = number_format($aRow["bono_viv"], 2, ',', '.');
				}
				else if( $aColumns[$i] == "viv.prorrateo_viv") {
					$row[] = number_format($aRow["prorrateo_viv"], 2, ',', '.');
				}
				else if( $aColumns[$i] == "est_viv.nombre_est_viv") {
					$row[] =  utf8_encode($aRow["nombre_est_viv"]);
				}
				else if( $aColumns[$i] == "viv.metro_terraza_viv") {
					$row[] =  number_format($aRow["metro_terraza_viv"], 2, ',', '.');
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			
	        $acciones = '<button value="'.$aRow["id_viv"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_viv"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}

	    	if($aRow["id_est_viv"] == 1){
				$acciones .= '<button value="'.$aRow["id_viv"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_viv"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }
	        $acciones .= '<a href="contrato.php?id='.$aRow["id_viv"].'" class="btn btn-sm btn-icon btn-info" data-toggle="tooltip" data-original-title="Emitir Contrato" target="_blank"><i class="fa fa-file-text-o"></i></a>';
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>