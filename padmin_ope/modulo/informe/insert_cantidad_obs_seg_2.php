<?php 
session_start(); 
require "../../config.php"; 
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
require_once _INCLUDE."head_informe.php";


include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo_no_aside.php";

// parametro cantidad días tolerancia seg
$consulta_par = 
  "
  SELECT 
    valor_par
  FROM
    parametro_parametro
  WHERE 
  	id_par = 22
  ";
$conexion->consulta($consulta_par);
$fila = $conexion->extraer_registro_unico();
$tolerancia = $fila["valor_par"];

$hoy = date("Y-m-d");
 
$consulta_clientes_sin_seg = 
    "
    SELECT
        DISTINCT pro.id_pro
    FROM
        propietario_propietario AS pro,
        cotizacion_cotizacion AS cot
    WHERE 
        (cot.id_est_cot <> 2 AND cot.id_est_cot <> 3) AND
        pro.id_pro = cot.id_pro
    	AND pro.id_pro < 4000
    	AND pro.id_pro >= 2000
    ";
$conexion->consulta($consulta_clientes_sin_seg);
$fila_consulta_sin = $conexion->extraer_registro();
if(is_array($fila_consulta_sin)){
    foreach ($fila_consulta_sin as $fila_sin) {
        $id_pro = $fila_sin["id_pro"];

        $consulta_obs = "
            SELECT 
                obs.id_obs_pro
            FROM
                propietario_observacion_propietario AS obs
            WHERE
                obs.id_pro = ".$id_pro."";
        $conexion->consulta($consulta_obs);
        $tiene_obs = $conexion->total();
        
        $consulta_cot_seg = "
            SELECT 
                seg.id_seg_cot
            FROM
                cotizacion_cotizacion AS cot,
                cotizacion_seguimiento_cotizacion AS seg
            WHERE
                cot.id_pro = ".$id_pro." AND
                cot.id_cot = seg.id_cot";
        $conexion->consulta($consulta_cot_seg);
        $tiene_seg = $conexion->total();
        
        $queryUpdate = "UPDATE propietario_propietario SET cantidad_obs_pro = ".$tiene_obs.", cantidad_seg_pro = ".$tiene_seg." WHERE id_pro = ".$id_pro."";
        $conexion->consulta($queryUpdate);

        //---------------- SEGUNDA INSERCIÓN DE FECHAS ------------------
        $consulta_obs = "
            SELECT 
                obs.id_obs_pro,
                obs.fecha_obs_pro
            FROM
                propietario_observacion_propietario AS obs
            WHERE
                obs.id_pro = ".$id_pro." 
            ORDER BY obs.id_obs_pro DESC 
            LIMIT 0,1";
        $conexion->consulta($consulta_obs);
        $tiene_obs = $conexion->total();

        if ($tiene_obs>0) {
            $fila_consulta_obs = $conexion->extraer_registro();
            if(is_array($fila_consulta_obs)){
                foreach ($fila_consulta_obs as $fila_obs) {
                    $fecha_obs_pro = $fila_obs["fecha_obs_pro"];
                    $fecha_ultimo_obs = date("Y-m-d",strtotime($fecha_obs_pro));
                    $queryUpdate = "UPDATE propietario_propietario SET fecha_obs_pro = '".$fecha_ultimo_obs."' WHERE id_pro = ".$id_pro."";
                    $conexion->consulta($queryUpdate);
                }
            }
        }

        $consulta_seg = "
            SELECT 
                cot.id_cot,
                cot_seg.fecha_seg_cot
            FROM
                cotizacion_cotizacion AS cot,
                cotizacion_seguimiento_cotizacion AS cot_seg
            WHERE
                cot.id_pro = ".$id_pro." AND
                cot.id_cot = cot_seg.id_cot
            ORDER BY cot_seg.id_seg_cot DESC 
            LIMIT 0,1";

        $conexion->consulta($consulta_seg);
        $tiene_seg = $conexion->total();

        if ($tiene_seg>0) {
            $fila_consulta_seg = $conexion->extraer_registro();
            if(is_array($fila_consulta_seg)){
                foreach ($fila_consulta_seg as $fila_seg) {
                    $fecha_seg_cot = $fila_seg["fecha_seg_cot"];
                    $fecha_ultimo_seg = date("Y-m-d",strtotime($fecha_seg_cot));
                    $queryUpdate = "UPDATE propietario_propietario SET fecha_seg_pro = '".$fecha_ultimo_seg."' WHERE id_pro = ".$id_pro."";
                    $conexion->consulta($queryUpdate);
                }
            }
        }
        
    }
}

?>   
                                                            