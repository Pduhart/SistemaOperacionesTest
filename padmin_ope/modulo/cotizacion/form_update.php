<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_cotizacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_cot = $_POST["valor"];
$conexion = new conexion();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
<style type="text/css">
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d2d6de;
    border-radius: 0px;
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 34px;
    user-select: none;
    -webkit-user-select: none;
}
</style>
</head>
<body>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil" aria-hidden="true"></i> Formulario Actualización       </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="formulario" role="form" method="post" action="update.php">
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
                pro.direccion_trabajo_pro,
                pro.correo_pro,
                pro.fono_pro,
                cot.fecha_cot,
                pre_cot.id_pre_cot,
                pre_cot.nombre_pre_cot,
                nac.id_nac,
                nac.nombre_nac,
                reg.id_reg,
                reg.descripcion_reg,
                com.id_com,
                com.nombre_com,
                sex.id_sex,
                sex.nombre_sex,
                civ.id_civ,
                civ.nombre_civ,
                est.id_est,
                est.nombre_est,
                prof.id_prof,
                prof.nombre_prof,
                pre_int.id_seg_int_cot,
                pre_int.nombre_seg_int_cot,
                ren_cot.id_ren_cot,
                ren_cot.nombre_ren_cot,
	            cot.porcentaje_credito_cot
            FROM
                cotizacion_cotizacion AS cot 
                INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot
                INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con  
                INNER JOIN modelo_modelo AS mode ON mode.id_mod = cot.id_mod
                INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro
                INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot
                INNER JOIN cotizacion_preaprobacion_cotizacion AS pre_cot ON pre_cot.id_pre_cot = cot.id_pre_cot
                LEFT JOIN cotizacion_seguimiento_interes_cotizacion AS pre_int ON pre_int.id_seg_int_cot = cot.id_seg_int_cot
                INNER JOIN nacionalidad_nacionalidad AS nac ON nac.id_nac = pro.id_nac
                INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com
                INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg
                INNER JOIN sexo_sexo  AS sex ON sex.id_sex = pro.id_sex
                INNER JOIN civil_civil  AS civ ON civ.id_civ = pro.id_civ
                LEFT JOIN estudio_estudio  AS est ON est.id_est = pro.id_est
                LEFT JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof
                LEFT JOIN cotizacion_renta_cotizacion AS ren_cot ON ren_cot.id_ren_cot = cot.id_ren_cot
            WHERE
                cot.id_cot = ?
            ";
        $conexion->consulta_form($consulta,array($id_cot));
        $fila = $conexion->extraer_registro_unico();
        $id_con = utf8_encode($fila['id_con']);
        $nombre_con = utf8_encode($fila['nombre_con']);
        $id_viv = utf8_encode($fila['id_viv']);
        $nombre_viv = utf8_encode($fila['nombre_viv']);
        $id_can_cot = utf8_encode($fila['id_can_cot']);
        $nombre_can_cot = utf8_encode($fila['nombre_can_cot']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $id_pro = utf8_encode($fila['id_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $nombre2_pro = utf8_encode($fila['nombre2_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $rut_pro = utf8_encode($fila['rut_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_tor = utf8_encode($fila['id_tor']);
        $nombre_tor = utf8_encode($fila['nombre_tor']);
        $id_mod = utf8_encode($fila['id_mod']);
        $nombre_mod = utf8_encode($fila['nombre_mod']);
        $fecha_cot = date("d-m-Y",strtotime($fila['fecha_cot']));
        $id_pre_cot = utf8_encode($fila['id_pre_cot']);
        $nombre_pre_cot = utf8_encode($fila['nombre_pre_cot']);
        $direccion_trabajo_pro = utf8_encode($fila['direccion_trabajo_pro']);
        $porcentaje_credito_cot = utf8_encode($fila['porcentaje_credito_cot']);

        $nombre_pre_cot = utf8_encode($fila['nombre_pre_cot']);

        $id_seg_int_cot = $fila['id_seg_int_cot'];
        $nombre_seg_int_cot = utf8_encode($fila['nombre_seg_int_cot']);

        $id_ren_cot = $fila['id_ren_cot'];
        $nombre_ren_cot = utf8_encode($fila['nombre_ren_cot']);

        $id_nac = utf8_encode($fila['id_nac']);
        $id_sex = utf8_encode($fila['id_sex']);
        $id_civ = utf8_encode($fila['id_civ']);
        $id_est = utf8_encode($fila['id_est']);
        $id_prof = utf8_encode($fila['id_prof']);
        $id_com = $fila['id_com'];
        $id_reg = $fila['id_reg'];
        $nombre_com = utf8_encode($fila['nombre_com']);
        $descripcion_reg = utf8_encode($fila['descripcion_reg']);
        $nombre_nac = utf8_encode($fila['nombre_nac']);
        $nombre_sex = utf8_encode($fila['nombre_sex']);
        $nombre_civ = utf8_encode($fila['nombre_civ']);
        $nombre_est = utf8_encode($fila['nombre_est']);
        $nombre_prof = utf8_encode($fila['nombre_prof']);

        $consulta = "SELECT id_vend FROM vendedor_vendedor WHERE id_usu = ?";
        $conexion->consulta_form($consulta,array($_SESSION["sesion_id_panel"]));
        $fila = $conexion->extraer_registro_unico();
        $id_vend = $fila["id_vend"];
        
        ?>
        <input type="hidden" name="id" id="id" value="<?php echo $id_cot;?>"></input>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-5 col-sm-offset-4">
                    <h4>Búsqueda o Ingreso de Cliente</h4>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="propietario">Cliente:</label>
                        <select class="form-control select2" id="propietario" name="propietario"> 
                            <option value="<?php echo $id_pro;?>"><?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." / ".$rut_pro;?></option>
                            <?php  
                            $consulta = 
                                "
                                SELECT
                                    pro.id_pro,
                                    pro.nombre_pro,
                                    pro.apellido_paterno_pro,
                                    pro.apellido_materno_pro,
                                    pro.rut_pro
                                FROM
                                    propietario_propietario AS pro
                                WHERE
                                    pro.id_est_pro = 1 AND
                                    pro.id_pro <> '".$id_pro."' AND
                                    EXISTS(
                                        SELECT 
                                            ven.id_vend
                                        FROM 
                                            vendedor_propietario_vendedor AS ven
                                        WHERE
                                            ven.id_pro = pro.id_pro AND
                                            ven.id_vend = '".$id_vend."'
                                    )
                                ORDER BY
                                    pro.nombre_pro";
                            $conexion->consulta($consulta);
                            $fila_consulta = $conexion->extraer_registro();
                            if(is_array($fila_consulta)){
                                foreach ($fila_consulta as $fila) {
                                    ?>
                                    <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']." / ".$fila['rut_pro']);?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-8" id="contenedor_propietario">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="rut">Rut:</label>
                            <input type="text" name="rut" class="form-control rut" id="rut" value="<?php echo $rut_pro;?>" disabled />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="nombre">Primer Nombre:</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="segundo_nombre">Segundo Nombre:</label>
                            <input type="text" name="segundo_nombre" class="form-control" id="segundo_nombre" value="<?php echo $nombre2_pro;?>"/>
                        </div>
                        
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="apellido_paterno">Apellido Paterno:</label>
                            <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" value="<?php echo $apellido_paterno_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="apellido_materno">Apellido Materno:</label>
                            <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" value="<?php echo $apellido_materno_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fono">Fono:</label>
                            <input type="text" name="fono" class="form-control numero" id="fono" value="<?php echo $fono_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="text" name="correo" class="form-control" id="correo" value="<?php echo $correo_pro;?>"/>
                        </div>
                        
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
                            <label for="nacionalidad">Nacionalidad:</label>
                            <select class="form-control select2" id="nacionalidad" name="nacionalidad"> 
                                <option value="<?php echo $id_nac;?>"><?php echo $nombre_nac;?></option>
                                <?php  
                                $consulta = "SELECT * FROM nacionalidad_nacionalidad WHERE id_nac <> '".$id_nac."' ORDER BY nombre_nac";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_nac'];?>"><?php echo utf8_encode($fila['nombre_nac']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
                            <label for="region">Región:</label>
                            <select class="form-control select2" id="region" name="region"> 
                                <option value="<?php echo $id_reg;?>"><?php echo $descripcion_reg;?></option>
                                <?php  
                                $consulta = "SELECT * FROM region_region WHERE id_reg <> ".$id_reg." ORDER BY descripcion_reg";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_reg'];?>"><?php echo utf8_encode($fila['descripcion_reg']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
	                        <label for="comuna">Comuna:</label>
	                        <select class="form-control select2" id="comuna" name="comuna"> 
	                            <option value="<?php echo $id_com;?>"><?php echo $nombre_com;?></option>
	                            <?php  
	                            $consulta = "SELECT * FROM comuna_comuna WHERE id_reg = ".$id_reg." AND id_com <> ".$id_com." ORDER BY nombre_com";
	                            $conexion->consulta($consulta);
	                            $fila_consulta = $conexion->extraer_registro();
	                            if(is_array($fila_consulta)){
	                                foreach ($fila_consulta as $fila) {
	                                    ?>
	                                    <option value="<?php echo $fila['id_com'];?>"><?php echo utf8_encode($fila['nombre_com']);?></option>
	                                    <?php
	                                }
	                            }
	                            ?>
	                        </select>
	                    </div>
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
                            <label for="profesion">Profesión:</label>
                            <select class="form-control select2" id="profesion" name="profesion"> 
                                <?php  
	                            if(!empty($id_prof)){
	                                ?>
	                                <option value="<?php echo $id_prof;?>"><?php echo $nombre_prof;?></option>
	                                <?php
	                            }
	                            else{
	                                ?>
	                                <option value="">Seleccione profesión</option>
	                                <?php
	                            }
	                            ?>
                                <?php  
                                $consulta = "SELECT * FROM profesion_profesion WHERE id_prof <> '".$id_prof."' ORDER BY nombre_prof";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_prof'];?>"><?php echo utf8_encode($fila['nombre_prof']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
                            <label for="direccion_trabajo">Lugar de Trabajo:</label>
                            <input type="text" name="direccion_trabajo" class="form-control" id="direccion_trabajo" value="<?php echo $direccion_trabajo_pro;?>"/>
                        </div>
                    </div>
                    <div class="col-sm-3">
                    	<div class="form-group">
                            <label for="sexo">Sexo:</label>
                            <select class="form-control" id="sexo" name="sexo"> 
                                <option value="<?php echo $id_sex;?>"><?php echo $nombre_sex;?></option>
                                <?php  
                                $consulta = "SELECT * FROM sexo_sexo WHERE id_sex <> '".$id_sex."' ORDER BY nombre_sex";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_sex'];?>"><?php echo utf8_encode($fila['nombre_sex']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <hr> 
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="condominio">Condominio:</label>
                            <select class="form-control select2" id="condominio" name="condominio"> 
                                <option value="<?php echo $id_con;?>"><?php echo $nombre_con;?></option>
                                <?php  
                                $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 AND id_con <> '".$id_con."' ORDER BY nombre_con";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="torre">Torre:</label>
                            <select class="form-control select2" id="torre" name="torre">
                                <option value="<?php echo $id_tor;?>"><?php echo $nombre_tor;?></option>
                                <?php  
                                $consulta = "SELECT * FROM torre_torre WHERE id_est_tor = 1 AND id_con = '".$id_con."' AND id_tor <> '".$id_tor."' ORDER BY nombre_tor";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_tor'];?>"><?php echo utf8_encode($fila['nombre_tor']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="modelo">Modelo:</label>
                            <select class="form-control select2" id="modelo" name="modelo"> 
                                <option value="<?php echo $id_mod;?>"><?php echo $nombre_mod;?></option>
                                <?php  
                                $consulta = "SELECT * FROM modelo_modelo WHERE id_est_mod = 1 AND id_mod <> '".$id_mod."' ORDER BY nombre_mod";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_mod'];?>"><?php echo utf8_encode($fila['nombre_mod']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="vivienda">Departamento:</label>
                            <select class="form-control select2" id="vivienda" name="vivienda"> 
                                <option value="<?php echo $id_viv;?>"><?php echo $nombre_viv;?></option>
                                <?php  
                                $consulta = 
                                    "
                                    SELECT 
                                        *
                                    FROM
                                        vivienda_vivienda AS viv
                                    WHERE
                                        viv.id_est_viv = 1 AND 
                                        viv.id_viv <> '".$id_viv."' AND NOT 
                                       EXISTS(
                                            SELECT 
                                                ven.id_ven
                                            FROM 
                                                venta_venta AS ven
                                            WHERE
                                                ven.id_viv = viv.id_viv AND
                                                ven.id_est_ven <> 3
                                        )
                                    ORDER BY viv.nombre_viv
                                    ";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_viv'];?>"><?php echo utf8_encode($fila['nombre_viv']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="canal">Canal:</label>
                            <select class="form-control select2" id="canal" name="canal"> 
                                <option value="<?php echo $id_can_cot;?>"><?php echo $nombre_can_cot;?></option>
                                <?php  
                                $consulta = "SELECT * FROM cotizacion_canal_cotizacion WHERE id_can_cot <> '".$id_can_cot."' AND id_can_cot IN (1,2,3,4,8,11,12,14,15,17) ORDER BY nombre_can_cot";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_can_cot'];?>"><?php echo utf8_encode($fila['nombre_can_cot']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="preaprobacion">Preaprobación crédito:</label>
                            <select class="form-control" id="preaprobacion" name="preaprobacion"> 
                                <option value="<?php echo $id_pre_cot;?>"><?php echo $nombre_pre_cot;?></option>
                                <?php  
                                $consulta = "SELECT * FROM cotizacion_preaprobacion_cotizacion WHERE id_pre_cot <> ".$id_pre_cot." ORDER BY id_pre_cot";
                                $conexion->consulta($consulta);
                                $fila_consulta = $conexion->extraer_registro();
                                if(is_array($fila_consulta)){
                                    foreach ($fila_consulta as $fila) {
                                        ?>
                                        <option value="<?php echo $fila['id_pre_cot'];?>"><?php echo utf8_encode($fila['nombre_pre_cot']);?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="interes">Interés:</label>
                            <select class="form-control" id="interes" name="interes"> 
                                <option value="<?php echo $id_seg_int_cot;?>"><?php echo $nombre_seg_int_cot;?></option>
                                <?php  
                                if ($id_seg_int_cot<>'') {
                                	$consulta = "SELECT * FROM cotizacion_seguimiento_interes_cotizacion WHERE id_seg_int_cot <> ".$id_seg_int_cot." ORDER BY id_seg_int_cot";
                                } else {
                                	$consulta = "SELECT * FROM cotizacion_seguimiento_interes_cotizacion ORDER BY id_seg_int_cot";
                                }
	                            
	                            $conexion->consulta($consulta);
	                            $fila_consulta = $conexion->extraer_registro();
	                            if(is_array($fila_consulta)){
	                                foreach ($fila_consulta as $fila) {
	                                    ?>
	                                    <option value="<?php echo $fila['id_seg_int_cot'];?>"><?php echo utf8_encode($fila['nombre_seg_int_cot']);?></option>
	                                    <?php
	                                }
	                            }
	                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="fecha_cot">Fecha:</label>
                            <input type="text" name="fecha_cot" class="form-control datepicker elemento" id="fecha_cot" value="<?php echo $fecha_cot; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="renta">Renta Aproximada:</label>
                            <select class="form-control" id="renta" name="renta"> 
                                <option value="<?php echo $id_ren_cot;?>"><?php echo $nombre_ren_cot;?></option>
                                <?php  
                                if ($id_ren_cot<>'') {
                                	$consulta = "SELECT * FROM cotizacion_renta_cotizacion WHERE id_ren_cot <> ".$id_ren_cot." ORDER BY id_ren_cot ASC";
                                } else {
                                	$consulta = "SELECT * FROM cotizacion_renta_cotizacion ORDER BY id_ren_cot ASC";
                                }
	                            
	                            $conexion->consulta($consulta);
	                            $fila_consulta = $conexion->extraer_registro();
	                            if(is_array($fila_consulta)){
	                                foreach ($fila_consulta as $fila) {
	                                    ?>
	                                    <option value="<?php echo $fila['id_ren_cot'];?>"><?php echo utf8_encode($fila['nombre_ren_cot']);?></option>
	                                    <?php
	                                }
	                            }
	                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                    	<div class="form-group">
                            <label for="porcentaje_credito">Porcentaje Crédito:</label>
                            <input type="text" name="porcentaje_credito" class="form-control" id="porcentaje_credito" value="<?php echo $porcentaje_credito_cot;?>"/>
                        </div>
                    </div>
                </div>
            </div>
            <div id="contendor_boton" class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
            </div>
        </div>
        <!-- /.box-body -->
        
    </form>
</div>

<?php // include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
        });

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                rut: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                apellido_paterno: { 
                    required: true,
                    minlength: 3
                },
                correo:{
                    required: true,
                    minlength: 4,
                    email: true
                },
                fono:{
                    required: true,
                    minlength: 4
                },
                condominio: { 
                    required: true
                },
                 torre: { 
                    required: true
                },
                departamento: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                sexo: { 
                    required: true
                },
                canal: { 
                    required: true
                },
                fecha: { 
                    required: true
                },
                preaprobacion: { 
                    required: true
                },
                interes: { 
                    required: true
                },
                 porcentaje_credito: {
                	required: true
                },
                renta: { 
                    required: true
                }


            },
            messages: {
                rut: {
                    required: "Ingrese Rut"
                },
                nombre: {
                    required: "Ingrese Nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                apellido_paterno: {
                    required: "Ingrese Apellido Paterno",
                    minlength: "Mínimo 3 caracteres"
                },
                correo: {
                    required: "Ingrese correo",
                    minlength: "Mínimo 4 caracteres",
                    email: "Ingrese correo válido"
                },
                fono: {
                    required: "Ingrese fono",
                    minlength: "Mínimo 4 caracteres"
                },
                condominio: {
                    required: "Seleccione condominio"
                },
                torre: {
                    required: "Seleccione torre"
                },
                sexo: {
                    required: "Seleccione sexo"
                },
                departamento: {
                    required: "Seleccione departamento"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                porcentaje_credito: {
                    required: "Seleccione Porcentaje"
                },
                canal: {
                    required: "Ingrese canal"
                },
                fecha: {
                    required: "Ingrese fecha"
                },
                preaprobacion: {
                    required: "Ingrese preaprobación de crédito"
                },
                interes: {
                    required: "Ingrese Nivel de Interés"
                },
                renta: {
                    required: "Ingrese Renta"
                }
            }
        });

        $(document).on( "change","#propietario" , function() {
            valor = $(this).val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_propietario.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#contenedor_propietario').html(data);
                    }
                })
            }
        });

        $('#region').change(function(){
            var_region = $('#region').val();
            $.ajax({
                type: 'POST',
                url: ("select_comuna.php"),
                data:"region="+var_region,
                success: function(data) {
                    $('#comuna').html(data);
                }
            })
            
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
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
                    url: ("procesa_torre.php"),
                    data:"valor="+valor,
                    success: function(data) {
                         $('#vivienda').html(data);
                    }
                })
            }
        });
        
        $(document).on( "change","#modelo" , function() {
            valor = $(this).val();
            var_torre = $('#torre').val();
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_modelo.php"),
                    data:"valor="+valor+"&torre="+var_torre,
                    success: function(data) {
                         $('#vivienda').html(data);
                    }
                })
            }
            else{
                $('#vivienda').html("Seleccione Departamento");
            }
        });
        
        $(document).on( "change","#vivienda" , function() {
            var_valor = $(this).val();
            var_modelo = $('#modelo').val();
            if(var_valor != ""){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_vivienda.php"),
                    data:"valor="+var_valor,
                    success: function(data) {
                        $('#contenedor_info_vivienda').html(data);
                    }
                })
                
                $.ajax({
                    type: 'POST',
                    url: ("procesa_vivienda_modelo.php"),
                    data:"valor="+var_valor,
                    success: function(data) {
                        $('#modelo').html(data);
                    }
                })
                
            }
            else{
                $('#contenedor_info_vivienda').html("");
            }

            
        });
        
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información actualizada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.href = "form_select.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Cotización ya ha sido ingresada", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        }

        $('#formulario').submit(function () {
            if ($("#formulario").validate().form() == true){
                $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario').serialize();
                $.ajax({
                    data: dataString,
                    type: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    success: function (data) {
                        resultado(data);
                    }
                })
            }
            
            return false;
        });
    }); 
</script>
</body>
</html>