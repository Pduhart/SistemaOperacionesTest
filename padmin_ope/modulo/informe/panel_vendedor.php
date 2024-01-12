<!-- Small boxes (Stat box) -->
<style>
    .widget-user-2 .widget-user-header{
          /* padding: 1px; */
      border-top-right-radius: 0; 
      border-top-left-radius: 0; 
      padding-top: 5px;
      padding-bottom: 1px;
      padding-left: 15px;
    }

    .box{
      margin-bottom: 4px;
    }

    .box-body {
      padding: 15px;
  }

    .content{
      padding-top: 0px;
    }

    .box h3{
      margin-top: 4px;
    }

    .ml-0{
      padding-left: 0;
    }

    .col_calendar{
      padding-right: 5px;
    }


    .hb-current-month {
      line-height: 35px;
      font-size: 33px;
      font-weight: 200;
      margin-top: 10px;
  }

  .hb-current-month span {
      display: block;
      margin-top: 2px;
      line-height: 20px;
      font-size: 24px;
      color: #666 !important;
  }

  .hb-months {
      position: relative;
      height: 90px;
      margin-bottom: 0px;
      padding-top: 10px;
      text-align: center;
      border-bottom: 1px solid #ccc;
  }

  .hb-day.current_day {
      color: #fff;
      background-color: #77b8dd;
  }

  .hb-day.hb-day-selected, .hb-day.hb-day-selected:hover {
      background-color: #dd4b39;
  }

  #hb-event-list .fecha{
    font-size: 1.6rem;
    color: #dd4b39;
    font-weight: bold;
  }

  #hb-event-list h4{
    font-size: 1.5rem;
    color: #000000;
    font-weight: 700;
    margin-bottom: 5px;
  }

  #hb-event-list h5{
    font-size: 1.4rem;
    color: #000000;
    margin-top: 0;
    margin-bottom: 5px;
  }

  #hb-event-list p{
    font-size: 1.2rem;
    color: #777;
  }

  #hb-event-list .realizado{
    background-color: rgba(204,204,204,.4);
  }

  #hb-event-list .realizado h4{
    font-style: italic;
  }

  #hb-event-list .realizado h5{
    font-style: italic;
  }

  #hb-event-list .fa-exclamation-triangle{
    color: #dd4b39;
  }
</style>
<?php  
$hoy = date("Y-m-d");
$mes_actual = date("m");
$anio_actual = date("Y");

$departamento_numero = 0;
$departamento_numero_vendido = 0;
$estacionamiento_numero = 0;
$estacionamiento_numero_vendido = 0;
$bodega_numero = 0;
$bodega_numero_vendido = 0;

$monto_total_venta = 0;
$acumulado_atrasado_etapa = 0;

$fila_consulta_ingreso = array();


//------ TOTALES ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(monto_ven),0) AS total
  FROM
    venta_venta
  WHERE
    id_est_ven IN (6,7) AND
    id_vend = ".$_SESSION["sesion_id_vend"]."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta = $fila["total"];

//------ TOTALES ------
$consulta = 
  "
  SELECT 
    vivienda_vivienda.id_viv
  FROM
    vivienda_vivienda,
    venta_venta
  WHERE
    vivienda_vivienda.id_est_viv = 2 AND
    venta_venta.id_vend = ".$_SESSION["sesion_id_vend"]." AND
    vivienda_vivienda.id_viv = venta_venta.id_viv
  ";
$conexion->consulta($consulta);
$total_unidades_vendidas = $conexion->total();

// total cotizaciones
$consulta_ventas_promesa = 
  "
  SELECT 
    id_ven
  FROM
    venta_venta
  WHERE
    (id_est_ven = 4 or id_est_ven = 5) AND
    id_vend = ".$_SESSION["sesion_id_vend"]."
  ";
$conexion->consulta($consulta_ventas_promesa);
$total_ventas_promesa = $conexion->total();


// unidades mes actual
$consulta_unidades_mes = 
  "
  SELECT 
    id_ven
  FROM
    venta_venta
  WHERE
    (id_est_ven > 3) AND
    MONTH(fecha_ven) = '".$mes_actual."' AND
    YEAR(fecha_ven) = '".$anio_actual."' AND
    id_vend = ".$_SESSION["sesion_id_vend"]."
  ";
$conexion->consulta($consulta_unidades_mes);
$total_unidades_vendidas_mes_actual = $conexion->total();


//------ DEPARTAMENTO / ESTACIONAMIENTO / BODEGA ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (viv.id_est_viv = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    vivienda_vivienda as viv,
    venta_venta as ven
  WHERE
  	viv.id_viv = ven.id_viv AND
    ven.id_vend = ".$_SESSION['sesion_id_vend']."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$departamento_numero_vendido = $fila["total_vendido"];


$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (est.id_est_esta = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    estacionamiento_estacionamiento as est,
    venta_venta as ven,
    venta_estacionamiento_venta as vene
  WHERE
  	est.id_esta = vene.id_esta AND
    ven.id_vend = ".$_SESSION['sesion_id_vend']." AND
    ven.id_ven = vene.id_ven
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$estacionamiento_numero = $fila["total_vendido"];
$estacionamiento_numero_vendido = $fila["total_vendido"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (bod.id_est_bod = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    bodega_bodega as bod,
    venta_venta as ven,
    venta_bodega_venta as bode
  WHERE
  	bod.id_bod = bode.id_bod AND
    ven.id_vend = ".$_SESSION['sesion_id_vend']." AND
    ven.id_ven = bode.id_ven
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$bodega_numero = $fila["total_vendido"];
$bodega_numero_vendido = $fila["total_vendido"];


//------ EVENTOS ATRASADOS ------
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i:00");
$consulta_pasados = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (eve.id_est_eve = 1) THEN 1 ELSE 0 END),0) AS total_atrasado_dia
  FROM
    evento_evento as eve
  WHERE
    eve.id_usu = ".$_SESSION['sesion_id_panel']." AND
    eve.fecha_eve < '".$fecha_actual."'
  ";

$conexion->consulta($consulta_pasados);
$fila = $conexion->extraer_registro_unico();
$eventos_atrasados_dia = $fila["total_atrasado_dia"];

$consulta_atrasados = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (eve.id_est_eve = 1) THEN 1 ELSE 0 END),0) AS total_atrasado_hora
  FROM
    evento_evento as eve
  WHERE
    eve.id_usu = ".$_SESSION['sesion_id_panel']." AND
    eve.fecha_eve = '".$fecha_actual."' AND
    eve.time_eve < '".$hora_actual."'
  ";

$conexion->consulta($consulta_atrasados);
$fila = $conexion->extraer_registro_unico();
$eventos_atrasados_hora = $fila["total_atrasado_hora"];

$atrasos_totales = $eventos_atrasados_dia + $eventos_atrasados_hora;
?>

<div class="modal fade" id="contenedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
</div>


<div class="row">
  	<div class="col-md-10">
    	<div class="box box-solid">
	      	<div class="box-body">             
		        <div class="row">
		        	<div class="col-md-3 col_calendar">
		        		<div id="calendar"></div><!-- div del calendario -->
		        	</div>
		        	<div class="col-md-9">
		        		<div id="hb-event-list" class="row">
		        			<p style="margin-top: 20px; font-size: 1.6rem"><i class="fa fa-exclamation-circle"></i> No tiene eventos para este día, seleccione días en el calendario.</p>
		        		</div><!-- div donde salen los eventos -->
		        	</div>
		      	</div>

	      		<script src="<?php echo _ASSETS?>calendar/jquery.event.calendar.js"></script>
				<script src="<?php echo _ASSETS?>calendar/jquery.event.calendar.es.js"></script>
		      	<script type="text/javascript">
					$('#calendar').eCalendar({
						ajaxDayLoader	: "<?php echo _MODULO?>informe/ajax/hb-days.php",
						ajaxEventLoader	: "<?php echo _MODULO?>informe/ajax/hb-events.php",
						eventsContainer	: "#hb-event-list",
						currentMonth	: <?php echo date("n");?>,
						currentYear		: <?php echo date("Y");?>,
						startMonth		: 1,
						startYear		: <?php echo date("Y")-1;?>,
						endMonth		: 12,
						endYear		: <?php echo date("Y")+1;?>,
						firstDayOfWeek	: 1,
						/*onBeforeLoad	: function() {},*/
						onAfterLoad		: function() {
								$(".hb-day").removeClass("hb-day-selected");
								$(".hb-day.current_day").trigger('click'); 
							},
						onClickMonth	: function() {
							$("#hb-event-list").text("");
							//$(".hb-day").removeClass("hb-day-selected");
							},
						
						onClickDay		: function() {
							
							}
						
					});


				</script>   
			</div>
		</div>
    	<div class="row">
    		<div class="col-md-12">          
			    <div class="box box-solid">                       
			      <div class="box-body">
			      <table style="border: none; width: 100%">
			        <tr>
			          <td width="50%"><h3 class="text-muted text-left">Total Unidades Vendidas</h3></td>
			          <td width="50%"><h3 class="text-muted text-right" style="color: #f56954;"><?php echo number_format($total_unidades_vendidas, 0, ',', '.');?> Unidades</h3></td>
			        </tr>
			        
			        <tr>
			            <td width="50%"><h3 class="text-muted text-left">Total Promesas </h3></td>
			            <td width="50%"><h3 class="text-muted text-right" style="color: #f56954"><?php echo number_format($total_ventas_promesa, 0, ',', '.');?></h3></td>
			        </tr>
			        <tr>
			            <td width="50%"><h3 class="text-muted text-left">Ventas en Mes Actual </h3></td>
			            <td width="50%"><h3 class="text-muted text-right" style="color: #f56954"><?php  echo number_format($total_unidades_vendidas_mes_actual, 0, ',', '.');?></h3></td>
			        </tr>
			      </table>

			      </div>
			    </div>          
		  	</div>
    	</div>
  	</div> 

  	<div class="col-md-2 ml-0">          
	    <div class="box box-widget widget-user-2">            
	      <div class="widget-user-header bg-red disabled color-palette">             
	      <p class="">Alertas</p>             
	      </div>
	      <div class="box-footer no-padding">
	        <ul class="nav nav-stacked">
	        	<li><a href="#" id="<?php echo $_SESSION["sesion_perfil_panel"]; ?>" class="detalle_eve_atrasado">Eventos Atrasados <span class="pull-right badge bg-red"><?php echo number_format($atrasos_totales, 0, ',', '.');?></span></a></li>
	          	<li><a href="#">Deptos Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($departamento_numero_vendido, 0, ',', '.');?></span></a></li>
	          	<li><a href="#">Estac. Adic. Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($estacionamiento_numero_vendido, 0, ',', '.');?></span></a></li>
	          	<li><a href="#">Bodegas Adic. Vendidas <span class="pull-right badge bg-aqua"><?php echo number_format($bodega_numero_vendido, 0, ',', '.');?></span></a></li>
	        </ul>
	      </div>
	    </div>

	    <div class="info-box">
	      <a href="<?php echo _MODULO?>informe/condominio_departamento_listado.php" target="_blank" data-slug="">
	        <span class="info-box-icon bg-aqua"><i class="fa fa-building"></i></span>
	        <div class="info-box-content">
	          <span class="info-box-text">Condominio</span>
	          <!-- <span class="info-box-number"><?php //echo number_format($monto_moroso, 0, ',', '.');?></span>-->
	        </div> 
	      </a>          
	    </div>

	    <div class="info-box">
	      <a href="<?php echo _MODULO?>informe/ficha_cliente_proceso.php" target="_blank" data-slug="">
	        <span class="info-box-icon bg-aqua"><i class="fa fa-address-card"></i></span>
	        <div class="info-box-content">
	          <span class="info-box-text">CLIENTE <br> COTIZACIÓN</span>
	          <!-- <span class="info-box-number"><?php echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
	        </div>  
	      </a>         
	    </div>     
  	</div>               
</div>

<script type="text/javascript">
	// ver modal
    $(document).on( "click",".edita_evento" , function() {
        valor = $(this).val();
        $.ajax({
            type: 'POST',
            url: ("modulo/evento/form_update_modal.php"),
            data:"valor="+valor,
            success: function(data) {
                 $('#contenedor_modal').html(data);
                 $('#contenedor_modal').modal('show');
            }
        })
    });

    $(document).on( "click",".estado_evento" , function() {
        valor = $(this).val();
        swal({
            title: "Está Seguro?",
            text: "Desea cambiar el estado del evento seleccionado!",
            icon: "warning"            
        }).then(()=>{
          $.ajax({
                type: 'POST',
                url: ("modulo/evento/estado.php"),
                data:"valor="+valor,
                dataType:'json',
                success: function(data) {
                    resultado_estado(data);                 
                }
            })
        });
    });
    function resultado_estado(data) {
        if(data.envio == 1){
            swal({
              title: "Excelente!",
              text: "Estado modificado con éxito!",
              icon: "success"
                
            }).then(()=>location.reload());
        }
        if(data.envio == 3){
            swal("Error!", "Favor intentar denuevo","error");
        }
    } 


    // ver modal
    $(document).on( "click",".detalle_cot" , function() {
        valor = $(this).val();
        $.ajax({
            type: 'POST',
            url: ("modulo/cotizacion/form_detalle.php"),
            data:"valor="+valor,
            success: function(data) {
                 $('#contenedor_modal').html(data);
                 $('#contenedor_modal').modal('show');
            }
        })
    });

    $(document).on( "click",".detalle_eve_atrasado" , function() {
        valor = $(this).attr("id");
        $.ajax({
            type: 'POST',
            url: ("modulo/evento/form_detalle_atrasados.php"),
            data:"valor="+valor,
            success: function(data) {
                 $('#contenedor_modal').html(data);
                 $('#contenedor_modal').modal('show');
            }
        })
    });
</script>