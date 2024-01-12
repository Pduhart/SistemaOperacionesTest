<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'Liquidaciones_'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
// require_once _INCLUDE."head.php";
?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<meta charset="utf-8">
</head>

<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();


$consulta = "SELECT id_res FROM reserva_reserva ORDER BY id_res";
$conexion->consulta($consulta);
$fila_consulta = $conexion->extraer_registro();
$fila_consulta_condominio_original = $fila_consulta;

?>
<!-- <div class="row"> -->
    <!-- <div class="col-sm-12" id="contenedor_filtro"> -->
        <!-- <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px"> -->
          <!-- <i>Filtro:  -->
            <?php 
            $filtro_consulta = "";
            $elimina_filtro = 0;
            if(isset($_SESSION["sesion_filtro_numero_reserva_panel"])){
                $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                $fila_consulta_condominio = array();
                foreach($it as $v) {
                    $fila_consulta_condominio[]=$v;
                }
                $elimina_filtro = 1;
                
                if(is_array($fila_consulta_condominio)){
                    foreach ($fila_consulta_condominio as $fila) {
                        if(in_array($_SESSION["sesion_filtro_numero_reserva_panel"],$fila_consulta_condominio)){
                            $key = array_search($_SESSION["sesion_filtro_numero_reserva_panel"], $fila_consulta_condominio);
                            $texto_filtro = $fila_consulta_condominio[$key];
                            
                        }
                    }
                }
                ?>
                <!-- <span class="label label-primary"><?php // echo utf8_encode($texto_filtro);?></span>   -->
                <?php
                $filtro_consulta .= " AND res.id_res >= ".$_SESSION["sesion_filtro_numero_reserva_panel"];
            }
            else{
                ?>
                <!-- <span class="label label-default">Sin filtro</span>  -->
                <?php       
            }
                                                            
            if ($elimina_filtro<>0) {
              ?>
              <!-- <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i> -->
              <?php
            }

            ?>
            
        <!-- </i>
      </h6>
    </div>
</div> -->
<?php
// require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
  <!-- Full Width Column -->
  
        <table class="table table-condensed table-bordered">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Condominio</th>
                    <!-- <th>Torre</th> -->
                    <!-- <th>Modelo</th> -->
                    <th>Depto.</th>
                    <th>Propietario</th>
                    <th>Programa</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Cant. Noche</th>
                    <th>Cant. Pasaj.</th>
                    <th>Temporada</th>
                    <th>Ingresos Total</th>
                    <th>Comisión Adm.</th>
                    <?php
                    $consulta = "SELECT id_int_ser, nombre_int_ser FROM servicio_interno_servicio WHERE id_est_int_ser = 1 ORDER BY nombre_int_ser";
                    $conexion->consulta($consulta);
                    $fila_consulta_servicio = $conexion->extraer_registro();
                    if(is_array($fila_consulta_servicio)){
                        foreach ($fila_consulta_servicio as $fila_servicio) {
                            ?>
                            <th><?php echo utf8_encode($fila_servicio['nombre_int_ser']);?></th>
                            <?php
                        }
                    }  
                    ?>
                    <th>Extras</th>
                    <th>Monto Liquidado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $consulta = 
                    "
                    SELECT
                        res.programa_base_res,
                        res.id_res,
                        con.nombre_con,
                        tor.nombre_tor,
                        mode.nombre_mod,
                        viv.nombre_viv,
                        prog.nombre_prog,
                        res.fecha_desde_res,
                        res.fecha_hasta_res,
                        res.cantidad_dia_res,
                        res.cantidad_pasajero_res,
                        tip_res.nombre_tip_res,
                        res.monto_interno_res,
                        res.monto_comision_res,
                        res.monto_comision_base_res,
                        res.monto_dia_res,
                        res.monto_dia_base_res,
                        res.monto_adicional_res,
                        res.nombre_propietario_res,
                        res.apellido_paterno_propietario_res,
                        res.apellido_materno_propietario_res,
                        est_res.nombre_est_res,
                        res.id_est_res
                    FROM
                        reserva_reserva AS res
                        INNER JOIN reserva_tipo_reserva AS tip_res ON tip_res.id_tip_res = res.id_tip_res
                        INNER JOIN reserva_estado_reserva AS est_res ON est_res.id_est_res = res.id_est_res
                        INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
                        INNER JOIN programa_programa AS prog ON prog.id_prog = res.id_prog
                        INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                        INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                        INNER JOIN modelo_modelo AS mode ON mode.id_mod = viv.id_mod
                    WHERE
                        res.id_est_res = 1
                        ".$filtro_consulta."
                    ORDER BY 
                        res.id_res
                    DESC
                    ";
                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();

                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
                        $programa_base = $fila["programa_base_res"];
                        $cantidad_dia_res = $fila["cantidad_dia_res"];
                        $cantidad_pasajero_res = $fila["cantidad_pasajero_res"];

                        if($programa_base == 1){
                            $monto_total_res = $fila["monto_total_base_res"];
                            $monto_dia_res = $fila["monto_dia_base_res"];
                            $monto_comision_res = $fila["monto_comision_base_res"];
                            
                        }   
                        else{
                            $monto_total_res = $fila["monto_total_res"];
                            $monto_dia_res = $fila["monto_dia_res"];
                            $monto_comision_res = $fila["monto_comision_res"];
                        }

                        $monto_total_res =   ($monto_dia_res * $cantidad_dia_res);

                        

                        if($fila["fecha_desde_res"] != "0000-00-00"){
                            $fecha_desde = date("d/m/Y",strtotime($fila["fecha_desde_res"]));
                        }
                        else{
                            $fecha_desde = "";
                        }
                        
                    
                        if($fila["fecha_hasta_res"] != "0000-00-00"){
                            $fecha_hasta = date("d/m/Y",strtotime($fila["fecha_hasta_res"]));
                        }
                        else{
                            $fecha_hasta = "";
                        }


                        if($fila["programa_base_res"] == 2){
                            $monto = ($fila["monto_dia_res"] * $fila["cantidad_dia_res"]) - $fila["monto_comision_res"] - $fila["monto_interno_res"];
                        }
                    
                        if($fila["programa_base_res"] == 1){
                            $monto = ($fila["monto_dia_base_res"] * $fila["cantidad_dia_res"]) - $fila["monto_comision_base_res"] - $fila["monto_interno_res"];
                        }
                        
                        
                        ?>
                        <tr>
                            <td><?php echo utf8_encode($fila['id_res']);?></td>
                            <td><?php echo utf8_encode($fila['nombre_con']);?></td>
                            <!--<td><?php //echo utf8_encode($fila['nombre_tor']);?></td>
                            <td><?php// echo utf8_encode($fila['nombre_mod']);?></td>-->
                            <td><?php echo utf8_encode($fila['nombre_viv']);?></td>
                            <td><?php echo utf8_encode($fila['nombre_propietario_res']." ".$fila['apellido_paterno_propietario_res']." ".$fila['apellido_materno_propietario_res']);?></td>
                            <td><?php echo utf8_encode($fila['nombre_prog']);?></td>
                            <td><?php echo utf8_encode($fecha_desde);?></td>
                            <td><?php echo utf8_encode($fecha_hasta);?></td>
                            <td><?php echo utf8_encode($fila['cantidad_dia_res']);?></td>
                            <td><?php echo utf8_encode($fila['cantidad_pasajero_res']);?></td>
                            <td><?php echo utf8_encode($fila['nombre_tip_res']);?></td>

                            <td><?php echo number_format($monto_total_res, 0, ',', '.');?></td>
                            <td><?php echo number_format($monto_comision_res, 0, ',', '.');?></td>
                            
                            <?php
                            
                            if(is_array($fila_consulta_servicio)){
                                foreach ($fila_consulta_servicio as $fila_servicio) {

                                    $consulta = 
                                        "
                                        SELECT 
                                            int_res.id_ser_int_res,
                                            int_res.nombre_ser_int_res,
                                            int_res.valor_ser_int_res,
                                            tip_cob.id_tip_cob,
                                            tip_cob.nombre_tip_cob
                                        FROM
                                            reserva_servicio_interno_reserva AS int_res,
                                            cobro_tipo_cobro AS tip_cob
                                        WHERE 
                                            int_res.id_tip_cob = tip_cob.id_tip_cob AND
                                            int_res.id_res = ".$fila["id_res"]." AND
                                            int_res.id_int_ser = ".$fila_servicio["id_int_ser"]."
                                        ORDER BY 
                                            int_res.id_int_ser 
                                        ASC
                                        ";
                                    $conexion->consulta($consulta);
                                    $fila_consulta_servicio_reserva = $conexion->extraer_registro();
                                    if(is_array($fila_consulta_servicio_reserva)){
                                        foreach ($fila_consulta_servicio_reserva as $fila_servicio_reserva) {
                                            $valor = $fila_servicio_reserva["valor_ser_int_res"];
                                            
                                            switch ($fila_servicio_reserva["id_tip_cob"]) {
                                                case 1:
                                                    $cantidad_servicio_dia = '';
                                                    $cantidad_servicio_persona = '';
                                                    $valor_total = $valor;
                                                    break;
                                                case 2:
                                                    $cantidad_servicio_dia = $cantidad_dia_res;
                                                    $cantidad_servicio_persona = '';
                                                    $valor_total = $valor * $cantidad_dia_res;
                                                    break;
                                                case 3:
                                                    $cantidad_servicio_dia = '';
                                                    $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                    $valor_total = $valor * $cantidad_pasajero_res;
                                                    break;
                                                case 4:
                                                    $cantidad_servicio_dia = $cantidad_dia_res;
                                                    $cantidad_servicio_persona = $cantidad_pasajero_res;
                                                    $valor_total = ($valor * $cantidad_pasajero_res) * $cantidad_dia_res;
                                                    break;
                                            }
                                            $valor_total_acumulado = $valor_total_acumulado + $valor_total;
                                            
                                            ?>
                                            <td>$ <?php echo number_format($valor_total, 0, '', '.');?></td>
                                            <?php
                                         }
                                    }
                                    else{
                                        ?>
                                        <td></td>
                                        <?php
                                    }
                                }
                            } 
                            $consulta = 
                                "
                                SELECT 
                                    IFNULL(SUM(int_res.valor_ser_int_res),0) AS valor
                                FROM
                                    reserva_servicio_interno_reserva AS int_res
                                WHERE 
                                    int_res.id_res = ".$fila["id_res"]." AND
                                    int_res.extra_ser_int_res = 1
                                ";
                            $conexion->consulta($consulta);
                            $fila_servicio = $conexion->extraer_registro_unico(); 
                            ?>

                            <td><?php echo number_format($fila_servicio["valor"], 0, ',', '.');?></td>
                            <td><?php echo number_format($monto, 0, ',', '.');?></td>

                            
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            
        </table>

</body>
</html>
