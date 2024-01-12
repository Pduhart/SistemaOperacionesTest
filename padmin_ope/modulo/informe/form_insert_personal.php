<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}

include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$id_ase = $_POST["valor"];


$consulta = 
    "
    SELECT
        ase.id_viv,
        ase.id_tip_ase,
        ase.fecha_ase,
        ase.id_usu,
        viv.nombre_viv,
        tor.id_tor,
        tor.nombre_tor, 
        con.id_con, 
        con.nombre_con,
        usu.nombre_usu,
        usu.apellido1_usu,
        usu.apellido2_usu 
    FROM
        aseo_aseo AS ase
        INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ase.id_viv
        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
        INNER JOIN aseo_tipo_aseo AS tip_ase ON tip_ase.id_tip_ase = ase.id_tip_ase
        INNER JOIN aseo_estado_aseo AS est_ase ON est_ase.id_est_ase = ase.id_est_ase
        LEFT JOIN usuario_usuario AS usu ON usu.id_usu = ase.id_usu
    WHERE 
        ase.id_ase = ?
    ";
$conexion->consulta_form($consulta,array($id_ase));
$fila = $conexion->extraer_registro_unico();
$id_viv = utf8_encode($fila['id_viv']);
$id_tip_ase = utf8_encode($fila['id_tip_ase']);
$fecha_ase = utf8_encode($fila['fecha_ase']);
$id_usu = utf8_encode($fila['id_usu']);
$nombre_viv = utf8_encode($fila['nombre_viv']);
$id_tor = utf8_encode($fila['id_tor']);
$nombre_tor = utf8_encode($fila['nombre_tor']);
$id_con = utf8_encode($fila['id_con']);
$nombre_con = utf8_encode($fila['nombre_con']);
$nombre_usu = utf8_encode($fila['nombre_usu']);
$apellido1_usu = utf8_encode($fila['apellido1_usu']);
$apellido2_usu = utf8_encode($fila['apellido2_usu']);
if($id_tip_ase == 1){
    $tipo_aseo = "Checkin";
}
else{
    $tipo_aseo = "Checkout";
}
$fecha_ase = date("d-m-Y",strtotime($fecha_ase));
?>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i> Asignar Personal</h4>
        </div>
        

        <div class="modal-body">

            <label><b>Departamento/a:</b> </label> <span><?php echo $nombre_viv;?></span><br>
            <label><b>Tipo :</b> </label> <span><?php echo $tipo_aseo;?></span><br>
            <label><b>Fecha :</b> </label> <span><?php echo $fecha_ase;?></span><br>

            <form id="formulario" method="POST" action="insert_personal.php" role="form">
                <input type="hidden" id="id" value="<?php echo $id_ase;?>" name="id" />
                
                <div class="modal-body">
                    
                        

                        <div class="row margin-bottom-40">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="personal">Personal:</label>
                                    <select class="form-control select2" id="personal" name="personal"> 
                                        <option value="<?php echo $id_usu;?>"><?php echo $nombre_usu." ".$apellido1_usu." ".$apellido2_usu;?></option>
                                        <?php  
                                        $consulta = "SELECT * FROM usuario_usuario WHERE id_est_usu = 1 AND id_per = 4 AND NOT id_usu = ".$id_usu." ORDER BY nombre_usu";
                                        $conexion->consulta($consulta);
                                        $fila_consulta = $conexion->extraer_registro();
                                        if(is_array($fila_consulta)){
                                            foreach ($fila_consulta as $fila) {
                                                ?>
                                                <option value="<?php echo $fila['id_usu'];?>"><?php echo utf8_encode($fila['nombre_usu']." ".$fila['apellido1_usu']." ".$fila['apellido2_usu']);?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                            </div>
                            
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    
                    <button type="submit" id="guarda_retiro" class="btn btn-primary">Registrar Personal</button>
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
        </form>
        </div>
    </div>
</div>

<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/validate/jquery.validate.js"></script>
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>
<script type="text/javascript">
      
  $(document).ready(function () {
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Información ingresada con éxito!",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#9bde94",
                    confirmButtonText: "Aceptar",
                    closeOnConfirm: false
                },
                function () {
                    location.href = "informe_aseo.php";
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Registro ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<input type="button" name="boton" class="btn2" value="Guardar" id="bt"/>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<input type="button" name="boton" class="btn2" value="Guardar" id="bt"/>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            //  }
        }
        
        $('#formulario').submit(function () {
            
            if ($("#formulario").validate().form() == true){
                //$('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
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

        $("#formulario").validate({
            rules: {
                personal: { 
                    required: true
                }
            },
            messages: { 
                personal: {
                    required: "Seleccione_personal"
                }
            }
        });

  });  
     
</script>   
