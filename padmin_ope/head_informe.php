<?php 
require "config.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>font-awesome-4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://unpkg.com/ionicons@4.2.0/dist/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/skins/_all-skins.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/alert_prueba/dist/sweetalert.css">

  <link rel="stylesheet" href="<?php echo _ASSETS?>dist/css/ajustes.css">

  <script src="<?php echo _ASSETS?>plugins/jQuery/jquery-2.2.3.min.js"></script>

  <!-- estilos nuevos -->
  <style type="text/css">
  	.layout-top-nav .nav-tabs-custom ul.nav-tabs li{
  		margin-right: 1px !important;
  	}
  	.layout-top-nav .nav-tabs-custom ul.nav-tabs li a {
	    position: relative;
	    display: block;
	    padding: 10px 5px !important;
	}
  </style>

  <?php 
  // funciones de encripatacion
	function e64($dato)
	{
		$encode =  base64_encode(base64_encode(base64_encode($dato)));
		return $encode;
	}
	function d64($dato)
	{
		$decode = base64_decode(base64_decode(base64_decode($dato)));
		 return $decode;
	}

   ?>