<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
$nombre = 'listado_clientes_'.date('d-m-Y');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$nombre.".xls");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<title>Listado Clientes</title>
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
                    <th>Nombre</th>
                    <th>N° Depto.</th>
                    <th>Rut</th>
                    <th>Fono</th>
                    <th>Dirección</th>
                    <th>Correo</th>
                    <th>Profesión</th>
                    <th>Género</th>
                    <th>Est. Civil</th>
                    <th>Región</th>
                    <th>Comuna</th>
                    <th>Estado</th>
                </tr>
            </thead>
            
            <tbody>
            	<?php 
            	$consulta = 
                    "
                    SELECT CONCAT(pro.nombre_pro,' ',pro.nombre2_pro,' ',pro.apellido_paterno_pro,' ',pro.apellido_materno_pro) AS fullName, viv.id_viv, pro.rut_pro, pro.fono_pro, pro.direccion_pro, pro.correo_pro, prof.nombre_prof, sex.nombre_sex, civ.nombre_civ, reg.descripcion_reg, com.nombre_com, est_pro.nombre_est_pro, pro.id_est_pro 
                    FROM propietario_propietario AS pro 
                    INNER JOIN propietario_estado_propietario AS est_pro ON est_pro.id_est_pro = pro.id_est_pro 
                    LEFT JOIN vendedor_propietario_vendedor AS ven_pro ON ven_pro.id_pro = pro.id_pro 
                    INNER JOIN profesion_profesion AS prof ON prof.id_prof = pro.id_prof 
                    INNER JOIN civil_civil AS civ ON civ.id_civ = pro.id_civ 
                    INNER JOIN sexo_sexo AS sex ON sex.id_sex = pro.id_sex 
                    INNER JOIN region_region AS reg ON reg.id_reg = pro.id_reg 
                    INNER JOIN comuna_comuna AS com ON com.id_com = pro.id_com 
                    LEFT JOIN propietario_vivienda_propietario AS viv ON viv.id_pro = pro.id_pro 
                    ORDER BY fullName desc
                    ";

                // echo $consulta;
                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();

                if(is_array($fila_consulta)){
                    foreach ($fila_consulta as $fila) {
            		?>
            		<tr>
            			<td><?php echo $fila['fullName']; ?></td>
            			<td><?php 
            			if($fila["id_viv"]<>'') {
							$consulta_viv = 
								"
								SELECT
									viv.nombre_viv
								FROM
									vivienda_vivienda AS viv
								WHERE
									viv.id_est_viv = 2 AND
									viv.id_viv = ".$fila["id_viv"]."
								LIMIT 0,1
								";
							$conexion->consulta($consulta_viv);
							$fila_viv = $conexion->extraer_registro_unico();
							echo utf8_encode($fila_viv['nombre_viv']);
						}
            			 ?></td>
            			<td><?php echo $fila['rut_pro']; ?></td>
            			<td><?php echo $fila['fono_pro']; ?></td>
            			<td><?php echo $fila['direccion_pro']; ?></td>
            			<td><?php echo $fila['correo_pro']; ?></td>
            			<td><?php echo $fila['nombre_prof']; ?></td>
            			<td><?php echo $fila['nombre_sex']; ?></td>
            			<td><?php echo $fila['nombre_civ']; ?></td>
            			<td><?php echo $fila['descripcion_reg']; ?></td>
            			<td><?php echo $fila['nombre_com']; ?></td>
            			<td><?php echo $fila['nombre_est_pro']; ?></td>
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
