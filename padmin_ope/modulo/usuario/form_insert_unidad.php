<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_usuario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
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
                    Usuarios
                    <small>Asignar Unidad / Proyecto</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Usuarios</a></li>
                    <li class="active">Unidad / Proyecto</li>
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
                            <!-- form start -->
                            <form id="formulario" method="POST" action="insert.php" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-1 control-label">Perfil:</label>
                                                <select class="form-control" id="perfil_usu" name="perfil_usu" data-fv-field="perfil_usu"> 
                                                    <option value="0">Seleccione Perfil</option>
                                                    <?php
                                                    $consulta="SELECT * FROM usuario_perfil ORDER BY nombre_per ASC";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            $nombre_per = utf8_encode($fila['nombre_per']);
                                                            $id_per = $fila['id_per'];
                                                            ?>
                                                            <option value="<?php echo $id_per;?>"><?php echo $nombre_per;?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-1 control-label" for="selectMulti">Usuarios</label>
                                                
                                                <select class="form-control" multiple="" id="usuario_usu" name="usuario_usu" style=" height:280px;">
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-1 control-label" for="selectMulti">Unidades</label>
                                                
                                                <select class="form-control" id="unidad_usu" name="unidad_usu" multiple="" style=" height:230px;">
                                                </select>
                                            </div>  
                                        </div>
                                        <div class="col-sm-6">
                                            <div id="contenedor_modulo_usuario" class="box box-solid no-border margin-bottom-40">

                                            </div>
                                            <div id="contenedor_proceso_modulo" class="box box-solid no-border">

                                            </div>
                                            <div class="form-group" id="contenedor_proyecto_usuario">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer" id="contenedor_boton">
                                    <!-- <button type="submit" class="btn btn-primary pull-right">Registrar</button> -->
                                </div>
                            </form>
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

<script type="text/javascript">
    $(document).ready(function(){
       
        
        $(document).on( "click",".unidad_usuario" , function() {
            var_unidad = $(this).val();
            if($(this).prop('checked') ) {
                var_seleccion = 1;  
            }
            else{
                var_seleccion = 0;
            }
            var_usuario_usu = $('#usuario_usu').val();
            if(var_usuario_usu != null && var_usuario_usu != '' && var_unidad != null && var_unidad != ''){
                $.ajax({
                    type: 'POST',
                    url: ("insert_unidad.php"),
                    data:"usuario_usu="+var_usuario_usu+"&unidad_usu="+var_unidad+"&seleccion_usu="+var_seleccion,
                    success: function(data) {
                        //$('#contenedor_modulo_usuario').html(data);
                    }
                })

                $.ajax({
                    type: 'POST',
                    url: ("select_unidad.php"),
                    data:"usuario_usu="+var_usuario_usu,
                    success: function(data) {
                        $('#unidad_usu').html(data);
                    }
                })
            }   
            $('#contenedor_proceso_modulo').html('');
        });

        $(document).on( "click",".proyecto_usuario" , function() {
            var_proyecto = $(this).val();

            if($(this).prop('checked') ) {
                var_seleccion = 1;  
            }
            else{
                var_seleccion = 0;
            }
            var_usuario_usu = $('#usuario_usu').val();
            var_unidad_usu = $('#unidad_usu').val();
            if(var_usuario_usu != null && var_usuario_usu != '' && var_unidad_usu != null && var_unidad_usu != ''){
                $.ajax({
                    type: 'POST',
                    url: ("insert_proyecto.php"),
                    dataType: 'json',
                    data:"usuario_usu="+var_usuario_usu+"&proyecto_usu="+var_proyecto+"&seleccion_usu="+var_seleccion+"&unidad_usu="+var_unidad_usu,
                    success: function(data) {
                        //alert(data.error_consulta);
                        //$('#contenedor_modulo_usuario').html(data);
                    }
                })
            }
        });

        $('#perfil_usu').change(function(){
            var_perfil_usu = $('#perfil_usu').val();
            $.ajax({
                type: 'POST',
                url: ("select_perfil.php"),
                data:"perfil_usu="+var_perfil_usu,
                success: function(data) {
                    $('#usuario_usu').html(data);
                }
            })
            $('#unidad_usu option').remove();
            $('#contenedor_modulo_usuario').html('');
            $('#contenedor_proceso_modulo').html('');
            $('#contenedor_proceso_modulo').html('');
        });

        $('#usuario_usu').click(function(){
            var_usuario_usu = $('#usuario_usu').val();
            if(var_usuario_usu != null && var_usuario_usu != ''){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_unidad.php"),
                    data:"usuario_usu="+var_usuario_usu,
                    success: function(data) {
                        $('#contenedor_modulo_usuario').html(data);
                        $.ajax({
                            type: 'POST',
                            url: ("select_unidad.php"),
                            data:"usuario_usu="+var_usuario_usu,
                            success: function(data) {
                                $('#unidad_usu').html(data);
                            }
                        })
                    }
                })
                
                $('#contenedor_proceso_modulo').html('');
            }
            
        });
        $(document).on( "click","#unidad_usu" , function() {
            var_usuario_usu = $('#usuario_usu').val();
            var_unidad_usu = $('#unidad_usu').val();
            if(var_usuario_usu != null && var_usuario_usu != '' && var_unidad_usu != null && var_unidad_usu != ''){
                $.ajax({
                    type: 'POST',
                    url: ("procesa_proyecto.php"),
                    data:"usuario_usu="+var_usuario_usu+"&unidad_usu="+var_unidad_usu,
                    success: function(data) {
                        $('#contenedor_proceso_modulo').html(data);
                    }
                })
            }
        });
    });
</script>


</body>
</html>
