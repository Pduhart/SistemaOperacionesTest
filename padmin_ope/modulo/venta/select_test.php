<?php
	session_start();
	require "../../config.php";
	include '../../class/class_fecha.php';
	//include '../../class/conexion_tabla.php';
	require '../../class/conexion.php';
	$fecha = new fecha();
	$conexion = new conexion();
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'ven.id_ven','con.nombre_con','tor.nombre_tor','viv.nombre_viv','pro.nombre_pro','pro.apellido_paterno_pro','pro.apellido_materno_pro','pro.rut_pro','pro.correo_pro','pro.fono_pro','vend.nombre_vend','vend.apellido_paterno_vend','vend.apellido_materno_vend','ven.fecha_ven','ven.monto_ven','est_ven.nombre_est_ven','ven.id_est_ven','ven.id_tip_pag','ven.id_ban','ven.id_for_pag','ven.id_pre');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "ven.id_ven";
	
	/* DB table to use */
	$sTable = 
		"
		venta_venta AS ven 
		INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
		INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
		INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
		INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
		INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
		LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
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
			id_ven
		FROM
			venta_desestimiento_venta
		";
	$conexion->consulta($consulta);
	$fila_consulta_desistimiento_original = $conexion->extraer_registro();
	$fila_consulta_desistimiento = array();
	if(is_array($fila_consulta_desistimiento_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_desistimiento_original));
        foreach($it as $v) {
            $fila_consulta_desistimiento[]=$v;
        }
	}

	$consulta = 
		"
		SELECT
			id_ven
		FROM
			pago_pago
		";
	$conexion->consulta($consulta);
	$fila_consulta_detalle_original = $conexion->extraer_registro();
	$fila_consulta_detalle = array();
	if(is_array($fila_consulta_detalle_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_detalle_original));
        foreach($it as $v) {
            $fila_consulta_detalle[]=$v;
        }
	}

	$consulta = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		";
	$conexion->consulta($consulta);
	$fila_consulta_etapa_original = $conexion->extraer_registro();
	$fila_consulta_etapa = array();
	if(is_array($fila_consulta_etapa_original)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original));
        foreach($it as $v) {
            $fila_consulta_etapa[]=$v;
        }
	}

	$consulta_1 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 51 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_1);
	$fila_consulta_etapa_original_1 = $conexion->extraer_registro();
	$fila_consulta_etapa_1 = array();
	if(is_array($fila_consulta_etapa_original_1)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_1));
        foreach($it as $v) {
            $fila_consulta_etapa_1[]=$v;
        }
	}

	$consulta_2 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 23 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_2);
	$fila_consulta_etapa_original_2 = $conexion->extraer_registro();
	$fila_consulta_etapa_2 = array();
	if(is_array($fila_consulta_etapa_original_2)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_2));
        foreach($it as $v) {
            $fila_consulta_etapa_2[]=$v;
        }
	}

	$consulta_3 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 24 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_3);
	$fila_consulta_etapa_original_3 = $conexion->extraer_registro();
	$fila_consulta_etapa_3 = array();
	if(is_array($fila_consulta_etapa_original_3)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_3));
        foreach($it as $v) {
            $fila_consulta_etapa_3[]=$v;
        }
	}

	$consulta_4 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 25 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_4);
	$fila_consulta_etapa_original_4 = $conexion->extraer_registro();
	$fila_consulta_etapa_4 = array();
	if(is_array($fila_consulta_etapa_original_4)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_4));
        foreach($it as $v) {
            $fila_consulta_etapa_4[]=$v;
        }
	}

	$consulta_5 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 26 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_5);
	$fila_consulta_etapa_original_5 = $conexion->extraer_registro();
	$fila_consulta_etapa_5 = array();
	if(is_array($fila_consulta_etapa_original_5)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_5));
        foreach($it as $v) {
            $fila_consulta_etapa_5[]=$v;
        }
	}

	$consulta_6 = 
		"
		SELECT
			id_ven
		FROM
			venta_etapa_venta
		WHERE 
			id_eta = 27 AND
			(id_est_eta_ven = 2 OR id_est_eta_ven = 3)
		";
	$conexion->consulta($consulta_6);
	$fila_consulta_etapa_original_6 = $conexion->extraer_registro();
	$fila_consulta_etapa_6 = array();
	if(is_array($fila_consulta_etapa_original_6)){
		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_etapa_original_6));
        foreach($it as $v) {
            $fila_consulta_etapa_6[]=$v;
        }
	}

	$flipped_tor = array_flip($fila_consulta_torre);
	$flipped_des = array_flip($fila_consulta_desistimiento);
	$flipped_det = array_flip($fila_consulta_detalle);
	$flipped_eta = array_flip($fila_consulta_etapa);
	$flipped_eta_1 = array_flip($fila_consulta_etapa_1);
	$flipped_eta_2 = array_flip($fila_consulta_etapa_2);
	$flipped_eta_3 = array_flip($fila_consulta_etapa_3);
	$flipped_eta_4 = array_flip($fila_consulta_etapa_4);
	$flipped_eta_5 = array_flip($fila_consulta_etapa_5);
	$flipped_eta_6 = array_flip($fila_consulta_etapa_6);

	if(is_array($fila_consulta)) {
		foreach ($fila_consulta as $aRow) {
			$row = array();
			$cantidad_eliminar = 0;
			$mostrar_seguimiento = 0;
			$con_premio = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_desistimiento)){
   //              $cantidad_eliminar = 1;	
   //          }
            if (isSet($flipped_des[$aRow["id_ven"]])) {
			    $cantidad_eliminar = 1;
			}
            // if(in_array($aRow["id_ven"],$fila_consulta_desistimiento)){
            //     $mostrar_seguimiento = 1;	
            // }

            $cantidad_detalle = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_detalle)){
   //              $cantidad_detalle = 1;	
   //          }
            if (isSet($flipped_det[$aRow["id_ven"]])) {
			    $cantidad_detalle = 1;
			}
            $cantidad_etapa = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa)){
   //              $cantidad_etapa = 1;	
   //          }
            if (isSet($flipped_eta[$aRow["id_ven"]])) {
			    $cantidad_etapa = 1;
			}

            $cantidad_etapa_1 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_1)){
   //              $cantidad_etapa_1 = 1;	
   //          }

            if (isSet($flipped_eta_1[$aRow["id_ven"]])) {
			    $cantidad_etapa_1 = 1;
			}

            $cantidad_etapa_2 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_2)){
   //              $cantidad_etapa_2 = 1;	
   //          }

            if (isSet($flipped_eta_2[$aRow["id_ven"]])) {
			    $cantidad_etapa_2 = 1;
			}

            $cantidad_etapa_3 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_3)){
   //              $cantidad_etapa_3 = 1;	
   //          }

            if (isSet($flipped_eta_3[$aRow["id_ven"]])) {
			    $cantidad_etapa_3 = 1;
			}

            $cantidad_etapa_4 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_4)){
   //              $cantidad_etapa_4 = 1;	
   //          }

            if (isSet($flipped_eta_4[$aRow["id_ven"]])) {
			    $cantidad_etapa_4 = 1;
			}

            $cantidad_etapa_5 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_5)){
   //              $cantidad_etapa_5 = 1;	
   //          }

            if (isSet($flipped_eta_5[$aRow["id_ven"]])) {
			    $cantidad_etapa_5 = 1;
			}

            $cantidad_etapa_6 = 0;
			// if(in_array($aRow["id_ven"],$fila_consulta_etapa_6)){
   //              $cantidad_etapa_6 = 1;	
   //          }

            if (isSet($flipped_eta_6[$aRow["id_ven"]])) {
			    $cantidad_etapa_6 = 1;
			}

			
			if($cantidad_eliminar == 0){
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_ven"].'" class="check_registro" id="'.$aRow["id_ven"].'"><label for="'.$aRow["id_ven"].'"><span></span></label>';
			}
			else{
				$row[] = '<input type="checkbox" name="check" value="'.$aRow["id_ven"].'" class="check_registro" id="'.$aRow["id_ven"].'" disabled><label for="'.$aRow["id_ven"].'"><span></span></label>';
			}
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){

				if( $aColumns[$i] == "ven.id_tip_pag" || $aColumns[$i] == "ven.id_ban" || $aColumns[$i] == "ven.id_for_pag" || $aColumns[$i] == "ven.id_est_ven" || $aColumns[$i] == "pro.apellido_paterno_pro" || $aColumns[$i] == "pro.apellido_materno_pro" || $aColumns[$i] == "vend.apellido_paterno_vend" || $aColumns[$i] == "vend.apellido_materno_vend") {
					
				}
				else if( $aColumns[$i] == "ven.id_ven") {
					$row[] =  utf8_encode($aRow["id_ven"]);
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
				else if( $aColumns[$i] == "pro.rut_pro") {
					$row[] =  utf8_encode($aRow["rut_pro"]);
				}
				else if( $aColumns[$i] == "pro.fono_pro") {
					$row[] =  utf8_encode($aRow["fono_pro"]);
				}
				else if( $aColumns[$i] == "pro.correo_pro") {
					$row[] =  utf8_encode($aRow["correo_pro"]);
				}
				else if( $aColumns[$i] == "modelo.nombre_mod") {
					$row[] =  utf8_encode($aRow["nombre_mod"]);
				}
				else if( $aColumns[$i] == "viv.nombre_viv") {
					$row[] =  utf8_encode($aRow["nombre_viv"]);
				}
				else if( $aColumns[$i] == "est_ven.nombre_est_ven") {
					$row[] =  utf8_encode($aRow["nombre_est_ven"]);
				}
				else if( $aColumns[$i] == "ven.fecha_ven") {
					$row[] =  date("d/m/Y",strtotime($aRow["fecha_ven"]));
				}
				else if( $aColumns[$i] == "ven.monto_ven") {
					$row[] = number_format($aRow["monto_ven"], 2, ',', '.');
 				}
				else if( $aColumns[$i] == "vend.nombre_vend") {
					$row[] =  utf8_encode($aRow["nombre_vend"]." ".$aRow["apellido_paterno_vend"]." ".$aRow["apellido_materno_vend"]);
				}
				else if( $aColumns[$i] == "ven.id_pre") {
					// $row[] =  utf8_encode($aRow["nombre_tor"]);
					if ($aRow["id_pre"]<>0) {
						$con_premio = 1;
					}
				}
				else{
					$row[] =  utf8_encode($aRow[ $aColumns[$i] ]);
				}
			}
			if ($_SESSION["sesion_perfil_panel"]<>6) {
				if ($cantidad_etapa > 0) {
					$acciones = '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button>';
				}

				$entro_edita = 0;

				// Si esta en etapa 1 de crédito deja editar el banco
				if ($cantidad_etapa_1 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }

				// Si esta en etapa 2 de crédito deja editar el banco
				if ($cantidad_etapa_2 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }

			    // Si esta en etapa 3 de crédito deja editar el banco
				if ($cantidad_etapa_3 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }

			    // Si esta en etapa 3 de crédito deja editar el banco
				if ($cantidad_etapa_4 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }

			    if ($cantidad_etapa_5 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }

			    if ($cantidad_etapa_6 > 0) {
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }
			    }


				if ($cantidad_etapa == 0) {
					$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button>';
					
					if($aRow["id_est_ven"] == 4 || $aRow["id_est_ven"] == 5){
						$entro_edita = 1;
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			        }

			        if($aRow["id_for_pag"] == 1 && $aRow["id_ban"] > 0){
		    			$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info operacion" data-toggle="tooltip" data-original-title="Pasar a Operación"><i class="fa fa-cog"></i></button>';
		    		}
		    		else if($aRow["id_for_pag"] == 2 && $aRow["id_tip_pag"] > 0){
		    			$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info operacion" data-toggle="tooltip" data-original-title="Pasar a Operación"><i class="fa fa-cog"></i></button>';
	    		}
			        /*if($cantidad_eliminar == 0){
			        	$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-danger eliminar" data-toggle="tooltip" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
			    	}*/			    	
			    	
		    }

		    	//
		    	$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success pago" data-toggle="tooltip" data-original-title="Registrar Pago"><i class="fa fa-usd"></i></button>';

	    		
	    		//

		    	if($aRow["id_est_ven"] == 3){ //desistimiento
		    		$acciones = '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info detalle" data-toggle="tooltip" data-original-title="Ver Detalle"><i class="fa fa-search"></i></button>';
					if ($cantidad_detalle > 0) {
			    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info listado_detalle" data-toggle="tooltip" data-original-title="Listado Detalle"><i class="fa fa-list"></i></button>';
			    	}

		    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success desistimiento_carta" data-toggle="tooltip" data-original-title="Carta Desistimiento"><i"><i class="fa fa-envelope-o"></i></button>';

		    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success finiquito" data-toggle="tooltip" data-original-title="Desistimiento y Finiquito"><i"><i class="fa fa-print"></i></button>';

		    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success resciliacion_contrato" data-toggle="tooltip" data-original-title="Contrato Resciliación"><i"><i class="fa fa-file-text"></i></button>';
		    	}
		    	else{

					if ($cantidad_detalle > 0) {
			    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info listado_detalle" data-toggle="tooltip" data-original-title="Listado Detalle"><i class="fa fa-list"></i></button>';
			    	}

			    	$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success informe_pago" data-toggle="tooltip" data-original-title="Informe de Pagos"><i"><i class="fa fa-print"></i></button>';
			    	
			    	$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success cierre_negocio" data-toggle="tooltip" data-original-title="Carta Cierre Negocio"><i"><i class="fa fa-handshake-o"></i></button>';

			    	$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success entrega_abono" data-toggle="tooltip" data-original-title="Entrega Documentos"><i"><i class="fa fa-exchange"></i></button>';

		    		$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success despacho" data-toggle="tooltip" data-original-title="Despacho Promesa"><i"><i class="fa fa-envelope-open-o"></i></button>';

					$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info carta_oferta" data-toggle="tooltip" data-original-title="Carta Oferta"><i"><i class="fa fa-university"></i></button>';

					$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success marcha" data-toggle="tooltip" data-original-title="Fondo Puesta en Marcha"><i"><i class="fa fa-envelope-o"></i></button>';

					if ($_SESSION["sesion_perfil_panel"]<>3) {
		    			$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-danger desistimiento" data-toggle="tooltip" data-original-title="Ingresar Desistimiento">'.$entro_edita.'<i class="fa fa-thumbs-o-down"></i></button>';
		    		}
		    	}


		    	
		    	if ($con_premio == 1) {
					$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-info premio" data-toggle="tooltip" data-original-title="Certificado Premio"><i"><i class="fa fa-gift"></i></button>';
		    	}
			} else {
				$acciones .= '<button value="'.$aRow["id_ven"].'" class="btn btn-sm btn-icon btn-success informe_pago" data-toggle="tooltip" data-original-title="Informe de Pagos"><i"><i class="fa fa-print"></i></button>';
			}

			if ($_SESSION["sesion_perfil_panel"]==2) {
			    	if($entro_edita == 0){
						$acciones .= '<button value="'.$aRow["id_ven"].'" type="button" class="btn btn-sm btn-icon btn-warning edita" data-toggle="tooltip" data-original-title="Editar"><i class="fa fa-pencil"></i></button>';
			    	}
			    }
	        
			
		 	$row[] = $acciones;
		 	$acciones = "";
			$output['aaData'][] = $row;
		}
	}
	//print_r ($output);
	echo json_encode( $output );
?>