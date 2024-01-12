<?php
	session_start();
    require "../../config.php";
	require '../../class/class_fecha.php';
	$fecha = new fecha();
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$acciones = "";
	$filtro = "";
	$conexion = new conexion();

	date_default_timezone_set('America/Santiago');
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('id_cat_vend','nombre_vend','apellido_paterno_vend','apellido_materno_vend','rut_vend','correo_vend','fono_vend','id_usu','id_vend','id_est_vend');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id_vend";
	
	/* DB table to use */
	$sTable = "vendedor_vendedor";
	
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


	if($filtro == 1 || $filtro == 2){
		$sWhere .= "AND id_est_vend = 1";
	}
	else{
		$sWhere .= "WHERE id_est_vend = 1";
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
		// "sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);

	$consulta = 
		"
		SELECT
			id_vend
		FROM
			cotizacion_cotizacion
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
			if(in_array($aRow["id_vend"],$fila_consulta_torre)){
                $cantidad_eliminar = 1;	
            }

			
			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[8]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'"><label for="'.$aRow[$aColumns[0]].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow[$aColumns[8]].'" class="check_registro" id="'.$aRow[$aColumns[0]].'" disabled><label for="'.$aRow[$aColumns[0]].'" ><span></span></label>';
			}
			
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				
				if( $aColumns[$i] == "nombre_vend") {
									
					$row[] =  utf8_encode($aRow[$aColumns[$i]]." ".$aRow[$aColumns[$i+1]]." ".$aRow[$aColumns[$i+2]]);
				}
				else if( $aColumns[$i] == "apellido_paterno_vend") {

				}
				else if( $aColumns[$i] == "apellido_materno_vend") {

				}
				else if( $aColumns[$i] == "id_cat_vend") {
					$consulta_estado = 
						"
						SELECT
							*
						FROM
							vendedor_categoria_vendedor
						WHERE
							id_cat_vend = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_cat_vend"]);
				}
				else if( $aColumns[$i] == "id_usu") {
					$consulta_estado = 
						"
						SELECT
							usuario_usu,
							contrasena_usu
						FROM
							usuario_usuario
						WHERE
							id_usu = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();
					if ($_SESSION["sesion_perfil_panel"] == 1) {
						// $row[] =  utf8_encode($fila["usuario_usu"]." / ".$fila["contrasena_usu"]);
						$row[] =  utf8_encode($fila["usuario_usu"]." / ".$fila["contrasena_usu"]);
					} else if ($_SESSION["sesion_perfil_panel"] != 3) {
						// $row[] =  utf8_encode($fila["usuario_usu"]." / ".$fila["contrasena_usu"]);
						$row[] =  utf8_encode($fila["usuario_usu"]);
					} else {
						$row[] =  "--";
					}			
				}
				else if( $aColumns[$i] == "id_vend") {
					$row[] =  $aRow[ $aColumns[$i] ];
				}
				else if( $aColumns[$i] == "id_est_vend") {
					$condominio = '';
					$consulta = 
						"
						SELECT
							con.nombre_con
						FROM
							vendedor_condominio_vendedor AS con_ven
							INNER JOIN condominio_condominio AS con  ON con.id_con = con_ven.id_con
						WHERE
							con_ven.id_vend = ".$aRow[$aColumns[8]]."
						";

					$conexion->consulta($consulta);
					$fila_consulta_condominio = $conexion->extraer_registro();	
					if(is_array($fila_consulta_condominio)) {
						foreach ($fila_consulta_condominio as $fila_condominio) {
							$condominio .= $fila_condominio["nombre_con"].' - ';	
						}
					}	
					$condominio = substr($condominio, 0, -3);
					$row[] =  utf8_encode($condominio);


					$consulta_estado = 
						"
						SELECT
							nombre_est_vend
						FROM
							vendedor_estado_vendedor
						WHERE
							id_est_vend = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_est_vend"]);
				}
				else if( $aColumns[$i] == "id_per") {
					$consulta_estado = 
						"
						SELECT
							nombre_per
						FROM
							usuario_perfil
						WHERE
							id_per = ".$aRow[$aColumns[$i]]."
						";
					$conexion->consulta($consulta_estado);
					$fila = $conexion->extraer_registro_unico();				
					$row[] =  utf8_encode($fila["nombre_per"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			if ($_SESSION["sesion_perfil_panel"] != 6 && $_SESSION["sesion_perfil_panel"] != 3) {
	        	$acciones = '<button value="'.$aRow[$aColumns[8]].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
	    	}
	        if ($_SESSION["sesion_perfil_panel"] == 1 || $_SESSION["sesion_perfil_panel"] == 2) {
		        if($cantidad_eliminar == 0){
		        	$acciones .= '<button value="'.$aRow[$aColumns[8]].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
		    	}

		        if($aRow[$aColumns[7]] == 1){

		        	$acciones .= '<button value="'.$aRow[$aColumns[8]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
		        }
		        else{
		        	$acciones .= '<button value="'.$aRow[$aColumns[8]].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
		        }	
		    }


			
		 	$row[] = $acciones;                                       
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>