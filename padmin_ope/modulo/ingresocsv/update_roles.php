<?php
session_start();
require "../../config.php"; 
if(!isset($_SESSION["sesion_usuario_panel"])) header("Location: ../../index.php");
if(!isset($_SESSION["modulo_uf_panel"])) header("Location: ../../panel.php");
if($_FILES['file_condominio'] == ''){
	header("Location: "._ADMIN."index.php");
	exit();
}
?>
<link rel="stylesheet" href="<?php echo _ASSETS?>plugins/alert_prueba/dist/sweetalert.css">
<script src="<?php echo _ASSETS?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include _INCLUDE."class/conexion.php";
$conexion = new conexion();

try {	
		$condominio = isset($_POST["condominio"]) ? $_POST["condominio"] : "";
		$r=array("//","\\","//");
		$rr=array("/","/","/");		
		$destino =  "../../../archivo/roles/".$_FILES['file_condominio']['name'];
		copy($_FILES['file_condominio']['tmp_name'],$destino);
			
		$rol_viv = 0;
		$rol_bod = 0;
		$aCadena = file($destino);
			$resultado = count($aCadena);
			$resultado = $resultado-1;             
			for ($i = 1; $i <= $resultado; $i++) {
				$dato = explode(";",$aCadena[$i]);

				$vivienda = $dato[0];
				$rol_vivienda = $dato[1];
				$rol_bodega = $dato[2];

                /**  PRIMERO OBTENGO EL ID DE VIVIENDA PARA PORDER ACTUALIZAR EL REGISTRO DE BODEGA DEL CONDOMINIO RESPECTIVO */
				            				
				$conexion->consulta("SELECT id_viv FROM vivienda_vivienda WHERE id_tor = ".$condominio." and nombre_viv='".$vivienda."'");
				$id_vivi = $conexion->extraer_registro_unico();
				$id_vivienda = $id_vivi['id_viv'];

                /** AHORA EJECUTO LA ACTUALIZACION DE REGISTRO DE ROLES A LA TABLA BODEGA */

                $conexion->consulta("UPDATE bodega_bodega SET rol_bod = '".$rol_bodega."' WHERE id_viv = ".$id_vivienda." ");
                $conexion->consulta("UPDATE vivienda_vivienda SET rol_viv = '".$rol_vivienda."' WHERE id_viv = ".$id_vivienda." ");
				
			}           
			echo 'listo?';
	} catch (\Throwable $th) {
		throw $th;
	}catch (Exception $e){
		echo $e->getMessage();
	}catch(PDOException $e){
		echo $e->getMessage();		
	}
?>
<!-- <script>
$(document).ready(function(){
	swal({
	  title: "Excelente!",
	  text: "Estructura de condominio ingresada con éxito!",
	  icon: "success"		
	}).then(()=> window.location.replace("form_select.php"));
});
</script> -->