<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Operación - Listado</title>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
    .container-fluid .content .filtros .form-control {
        display: block;
        width: 100%;
        height: 24px;
        padding: 8px 4px;
        font-size: 12px;
        line-height: 1.3;
        height: 35px;
    }

    .container-fluid .content .input-group .form-control.chico {
        display: block;
        width: 100%;
        /*height: 24px;*/
        padding: 3px 4px;
        font-size: 12px;
        line-height: 1.3;
        height: 24px;
    }

    .container-fluid .content .filtros .form-control.chico {
        display: block;
        width: 100%;
        padding: 3px 4px;
        font-size: 12px;
        line-height: 1.3;
        height: 24px;
    }

    .filtros .input-group-addon {
        padding: 4px 12px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 0px;
    }

    #contenedor_filtro .label {
        display: inline;
        padding: .6em .8em .6em;
        font-size: 80%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
    }

    .bg-grays{
    background-color: #e8f0f5;
    }

    .filtros label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 0px;
        font-weight: 600;
        font-size: 90%;
    }

    h4.titulo_informe{
    margin-top: 0;
    }

    .form-group.filtrar {
        margin-bottom: 0px;
        padding-top: 20px;
    }

    /*.container-fluid .content .form-control {
        display: inline-block;
        width: auto;
    }*/

    ul.etapas{
        padding: 0;
    }

    ul.etapas li{
        list-style: none;
        display: inline-block;
        border-radius: 4px;
        border: 1px solid #cccccc;
        background-color: rgba(255,255,255,.6);
        padding: 3px 6px;
        margin-bottom: 4px;
    }

    ul.etapas li .categoria{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


    button.btn.btn-sm.btn-icon {
        margin: 0px;
        padding: 2px 5px;
    }
    .clase_atraso{
        background-color: #ffd8e8;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #d2d6de;
        border-radius: 0px;
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 26px;
        user-select: none;
        -webkit-user-select: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 21px;
    }
</style>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
include _INCLUDE."class/dias.php";
require_once _INCLUDE."menu_modulo_no_aside.php";
?>
<!-- Full Width Column -->
<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Operaciones - Etapas
                <small>informe</small>
            </h1>
            <ol class="breadcrumb">
                <li> Home </li>
                <li>Operación - Etapas</li>
                <li class="active">informe</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="col-sm-12">
                <!-- general form elements -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <?php
                                $consulta = 
                                    "
                                    SELECT 
                                        usu.id_mod 
                                    FROM 
                                        usuario_usuario_proceso AS usu,
                                        usuario_proceso AS proceso
                                    WHERE 
                                        usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                        usu.id_mod = 1 AND
                                        proceso.opcion_pro = 9 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li><a href="../operacion/operacion_ficha.php">OPERACIONES</a></li>
                                    <?php
                                }
                                $consulta = 
                                    "
                                    SELECT 
                                        usu.id_mod 
                                    FROM 
                                        usuario_usuario_proceso AS usu,
                                        usuario_proceso AS proceso
                                    WHERE 
                                        usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                        usu.id_mod = 1 AND
                                        proceso.opcion_pro = 17 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li class="active"><a href="../informe/operacion_etapa.php">OPERACIONES / ETAPAS</a></li>
                                    <?php
                                }
                                
                                $consulta = 
                                    "
                                    SELECT 
                                        usu.id_mod 
                                    FROM 
                                        usuario_usuario_proceso AS usu,
                                        usuario_proceso AS proceso
                                    WHERE 
                                        usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                        usu.id_mod = 1 AND
                                        proceso.opcion_pro = 3 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li><a href="../informe/operacion_etapa_listado.php">ETAPAS</a></li>
                                    <?php
                                }
                                
                                $consulta = 
                                    "
                                    SELECT 
                                        usu.id_mod 
                                    FROM 
                                        usuario_usuario_proceso AS usu,
                                        usuario_proceso AS proceso
                                    WHERE 
                                        usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                        usu.id_mod = 1 AND
                                        proceso.opcion_pro = 21 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 1
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li><a href="operacion_listado_operacion.php">LISTADO OPERACIONES</a></li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <div class="col-sm-12">
                                        
                                            <div class="col-sm-12 filtros">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="condominio">Condominio:</label>
                                                            <select class="form-control chico" id="condominio" name="condominio"> 
                                                                <option value="">Seleccione Condominio</option>
                                                                <?php  
                                                                $consulta = "SELECT id_con, nombre_con FROM condominio_condominio ORDER BY nombre_con";
                                                                $conexion->consulta($consulta);
                                                                $fila_consulta_condominio_original = $conexion->extraer_registro();
                                                                if(is_array($fila_consulta_condominio_original)){
                                                                    foreach ($fila_consulta_condominio_original as $fila) {
                                                                        ?>
                                                                        <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label for="torre">Torre:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico" id="torre" name="torre"> 
                                                                <option value="">Seleccione Torre</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label for="departamento">Departamento:</label>
                                                              <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                            <select class="form-control chico select2" id="departamento" name="departamento"> 
                                                                <option value="">Seleccione Departamento</option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 text-center">
                                                <div class="form-group filtrar">
                                                    <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12" id="contenedor_filtro">
                                                <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
                                                <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                                                  <i>Filtro: 
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $elimina_filtro = 0;
                                                    
                                                    if(isset($_SESSION["sesion_filtro_departamento_panel"])){
                                                        $porciones = explode("-", $_SESSION["sesion_filtro_departamento_panel"]);
                                                        $id_vivienda = $porciones[0];
                                                        $id_venta = $porciones[1];
                                                        $elimina_filtro = 1;
                                                        $consulta = 
                                                            "
                                                            SELECT
                                                                venta.id_ven,
                                                                viv.nombre_viv,
                                                                viv.id_viv,
                                                                CONCAT(pro.nombre_pro, ' ', pro.apellido_paterno_pro, ' ', pro.apellido_materno_pro) As cliente
                                                            FROM 
                                                                venta_venta AS venta
                                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = venta.id_viv
                                                                INNER JOIN propietario_propietario AS pro ON venta.id_pro = pro.id_pro
                                                            WHERE
                                                                venta.id_ven = ?
                                                            ";

                                                        $conexion->consulta_form($consulta,array($id_venta));
                                                        $fila_consulta_departamento = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta_departamento)){
                                                            foreach ($fila_consulta_departamento as $fila) {
                                                                // $texto_filtro = $fila['nombre_viv']." ( Venta: ".$fila['id_ven'].")";
                                                                $texto_filtro = $fila['nombre_viv']." ( Venta: ".$fila['id_ven']." - Cliente: ".$fila['cliente'].")";
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        <span class="label label-primary" style="font-size: 1.3rem"><?php echo utf8_encode($texto_filtro);?></span>
                                                        <?php
                                                        $filtro_consulta .= " AND viv.id_viv = ".$id_vivienda;
                                                    }
                                                    else{
                                                        ?>
                                                        <span class="label label-default">Sin filtro</span>
                                                        <?php       
                                                    }

                                                    
                                                
                                                    if ($elimina_filtro<>0) {
                                                      ?>
                                                      <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i> 
                                                      <?php
                                                    }

                                                    ?>
                                                    
                                                </i>
                                              </h6>
                                            </div>
                                        </div>
                                        <?php  
                                        if(isset($_SESSION["sesion_filtro_departamento_panel"])){
                                            $consulta = 
                                                "
                                                SELECT
                                                    ven.id_pre,
                                                    ven.id_ven,
                                                    ven.monto_estacionamiento_ven,
                                                    ven.monto_bodega_ven,
                                                    ven.monto_vivienda_ingreso_ven,
                                                    ven.monto_ven,

                                                    con.nombre_con,
                                                    tor.nombre_tor,
                                                    viv.nombre_viv,
                                                    pro.rut_pro,
                                                    pro.correo_pro,
                                                    pro.fono_pro,
                                                    pro.nombre_pro,
                                                    pro.apellido_paterno_pro,
                                                    pro.apellido_materno_pro,
                                                    vend.rut_vend,
                                                    vend.nombre_vend,
                                                    vend.apellido_paterno_vend,
                                                    vend.apellido_materno_vend,
                                                    vend.fono_vend,
                                                    ven.fecha_ven,
                                                    ven.monto_ven,
                                                    est_ven.nombre_est_ven,
                                                    ven.id_est_ven,
                                                    ven.id_for_pag,
                                                    ven.id_ban
                                                FROM
                                                    venta_venta AS ven
                                                INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
                                                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
                                                LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                WHERE
                                                    ven.id_est_ven <> 3
                                                    ".$filtro_consulta."
                                                ";
                                            $conexion->consulta($consulta);
                                            $fila = $conexion->extraer_registro_unico();
                                            $id_ven = $fila["id_ven"];
											if ($fila["id_for_pag"]==1) {
												$forma_pago = "Crédito";

												$consulta_banco = 
												  "
												  SELECT 
												    nombre_ban
												  FROM
												    banco_banco
												  WHERE
												  	id_ban = ".$fila["id_ban"]."
												  ";
												$conexion->consulta($consulta_banco);
												$filaban = $conexion->extraer_registro_unico();
												$nombre_ban = $filaban["nombre_ban"];
											} else {
												$forma_pago = "Contado";
											}
                                            ?>
                                            <h3>Información de Venta</h3>
                                            <table class="table table-condensed" style="font-size: 16px;">
                                                <tbody>
                                                    <tr>
                                                        <th class="cabecera_tabla active" colspan="2">CLIENTE</th>
                                                        <th class="cabecera_tabla active" colspan="2">VENDEDOR</th>
                                                        <th class="cabecera_tabla active" colspan="2">DEPTO</th>
                                                        <th class="cabecera_tabla active" colspan="2">VENTA - ID: <?php echo $id_ven; ?></th>
                                                    </tr>
                                                    <tr>
                                                        
                                                        <th class="cabecera_tabla active">Rut</th>
                                                        <td><?php echo utf8_encode($fila['rut_pro']);?></td>
                                                        <th class="cabecera_tabla active">Rut</th>
                                                        <td><?php echo utf8_encode($fila['rut_vend']);?></td>
                                                        <th class="cabecera_tabla active">Condominio</th>
                                                        <td><?php echo utf8_encode($fila['nombre_con']);?></td>
                                                        <th class="cabecera_tabla active">Fecha</th>
                                                        <td><?php echo date("d/m/Y",strtotime($fila["fecha_ven"]));?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="cabecera_tabla active">Nombre</th>
                                                        <td><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></td>
                                                        <th class="cabecera_tabla active">Nombre</th>
                                                        <td><?php echo utf8_encode($fila['nombre_vend']." ".$fila['apellido_paterno_vend']." ".$fila['apellido_materno_vend']);?></td>
                                                        <th class="cabecera_tabla active">Torre</th>
                                                        <td><?php echo utf8_encode($fila['nombre_tor']);?></td>
                                                        <th class="cabecera_tabla active">Estado</th>
                                                        <td><?php echo utf8_encode($fila['nombre_est_ven']);?></td>
                                                    </tr>
                                                    <tr>
                                                        
                                                        <th class="cabecera_tabla active">Fono</th>
                                                        <td><?php echo utf8_encode($fila['fono_pro']);?></td>
                                                        <th class="cabecera_tabla active">Rut</th>
                                                        <td><?php echo utf8_encode($fila['fono_vend']);?></td>
                                                        <th class="cabecera_tabla active">Depto</th>
                                                        <td><?php echo utf8_encode($fila['nombre_viv']);?></td>
                                                        <th class="cabecera_tabla active">Monto</th>
                                                        <td><?php echo number_format($fila["monto_ven"], 2, ',', '.');?></td>
                                                    </tr>
                                                    <tr>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th class="cabecera_tabla active">Forma Pago</th>
                                                    	<td><?php echo $forma_pago; ?></td>
                                                    </tr>
                                                    <tr>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th></th>
                                                    	<td></td>
                                                    	<th class="cabecera_tabla active">Banco</th>
                                                    	<td><?php echo utf8_encode($nombre_ban); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h3 style="margin-top: 60px;">Información de Etapas</h3>
                                            <table class="table table-condensed" style="font-size: 16px;">
                                                <thead>
                                                    <tr>
                                                        <th>Etapa</th>
                                                        <th>F. Inicio</th>
                                                        <th>F. Fin</th>
                                                        <th>Atrasada</th>
                                                        <th>Estado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $hoy = date("Y-m-d");
                                                    $consulta = 
                                                        "
                                                        SELECT 
                                                            eta_ven.id_eta_ven,
                                                            eta_ven.id_eta,
                                                            eta.nombre_eta,
                                                            eta.alias_eta,
                                                            eta.duracion_eta,
                                                            eta_ven.fecha_desde_eta_ven,
                                                            eta_ven.fecha_hasta_eta_ven,
                                                            est_eta_ven.id_est_eta_ven,
                                                            est_eta_ven.nombre_est_eta_ven
                                                        FROM
                                                            venta_etapa_venta AS eta_ven
                                                            INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
                                                            INNER JOIN venta_estado_etapa_venta AS est_eta_ven ON est_eta_ven.id_est_eta_ven = eta_ven.id_est_eta_ven
                                                        WHERE 
                                                            eta_ven.id_ven = ?
                                                        ORDER BY
                                                            eta.numero_real_eta
                                                        ";
                                                    $contador = 1;
                                                    $conexion->consulta_form($consulta,array($id_ven));
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        
                                                        foreach ($fila_consulta as $fila) {
                                                            $atrasada = 0;
                                                            $dias = 0;
                                                            $id_eta_ven = $fila["id_eta_ven"];
                                                            $id_eta = $fila["id_eta"];
                                                            if ($fila["fecha_desde_eta_ven"] == "0000-00-00 00:00:00" || $fila["fecha_desde_eta_ven"] == null) {
                                                                $fecha_desde_mostrar = "-";
                                                            }
                                                            else{
                                                                $fecha_desde_mostrar = date("d/m/Y",strtotime($fila["fecha_desde_eta_ven"]));
                                                                $fecha_desde_formato = date("Y-m-d",strtotime($fila["fecha_desde_eta_ven"]));
                                                            }
                                                            if ($fila["fecha_hasta_eta_ven"] == "0000-00-00 00:00:00" || $fila["fecha_hasta_eta_ven"] == NULL) {
                                                                $fecha_hasta_mostrar = "-";
                                                            }
                                                            else{
                                                                $fecha_hasta_mostrar = date("d/m/Y",strtotime($fila["fecha_hasta_eta_ven"]));
                                                                $fecha_hasta_formato = date("Y-m-d",strtotime($fila["fecha_hasta_eta_ven"]));
                                                            }
                                                            if($fila["id_est_eta_ven"] == 1){
                                                                
                                                                $duracion_eta = $fila["duracion_eta"];
                                                                // $fecha_limite = date("Y-m-d", strtotime("$fecha_desde_formato + $duracion_eta days"));
																$dias_reales = number_of_working_days($fecha_desde_formato,$fecha_hasta_formato);

                                                                // $fecha_limite = add_business_days($fecha_desde_formato,$duracion_eta,$holidays,'Y-m-d');

                                                                if($dias_reales > $duracion_eta){
                                                                    $atrasada = 1;
                                                                    // $dias = (strtotime($fecha_hasta_formato)-strtotime($fecha_limite))/86400;
                                                                    // $dias = abs($dias); 
                                                                    // $dias = floor($dias);

                                                                    $dias = $dias_reales-$duracion_eta;
                                                                }
                                                            }
                                                            else if($fila["id_est_eta_ven"] == 2){
                                                                $duracion_eta = $fila["duracion_eta"];
                                                                // $fecha_limite = date("Y-m-d", strtotime("$fecha_desde_formato + $duracion_eta days"));
                                                                $fecha_limite = add_business_days($fecha_desde_formato,$duracion_eta,$holidays,'Y-m-d');

                                                                if($hoy > $fecha_limite){
                                                                    $atrasada = 1;
                                                                    // $dias = (strtotime($hoy)-strtotime($fecha_limite))/86400;
                                                                    // $dias = abs($dias); 
                                                                    // $dias = floor($dias);
                                                                    $dias = getWorkingDays($fecha_limite,$hoy,$holidays);
                                                                }
                                                            }

                                                            if($atrasada == 1){
                                                                $clase_atraso = "clase_atraso";
                                                            }
                                                            else{
                                                                $clase_atraso = "";
                                                            }
                                                            ?>
                                                            <tr class="<?php echo $clase_atraso;?>">
                                                                <td><?php echo utf8_encode($fila["nombre_eta"]." - ".$fila["alias_eta"]);?></td>
                                                                <td><?php echo $fecha_desde_mostrar;?></td>
                                                                <td><?php echo $fecha_hasta_mostrar;?></td>
                                                                <td><?php echo $dias;?></td>
                                                                <td><?php echo utf8_encode($fila["nombre_est_eta_ven"]);?></td>
                                                                <td>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            a.id_eta_doc_ven
                                                                        FROM 
                                                                            venta_etapa_documento_venta AS a
                                                                        WHERE   
                                                                            a.id_eta_ven = ".$id_eta_ven."
                                                                        ";
                                                                    $conexion->consulta($consulta);
                                                                    $cantidad_documento = $conexion->total();


                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            a.id_obs_eta_ven
                                                                        FROM 
                                                                            venta_observacion_etapa_venta AS a
                                                                        WHERE   
                                                                            a.id_eta = ".$id_eta." AND
                                                                            a.id_ven = ".$id_ven."
                                                                        ";
                                                                    $conexion->consulta($consulta);
                                                                    $cantidad_observacion = $conexion->total();
                                                                    if ($cantidad_documento > 0 || $cantidad_observacion > 0) {
                                                                        ?>
                                                                        <button value="<?php echo $id_ven;?>" data-valor="<?php echo $id_eta;?>" data-doc="<?php echo $id_eta_ven;?>" class="btn btn-sm btn-icon btn-info observacion" data-toggle="tooltip" data-original-title="Observaciones y Documentos"><i class="fa fa-search"></i></button>
                                                                        <?php
                                                                    }
                                                                    
                                                                    ?>
                                                                </td>
                                                                
                                                            </tr>

                                                            <?php
                                                            $contador++;
                                                        }
                                                    }
                                                    ?>
                                                    
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<?php include_once _INCLUDE."js_comun.php";?>
<!-- .wrapper cierra en el footer -->
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.numero').numeric();

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        

        $(document).on( "click",".observacion" , function() {
            id_ven = $(this).val();
            var_id_eta = $(this).attr("data-valor");
            var_id_eta_ven = $(this).attr("data-doc");
            $.ajax({
                type: 'POST',
                url: ("form_detalle_observacion_etapa.php"),
                data:"id_ven="+id_ven+"&id_eta="+var_id_eta+"&id_eta_ven="+var_id_eta_ven,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });

        $(document).on( "click","#filtro" , function() {
            var_departamento = $('#departamento').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"departamento="+var_departamento,
                success: function(data) {
                    location.reload();
                }
            })
        });


        $(document).on( "change","#condominio" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_condominio.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#torre').html(data);
                    }
                })
            }
        });

        $(document).on( "change","#torre" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_torre_operacion.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#departamento').html(data);
                    }
                })
            }
        });


        

        $(document).on( "click",".borra_sesion" , function() {
            // $('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            $.ajax({
                type: 'POST',
                url: ("filtro_delete.php"),
                // data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&estado="+var_estado+"&vehiculo="+var_vehiculo,
                success: function(data) {
                    location.reload();
                }
            })
        });
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });
        
        
        
    });
</script>
</body>
</html>
