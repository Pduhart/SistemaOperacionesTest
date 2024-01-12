<?php
// require "config.php";

$usuario = $_SESSION["sesion_usuario_panel"];
$nombre_per = $_SESSION["sesion_nombre_perfil_panel"];
$id_usuario = $_SESSION["sesion_id_panel"];

 ?>
<!-- CABECERA USUARIO -->
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo _ADMIN?>panel.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>S</b>C</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Sistema</b> Operación</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- <img src="<?php echo _ASSETS; ?>img/medicina.png" width="40" class="img-responsive pull-left logo-ucn">
        <div class="navbar-header" style="width: 500px; margin-top: 1%;">
            <span style="color: #fff;font-size: 18px;margin-left: 5%;margin-top: 5px;"><b>--</b></span>
        </div> -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- <img src="<?php //echo _ASSETS?>dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                        <span class="hidden-xs"><?php echo $usuario; ?> <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                    <!-- User image -->
                        <!-- <li class="user-header">
                        </li>   -->           
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <!-- <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Perfil</a>
                            </div> -->
                            <div class="pull-right">
                                <a href="<?php echo _MODULO?>login/cerrar_sesion.php" class="btn btn-default btn-flat">Salir</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar MENU -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <!-- <img src="<?php //echo _ASSETS?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">     -->
            </div>
            <div class="pull-left info">
                <p><?php echo $nombre_per; ?></p>
            </div>
        </div>         
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGACION PRINCIPAL</li>
            <li id="escritorio"><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> <span>Escritorio</span></a></li>
            
            <!-- Parte dinámica del menú -->
            <?php 
            $conexion = new conexion();
            $consulta_usu = 
                "
                SELECT 
                    * 
                FROM 
                    usuario_usuario_modulo,
                    usuario_modulo
                WHERE 
                    usuario_usuario_modulo.id_usu = ".$id_usuario." AND
                    usuario_usuario_modulo.id_mod = usuario_modulo.id_mod AND NOT
                    usuario_modulo.id_mod = 90
                ORDER BY
                    usuario_modulo.orden_mod
                ASC
                ";
            //echo $consulta_usu;
            $conexion->consulta($consulta_usu);
            $total_usu = $conexion->total();
            if ($total_usu > 0){
                $fila_consulta = $conexion->extraer_registro();
                foreach ($fila_consulta as $fila) {
                    switch($fila["id_mod"]){
                        //Usuarios
                        case 15:
                            $_SESSION["modulo_usuario_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_usuario">
                                <a href="#">
                                    <i class="fa ion-android-person"></i> <span>Usuarios</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="usuario-insert"><a href="<?php echo _MODULO?>usuario/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="usuario-select"><a href="<?php echo _MODULO?>usuario/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 3 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="usuario-insert_privilegio"><a href="<?php echo _MODULO?>usuario/form_insert_privilegio.php"><i class="fa ion-ios-shuffle-strong" aria-hidden="true"></i> Privilegios</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 4 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="usuario-insert_unidad"><a href="<?php echo _MODULO?>usuario/form_insert_unidad.php"><i class="fa ion-android-home" aria-hidden="true"></i> Asignar Unidad / Proyecto</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //PARAMETROS
                        case 44:
                            $_SESSION["modulo_parametro_panel"] = 1;
                            ?>
                            <!-- <li class="treeview" id="menu_parametro">
                                <a href="#">
                                    <i class="fa ion-wrench"></i> <span>Parámetros</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="parametro-insert"><a href="<?php // echo _MODULO?>parametro/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Modificar</a></li>
                                        <?php
                                    }

                                    
                                    ?>
                                </ul>
                            </li> -->
                            <li class="treeview" id="menu_parametro">
                                <a href="#">
                                    <i class="fa ion-wrench"></i> <span>Parámetros</span> <i class="fa fa-angle-left pull-right"></i>
                                    <!-- <i class="fa fa-share"></i> <span>Multilevel</span>
                                    <i class="fa fa-angle-left pull-right"></i> -->
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="parametro-insert"><a href="<?php echo _MODULO?>parametro/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Modificar</a></li>
                                        <?php
                                    }
                                    ?>
                                    
                                </ul>
                            </li>
                        <?php
                        break;

                        //PROFESION
                        case 187:
                        $_SESSION["modulo_profesion_panel"] = 1;
                        ?>
                        <li class="treeview" id="menu_profesion">
                            <a href="#">
                                <i class="fa fa-graduation-cap" aria-hidden="true"></i> <span>Profesión</span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                $consulta = 
                                    "
                                    SELECT 
                                        usu.id_mod 
                                    FROM 
                                        usuario_usuario_proceso AS usu,
                                        usuario_proceso AS proceso
                                    WHERE 
                                        usu.id_usu = ".$id_usuario." AND
                                        usu.id_mod = 187 AND
                                        proceso.opcion_pro = 1 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 187
                                    ";
                                
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li id="profesion-insert"><a href="<?php echo _MODULO?>profesion/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                        usu.id_usu = ".$id_usuario." AND
                                        usu.id_mod = 187 AND
                                        proceso.opcion_pro = 2 AND
                                        proceso.id_pro = usu.id_pro AND
                                        proceso.id_mod = 187
                                    ";
                                $conexion->consulta($consulta);
                                $cantidad_opcion = $conexion->total();
                                if($cantidad_opcion > 0){
                                    ?>
                                    <li id="profesion-select"><a href="<?php echo _MODULO?>profesion/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        break;
                        //CONDOMINIO
                        case 306:
                            $_SESSION["modulo_condominio_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_condominio_estructura">
                                <a href="#">
                                    <i class="fa fa-building"></i> <span>Condominio</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>

                                <ul class="treeview-menu">

                                    <li class="treeview" id="menu_condominio">
                                        <a href="#">
                                            <i class="fa fa-building"></i> <span>Condominio</span> <i class="fa fa-angle-left pull-right"></i>
                                        </a>
                                        <ul class="treeview-menu">
                                            <?php
                                            $consulta = 
                                                "
                                                SELECT 
                                                    usu.id_mod 
                                                FROM 
                                                    usuario_usuario_proceso AS usu,
                                                    usuario_proceso AS proceso
                                                WHERE 
                                                    usu.id_usu = ".$id_usuario." AND
                                                    usu.id_mod = ".$fila["id_mod"]." AND
                                                    proceso.opcion_pro = 1 AND
                                                    proceso.id_pro = usu.id_pro AND
                                                    proceso.id_mod = ".$fila["id_mod"]." 
                                                ";
                                            
                                            $conexion->consulta($consulta);
                                            $cantidad_opcion = $conexion->total();
                                            if($cantidad_opcion > 0){
                                                ?>
                                                <li id="condominio-insert"><a href="<?php echo _MODULO?>condominio/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                    usu.id_usu = ".$id_usuario." AND
                                                    usu.id_mod = ".$fila["id_mod"]." AND
                                                    proceso.opcion_pro = 2 AND
                                                    proceso.id_pro = usu.id_pro AND
                                                    proceso.id_mod = ".$fila["id_mod"]." 
                                                ";
                                            $conexion->consulta($consulta);
                                            $cantidad_opcion = $conexion->total();
                                            if($cantidad_opcion > 0){
                                                ?>
                                                <li id="condominio-insert_condominio"><a href="<?php echo _MODULO?>condominio/form_insert_condominio.php"><i class="fa fa-upload" aria-hidden="true"></i> Cargar Condominio</a></li>
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
                                                    usu.id_usu = ".$id_usuario." AND
                                                    usu.id_mod = ".$fila["id_mod"]." AND
                                                    proceso.opcion_pro = 3 AND
                                                    proceso.id_pro = usu.id_pro AND
                                                    proceso.id_mod = ".$fila["id_mod"]." 
                                                ";
                                            $conexion->consulta($consulta);
                                            $cantidad_opcion = $conexion->total();
                                            if($cantidad_opcion > 0){
                                                ?>
                                                <li id="condominio-select"><a href="<?php echo _MODULO?>condominio/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php 
                                    //MODELO 
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 300
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_modelo_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_modelo">
                                            <a href="#">
                                                <i class="fa fa-object-group"></i> <span>Modelo</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 300 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 300 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="modelo-insert"><a href="<?php echo _MODULO?>modelo/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 300 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 300 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="modelo-select"><a href="<?php echo _MODULO?>modelo/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }
                                    //TORRE 
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 307
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_torre_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_torre">
                                            <a href="#">
                                                <i class="fa fa-building"></i> <span>Torre</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 307 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 307 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="torre-insert"><a href="<?php echo _MODULO?>torre/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 307 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 307 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="torre-select"><a href="<?php echo _MODULO?>torre/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }
                                    //VIVIENDA 
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 301
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_vivienda_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_vivienda">
                                            <a href="#">
                                                <i class="fa fa-home"></i> <span>Departamento</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 301 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 301 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="vivienda-insert"><a href="<?php echo _MODULO?>vivienda/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 301 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 301 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="vivienda-select"><a href="<?php echo _MODULO?>vivienda/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }

                                    //ESTACIONAMIENTO 
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 108
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_estacionamiento_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_estacionamiento">
                                            <a href="#">
                                                <i class="fa fa-car"></i> <span>Estacionamiento</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 108 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 108 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="estacionamiento-insert"><a href="<?php echo _MODULO?>estacionamiento/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 108 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 108 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="estacionamiento-select"><a href="<?php echo _MODULO?>estacionamiento/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }

                                    //BODEGA 
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 101
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_bodega_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_bodega">
                                            <a href="#">
                                                <i class="fa fa-building"></i> <span>Bodega</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 101 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 101 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="bodega-insert"><a href="<?php echo _MODULO?>bodega/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 101 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 101 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="bodega-select"><a href="<?php echo _MODULO?>bodega/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }

                                    ?>
                                </ul>
                        <?php
                        break;
                        //SERVICIOS
                        case 299:
                            ?>
                            <li class="treeview" id="menu_servicio">
                                <a href="#">
                                    <i class="fa ion-wrench"></i> <span>Servicios</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                
                                <ul class="treeview-menu">
                                    <?php  
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 303
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_servicio_vivienda_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_servicio_vivienda">
                                            <a href="#">
                                                <i class="fa fa-check-square"></i> <span>Equipamiento</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 303 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 303 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_vivienda-insert"><a href="<?php echo _MODULO?>servicio_vivienda/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 303 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 303 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_vivienda-select"><a href="<?php echo _MODULO?>servicio_vivienda/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php  
                                    }
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 304
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_servicio_adicional_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_servicio_adicional">
                                            <a href="#">
                                                <i class="fa fa-plus-square"></i> <span>Servicio Adicional</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 304 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 304 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_adicional-insert"><a href="<?php echo _MODULO?>servicio_adicional/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 304 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 304 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_adicional-select"><a href="<?php echo _MODULO?>servicio_adicional/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    $consulta = 
                                        "
                                        SELECT 
                                            id_usu
                                        FROM 
                                            usuario_usuario_modulo
                                        WHERE 
                                            id_usu = ".$id_usuario." AND
                                            id_mod = 305
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        $_SESSION["modulo_servicio_interno_panel"] = 1;
                                        ?>
                                        <li class="treeview" id="menu_servicio_interno">
                                            <a href="#">
                                                <i class="fa fa-bed"></i> <span>Servicio Interno</span> <i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <?php
                                                $consulta = 
                                                    "
                                                    SELECT 
                                                        usu.id_mod 
                                                    FROM 
                                                        usuario_usuario_proceso AS usu,
                                                        usuario_proceso AS proceso
                                                    WHERE 
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 305 AND
                                                        proceso.opcion_pro = 1 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 305 
                                                    ";
                                                
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_interno-insert"><a href="<?php echo _MODULO?>servicio_interno/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                                        usu.id_usu = ".$id_usuario." AND
                                                        usu.id_mod = 305 AND
                                                        proceso.opcion_pro = 2 AND
                                                        proceso.id_pro = usu.id_pro AND
                                                        proceso.id_mod = 305 
                                                    ";
                                                $conexion->consulta($consulta);
                                                $cantidad_opcion = $conexion->total();
                                                if($cantidad_opcion > 0){
                                                    ?>
                                                    <li id="servicio_interno-select"><a href="<?php echo _MODULO?>servicio_interno/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        case 308:
                            $_SESSION["modulo_programa_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_programa">
                                <a href="#">
                                    <i class="fa fa-usd"></i> <span>Programa</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="programa-insert"><a href="<?php echo _MODULO?>programa/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="programa-select"><a href="<?php echo _MODULO?>programa/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        case 225:
                            $_SESSION["modulo_reserva_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_reserva">
                                <a href="#">
                                    <i class="fa fa-tag"></i> <span>Reserva</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="reserva-insert"><a href="<?php echo _MODULO?>reserva/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="reserva-select"><a href="<?php echo _MODULO?>reserva/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        case 309:
                            $_SESSION["modulo_aseo_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_aseo">
                                <a href="#">
                                    <i class="fa fa-calendar-o"></i> <span>Planif. Aseo</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="aseo-insert"><a href="<?php echo _MODULO?>aseo/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="aseo-select"><a href="<?php echo _MODULO?>aseo/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        case 302:
                            $_SESSION["modulo_propietario_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_propietario">
                                <a href="#">
                                    <i class="fa fa-user"></i> <span>Cliente</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="propietario-insert"><a href="<?php echo _MODULO?>propietario/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="propietario-select"><a href="<?php echo _MODULO?>propietario/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //INGRESO
                        case 4:
                            $_SESSION["modulo_ingreso_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_ingreso">
                                <a href="#">
                                    <i class="fa fa-usd"></i> <span>Abono</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="ingreso-insert"><a href="<?php echo _MODULO?>ingreso/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="ingreso-select"><a href="<?php echo _MODULO?>ingreso/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="ingreso-select-anulado"><a href="<?php echo _MODULO?>ingreso/form_select_anulado.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar Anulados</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //LIQUIDACION
                        case 310:
                            $_SESSION["modulo_liquidacion_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_liquidacion">
                                <a href="#">
                                    <i class="fa fa-check-square-o"></i> <span>Liquidación</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="liquidacion-insert"><a href="<?php echo _MODULO?>liquidacion/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="liquidacion-select"><a href="<?php echo _MODULO?>liquidacion/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //ARRENDATARIO
                        case 311:
                            $_SESSION["modulo_arrendatario_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_arrendatario">
                                <a href="#">
                                    <i class="fa fa-user"></i> <span>Pasajero</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="arrendatario-insert"><a href="<?php echo _MODULO?>arrendatario/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="arrendatario-select"><a href="<?php echo _MODULO?>arrendatario/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //BLOQUEO DE FECHAS   
                        case 313:
                            $_SESSION["modulo_bloqueo_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_bloqueo">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Bloqueo de Fechas</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="bloqueo-insert"><a href="<?php echo _MODULO?>bloqueo/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="bloqueo-select"><a href="<?php echo _MODULO?>bloqueo/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //Vendedor
                        case 169:
                            $_SESSION["modulo_vendedor_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_vendedor">
                                <a href="#">
                                    <i class="fa ion-android-person"></i> <span>Vendedor</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-insert"><a href="<?php echo _MODULO?>vendedor/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-select"><a href="<?php echo _MODULO?>vendedor/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 3 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-supervisor"><a href="<?php echo _MODULO?>vendedor/form_supervisor.php"><i class="fa fa-plus" aria-hidden="true"></i> Asignar Supervisor de Ventas</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 4 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-supervisor"><a href="<?php echo _MODULO?>vendedor/form_jefe.php"><i class="fa fa-plus" aria-hidden="true"></i> Asignar Jefe de Ventas</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 5 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-cliente"><a href="<?php echo _MODULO?>vendedor/form_cliente.php"><i class="fa fa-plus" aria-hidden="true"></i> Asignar Clientes</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 6 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-lista"><a href="<?php echo _MODULO?>vendedor/form_lista.php"><i class="fa fa-plus" aria-hidden="true"></i> Asignar Listas</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 7 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="vendedor-meta"><a href="<?php echo _MODULO?>vendedor/form_meta.php"><i class="fa fa-plus" aria-hidden="true"></i> Asignar Meta</a></li>
                                        <?php
                                    }


                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;


                        //Bono
                        case 142:
                            $_SESSION["modulo_bono_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_bono">
                                <a href="#">
                                    <i class="fa fa-dollar"></i> <span>Bono</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="bono-insert"><a href="<?php echo _MODULO?>bono/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="bono-select"><a href="<?php echo _MODULO?>bono/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //Descuento
                        case 135:
                            $_SESSION["modulo_descuento_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_descuento">
                                <a href="#">
                                    <i class="fa fa-dollar"></i> <span>Descuento</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="descuento-insert"><a href="<?php echo _MODULO?>descuento/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="descuento-select"><a href="<?php echo _MODULO?>descuento/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //Premio
                        case 143:
                            $_SESSION["modulo_premio_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_premio">
                                <a href="#">
                                    <i class="fa fa-gift"></i> <span>Premio</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="premio-insert"><a href="<?php echo _MODULO?>premio/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="premio-select"><a href="<?php echo _MODULO?>premio/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //UF
                        case 312:
                            $_SESSION["modulo_uf_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_uf">
                                <a href="#">
                                    <i class="fa fa-bank"></i> <span>UF</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="uf-insert"><a href="<?php echo _MODULO?>uf/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="uf-select"><a href="<?php echo _MODULO?>uf/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //ETAPA
                        case 400:
                            $_SESSION["modulo_etapa_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_etapa">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> <span>Etapa</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="etapa-insert"><a href="<?php echo _MODULO?>etapa/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="etapa-select"><a href="<?php echo _MODULO?>etapa/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //ETAPA
                        case 23:
                            $_SESSION["modulo_cotizacion_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_cotizacion">
                                <a href="#">
                                    <i class="fa fa-check-square-o"></i> <span>Cotización</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="cotizacion-insert"><a href="<?php echo _MODULO?>cotizacion/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="cotizacion-select"><a href="<?php echo _MODULO?>cotizacion/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //PROMESA
                        case 144:
                            $_SESSION["modulo_promesa_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_promesa">
                                <a href="#">
                                    <i class="fa fa-check-square-o"></i> <span>Promesa</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="promesa-insert"><a href="<?php echo _MODULO?>promesa/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="promesa-select"><a href="<?php echo _MODULO?>promesa/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //VENTA
                        case 173:
                            $_SESSION["modulo_venta_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_venta">
                                <a href="#">
                                    <i class="fa fa-check-square-o"></i> <span>Venta</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    /*$consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="venta-insert"><a href="<?php echo _MODULO?>venta/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
                                        <?php
                                    }*/
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="venta-select"><a href="<?php echo _MODULO?>venta/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                         //PAGO
                        case 145:
                            $_SESSION["modulo_pago_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_pago">
                                <a href="#">
                                    <i class="fa fa-dollar"></i> <span>Pago</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="pago-select"><a href="<?php echo _MODULO?>pago/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //BANCO
                        case 320:
                            $_SESSION["modulo_banco_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_banco">
                                <a href="#">
                                    <i class="fa fa-bank"></i> <span>Banco</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="banco-insert"><a href="<?php echo _MODULO?>banco/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="banco-select"><a href="<?php echo _MODULO?>banco/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        //EVENTOS
                        case 9:
                            $_SESSION["modulo_evento_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_evento">
                                <a href="#">
                                    <i class="fa fa-flag"></i> <span>Calendario de Eventos</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="evento-insert"><a href="<?php echo _MODULO?>evento/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="evento-select"><a href="<?php echo _MODULO?>evento/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>


                                        <?php
                                    }
                                    
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;

                        case 500:
                            $_SESSION["modulo_mailing_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_mailing">
                                <a href="#">
                                    <i class="fa fa-flag"></i> <span>Campañas Mailing</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    $consulta = 
                                        "
                                        SELECT 
                                            usu.id_mod 
                                        FROM 
                                            usuario_usuario_proceso AS usu,
                                            usuario_proceso AS proceso
                                        WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="mailing-insert"><a href="<?php echo _MODULO?>mailing/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                       
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0){
                                        ?>
                                        <li id="mailing-select"><a href="<?php echo _MODULO?>mailing/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar Envíos Clientes</a></li>


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
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 3 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0): ?>
                                        <li id="mailing-select"><a href="<?php echo _MODULO?>mailing/form_select_listas.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Mis Listas Masivas</a></li>
                                    <?php endif;
                                    
                                     $consulta = "SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 4 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                        ";
                                    $conexion->consulta($consulta);
                                    $cantidad_opcion = $conexion->total();
                                    if($cantidad_opcion > 0): ?>
                                        <li id="mailing-select_envios"><a href="<?php echo _MODULO?>mailing/form_select_envios.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Mis Envíos Masivos</a></li>
                                    <?php endif;?>
                                </ul>
                            </li>
                        <?php
                        break;
                        case 501:
                            $_SESSION["modulo_evaluacion_panel"] = 1;
                            ?>
                            <li class="treeview" id="menu_evaluacion">
                                <a href="#">
                                    <i class="fa fa-file-text"></i> <span>Evaluación de desempeño</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                <?php
                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM  usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 1 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");
                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-insert"><a href="<?php echo _MODULO?>evaluacion/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar evaluación</a></li>
                                    <?php endif;    

                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 2 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");

                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-select"><a href="<?php echo _MODULO?>evaluacion/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar evaluaciones</a></li>
                                    <?php endif;

                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 3 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");

                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-detalle"><a href="<?php echo _MODULO?>evaluacion/form_detalle.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Informe de Desempeño</a></li>
                                    <?php endif;

                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 4 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");

                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-operaciones"><a href="<?php echo _MODULO?>evaluacion/form_operaciones.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Informe de Operaciones</a></li>
                                                                    
                                    <?php endif;

                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 5 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");

                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-carta"><a href="<?php echo _MODULO?>evaluacion/form_carta.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Carta de mérito y demérito</a></li>
                                    <?php endif;
                                    $cantidad_opcion = conexion::consulta_total("SELECT usu.id_mod FROM usuario_usuario_proceso AS usu,usuario_proceso AS proceso WHERE 
                                            usu.id_usu = ".$id_usuario." AND
                                            usu.id_mod = ".$fila["id_mod"]." AND
                                            proceso.opcion_pro = 6 AND
                                            proceso.id_pro = usu.id_pro AND
                                            proceso.id_mod = ".$fila["id_mod"]." 
                                            ");

                                    if($cantidad_opcion > 0): ?>
                                        <li id="evaluacion-listar_carta"><a href="<?php echo _MODULO?>evaluacion/form_listar_carta.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listado de cartas</a></li>
                                    <?php endif ?>


                                </ul>
                            </li>
                        <?php break; 
                    }
                }
            }
            ?>

            <!-- Fin menú dinámico -->

            <!-- Ejemplo botón de 3 Niveles <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li> Multinivel -->
            <li class="header">&nbsp;</li>          
        </ul>
    </section>
<!-- /.sidebar -->
</aside>