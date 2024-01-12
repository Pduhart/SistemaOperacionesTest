<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_etapa_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
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

.entry:not(:first-of-type)
{
    margin-top: 10px;
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
                    Etapa
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Etapa</a></li>
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
                            <!-- form start -->
                            <form id="formulario" method="POST" action="insert.php" role="form">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="categoria">Categoría:</label>
                                                <select class="form-control" id="categoria" name="categoria"> 
                                                    <option value="">Seleccione Categoría</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM etapa_categoria_etapa ORDER BY nombre_cat_eta";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_cat_eta'];?>"><?php echo utf8_encode($fila['nombre_cat_eta']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Nombre:</label>
                                                <input type="text" name="nombre" class="form-control" id="nombre"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="alias">Alias:</label>
                                                <input type="text" name="alias" class="form-control" id="alias"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="numero">Número:</label>
                                                <input type="text" name="numero" class="form-control numero" id="numero"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="forma_pago">Forma de Pago:</label>
                                                <select class="form-control" id="forma_pago" name="forma_pago"> 
                                                    <option value="">Seleccione Forma de Pago</option>
                                                    <?php  
                                                    $consulta = "SELECT * FROM pago_forma_pago WHERE id_for_pag <= 2 ORDER BY nombre_for_pag";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_for_pag'];?>"><?php echo utf8_encode($fila['nombre_for_pag']);?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="duracion">Duración (Días):</label>
                                                <input type="text" name="duracion" class="form-control numero" id="duracion"/>
                                            </div>

                                        </div>
                                        <div class="col-sm-12">
                                        	<h4 class="text-center">Campos Adicionales</h4>
                                        </div>
                                        <div class="col-sm-4 controls_num">
                                        	<div style="width: 100%">
                                        		<label>Campos Numéricos</label>
	                                        	<div class="entry input-group">
							                        <input class="form-control addnum" name="campo_numero[]" type="text" placeholder="nombre de campo numérico" />
							                    	<span class="input-group-btn">
							                            <button class="btn btn-success btn-add-num" type="button">
							                                <span class="glyphicon glyphicon-plus"></span>
							                            </button>
							                        </span>
							                    </div>
							                </div>
                                        </div>
                                        <div class="col-sm-4 controls_fec">
                                        	<div style="width: 100%">
                                        		<label>Campos de Fecha</label>
	                                        	<div class="entry input-group">
							                        <input class="form-control addfec" name="campo_fecha[]" type="text" placeholder="nombre de campo fecha" />
							                    	<span class="input-group-btn">
							                            <button class="btn btn-success btn-add-fec" type="button">
							                                <span class="glyphicon glyphicon-plus"></span>
							                            </button>
							                        </span>
							                    </div>
							                </div>
                                        </div>
                                        <div class="col-sm-4 controls_tex">
                                        	<div style="width: 100%">
                                        		<label>Campos de Texto</label>
	                                        	<div class="entry input-group">
							                        <input class="form-control addtex" name="campo_texto[]" type="text" placeholder="nombre de campo texto" />
							                    	<span class="input-group-btn">
							                            <button class="btn btn-success btn-add-tex" type="button">
							                                <span class="glyphicon glyphicon-plus"></span>
							                            </button>
							                        </span>
							                    </div>
							                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer" id="contenedor_boton">
                                    <button type="submit" class="btn btn-primary pull-right">Registrar</button>
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

<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	// add de campo numérico
		$(document).on('click', '.btn-add-num', function(e)
	    {
	    	// alert("dsfsd");
	        e.preventDefault();
	        var controlForm = $('.controls_num div:first'),
	            currentEntry = $(this).parents('.entry:first'),
	            newEntry = $(currentEntry.clone()).appendTo(controlForm);

	        newEntry.find('input.addnum').val('');
	        controlForm.find('.entry:not(:last) .btn-add-num')
	            .removeClass('btn-add-num').addClass('btn-remove-num')
	            .removeClass('btn-success').addClass('btn-danger')
	            .html('<span class="glyphicon glyphicon-minus"></span>');
	    }).on('click', '.btn-remove-num', function(e)
	    {
			$(this).parents('.entry:first').remove();
			e.preventDefault();
			return false;
		});
		// add de campo fecha
		$(document).on('click', '.btn-add-fec', function(e)
	    {
	    	// alert("dsfsd");
	        e.preventDefault();
	        var controlForm = $('.controls_fec div:first'),
	            currentEntry = $(this).parents('.entry:first'),
	            newEntry = $(currentEntry.clone()).appendTo(controlForm);

	        newEntry.find('input.addfec').val('');
	        controlForm.find('.entry:not(:last) .btn-add-fec')
	            .removeClass('btn-add-fec').addClass('btn-remove-fec')
	            .removeClass('btn-success').addClass('btn-danger')
	            .html('<span class="glyphicon glyphicon-minus"></span>');
	    }).on('click', '.btn-remove-fec', function(e)
	    {
			$(this).parents('.entry:first').remove();
			e.preventDefault();
			return false;
		});
		// add de campo texto
		$(document).on('click', '.btn-add-tex', function(e)
	    {
	    	// alert("dsfsd");
	        e.preventDefault();
	        var controlForm = $('.controls_tex div:first'),
	            currentEntry = $(this).parents('.entry:first'),
	            newEntry = $(currentEntry.clone()).appendTo(controlForm);

	        newEntry.find('input.addtex').val('');
	        controlForm.find('.entry:not(:last) .btn-add-tex')
	            .removeClass('btn-add-tex').addClass('btn-remove-tex')
	            .removeClass('btn-success').addClass('btn-danger')
	            .html('<span class="glyphicon glyphicon-minus"></span>');
	    }).on('click', '.btn-remove-tex', function(e)
	    {
			$(this).parents('.entry:first').remove();
			e.preventDefault();
			return false;
		});

        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                categoria: { 
                    required: true
                },
                forma_pago: { 
                    required: true
                },
                nombre: { 
                    required: true,
                    minlength: 3
                },
                alias: { 
                    required: true
                },
                numero: { 
                    required: true
                },
                duracion: { 
                    required: true
                }
            },
            messages: { 
                categoria: {
                    required: "Seleccione categoría"
                },
                forma_pago: {
                    required: "Seleccione forma de pago"
                },
                nombre: {
                    required: "Ingrese nombre",
                    minlength: "Mínimo 3 caracteres"
                },
                alias: {
                    required: "Ingrese nombre"
                },
                numero: {
                    required: "Ingrese número"
                },
                duracion: {
                    required: "Ingrese duración"
                }
            }
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        
        
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
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
                swal("Atención!", "Etapa ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //     alert(data.envio);
            // }
        }
        $('#formulario').submit(function () {
            if ($("#formulario").validate().form() == true){
                $('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
                var dataString = $('#formulario').serialize();
                // $.ajax({
                //     data: dataString,
                //     type: 'POST',
                //     url: $(this).attr('action'),
                //     dataType: 'json',
                //     success: function (data) {
                //         resultado(data);
                //     }
                // })
            }
            
            //return false;
        });
    });
</script>


</body>
</html>
