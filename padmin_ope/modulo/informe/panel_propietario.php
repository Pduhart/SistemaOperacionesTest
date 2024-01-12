<!-- Small boxes (Stat box) -->
<style>
  .box{
    margin-bottom: 4px;
  }

  

  .box h3{
    margin-top: 4px;
  }
</style>
<?php
$total_reserva_propietario = 0;
$total_ticket_propietario = 0;

$consulta = 
  "
  SELECT
    COUNT(res.id_res) AS total
  FROM
    reserva_reserva AS res,
    usuario_usuario AS usu
  WHERE
    res.id_est_res <> 3 AND
    usu.rut_usu = res.rut_propietario_res AND
    usu.id_usu = ".$_SESSION["sesion_id_panel"]."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$total_reserva_propietario = utf8_encode($fila["total"]);

//TICKET
$consulta = 
  "
  SELECT
    COUNT(id_tic) AS totalmios
  FROM
    ticket_ticket
  WHERE
    id_tip_tic = 2 AND
    (id_est_tic = 1 or id_est_tic = 3) AND
    id_usu = ".$_SESSION["sesion_id_panel"]."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$total_ticket_propietario = utf8_encode($fila["totalmios"]);

//TICKET A MI
$consulta = 
  "
  SELECT
    COUNT(ticket_ticket.id_tic) AS totalami
  FROM
    ticket_ticket,
    ticket_usuario_ticket
  WHERE
    ticket_ticket.id_tip_tic = 2 AND
    (ticket_ticket.id_est_tic = 1 or ticket_ticket.id_est_tic = 3) AND
    ticket_ticket.id_tic = ticket_usuario_ticket.id_tic AND
    ticket_usuario_ticket.id_usu = ".$_SESSION["sesion_id_panel"]."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$total_ticket_administrador = utf8_encode($fila["totalami"]);

$totalalerta = $total_ticket_propietario + $total_ticket_administrador;
?>
<!-- Modal -->
<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
</div>
<div class="row">
    <div class="col-md-8">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Anuncios</h3>
            </div>
            <div class="box-body" style="max-height: 500px; overflow-y: scroll;">
                <?php
                $consulta = 
                  "
                  SELECT
                    *
                  FROM
                    ticket_ticket
                  WHERE
                    id_tip_tic = 1 AND
                    id_est_tic = 4
                  ";

                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();
                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
                        ?>         
                    
                        <!-- Attachment -->
                        <div class="attachment-block clearfix">
                            <img class="attachment-img" src="../archivo/ticket/documento/<?php echo utf8_encode($fila["id_tic"]);?>/thumbnail/<?php echo utf8_encode($fila["documento_tic"]);?>" alt="Attachment Image">
                            <div class="attachment-pushed">
                                <h4 class="attachment-heading"><?php echo utf8_encode($fila["asunto_tic"]);?></h4>
                                <div class="attachment-text">
                                   <?php
                                   $total_cadena = strlen($fila["descripcion_tic"]);

                                   if ($total_cadena >= 200) {
                                       $descripcion = substr($fila["descripcion_tic"], 0, 200)."...";
                                       $descripcion = str_replace("\n", "<br>", $descripcion); 
                                   }
                                   else{
                                        $descripcion = $fila["descripcion_tic"];
                                   }
                                   echo utf8_encode($descripcion);
                                   ?>
                                   <button type="button" value="<?php echo $fila["id_tic"];?>" class="btn btn-link detalle_anuncio">Leer</button>
                                </div>
                                <!-- /.attachment-text -->
                            </div>
                            <!-- /.attachment-pushed -->
                        </div>
                        <!-- /.attachment-block -->
                    
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div> 
  <div class="col-md-4">
    <div class="box box-widget widget-user-2">            
        <div class="widget-user-header bg-red disabled color-palette">             
            <p class="lead">Alertas</p>             
        </div>
        <div class="box-footer no-padding">
            <ul class="nav nav-stacked"> 
                <li><a href="<?php echo _MODULO?>informe/calendario_general.php" target="_blank">Mis Reservas<span class="pull-right badge bg-aqua"><?php echo number_format($total_reserva_propietario, 0, ',', '.');?></span></a></li>
                <li><a href="../padmin_dep/modulo/ticket/form_select.php">Mis Tickets <span class="pull-right badge bg-aqua"><?php echo number_format($totalalerta, 0, ',', '.');?></span></a></li>
            </ul>
        </div>
    </div>             
    <div class="small-box bg-green mt-20">
        <div class="inner">
            <h3>Calendario</h3>
            <p>Reservas</p>
        </div>
        <div class="icon">
            <i class="fa fa-calendar-o"></i>
        </div>
        <a href="<?php echo _MODULO?>informe/calendario_general.php" target="_blank" class="small-box-footer">Entrar <i class="fa fa-arrow-circle-right"></i></a>
    </div>        
  </div>    
</div>

<script type="text/javascript">
    $(document).ready(function() {
    // ver modal
        $(document).on( "click",".detalle_anuncio" , function() {
            valor = $(this).val();
            $.ajax({
                type: 'POST',
                url: ("modulo/informe/detalle_anuncio.php"),
                data:"valor="+valor,
                success: function(data) {
                     $('#contenedor_modal').html(data);
                     $('#contenedor_modal').modal('show');
                }
            })
        });
    });
</script>