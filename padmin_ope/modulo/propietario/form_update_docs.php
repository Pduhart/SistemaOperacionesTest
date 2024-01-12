<?php
session_start();
require "../../config.php"; 

if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_propietario_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
include _INCLUDE."class/conexion.php";
include _INCLUDE."class/class_fecha.php";
$conexion = new conexion();

$origen = $_POST["origen"];
if($origen<>''){
	$id_cot = $_POST["valor"];

	$consulta = 
            "
            SELECT
                id_pro
            FROM
                cotizacion_cotizacion
            WHERE
                id_cot = ?
            ORDER BY id_cot DESC
            LIMIT 0,1
            ";
    $conexion->consulta_form($consulta,array($id_cot));
    $fila = $conexion->extraer_registro_unico();
	$id_pro = $fila["id_pro"];
	$_SESSION["codigo_propietario_panel"] = $id_pro;

} else {
	$id_pro = $_POST["valor"];
	$_SESSION["codigo_propietario_panel"] = $id_pro;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/bootstrap-image-gallery.min.css">
    <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui.css">
    <noscript><link rel="stylesheet" href="<?php echo _ASSETS?>plugins/upload/css/jquery.fileupload-ui-noscript.css"></noscript>
    <!-- daterange picker -->
    <!-- <link rel="stylesheet" href="<?php // echo _ASSETS?>plugins/datepicker/datepicker3.css"> -->
    <!-- Bootstrap Color Picker -->
<!-- <link rel="stylesheet" href="<?php //echo _ASSETS?>plugins/colorpicker/bootstrap-colorpicker.min.css"> -->

<script src="<?php echo _ASSETS?>plugins/upload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/tmpl.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/load-image.min.js"></script>
<!-- <script src="<?php //echo _ASSETS?>plugins/upload/js/canvas-to-blob.min.js"></script> -->
<script src="<?php echo _ASSETS?>plugins/upload/js/bootstrap-image-gallery.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.iframe-transport.js"></script>
    
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-fp.js"></script>
<script src="<?php echo _ASSETS?>plugins/upload/js/jquery.fileupload-ui.js"></script>
</head>
<body>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-pencil" aria-hidden="true"></i>Carga de Documentos    </h3>
        <button class="btn btn-link btn-sm pull-right cerrar-formulario" data-toggle="tooltip" data-original-title="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
    	<div class="row">
    		<div class="col-sm-12">
    			<?php 
				$consulta = 
		            "
		            SELECT
		                pro.rut_pro,
		                pro.nombre_pro,
		                pro.apellido_paterno_pro,
		                pro.apellido_materno_pro,
		                pro.fono_pro
		            FROM
		                propietario_propietario AS pro
		            WHERE
		                pro.id_pro = ?
		            ";
		        $conexion->consulta_form($consulta,array($id_pro));
		        $fila = $conexion->extraer_registro_unico();
		        $rut_pro = utf8_encode($fila['rut_pro']);
		        $nombre_pro = utf8_encode($fila['nombre_pro']);
		        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
		        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
		        $fono_pro = utf8_encode($fila['fono_pro']);
    			 ?>
	            <h4>Cliente: <?php echo $nombre_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro." - Fono:".$fono_pro;?> - RUT: <?php echo $rut_pro; ?></h4>
	        </div>
    	</div>
	    <!-- <form id="formulario" role="form" method="post" action="update_docs.php">

	        <input type="hidden" name="id" id="id" value="<?php //echo $id;?>"></input>
	        
	    </form> -->
	    
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
	    <div class="row" style="margin-top: 0px;">
	        <div class="col-sm-12">
	            <div class="text-center" id="contenedor_boton">
	                <button type="button" id="boton_cerrar" class="btn btn-warning cerrar-formulario">Cerrar</button>
	            </div>    
	        </div>
	    </div>
	</div>
</div>

<?php //include_once _INCLUDE."js_comun.php";?>
<!-- sweet alert -->
<script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script>
<!-- DatePicker -->
<!-- <script src="<?php// echo _ASSETS?>plugins/datepicker/bootstrap-datepicker.js"></script> -->
<!-- <script src="<?php //echo _ASSETS?>plugins/datepicker/locales/bootstrap-datepicker.es.js"></script> -->

<!-- bootstrap color picker -->
<!-- <script src="<?php //echo _ASSETS?>plugins/colorpicker/bootstrap-colorpicker.min.js"></script> -->

<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>

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
        //Colorpicker
        

        // cerrar formulario update
        $(document).on( "click",".cerrar-formulario" , function() {
            $('#contenedor_opcion').html('');
        });

        $('#fileupload2').fileupload({
            url: '../../../archivo/propietario/documento/',
            maxNumberOfFiles: 10,
            autoUpload:true,
            dropZone: $('#zona1'),
            pasteZone: $('#zona1'),
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|pdf|doc|docx|xls|xlsx|txt)$/i
        });
        $.ajax({
            url: $('#fileupload2').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload2')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
              .call(this, null, {result: result});
        });

        $(document).on( "click","#boton_update" , function() {
            $("#formulario").submit();
        });
        $("#formulario").validate({
            rules: {

            },
            messages: { 
            }
        });
        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Documentos subidos con éxito!",
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
                swal("Atención!", "Información ya ha sido ingresada", "warning");
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
    }); 
</script>
</body>
</html>