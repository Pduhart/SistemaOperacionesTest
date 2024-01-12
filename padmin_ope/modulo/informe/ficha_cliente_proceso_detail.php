<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}

if (!isset($_GET["opc"])) {
    header("Location: "._MODULO."informe/ficha_cliente_proceso.php");
}


require_once _INCLUDE."head_informe.php";
// inicializando variables
$id_cot = 0;
$id_ven = 0;
$id_pag = 0;
// recibe los get
$opc = $_GET["opc"];
$id_pro = (isset($_GET["id_pro"])) ? d64($_GET["id_pro"]) :0;
$id_cot = (!empty($_GET["id_cot"]))? d64($_GET["id_cot"]) :0;
$id_ven = (!empty($_GET["id_ven"]))? d64($_GET["id_ven"]) :0;
$id_pag = (!empty($_GET["id_pag"]))? d64($_GET["id_pag"]) :0;
?>
<title>Cliente - Ficha</title>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>dist/css/cotizador.css">
<?php include_once _INCLUDE."js_comun.php";?>
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
        <button type="button" class="btn btn-primary" onClick="window.history.back();return false;"> < VOLVER</button>
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
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body" style="padding-top: 0">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="row contenedor_tabla" id="contenedor_tabla">

                                            	<?php 
                                            	switch ($opc) {
                                            	case 1: //------------------- VER CLIENTE
			                                        if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
			                                       	//------------------- SI HAY CLIENTE SELECCIONADO
	                                                	include "inner-ficha/ver-cliente.php";
	                                                }
                                                break;

                                                case 2: //------------------- EDITAR CLIENTE
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/edicion-cliente.php";
	                                                }
                                                break;

                                                case 3: //------------------- CREAR CLIENTE
	                                                include "inner-ficha/crea-cliente.php";
                                                break;

                                                case 4: //------------------- EDITA COTIZACIÓN
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/edicion-cotizacion.php";
	                                                }
                                                break;

                                                case 5: //------------------- CREAR COTIZACION
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/crea-cotizacion.php";
	                                                }
                                                break;

                                                case 6: //------------------- AGREGAR SEGUIMIENTO
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/agrega-seguimiento.php";
	                                                }
                                                break;

                                                case 7: //------------------- AGREGAR EVENTO AGENDA
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/agrega-evento.php";
	                                                }
                                                break;

                                                case 8: //------------------- PASAR A PROMESA
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/pasar-promesa.php";
	                                                }
                                                break;

                                                case 9: //------------------- REGISTRAR PAGOS
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/registra-pago.php";
	                                                }
                                                break;

                                                case 10: //------------------- EDITA PAGOS
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/edita-pago.php";
	                                                }
                                                break;

                                                case 11: //------------------- EDITA VENTA
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
                                                		include "inner-ficha/edita-venta.php";
	                                                }
                                                break;

                                                case 12: //------------------- DESISTE VENTA
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
                                                		include "inner-ficha/desiste-venta.php";
	                                                }
                                                break;

                                                case 13: //------------------- AGREGAR OBS CLIENTE
                                                	if(isset($_SESSION["id_cliente_filtro_ficha_panel"])){
	                                                	include "inner-ficha/agrega-observacion.php";
	                                                }
                                                break;
                                            		
                                        		default:

                                        		break;
                                            	}
                                            	 ?>
                                                
                                            </div>
                                        </div>
	                                    
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

</body>
</html>
