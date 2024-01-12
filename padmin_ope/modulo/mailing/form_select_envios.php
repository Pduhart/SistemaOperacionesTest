<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_mailing_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/datatables/extensions/buttons/buttons.bootstrap.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<!-- siempre al final los ajustes -->
<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">
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
                    Enviós de Mails Masivos
                    <small>Listado</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Campañas Mailing Masivos</a></li>
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
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <?php
                                                //if ($_SESSION["sesion_perfil_panel_intranet"] == 1) {
                                                    ?>
                                                    <input type="checkbox" name="check_todo" id="check_todo" class="fx">
                                                    <label for="check_todo"><span></span></label>
                                                <?php
                                                //}
                                                ?>
                                            </th>
                                            <th>Id</th>
                                            <th>Asunto</th>
                                            <th>link</th>
                                            <th>Fecha</th>
                                            <th>Remitente</th>
                                            <th>Plantilla</th>
                                            <th>Cantidad</th>
                                            <th style="width:14%">Acción</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <?php
                                        //if ($_SESSION["sesion_perfil_panel_intranet"] == 1) {
                                            ?>
                                            <tr>
                                                <th>
                                                	<?php 
													if($_SESSION["sesion_perfil_panel"] <> 4){
                                                	 ?>
                                                	<!-- <button type="button" class="btn btn-xs btn-icon btn-danger borra_todo" data-toggle="tooltip" data-original-title="Eliminar Seleccionados"><i class="fa fa-trash"></i></button> -->
                                                	<?php 
                                                	}
                                                	?>
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        <?php
                                        //}
                                        ?>
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

    // { "bVisible": false },

    $(document).ready(function() {
        var table = $('#example').DataTable( {
            dom:'lfBrtip',
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
            "bProcessing": true,
            // "bServerSide": true,
            responsive: true,
            "sAjaxSource": "select_envios.php",
            "sPaginationType": "full_numbers",
            "aaSorting": [[ 1, "desc" ]],
            "aoColumns": [
                { "bSortable": false },

                { "bVisible": false },
                null,
                null,
                { "sType": "date-uk" },
                null,
                null,
                null,
                { "bSortable": false }
            ],
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
          } );
        // $.fn.dataTable.moment( 'DD.MM.YYYY' );

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
        // ver modal
        $(document).on( "click",".detalle" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("form_detalle_envios.php"),
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
    
    } );
</script>
</body>
</html>
