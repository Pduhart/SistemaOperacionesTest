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

  h3{
  	line-height: 21px;
  }

  h3 small{
  	font-size: 1.3rem;
  	line-height: 8px;
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
$conexion->consulta("SELECT id_viv FROM vivienda_vivienda WHERE id_est_viv = 2");
$total_unidades_vendidas = $conexion->total();

$conexion->consulta("SELECT IFNULL(SUM(monto_ven),0) AS total FROM venta_venta WHERE id_est_ven IN (6,7) AND MONTH(fecha_ven) = '".$mes_actual."' AND YEAR(fecha_ven) = '".$anio_actual."'");
$fila = $conexion->extraer_registro_unico();
$monto_total_venta_mes_actual = $fila["total"];

// unidades mes actual
$conexion->consulta("SELECT id_ven FROM venta_venta WHERE (id_est_ven = 6 or id_est_ven = 7) AND MONTH(fecha_ven) = '".$mes_actual."' AND YEAR(fecha_ven) = '".$anio_actual."'");
$total_unidades_vendidas_mes_actual = $conexion->total();

$conexion->consulta("SELECT  IFNULL(SUM(valor_viv),0) AS total FROM cotizacion_cotizacion AS cot INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv WHERE cot.id_est_cot IN (1,4,5,6,7)");
$fila = $conexion->extraer_registro_unico();
$monto_total_cotizacion = $fila["total"];

// total escrituras
$conexion->consulta("SELECT id_ven FROM venta_venta WHERE (id_est_ven = 6 or id_est_ven = 7)");
$total_ventas_escrituras = $conexion->total();

$consulta = 
  "
  SELECT 
    pag.id_pag
  FROM
    pago_pago AS pag,
    venta_venta AS ven
  WHERE
  	pag.id_ven = ven.id_ven AND
  	ven.id_est_ven <> 3 AND
    pag.id_for_pag = 4 AND
    pag.id_est_pag = 2 AND
    MONTH(pag.fecha_pag) = '".$mes_actual."' AND
    YEAR(pag.fecha_pag) = '".$anio_actual."'
  ";
$conexion->consulta($consulta);
$cheque_numero_mes = $conexion->total();


$consulta = 
  "
  SELECT 
    pag.id_pag
  FROM
    pago_pago AS pag,
    venta_venta AS ven
  WHERE
  	pag.id_ven = ven.id_ven AND
  	ven.id_est_ven <> 3 AND
    pag.id_for_pag = 4 AND
    pag.id_est_pag = 2 AND
    pag.fecha_pag = ".$hoy."
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
        ven.id_est_ven IN (1,4,5,6)
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


// revisa si hay listas sin asignar
$consulta_listas_noasignadas = 
  "
  SELECT 
    lis.id_lis
  FROM
    lista_lista AS lis
  WHERE
  	NOT EXISTS (
  		SELECT 
            lis_ven.id_lis
        FROM
            lista_vendedor_lista AS lis_ven
        WHERE
            lis_ven.id_lis = lis.id_lis)
  ";
$conexion->consulta($consulta_listas_noasignadas);
$listas_sin_asig = $conexion->total();
?>
<div class="row">

  <div class="col-md-9">
    <div class="box box-solid" style="height: 500px">          
      	<div class="box-body">                     
                <!-- Gráfico desactivado temporalmente -->
              <?php include "grafico_condominios.php";?>          
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
          <li><a href="<?php echo _MODULO?>vendedor/form_lista.php">Listas sin asgnar <span class="pull-right badge bg-red"><?php echo $listas_sin_asig;?></span></a></li>
          <li><a href="<?php echo _MODULO?>informe/venta_pago_venta.php" target="_blank">Cheques por Cobrar (mes) <span class="pull-right badge bg-red"><?php echo number_format($cheque_numero_mes, 0, ',', '.');?></span></a></li>
          <li><a href="<?php echo _MODULO?>informe/venta_pago_venta.php" target="_blank">Cheques para hoy <span class="pull-right badge bg-red"><?php echo number_format($cheque_numero_hoy, 0, ',', '.');?></span></a></li>
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
  <div class="col-md-12">          
    <div class="box box-solid">                       
      <div class="box-body">
      	<?php 
      	include "tabla_estados.php";
      	 ?>
      </div>
    </div>          
  </div>
  <!-- <div class="col-md-12">           -->
    <!-- <div class="box box-solid">                        -->
      <!-- <div class="box-body"> -->
        <!-- <table style="border: none; width: 100%"> -->
          <?php  
          // $total_pago = $cheque_realizado + $transferencia_realizado;
          ?>
         <!--  <tr>
            <td width="50%"><h3 class="text-muted text-left">Pagos Recibidos</h3></td>
            <td width="50%"><h3 class="text-muted text-right" style="color: #f56954">$<?php //echo number_format($total_pago, 0, ',', '.');?></h3></td>
          </tr>
        </table>
        <table class="table table-bordered">
          
          <tr>
            <td>Cheques Cobrados</td>
            <td>Cheques Por Cobrar</td>
            <td>Cheques Protestados</td>
          </tr>
            
          
          <tr> -->
           <!--  <td>$<?php //echo number_format($cheque_realizado, 0, ',', '.');?></td>
            <td>$<?php //echo number_format($cheque_pendiente, 0, ',', '.');?></td>
            <td>$<?php //echo number_format($cheque_protestado, 0, ',', '.');?></td>
          </tr>

          <tr>
            <td>Transferencia Cobrados</td>
            <td>Transferencia Por Cobrar</td>
            <td>Transferencia Anulada</td>
          </tr>
            
          
          <tr>
            <td>$<?php //echo number_format($transferencia_realizado, 0, ',', '.');?></td>
            <td>$<?php //echo number_format($transferencia_pendiente, 0, ',', '.');?></td>
            <td>$<?php //echo number_format($transferencia_protestado, 0, ',', '.');?></td>
          </tr>
        </table> -->
      <!-- </div> -->
    <!-- </div>            -->
  <!-- </div>     -->
         
</div>
<div class="row">
  
  <div class="col-md-6">          
    
    
           
  </div>
</div>
<div class="row">
	<div class="col-sm-12" style="display: flex; justify-content: space-between;">
		<div style="width: 19%">
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
		<div style="width: 19%">
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
		<div style="width: 19%">
			 <div class="info-box">
		      <a href="<?php echo _MODULO?>informe/vendedor_historial_liquidacion.php" target="_blank" data-slug="">
		        <span class="info-box-icon bg-aqua"><i class="fa fa-usd"></i></span>
		        <div class="info-box-content">
		          <span class="info-box-text">Liquidación<br>Comisiones</span>
		          <!-- <span class="info-box-number"><?php echo number_format($total_monto_ingreso, 0, ',', '.');?></span> -->
		        </div> 
		      </a>          
		    </div>
		</div>
		<div style="width: 19%">
			<div class="info-box">
		      <a href="<?php echo _MODULO?>informe/informe_tubo.php" target="_blank" data-slug="">
		        <span class="info-box-icon bg-aqua"><i class="fa fa-database"></i></span>
		        <div class="info-box-content">
		          <span class="info-box-text">Flujo / Tubo</span>
		          <!-- <span class="info-box-number"><?php echo number_format($total_monto_ingreso, 0, ',', '.');?></span> -->
		        </div> 
		      </a>          
		    </div>
		</div>
		<div style="width: 19%">
			<div class="info-box">
		      <a href="<?php echo _MODULO?>informe/operacion_listado.php" target="_blank" data-slug="">
		        <span class="info-box-icon bg-aqua"><i class="fa fa-bar-chart"></i></span>
		        <div class="info-box-content">
		          <span class="info-box-text">Informes de Ventas</span>
		          <!-- <span class="info-box-number"><?php echo number_format($cantidad_ingresado, 0, ',', '.');?></span> -->
		        </div>  
		      </a>
		    </div>
		</div>
	</div>
  <!-- <div class="col-md-2">          
    
    <div class="info-box">
      <a href="<?php echo _MODULO?>operacion/operacion_ficha.php" target="_blank" data-slug="">
        <span class="info-box-icon bg-aqua"><i class="fa ion-wrench"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Operacion / Etapas</span>
           <span class="info-box-number"><?php echo number_format($cantidad_apoderado, 0, ',', '.');?></span> -
        </div> 
      </a>          
    </div>
  </div> -->
<!--   <div class="col-md-2">  
   
  </div>
  <div class="col-md-2"> 
   
  </div>
  <div class="col-md-2"> 
    
  </div>
  <div class="col-md-2"> 
          
  </div>  -->  
</div>