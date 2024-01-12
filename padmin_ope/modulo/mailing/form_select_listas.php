<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_mailing_panel"])) {
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


</style>
<?php 
// if ($_SESSION["sesion_perfil_panel"]==4 || $_SESSION["sesion_perfil_panel"]==6 || $_SESSION["sesion_id_panel"]==30) { //le oculta al vendedor los botontes tabla
	?>
	<!-- <style type="text/css">
	.dt-buttons.btn-group{
		display: none;
	}
	</style> -->
	<?php
// }
 ?>
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
                    Mailing
                    <small>Listado de Listas</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Mailing</a></li>
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
                            	
                                <div id="deny_copy" class="table-responsive">
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
                                            <th>Nombre Lista</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>
                                                <?php 
                                                if ($_SESSION["sesion_perfil_panel"]==1) {
                                                 ?>
                                                <button type="button" class="btn btn-xs btn-icon btn-danger borra_todo" data-toggle="tooltip" data-original-title="Eliminar Seleccionados"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </th>
                                            <th></th>
                                            
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


        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            stateSave: true,
            lengthChange: true,
            pageLength: 50,
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
            "sAjaxSource": "select_listas.php",
            "sPaginationType": "listbox",
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns": [
                { "bSortable": false },
                null
            ]
        });
 
        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

        // para limpiar la sesión de la tabla al filtrar
        $('.carClass').on('click', function() {
		    console.log("here")
		    table.state.clear();
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
	                url: ("form_insert_campana_email_lista.php"),
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
