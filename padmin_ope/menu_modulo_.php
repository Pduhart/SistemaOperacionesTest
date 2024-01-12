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
        <span class="logo-mini"><b>P</b>Sis</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Padmin</b>Sis</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo _ASSETS?>dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $usuario; ?> <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                    </a>
                <ul class="dropdown-menu">
                <!-- User image -->
                    <!-- <li class="user-header">
                    </li>   -->           
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">Perfil</a>
                        </div>
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
                <img src="<?php echo _ASSETS?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">                     
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
                    usuario_usuario_modulo.id_mod = usuario_modulo.id_mod
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
                        //Vehículo
                        case 224:
                            $_SESSION["modulo_vehiculo_panel"] = 1;
                            ?>
                            <li class="treeview" id="vehiculo">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Vehículo</span> <i class="fa fa-angle-left pull-right"></i>
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
                                        <li id="vehiculo-insert"><a href="<?php echo _MODULO?>vehiculo/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                        <li id="vehiculo-select"><a href="<?php echo _MODULO?>vehiculo/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //Unidad
                        case 220:
                            $_SESSION["modulo_unidad_panel"] = 1;
                            ?>
                            <li class="treeview" id="unidad">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Unidad</span> <i class="fa fa-angle-left pull-right"></i>
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
                                        <li id="unidad-insert"><a href="<?php echo _MODULO?>unidad/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                        <li id="unidad-select"><a href="<?php echo _MODULO?>unidad/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //Proyecto
                        case 221:
                            $_SESSION["modulo_proyecto_panel"] = 1;
                            ?>
                            <li class="treeview" id="proyecto">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Proyecto</span> <i class="fa fa-angle-left pull-right"></i>
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
                                        <li id="proyecto-insert"><a href="<?php echo _MODULO?>proyecto/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                        <li id="proyecto-select"><a href="<?php echo _MODULO?>proyecto/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //CUENTAS
                        case 222:
                            $_SESSION["modulo_cuenta_panel"] = 1;
                            ?>
                            <li class="treeview" id="cuenta">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Cuentas</span> <i class="fa fa-angle-left pull-right"></i>
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
                                        <li id="cuenta-insert"><a href="<?php echo _MODULO?>cuenta/form_insert.php"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a></li>
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
                                        <li id="cuenta-select"><a href="<?php echo _MODULO?>cuenta/form_select.php"><i class="fa fa-list-alt" aria-hidden="true"></i> Listar</a></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php
                        break;
                        //RESERVAS
                        case 225:
                            $_SESSION["modulo_reserva_panel"] = 1;
                            ?>
                            <li class="treeview" id="reserva">
                                <a href="#">
                                    <i class="fa fa-calendar"></i> <span>Reservas</span> <i class="fa fa-angle-left pull-right"></i>
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

        </ul>
    </section>
<!-- /.sidebar -->
</aside>