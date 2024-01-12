<?php
session_start();                      				
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

$valor = $_POST['valor'];
$_SESSION['sesion_anio_uf_panel'] = $valor;
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th></th>
            <?php 
            $consulta = "SELECT * FROM uf_uf WHERE YEAR(fecha_uf) = ".$valor."";
            $conexion->consulta($consulta);
            $fila_consulta_uf_original = $conexion->extraer_registro();
            $fila_consulta_uf = array();
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
        for ($i=1; $i <= 31; $i++) { 
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <?php 
                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
                        $id_mes = $fila['id_mes'];
                        if($id_mes < 10){
                            $mes_fecha = "0".$id_mes;
                        }
                        else{
                            $mes_fecha = $id_mes;
                        }

                        if($i < 10){
                            $dia_fecha = "0".$i;
                        }
                        else{
                            $dia_fecha = $i;
                        }
                        $fecha_uf = $dia_fecha."-".$mes_fecha."-".$valor;

                        $fecha_uf = date("Y-m-d",strtotime($fecha_uf));

                        if(in_array($fecha_uf,$fila_consulta_uf)){
                            $key = array_search($fecha_uf, $fila_consulta_uf);
                            $valor_uf = $fila_consulta_uf[$key + 1];
                        }
                        else{
                            $valor_uf = 0; 
                        }
                       
                        
                        // if(is_array($fila_consulta_uf)){
                        //     foreach ($fila_consulta_uf as $fila_uf) {
                        //         $dia_fecha = date("j",strtotime($fila_uf["fecha_uf"]));
                        //         $mes_fecha = date("j",strtotime($fila_uf["n"]));
                        //         if($dia_fecha == $i && $mes_fecha == $id_mes){
                        //             $valor_uf = $fila_uf["valor_uf"];
                        //             break;
                        //         }
                        //         else{
                        //             $valor_uf = 0;
                        //         }
                        //     }
                        // }
                        // else{
                        //     $valor_uf = 0;
                        // }
                        ?>
                        <td class="edita"><a href="#" id="<?php echo $i."-".$id_mes; ?>" data-type="text" data-pk="<?php echo $fecha_uf;?>" data-value="<?php echo $valor_uf;?>" title="Ingrese Valor Uf"><?php echo number_format($valor_uf, 2, ',', '.');?></a></td>
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
<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS?>plugins/bootstrap3-editable/js/bootstrap-editable.js"></script>
<script type="text/javascript">
    $(function(){
        // $.fn.editable.defaults.mode = 'inline';   
        $('.edita a').editable({
            url: 'update.php',
            success: function(data) {
                location.reload();
                
            }
            
        });
        
    });
</script>