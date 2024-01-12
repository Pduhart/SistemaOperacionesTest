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
	$field = "CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName";
	// $aColumns = array('pro.id_pro',$field,'viv.id_viv','pro.rut_pro','pro.nombre_pro', 'pro.nombre2_pro','pro.apellido_paterno_pro','pro.apellido_materno_pro','pro.fono_pro','pro.direccion_pro','pro.correo_pro','prof.nombre_prof','sex.nombre_sex','-- civ.nombre_civ','reg.descripcion_reg','com.nombre_com','est_pro.nombre_est_pro','pro.id_est_pro');
	$aColumns = array('pro.id_pro',
					  $field,
					  'viv.id_viv',
					  'pro.rut_pro',
					  'pro.nombre_pro', 
					  'pro.nombre2_pro',
					  'pro.apellido_paterno_pro',
					  'pro.apellido_materno_pro',
					  'pro.fono_pro',
					  'pro.direccion_pro',
					  'pro.correo_pro',
					  'canal.nombre_can_cot',
					  'prof.nombre_prof',
					  'sex.nombre_sex',
					  'reg.descripcion_reg',
					  'com.nombre_com',
					  'est_pro.nombre_est_pro',
					  'pro.id_est_pro');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pro.id_pro";
	
	/* DB table to use */
	// $sTable = 
	// 	"
	// 	propietario_propietario AS pro
	// 	INNER JOIN propietario_estado_propietario AS est_pro ON est_pro.id_est_pro = pro.id_est_pro
	// 	LEFT JOIN vendedor_propietario_vendedor AS ven_pro ON ven_pro.id_pro = pro.id_pro
	// 	INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
	// 	INNER JOIN civil_civil AS civ ON civ.id_civ = pro.id_civ
	// 	INNER JOIN sexo_sexo AS sex ON sex.id_sex = pro.id_sex
	// 	INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
	// 	INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
	// 	LEFT JOIN propietario_vivienda_propietario AS viv ON viv.id_pro = pro.id_pro
	// 	";
	$sTable = 
		"
		propietario_propietario AS pro
		INNER JOIN propietario_estado_propietario AS est_pro ON est_pro.id_est_pro = pro.id_est_pro
		LEFT JOIN cotizacion_cotizacion AS cotiza ON cotiza.id_pro = pro.id_pro
		LEFT JOIN cotizacion_canal_cotizacion AS canal ON canal.id_can_cot = cotiza.id_can_cot
		LEFT JOIN vendedor_propietario_vendedor AS ven_pro ON ven_pro.id_pro = pro.id_pro
		INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof		
		INNER JOIN sexo_sexo AS sex ON sex.id_sex = pro.id_sex
		INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
		INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
		LEFT JOIN propietario_vivienda_propietario AS viv ON viv.id_pro = pro.id_pro
		";
	
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) $sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	/*
	 * Ordering
	 */
	
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		/* for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				if($aColumns[ intval( $_GET['iSortCol_'.$i] )-1 ] == $field){
					$sOrder .= " fullName ".$_GET['sSortDir_'.$i].", ";
				}
				else{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] )-1 ]."
				 	".$_GET['sSortDir_'.$i].", ";
				}
				
			}
		} */
		$i = 0;
		$sOrder .= " fullName ".$_GET['sSortDir_'.$i].", ";
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
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$filtro = 1;
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if($aColumns[$i] == "CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName"){
				// $sWhere .= "CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) LIKE '%".utf8_decode($_GET['sSearch'])."%' OR ";

				$words_sep = utf8_decode($_GET['sSearch']);
				$words_sep = explode(" ", $words_sep);
				$cant_palabras = count($words_sep);
				// echo $cant_palabras."<------".$words_sep[0];
			// $sWhere .= "MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('".$words_sep."' IN BOOLEAN MODE) OR ";
				// $sWhere .= "MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('".$words_sep."' IN BOOLEAN MODE) OR ";
				// aquí devide en cada palabra para la consulta y le agrega + a cada una
				$sWhere .= "MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('";
				for ($i=0; $i < $cant_palabras; $i++) { 
					$sWhere .= " +".$words_sep[$i];
				}
				$sWhere .= "' IN BOOLEAN MODE) OR ";
			}
			else{
				$sWhere .= $aColumns[$i]." LIKE '%".utf8_decode($_GET['sSearch'])."%' OR ";
			}
			
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
			if($aColumns[$i] == $field){
				//$sWhere .= "fullName LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
			else{
				$sWhere .= $aColumns[$i]." LIKE '%".$_GET['sSearch_'.$i]."%' ";
			}
			
		}
	}

	if($_SESSION["sesion_perfil_panel"] == 4){
		if($filtro == 1 || $filtro == 2){
			$sWhere .= " AND ven_pro.id_vend = ".$_SESSION["sesion_id_vend"]." ";
		}
		else{
			$sWhere .= " WHERE ven_pro.id_vend = ".$_SESSION["sesion_id_vend"]." ";
			$filtro = 2;
		}
	}


	if (isset($_SESSION["id_sexo_prop_filtro"])) {
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (pro.id_sex = ".$_SESSION["id_sexo_prop_filtro"]." ) ";
		}
		else{
			$sWhere .= "WHERE (pro.id_sex = ".$_SESSION["id_sexo_prop_filtro"]." ) ";
			$filtro = 2;
		}
	}

	if (isset($_SESSION["id_prof_prop_filtro"])) {
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (pro.id_prof = ".$_SESSION["id_prof_prop_filtro"]." ) ";
		}
		else{
			$sWhere .= "WHERE (pro.id_prof = ".$_SESSION["id_prof_prop_filtro"]." ) ";
			$filtro = 2;
		}
	}

	if (isset($_SESSION["id_condepto_prop_filtro"])) {
		if ($_SESSION["id_condepto_prop_filtro"]==1) {
			$filt = "<> ''";
		} else {
			$filt = "IS NULL";
		}
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (viv.id_viv ".$filt." ) ";
		}
		else{
			$sWhere .= "WHERE (viv.id_viv ".$filt." ) ";
			$filtro = 2;
		}
	}

	if (isset($_SESSION["id_reg_prop_filtro"])) {
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (pro.id_reg = ".$_SESSION["id_reg_prop_filtro"]." ) ";
		}
		else{
			$sWhere .= "WHERE (pro.id_reg = ".$_SESSION["id_reg_prop_filtro"]." ) ";
			$filtro = 2;
		}
	}

	if (isset($_SESSION["id_com_prop_filtro"])) {
		if($filtro == 1 || $filtro == 2){
			$sWhere .= "AND (pro.id_com = ".$_SESSION["id_com_prop_filtro"]." ) ";
		}
		else{
			$sWhere .= "WHERE (pro.id_com = ".$_SESSION["id_com_prop_filtro"]." ) ";
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

	// echo $sQuery;
	// $sQuery = "SELECT SQL_CALC_FOUND_ROWS pro.id_pro, CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName, viv.id_viv, pro.rut_pro, pro.nombre_pro, pro.nombre2_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro, pro.fono_pro, pro.direccion_pro, pro.correo_pro, prof.nombre_prof, est_pro.nombre_est_pro, pro.id_est_pro FROM propietario_propietario AS pro INNER JOIN propietario_estado_propietario AS est_pro ON est_pro.id_est_pro = pro.id_est_pro INNER JOIN vendedor_propietario_vendedor AS ven_pro ON ven_pro.id_pro = pro.id_pro INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof LEFT JOIN propietario_vivienda_propietario AS viv ON viv.id_pro = pro.id_pro WHERE (pro.id_pro LIKE '%jara al%' OR MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('jara al' IN BOOLEAN MODE) OR viv.id_viv LIKE '%jara al%' OR pro.rut_pro LIKE '%jara al%' OR pro.nombre_pro LIKE '%jara al%' OR pro.nombre2_pro LIKE '%jara al%' OR pro.apellido_paterno_pro LIKE '%jara al%' OR pro.apellido_materno_pro LIKE '%jara al%' OR pro.fono_pro LIKE '%jara al%' OR pro.direccion_pro LIKE '%jara al%' OR pro.correo_pro LIKE '%jara al%' OR prof.nombre_prof LIKE '%jara al%' OR est_pro.nombre_est_pro LIKE '%jara al%' OR pro.id_est_pro LIKE '%jara al%' ) ORDER BY fullName desc LIMIT 0, 50";

	$conexion->consulta($sQuery);

	$fila_consulta = $conexion->extraer_registro();
	
	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";
	$conexion->consulta($sQuery);
	$fila_consulta2 = $conexion->extraer_registro_unico();	
	$iFilteredTotal = $fila_consulta2[0];
	/* Total data set length */
	$sQuery = "SELECT COUNT(".$sIndexColumn.") AS suma FROM   $sTable";
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

	$consulta = "SELECT id_pro FROM cotizacion_cotizacion";
	$conexion->consulta($consulta);
	$fila_consulta_solicitud_original = $conexion->extraer_registro();
	$fila_consulta_solicitud = array();
	if(is_array($fila_consulta_solicitud_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_solicitud_original));
        foreach($it as $v) {
            $fila_consulta_solicitud[]=$v;
        }
	}

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();
			$cantidad_eliminar = 0;
			if(in_array($aRow["id_pro"],$fila_consulta_solicitud)){
                $cantidad_eliminar = 1;	
            }

			if($_SESSION["sesion_perfil_panel"]==1){
				if($cantidad_eliminar == 0){
					$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pro"].'" class="check_registro" id="'.$aRow["id_pro"].'"><label for="'.$aRow["id_pro"].'"><span></span></label>';
				}
				else{
					$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pro"].'" class="check_registro" id="'.$aRow["id_pro"].'" disabled><label for="'.$aRow["id_pro"].'"><span></span></label>';
				}
			}

			if($_SESSION["sesion_perfil_panel"]==4){
				// $row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pro"].'" class="check_registro_obs" id="'.$aRow["id_pro"].'"><label for="'.$aRow["id_pro"].'"><span></span></label><br><input type="checkbox" name="check_mail" value="'.$aRow["id_pro"].'" class="check_registro_mail" id="mail_'.$aRow["id_pro"].'"><label for="mail_'.$aRow["id_pro"].'"><span></span></label>';
				$row[] = '<input type="checkbox" name="check_mail" value="'.$aRow["id_pro"].'" class="check_registro_mail" id="mail_'.$aRow["id_pro"].'"><label for="mail_'.$aRow["id_pro"].'"><span></span></label>';

			}

			if($_SESSION["sesion_perfil_panel"] <> 4 && $_SESSION["sesion_perfil_panel"] <> 1) {
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_pro"].'" class="check_registro" id="'.$aRow["id_pro"].'" disabled><label for="'.$aRow["id_pro"].'"><span></span></label>';
			}

			// $aColumns = array('pro.id_pro','pro.nombre_pro','pro.rut_pro','pro.fono_pro','pro.correo_pro','est_pro.nombre_est_pro','pro.id_est_pro');
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if($aColumns[$i] == "pro.id_pro" || 
				$aColumns[$i] == "pro.id_est_pro" || 
				$aColumns[$i] == "pro.nombre_pro" || 
				$aColumns[$i] == "pro.nombre2_pro" ||
				$aColumns[$i] == "pro.apellido_paterno_pro" || 
				$aColumns[$i] == "pro.apellido_materno_pro") {
				}else if( $aColumns[$i] == "pro.rut_pro") {
					$row[] =  utf8_encode($aRow["rut_pro"]);
				}else if( $aColumns[$i] == $field) {
					$row[] =  utf8_encode($aRow["fullName"]);
					// $consulta_viv = 
					// 	"
					// 	SELECT
					// 		viv.nombre_viv
					// 	FROM
					// 		propietario_vivienda_propietario AS pro_viv,
					// 		vivienda_vivienda AS viv
					// 	WHERE
					// 		viv.id_est_viv = 2 AND
					// 		pro_viv.id_viv = viv.id_viv AND
					// 		pro_viv.id_pro = ".$aRow["id_pro"]."
					// 	LIMIT 0,1
					// 	";
					// $conexion->consulta($consulta_viv);
					// $fila_viv = $conexion->extraer_registro_unico();
					// $row[] =  utf8_encode($fila_viv['nombre_viv']);
				}else if( $aColumns[$i] == "viv.id_viv") {
					if($aRow["id_viv"]<>'') {
						$consulta_viv = 
						"
						SELECT
							viv.nombre_viv
						FROM
							vivienda_vivienda AS viv
						WHERE
							viv.id_est_viv = 2 AND
							viv.id_viv = ".$aRow["id_viv"]."
						LIMIT 0,1
						";
						$conexion->consulta($consulta_viv);
						$fila_viv = $conexion->extraer_registro_unico();
						$row[] =  utf8_encode($fila_viv['nombre_viv']);
					} else {
						$row[] =  '';
					}
					
				}
				else if( $aColumns[$i] == "pro.fono_pro") {
					$row[] =  utf8_encode($aRow["fono_pro"]);
				}
				else if( $aColumns[$i] == "pro.direccion_pro") {
					$row[] =  utf8_encode($aRow["direccion_pro"]);
				}
				else if( $aColumns[$i] == "pro.correo_pro") {
					$row[] =  utf8_encode($aRow["correo_pro"]);
				}
				else if( $aColumns[$i] == "est_pro.nombre_est_pro") {
					$row[] =  utf8_encode($aRow["nombre_est_pro"]);
				}
				else if( $aColumns[$i] == "prof.nombre_prof") {
					$row[] =  utf8_encode($aRow["nombre_prof"]);
				}
				else if( $aColumns[$i] == "sex.nombre_sex") {
					$row[] =  utf8_encode($aRow["nombre_sex"]);
				}
				// else if( $aColumns[$i] == "civ.nombre_civ") {
				// 	$row[] =  utf8_encode($aRow["nombre_civ"]);
				// }
				else if( $aColumns[$i] == "reg.descripcion_reg") {
					$row[] =  utf8_encode($aRow["descripcion_reg"]);
				}
				else if( $aColumns[$i] == "com.nombre_com") {
					$row[] =  utf8_encode($aRow["nombre_com"]);
				}
				else if( $aColumns[$i] == "canal.nombre_can_cot") {
					$row[] =  utf8_encode($aRow["nombre_can_cot"]);
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			


			
			$acciones = '<button value="'.$aRow["id_pro"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver"><i class="fa fa-search"></i></button>';

	        
	    	if ($_SESSION["sesion_perfil_panel"] != 3 && $_SESSION["sesion_perfil_panel"] != 6) {
				$acciones .= '<button value="'.$aRow["id_pro"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';

		    	if($cantidad_eliminar == 0){
		        	$acciones .= '<button value="'.$aRow["id_pro"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
		    	}
		    	if($aRow["id_est_pro"] == 1){

		        	$acciones .= '<button value="'.$aRow["id_pro"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
		        }else{
		        	$acciones .= '<button value="'.$aRow["id_pro"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
		        }    
	    	}

	    	if ($_SESSION["sesion_perfil_panel"] != 3 && $_SESSION["sesion_perfil_panel"] != 6) {
				$acciones .= '<button value="'.$aRow["id_pro"].'" class="btn btn-sm btn-icon btn-info agrega_observacion" data-toggle="tooltip" data-original-title="Agregar Observación"><i class="fa fa-plus"></i></button>';

				$acciones .= '<button value="'.$aRow["id_pro"].'" class="btn btn-sm btn-icon btn-info agrega_evento" data-toggle="tooltip" data-original-title="Agregar Evento Agenda"><i class="fa fa-calendar-plus-o"></i></button>';
	    	}

	    	$acciones .= '<button value="'.$aRow["id_pro"].'" class="btn btn-sm btn-icon btn-primary agrega_documento" data-toggle="tooltip" data-original-title="Agregar Documento"><i class="fa fa-paperclip" aria-hidden="true"></i></button>';
	        
			
		 	$row[] = $acciones; 
		 	$acciones = '';                                      
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>