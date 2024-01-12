<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'listado_abonos'.date('d-m-Y');

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Venta - Listado</title>
<!-- DataTables -->

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body>
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->

      <!-- Main content -->
      <section class="content">
        <div class="col-sm-12">
            <!-- general form elements -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <?php  
                        $consulta = 
                            "
                            SELECT 
                                usu.id_mod 
                            FROM 
                                usuario_usuario_proceso AS usu,
                                usuario_proceso AS proceso
                            WHERE 
                                usu.id_usu = ".$_SESSION["sesion_id_panel"]." AND
                                usu.id_mod = 1 AND
                                proceso.opcion_pro = 12 AND
                                proceso.id_pro = usu.id_pro AND
                                proceso.id_mod = 1
                            ";
                        $conexion->consulta($consulta);
                        $cantidad_opcion = $conexion->total();
                        if($cantidad_opcion > 0){
                            ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="box-body" style="padding-top: 0">
                                        <!-- <div class="row"> -->
                                            <!-- <div class="col-sm-12 filtros"> -->
                                                <!-- <div class="row"> -->
                                                    <!-- <div class="col-sm-3 radiomio"> -->
                                                        <!-- <div class="radio bg-grays" style="margin-top: 20px; padding: 5px"> -->
                                                            <?php  
                                                            $consulta = "SELECT * FROM pago_categoria_pago ORDER BY nombre_cat_pag";
                                                            $conexion->consulta($consulta);
                                                            $fila_consulta = $conexion->extraer_registro();
                                                            if(is_array($fila_consulta)){
                                                                foreach ($fila_consulta as $fila) {
                                                                    ?>
                                                                    <!-- <input id="categoria_pago<?php //echo $fila['id_cat_pag'];?>" type="radio" name="categoria_pago" class="filtro" value="<?php //echo $fila['id_cat_pag'];?>"> -->
                                                                    <!-- <label for="categoria_pago<?php //echo $fila['id_cat_pag'];?>"><?php //echo utf8_encode($fila['nombre_cat_pag']);?></label> -->
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        <!-- </div>  -->
                                                    <!-- </div> -->
                                                    <!-- <div class="col-sm-8"> -->
                                                        
                                                        <!-- <div class="col-sm-7"> -->
                                                            <!-- <div class="col-sm-6"> -->
                                                                <!-- <div class="form-group"> -->
                                                                    <!-- <label for="banco">Banco:</label> -->
                                                                      <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                    <!-- <select class="form-control select2 chico" id="banco" name="banco">  -->
                                                                        <!-- <option value="">Seleccione banco</option> -->
                                                                        <?php  
                                                                        $consulta = "SELECT * FROM banco_banco ORDER BY nombre_ban";
                                                                        $conexion->consulta($consulta);
                                                                        $fila_consulta_banco_original = $conexion->extraer_registro();
                                                                        if(is_array($fila_consulta_banco_original)){
                                                                            foreach ($fila_consulta_banco_original as $fila) {
                                                                                ?>
                                                                                <!--<option value="<?php //echo $fila['id_ban'];?>"><?php //echo utf8_encode($fila['nombre_ban']);?></option>-->
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    <!-- </select> -->
                                                                <!-- </div> -->
                                                            <!-- </div> -->
                                                            <!-- <div class="col-sm-6"> -->
                                                                <!-- <div class="form-group"> -->
                                                                    <!-- <label for="cliente">Cliente:</label> -->
                                                                      <!-- <span class="input-group-addon"><i class="fa fa-car" aria-hidden="true"></i></span> -->
                                                                    <!-- <select class="form-control chico select2" id="cliente" name="cliente">  -->
                                                                        <!-- <option value="">Seleccione Cliente</option> -->
                                                                        <?php  
                                                                        $consulta = "SELECT id_pro,nombre_pro,apellido_paterno_pro,apellido_materno_pro FROM propietario_propietario ORDER BY nombre_pro, apellido_paterno_pro, apellido_materno_pro";
                                                                        $conexion->consulta($consulta);
                                                                        $fila_consulta_propietario_original = $conexion->extraer_registro();
                                                                        if(is_array($fila_consulta_propietario_original)){
                                                                            foreach ($fila_consulta_propietario_original as $fila) {
                                                                                ?>
                                                                                <!-- <option value="<?php //echo $fila['id_pro'];?>"><?php //echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']);?></option> -->
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    <!-- </select> -->
                                                                <!-- </div> -->
                                                            <!-- </div> -->
                                                        <!-- </div> -->
                                                    <!-- </div> -->
                                                    
                                                <!-- </div> -->
                                                
                                            <!-- </div> -->
                                            
                                            
                                            
                                        <!-- </div> -->
                                        <!-- <div class="row"> -->
                                            <!-- <div class="col-sm-12" id="contenedor_filtro"> -->
                                                <!-- <button class="btn btn-xs btn-primary borra_sesion">Ver Todos</button> -->
                                                <!-- <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px"> -->
                                                  <!-- <i>Filtro:  -->
                                                    <?php 
                                                    $filtro_consulta = '';
                                                    $elimina_filtro = 0;
                                                    if(isset($_SESSION["sesion_filtro_categoria_pago_panel"])){
                                                        $elimina_filtro = 1;

                                                        $consulta = "SELECT * FROM pago_categoria_pago WHERE id_cat_pag = ".$_SESSION["sesion_filtro_categoria_pago_panel"]." ORDER BY nombre_cat_pag";
                                                        $conexion->consulta($consulta);
                                                        $fila_consulta = $conexion->extraer_registro();
                                                        if(is_array($fila_consulta)){
                                                            foreach ($fila_consulta as $fila) {
                                                                $texto_filtro = utf8_encode($fila['nombre_cat_pag']);
                                                                $filtro_consulta .= " AND cat_pag.id_cat_pag = '".$_SESSION["sesion_filtro_categoria_pago_panel"]."'";
                                                            }
                                                        }

                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo $texto_filtro;?></span> |  -->
                                                        <?php
                                                        //$filtro_consulta = " AND alu.id_alu = ".$_SESSION["id_alumno_filtro_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span> |  -->
                                                        <?php       
                                                    }
                                                    
                                                    if(isset($_SESSION["sesion_filtro_fecha_desde_panel"])){
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php// echo $_SESSION["sesion_filtro_fecha_desde_panel"];?></span> | -->
                                                        <?php
                                                        $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_desde_panel"]));
                                                        $filtro_consulta .= " AND pag.fecha_real_pag >= '".$fecha."'";
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span> |  -->
                                                        <?php       
                                                    }
                            
                            
                                                    if(isset($_SESSION["sesion_filtro_fecha_hasta_panel"])){
                                                        $elimina_filtro = 1;
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php// echo $_SESSION["sesion_filtro_fecha_hasta_panel"];?></span> | -->
                                                        <?php
                                                        $fecha = date("Y-m-d",strtotime($_SESSION["sesion_filtro_fecha_hasta_panel"]));
                                                        $filtro_consulta .= " AND pag.fecha_real_pag <= '".$fecha."'";
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span> | -->
                                                        <?php       
                                                    }

                                                    if(isset($_SESSION["sesion_filtro_banco_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_banco_original));
                                                        $fila_consulta_banco = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_banco[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_banco)){
                                                            foreach ($fila_consulta_banco as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_banco_panel"],$fila_consulta_banco)){
                                                                    $key = array_search($_SESSION["sesion_filtro_banco_panel"], $fila_consulta_banco);
                                                                    $texto_filtro = $fila_consulta_banco[$key + 1];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span> |   -->
                                                        <?php
                                                        $filtro_consulta .= " AND pag.id_ban = ".$_SESSION["sesion_filtro_banco_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span> |  -->
                                                        <?php       
                                                    }
                                                    if(isset($_SESSION["sesion_filtro_propietario_panel"])){
                                                        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_propietario_original));
                                                        $fila_consulta_propietario = array();
                                                        foreach($it as $v) {
                                                            $fila_consulta_propietario[]=$v;
                                                        }
                                                        $elimina_filtro = 1;
                                                        
                                                        if(is_array($fila_consulta_propietario)){
                                                            foreach ($fila_consulta_propietario as $fila) {
                                                                if(in_array($_SESSION["sesion_filtro_propietario_panel"],$fila_consulta_propietario)){
                                                                    $key = array_search($_SESSION["sesion_filtro_propietario_panel"], $fila_consulta_propietario);
                                                                    $texto_filtro = $fila_consulta_propietario[$key + 1]." ".$fila_consulta_propietario[$key + 2]." ".$fila_consulta_propietario[$key + 3];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <!-- <span class="label label-primary"><?php //echo utf8_encode($texto_filtro);?></span>  -->
                                                        <?php
                                                        $filtro_consulta .= " AND ven.id_pro = ".$_SESSION["sesion_filtro_propietario_panel"];
                                                    }
                                                    else{
                                                        ?>
                                                        <!-- <span class="label label-default">Sin filtro</span>  -->
                                                        <?php       
                                                    }
                                                    

                                                    if ($elimina_filtro<>0) {
                                                      ?>
                                                      <!-- <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i>  -->
                                                      <?php
                                                    }

                                                    ?>
                                                    
                                                <!-- </i> -->
                                              <!-- </h6>
                                            </div>
                                        </div> -->
                                            <table id="example" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Condominio</th>
                                                        <th>Depto</th>
                                                        <th>Rut Cliente</th>
                                                        <th>Nombre Cliente</th>
                                                        <th>Fono Cliente</th>
                                                        <th>Categoría</th>
                                                        <th>Banco</th>
                                                        <th>Forma de Pago</th>
                                                        <th>Fecha</th>
                                                        <th>Fecha Real</th>
                                                        <th>Documento</th>
                                                        <!-- <th>Serie</th> -->
                                                        <th>Monto</th>
                                                        <th>Estado</th>
                                                        <th>Valor UF</th>
                                                        <th>Monto Abono UF</th>
                                                    </tr>    
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $acumulado_monto = 0;
                                                    
                                                    
                                                    $consulta = 
                                                        "
                                                        SELECT
                                                            con.nombre_con,
                                                            pag.id_pag,
                                                            cat_pag.nombre_cat_pag,
                                                            ban.nombre_ban,
                                                            for_pag.nombre_for_pag,
                                                            pag.fecha_pag,
                                                            pag.fecha_real_pag,
                                                            pag.numero_documento_pag,
                                                            pag.numero_serie_pag,
                                                            pag.monto_pag,
                                                            est_pag.nombre_est_pag,
                                                            pag.id_est_pag,
                                                            pag.id_ven,
                                                            viv.nombre_viv,
                                                            pro.rut_pro,
                                                            pro.nombre_pro,
                                                            pro.apellido_paterno_pro,
                                                            pro.apellido_materno_pro,
                                                            pro.fono_pro
                                                        FROM 
                                                            pago_pago AS pag 
                                                            INNER JOIN pago_categoria_pago AS cat_pag ON cat_pag.id_cat_pag = pag.id_cat_pag
                                                            INNER JOIN pago_estado_pago AS est_pag ON est_pag.id_est_pag = pag.id_est_pag
                                                            LEFT JOIN banco_banco AS ban ON ban.id_ban = pag.id_ban
                                                            INNER JOIN pago_forma_pago AS for_pag ON for_pag.id_for_pag = pag.id_for_pag
                                                            INNER JOIN venta_venta AS ven ON ven.id_ven = pag.id_ven
                                                            INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = ven.id_viv
                                                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                                            INNER JOIN propietario_propietario AS pro ON pro.id_pro = ven.id_pro
                                                        WHERE 
                                                            pag.id_pag > 0 AND
                                                            ven.id_est_ven <> 3
                                                            ".$filtro_consulta."
                                                        ORDER BY 
                                                            pag.id_pag DESC
                                                        "; 
                                                    $conexion->consulta($consulta);
                                                    $fila_consulta = $conexion->extraer_registro();
                                                    if(is_array($fila_consulta)){
                                                        foreach ($fila_consulta as $fila) {
                                                            if ($fila['fecha_cot'] == '0000-00-00') {
                                                                $fecha_cotizacion = "";
                                                            }
                                                            else{
                                                                $fecha_cotizacion = date("d/m/Y",strtotime($fila['fecha_cot']));
                                                            }
                                                            $acumulado_monto = $acumulado_monto + $fila['monto_ven'];
                                                            
                                                            
                                                            ?>
                                                            <tr>
                                                                <td><?php echo utf8_encode($fila['nombre_con']); ?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_viv']); ?></td>
                                                                <td><?php echo utf8_encode($fila['rut_pro']); ?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']." ".$fila['apellido_materno_pro']); ?></td>
                                                                <td><?php echo utf8_encode($fila['fono_pro']); ?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_cat_pag']); ?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_ban']); ?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_for_pag']); ?></td>
                                                                <td><?php echo date("d/m/Y",strtotime($fila["fecha_pag"]));?></td>
                                                                <td>
                                                                	<?php
                                                                	$monto_abono = 0;
                                                                	if ($fila["fecha_real_pag"]<>'0000-00-00' && $fila["fecha_real_pag"]<>null) {
                                                                		echo date("d/m/Y",strtotime($fila["fecha_real_pag"]));
                                                                		// busca la uF
																        $consultauf = 
																            "
																            SELECT
																                valor_uf
																            FROM
																                uf_uf
																            WHERE
																                fecha_uf = '".$fila["fecha_real_pag"]."'
																            ";
																        // echo $consultauf;
																        $conexion->consulta($consultauf);
																        $filauf = $conexion->extraer_registro_unico();
																        $valor_uf = $filauf["valor_uf"];
																        if ($valor_uf<>'') {
																	        if ($fila["id_for_pag"]==6) { // si es pago contra escritura UF
																	        	$monto_abono = $fila['monto_pag'] * $valor_uf;
																	        	$valor_uf = number_format($valor_uf, 2, ',', '.');
																	        	$monto_abono = number_format($monto_abono, 0, ',', '.');
																	        } else {
																				$monto_abono = $fila['monto_pag'] / $valor_uf;
																	        	$valor_uf = number_format($valor_uf, 2, ',', '.');
																	        	$monto_abono = number_format($monto_abono, 2, ',', '.');
																	        }
																	    }
                                                                	} else {
                                                                		echo "--";
                                                                		$valor_uf = "";
                                                                		$monto_abono = "";
                                                                	}
                                                                	?>
                                                                </td>
                                                                <td><?php echo utf8_encode($fila['numero_documento_pag']); ?></td>
                                                                <!-- <td><?php //echo utf8_encode($fila['numero_serie_pag']); ?></td> -->
                                                                <td><?php echo number_format($fila['monto_pag'], 0, ',', '.');?></td>
                                                                <td><?php echo utf8_encode($fila['nombre_est_pag']); ?></td>

                                                                <td><?php echo $valor_uf; ?></td>
                                                                <td><?php echo $monto_abono; ?></td>
                                                            </tr>
                                                            <?php
                                                            
                                                        }
                                                    }
                                                    ?>   
                                                </tbody>
                                                <!-- <tfoot>
                                                    <tr>
                                                        <td colspan="9"></td>
                                                        
                                                        <td>$<?php echo number_format($acumulado_monto, 0, ',', '.'); ?></td>
                                                    </tr> 
                                                </tfoot> -->
                                                
                                                
                                            </table>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div>
                            <?php
                        }
                        ?>
                        
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->

</body>
</html>
