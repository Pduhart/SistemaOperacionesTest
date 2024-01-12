<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_cotizacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
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
                    Cotización
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Cotización</a></li>
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
                                        <div class="col-sm-5 col-sm-offset-4">
                                            <h4>Búsqueda o Ingreso de Cliente</h4>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="propietario">Cliente:</label>
                                                <select class="form-control select2" id="propietario" name="propietario"> 
                                                    <option value="">Buscar Cliente</option>
                                                    <?php
                                                    //VENDEDOR
                                                    if($_SESSION["sesion_perfil_panel"] == 4){

                                                        $consulta = "SELECT id_vend FROM vendedor_vendedor WHERE id_usu = ?";
                                                        $conexion->consulta_form($consulta,array($_SESSION["sesion_id_panel"]));
                                                        $fila = $conexion->extraer_registro_unico();
                                                        $id_vend = $fila["id_vend"];
                                                        
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
                                                                pro.nombre_pro
                                                            ";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta)){
                                                            foreach ($fila_consulta as $fila) {
                                                                ?>
                                                                <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']." / ".$fila['rut_pro']);?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    /*$consulta = "SELECT id_pro,nombre_pro,apellido_paterno_pro,apellido_materno_pro,rut_pro FROM propietario_propietario WHERE id_est_pro = 1 ORDER BY nombre_pro";
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            ?>
                                                            <option value="<?php echo $fila['id_pro'];?>"><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']." / ".$fila['rut_pro']);?></option>
                                                            <?php
                                                        }
                                                    }*/
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-8" id="contenedor_propietario">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                	<label for="rut">Rut:</label>
                                                	<div class="input-group input-group-sm">
										                <input type="text" name="rut" class="form-control rut" id="rut"/>
									                    <span class="input-group-btn">
									                      <button type="button" id="revisarut" class="btn btn-info btn-flat">Revisar</button>
									                    </span>
										            </div>
                                                    <!-- <label for="rut">Rut:</label> -->
                                                    <!-- <input type="text" name="rut" class="form-control rut" id="rut"/> -->
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="nombre">Primer Nombre:</label>
                                                    <input type="text" name="nombre" class="form-control" id="nombre"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="segundo_nombre">Segundo Nombre:</label>
                                                    <input type="text" name="segundo_nombre" class="form-control" id="segundo_nombre"/>
                                                </div>
                                                
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="apellido_paterno">Apellido Paterno:</label>
                                                    <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="apellido_materno">Apellido Materno:</label>
                                                    <input type="text" name="apellido_materno" class="form-control" id="apellido_materno"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                            	<div class="form-group">
	                                                <label for="nacionalidad">Nacionalidad:</label>
	                                                <select class="form-control select2" id="nacionalidad" name="nacionalidad"> 
	                                                    <option value="">Seleccione Nacionalidad</option>
	                                                    <?php  
	                                                    $consulta = "SELECT * FROM nacionalidad_nacionalidad ORDER BY nombre_nac";
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
	                                                    <option value="">Seleccione Región</option>
	                                                    <?php  
	                                                    $consulta = "SELECT * FROM region_region ORDER BY descripcion_reg";
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
	                                                    <option value="">Seleccione Comuna</option>
	                                                </select>
	                                            </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="fono">Fono:</label>
                                                    <input type="text" name="fono" class="form-control numero" id="fono"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="correo">Correo:</label>
                                                    <input type="text" name="correo" class="form-control" id="correo"/>
                                                </div>
                                                
                                            </div>
                                            <div class="col-sm-3">
                                            	<div class="form-group">
	                                                <label for="profesion">Profesión:</label>
	                                                <select class="form-control select2" id="profesion" name="profesion"> 
	                                                    <option value="">Seleccione Profesión</option>
	                                                    <?php  
	                                                    $consulta = "SELECT * FROM profesion_profesion ORDER BY nombre_prof";
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
	                                                <input type="text" name="direccion_trabajo" class="form-control" id="direccion_trabajo"/>
	                                            </div>
                                            </div>
                                            <div class="col-sm-3">
                                            	<div class="form-group">
	                                                <label for="sexo">Sexo:</label>
	                                                <select class="form-control" id="sexo" name="sexo"> 
	                                                    <option value="">Seleccione Sexo</option>
	                                                    <?php  
	                                                    $consulta = "SELECT * FROM sexo_sexo ORDER BY nombre_sex";
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
                                                        <option value="">Seleccione Condominio</option>
                                                        <?php  
                                                        $consulta = "SELECT * FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
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
                                                    <option value="">Seleccione Torre</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="modelo">Modelo:</label>
                                                    <select class="form-control select2" id="modelo" name="modelo"> 
                                                        <option value="">Seleccione Modelo</option>                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="vivienda">Departamento:</label>
                                                    <select class="form-control select2" id="vivienda" name="vivienda"> 
                                                        <option value="">Seleccione Departamento</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="canal">Canal:</label>
                                                    <select class="form-control" id="canal" name="canal"> 
                                                        <option value="">Seleccione Canal</option>
                                                        <?php  
                                                        $consulta = "SELECT * FROM cotizacion_canal_cotizacion WHERE id_can_cot IN (1,2,3,4,8,11,12,14,15,17) ORDER BY id_can_cot";
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
                                                    <label for="preaprobacion">Pre aprobación de Crédito:</label>
                                                    <select class="form-control" id="preaprobacion" name="preaprobacion"> 
                                                        <option value="">Seleccione</option>
                                                        <?php  
                                                        $consulta = "SELECT * FROM cotizacion_preaprobacion_cotizacion ORDER BY id_pre_cot";
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
							                            <option value="">Seleccione Nivel de Interés</option>
							                            <?php  
							                            $consulta = "SELECT * FROM cotizacion_seguimiento_interes_cotizacion ORDER BY id_seg_int_cot DESC";
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
						                            <input type="text" name="fecha_cot" autocomplete="off" class="form-control datepicker elemento" id="fecha_cot"/>
						                        </div>
						                    </div>
						                    <div class="col-sm-3">
                                            	<div class="form-group">
							                        <label for="renta">Renta Aproximada:</label>
							                        <select class="form-control" id="renta" name="renta"> 
							                            <option value="">Seleccione Renta</option>
							                            <?php  
							                            $consulta = "SELECT * FROM cotizacion_renta_cotizacion ORDER BY id_ren_cot ASC";
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
						                            <input type="text" name="porcentaje_credito" class="form-control" id="porcentaje_credito"/>
						                        </div>
				                            </div>
                                            <div class="col-sm-6" id="contenedor_info_vivienda"></div>

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
<script src="<?php echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.rut.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.numero').numeric();
        $.validator.addMethod("rut", function(value, element) {
            return this.optional(element) || $.Rut.validar(value);
        }, "Rut invalido.");

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            // startDate: '-0d',
            todayHighlight: true,
            language: 'es',
            autoclose: true
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
                porcentaje_credito: {
                	required: true
                },
                // region: {
                //     required: true
                // },
                // comuna: {
                //     required: true
                // },
                // nacionalidad: {
                //     required: true
                // },
                sexo: {
                    required: true
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
                // direccion_trabajo: { 
                //     required: true
                // },
                departamento: { 
                    required: true
                },
                modelo: { 
                    required: true
                },
                vivienda: { 
                    required: true
                },
                canal: { 
                    required: true
                },
                fecha_cot: { 
                    required: true
                },
                preaprobacion: { 
                    required: true
                },
                interes: { 
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
                porcentaje_credito: {
                    required: "Seleccione Porcentaje"
                },
                // nacionalidad: {
                //     required: "Seleccione nacionalidad"
                // },
                // region: {
                //     required: "Seleccione Región"
                // },
                // comuna: {
                //     required: "Seleccione comuna"
                // },
                // direccion_trabajo: {
                //     required: "Ingrese Lugar de trabajo"
                // },
                sexo: {
                    required: "Seleccione sexo"
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
                departamento: {
                    required: "Seleccione departamento"
                },
                modelo: {
                    required: "Seleccione modelo"
                },
                vivienda: {
                    required: "Seleccione Depto"
                },
                canal: {
                    required: "Ingrese canal"
                },
                fecha_cot: {
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

        $('#rut').Rut({
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
            else{
                $('#torre').html("Seleccione Torre");
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
                         $('#modelo').html(data);
                    }
                })
            }
            else{
                $('#vivienda').html("Seleccione Departamento");
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
                    text: "Información ingresada con éxito!",
                    icon: "success"                                  
                }).then(()=>location.href = "form_select.php");
            }
            if (data.envio == 2) {
                swal("Atención!", "Cotización ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 4) {
                swal("Atención!", "Cliente esta asignado a otro vendedor", "warning");
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


        // revisar rut
		function resultadorut(data) {
            if (data.envio == 5) {
                swal("Atención!", "Cliente no existe en el sistema", "success");
            }
            if (data.envio == 6) {
                swal("Atención!", "Cliente ya está en el sistema, pero no está asignado a Vendedor", "info");
            }
            if (data.envio == 7) {
                swal("Atención!", "Cliente esta asignado a otro vendedor - "+data.name+"", "info");
            }
            if (data.envio == 8) {
                swal("Atención!", "Cliente ya está en el sistema, y está asociado a USTED", "success");
            }
        }

        $(document).on( "click","#revisarut" , function() {
            valor = $("#rut").val();
            // alert(valor);
            if(valor != ""){
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ("procesa_rut.php"),
                    data:"valor="+valor,
                    success: function (data) {
                        resultadorut(data);
                    }
                })
            }
        });
    });
</script>


</body>
</html>
