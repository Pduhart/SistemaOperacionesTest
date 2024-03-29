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
	$aColumns = array('mode.id_mod','mode.nombre_mod','mode.numero_cama_mod','mode.numero_banio_mod','mode.descripcion_mod','est_mod.nombre_est_mod','est_mod.id_est_mod');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "mode.id_mod";
	
	/* DB table to use */
	$sTable =
		"
		modelo_modelo AS mode 
		INNER JOIN modelo_estado_modelo AS est_mod ON est_mod.id_est_mod = mode.id_est_mod
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
	$sEcho = (isset($_GET['sEcho']))?intval($_GET['sEcho']):'';
	$output = array(
		"sEcho" => $sEcho,
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	$acciones = '';
	if(is_array($fila_consulta)) {
		$consulta = 
			"
			SELECT
				id_mod
			FROM
				vivienda_vivienda
			";
		$conexion->consulta($consulta);
		$fila_consulta_vivienda_original = $conexion->extraer_registro();
		$fila_consulta_vivienda = array();
		if(is_array($fila_consulta_vivienda_original)){
			$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vivienda_original));
	        foreach($it as $v) {
	            $fila_consulta_vivienda[]=$v;
	        }
		}                            
		foreach ($fila_consulta as $aRow) {
			$row = array();
			
			if(in_array($aRow["id_mod"],$fila_consulta_vivienda)){
                $cantidad_eliminar = 1;	
            }
			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" class="fx check_registro" id="'.$aRow["id_mod"].'" name="check" value="'.$aRow["id_mod"].'"></input><label for="'.$aRow["id_mod"].'" name=""><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" class="fx check_registro" id="'.$aRow["id_mod"].'" name="check" value="'.$aRow["id_mod"].'" disabled></input><label for="'.$aRow["id_mod"].'" name=""><span></span></label>';
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "mode.id_mod" || $aColumns[$i] == "est_mod.id_est_mod") {

				}
				else if( $aColumns[$i] == "mode.nombre_mod") {
					$row[] =  utf8_encode($aRow["nombre_mod"]);

					// $servicio = "";
					// $consulta = 
					// 	"
					// 	SELECT
					// 		ser.id_ser,
					// 		ser.nombre_ser
					// 	FROM
					// 		modelo_servicio_modelo as mod_ser,
					// 		servicio_servicio as ser
					// 	WHERE
					// 		mod_ser.id_ser = ser.id_ser AND
					// 		id_mod = '".$aRow["id_mod"]."'
					// 	ORDER BY
					// 		nombre_ser ASC
					// 	";
     //                $conexion->consulta($consulta);
     //                $fila_consulta = $conexion->extraer_registro();
     //                if(is_array($fila_consulta)){
     //                    foreach ($fila_consulta as $fila) {
     //                        $nombre_ser = utf8_encode($fila['nombre_ser']);
     //                        $id_ser = $fila['id_ser'];
     //                        $servicio .= $nombre_ser." - ";
     //                    }
     //                    $servicio = substr($servicio, 0, -2);
     //                }
                    
     //                $row[] =  $servicio;
				}
				else if( $aColumns[$i] == "est_mod.nombre_est_mod") {
					$row[] =  utf8_encode($aRow["nombre_est_mod"]);
				}
				// else if( $aColumns[$i] == "mode.numero_cama_mod") {
				// 	$row[] =  utf8_encode($aRow["numero_cama_mod"]);
				// }
				// else if( $aColumns[$i] == "mode.numero_banio_mod") {
				// 	$row[] =  utf8_encode($aRow["numero_banio_mod"]);
				// }
				else if( $aColumns[$i] == "mode.descripcion_mod") {
					$row[] =  utf8_encode($aRow["descripcion_mod"]);
				}
				else{
					//$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}

			$acciones .= '<button value="'.$aRow["id_mod"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			
			$acciones .= '<button value="'.$aRow["id_mod"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
	        if($aRow["id_est_mod"] == 1){
				$acciones .= '<button value="'.$aRow["id_mod"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
	        }
	        else{
	        	$acciones .= '<button value="'.$aRow["id_mod"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
	        }
		    
			
		 	$row[] = $acciones; 
		 	$acciones = '';                                      
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>