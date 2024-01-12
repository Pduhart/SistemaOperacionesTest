<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";
?>
<title>Cliente - Ficha</title>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
.container-fluid .content .form-control {
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
.cabecera_tabla{
    width: 11%;
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
    padding-top: 15px;
}
</style>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
 	<!-- Modal -->
   	<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    </div>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Cliente
          <small>informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Cliente</li>
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
                                    proceso.opcion_pro = 1 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="../informe/operacion_listado.php">LISTADO VENTAS</a></li>
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
                                    proceso.opcion_pro = 14 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="condominio_disponibilidad_listado.php">DISPONIBILIDAD</a></li>
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
                                    proceso.opcion_pro = 4 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_velocidad_listado.php">VELOCIDAD DE VENTAS</a></li>
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
                                    proceso.opcion_pro = 15 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_recuperacion_listado.php">RECUPERACIÓN</a></li>
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
                                    proceso.opcion_pro = 8 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_estadistica_venta.php">ANÁLISIS DE VENTAS</a></li>
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
                                    proceso.opcion_pro = 10 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_cotizacion_venta.php">LISTADO COTIZACIONES</a></li>
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
                                    proceso.opcion_pro = 11 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li class="active"><a href="venta_ficha_cliente_venta.php">FICHA DE CLIENTE</a></li>
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
                                    proceso.opcion_pro = 12 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_pago_venta.php">LISTADO PAGOS</a></li>
                                <?php
                            }
                            // alzamiento
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
                                    proceso.opcion_pro = 16 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_alzamiento_listado.php">ALZAMIENTO</a></li>
                                <?php
                            }
                            // fondo explotación
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
                                    proceso.opcion_pro = 18 AND
                                    proceso.id_pro = usu.id_pro AND
                                    proceso.id_mod = 1
                                ";
                            $conexion->consulta($consulta);
                            $cantidad_opcion = $conexion->total();
                            if($cantidad_opcion > 0){
                                ?>
                                <li><a href="venta_fondo_listado.php">FONDO EXPLOTACIÓN</a></li>
                                <?php
                            }
                            ?>
                            
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body" style="padding-top: 0">
                                    <div class="row">
                                        <div id="contenedor_opcion"></div>
                                        <div class="col-sm-6 filtros">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="fecha_desde">Buscador Cliente:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="busqueda" id="busqueda">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-sm-2 text-center">
                                                  <div class="form-group filtrar">
                                                      <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
                                                  </div>
                                                </div> -->
                                            </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div id="resultado" class="text-center"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if(isset($_SESSION["id_cliente_filtro_panel"])){
                                                ?>
                                                <div class="col-sm-5">
                                                    <?php
                                                
                                                    ?>
                                                    <h3 class="panel-title"><i class="icon fa fa-users"></i>
                                                    <?php
                                                    $consulta = 
                                                    "
                                                    SELECT 
                                                        nombre_pro,
                                                        apellido_paterno_pro,
                                                        apellido_materno_pro
                                                    FROM 
                                                        propietario_propietario
                                                    WHERE 
                                                        id_pro = '".$_SESSION["id_cliente_filtro_panel"]."'
                                                    ";
                                                    $conexion->consulta($consulta);
                                                    $fila = $conexion->extraer_registro_unico();

                                                    ?>
                                                    Cliente Seleccionado: <span style="font-size: 16px;" class="label label-default"><?php echo utf8_encode($fila["nombre_pro"]." ".$fila["apellido_paterno_pro"]." ".$fila["apellido_materno_pro"]);?></span>
                                                    &nbsp;&nbsp;<button style="font-size: 10px;padding: 3px 5px;" type="button" class="btn btn-danger input-search-close icon fa fa-times eliminar_sesion" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Cerrar sesión de la familia"></button>
                                                    </h3>
                                                
                                                </div>
                                            <?php
                                            }
                                            ?> 
                                        </div>
                                        
                                        <?php 
                                        if(isset($_SESSION["id_cliente_filtro_panel"])){
                                        ?>
                                        <div class="col-md-12">
                                            <div class="row contenedor_tabla" id="contenedor_tabla">
                                                
                                                <div class="col-sm-12" style="margin-top: 10px;">
                                                    <div class="box box-primary">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">INFORMACIÓN</h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <?php
                                                            $consulta = 
                                                                "
                                                                SELECT 
                                                                    pro.id_pro,
                                                                    pro.nombre_pro,
                                                                    pro.nombre2_pro,
                                                                    pro.apellido_paterno_pro,
                                                                    pro.apellido_materno_pro,
                                                                    pro.rut_pro,
                                                                    pro.pasaporte_pro,
                                                                    pro.fono_pro,
                                                                    pro.fono2_pro,
                                                                    pro.direccion_pro,
                                                                    pro.direccion_trabajo_pro,
                                                                    pro.correo_pro,
                                                                    pro.correo2_pro,
                                                                    nac.id_nac,
                                                                    nac.nombre_nac,
                                                                    com.id_com,
                                                                    com.nombre_com,
                                                                    sex.id_sex,
                                                                    sex.nombre_sex,
                                                                    civ.id_civ,
                                                                    civ.nombre_civ,
                                                                    est.id_est,
                                                                    est.nombre_est,
                                                                    prof.id_prof,
                                                                    prof.nombre_prof
                                                                FROM 
                                                                    propietario_propietario AS pro
                                                                    INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                                                                    INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                                                                    INNER JOIN sexo_sexo  AS sex ON sex.id_sex = pro.id_sex
                                                                    INNER JOIN civil_civil  AS civ ON civ.id_civ = pro.id_civ
                                                                    INNER JOIN estudio_estudio  AS est ON est.id_est = pro.id_est
                                                                    LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
                                                                WHERE 
                                                                    pro.id_pro = ?
                                                                ";

                                                            $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                            $fila = $conexion->extraer_registro_unico();
                                                            ?>
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="cabecera_tabla active">Rut</th>
                                                                        <td><?php echo utf8_encode($fila['rut_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Fono</th>
                                                                        <td><?php echo utf8_encode($fila['fono_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Pasaporte</th>
                                                                        <td><?php echo utf8_encode($fila['pasaporte_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Sexo</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_sex']);?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="cabecera_tabla active">Primer Nombre</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Fono 2</th>
                                                                        <td><?php echo utf8_encode($fila['fono2_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Dirección Trabajo</th>
                                                                        <td><?php echo utf8_encode($fila['direccion_trabajo_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Comuna</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_com']);?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="cabecera_tabla active">Segundo Nombre</th>
                                                                        <td><?php echo utf8_encode($fila['nombre2_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Correo</th>
                                                                        <td><?php echo utf8_encode($fila['correo_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Estado Civil</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_civ']);?></td>

                                                                        <th class="cabecera_tabla active">Nacionalidad</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_nac']);?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="cabecera_tabla active">Apellido Paterno</th>
                                                                        <td><?php echo utf8_encode($fila['apellido_paterno_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Correo 2</th>
                                                                        <td><?php echo utf8_encode($fila['correo2_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Estudios</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_est']);?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="cabecera_tabla active">Apelllido Materno</th>
                                                                        <td><?php echo utf8_encode($fila['apellido_materno_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Dirección</th>
                                                                        <td><?php echo utf8_encode($fila['direccion_pro']);?></td>

                                                                        <th class="cabecera_tabla active">Profesión</th>
                                                                        <td><?php echo utf8_encode($fila['nombre_prof']);?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>  

                                                        </div>
                                                    </div>
                                                </div>
                                                

                                                <div class="col-sm-12" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Departamentos <i class="fa fa-building"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>N°</th>
                                                                        <th>Depto</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Modelo</th>
                                                                        <th>Orientación</th>
                                                                        <th>Mt Totales</th>
                                                                        <th>Mt Terraza</th>
                                                                        <th>Estado</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            viv.nombre_viv,
                                                                            con.nombre_con,
                                                                            tor.nombre_tor,
                                                                            modelo.nombre_mod,
                                                                            ori_viv.nombre_ori_viv,
                                                                            viv.metro_viv,
                                                                            viv.metro_terraza_viv,
                                                                            viv.valor_viv,
                                                                            est_viv.nombre_est_viv,
                                                                            viv.bono_viv
                                                                        FROM 
                                                                            propietario_vivienda_propietario AS pro
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                            INNER JOIN vivienda_estado_vivienda AS est_viv ON est_viv.id_est_viv = viv.id_est_viv
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = viv.id_mod
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                            INNER JOIN vivienda_orientacion_vivienda AS ori_viv ON ori_viv.id_ori_viv = viv.id_ori_viv
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $contador;?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_mod"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_ori_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["metro_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["metro_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["metro_terraza_viv"]);?></td>
                                                                                <td><?php echo number_format($fila["valor_viv"], 2, ',', '.');?> UF</td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Estacionamientos <i class="fa fa-car"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>N°</th>
                                                                        <th>Nombre</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Depto</th>
                                                                        <th>Estado</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            estac.nombre_esta,
                                                                            estac.valor_esta,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            est_esta.nombre_est_esta
                                                                        FROM 
                                                                            propietario_vivienda_propietario AS pro
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                            INNER JOIN estacionamiento_estacionamiento AS estac ON estac.id_viv = viv.id_viv
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = estac.id_con
                                                                            LEFT JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN estacionamiento_estado_estacionamiento AS est_esta ON est_esta.id_est_esta = estac.id_est_esta
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    $total_estacionamiento = $conexion->total();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $contador;?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_esta"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_esta"]);?></td>
                                                                                <td><?php echo number_format($fila["valor_esta"], 2, ',', '.');?> UF</td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Bodegas <i class="fa fa-cubes"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>N°</th>
                                                                        <th>Nombre</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Depto</th>
                                                                        <th>Estado</th>
                                                                        <th>Valor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            bod.nombre_bod,
                                                                            bod.valor_bod,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            con.id_con,
                                                                            con.nombre_con,
                                                                            est_bod.nombre_est_bod
                                                                        FROM 
                                                                            propietario_vivienda_propietario AS pro
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = pro.id_viv
                                                                            INNER JOIN bodega_bodega AS bod ON bod.id_viv = viv.id_viv
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = bod.id_con
                                                                            LEFT JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN bodega_estado_bodega AS est_bod ON est_bod.id_est_bod = bod.id_est_bod
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    $total_estacionamiento = $conexion->total();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $contador;?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_bod"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_bod"]);?></td>
                                                                                <td><?php echo number_format($fila["valor_bod"], 2, ',', '.');?> UF</td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Cotizaciones <i class="fa fa-check-square-o"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Modelo</th>
                                                                        <th>Depto</th>
                                                                        <th>Canal</th>
                                                                        <th>Vendedor</th>
                                                                        <th>Fecha</th>
                                                                        <th>Seguimientos</th>
                                                                        <th>Estado</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            con.nombre_con,
                                                                            con.id_con,
                                                                            viv.id_viv,
                                                                            viv.nombre_viv,
                                                                            can_cot.id_can_cot,
                                                                            can_cot.nombre_can_cot,
                                                                            tor.id_tor,
                                                                            tor.nombre_tor,
                                                                            mode.id_mod,
                                                                            mode.nombre_mod,
                                                                            pro.rut_pro,
                                                                            pro.id_pro,
                                                                            pro.nombre_pro,
                                                                            pro.nombre2_pro,
                                                                            pro.apellido_paterno_pro,
                                                                            pro.apellido_materno_pro,
                                                                            pro.rut_pro,
                                                                            pro.correo_pro,
                                                                            pro.fono_pro,
                                                                            cot.id_cot,
                                                                            cot.fecha_cot,
                                                                            vend.nombre_vend,
                                                                            vend.apellido_paterno_vend,
                                                                            est_cot.nombre_est_cot
                                                                        FROM 
                                                                            cotizacion_cotizacion AS cot 
                                                                            INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                                                                            INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                                                                            INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                                                                            INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                                                                            LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $fila["id_cot"];?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_mod"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_can_cot"]);?></td>

                                                                                <td><?php echo utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);?></td>
                                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_cot"]));?></td>
                                                                                <?php 
																				$consulta = "SELECT id_seg_cot FROM cotizacion_seguimiento_cotizacion WHERE id_cot = ".$fila['id_cot']."";
	                                                                        	$conexion->consulta($consulta);
	                                                                        	$cant_seg = $conexion->total();
	                                                                             ?>
	                                                                            <td><?php echo $cant_seg; ?>
	                                                                            	<?php 
																					if ($cant_seg > 0) {
																						?>
																						&nbsp;&nbsp;<button value="<?php echo $fila['id_cot']; ?>" class="btn btn-xs btn-info detalle" data-toggle="tooltip" data-original-title="Ver Seguimientos"><i class="fa fa-search"></i></button>
																						<?php
																					}
	                                                                            	?>
	                                                                            </td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_cot"]);?></td>
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Ventas <i class="fa fa-check-square-o"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th>Condominio</th>
                                                                        <th>Torre</th>
                                                                        <th>Depto</th>
                                                                        <th>Vendedor</th>
                                                                        <th>Fecha</th>
                                                                        <th>Total</th>
                                                                        <th>F. Pago</th>
                                                                        <th>N° Bien Inscrito</th>
                                                                        <th>Estado</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            ven.id_ven,
                                                                            con.nombre_con,
                                                                            tor.nombre_tor,
                                                                            viv.nombre_viv,
                                                                            pro.nombre_pro,
                                                                            pro.apellido_paterno_pro,
                                                                            pro.apellido_materno_pro,
                                                                            vend.nombre_vend,
                                                                            vend.apellido_paterno_vend,
                                                                            vend.apellido_materno_vend,
                                                                            ven.fecha_ven,
                                                                            ven.monto_ven,
                                                                            est_ven.nombre_est_ven,
                                                                            ven.numero_compra_ven,
                                                                            for_pag.nombre_for_pag,
                                                                            ven.id_est_ven
                                                                        FROM 
                                                                            venta_venta AS ven 
                                                                            INNER JOIN venta_estado_venta AS est_ven ON est_ven.id_est_ven = ven.id_est_ven
                                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                                            INNER JOIN propietario_propietario AS pro ON ven.id_pro = pro.id_pro
                                                                            LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = ven.id_vend
                                                                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = ven.id_for_pag
                                                                        WHERE 
                                                                            pro.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $fila["id_ven"];?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_con"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_tor"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_viv"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_vend"]." ".$fila["apellido_paterno_vend"]);?></td>
                                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_ven"]));?></td>
                                                                                <td><?php echo number_format($fila["monto_ven"], 2, ',', '.');?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["numero_compra_ven"]);?></td>
                                                                                <td><?php 
                                                                                echo utf8_encode($fila["nombre_est_ven"]);
                                                                                if ($fila["id_est_ven"]==3) {
                                                                                	$consulta_des = 
																                        "
																                        SELECT
																                            tip_des.nombre_tip_des
																                        FROM
																                            venta_desestimiento_venta AS des,
																                            desistimiento_tipo_desistimiento AS tip_des
																                        WHERE
																                            des.id_ven = " .$fila["id_ven"]. " AND 
																                            des.id_tip_des = tip_des.id_tip_des
																                        ";
																                    $conexion->consulta($consulta_des);
																                    $fila_des = $conexion->extraer_registro_unico();
																                    echo "<br><font style='font-size:11px'>".utf8_encode($fila_des["nombre_tip_des"])."</font>";
                                                                                }

                                                                                ?></td>
                                                                                
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" style="margin-top: 30px;">
                                                    <div class="box box-default">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Pagos <i class="fa fa-dollar"></i></h3>
                                                        </div>
                                                        <div class="box-body no-padding">
                                                            <table class="table table-condensed" style="font-size: 16px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID Venta</th>
                                                                        <th>Categoría</th>
                                                                        <th>Banco</th>
                                                                        <th>Forma de Pago</th>
                                                                        <th>Fecha</th>
                                                                        <th>Fecha Real</th>
                                                                        <th>Documento</th>
                                                                        <th>Serie</th>
                                                                        <th>Monto</th>
                                                                        <th>Estado</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $consulta = 
                                                                        "
                                                                        SELECT 
                                                                            pag.id_pag,
                                                                            cat_pag.nombre_cat_pag,
                                                                            ban.nombre_ban,
                                                                            for_pag.nombre_for_pag,
                                                                            pag.fecha_pag,
                                                                            pag.fecha_real_pag,
                                                                            pag.numero_documento_pag,
                                                                            pag.numero_serie_pag,
                                                                            pag.monto_pag,
                                                                            est_pag.nombre_est_pag,
                                                                            pag.id_est_pag,
                                                                            pag.id_ven
                                                                        FROM
                                                                            pago_pago AS pag 
                                                                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                                                                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                                                                            INNER JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                                                                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
                                                                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
                                                                        WHERE 
                                                                            ven.id_pro = ?
                                                                        ";
                                                                    $contador = 1;
                                                                    $conexion->consulta_form($consulta,array($_SESSION["id_cliente_filtro_panel"]));
                                                                    $fila_consulta = $conexion->extraer_registro();
                                                                    if(is_array($fila_consulta)){
                                                                        foreach ($fila_consulta as $fila) {
                                                                            if (empty($fila["fecha_real_pag"])) {
                                                                                $fecha_real_mostrar = "";
                                                                            }
                                                                            else{
                                                                                $fecha_real_mostrar = date("d/m/Y",strtotime($fila["fecha_real_pag"]));
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><span class="badge"><?php echo $fila["id_ven"];?></span></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_cat_pag"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_ban"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_for_pag"]);?></td>
                                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_pag"]));?></td>
                                                                                <td><?php echo $fecha_real_mostrar;?></td>
                                                                                <td><?php echo utf8_encode($fila["numero_documento_pag"]);?></td>
                                                                                <td><?php echo utf8_encode($fila["numero_serie_pag"]);?></td>
                                                                                
                                                                                <td><?php echo number_format($fila["monto_pag"], 0, ',', '.');?></td>
                                                                                <td><?php echo utf8_encode($fila["nombre_est_pag"]);?></td>
                                                                                
                                                                            </tr>

                                                                            <?php
                                                                            $contador++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            
                                                            

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php  
                                    }
                                    ?>
                                    
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
    	// ver modal
        $(document).on( "click",".detalle" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("../cotizacion/form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });
        
        //BUSQUEDA
        $("#busqueda").focus();
        $("#busqueda").keyup(function(e){
           $('#resultado').show();               
              //obtenemos el texto introducido en el campo de búsqueda
           var consulta = $("#busqueda").val();
                                                                  
              $.ajax({
                    type: "POST",
                    url: "busca_cliente.php",
                    data: "b="+consulta,
                    dataType: "html",
                    beforeSend: function(){
                          //imagen de carga
                          $("#resultado").html("<p align='center'><img src='../../assets/img/loading.gif'/></p>");
                    },
                    error: function(){
                          alert("error petición ajax");
                    },
                    success: function(data){                                              
                          $("#resultado").empty();
                          $("#resultado").append(data);                                  
                    }
              });                                                              
        });
        $(document).on( "click",".busqueda" , function() {
            //$('#contenedor_opcion').html('<img src="../../imagen/ajax-loader.gif">');
            var valor = $(this).attr( "id" );
            $.ajax({
                type: 'POST',
                url: ("procesa_cliente.php"),
                data:"valor="+valor,
                dataType:'json',
                success: function(data) {
                    resultado_busqueda(data);
                }       
            })
            
        });
        function resultado_busqueda(data) {
            location.reload();
        }

        //ELIMINAR SESION GRUPO FAMILIA
        $(document).on("click",".eliminar_sesion" , function() {
            valor = 1;
            $.ajax({
                type: 'POST',
                url: ("sesion_cliente_delete.php"),
                data:"valor="+valor,
                success: function(data) {
                    location.reload();
                }
            })
        });
        //*******//
        
        
    });
</script>
</body>
</html>
