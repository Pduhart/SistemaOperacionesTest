<?php 
include("../../class/carro.php");
session_start(); 
unset($_SESSION['c2']);
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_liquidacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
unset($_SESSION["ocarrito_item"]);
unset($_SESSION["numero_item"]);
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/alert_prueba/dist/sweetalert.css">

<!-- iCheck for checkboxes and radio inputs -->
<!-- <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css"> -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">

<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">

<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/bootstrap-image-gallery.min.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/datepicker/datepicker3.css">
<noscript><link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui-noscript.css"></noscript>
<style type="text/css">
    .callout {
        border-radius: 3px;
        margin: 0 0 20px 0;
        padding: 8px 30px 8px 15px;
        border-left: 5px solid #eee;
    }

    .callout h4{
        margin-bottom: 0;
    }

    .vehiculo label.sel {

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
        height: 34px;
        user-select: none;
        -webkit-user-select: none;
    }

    .table>tbody>tr>td, 
    .table>tbody>tr>th, 
    .table>tfoot>tr>td, 
    .table>tfoot>tr>th, 
    .table>thead>tr>td, 
    .table>thead>tr>th {
        padding: 4px 8px;
        line-height: 1.3;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .bg-gray {
        color: #000;
        background-color: #f5f5f5 !important;
    }

    #mensaj1{
        display: none;
    }
    #mensaj2{
        display: none;
    }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        
        require_once _INCLUDE."menu_modulo.php";
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Liquidación
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Liquidación</a></li>
                    <li class="active">Ingreso</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- <div class="box-header with-border">
                                <h3 class="box-title">Complete el formulario</h3>
                            </div> -->
                            <!-- /.box-header -->
                            <!-- Modal -->
                            <div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            </div>
                            <!-- form start -->
                            <div class="box-body">
                                <!-- <form role="form" id="formulario" action="insert.php" method="POST"> -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <?php
                                                $consulta = "SELECT fecha_hasta_cie FROM cierre_cierre ORDER BY id_cie DESC";
                                                $conexion->consulta_form($consulta,array());
                                                $total_consulta = $conexion->total();
                                                if ($total_consulta > 0) {
                                                    $fila = $conexion->extraer_registro_unico();
                                                    $fecha_hasta_cie = $fila['fecha_hasta_cie'];
                                                    $fecha_hasta_cie = date('Y-m-d H:i:s',strtotime ( '+1 day' , strtotime ($fecha_hasta_cie)));
                                                    $fecha_hasta_cie = date("d-m-Y",strtotime($fecha_hasta_cie));
                                                }
                                                else{
                                                    $fecha_hasta_cie = "";
                                                }
                                                ?>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fecha_desde">Fecha Desde:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="fecha_desde" id="fecha_desde" value="<?php echo $fecha_hasta_cie;?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label for="fecha_hasta">Fecha Hasta:</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                                            <input type="text" class="form-control chico pull-right datepicker" name="fecha_hasta" id="fecha_hasta">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="mes">Mes:</label>
                                                    <select class="form-control select2" id="mes" name="mes"> 
                                                        <option value="">Elegir Mes</option>
                                                        <?php  
                                                        $consulta = "SELECT * FROM mes_mes ORDER BY id_mes";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta)){
                                                            foreach ($fila_consulta as $fila) {
                                                                ?>
                                                                <option value="<?php echo $fila['id_mes'];?>"><?php echo utf8_encode($fila['nombre_mes']);?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="anio">Año:</label> 
                                                    <select name="anio" id="anio" class="form-control select2"> 
                                                        <option value="">Elegir Año</option> 
                                                        <?php
                                                        for ($i=date("Y")-1; $i <= date("Y")+1; $i++) {
                                                            if ($i == date("Y")) {
                                                                $clase_select = "selected";
                                                            }
                                                            else{
                                                                $clase_select = "";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $i;?>" <?php echo $clase_select;?>><?php echo $i;?></option>
                                                        <?php
                                                        }
                                                        
                                                        ?> 
                                                    </select>
                                                </div>
                                                
                                                <div class="col-sm-1" style="padding-top: 20px">
                                                    <button type="button" class="btn btn-primary pull-right" id="procesar">Procesar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="contenedor_liquidacion">
                                    	<!-- procesa -->
                                    	
                                    	<!-- fin -->
                                    </div>
                            </div>
                            
                        </div>
                      <!-- /.box -->
                    </div>
                    <!--/.col (left) -->
                </div>
            <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>

<!-- DatePicker -->
<!-- <script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script> -->

<!-- date-range-picker -->
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>

<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>



<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            language: 'es',
            autoclose: true
        });

        $(document).on( "click","#procesar" , function() {
        	$('#contenedor_liquidacion').html('<div style="width: 100%; min-height: 40vh; text-align: center; padding-top: 50px"><img src="../../assets/img/loading.gif"></div>');
            var_fecha_desde = $('#fecha_desde').val();
            var_fecha_hasta = $('#fecha_hasta').val();
            var_mes = $('#mes').val();
            var_anio = $('#anio').val();

            if(var_fecha_desde != null && var_fecha_desde != '' && var_fecha_hasta != null && var_fecha_hasta != '' && var_mes != null && var_mes != '' &&  var_anio != null && var_anio != '' ){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_liquidacion.php"),
                    data:"fecha_desde="+var_fecha_desde+"&fecha_hasta="+var_fecha_hasta+"&mes="+var_mes+"&anio="+var_anio,
                    success: function(data) {
                        $('#contenedor_liquidacion').html(data);
                    }       
                })  
            }
            else{
                swal("Atención!", "Falta información para procesar liquidación", "warning");
            }            
        });               
        $(document).on( "click","#guarda" , function() {
            swal({
                title: "Está Seguro?",
                text: "Desea cerrar el mes seleccionado!",
                icon: "warning"                        
                
            }).then(()=>{
                $.ajax({
                    type: 'POST',
                    url: ("insert.php"),                    
                    dataType: 'json',                    
                    success: function (response) {                      
                            resultado(response);                        
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {                    
                        alert('Error de sistema : '+textStatus+' favor contactar con el administrador de sistemas.');
                    }       
                })
            });
            
            
        });
        
        
        $('.numero').numeric();
        $(function () {
            
            $(".select2").select2();
        });


        function resultado(data) {
            if (data.envio == 1) {

                swal({
                    title: "Excelente!",
                    text: "Cierre de mes realizado correctamente",
                    icon: "success"
                    
                }).then(()=>location.href = "form_select.php");
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar nuevamente o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
        }


    });

</script>

<!-- iCheck 1.0.1 -->
<script src="<?php echo _ASSETS?>plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    // $('input[type="radio"].minimal').iCheck({
    //   radioClass: 'iradio_flat-blue'
    // });
</script>
</body>
</html>
