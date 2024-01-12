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
<div class="col-sm-12" style="margin-top: 10px;">
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">AGREGAR OBSERVACIÓN CLIENTE</h3>
        </div>
        <div class="box-body no-padding">
        	<form id="formulario" role="form" method="post" action="../propietario/insert_observacion.php">
		        <?php  
		        $consulta = 
		            "
		            SELECT
		                rut_pro,
		                nombre_pro,
		                nombre2_pro,
		                apellido_paterno_pro,
		                apellido_materno_pro
		            FROM
		                propietario_propietario AS pro 
		                
		            WHERE
		                pro.id_pro = ?
		            ";
		        $conexion->consulta_form($consulta,array($id_pro));
		        $fila = $conexion->extraer_registro_unico();
		        $rut_pro = utf8_encode($fila['rut_pro']);
		        $nombre_pro = utf8_encode($fila['nombre_pro']);
		        $nombre2_pro = utf8_encode($fila['nombre2_pro']);
		        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
		        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
		                        
		        ?>
		        <input type="hidden" name="id" id="id" value="<?php echo $id_pro;?>"></input>
		        <div class="box-body">
		            <div class="row">
		                <div class="col-sm-12">
		                    <h4>Cliente: <?php echo $nombre_pro." ".$nombre2_pro." ".$apellido_paterno_pro." ".$apellido_materno_pro;?></h4>
		                </div>
		                
		                <div class="col-sm-12">
		                    <div class="form-group">
		                        <label for="descripcion">Observación:</label>
		                        <textarea name="descripcion" class="form-control" id="descripcion"></textarea>
		                    </div>
		                </div>
		                <div class="col-sm-6">
		                    
		                </div>
		            </div>
		            <div id="contendor_boton" class="box-footer">
		                <button type="submit" class="btn btn-primary pull-right">Ingresar</button>
		            </div>
		        </div>
		        <!-- /.box-body -->
		        
		    </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.numeric.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.numero').numeric();
        $("#formulario").validate({
            rules: {
                descripcion: { 
                    required: true
                }

            },
            messages: {
                descripcion: {
                    required: "Ingrese descripción"
                }
            }
        });


        

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información actualizada con éxito!",
                    icon: "success"
                    
                }).then(()=>window.history.back());
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