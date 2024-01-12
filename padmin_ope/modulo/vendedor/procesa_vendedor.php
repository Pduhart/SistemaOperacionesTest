<?php
session_start();
require "../../config.php"; 
require_once _INCLUDE."head.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: "._ADMIN."index.php");
    exit();
}
if(!isset($_SESSION["modulo_vendedor_panel"])){
    header("Location: "._ADMIN."panel.php");
    exit();
}
if(!isset($_POST["id"])){
	header("Location: "._ADMIN."index.php");
	exit();
}
require "../../config.php";
include '../../class/conexion.php';
$conexion = new conexion();


$id = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;

?>

<?php 
$data = "";

$consulta_nueva = "
	SELECT pro.rut_pro, pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro, pro.id_pro 
	FROM propietario_propietario AS pro, vendedor_propietario_vendedor AS ven_pro 
	WHERE pro.id_est_pro = 1 
	AND ven_pro.id_pro = pro.id_pro 
	AND ven_pro.id_vend = ".$id." 
	ORDER BY pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro ASC";

// $consulta = "SELECT rut_pro, nombre_pro, apellido_paterno_pro, apellido_materno_pro, correo_pro, fono_pro, id_pro FROM propietario_propietario WHERE id_est_pro = 1 ORDER BY nombre_pro, apellido_paterno_pro, apellido_materno_pro ASC";
$conexion->consulta($consulta_nueva);

$cantidad = $conexion->total();
$clientes_actuales = "";

$fila_consulta = $conexion->extraer_registro();

if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	$clientes_actuales .= $fila['id_pro'].",";
        $rut_pro = utf8_encode($fila['rut_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_pro = $fila['id_pro'];
        $data .= '{"name": "'.$nombre_pro.' '.$apellido_paterno_pro.' '.$apellido_materno_pro.' | '.$rut_pro.'", "id":'.$id_pro.', "disabled": false, "selected": true },';
    }
}

$clientes_actuales = substr($clientes_actuales, 0, -1);

$consulta_libres = "
	SELECT pro.rut_pro, pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro, pro.id_pro 
	FROM propietario_propietario AS pro 
	WHERE pro.id_est_pro = 1 
	AND NOT EXISTS(
        SELECT 
            ven_pro.id_pro
        FROM
            vendedor_propietario_vendedor AS ven_pro
        WHERE
            ven_pro.id_pro = pro.id_pro)
    ORDER BY pro.nombre_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro ASC";

$conexion->consulta($consulta_libres);

$cantidad_libres = $conexion->total();

$fila_consulta = $conexion->extraer_registro();

if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
        $rut_pro = utf8_encode($fila['rut_pro']);
        $nombre_pro = utf8_encode($fila['nombre_pro']);
        $apellido_paterno_pro = utf8_encode($fila['apellido_paterno_pro']);
        $apellido_materno_pro = utf8_encode($fila['apellido_materno_pro']);
        $correo_pro = utf8_encode($fila['correo_pro']);
        $fono_pro = utf8_encode($fila['fono_pro']);
        $id_pro = $fila['id_pro'];
        $data .= '{"name": "'.$nombre_pro.' '.$apellido_paterno_pro.' '.$apellido_materno_pro.' | '.$rut_pro.'", "id":'.$id_pro.', "disabled": false, "selected": false },';
    }
}
// var_dump(count($clientes_actuales));



$data = substr($data, 0, -1);


?>

  <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->
<link rel="stylesheet" type="text/css" href="<?php echo _ASSETS?>plugins/Searchable-Multi-select-jQuery-Dropdown/jquery.dropdown.css">
  <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> -->

<link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">
<style type="text/css">
	.dropdown-display-label .dropdown-chose-list {
	    display: inline-block;
	    padding: 0 5px;
	    max-height: 200px;
	    overflow-y: auto;
	    /*margin-top: 10px;*/
	}

	.dropdown-display-label .dropdown-selected {
	    min-width: 32%;
	}

	.dropdown-chose-list{
		width: 100%;
	}

	.dropdown-main ul {
	    overflow-x: hidden;
	    overflow-y: auto;
	    max-height: 180px;
	    margin: 0;
	    padding: 0;
	}

	.dropdown-main {
     	position: relative; 
    /* top: 100%; */
	    left: 0;
	    z-index: 1010;
	    width: 100%;
	    color: #444;
	    box-sizing: border-box;
	    background-color: #fff;
	    border: 1px solid #ccc;
	    border-radius: 0 0 4px 4px;
	    box-shadow: none;
	    margin-top: 5px !important;
	    /*border-top: 0;*/
	    margin-bottom: 10px;
	    padding: 4px 7px;
	    display: none;
	}

	.dropdown-group, .dropdown-option {
	    margin: 0;
	    margin-left: 40px;
	    list-style: none;
	    line-height: 24px;
	    word-wrap: break-word;
	}

	.dropdown-chose{
		background-color: rgba(217,230,238,.4);
	}

	.ver_det{
		cursor: pointer;
		color: #3c8dbc;
		margin-left: 10px;
    	position: absolute;
	}

	.cont_li{
		border-top: 1px solid rgba(60,141,188,.2);
	}
</style>

<div class="box-header">
    <h3 class="box-title"><i class="fa fa-list" aria-hidden="true"></i> Clientes</h3>
</div>

    <input type="hidden" name="vendedor" id="vendedor" value="<?php echo $id;?>">
    <input type="hidden" name="cli_actuales" id="cli_actuales" value="<?php echo $clientes_actuales;?>">
    <div class="box-body">
	<div class="container">
	    <div class="dropdown-sin-2 active">
	        <select style="display:none" id="my-select" multiple placeholder="Select"></select>
	    </div>
	</div>
        
    </div>
    <br>
    <div class="box-footer" id="contenedor_boton">
    	<button type="button" id="registro" class="btn btn-primary pull-right">Registrar</button>
    </div>

<?php 
include_once _INCLUDE."js_comun.php";
 ?>
 <script src="<?php echo _ASSETS?>plugins/Searchable-Multi-select-jQuery-Dropdown/jquery.dropdown.js"></script>
<script>
    $(document).ready(function() {
    	$('.dropdown-sin-2').dropdown({
	      data: <?php echo "[".$data."]"; ?>,
	      multipleMode:'label',
	      input: '<input type="text" maxLength="20" placeholder="Buscar...">'
	    });


	    show_mod = function(id){
	        valor = id;
            $.ajax({
                type: 'POST',
                url: ("../propietario/form_detalle.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
	    }



    	function resultado(data) {
            if (data.envio == 1) {
                swal({
                    title: "Excelente!",
                    text: "Asignación ingresada con éxito!",
                    icon: "success"
                    
                }).then(()=>{
                    document.getElementById("vendedor").value = '0';
                    location.reload();
                });
            }
            if (data.envio == 2) {
                swal("Atención!", "Usuario ya ha sido ingresado", "warning");
                $('#contenedor_boton').html('<button type="button" id="registro" class="btn btn-primary pull-right">Registrar</button>');
            }
            if (data.envio == 3) {
                swal("Error!", "Favor intentar denuevo o contáctese con administrador", "error");
                $('#contenedor_boton').html('<button type="button" id="registro" class="btn btn-primary pull-right">Registrar</button>');
            }
            // if(data.envio != ""){
            //  alert(data.envio);
            // }
        }

    	
    	$(document).on( "click","#registro" , function() {
    		$('#contenedor_boton').html('<img src="../../assets/img/loading.gif">');
    		var clientes = new Array();

            $('#my-select').find(":selected").each(function() {
            	clientes.push($(this).val());
		        
		    });

		    var_vendedor = $('#vendedor').val();
		    var_clientes_actuales = $('#cli_actuales').val();


        	// alert(var_clientes_actuales);
            $.ajax({
                data: "vendedor="+var_vendedor+"&clientes_nuevos="+clientes+"&clientes_actuales="+var_clientes_actuales,
                type: 'POST',
                url: ("insert_cliente.php"),
                dataType: 'json',
                success: function (data) {
                    resultado(data);
                }
            })
        });
	
    });
</script>