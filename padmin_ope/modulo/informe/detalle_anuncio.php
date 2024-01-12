<?php
session_start();
require "../../config.php";
if(!isset($_SESSION["sesion_usuario_panel"])){
    header("Location: ../../index.php");
}
if(!isset($_POST["valor"])){
	?>
	<script type="text/javascript">
		window.location="../../index.php";
	</script>
	<?php
	exit();
}
include _INCLUDE."class/conexion.php";
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.9";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php
$conexion = new conexion();
$id = $_POST["valor"];

$consulta = 
  "
  SELECT
    *
  FROM
    ticket_ticket
  WHERE
    id_tic = ?
";
$conexion->consulta_form($consulta,array($id));
$fila = $conexion->extraer_registro_unico();
$asunto_tic = utf8_encode($fila["asunto_tic"]);
$descripcion_tic = utf8_encode($fila["descripcion_tic"]);
$descripcion_tic = str_replace("\n", "<br>", $descripcion_tic); 
$fecha_tic = utf8_encode($fila["fecha_tic"]);
$fecha_tic = date("d-m-Y",strtotime($fecha_tic));
$documento_tic = utf8_encode($fila["documento_tic"]);
?>

<div class="modal-dialog modal-center">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title"><?php echo $asunto_tic;?></h4>
        </div>
        <div class="modal-body">
            <img src="../archivo/ticket/documento/<?php echo utf8_encode($fila["id_tic"]);?>/<?php echo utf8_encode($fila["documento_tic"]);?>" class="img-responsive">
            <p class="text-muted"><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php echo $fecha_tic;?></p>
            <p><?php echo $descripcion_tic;?></p>
        </div>
        <div class="modal-footer">
            <a href="../archivo/ticket/documento/<?php echo utf8_encode($fila["id_tic"]);?>/<?php echo utf8_encode($fila["documento_tic"]);?>" class="btn btn-default btn-sm" download><i class="fa fa-download" aria-hidden="true"></i></a>
            <div class="fb-like" data-href="http://administradorapacifico.cl/ticket/documento/<?php echo utf8_encode($fila["id_tic"]);?>/<?php echo utf8_encode($fila["documento_tic"]);?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
        </div>
    </div>
</div>



