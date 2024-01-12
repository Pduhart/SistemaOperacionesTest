<?php 
session_start(); 
date_default_timezone_set('America/Santiago');
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_uf_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
$_SESSION['sesion_anio_uf_panel'] = date("Y");
?>
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/daterangepicker/daterangepicker-bs3.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/bootstrap3-editable/css/bootstrap-editable.css">
<style type="text/css">
    .btn.focus, .btn:focus, .btn:hover {
        color: #333;
        font-weight: 700;
        text-decoration: none;
        -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
        box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
    }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    UF
                    <small>Cuadro Edición</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">UF</a></li>
                    <li class="active">Modificación</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"></h3>
                                <?php 
                                // $actual = $_SESSION["sesion_periodo_filtro_panel"];
                                $actual = date("Y");
                                $anterior = $actual-1;
                                $futuro = $actual+1;

                                if(isset($_SESSION['sesion_anio_uf_panel'])){
                                    $seleccion = $_SESSION['sesion_anio_uf_panel'];
                                }
                                else{
                                    $seleccion = $actual;
                                }
                                ?>
                                <div class="box-tools pull-right">
                                    <?php  
                                    if($seleccion == $anterior){
                                        $estilo_anterior = 'style="font-weight: bold; color: #000;"';
                                        $estilo_actual = '';
                                        $estilo_futuro = '';
                                    }
                                    else if($seleccion == $actual){
                                        $estilo_anterior = '';
                                        $estilo_actual = 'style="font-weight: bold; color: #000;"';
                                        $estilo_futuro = '';
                                    } else {
                                    	$estilo_anterior = '';
                                        $estilo_actual = '';
                                        $estilo_futuro = 'style="font-weight: bold; color: #000;"';
                                    }
                                    ?>
                                    <button type="button" <?php echo $estilo_anterior;?> value="<?php echo $anterior ?>" class="btn btn-box-tool boton"><?php echo $anterior ?></button>
                                    <button type="button" <?php echo $estilo_actual;?> value="<?php echo $actual ?>" class="btn btn-box-tool boton"><?php echo $actual ?></button>
                                    <button type="button" <?php echo $estilo_futuro;?> value="<?php echo $futuro ?>" class="btn btn-box-tool boton"><?php echo $futuro ?></button>
                                </div>
                            </div>
                            <div class="table-responsive" id="contenedor">
                                
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php 
                                            if(isset($_SESSION['sesion_anio_uf_panel'])){
                                               $actual = $_SESSION["sesion_anio_uf_panel"];
                                               unset($_SESSION["sesion_anio_uf_panel"]); 
                                            }
                                            $fila_consulta_uf = array();
                                            $consulta = "SELECT * FROM uf_uf WHERE YEAR(fecha_uf)=".$actual."";
                                            $conexion->consulta($consulta);
                                            $fila_consulta_uf_original = $conexion->extraer_registro();
                                            if(is_array($fila_consulta_uf_original)){
                                                $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_uf_original));
                                                
                                                foreach($it as $v) {
                                                    $fila_consulta_uf[]=$v;
                                                }
                                            }
                                            $consulta = "SELECT * FROM mes_mes ORDER BY id_mes ASC";
                                            $conexion->consulta($consulta);
                                            $fila_consulta = $conexion->extraer_registro();
                                            if(is_array($fila_consulta)){
                                                foreach ($fila_consulta as $fila) {
                                                    $nombre_mes = utf8_encode($fila['nombre_mes']);
                                                    $id_mes = $fila['id_mes'];
                                             ?>
                                            <th><?php echo $nombre_mes ?></th>
                                            <?php }
                                            } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        for ($i=1; $i <= 31; $i++) { 
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <?php 
                                                if(is_array($fila_consulta)){
                                                    foreach ($fila_consulta as $fila) {
                                                        $id_mes = $fila['id_mes'];
                                                        if($id_mes < 10){
                                                            $mes_fecha = "0".$id_mes;
                                                        }
                                                        else{
                                                            $mes_fecha = $id_mes;
                                                        }

                                                        if($i < 10){
                                                            $dia_fecha = "0".$i;
                                                        }
                                                        else{
                                                            $dia_fecha = $i;
                                                        }
                                                        $fecha_uf_org = $dia_fecha."-".$mes_fecha."-".$actual;

                                                        // echo "sin convertir: ".$fecha_uf."------------";

                                                        $fecha_uf = date("Y-m-d",strtotime($fecha_uf_org));

                                                        // echo $fecha_uf."<br>";


                                                        if(in_array($fecha_uf,$fila_consulta_uf)){
                                                        	// echo $fecha_uf."<------------".print_r($fila_consulta_uf)."<br><br><br>";
                                                        	if(!checkdate($mes_fecha, $dia_fecha, $actual)) {
	                                                        	$valor_uf = 0; 
	                                                        } else {
	                                                        		$key = array_search($fecha_uf, $fila_consulta_uf);
	                                                            	$valor_uf = $fila_consulta_uf[$key + 1];
	                                                        	}
                                                        }
                                                        else{
                                                            $valor_uf = 0; 
                                                        }
                                                       
                                                        
                                                        // if(is_array($fila_consulta_uf)){
                                                        //     foreach ($fila_consulta_uf as $fila_uf) {
                                                        //         $dia_fecha = date("j",strtotime($fila_uf["fecha_uf"]));
                                                        //         $mes_fecha = date("j",strtotime($fila_uf["n"]));
                                                        //         if($dia_fecha == $i && $mes_fecha == $id_mes){
                                                        //             $valor_uf = $fila_uf["valor_uf"];
                                                        //             break;
                                                        //         }
                                                        //         else{
                                                        //             $valor_uf = 0;
                                                        //         }
                                                        //     }
                                                        // }
                                                        // else{
                                                        //     $valor_uf = 0;
                                                        // }
                                                        ?>
                                                        <td class="edita"><a href="#" id="<?php echo $i."-".$id_mes; ?>" data-type="text" data-pk="<?php echo $fecha_uf;?>" data-value="<?php echo $valor_uf;?>" title="Ingrese Valor Uf"><?php echo number_format($valor_uf, 2, ',', '.');?></a></td>
                                                        <?php
                                                    }
                                                } 
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
<script src="<?php echo _ASSETS?>plugins/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script type="text/javascript">
    $(function(){
        // $.fn.editable.defaults.mode = 'inline';   
        $('.edita a').editable({
            url: 'update.php',
            success: function(data) {
                location.reload();
                
            }
            
        });

        // cambio de año   
        $('.boton').click(function () {
            // alert("hola");
            $(".boton").attr("style","");
            $(this).attr("style","font-weight: bold; color: #000;");
            var valor = $(this).val();
            $.ajax({
                    data: 'valor='+valor,
                    type: 'POST',
                    url: 'procesa.php',
                    success: function (data) {
                        $('#contenedor').html(data);
                    }
                })
        });
        
    });
</script>
</body>
</html>
