<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
// if (!isset($_SESSION["sesion_usuario_panel"])) {
//     header("Location: "._ADMIN."index.php");
// }
// if (!isset($_SESSION["modulo_venta_panel"])) {
//     header("Location: "._ADMIN."panel.php");
// }
?>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<!-- <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css"> -->
<!-- siempre al final los ajustes -->
<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">
<style>
	.modal-dialog label{
		font-weight: normal;
	}
	.modal-dialog label.negrita_detalle{
		font-weight: 700;
	}

	.cajas h5{
		margin-top: 4px;
		margin-bottom: 4px;
	}

</style>
<?php 
if ($_SESSION["sesion_id_panel"]==30) { //le oculta al vendedor los botontes tabla
	?>
	<style type="text/css">
	.dt-buttons.btn-group{
		display: none;
	}
	</style>
	<?php
}
 ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        // require_once _INCLUDE."menu_modulo.php";
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
                    Venta
                    <small>Listado</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Venta</a></li>
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
                            <div class="box-body table-responsive">
                            	<div class="row">
	                            	<div class="col-sm-3">
	                                	<div class="form-group">
	                                        <label for="condominio">Condominio:</label>
	                                          <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
	                                        <select class="form-control chico" id="condominio" name="condominio"> 
	                                            <option value="">Todos los condominios</option>
	                                            <?php  
	                                            $consulta = "SELECT id_con, nombre_con, fecha_venta_con FROM condominio_condominio ORDER BY nombre_con";
	                                            $conexion->consulta($consulta);
	                                            $fila_consulta_condominio_original = $conexion->extraer_registro();
	                                            if(is_array($fila_consulta_condominio_original)){
	                                                foreach ($fila_consulta_condominio_original as $fila) {
	                                                	$selected = $_SESSION["sesion_filtro_condominio_panel"] === $fila['id_con'] ? "selected" : "";
	                                                    ?>
	                                                    <option value="<?php echo $fila['id_con'];?>" <?php echo $selected ?>><?php echo utf8_encode($fila['nombre_con']);?></option>
	                                                    <?php
	                                                }
	                                            }
	                                            ?>
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="col-sm-1 text-center">
	                                  <div class="form-group filtrar">
	                                      <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
	                                  </div>
	                                </div>
	                            </div>
                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5">
                                                <input type="checkbox" name="check_todo" id="check_todo">
                                                <label for="check_todo"><span></span></label>
                                            </th>
                                            <th>Id</th>
                                            <th>Condominio</th>
                                            <th>Torre</th>
                                            <th>Depto</th>
                                            <th>Cliente</th>
                                            <th>RUT</th>
                                            <th>Mail</th>
                                            <th>Teléfono</th>
                                            <th>Vendedor</th>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th style="width:14%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>
                                            	<?php 
                                            	if ($_SESSION["sesion_perfil_panel"]<>6) {
                                            	?><button type="button" class="btn btn-xs btn-icon btn-danger borra_todo" data-toggle="tooltip" data-original-title="Eliminar Seleccionados"><i class="fa fa-trash"></i></button><?php } ?></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
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
<script src="<?php echo _ASSETS?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/datetime-moment.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/jszip.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/pdfmake.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/vfs_fonts.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.html5.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.print.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.colVis.min.js"></script>

<!-- iCheck 1.0.1 -->
<!-- <script src="<?php // echo _ASSETS?>plugins/iCheck/icheck.min.js"></script> -->

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

    $(document).ready(function() {

    	$(document).on( "click","#filtro" , function() {
    		// alert("prueba");
            //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
            var_condominio = $('#condominio').val();
            $.ajax({
                type: 'POST',
                url: ("filtro_update_modulo.php"),
                data:"condominio="+var_condominio,
                success: function(data) {
                    location.reload();
                }
            })
        });
        // $(document).on('icheck', function(){
        //     $('input[type=checkbox].flat-red').iCheck({
        //         checkboxClass: 'icheckbox_flat-red'
        //     });
        // }).trigger('icheck');
        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            stateSave: true,
            // success de tabla
            // "fnInitComplete":function(oSettings,json){
            //     $(document).trigger('icheck');
            // },
            lengthChange: true,
            buttons: [ 'copy', {
                extend: 'excelHtml5',
                exportOptions: {
                	orthogonal: 'export',
                    columns: ':visible'
                }
            }, 'pdf', 'print', 'colvis' ],
            "bProcessing": true,
            // "bServerSide": true,
            responsive: true,
            "sAjaxSource": "select_test.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns": [
                { "bSortable": false },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                { "sType": "date-uk" },
                { "sType": "numeric", render: function (data, type, row) {
                	if (type === 'export') {
						var arr = data;
		                arr = arr.replace( /[\.]/g, "" );
		                arr = arr.replace( /[\,]/g, "." );
	                    return arr;
                	} else {
                		return data;
                	}
	            } }, //20
                null,
                { "bSortable": false, "sType": "string", render: function (data, type, row) {
                	if (type === 'export') {
						// var arr = data;
		    //             arr = arr.replace( /[\.]/g, "" );
		    //             // arr = arr.replace( /[\,]/g, "." );
	                    return '';
                	} else {
                		return data;
                	}
	            } }, //20
                // { "bSortable": false }
            ]
        });

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "date-uk-pre": function ( a ) {
                var ukDatea = a.split('/');
                return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
            },

            "date-uk-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "date-uk-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });
        $.fn.dataTable.moment( 'DD.MM.YYYY' );
 
        table.buttons().container()
            .appendTo( '#example_wrapper .col-sm-6:eq(1)' );

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

        $(document).on( "click",".agrega_seguimiento" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_insert_seguimiento.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 

        $(document).on( "click",".desistimiento" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_insert_desistimiento.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 

        $(document).on( "click",".pago" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_insert_pago.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_opcion').html(data);
                }
            })
            $("html, body").animate({
                scrollTop: 100
            }, 1000);
        }); 

        $(document).on( "click",".listado_detalle" , function() {
            valor = $(this).val();
            location.href = "form_select_detalle.php?id="+valor;
        });

        $(document).on( "click",".finiquito" , function() {
            valor = $(this).val();
            window.open('../documento/desistimiento.php?id='+valor);
        });

        $(document).on( "click",".desistimiento_carta" , function() {
            valor = $(this).val();
             window.open('../documento/carta_desistimiento.php?id='+valor);
        });

        $(document).on( "click",".resciliacion_contrato" , function() {
            valor = $(this).val();
             window.open('../documento/resciliacion.php?id='+valor);
        });
        
        $(document).on( "click",".despacho" , function() {
            valor = $(this).val();
            window.open('../documento/despacho_promesa.php?id='+valor);
        });

        $(document).on( "click",".marcha" , function() {
            valor = $(this).val();
            window.open('../documento/carta_fondo_marcha.php?id='+valor);
        });

        $(document).on( "click",".informe_pago" , function() {
            valor = $(this).val();
            window.open('../documento/informe_pago.php?id='+valor);
        });

        $(document).on( "click",".cierre_negocio" , function() {
            valor = $(this).val();
            window.open('../documento/carta_cierre.php?id='+valor);
        });

        $(document).on( "click",".entrega_abono" , function() {
            valor = $(this).val();
            window.open('../documento/entrega_abono.php?id='+valor);
        });

        $(document).on( "click",".premio" , function() {
            valor = $(this).val();
            window.open('../documento/certificado_premio.php?id='+valor);
        });

        $(document).on( "click",".carta_oferta" , function() {
            valor = $(this).val();
            window.open('../informe/carta_oferta.php?id='+valor);
        });

        $(document).on( "click",".operacion" , function() {
            valor = $(this).val();
            swal({
                title: "Está Seguro?",
                text: "Desea pasar a operación el registro seleccionado!",
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
                    url: ("insert_operacion.php"),
                    data:"valor="+valor,
                    dataType:'json',
                    success: function(data) {
                        resultado_operacion(data);                 
                    }
                })
            });
        });


        $(document).on( "click",".edita" , function() {
            $('#contenedor_opcion').html('<img src="<?php echo _ASSETS;?>img/loading.gif">');
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

        function resultado_operacion(data) {
            if(data.envio == 1){
                swal({
                  title: "Excelente!",
                  text: "Venta actualizada con éxito!",
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
    } );
</script>
</body>
</html>
