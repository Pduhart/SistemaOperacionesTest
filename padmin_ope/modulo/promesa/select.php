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
	$aColumns = array( 'cot.id_cot','con.nombre_con','tor.nombre_tor','modelo.nombre_mod','viv.nombre_viv','pro.nombre_pro','pro.apellido_paterno_pro','pro.apellido_materno_pro','can_cot.nombre_can_cot','vend.nombre_vend','vend.apellido_paterno_vend','vend.apellido_materno_vend','cot.fecha_cot','est_cot.nombre_est_cot','cot.id_est_cot');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "cot.id_cot";
	
	/* DB table to use */
	$sTable = 
		"
		cotizacion_cotizacion AS cot 
		INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
		INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con	
		INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = cot.id_mod
		INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
		INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
		LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
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
		$sWhere .= "AND (cot.id_est_cot = 3 OR cot.id_est_cot = 4 )";
	}
	else{
		$sWhere .= "WHERE (cot.id_est_cot = 3 OR cot.id_est_cot = 4 )";
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
	
	$consulta = 
		"
		SELECT
			id_cot
		FROM
			cotizacion_seguimiento_cotizacion
		";
	$conexion->consulta($consulta);
	$fila_consulta_seguimiento_original = $conexion->extraer_registro();
	$fila_consulta_seguimiento = array();
	if(is_array($fila_consulta_seguimiento_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_seguimiento_original));
        foreach($it as $v) {
            $fila_consulta_seguimiento[]=$v;
        }
	}

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

			$cantidad_eliminar = 0;
			$mostrar_seguimiento = 0;
			if(in_array($aRow["id_cot"],$fila_consulta_torre)){
                $cantidad_eliminar = 1;	
            }
            if(in_array($aRow["id_cot"],$fila_consulta_seguimiento)){
                $mostrar_seguimiento = 1;	
            }

			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_cot"].'" class="check_registro" id="'.$aRow["id_cot"].'"><label for="'.$aRow["id_cot"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_cot"].'" class="check_registro" id="'.$aRow["id_cot"].'" disabled><label for="'.$aRow["id_cot"].'"><span></span></label>';
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "cot.id_cot" || $aColumns[$i] == "cot.id_est_cot" || $aColumns[$i] == "pro.apellido_paterno_pro" || $aColumns[$i] == "pro.apellido_materno_pro" || $aColumns[$i] == "vend.apellido_paterno_vend" || $aColumns[$i] == "vend.apellido_materno_vend") {
					
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
				else if( $aColumns[$i] == "viv.nombre_viv") {
					$row[] =  utf8_encode($aRow["nombre_viv"]);
				}
				else if( $aColumns[$i] == "est_cot.nombre_est_cot") {
					$row[] =  utf8_encode($aRow["nombre_est_cot"]);
				}
				else if( $aColumns[$i] == "can_cot.nombre_can_cot") {
					$row[] =  utf8_encode($aRow["nombre_can_cot"]);
				}
				else if( $aColumns[$i] == "cot.fecha_cot") {
					$row[] =  date("d/m/Y",strtotime($aRow["fecha_cot"]));
				}
				else if( $aColumns[$i] == "vend.nombre_vend") {
					$row[] =  utf8_encode($aRow["nombre_vend"]." ".$aRow["apellido_paterno_vend"]." ".$aRow["apellido_materno_vend"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			//if ($mostrar_seguimiento > 0) {
				$acciones = '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button>';
			//}
	        //$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	        
	        /*if($cantidad_eliminar == 0){
	        	$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	    	}*/
	    	if($aRow["id_est_cot"] == 4){
	    		$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-danger desistimiento" data-toggle="tooltip" data-original-title="Ingresar Desistimiento"><i class="fa fa-close"></i></button>';
	    	}
	    	
	        //$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info agrega_seguimiento" data-toggle="tooltip" data-original-title="Agregar Seguimiento"><i class="fa fa-plus"></i></button>';

	        if($aRow["id_est_cot"] == 4){
	        	$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-success venta" data-toggle="tooltip" data-original-title="Pasar a Venta"><i class="fa fa-check"></i></button>';
	        }
			
		 	$row[] = $acciones;
		 	$acciones = "";
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>