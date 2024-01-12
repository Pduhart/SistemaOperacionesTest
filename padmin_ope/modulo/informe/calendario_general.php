<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="<?php echo _ASSETS;?>plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo _ASSETS;?>plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- qtip -->
   <link rel="stylesheet" href="<?php echo _ASSETS; ?>plugins/qtip/jquery.qtip.css">
   <link rel="stylesheet" href="<?php echo _ASSETS?>plugins/select2/select2.min.css">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<?php 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo_no_aside.php";
 ?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid amplio">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Reservas
          <small>Informe</small>
        </h1>
        <ol class="breadcrumb">
            <li></i> Home</li>
            <li>Reservas</li>
            <li class="active">Calendario</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="col-sm-12">
          <!-- general form elements -->
          <div class="box box-primary">
              
            <div class="box-tools">


                <?php
                if ($_SESSION["sesion_perfil_panel"] == 3) {
                    //propietario
                    $consulta = 
                        "
                        SELECT DISTINCT
                            tor.id_tor,
                            tor.nombre_tor
                        FROM
                            vivienda_vivienda AS viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                            INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
                            INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
                        WHERE
                            tor.id_est_tor = 1 AND
                            usu.id_usu = ".$_SESSION["sesion_id_panel"]."
                        ";
                }
                else{
                    $consulta = 
                    "
                    SELECT 
                        id_tor,
                        nombre_tor 
                    FROM 
                        torre_torre
                    WHERE
                        id_est_tor = 1
                    ORDER BY 
                        nombre_tor
                    ASC
                    ";
                }

                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();
                $fila_consulta_torre_original = $fila_consulta;

                //---------DEPARTAMENTO

                if ($_SESSION["sesion_perfil_panel"] == 3) {
                    //propietario
                    $consulta = 
                        "
                        SELECT DISTINCT
                            viv.id_viv,
                            viv.nombre_viv
                        FROM
                            vivienda_vivienda AS viv
                            INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                            INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                            INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
                            INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
                        WHERE
                            viv.id_est_viv = 1 AND
                            usu.id_usu = ".$_SESSION["sesion_id_panel"]."
                        ";
                }
                else{
                    $consulta = 
                    "
                    SELECT
                        viv.id_viv, 
                        viv.nombre_viv
                        
                    FROM 
                        vivienda_vivienda AS viv
                    WHERE
                        viv.id_est_viv = 1
                    ORDER BY 
                        viv.nombre_viv
                    ASC
                    ";
                }
                $conexion->consulta($consulta);
                $fila_consulta = $conexion->extraer_registro();
                $fila_consulta_vivienda_original = $fila_consulta;
                ?>


                <div class="form-group col-sm-2">
                    <label for="condominio">Condominio:</label>
                    <select class="form-control select2" id="condominio" name="condominio"> 
                        <option value="">Seleccione Condominio</option>
                        <?php
                        if ($_SESSION["sesion_perfil_panel"] == 3) {
                            //propietario
                            $consulta = 
                                "
                                SELECT DISTINCT
                                    con.id_con,
                                    con.nombre_con
                                FROM
                                    vivienda_vivienda AS viv
                                    INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                                    INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                                    INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
                                    INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
                                WHERE
                                    con.id_est_con = 1 AND
                                    usu.id_usu = ".$_SESSION["sesion_id_panel"]."
                                ORDER BY nombre_con
                                ";
                        }
                        else{
                            $consulta = "SELECT id_con, nombre_con FROM condominio_condominio WHERE id_est_con = 1 ORDER BY nombre_con";
                        }
                        $conexion->consulta($consulta);
                        $fila_consulta = $conexion->extraer_registro();
                        $fila_consulta_condominio_original = $fila_consulta;
                        if(is_array($fila_consulta)){
                            foreach ($fila_consulta as $fila) {
                                ?>
                                <option value="<?php echo $fila['id_con'];?>"><?php echo utf8_encode($fila['nombre_con']);?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-sm-2">
                    <label for="torre">Torre:</label>
                    <select class="form-control select2" id="torre" name="torre"> 
                        <option value="">Seleccione Torre</option>
                    </select>
                </div>

                <div class="form-group col-sm-2">
                    <label for="departamento">Departamento:</label>
                    <select class="form-control select2" id="departamento" name="departamento"> 
                        <option value="">Seleccione Departamento</option>
                    </select>
                </div>
                <div class="form-group col-sm-2" style="padding-top: 20px">
                    <input type="button" value="FILTRAR" name="filtro" id="filtro" class="btn btn-xs btn-icon btn-primary"></input>
                </div>
                <div class="col-sm-12" id="contenedor_filtro">
                  <h6 class="pull-right" style="font-style: italic; color:#ccc; font-size: 13px">
                    <i>Filtro: 
                      <?php 
                      $elimina_filtro = 0;
                      if(isset($_SESSION["sesion_filtro_condominio_panel"])){
                          $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_condominio_original));
                          $fila_consulta_condominio = array();
                          foreach($it as $v) {
                              $fila_consulta_condominio[]=$v;
                          }
                          $elimina_filtro = 1;
                          
                          if(is_array($fila_consulta_condominio)){
                              foreach ($fila_consulta_condominio as $fila) {
                                  if(in_array($_SESSION["sesion_filtro_condominio_panel"],$fila_consulta_condominio)){
                                      $key = array_search($_SESSION["sesion_filtro_condominio_panel"], $fila_consulta_condominio);
                                      $texto_filtro = $fila_consulta_condominio[$key + 1];
                                      
                                  }
                              }
                          }
                          ?>
                          <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                          <?php
                          $filtro_consulta .= " AND con.id_con = ".$_SESSION["sesion_filtro_condominio_panel"];
                      }
                      else{
                          ?>
                          <span class="label label-default">Sin filtro</span> | 
                          <?php       
                      }
                      if(isset($_SESSION["sesion_filtro_torre_panel"])){
                          $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_torre_original));
                          $fila_consulta_torre = array();
                          foreach($it as $v) {
                              $fila_consulta_torre[]=$v;
                          }
                          $elimina_filtro = 1;
                          
                          if(is_array($fila_consulta_torre)){
                              foreach ($fila_consulta_torre as $fila) {
                                  if(in_array($_SESSION["sesion_filtro_torre_panel"],$fila_consulta_torre)){
                                      $key = array_search($_SESSION["sesion_filtro_torre_panel"], $fila_consulta_torre);
                                      $texto_filtro = $fila_consulta_torre[$key + 1];
                                      
                                  }
                              }
                          }
                          ?>
                          <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                          <?php
                          $filtro_consulta .= " AND tor.id_tor = ".$_SESSION["sesion_filtro_torre_panel"];
                      }
                      else{
                          ?>
                          <span class="label label-default">Sin filtro</span> | 
                          <?php       
                      }
                      if(isset($_SESSION["sesion_filtro_vivienda_panel"])){
                          $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($fila_consulta_vivienda_original));
                          $fila_consulta_vivienda = array();
                          foreach($it as $v) {
                              $fila_consulta_vivienda[]=$v;
                          }
                          $elimina_filtro = 1;
                          
                          if(is_array($fila_consulta_vivienda)){
                              foreach ($fila_consulta_vivienda as $fila) {
                                  if(in_array($_SESSION["sesion_filtro_vivienda_panel"],$fila_consulta_vivienda)){
                                      $key = array_search($_SESSION["sesion_filtro_vivienda_panel"], $fila_consulta_vivienda);
                                      $texto_filtro = $fila_consulta_vivienda[$key + 1];
                                      
                                  }
                              }
                          }
                          ?>
                          <span class="label label-primary"><?php echo utf8_encode($texto_filtro);?></span> |  
                          <?php
                          $filtro_consulta .= " AND res.id_viv = ".$_SESSION["sesion_filtro_vivienda_panel"];
                      }
                      else{
                          ?>
                          <span class="label label-default">Sin filtro</span>
                          <?php       
                      }
                      
                      if ($elimina_filtro <> 0) {
                        ?>
                        <i class="fa fa-times fa-2x borra_sesion" style="cursor: pointer;" aria-hidden="true"></i>
                        <?php
                      }

                      ?>
                      
                  </i>
                </h6>
              </div>
            </div>
            
            <div class="clearfix"></div>
            <div class="box-body">
                <!-- <a href="http://www.google.com/calendar/event?action=TEMPLATE&text=Titulo&dates=20170127T224000Z/<?php //echo $fecha;?>T<?php //echo $hora;?>Z&details=detalle&location=casa&trp=false&ctz=America/Santiago" target="_blank" rel="nofollow">Add to my calendar</a> -->
                <!-- THE CALENDAR -->
                <div id="calendar"></div> 
            </div>
          </div>
          <!-- /.box -->
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
<?php include_once _INCLUDE."footer_comun.php";?>
<!-- .wrapper cierra en el footer -->
<?php include_once _INCLUDE."js_comun.php";?>
<script src="<?php echo _ASSETS;?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo _ASSETS;?>plugins/fullcalendar/fullcalendar.js"></script>
<script src="<?php echo _ASSETS;?>plugins/fullcalendar/es.js"></script>
<script src="<?php echo _ASSETS;?>plugins/qtip/jquery.qtip.js"></script>
<!-- date-range-picker -->
<script src="<?php echo _ASSETS?>plugins/select2/select2.full.min.js"></script>

<script>
  $(function () {
    $('#condominio').change(function () {
        valor = $(this).val();
        $.ajax({
            type: 'POST',
            url: ("procesa_condominio_propietario.php"),
            data:"valor="+valor,
            success: function(data) {
                $('#torre').html(data);
            }
        })
    });
    $('#torre').change(function () {
        valor = $(this).val();
        $.ajax({
            type: 'POST',
            url: ("procesa_torre_propietario.php"),
            data:"valor="+valor,
            success: function(data) {
                $('#departamento').html(data);
            }
        })
    });

    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });

    $(document).on( "click","#filtro" , function() {
        //$('#contenedor_filtro').html('<img src="../../assets/img/loading.gif">');
        var_condominio = $('#condominio').val();
        var_torre = $('#torre').val();
        var_vivienda = $('#departamento').val();
        $.ajax({
            type: 'POST',
            url: ("filtro_update.php"),
            data:"condominio="+var_condominio+"&torre="+var_torre+"&vivienda="+var_vivienda,
            success: function(data) {
                location.reload();
            }
        })
    });

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    $('#calendar').fullCalendar({
      timeFormat: 'H:mm',
      locale: 'es',
      height: 650,
      displayEventEnd: true,
      timezone: 'America/Santiago',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
      buttonText: {
        today: 'Hoy',
        month: 'Mes',
        week: 'semana',
        day: 'día'
      },
      //Random default events
      events: [
        <?php
        if ($_SESSION["sesion_perfil_panel"] == 3) {
          //propietario
          $consulta = 
            "
            SELECT
              *
            FROM
                reserva_reserva AS res
                INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
                INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
                INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
                INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
                INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
            WHERE
                (res.id_est_res = 2 or res.id_est_res = 1) AND
                usu.id_usu = ".$_SESSION["sesion_id_panel"]." ".$filtro_consulta."
            ";
        }
        else{
          $consulta = 
            "
            SELECT 
              * 
            FROM 
              reserva_reserva AS res
              INNER JOIN vivienda_vivienda AS viv ON res.id_viv = viv.id_viv
              INNER JOIN torre_torre AS tor ON tor.id_tor = viv.id_tor
              INNER JOIN condominio_condominio AS con ON con.id_con = tor.id_con
              INNER JOIN propietario_vivienda_propietario AS pro ON pro.id_viv = viv.id_viv
              INNER JOIN usuario_usuario AS usu ON usu.id_pro = pro.id_pro
            WHERE 
              res.id_est_res = 2 ".$filtro_consulta."
            ";
        }
        
        $conexion->consulta($consulta);
        $cantidad_reserva = $conexion->total();
        $contador = 1;
        $fila_consulta = $conexion->extraer_registro();
        if(is_array($fila_consulta)){
          foreach ($fila_consulta as $fila) {
              $color = "C3D2D3";
              
              
              $fecha_anio_desde = date("Y",strtotime($fila["fecha_desde_res"]));
              $fecha_mes_desde = date("m",strtotime($fila["fecha_desde_res"]));
              $fecha_mes_desde = $fecha_mes_desde - 1;
              $fecha_dia_desde = date("d",strtotime($fila["fecha_desde_res"]));

              

              $fecha_anio_hasta = date("Y",strtotime($fila["fecha_hasta_res"]));
              $fecha_mes_hasta = date("m",strtotime($fila["fecha_hasta_res"]));
              $fecha_mes_hasta = $fecha_mes_hasta - 1;
              $fecha_dia_hasta = date("d",strtotime($fila["fecha_hasta_res"]));

              // $hora_hora_desde = date("G",strtotime($fila["hora_desde_res"]));
              // $hora_minuto_desde = date("i",strtotime($fila["hora_desde_res"]));

              // $hora_hora_hasta = date("G",strtotime($fila["hora_hasta_res"]));
              // $hora_minuto_hasta = date("i",strtotime($fila["hora_hasta_res"]));
              $descripcion = 'Depto: '.utf8_encode($fila["nombre_viv"]);
              // if($_SESSION["sesion_perfil_panel"] == 1 || $_SESSION["sesion_perfil_panel"] == 3){
              //   $chofer_texto = utf8_encode($fila["nombre_chofer"]." ".$fila["apellido1_chofer"]." ".$fila["apellido2_chofer"]);
              //   $descripcion = "Chofer: ".utf8_encode($fila["nombre_chofer"]." ".$fila["apellido1_chofer"]." ".$fila["apellido2_chofer"]);
              //   $descripcion .= " - Solicitante: ".utf8_encode($fila["nombre_usu"]." ".$fila["apellido1_usu"]." ".$fila["apellido2_usu"]);
              // }
              
              $hora_hora_desde = 15;
              $hora_hora_hasta = 12;
              if($contador < $cantidad_reserva){
                ?>
                {
                  title: 'Reserva : <?php echo $fila["id_res"];?>',
                  description: '<?php echo $descripcion;?>',
                  start: new Date(<?php echo $fecha_anio_desde;?>, <?php echo $fecha_mes_desde;?>, <?php echo $fecha_dia_desde;?>, <?php echo $hora_hora_desde;?>, 0),
                  end: new Date(<?php echo $fecha_anio_hasta;?>, <?php echo $fecha_mes_hasta;?>, <?php echo $fecha_dia_hasta;?>, <?php echo $hora_hora_hasta;?>, 0),
                  allDay: false,
                  backgroundColor: "<?php echo $color;?>", //Blue
                  borderColor: "<?php echo $color;?>" //Blue
                },
                <?php
              }
              else{
                ?>
                {
                  title: 'Reserva : <?php echo $fila["id_res"];?>',
                  description: '<?php echo $descripcion;?>',
                  start: new Date(<?php echo $fecha_anio_desde;?>, <?php echo $fecha_mes_desde;?>, <?php echo $fecha_dia_desde;?>, <?php echo $hora_hora_desde;?>, <?php echo $hora_minuto_desde;?>),
                  end: new Date(<?php echo $fecha_anio_hasta;?>, <?php echo $fecha_mes_hasta;?>, <?php echo $fecha_dia_hasta;?>, <?php echo $hora_hora_hasta;?>, <?php echo $hora_minuto_hasta;?>),
                  allDay: false,
                  backgroundColor: "<?php echo $color;?>", //Blue
                  borderColor: "<?php echo $color;?>" //Blue
                }
                <?php
              }
                
              $contador++;
            }
        }
        ?>
        
      ],
      editable: false,
      droppable: false, // this allows things to be dropped onto the calendar !!!

      eventRender: function(event, element) {
        element.qtip({
            content: event.description,
            position: {
                my: 'top left',  // Position my top left...
                at: 'bottom',
                target: 'mouse'
            }
        });
      }

    });    
    
  });
</script>
</body>
</html>
