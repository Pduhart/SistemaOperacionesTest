<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
require_once _INCLUDE."head.php";
?>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<!-- siempre al final los ajustes -->
<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">
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

    table#example{
        width: 160% !important;
    }

    .wmd-view-topscroll, .wmd-view {
        overflow-x: scroll;
        overflow-y: hidden;
        width: 100%;
        border: none 0px RED;
    }

    .wmd-view-topscroll { height: 20px; }
    .wmd-view { height: 100%; }
    .scroll-div1 { 
        width: 160%; 
        overflow-x: scroll;
        overflow-y: hidden;
        height:20px;
    }
    .scroll-div2 { 
        width: 3000px; 
        height:20px;
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
        <!-- Modal Ver -->
        <!-- Modal -->
        <div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        </div>

        <!-- Fin modal -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Cliente
                    <small>Listado</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Cliente</a></li>
                    <li class="active">Listado</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                        <div id="contenedor_opcion"></div>
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <!-- <div class="box-header with-border">
                                <h3 class="box-title">Complete el formulario</h3>
                            </div> -->
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                            	<?php 
                                 if ($_SESSION["sesion_perfil_panel"]==4) {
								?>
                            	<form class="form-inline" style="margin-bottom: 20px">
                            		<div class="checkbox" style="margin-right: 20px">
									    <label>
									      <input type="checkbox" name="check_todo_mail" id="check_todo_mail"> Seleccionar Visibles para Envío
									    </label>
									</div>
                            		<button class="btn btn-primary mails_checks" type="button">Generar Campaña</button>
                            	</form>
                            	<?php } ?>
                            	<!-- Método para limpiar Tabla sesión -->
                            	<div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="region">Región:</label>
                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                        <select class="form-control chico" id="region" name="region"> 
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
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="comuna">Comuna:</label>
                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                        <select class="form-control chico" id="comuna" name="comuna"> 
                                            <option value="">Seleccione Comuna</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            	<div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="sexo">Género:</label>
                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                        <select class="form-control chico" id="sexo" name="sexo"> 
                                            <option value="">Seleccione Género</option>
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
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="profesion">Profesión:</label>
                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                        <select class="form-control chico" id="profesion" name="profesion"> 
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
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="condepto">Con Depto:</label>
                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                        <select class="form-control chico" id="condepto" name="condepto"> 
                                            <option value="">Seleccione</option>
                                            <option value="1">Con Depto</option>
                                            <option value="2">Sin Depto</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center" style="padding-top: 20px">
                                  <div class="form-group filtrar">
                                      <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
                                  </div>
                                </div>
                                <div class="col-sm-2" style="border-left: 1px solid #cccccc;">
                                	
                                	<h6 style="font-style: italic; color:#ccc; font-size: 13px">
                                    Filtro: 
                                    	<?php 
                                    	if(isset($_SESSION["id_sexo_prop_filtro"])){
                                    	?>
                                    		<span class="label label-primary">Sexo</span>
                                    	<?php
                                    	}
                                    	?>
                                    	<?php 
                                    	if(isset($_SESSION["id_prof_prop_filtro"])){
                                    	?>
                                    		<span class="label label-primary">Profesión</span>
                                    	<?php
                                    	}
                                    	?>
                                    	<?php 
                                    	if(isset($_SESSION["id_condepto_prop_filtro"])){
                                    		if ($_SESSION["id_condepto_prop_filtro"]==1) {
                                    		?>
                                    		<span class="label label-primary">Con Depto</span>	
                                    		<?php
                                    		} else {
                                    		?>
                                    		<span class="label label-primary">Sin Depto</span>	
                                    		<?php
                                    		}
                                    	?>
                                    	<?php
                                    	}
                                    	?>
                                    	<?php 
                                    	if(isset($_SESSION["id_reg_prop_filtro"])){
                                    	?>
                                    		<span class="label label-primary">Región</span>
                                    	<?php
                                    	}
                                    	?>
                                    	<?php 
                                    	if(isset($_SESSION["id_com_prop_filtro"])){
                                    	?>
                                    		<span class="label label-primary">Comuna</span>
                                    	<?php
                                    	}
                                    	?>
                                	</h6>
                                	<button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button>
                                </div>
                                <?php 
                                if ($_SESSION["sesion_perfil_panel"]==1 || $_SESSION["sesion_perfil_panel"]==2) {
                                 ?>
                                <a href="listado_cliente_exc.php" target="_blank" class="btn btn-sm btn-info">Exportar Listado Completo a Excel</a>
                                 <?php 
                                }
                                ?>
                            	<div class="wmd-view-topscroll">
								    <div class="scroll-div1">
								    </div>
								</div>
                                <div id="deny_copy" class="table-responsive wmd-view">
                                  <table id="example" class="table scroll-div2 table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width:1%">
                                            	<?php 
                                                if ($_SESSION["sesion_perfil_panel"]==1) {
                                                 ?>
                                                <input type="checkbox" name="check_todo" id="check_todo">
                                                <label for="check_todo"><span></span></label>
                                                 <?php } ?>
                                                 <?php 
                                                 if ($_SESSION["sesion_perfil_panel"]==4) {
													?>
												<!-- <button type="button" class="btn btn-xs btn-icon btn-info observa_checks" title="Observar Seleccionados"><i class="fa fa-plus"></i></button> -->
													<?php
                                                 }
                                                  ?>
                                            </th>
                                            <th>Nombre</th>
                                            <th>N° Depto.</th>
                                            <th>Rut</th>
                                            <th>Fono</th>
                                            <th>Dirección</th>
                                            <th>Correo</th>
                                            <th>Canal</th>
                                            <th>Profesión</th>
                                            <th>Género</th>                                            
                                            <th>Región</th>
                                            <th>Comuna</th>
                                            <th>Estado</th>
                                            <th style="width:5%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>
                                                <?php if ($_SESSION["sesion_perfil_panel"]==1):?>
                                                <button type="button" class="btn btn-xs btn-icon btn-danger borra_todo" data-toggle="tooltip" data-original-title="Eliminar Seleccionados"><i class="fa fa-trash"></i></button>
                                                <?php endif;?>
                                            </th>
                                            <th>Nombre</th>
                                            <th>N° Depto.</th>
                                            <th>Rut</th>
                                            <th>Fono</th>
                                            <th>Dirección</th>
                                            <th>Correo</th>
                                            <th>Canal</th>
                                            <th>Profesión</th>
                                            <th>Género</th>                                            
                                            <th>Región</th>
                                            <th>Comuna</th>
                                            <th>Estado</th>
                                            <th style="width:5%">Acción</th>
                                            
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.box-body -->
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

<!-- DataTables -->
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/pagination/select.js"></script>


<script>
    // cheks
    var valor;
    jQuery.fn.getCheckboxValues = function(){
        var values = [];
        var i = 0;
        this.each(function(){
            // guarda los valores en un array
            valor = $(this).val();
            values[i++] = valor;
        });
        if(i == 0 ){
            return i;
        }
        else{
            return values;
        }
    }

    $(function(){
	    $(".wmd-view-topscroll").scroll(function(){
	        $(".wmd-view")
	            .scrollLeft($(".wmd-view-topscroll").scrollLeft());
	    });
	    $(".wmd-view").scroll(function(){
	        $(".wmd-view-topscroll")
	            .scrollLeft($(".wmd-view").scrollLeft());
	    });
	});

    $(document).ready(function() {

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

    	$(document).on( "click","#filtro" , function() {
            var_sexo = $('#sexo').val();
            var_region = $('#region').val();
            var_comuna = $('#comuna').val();
            var_profesion = $('#profesion').val();
            var_condepto = $('#condepto').val();

            table.state.clear();
            $.ajax({
                type: 'POST',
                url: ("filtro_update.php"),
                data:"sexo="+var_sexo+"&profesion="+var_profesion+"&condepto="+var_condepto+"&region="+var_region+"&comuna="+var_comuna,
                success: function(data) {
                    location.reload();
                }
            })
        });

        $(document).on( "click",".borra_sesion" , function() {
        	table.state.clear();
            $.ajax({
                type: 'POST',
                url: ("filtro_delete.php"),
                success: function(data) {
                    location.reload();
                }
            })
        });
		
		<?php 
		if ($_SESSION["sesion_perfil_panel"]==4 || $_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_id_panel"]==30) { //le oculta al vendedor los botontes tabla
		?>
			$('#deny_copy').bind("cut copy paste",function(e) {
			     e.preventDefault();
			});
		<?php 
		}
		 ?>

        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            stateSave: true,
            lengthChange: true,
            pageLength: 10,
            <?php 
			if ($_SESSION["sesion_perfil_panel"]==4 || $_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_id_panel"]==30) { //le oculta al vendedor los botontes tabla
			?>
			buttons: [ { 
				extend: 'excelHtml5', 
				sheetName: 'Hoja1',
				exportOptions: { 
					columns: [0,4,1],
					format: {
	                    header: function ( data, columnIdx ) {
	                        if(columnIdx==0){
	                        	return 'n';
	                        } 
	                        else if (columnIdx==4) {
	                        	return 'telefono';
	                        } 
	                        else if (columnIdx==1) {
	                        	return 'Nombre';
	                        }
	                        else{
	                        return data;
	                        }
	                    }
	                }
				}
			}],
			<?php
			} else {
			 ?>
			buttons: [ 'copy', { extend: 'excel', exportOptions: { rows: ':visible', columns: ':visible'}}, 'pdf', 'print', 'colvis' ],
			<?php
			}
			?>
            "bProcessing": true,
            "bServerSide": true,
            responsive: true,
            "sAjaxSource": "select.php",
            "sPaginationType": "listbox",
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns": [
                { "bSortable": false },
                null,
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },
                { "bSortable": false },                               
                { "bSortable": false },
                { "bSortable": false }
            ]
        });
 
        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

        // para limpiar la sesión de la tabla al filtrar
        $('.carClass').on('click', function() {
		    console.log("here")
		    table.state.clear();
		 });

        // eliminar
        function resultado(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registros eliminados con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }
        function resultado_eliminar(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro eliminado con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                alert(data.error_consulta);
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }

        // $(document).on( "click",".agrega_observacion" , function() {
        //     $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
        //     valor = $(this).val();
        //     $.ajax({
        //         type: 'POST',
        //         url: ("form_insert_observacion.php"),
        //         data:"valor="+valor,
        //         success: function(data) {
        //              $('#contenedor_opcion').html(data);
        //         }
        //     })
        //     $("html, body").animate({
        //         scrollTop: 100
        //     }, 1000);
        // }); 

        // ver modal
        $(document).on( "click",".agrega_observacion" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_insert_observacion_modal.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });

        $(document).on( "click",".eliminar_observacion" , function() {
            valor = $(this).val();
            idpro = $(this).attr("data-pro");
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar la observación seleccionadoa!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("delete_obs.php"),
                    data:"valor="+valor+"&idpro="+idpro,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar_obs(data);
                    }
                })
            });
        });

        function resultado_eliminar_obs(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Registro eliminado con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: true
                },
                function(){
                	$.ajax({
		                type: 'POST',
		                url: ("form_detalle.php"),
		                data:"valor="+data.propietario,
		                success: function(data) {
		                     $('#contenedor_modal').html(data);
		                }
		            })
                        //          	setTimeout(
					  // function() 
					  // {
					  //   $('#contenedor_modal').html(data.propietario);
                      //              	$('#contenedor_modal').modal('show');
					  // }, 1000);
                });
            }
            if(data.envio == 3){
                alert(data.error_consulta);
                swal("Error!", "Favor intentar denuevo","error");
            }
            /*if(data.envio != ""){
                alert(data.envio);
            }*/
        }

        $(document).on( "click",".eliminar" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar el registro seleccionado!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("delete.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_eliminar(data);
                    }
                })
            });
        });

        $('.borra_todo').click(function(){
            valor = $(".check_registro:checked").getCheckboxValues();
            var_check = $(".check_registro:checked").length;
            swal({
                title: "Está Seguro?",
                text: "Desea eliminar los registros seleccionados!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("delete_todo.php"),
                    data:"valor="+valor+"&cantidad="+var_check,
                    dataType:'json',
                    success: function(data) {
                        resultado(data);
                    }
                })
            });
        });
        $('#check_todo').on('change', function(event){
            $('.check_registro:enabled').each( function() {  
                if($("input[name=check_todo]:checked").length == 1){
                    $(this).prop("checked", "checked");
                } 
                else {
                    $(this).prop("checked", "");
                }
            });
        });

        $('#check_todo_mail').on('change', function(event){
            $('.check_registro_mail:enabled').each( function() {  
                if($("input[name=check_todo_mail]:checked").length == 1){
                    $(this).prop("checked", "checked");
                } 
                else {
                    $(this).prop("checked", "");
                }
            });
        });

        // ver modal
        $(document).on( "click",".detalle" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });


        $(document).on( "click",".edita" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
             //$('body').tooltip('destroy');
             
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_update.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 
        $(document).on( "click",".estado" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea cambiar el estado del registro seleccionado!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("estado.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_estado(data);                 
                    }
                })
            });
        });
        function resultado_estado(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Estado modificado con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: false
                },
                function(){
                    location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
        }

        $(document).on( "click",".maildatos" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea enviar los datos de acceso al Propietario?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    type: 'POST',
                    url: ("enviodatos.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_envio(data);                 
                    }
                })
            });
        });

        function resultado_envio(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Enviados con éxito!",
                  type: "success",
                  showCancelButton: false,
                  confirmButtonColor: "#9bde94",
                  confirmButtonText: "Aceptar",
                  closeOnConfirm: true
                },
                function(){
                    // location.reload();
                });
            }
            if(data.envio == 3){
                swal("Error!", "Favor intentar denuevo","error");
            }
        }

        // registro de eventos
		$(document).on( "click",".agrega_evento" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("../cotizacion/form_insert_evento.php"),
                data:"valor="+valor+"&origen=cliente",
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        });

        $(document).on( "click",".agrega_documento" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_update_docs.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 


        // observacion masiva
        $('.observa_checks').click(function(){
            valor = $(".check_registro_obs:checked").getCheckboxValues();
            var_check = $(".check_registro_obs:checked").length;

            if(var_check>0) {
				$('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
	             //$('body').tooltip('destroy');
	             
	            // valor = $(this).val();
	            $.ajax({
	                type: 'POST',
	                // url: ("form_insert_observacion_masiva.php"),
	                url: ("form_insert_observacion_masiva.php"),
	                data:"valor="+valor+"&cantidad="+var_check,
	                success: function(data) {
	                     $('#contenedor_opcion').html(data);
	                }
	            })
	            $("html, body").animate({
	                scrollTop: 100
	            }, 1000);
            }
        });

        // observacion masiva
        $('.mails_checks').click(function(){
            valor = $(".check_registro_mail:checked").getCheckboxValues();
            var_check = $(".check_registro_mail:checked").length;

            if(var_check>0) {
				$('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
	             //$('body').tooltip('destroy');
	             
	            // valor = $(this).val();
	            $.ajax({
	                type: 'POST',
	                // url: ("form_insert_observacion_masiva.php"),
	                url: ("form_insert_campana_email.php"),
	                data:"valor="+valor+"&cantidad="+var_check,
	                success: function(data) {
	                     $('#contenedor_opcion').html(data);
	                }
	            })
	            $("html, body").animate({
	                scrollTop: 100
	            }, 1000);
            }
        });
    
    } );
</script>
</body>
</html>
