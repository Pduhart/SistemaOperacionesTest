<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_condominio_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$id_con = $_POST["valor"];
$conexion = new conexion();
$_SESSION["codigo_condominio_panel"] = $id_con;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/bootstrap-image-gallery.min.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui.css">

    <noscript><link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui-noscript.css"></noscript>

</head>
<body>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil" aria-hidden="true"></i> Formulario Actualización       </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    
    <!-- <div class="col-sm-12">
        PORTADA
        <div id="zona3" class="zona">   
            <form id="fileupload3" action="" method="POST" enctype="multipart/form-data">
                <div class="fileupload-buttonbar">
                    <div class="span7">
                        <span class="btn btn-success fileinput-button btn-sm">
                            <i class="icon-plus icon-white"><img src="<?php echo _ASSETS?>plugins/upload/imagen/icono/adjuntar.png" width="16" height="16"/></i>
                            <span>Adjuntar Archivo</span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary btn-sm start">
                            <i class="icon-upload icon-white"><img src="plugin/upload/imagen/icono/subir.png" width="16" height="16"/></i>
                            <span>Subir Archivos</span>
                        </button>
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
    </div> -->
    <div class="col-sm-12">
        DOCUMENTOS
        <div id="zona4" class="zona">   
            <form id="fileupload4" action="" method="POST" enctype="multipart/form-data">
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
    </div>
    <div class="row margin-bottom-40">
        <div class="col-sm-12">
            <!-- <div class="text-center" id="contenedor_boton_alumno">
                <button type="button" id="guarda" class="btn btn-primary">Registrar</button>
            </div>   -->  
        </div>
    </div>
</div>

<?php include_once _INCLUDE."js_comun.php";?>
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
    $(document).ready(function() {
        $('.numero').numeric();
        
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });
        

        $('#fileupload4').fileupload({
            url: '../../../archivo/condominio/documento/',
            // maxNumberOfFiles: 1,
            // autoUpload:true,
            dropZone: $('#zona4'),
            pasteZone: $('#zona4'),
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|pdf)$/i
        });
        $.ajax({
            url: $('#fileupload4').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload4')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
              .call(this, null, {result: result});
        });
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información actualizada con éxito!",
                    icon: "success"                    
                }).then(()=>location.href = "form_select.php");
            }
            if (data.envio == 2) {
                swal("Atención!", "Registro ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="submit" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        }
        
    }); 
</script>
</body>
</html>