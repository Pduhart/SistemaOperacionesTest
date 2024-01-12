<?php
	session_start();
	include '../../class/class_fecha.php';
	require "../../config.php";
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$acciones = "";
	$fecha = new fecha();
	$conexion = new conexion();

	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$field = "CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName";

	$aColumns = array( 'cot.id_cot','con.nombre_con','tor.nombre_tor','modelo.nombre_mod','viv.nombre_viv',$field,'pro.nombre_pro', 'pro.nombre2_pro','pro.apellido_paterno_pro','pro.apellido_materno_pro','pro.rut_pro','pro.fono_pro','pro.correo_pro','pro.id_pro','can_cot.nombre_can_cot','pre_cot.nombre_pre_cot','cot_int_cot.nombre_seg_int_cot','vend.nombre_vend','vend.apellido_paterno_vend','vend.apellido_materno_vend','cot.fecha_cot','est_cot.nombre_est_cot','cot.id_est_cot');
	
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
		INNER JOIN cotizacion_preaprobacion_cotizacion AS pre_cot ON pre_cot.id_pre_cot = cot.id_pre_cot
		LEFT JOIN cotizacion_seguimiento_interes_cotizacion AS cot_int_cot ON cot_int_cot.id_seg_int_cot = cot.id_seg_int_cot
		LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
		";
	
	$sLimit = "";
	if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' ) $sLimit = "LIMIT ".$_GET['start'].", ".$_GET['length'];
   
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['order'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['order'] ) ; $i++ )
		{			
		  if ( intval($_GET['order'][$i]['column']) > 0 ) $sOrder .= $aColumns[ intval(  $_GET['order'][$i]['column'] )-1 ]." ".$_GET['order'][$i]['dir'].", ";							
		}		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" ) $sOrder = "";
	}		
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$filtro = 0;
	$sWhere = "";
	if ( isset($_GET['search']) && $_GET['search'] != "" )
	{

		$filtro = 1;
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if($aColumns[$i] == "CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName"){		 		
				
				$words_sep = $_GET['search']['value'];
				$words_sep = explode(" ", $words_sep);
				$cant_palabras = count($words_sep);				
		 		// aquí devide en cada palabra para la consulta y le agrega + a cada una
				$sWhere .= "MATCH(pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro) AGAINST ('";
				for ($ii=0; $ii < $cant_palabras; $ii++) { 
					// echo $ii.$words_sep[$ii]."-------";
					$sWhere .= " +".$words_sep[$ii];
				}
				$sWhere .= "' IN BOOLEAN MODE) OR ";
			}
			else{
				$sWhere .= $aColumns[$i]." LIKE '%".$_GET['search']['value']."%' OR ";
			}			
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['columns'][$i]['searchable']) && $_GET['columns'][$i]['searchable'] == "true" && $_GET['columns'][$i]['search']['value'] != '' )
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
				$sWhere .= $aColumns[$i]." LIKE '%".(string)$_GET['columns'][$i]['search']['value']."%' ";
			}
		}
	}
	if($_SESSION["sesion_perfil_panel"] == 4){
		if($filtro == 1 || $filtro == 2){
			$sWhere .= " AND vend.id_vend = ".$_SESSION["sesion_id_vend"]." ";
		}
		else{
			$sWhere .= " WHERE vend.id_vend = ".$_SESSION["sesion_id_vend"]." ";
		}
	} 
	// else {
	// 	if($filtro == 1 || $filtro == 2){
	// 		$sWhere .= " AND (YEAR(cot.fecha_cot) = 2021 OR YEAR(cot.fecha_cot) = 2020)";
	// 	}
	// 	else{
	// 		$sWhere .= " WHERE (YEAR(cot.fecha_cot) = 2021 OR YEAR(cot.fecha_cot) = 2020)";
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

	$conexion->consulta($sQuery);
	$fila_consulta = $conexion->extraer_registro();
	
	/* Data set length after filtering */
	$conexion->consulta("SELECT FOUND_ROWS()");
	$fila_consulta2 = $conexion->extraer_registro_unico();
	
	$iFilteredTotal = $fila_consulta2[0];
	
	/* Total data set length */	
	$conexion->consulta(" SELECT COUNT(".$sIndexColumn.") FROM   $sTable");
	$fila_consulta3 = $conexion->extraer_registro_unico();

	$iTotal = $fila_consulta3[0];
	
	/*
	 * Output
	 */
	$output = array(
		"draw" => isset ( $_GET['draw'] ) ? intval( $_GET['draw'] ) : 0,
		"recordsTotal" => $iTotal,
		"recordsFiltered" => $iFilteredTotal,
		"data" => array()
	);	
	$conexion->consulta("SELECT cotizacion_ven FROM venta_venta");
	$fila_consulta_cot_original = $conexion->extraer_registro();
	$fila_consulta_cot = array();
	if(is_array($fila_consulta_cot_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_cot_original));
        foreach($it as $v) {
            $fila_consulta_cot[]=$v;
        }
	}
		
	$conexion->consulta("SELECT id_cot FROM cotizacion_seguimiento_cotizacion");
	$fila_consulta_seguimiento_original = $conexion->extraer_registro();
	$fila_consulta_seguimiento = array();
	if(is_array($fila_consulta_seguimiento_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_seguimiento_original));
        foreach($it as $v) {
            $fila_consulta_seguimiento[]=$v;
        }
	}

	$flipped_cot = array_flip($fila_consulta_cot);
	$flipped_seg = array_flip($fila_consulta_seguimiento);

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();

			$cantidad_eliminar = 0;
			$mostrar_seguimiento = 0;
			// if(in_array($aRow["id_cot"],$fila_consulta_torre)){
   //              $cantidad_eliminar = 1;	
   //          }
   //          if(in_array($aRow["id_cot"],$fila_consulta_seguimiento)){
   //              $mostrar_seguimiento = 1;	
   //          }

            if (isSet($flipped_cot[$aRow["id_cot"]])) $cantidad_eliminar = 1;
			if (isSet($flipped_seg[$aRow["id_cot"]])) $mostrar_seguimiento = 1;
            
			if($cantidad_eliminar == 0 && $aRow["id_est_cot"] < 4){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_cot"].'" class="check_registro" id="'.$aRow["id_cot"].'"><label for="'.$aRow["id_cot"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_cot"].'" class="check_registro" id="'.$aRow["id_cot"].'" disabled><label for="'.$aRow["id_cot"].'"><span></span></label>';
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				if( $aColumns[$i] == "cot.id_est_cot" || $aColumns[$i] == "pro.apellido_paterno_pro" || $aColumns[$i] == "pro.apellido_materno_pro" || $aColumns[$i] == "vend.apellido_paterno_vend" || $aColumns[$i] == "vend.apellido_materno_vend" || $aColumns[$i] == "pro.nombre_pro" || $aColumns[$i] == "pro.nombre2_pro") {
					// if( $aColumns[$i] == "cot.id_est_cot" || $aColumns[$i] == "pro.apellido_paterno_pro" || $aColumns[$i] == "pro.apellido_materno_pro" || $aColumns[$i] == "pro.nombre_pro" || $aColumns[$i] == "pro.nombre2_pro") {
					
				}
				else if( $aColumns[$i] == "cot.id_cot") {
					$row[] =  utf8_encode($aRow["id_cot"]);
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
				else if( $aColumns[$i] == "pro.rut_pro") {
					$row[] =  utf8_encode($aRow["rut_pro"]);
				}
                else if( $aColumns[$i] == "pro.correo_pro") {
					$row[] =  utf8_encode($aRow["correo_pro"]);
				}
                else if( $aColumns[$i] == "pro.fono_pro") {
					$row[] =  utf8_encode($aRow["fono_pro"]);
				}
				else if( $aColumns[$i] == $field) {
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
				}
				else if( $aColumns[$i] == "pro.id_pro") {
					if($_SESSION["sesion_perfil_panel"]==='2') {
						$conexion->consulta( 
							"
							SELECT
								pro.correo_pro,
								pro.fono_pro,
								sex.nombre_sex,
								com.nombre_com,
								prof.nombre_prof
							FROM
								propietario_propietario AS pro,
								sexo_sexo AS sex,
								comuna_comuna AS com,
								profesion_profesion AS prof
							WHERE
								pro.id_sex = sex.id_sex AND
								pro.id_com = com.id_com AND
								pro.id_prof = prof.id_prof AND
								pro.id_pro = ".$aRow["id_pro"]."
							");
						$fila_pro_dat = $conexion->extraer_registro_unico();

						$row[] =  utf8_encode($fila_pro_dat['correo_pro']);
						$row[] =  utf8_encode($fila_pro_dat['fono_pro']);
						$row[] =  utf8_encode($fila_pro_dat['nombre_com']);
						$row[] =  utf8_encode($fila_pro_dat['nombre_prof']);
						$row[] =  utf8_encode($fila_pro_dat['nombre_sex']);
					} else {

					}
				}

				else if( $aColumns[$i] == "can_cot.nombre_can_cot") {
					$row[] =  utf8_encode($aRow["nombre_can_cot"]);
				}
				else if( $aColumns[$i] == "pre_cot.nombre_pre_cot") {
					$row[] =  utf8_encode($aRow["nombre_pre_cot"]);
				}
				else if( $aColumns[$i] == "cot_int_cot.nombre_seg_int_cot") {
					$row[] =  utf8_encode($aRow["nombre_seg_int_cot"]);
				}
				else if( $aColumns[$i] == "vend.nombre_vend") {
					$row[] =  utf8_encode($aRow["nombre_vend"]." ".$aRow["apellido_paterno_vend"]." ".$aRow["apellido_materno_vend"]);
				}
				else if( $aColumns[$i] == "cot.fecha_cot") {
					$row[] =  date("d/m/Y",strtotime($aRow["fecha_cot"]));
				}
				
				else if( $aColumns[$i] == "est_cot.nombre_est_cot") {
					$row[] =  utf8_encode($aRow["nombre_est_cot"]);
				}
				
				
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			if ($mostrar_seguimiento > 0) {
				$acciones = '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Seguimientos"><i class="fa fa-search"></i></button>';
			}
			if($aRow["id_est_cot"] <> 4){
				if ($_SESSION["sesion_perfil_panel"] <> 4) {
					if($aRow["id_est_cot"] < 4){
			        	$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			    	}
			        if($cantidad_eliminar == 0 && $aRow["id_est_cot"] < 4){
			        	$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
			    	}

			    	if($aRow["id_est_cot"] == 1){
						$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Desactivar"><i class="fa fa-minus-square-o""></i></button>';
			        }
			        else if($aRow["id_est_cot"] == 2){
			        	$acciones .= '<button value="'.$aRow["id_cot"].'" type="button" class="btn btn-sm btn-icon btn-default estado" data-toggle="tooltip" data-original-title="Activar"><i class="fa fa-check-square-o"></i></button>';
			        }
			    }

			    $lo_tiene = 0;

			    if ($_SESSION["sesion_perfil_panel"] == 4 ) {
			    // validar si es del vendedor aún
					$conexion->consulta(
							"
							SELECT
								ven_pro.id_pro_vend
							FROM
								cotizacion_cotizacion AS cot,
								vendedor_propietario_vendedor AS ven_pro
							WHERE
								cot.id_pro = ven_pro.id_pro AND
								ven_pro.id_vend = ".$_SESSION["sesion_id_vend"]." AND
								cot.id_cot = ".$aRow["id_cot"]."
							");
					$lo_tiene = $conexion->total();
				}

				if ($lo_tiene>0) {
		        
		        	$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info agrega_seguimiento" data-toggle="tooltip" data-original-title="Agregar Seguimiento"><i class="fa fa-plus"></i></button>';

		        	$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info agrega_evento" data-toggle="tooltip" data-original-title="Agregar Evento Agenda"><i class="fa fa-calendar-plus-o"></i></button>';
		        }

		        $conexion->consulta( 
						"
						SELECT
							ven.id_ven
						FROM
							venta_venta AS ven,
							cotizacion_cotizacion AS cot
						WHERE
							cot.id_viv = ven.id_viv AND
							cot.id_cot = ".$aRow["id_cot"]." AND
							ven.id_est_ven <> 3
						");
				$tiene_promesa = $conexion->total();
				if ($tiene_promesa===0) {
					if ($_SESSION["sesion_perfil_panel"] <> 4 && $aRow["id_est_cot"] < 4) {
			        	$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-success promesa" data-toggle="tooltip" data-original-title="Pasar a Promesa"><i class="fa fa-check"></i></button>';
			        }	
			    }
			}
			else if($aRow["id_est_cot"] < 5 && $aRow["id_est_cot"] <> 3){
				if ($mostrar_seguimiento > 0) {
					$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Seguimientos"><i class="fa fa-search"></i></button>';
				}

				// validar si es del vendedor aún
				if ($_SESSION["sesion_perfil_panel"] == 4) {
					$conexion->consulta( 
							"
							SELECT
								ven_pro.id_pro_vend
							FROM
								cotizacion_cotizacion AS cot,
								vendedor_propietario_vendedor AS ven_pro
							WHERE
								cot.id_pro = ven_pro.id_pro AND
								ven_pro.id_vend = ".$_SESSION["sesion_id_vend"]." AND
								cot.id_cot = ".$aRow["id_cot"]."
							");
					$lo_tiene = $conexion->total();

					if ($lo_tiene>0) {
						$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info agrega_seguimiento" data-toggle="tooltip" data-original-title="Agregar Seguimiento"><i class="fa fa-plus"></i></button>';

						$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-info agrega_evento" data-toggle="tooltip" data-original-title="Agregar Evento Agenda"><i class="fa fa-calendar-plus-o"></i></button>';
					}
				}
			}

			$acciones .= '<button value="'.$aRow["id_cot"].'" class="btn btn-sm btn-icon btn-primary agrega_documento" data-toggle="tooltip" data-original-title="Agregar Documento"><i class="fa fa-paperclip" aria-hidden="true"></i></button>';
	        
			
		 	$row[] = $acciones;
		 	$acciones = "";
			$output['data'][] = $row;
		}
	}	
	echo json_encode( $output );
?>