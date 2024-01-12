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
    h3{
  	line-height: 21px;
  }

  h3 small{
  	font-size: 1.3rem;
  	line-height: 8px;
  }

  .col_calendar{
  	padding-right: 15px;
  	padding-left: 15px;
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


$cheque_numero_mes = 0;
$cheque_numero_hoy = 0;

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
    IFNULL(SUM(venta_venta.monto_ven),0) AS total
  FROM
    venta_venta,
    vendedor_jefe_vendedor
  WHERE
    venta_venta.id_est_ven IN (6,7) AND
    vendedor_jefe_vendedor.id_vend = venta_venta.id_vend AND
    vendedor_jefe_vendedor.id_usu = ".$_SESSION["sesion_id_panel"]."
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta = $fila["total"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(venta_venta.monto_ven),0) AS total
  FROM
    venta_venta,
    vendedor_jefe_vendedor
  WHERE
    venta_venta.id_est_ven IN (6,7) AND
    MONTH(venta_venta.fecha_ven) = '".$mes_actual."' AND
    YEAR(venta_venta.fecha_ven) = '".$anio_actual."' AND
    vendedor_jefe_vendedor.id_vend = venta_venta.id_vend AND
    vendedor_jefe_vendedor.id_usu = ".$_SESSION["sesion_id_panel"]."
  ";

$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta_mes_actual = $fila["total"];

// unidades mes actual jefe
$consulta_unidades_mes = 
  "
  SELECT 
    id_ven
  FROM
    venta_venta,
    vendedor_jefe_vendedor
  WHERE
    (id_est_ven = 6 or id_est_ven = 7) AND
    MONTH(fecha_ven) = '".$mes_actual."' AND
    YEAR(fecha_ven) = '".$anio_actual."' AND
    vendedor_jefe_vendedor.id_vend = venta_venta.id_vend AND
    vendedor_jefe_vendedor.id_usu = ".$_SESSION["sesion_id_panel"]."
  ";
$conexion->consulta($consulta_unidades_mes);
$total_unidades_vendidas_mes_actual = $conexion->total();

// total escrituras
$consulta_ventas_escrituras = 
  "
  SELECT 
    id_ven
  FROM
    venta_venta
  WHERE
    (id_est_ven = 6 or id_est_ven = 7)
  ";
$conexion->consulta($consulta_ventas_escrituras);
$total_ventas_escrituras = $conexion->total();


//------ DEPARTAMENTO / ESTACIONAMIENTO / BODEGA ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_viv > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_viv = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    vivienda_vivienda
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$departamento_numero = $fila["total"];
$departamento_numero_vendido = $fila["total_vendido"];


$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_esta > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_esta = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    estacionamiento_estacionamiento
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$estacionamiento_numero = $fila["total"];
$estacionamiento_numero_vendido = $fila["total_vendido"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_bod > 0) THEN 1 ELSE 0 END),0) AS total,
    IFNULL(SUM(CASE WHEN (id_est_bod = 2) THEN 1 ELSE 0 END),0) AS total_vendido
  FROM
    bodega_bodega
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$bodega_numero = $fila["total"];
$bodega_numero_vendido = $fila["total_vendido"];


//------- OPERACION --------
$consulta = 
    "
    SELECT
        viv.id_viv,
        eta_ven.fecha_desde_eta_ven,
        eta.duracion_eta
    FROM
        torre_torre AS tor
        INNER JOIN vivienda_vivienda AS viv ON viv.id_tor = tor.id_tor
        INNER JOIN venta_venta AS ven ON ven.id_viv = viv.id_viv
        INNER JOIN venta_etapa_venta AS eta_ven ON eta_ven.id_ven = ven.id_ven
        INNER JOIN etapa_etapa AS eta ON eta.id_eta = eta_ven.id_eta
    WHERE
        eta_ven.id_est_eta_ven = ? AND
        ven.id_est_ven IN (1,4,5,6)
    ";
$conexion->consulta_form($consulta,array(2));
$fila_consulta_estado = $conexion->extraer_registro();
if(is_array($fila_consulta_estado)){
    foreach ($fila_consulta_estado as $fila_estado) {
        $fecha_inicio = $fila_estado["fecha_desde_eta_ven"];
        $duracion = $fila_estado["duracion_eta"];
        $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
        $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
        if($fecha_final < $hoy){
            $acumulado_atrasado_etapa = $acumulado_atrasado_etapa + 1;
        }
    }
}



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
  <div class="col-md-9" style="padding-right: 5px;">
  	<div class="box box-solid">
      	<div class="box-body">             
	        <div class="row">
	        	<div class="col-md-4 col_calendar text-center">
	        		<div class="input-group" style="padding-right: 10px">
				      	<span class="input-group-btn">
				        	<button class="btn btn-icon btn-primary" id="filtro" type="button">Filtrar</button>
				      	</span>
				      	<select class="form-control chico" id="vend_fil" name="vend_fil"> 
			                <option value="">Todos</option>
			                <?php  
			                $consulta = "SELECT 
							    		id_vend,
							    		id_usu,
							    		nombre_vend,
							    		apellido_paterno_vend 
							    	FROM 
							    		vendedor_vendedor
							    	WHERE
							    		id_est_vend = 1 AND
							    		id_vend IN (4,10,13,14,3,15,17)";
			                $conexion->consulta($consulta);
			                $fila_consulta_vend_original = $conexion->extraer_registro();
			                if(is_array($fila_consulta_vend_original)){
			                    foreach ($fila_consulta_vend_original as $fila_vend) {
			                    	if($_SESSION["sesion_filtro_vendedor_panel"] && $fila_vend['id_usu'] == $_SESSION["sesion_filtro_vendedor_panel"]){
										$sel = "selected";
			                    	} else {
			                    		$sel = "";
			                    	}
			                        ?>
			                        <option value="<?php echo $fila_vend['id_usu'];?>" <?php echo $sel; ?>><?php echo utf8_encode($fila_vend['nombre_vend']." ".$fila_vend['apellido_paterno_vend']);?></option>
			                        <?php
			                    }
			                }
			                ?>
			            </select>
				    </div><!-- /input-group -->
	        		<div id="calendar" style="width: 90%"></div><!-- div del calendario -->
	        		<a href="modulo/evento/form_insert.php" class="btn btn-lg btn-primary text-center">Agregar Evento</a>
	        	</div>
	        	<div class="col-md-8">
	        		<div id="hb-event-list" class="row" style="height: 500px; overflow-y: scroll;">
	        			<p style="margin-top: 20px; font-size: 1.6rem"><i class="fa fa-exclamation-circle"></i> No tiene eventos para este día, seleccione días en el calendario.</p>
	        		</div><!-- div donde salen los eventos -->
	        	</div>
	      	</div>

      		<script src="<?php echo _ASSETS?>calendar/jquery.event.calendar.js"></script>
			<script src="<?php echo _ASSETS?>calendar/jquery.event.calendar.es.js"></script>
	      	<script type="text/javascript">
				$('#calendar').eCalendar({
					ajaxDayLoader	: "<?php echo _MODULO?>informe/ajax/hb-days-jefe.php",
					ajaxEventLoader	: "<?php echo _MODULO?>informe/ajax/hb-events-jefe.php",
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
    
  </div> 

  <div class="col-md-3">          
    <div class="box box-widget widget-user-2">            
      <div class="widget-user-header bg-red disabled color-palette">             
      <p class="">Alertas</p>             
      </div>
      <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
          
          <!-- <li><a href="#">Cheques por Cobrar (mes) <span class="pull-right badge bg-red"><?php // echo number_format($cheque_numero_mes, 0, ',', '.');?></span></a></li> -->
          <!-- <li><a href="#">Cheques para hoy <span class="pull-right badge bg-red"><?php //echo number_format($cheque_numero_hoy, 0, ',', '.');?></span></a></li> -->
          <li><a href="#" id="<?php echo $_SESSION["sesion_perfil_panel"]; ?>" class="detalle_eve_atrasado">Eventos Atrasados <span class="pull-right badge bg-red"><?php echo number_format($atrasos_totales, 0, ',', '.');?></span></a></li>
          <!-- <li><a href="<?php //echo _MODULO?>informe/venta_cotizacion_venta.php?sin=1" target="_blank">Cotizaciones sin Seguimiento<span class="pull-right badge bg-red"><?php //echo number_format($cantidad_sin_seg, 0, ',', '.');?></span></a></li> -->
          <li><a href="<?php echo _MODULO?>informe/cliente_seguimiento_cot.php" target="_blank">Clientes sin seguimiento/Obs.<span class="pull-right badge bg-red">CLICK AQUÍ<?php //echo number_format($contador_sin_nada, 0, ',', '.');?></span></a></li>

          <li><a href="<?php echo _MODULO?>informe/cliente_seguimiento_cot_atrasados.php" target="_blank">Cli. con seg./Obs. Atrasados<span class="pull-right badge bg-red">CLICK AQUÍ<?php //echo number_format($contador_sin_nada, 0, ',', '.');?></span></a></li>
          <!-- <li><a href="<?php //echo _MODULO?>informe/venta_cotizacion_venta.php" target="_blank">Seguimientos Atrasados<span class="pull-right badge bg-red"><?php //echo number_format($acumulado_atrasado_seg, 0, ',', '.');?></span></a></li> -->
          <li><a href="#">Etapas Atrasadas<span class="pull-right badge bg-red"><?php echo number_format($acumulado_atrasado_etapa, 0, ',', '.');?></span></a></li>
          <li><a href="#">Deptos Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($departamento_numero_vendido, 0, ',', '.');?></span></a></li>
          <li><a href="#">Estac. Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($estacionamiento_numero_vendido, 0, ',', '.');?></span></a></li>
          <li><a href="#">Bodegas Vendidas <span class="pull-right badge bg-aqua"><?php echo number_format($bodega_numero_vendido, 0, ',', '.');?></span></a></li>
		<li><a href="<?php echo _MODULO?>informe/condominio_departamento_listado_exc.php" target="_blank">Listado de Ventas & Clientes</a></li>
		<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=1" target="_blank">Exportar Escrituras Pacífico 3</a></li>
          	<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=4" target="_blank">Exportar Escrituras Pacífico 2800 Etapa I</a></li>
          	<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=5" target="_blank">Exportar Escrituras Pacífico 2800 Etapa II</a></li>
          	<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=6" target="_blank">Exportar Escrituras Pacífico 3100 Etapa I</a></li>
        </ul>
      </div>
    </div>
    <div class="box box-solid">                       
      <div class="box-body">
        <ul class="nav nav-stacked">
          <li><a href="<?php echo _MODULO?>informe/grafico.php" target="_blank">Gráficos <i style="margin-left: 60%;font-size: 25px;" class="fa fa-bar-chart"></i></a>
        </ul>
      </div>
    </div>          
  </div>                             
</div>
<div class="row">
  <div class="col-md-8" style="padding-right: 5px">          
  	<div class="box box-solid">
     <!--  <div class="box-header with-border">
        <h3 class="box-title">Progress Bars Different Sizes</h3>
      </div>  -->           
      <div class="box-body">          
        <?php 
	      include "grafico_condominios.php";
	      ?>                                               
      </div>            
    </div>
  </div>
  <div class="col-md-4" style="padding-left: 5px">          
  	<div class="box box-solid">                       
      <div class="box-body">
      		<div class="row">
			  <div class="col-md-12">          
			    
			    <div class="info-box">
			      <a href="<?php echo _MODULO?>informe/operacion_etapa.php" target="_blank" data-slug="">
			        <span class="info-box-icon bg-aqua"><i class="fa ion-wrench"></i></span>
			        <div class="info-box-content">
			          <span class="info-box-text">Operacion / Etapas</span>
			          <!-- <span class="info-box-number"><?php //echo number_format($cantidad_apoderado, 0, ',', '.');?></span> -->
			        </div> 
			      </a>          
			    </div>
			  </div>
			  <div class="col-md-12">  
			    <div class="info-box">
			      <a href="<?php echo _MODULO?>informe/condominio_departamento_listado.php" target="_blank" data-slug="">
			        <span class="info-box-icon bg-aqua"><i class="fa fa-building"></i></span>
			        <div class="info-box-content">
			          <span class="info-box-text">Condominio</span>
			          <!-- <span class="info-box-number"><?php //echo number_format($monto_moroso, 0, ',', '.');?></span> -->
			        </div> 
			      </a>          
			    </div>
			  </div>
			  <!-- <div class="col-md-12"> 
			    <div class="info-box">
			      <a href="<?php //echo _MODULO?>informe/vendedor_historial_liquidacion.php" target="_blank" data-slug="">
			        <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
			        <div class="info-box-content">
			          <span class="info-box-text">Vendedor</span>
			          <span class="info-box-number"><?php //echo number_format($total_monto_ingreso, 0, ',', '.');?></span> 
			        </div> 
			      </a>          
			    </div>
			  </div> -->
			  <div class="col-md-12"> 
			    <div class="info-box">
			      <a href="<?php echo _MODULO?>informe/venta_velocidad_listado.php" target="_blank" data-slug="">
			        <span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>
			        <div class="info-box-content">
			          <span class="info-box-text">Informes Venta / Cotización</span>
			          <!-- <span class="info-box-number"><?php //echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
			        </div>  
			      </a>         
			    </div>           
			  </div>   
			  <div class="col-md-12"> 
			    <div class="info-box">
			      <a href="<?php echo _MODULO?>informe/ficha_cliente_proceso.php" target="_blank" data-slug="">
			        <span class="info-box-icon bg-aqua"><i class="fa fa-address-card"></i></span>
			        <div class="info-box-content">
			          <span class="info-box-text">CLIENTE <br> COTIZACIÓN</span>
			          <!-- <span class="info-box-number"><?php //echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
			        </div>  
			      </a>         
			    </div>           
			  </div>   
			</div>

      </div>
    </div>  
  </div>    
         
</div>
<div class="row">
  <div class="col-md-12">          
    <div class="box box-solid">                       
      <div class="box-body">
      	<?php 
      	include "tabla_estados.php";
      	 ?>
      </div>
    </div>          
  </div>
 </div>

<script type="text/javascript">
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

    $(document).on( "click",".estado_evento" , function() {
        valor = $(this).val();
        swal({
            title: "Está Seguro?",
            text: "Desea cambiar el estado del evento seleccionado!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Aceptar',
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        },
        function(){
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
              type: "success",
              showCancelButton: false,
              confirmButtonColor: "#9bde94",
              confirmButtonText: "Aceptar",
              closeOnConfirm: false
            },
            function(){
                location.reload();
            });
        }
        if(data.envio == 3){
            swal("Error!", "Favor intentar denuevo","error");
        }
    } 


    $(document).on( "click","#filtro" , function() {
        //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
        var_vend = $('#vend_fil').val();
        $.ajax({
            type: 'POST',
            url: ("modulo/informe/ajax/filtro_update.php"),
            data:"vendedor="+var_vend,
            success: function(data) {
                location.reload();
            }
        })
    });
</script>