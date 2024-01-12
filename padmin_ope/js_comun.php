<?php include_once 'config.php';?>
<script src="<?php echo _ASSETS?>plugins/jQueryUI/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script> -->
<script src="<?php echo _ASSETS?>bootstrap/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script src="<?php echo _ASSETS?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo _ASSETS?>plugins/fastclick/fastclick.js"></script>
<script src="<?php echo _ASSETS?>dist/js/app.min.js"></script>
<!-- <script src="<?php echo _ASSETS?>plugins/alert_prueba/dist/sweetalert-dev.js"></script> -->

<script src="<?php echo _ASSETS?>dist/js/app.min.js"></script>
<script src="<?php echo _ASSETS?>dist/js/active.js"></script>

<!-- Para marcar los active -->
<script type="text/javascript">
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>