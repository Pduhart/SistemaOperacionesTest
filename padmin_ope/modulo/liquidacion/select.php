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
	$aColumns = array( 'cie.id_cie','usu.nombre_usu','usu.apellido1_usu','usu.apellido2_usu','mes.nombre_mes','cie.anio_cie','cie.fecha_desde_cie','cie.fecha_hasta_cie');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "cie.id_cie";
	
	/* DB table to use */
	$sTable = 
		"
		cierre_cierre AS cie
		INNER JOIN mes_mes AS mes ON mes.id_mes = cie.id_mes
		INNER JOIN usuario_usuario AS usu ON usu.id_usu = cie.id_usu
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
		$filtro = 1;
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
		// "sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	
	if(is_array($fila_consulta)) {
		$consulta = 
			"
			SELECT 
				MAX(cie.id_cie) AS id_cie
			FROM
				cierre_cierre AS cie
			";
		$conexion->consulta($consulta);
		$fila_consulta_ingreso_original = $conexion->extraer_registro();
		$fila_consulta_ingreso = array();
		if(is_array($fila_consulta_ingreso_original)){
			$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_ingreso_original));
	        foreach($it as $v) {
	            $fila_consulta_ingreso[]=$v;
	        }
		}   
		foreach ($fila_consulta as $aRow) {
			$row = array();
			$cantidad_eliminar = 0;
			if(in_array($aRow["id_cie"],$fila_consulta_ingreso)){
                $cantidad_eliminar = 1;	
            }
			
			//$aColumns = array( 'cie.id_cie','con.nombre_con','usu.nombre_usu','usu.apellido1_usu','usu.apellido2_usu','mes.nombre_mes','cie.fecha_desde_cie','cie.fecha_hasta_cie');
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if($aColumns[$i] == "usu.apellido1_usu" || $aColumns[$i] == "usu.apellido2_usu" || $aColumns[$i] == "cie.anio_cie") {
					
				}
				else if( $aColumns[$i] == "cie.id_cie") {
					$row[] =  utf8_encode($aRow["id_cie"]);
				}
				else if( $aColumns[$i] == "usu.nombre_usu") {
					$row[] =  utf8_encode($aRow["nombre_usu"]." ".$aRow["apellido1_usu"]." ".$aRow["apellido2_usu"]);
				}
				else if( $aColumns[$i] == "mes.nombre_mes") {
					$row[] =  utf8_encode($aRow["nombre_mes"]." / ".$aRow['anio_cie']);
				}
				else if( $aColumns[$i] == "cie.fecha_desde_cie") {
					if($aRow["fecha_desde_cie"] != "0000-00-00"){
						$row[] = date("d/m/Y",strtotime($aRow["fecha_desde_cie"]));
					}
					else{
						$row[] = "";
					}
					
				}
				else if( $aColumns[$i] == "cie.fecha_hasta_cie") {
					if($aRow["fecha_hasta_cie"] != "0000-00-00"){
						$row[] = date("d/m/Y",strtotime($aRow["fecha_hasta_cie"]));
					}
					else{
						$row[] = "";
					}
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			$acciones = '<button value="'.$aRow["id_cie"].'" class="btn btn-sm btn-icon btn-info listado_detalle" data-toggle="tooltip" data-original-title="Listado Detalle"><i class="fa fa-list"></i></button>';

			// $acciones .= '<button value="'.$aRow["id_cie"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver"><i class="fa fa-search"></i></button>';
			
			if($cantidad_eliminar > 0){
				$acciones .= '<button value="'.$aRow["id_cie"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
			}

			

				
			
			
			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>