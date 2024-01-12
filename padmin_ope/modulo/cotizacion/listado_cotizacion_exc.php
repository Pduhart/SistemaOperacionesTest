<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'listado_cotizaciones_'.date('d-m-Y');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<title>Ventas - Informe</title>
<!-- DataTables -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->

    <section class="content">

    	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Condomin.</th>
                    <th>Torre</th>
                    <th>Modelo</th>
                    <th>Depto</th>
                    <th>Cliente</th>
                    <th>RUT</th>
                	<th>Email</th>
                	<th>Fono</th>
                	<th>Comuna</th>
                	<th>Profesión</th>
                	<th>Sexo</th>
                    <th>Canal</th>
                    <th>Preaprob.</th>
                    <th>Interés</th>
                    <th>Vendedor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            
            <tbody>
            	<?php 
            	$consulta = 
                    "
                    SELECT cot.id_cot, con.nombre_con, tor.nombre_tor, modelo.nombre_mod, viv.nombre_viv, CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName, pro.nombre_pro, pro.nombre2_pro, pro.apellido_paterno_pro, pro.apellido_materno_pro, pro.rut_pro, pro.correo_pro, pro.fono_pro, com.nombre_com, prof.nombre_prof, sex.nombre_sex, can_cot.nombre_can_cot, pre_cot.nombre_pre_cot, cot_int_cot.nombre_seg_int_cot, vend.nombre_vend, vend.apellido_paterno_vend, vend.apellido_materno_vend, cot.fecha_cot, est_cot.nombre_est_cot, cot.id_est_cot FROM cotizacion_cotizacion AS cot 
                    INNER JOIN cotizacion_estado_cotizacion AS est_cot ON est_cot.id_est_cot = cot.id_est_cot 
                    INNER JOIN vivienda_vivienda AS viv ON viv.id_viv = cot.id_viv 
                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con 
                    INNER JOIN modelo_modelo AS modelo ON modelo.id_mod = cot.id_mod 
                    INNER JOIN propietario_propietario AS pro ON cot.id_pro = pro.id_pro 
                    INNER JOIN comuna_comuna AS com ON pro.id_com = com.id_com 
                    INNER JOIN sexo_sexo AS sex ON pro.id_sex = sex.id_sex 
                    INNER JOIN profesion_profesion AS prof ON pro.id_prof = prof.id_prof 
                    INNER JOIN cotizacion_canal_cotizacion AS can_cot ON can_cot.id_can_cot = cot.id_can_cot 
                    INNER JOIN cotizacion_preaprobacion_cotizacion AS pre_cot ON pre_cot.id_pre_cot = cot.id_pre_cot 
                    LEFT JOIN cotizacion_seguimiento_interes_cotizacion AS cot_int_cot ON cot_int_cot.id_seg_int_cot = cot.id_seg_int_cot 
                    LEFT JOIN vendedor_vendedor AS vend ON vend.id_vend = cot.id_vend 
                    WHERE YEAR(cot.fecha_cot) >= 2019
                    ORDER BY cot.id_cot desc
                    ";
                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();

                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
            	?>
            		<tr>
            			<td><?php echo $fila['id_cot']; ?></td>
            			<td><?php echo $fila['nombre_con']; ?></td>
            			<td><?php echo $fila['nombre_tor']; ?></td>
            			<td><?php echo $fila['nombre_mod']; ?></td>
            			<td><?php echo $fila['nombre_viv']; ?></td>
            			<td><?php echo $fila['fullName']; ?></td>
            			<td><?php echo $fila['rut_pro']; ?></td>
            			<td><?php echo $fila['correo_pro']; ?></td>
            			<td><?php echo $fila['fono_pro']; ?></td>
            			<td><?php echo $fila['nombre_com']; ?></td>
            			<td><?php echo $fila['nombre_prof']; ?></td>
            			<td><?php echo $fila['nombre_sex']; ?></td>
            			<td><?php echo $fila['nombre_can_cot']; ?></td>
            			<td><?php echo $fila['nombre_pre_cot']; ?></td>
            			<td><?php echo $fila['nombre_seg_int_cot']; ?></td>
            			<td><?php echo $fila['nombre_vend']." ".$fila['apellido_paterno_vend']; ?></td>
            			<td><?php echo date("d-m-Y",strtotime($fila['fecha_cot'])); ?></td>
            			<td><?php echo $fila['nombre_est_cot']; ?></td>
            		</tr>
                <?php
                	}
                } 
                 ?>
            </tbody>
        </table>
    </section>

      <!-- Main content -->
   	
    <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
  <!-- /.content-wrapper -->
<!-- .wrapper cierra en el footer -->
</body>
</html>
