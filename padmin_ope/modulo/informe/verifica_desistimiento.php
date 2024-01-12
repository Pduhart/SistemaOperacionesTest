<?php 
session_start(); 
require "../../config.php"; 

require_once _INCLUDE."head_informe.php";
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
?>
<title>Departamentos - Listado</title>
<!-- DataTables -->

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">

	<table>

<?php 

echo "empieza";

// $consulta = 
//     "
//     SELECT 
//         ven.id_ven
//     FROM 
//         venta_venta AS ven
//     WHERE 
//         id_est_ven = 3 AND EXISTS (
//         	SELECT 
//             	ven_his.id_ven
//             FROM
//            		venta_estado_historial_venta AS ven_his
//         	WHERE
//              	ven_his.id_ven = ven.id_ven AND ven_his.id_est_ven = 3
//         	)
//     "; 
// $conexion->consulta($consulta);
// $cantidad = $conexion->total();

// echo "--->".$cantidad."<br>";
// $fila_consulta = $conexion->extraer_registro();
// if(is_array($fila_consulta)){
//     foreach ($fila_consulta as $fila) {
//     	echo $fila['id_ven']."<br>";
//     }
// }


$consulta = 
    "
    SELECT 
        ven.id_ven,
        ven.id_pro,
        ven.id_viv,
        ven.fecha_ven
    FROM 
        venta_venta AS ven
    WHERE 
        id_est_ven <> 3 AND EXISTS (
        	SELECT 
            	ven_his.id_ven
            FROM
           		venta_estado_historial_venta AS ven_his
        	WHERE
             	ven_his.id_ven = ven.id_ven AND ven_his.id_est_ven = 3
        	)
    "; 
$conexion->consulta($consulta);
$cantidad = $conexion->total();

echo "<br><br>2--->".$cantidad."<br>";
$fila_consulta = $conexion->extraer_registro();
if(is_array($fila_consulta)){
    foreach ($fila_consulta as $fila) {
    	$ven = $fila['id_ven'];
    	$id_pro = $fila['id_pro'];
    	$id_viv = $fila['id_viv'];
    	$fecha_ven = $fila['fecha_ven'];
    	$fecha_ven_for = date("d-m-Y",strtotime($fecha_ven));

    	$consulta_nombre_pro = 
		    "
		    SELECT
		        nombre_pro,
		        apellido_paterno_pro
		    FROM
		        propietario_propietario
		    WHERE
		        id_pro = ".$id_pro."
		    ";
	    $conexion->consulta($consulta_nombre_pro);
	    $fila = $conexion->extraer_registro_unico();
	    $nombre_pro = utf8_encode($fila['nombre_pro']." ".$fila['apellido_paterno_pro']);

	    $consulta_nombre_viv = 
		    "
		    SELECT
		        viv.nombre_viv,
		        tor.nombre_tor
		    FROM
		        vivienda_vivienda AS viv,
		        torre_torre AS tor
		    WHERE
		        viv.id_viv = ".$id_viv." AND
		        viv.id_tor = tor.id_tor
		    ";
	    $conexion->consulta($consulta_nombre_viv);
	    $fila = $conexion->extraer_registro_unico();
	    $nombre_viv = $fila['nombre_viv'];
	    $nombre_tor = $fila['nombre_tor'];

	    ?>
	    <tr>
	    	<td><?php echo $ven ?></td>
	    	<td><?php echo $nombre_pro ?></td>
	    	<td>DEPTO: <?php echo $nombre_viv ?> ( <?php echo $nombre_tor ?> )</td>
	    	<td><?php echo $fecha_ven_for ?></td>
	    </tr>
	    <?php


    }
}
 ?>
</table>
</body>
</html>
