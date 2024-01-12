<?php include_once 'config.php';?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Plataforma Online</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" href="<?php echo _ASSETS?>bootstrap5/css/bootstrap.min.css">
  <script src="<?php echo _ASSETS?>bootstrap5/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>font-awesome-4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/alert/sweet-alert.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a><b>Sistema  </b>Operaciones</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <!-- <img src="<?php echo _ASSETS?>img/medicina.png" width="79" class="img-responsive center-block"> -->
    <p class="login-box-msg">Iniciar Sesión</p>

    <form action="modulo/login/valida_login.php" id="formulario" method="post">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Usuario" name="usuario_usu" id="usuario_usu" required>
        <span class="fa fa-user-circle input-group-text"></span>
      </div>
      <div class="input-group mb-3">
        <input type="password" class="form-control" placeholder="Password" name="contrasena_usu" id="contrasena_usu" minlength="4" maxlength="6" required>
        <span class="fa fa-lock input-group-text"></span>
      </div>
      <div class="row">
       
        <!-- /.col -->
        <div class="col-xs-6 d-flex justify-content-center">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="ingresar">Ingresar</button>
          <button class="btn btn-primary btn-block btn-flat" type="button" id="load">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            cargando...
          </button>
        </div>
        <!-- /.col -->
      </div>
    </form>    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo _ASSETS?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo _ASSETS?>bootstrap/js/bootstrap.min.js"></script>
<!-- sweet alert -->
<!-- <script src="<?php echo _ASSETS?>plugins/alert/sweet-alert.js"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', (event) => { 
  $("#load").hide(); 
    const popup = (title,message,icon,accion) => {      
      Swal.fire({
        title: title,
        text: message,
        icon: icon,
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        location.href = accion
      })
    }    
    $('#formulario').submit(function(){
        let dataString = $('#formulario').serialize();       
        $.ajax({
          data: dataString,
          type: 'POST',
          url: $(this).attr('action'),
          dataType:'json',
          beforeSend:function (xhr, settings) {
            $("#load").show();
            $("#ingresar").hide();
          },
          success: function(data) {           
            $("#load").hide();
            $("#ingresar").show();
            popup(data.title,data.message,data.icon,data.action);
          }          
        })
      return false;
    });   
});
</script>
</body>
</html>