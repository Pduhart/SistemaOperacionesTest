<?php
session_start(); 
require "config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head.php";
?>
<!-- siempre al final los ajustes -->
<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">
<link href="<?php echo _ASSETS?>calendar/jquery.event.calendar.css" rel="stylesheet" />
<link href="<?php echo _ASSETS?>calendar/green.event.calendar.css" rel="stylesheet" id="theme" />
</head>
<!-- Siempre cerrar el head y abrir body -->
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php
        include _INCLUDE."class/conexion.php";
        // include _CONECTA."conexion.php";
        require_once "menu_modulo.php";
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Escritorio
                    <small>panel de Control</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Escritorio</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php
                switch ($_SESSION["sesion_perfil_panel"]) {
                  	case 1:
                    //administrador
                    	include("modulo/informe/panel_administrador.php");
                    break;
                  	case 2:
                    //Jefe Ventas
                    	include("modulo/informe/panel_jventas.php");
                    break;
                  	case 3:
                    //Operaciones
                    	include("modulo/informe/panel_operaciones.php");
                    break;
                	case 4:
                    //propietario
                    	include("modulo/informe/panel_vendedor.php");
                    //include("modulo/informe/panel_propietario.php");
                    break;
                    case 6:
                    //contabilidad
                    	include("modulo/informe/panel_contabilidad.php");
                    //include("modulo/informe/panel_propietario.php");
                    break;
                    case 7:
                    //Operaciones
                    	include("modulo/informe/panel_operaciones.php");
                   	break;
                }
                ?>
                     
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

</body>
</html>
