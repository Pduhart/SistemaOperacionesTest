<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/login.css"/>
<link rel="stylesheet" type="text/css" href="../../css/mensaje_login.css"/>
<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/jquery.validate.js"></script>
<title>Ingreso al Sistema</title>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".mensaje").hide();
			//--------  CERRAR MENSAJES
			$('#info_mensaje').click(function() {
				$(".info").hide("slow");
			});
			$('#exito_mensaje').click(function() {
				$(".exito").hide("slow");
				location.href = '../../index.php';
			});
			$('#alerta_mensaje').click(function() {
				$(".alerta").hide("slow");
			});
			$('#error_mensaje').click(function() {
				$(".error_mensaje").hide("slow");
			});
			//----- 
			$("#formulario").validate({
				rules: {
					correo_usu: "required",
	
					correo_usu: { 
						required: true,
						email: true,
						minlength: 10,
						maxlength: 150
					}
				},
				messages: { 
					correo_usu: {
						required: "Ingrese correo de usuario",
						email: "Ingrese correo válido",
						minlength: " Mínimo 10 caracteres",
						maxlength: " 150 caracteres como máximo"
					}
				}
			});
			function resultado(data) {
				if(data.envio == 1){
					$(".exito").show(500);
				}
				if(data.envio == 2){
					$(".alerta").show(500);
					$('#contenedor_boton').html('<input name="entrar" class="minimal" id="entrar" type="submit" value="Entrar"/>');
				}
				if(data.envio == 3){
					$(".error_mensaje").show(500);
					$('#contenedor_boton').html('<input name="entrar" class="minimal" id="entrar" type="submit" value="Entrar"/>');
				}
			}
			$('#formulario').submit(function() {
				if ($("#formulario").validate().form() == true){
					var_correo_usu = $('#correo_usu').val();
					$('#contenedor_boton').html('<img src="imagen/ajax-loader.gif">');
					$.ajax({
						data:"correo_usu="+var_correo_usu,
						type: 'POST',
						url: $(this).attr('action'),
						dataType:'json',
						success: function(data) {
							/*$('#informacion').html(data);*/
							resultado(data);
						}           
					})
				}
				return false;
			});
		});
    </script>
</head>

<body>
	<div id="contenedor_login">
    	<div id="cabecera">
        	<div id="logo">
        		<img id="proyectarse" src="../../imagen/menu/kstudio.png" />
            </div>
            <div id="titulo">
        		Restaurar Contraseña
            </div>
        </div>
        <div id="contenido">
        	<form method="post" action="valida_contrasena.php" id="formulario">
                <fieldset id="form">
                        <ol>
                        	<li id="mensaje_contrasena"><p>Para recuperar su contraseña por favor ingrese correo.</p></li>
                            <li><label><img src="../../imagen/login/correo.png" width="20" height="20" />&nbsp;Correo:</label><input type="input" name="correo_usu" size="25" id="correo_usu"/></li>
                        </ol>
                </fieldset>
                <div class="clear"></div>
            	<div id="pie">
                	<div id="olvido_contrasena">
            			<a href="../../index.php">Volver</a>
            		</div>
                    <div id="entrar">
            			<input name="entrar" class="minimal" id="entrar" type="submit" value="Entrar"/>
                	</div>
                    <div class="clear"></div>
            		
        		</div>
			</form>
            <div id="contenedor_mensaje">
            	<div id="contenedor_exito" class="exito mensaje">La contraseña ha sido enviada a su correo electrónico<input class="cerrar_mensaje" type="image" src="../../imagen/mensaje/cerrar.png" id="exito_mensaje"/></div>
                <div class="alerta mensaje">Correo Incorrecto<input class="cerrar_mensaje" type="image" src="../../imagen/mensaje/cerrar.png" id="alerta_mensaje"/></div>  
                <div class="error_mensaje mensaje">Se ha producido un error al ingresar al sistema
                  <input class="cerrar_mensaje" type="image" src="../../imagen/mensaje/cerrar.png" id="error_mensaje"/></div>
            </div>
        </div>
    </div>
</body>
</html>