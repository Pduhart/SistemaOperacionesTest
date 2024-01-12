<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_reserva_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
// $_SESSION["codigo_noticia_panel"] = $_POST["valor"];
$id_res = $_POST["valor"];
$conexion = new conexion();
$_SESSION["codigo_reserva_panel"] = $id_res;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/bootstrap-image-gallery.min.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui.css">

    <noscript><link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui-noscript.css"></noscript>
    
<style type="text/css">
.vehiculo label {

}

.vehiculo label h6{
    text-align: center;
    font-size: 15px;
}
.vehiculo label > input{ /* HIDE RADIO */
  visibility: hidden; /* Makes input not-clickable */
  position: absolute; /* Remove input from document flow */
}
.vehiculo label > input + img{ /* IMAGE STYLES */
  cursor:pointer;
  border:2px solid transparent;
}
.vehiculo label > input:checked + img{ /* (RADIO CHECKED) IMAGE STYLES */
  border:2px solid #c59d31;
}

.vehiculo label > input:checked + img + h6{ /* (RADIO CHECKED) IMAGE STYLES */
  font-weight: bold;
  color: #c59d31;
}
</style>
</head>
<body>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-usd" aria-hidden="true"></i> Carga de Comprobante Depósito </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <form id="formulario" role="form" method="post" action="update.php">
        <?php  
        
        // $consulta = 
        //     "
        //     SELECT 
        //         res.id_res,
        //         res.fecha_desde_res,
        //         res.fecha_hasta_res,
        //         res.cantidad_dia_res,
        //         res.cantidad_pasajero_res,
        //         res.descripcion_res,
        //         con.id_con,
        //         con.nombre_con,
        //         tor.id_tor,
        //         tor.nombre_tor,
        //         mode.id_mod,
        //         mode.nombre_mod,
        //         viv.id_viv,
        //         viv.nombre_viv,
        //         prog.id_prog,
        //         prog.nombre_prog,
        //         proc.id_proc,
        //         proc.nombre_proc,
        //         tip_res.id_tip_res,
        //         tip_res.nombre_tip_res,
        //         arr.id_arr,
        //         arr.nombre_arr,
        //         arr.nombre2_arr,
        //         arr.apellido_paterno_arr,
        //         arr.apellido_materno_arr
        //     FROM 
        //         reserva_reserva AS res
        //         INNER JOIN reserva_tipo_reserva AS tip_res ON tip_res.id_tip_res = res.id_tip_res
        //         INNER JOIN reserva_estado_reserva AS est_res ON est_res.id_est_res = res.id_est_res
        //         INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
        //         INNER JOIN programa_programa AS prog ON prog.id_prog = res.id_prog
        //         INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
        //         INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
        //         INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
        //         INNER JOIN procedencia_procedencia AS proc ON proc.id_proc = res.id_proc
        //         INNER JOIN arrendatario_arrendatario AS arr ON arr.id_arr = res.id_arr
        //     WHERE 
        //         res.id_res = ?
        //     ";
        // $conexion->consulta_form($consulta,array($id_res));
        // $fila = $conexion->extraer_registro_unico();
        // $cantidad_pasajero_res = utf8_encode($fila['cantidad_pasajero_res']);
        // $descripcion_res = utf8_encode($fila['descripcion_res']);

        // $id_tip_res = utf8_encode($fila['id_tip_res']);
        // $nombre_tip_res = utf8_encode($fila['nombre_tip_res']);
        
        // $id_prog = utf8_encode($fila['id_prog']);
        // $nombre_prog = utf8_encode($fila['nombre_prog']);
        // $id_proc = utf8_encode($fila['id_proc']);
        // $nombre_proc = utf8_encode($fila['nombre_proc']);
        // $id_viv = utf8_encode($fila['id_viv']);
        // $nombre_viv = utf8_encode($fila['nombre_viv']);

        // $id_mod = utf8_encode($fila['id_mod']);
        // $nombre_mod = utf8_encode($fila['nombre_mod']);

        // $id_tor = utf8_encode($fila['id_tor']);
        // $nombre_tor = utf8_encode($fila['nombre_tor']);

        // $id_con = utf8_encode($fila['id_con']);
        // $nombre_con = utf8_encode($fila['nombre_con']);

        // $fecha_desde = utf8_encode($fila['fecha_desde_res']);
        // $fecha_hasta = utf8_encode($fila['fecha_hasta_res']);

        // $fecha_desde_res = date("d-m-Y",strtotime($fecha_desde));
        // $fecha_hasta_res = date("d-m-Y",strtotime($fecha_hasta));    
        
        // $id_arr = utf8_encode($fila['id_arr']);
        // $nombre_arr = utf8_encode($fila['nombre_arr']);
        // $nombre2_arr = utf8_encode($fila['nombre2_arr']);
        // $apellido_paterno_arr = utf8_encode($fila['apellido_paterno_arr']);
        // $apellido_materno_arr = utf8_encode($fila['apellido_materno_arr']);
        
        ?>
            <input type="hidden" name="id" id="id" value="<?php echo $id_res;?>"></input>
            <label>Reserva: <?php echo $id_res;?></label>   
        <!-- /.box-body -->
       
        </form>
        <div id="zona1" class="zona">   
            <form id="fileupload2" action="" method="POST" enctype="multipart/form-data">                             
                <div class="fileupload-buttonbar">
                    <div class="span7">
                        <span class="btn btn-success fileinput-button btn-sm">
                            <i class="icon-plus icon-white"><img src="<?php echo _ASSETS?>plugins/upload/imagen/icono/adjuntar.png" width="16" height="16"/></i>
                            <span>Adjuntar Archivo</span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <!-- <button type="submit" class="btn btn-primary btn-sm start">
                            <i class="icon-upload icon-white"><img src="plugin/upload/imagen/icono/subir.png" width="16" height="16"/></i>
                            <span>Subir Archivos</span>
                        </button> -->
                        <button type="reset" class="btn btn-info btn-sm cancel" style="padding: 0; border: none">
                        </button>                                        
                    </div>
                    <div class="span5 fileupload-progress fade">
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="margin-bottom: 0">
                            <div class="bar" style="width:0%;"></div>
                        </div>
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <div class="fileupload-loading"></div>
                <br>
                <table role="presentation" class="table table-striped tabladoc"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>

            </form>

        </div>
        <div class="row margin-bottom-40">
            <!-- <div class="col-sm-12">
                <div class="text-center" id="contenedor_boton_alumno">
                    <button type="button" id="guarda" class="btn btn-warning">Registrar</button>
                </div>    
            </div> -->
        </div>
    </div>
     <div class="box-footer">
            <!-- <button type="submit" class="btn btn-primary pull-right">Registrar</button> -->
    </div>
</div>

<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/daterangepicker/daterangepicker.js"></script>

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>

<script src="<?php echo _ASSETS?>plugins/upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/tmpl.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/load-image.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/canvas-to-blob.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/bootstrap-image-gallery.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.iframe-transport.js"></script>
    
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-fp.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-ui.js"></script>

<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span><br><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary btn-sm">
                    <i class="icon-upload icon-white"></i>
                    <span>Subir</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade" >
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span><br><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}">{%=file.name%}</a><br><span>{%=o.formatFileSize(file.size)%}</span>
            </td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger btn-sm" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Borrar</span>
            </button>
            <!--<input type="checkbox" name="delete" value="1">-->
        </td>
    </tr>
{% } %}
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        $('#fileupload2').fileupload({
            url: '../../../archivo/comprobante/documento/',
            maxNumberOfFiles: 1,
            autoUpload:true,
            dropZone: $('#zona1'),
            pasteZone: $('#zona1'),
            acceptFileTypes: /(\.|\/)(pdf|xls|xlsx|doc|docx|jpg|jpeg)$/i
        });
        $.ajax({
            url: $('#fileupload2').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload2')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
              .call(this, null, {result: result});
        });
        // $(document).on( "click","#guarda" , function() {
        //     $("#formulario").submit();
        // });
        // $('.numero').numeric();
        // $(function () {
        //     //Initialize Select2 Elements
        //     $(".select2").select2();
        // });
        // $("#formulario").validate({
        //     rules: {
        //         condominio: { 
        //             required: true
        //         },
        //         torre:{
        //             required: true
        //         },
        //         fecha:{
        //             required: true
        //         },
        //         modelo:{
        //             required: true
        //         },
        //         vivienda:{
        //             required: true
        //         },
        //         programa:{
        //             required: true
        //         },
        //         procedencia:{
        //             required: true
        //         },
        //         cantidad:{
        //             required: true
        //         }
        //     },
        //     messages: {
        //         condominio: {
        //             required: "Seleccione condominio"
        //         },
        //         torre: {
        //             required: "Seleccione torre"
        //         },
        //         fecha: {
        //             required: "Ingrese Fecha"
        //         },
        //         modelo: {
        //             required: "Seleccione modelo"
        //         },
        //         vivienda: {
        //             required: "Seleccione vivienda"
        //         },
        //         programa: {
        //             required: "Seleccione programa"
        //         },
        //         procedencia: {
        //             required: "Seleccione procedencia"
        //         },
        //         cantidad: {
        //             required: "ingrese cantidad"
        //         }
        //     },
        // });
        

        // $(document).on( "click",".cambioreserva" , function() {
        //     valor = $(this).val();
        //     $.ajax({
        //         type: 'POST',
        //         url: ("form_ajuste.php"),
        //         data:"valor="+valor,
        //         success: function(data) {
        //              $('#contenedor_modal').html(data);
        //              $('#contenedor_modal').modal('show');
        //         }
        //     })
        // });

        

    });

</script>

<!-- iCheck 1.0.1 -->
<script src="<?php echo _ASSETS?>plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
   

   
</script>
</body>
</html>