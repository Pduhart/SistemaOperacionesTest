<?php
// require "config.php";

$usuario = $_SESSION["sesion_usuario_panel"];
$nombre_per = $_SESSION["sesion_nombre_perfil_panel"];
$id_usuario = $_SESSION["sesion_id_panel"];
?>
<!-- CABECERA USUARIO -->
<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand"><b>Sistema</b>Operación</a>
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
            </div>
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
                                <a onclick="window.close()" class="btn btn-default btn-flat">Cerrar</a>
                            </div>
                        </li>
                    </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                </ul>
            </div>
    </nav>
</header>
