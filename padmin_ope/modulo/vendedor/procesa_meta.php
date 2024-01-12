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
include '../../class/conexion.php';
$conexion = new conexion();


$id_vend = isset($_POST["id"]) ? utf8_decode($_POST["id"]) : 0;

?>

<?php 


?>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/bootstrap3-editable/css/bootstrap-editable.css">
<style type="text/css">
    .btn.focus, .btn:focus, .btn:hover {
        color: #333;
        font-weight: 700;
        text-decoration: none;
        -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
        box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
    }
</style>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
        <?php 
        // $actual = $_SESSION["sesion_periodo_filtro_panel"];
        $actual = date("Y");
        $anterior = $actual-1;
        $futuro = $actual+1;

        if(isset($_SESSION['sesion_anio_uf_panel'])){
            $seleccion = $_SESSION['sesion_anio_uf_panel'];
        }
        else{
            $seleccion = $actual;
        }
        ?>
        <div class="box-tools pull-right">
            <?php  
            if($seleccion == $anterior){
                $estilo_anterior = 'style="font-weight: bold; color: #000;"';
                $estilo_actual = '';
                $estilo_futuro = '';
            }
            else if($seleccion == $actual){
                $estilo_anterior = '';
                $estilo_actual = 'style="font-weight: bold; color: #000;"';
                $estilo_futuro = '';
            } else {
            	$estilo_anterior = '';
                $estilo_actual = '';
                $estilo_futuro = 'style="font-weight: bold; color: #000;"';
            }
            ?>
            
            <button type="button" <?php echo $estilo_actual;?> value="<?php echo $actual ?>" class="btn btn-box-tool boton"><?php echo $actual ?></button>
            <!--<button type="button" <?php echo $estilo_futuro;?> value="<?php echo $futuro ?>" class="btn btn-box-tool boton"><?php echo $futuro ?></button>-->
        </div>
    </div>
    <div class="table-responsive" id="contenedor">
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <?php 
                    //echo $_SESSION["sesion_prueba_meta"]."<br>";
                    if(isset($_SESSION['sesion_anio_uf_panel'])){
                       $actual = $_SESSION["sesion_anio_uf_panel"];
                       unset($_SESSION["sesion_anio_uf_panel"]); 
                    }
                    




                    $fila_consulta_uf = array();
                    $consulta = "SELECT * FROM vendedor_meta_vendedor WHERE anio_mes = ".$actual." AND id_vend = ".$id_vend." ";
                    $conexion->consulta($consulta);
                    $fila_consulta_uf_original = $conexion->extraer_registro();
                    if(is_array($fila_consulta_uf_original)){
                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_uf_original));
                        
                        foreach($it as $v) {
                            $fila_consulta_uf[]=$v;
                        }
                    }





                    $consulta = "SELECT * FROM mes_mes ORDER BY id_mes ASC";
                    $conexion->consulta($consulta);
                    $fila_consulta = $conexion->extraer_registro();
                    if(is_array($fila_consulta)){
                        foreach ($fila_consulta as $fila) {
                            $nombre_mes = utf8_encode($fila['nombre_mes']);
                            $id_mes = $fila['id_mes'];
                     ?>
                    <th><?php echo $nombre_mes ?></th>
                    <?php }
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                for ($i=1; $i <= 1; $i++) { 
                    ?>
                    <tr>
                        <td>Meta</td>
                        <?php 
						$consulta = "
							SELECT 
						        a.id_mes,
						        b.id_vend,
						        b.valor_met_ven
						      FROM
						        mes_mes AS a
						        LEFT JOIN vendedor_meta_vendedor AS b ON b.id_mes = a.id_mes AND 
						        (b.id_vend = '".$id_vend."' OR b.id_vend = NULL) AND 
						        (b.anio_mes = '".$actual."' OR  b.anio_mes = NULL)
						      
						      ORDER BY
						        a.id_mes
						      ASC";
	                    $conexion->consulta($consulta);
	                    $fila_consulta = $conexion->extraer_registro();	

                        if(is_array($fila_consulta)){
                            foreach ($fila_consulta as $fila) {
                                $id_mes = $fila['id_mes'];
                                $valor_met_ven = $fila['valor_met_ven'];

                               	$valores = $id_vend."-".$id_mes."-".$actual;
                                ?>
                                <td class="edita"><a href="#" id="<?php echo $i."-".$id_mes; ?>" data-type="text" data-pk="<?php echo $valores;?>" data-value="<?php echo $valor_met_ven;?>" title="Ingrese Valor Meta"><?php echo $valor_met_ven;?></a></td>
                                <?php
                            }
                        } 
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
include_once _INCLUDE."js_comun.php";
 ?>
<script src="<?php echo _ASSETS?>plugins/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script type="text/javascript">
    $(function(){
        // $.fn.editable.defaults.mode = 'inline';   
        $('.edita a').editable({
            url: 'update_meta.php',
            success: function(data) {
                location.reload();
                
            }
            
        });

        // cambio de año   
        $('.boton').click(function () {
            // alert("hola");
            $(".boton").attr("style","");
            $(this).attr("style","font-weight: bold; color: #000;");
            var valor = $(this).val();
            $.ajax({
                    data: 'valor='+valor,
                    type: 'POST',
                    url: 'procesa.php',
                    success: function (data) {
                        $('#contenedor').html(data);
                    }
                })
        });
        
    });
</script>