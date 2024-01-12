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

  .content{
    padding-top: 0px;
  }

  .box h3{
    margin-top: 4px;
  }
</style>
<?php  
include _INCLUDE."class/dias.php";
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
    IFNULL(SUM(monto_ven),0) AS total
  FROM
    venta_venta
  WHERE
    id_est_ven IN (6,7)
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta = $fila["total"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(monto_ven),0) AS total
  FROM
    venta_venta
  WHERE
    id_est_ven IN (6,7) AND
    MONTH(fecha_ven) = '".$mes_actual."' AND
    YEAR(fecha_ven) = '".$anio_actual."'
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_venta_mes_actual = $fila["total"];



$consulta = 
  "
  SELECT 
    IFNULL(SUM(valor_viv),0) AS total
  FROM
    cotizacion_cotizacion AS cot
    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv
  WHERE
    cot.id_est_cot IN (1,4,5,6,7)
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$monto_total_cotizacion = $fila["total"];

//------ CHEQUES ------
$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_est_pag = 1) THEN monto_pag ELSE 0 END),0) AS realizado,
    IFNULL(SUM(CASE WHEN (id_est_pag = 2) THEN monto_pag ELSE 0 END),0) AS pendiente,
    IFNULL(SUM(CASE WHEN (id_est_pag = 3) THEN monto_pag ELSE 0 END),0) AS protestado  
  FROM
    pago_pago
  WHERE
    id_for_pag = 4 OR
    id_for_pag = 8
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$cheque_realizado = $fila["realizado"];
$cheque_pendiente = $fila["pendiente"];
$cheque_protestado = $fila["protestado"];

$consulta = 
  "
  SELECT 
    IFNULL(SUM(CASE WHEN (id_est_pag = 1) THEN monto_pag ELSE 0 END),0) AS realizado,
    IFNULL(SUM(CASE WHEN (id_est_pag = 2) THEN monto_pag ELSE 0 END),0) AS pendiente,
    IFNULL(SUM(CASE WHEN (id_est_pag = 3) THEN monto_pag ELSE 0 END),0) AS protestado  
  FROM
    pago_pago
  WHERE
    id_for_pag = 3
  ";
$conexion->consulta($consulta);
$fila = $conexion->extraer_registro_unico();
$transferencia_realizado = $fila["realizado"];
$transferencia_pendiente = $fila["pendiente"];
$transferencia_protestado = $fila["protestado"];


$consulta = 
  "
  SELECT 
    id_pag
  FROM
    pago_pago
  WHERE
    id_for_pag = 4 AND
    id_est_pag = 2 AND
    MONTH(fecha_pag) = '".$mes_actual."' AND
    YEAR(fecha_pag) = '".$anio_actual."'
  ";
$conexion->consulta($consulta);
$cheque_numero_mes = $conexion->total();


$consulta = 
  "
  SELECT 
    id_pag
  FROM
    pago_pago
  WHERE
    id_for_pag = 4 AND
    id_est_pag = 2 AND
    fecha_pag = '".$hoy."'
  ";
$conexion->consulta($consulta);
$cheque_numero_hoy = $conexion->total();

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
        ven.id_est_ven IN (1,4,5,6,7)
    ";
$conexion->consulta_form($consulta,array(2));
$fila_consulta_estado = $conexion->extraer_registro();
if(is_array($fila_consulta_estado)){
    foreach ($fila_consulta_estado as $fila_estado) {
        $fecha_inicio = $fila_estado["fecha_desde_eta_ven"];
        $duracion = $fila_estado["duracion_eta"];
        $fecha_inicio = date("Y-m-d",strtotime($fecha_inicio));
        // $fecha_final = date("Y-m-d", strtotime("$fecha_inicio + $duracion days"));
        $fecha_final = add_business_days($fecha_inicio,$duracion,$holidays,'Y-m-d');
        if($fecha_final < $hoy){
            $acumulado_atrasado_etapa = $acumulado_atrasado_etapa + 1;
        }
    }
}

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
        ven.id_est_ven IN (1,4,5,6,7)
    ";
$conexion->consulta_form($consulta,array(2));

$cantidad_abiertas = $conexion->total();
?>
<div class="row">

  <div class="col-md-9">
    <div class="box box-solid" style="height: 500px">
     <!--  <div class="box-header with-border">
        <h3 class="box-title">Progress Bars Different Sizes</h3>
      </div>  -->           
      	<div class="box-body">             
        <!-- <table style="border: none; width: 100%;">
          <tbody>
            <tr>
              <td> -->
              <?php 
		      include "grafico_condominios.php";
		      ?>
             <!--- </td>
              <td width="50%"> 
                <dl>
                  <dt class="text-muted">UNIDADES MES ACTUAL</dt>
                  <dd class="text-muted" style="font-size:25px;color: #f56954">
                    <?php 
                    //echo number_format($total_unidades_vendidas_mes_actual, 0, ',', '.');
                    ?>
                  </dd>
                </dl>
               
               </td>
            </tr>
          </tbody>
        </table> -->
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

	  				<!-- <li><a href="<?php //echo _MODULO?>informe/venta_pago_venta.php" target="_blank">Cheques por Cobrar (mes) <span class="pull-right badge bg-red"><?php //echo number_format($cheque_numero_mes, 0, ',', '.');?></span></a></li> -->
	  				<!-- <li><a href="<?php //echo _MODULO?>informe/venta_pago_venta.php" target="_blank">Cheques para hoy <span class="pull-right badge bg-red"><?php //echo number_format($cheque_numero_hoy, 0, ',', '.');?></span></a></li> -->
	  				<li><a href="<?php echo _MODULO?>informe/operacion_etapa_listado.php" target="_blank">Etapas Abiertas<span class="pull-right badge bg-info"><?php echo number_format($cantidad_abiertas, 0, ',', '.');?></span></a></li>
	  				<li><a href="<?php echo _MODULO?>informe/operacion_etapa_listado.php" target="_blank">Etapas Atrasadas<span class="pull-right badge bg-red"><?php echo number_format($acumulado_atrasado_etapa, 0, ',', '.');?></span></a></li>
	  				<li><a href="#">Deptos Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($departamento_numero_vendido, 0, ',', '.');?></span></a></li>
	  				<li><a href="#">Estac. Vendidos <span class="pull-right badge bg-aqua"><?php echo number_format($estacionamiento_numero_vendido, 0, ',', '.');?></span></a></li>
	  				<li><a href="#">Bodegas Vendidas <span class="pull-right badge bg-aqua"><?php echo number_format($bodega_numero_vendido, 0, ',', '.');?></span></a></li>
					<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=1" target="_blank">Exportar Escrituras Pacífico 3</a></li>
          			<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=4" target="_blank">Exportar Escrituras Pacífico 2800 Etapa I</a></li>
          			<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=5" target="_blank">Exportar Escrituras Pacífico 2800 Etapa II</a></li>
          			<li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=6" target="_blank">Exportar Escrituras Pacífico 3100 Etapa I</a></li>
                <li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=7" target="_blank">Exportar Escrituras Pacífico 3100 Etapa II</a></li>
                <li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=8" target="_blank">Exportar Escrituras Distrito Verde Etapa I A</a></li>
            <li><a href="<?php echo _MODULO?>informe/escrituras_listado.php?tor=9" target="_blank">Exportar Escrituras Distrito Verde Etapa I B</a></li>
					<li><a href="<?php echo _MODULO?>informe/condominio_departamento_listado_exc.php" target="_blank">Listado de Ventas & Clientes</a></li>
	  			</ul>
	  		</div>
	  	</div>          
  	</div>
  <div class="col-md-3">
  </div>                              
</div>
<div class="row">
  <div class="col-md-3">          
    
    <div class="info-box">
      <a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa ion-wrench"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Operacion / Etapas</span>
          <!-- <span class="info-box-number"><?php echo number_format($cantidad_apoderado, 0, ',', '.');?></span> -->
        </div> 
      </a>          
    </div>
  </div>
  <div class="col-md-3">  
    <div class="info-box">
      <a href="<?php echo _MODULO?>informe/condominio_departamento_listado.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa fa-building"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Condominio</span>
          <!-- <span class="info-box-number"><?php echo number_format($monto_moroso, 0, ',', '.');?></span> -->
        </div> 
      </a>          
    </div>
  </div>
  <!-- <div class="col-md-3"> 
    <div class="info-box">
      <a href="<?php echo _MODULO?>informe/vendedor_historial_liquidacion.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Vendedor</span>
          <span class="info-box-number"><?php //echo number_format($total_monto_ingreso, 0, ',', '.');?></span> 
        </div> 
      </a>          
    </div>
  </div> -->
  <div class="col-md-3"> 
    <div class="info-box">
      <a href="<?php echo _MODULO?>informe/operacion_listado.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Informes Venta / Cotización</span>
          <!-- <span class="info-box-number"><?php echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
        </div>  
      </a>         
    </div>           
  </div>   
  <div class="col-md-3"> 
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