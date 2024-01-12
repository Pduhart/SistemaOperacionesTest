<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) header("Location: "._ADMIN."index.php");
if (!isset($_SESSION["modulo_evaluacion_panel"])) header("Location: "._ADMIN."panel.php");
?>
<style>
    table,td{
        text-align:center;
        border:none !important;
    }
    .oscuro{
        background-color:#ababab;
        color:black;
    }
    .claro{
        background-color:#ddd;
        color:black;
    }
    .alineacion{
        max-height: 20px;
        color:black;       
        background-color:#ababab;
    }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";
        ?>        
        <div class="content-wrapper">            
            <section class="content-header">
                <h1>
                    Evaluacion de desempeño al vendedor
                    <small>Ingreso</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Evaluacion</a></li>
                    <li class="active">Ingreso</li>
                </ol>
            </section>           
            <section class="content" >
                <div class="row">                    
                    <div class="col-sm-12">                      
                        <div class="box box-primary" style="padding-bottom:100px;">
                        
                          <form>
                            <div class="row">
                                <div class="col-lg-10 col-md-offset-1" style="margin-top:3%;">
                                <!-- etiqueta de prueba para react -->
                                <div id="root"></div>
                                <?php  
                                    $consulta = "SELECT * FROM matriz_preguntas";
                                    $conexion->consulta($consulta);
                                    $matriz = $conexion->extraer_registro();                                    
                                ?>
                                    <label for="vende">
                                        Vendedor a evaluar : 
                                        <select name="vendedor" id="vendedor" class="form-control">                                            
                                            <?php
                                            $queryVende="SELECT * FROM vendedor_vendedor WHERE id_est_vend=1 and id_vend not in(3,5)";
                                            $conexion->consulta($queryVende);
                                            $vendedores = $conexion->extraer_registro();
                                            if(is_array($vendedores)){
                                                foreach ($vendedores as $fila) {
                                                    ?>
                                                    <option value="<?php echo $fila['id_vend'];?>"><?php echo utf8_encode($fila['nombre_vend'])." ".utf8_encode($fila['apellido_paterno_vend'])." ".utf8_encode($fila['apellido_materno_vend']);?> </option>
                                                    <?php
                                                }
                                            }
                                            $queryOperaciones="SELECT * FROM usuario_usuario WHERE id_per=3";
                                            $conexion->consulta($queryOperaciones);
                                            $operaciones = $conexion->extraer_registro();
                                            if(is_array($operaciones)){
                                                foreach ($operaciones as $fila) {
                                                    ?>
                                                    <option value="<?php echo $fila['id_usu'];?>"><?php echo utf8_encode($fila['nombre_usu'])." ".utf8_encode($fila['apellido1_usu'])." ".utf8_encode($fila['apellido2_usu']);?> </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </label>


                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="6" class="oscuro"> <b>NIVELES</b> </td>                                               
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>                                    
                                                <td class="oscuro"><b>CRITERIOS</b></td>
                                                <td class="oscuro"><b>AUSENCIA</b></td>
                                                <td class="oscuro"><b>BAJA</b></td>
                                                <td class="oscuro"><b>MODERADA</b></td>
                                                <td class="oscuro"><b>ALTA</b></td>
                                                <td class="oscuro"><b>SOBRESALIENTE</b></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="3" style=" vertical-align:middle; " class="alineacion">N</td>
                                                <td rowspan="3" style=" vertical-align:middle; " class="alineacion">Competencia</td>
                                                <td rowspan="3" style=" vertical-align:middle; " class="alineacion">Frase</td>
                                                <td class="oscuro" style=" vertical-align:middle; ">ASPECTOS INVOLUCRADOS</td>
                                                <td class="oscuro">Sin presencia de conducta</td>
                                                <td class="oscuro">Baja eficacia o eficiencia</td>
                                                <td class="oscuro">Calidad parcial y baja efectividad</td>
                                                <td class="oscuro">Calidad parcial y alta efectividad</td>
                                                <td class="oscuro">Calidad + Efectividad(Eficiencia + Eficacia)</td>
                                            </tr>
                                            <tr>
                                                
                                                <td class="claro">DEFINICIÓN</td>
                                                <td class="claro">0</td>
                                                <td class="claro">1</td>
                                                <td class="claro">2</td>
                                                <td class="claro">3</td>
                                                <td class="claro">4</td>
                                            </tr>
                                            
                                            <tr>
                                                <td class="claro">CATEGORIA PORCENTUAL DE DESARROLLO</td>
                                                <td class="claro">0%</td>
                                                <td class="claro">25%</td>
                                                <td class="claro">50%</td>
                                                <td class="claro">75%</td>
                                                <td class="claro">100%</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for ($i=0; $i < count($matriz); $i++) {                                                                                             
                                            ?>
                                            <tr>
                                                <td class="oscuro"><?php echo $matriz[$i]['id']?></td>
                                                <td class="oscuro"><?php echo utf8_encode($matriz[$i]['competencia'])?></td>
                                                <td class="oscuro"><?php echo utf8_encode($matriz[$i]['frase'])?></td>
                                                <td style="border:1px solid black !important;"><?php echo utf8_encode($matriz[$i]['pregunta'])?></td>
                                                <td style="width:100px; border:1px solid black !important;">
                                                    <div class="radio" data-toggle="tooltip" data-placement="right" title="<?php echo utf8_encode($matriz[$i]['ausencia'])?>">
                                                        <label>
                                                            <input type="radio" name="optionsRadios<?php echo $matriz[$i]['id']?>" id="matriz1<?php echo $matriz[$i]['id']?>" value="<?php echo $matriz[$i]['id']."1"?>"  checked>                                                            
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="width:100px; border:1px solid black !important;">
                                                    <div class="radio" data-toggle="tooltip" data-placement="right" title="<?php echo utf8_encode($matriz[$i]['baja'])?>">
                                                        <label>
                                                            <input type="radio" name="optionsRadios<?php echo $matriz[$i]['id']?>" id="matriz2<?php echo $matriz[$i]['id']?>" value="<?php echo $matriz[$i]['id']."2"?>"  checked >                                                            
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="width:100px; border:1px solid black !important;">
                                                    <div class="radio" data-toggle="tooltip" data-placement="right" title="<?php echo utf8_encode($matriz[$i]['moderada'])?>">
                                                        <label>
                                                            <input type="radio" name="optionsRadios<?php echo $matriz[$i]['id']?>" id="matriz3<?php echo $matriz[$i]['id']?>" value="<?php echo $matriz[$i]['id']."3"?>"  checked>                                                            
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="width:100px; border:1px solid black !important;">
                                                    <div class="radio" data-toggle="tooltip" data-placement="left" title="<?php echo utf8_encode($matriz[$i]['alta'])?>">
                                                        <label>
                                                            <input type="radio" name="optionsRadios<?php echo $matriz[$i]['id']?>" id="matriz4<?php echo $matriz[$i]['id']?>" value="<?php echo $matriz[$i]['id']."4"?>"  checked>                                                            
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="width:100px; border:1px solid black !important;">
                                                    <div class="radio" data-toggle="tooltip" data-placement="left" title="<?php echo utf8_encode($matriz[$i]['sobresaliente'])?>">
                                                        <label>
                                                            <input type="radio" name="optionsRadios<?php echo $matriz[$i]['id']?>" id="matriz5<?php echo $matriz[$i]['id']?>" value="<?php echo $matriz[$i]['id']."5"?>"  checked>                                                            
                                                        </label>
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                                            
                           <div class="col-md-2 col-md-offset-10">
                                <button class="btn btn-primary btn-lg">Enviar evaluación</button>
                           </div>
                        </form>
                        </div>                    
                    </div>                   
                </div>            
            </section>            
        </div>        
<?php include_once _INCLUDE."footer_comun.php";?>
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<?php include_once _INCLUDE."js_comun.php";?>
<script>
    // react
    // const root = ReactDOM.createRoot(document.getElementById('root'));
    // root.render(<h1>Hello, world!</h1>);
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});  
    $( "form" ).on( "submit", function( event ) {   
      let data = $( this ).serialize();
      $.ajax({
            url:'insert.php',
            type:'POST',
            data: data,
            dataType:'json',
            success:function(result){
                swal(result.title, result.message, result.icon);
            }
      });
      event.preventDefault();
    });

</script>
</body>
</html>
