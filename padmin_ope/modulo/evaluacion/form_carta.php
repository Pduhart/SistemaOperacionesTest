<?php 
session_start(); 
require "../../config.php"; 
require_once _INCLUDE."head.php";
if (!isset($_SESSION["sesion_usuario_panel"])) {
    header("Location: "._ADMIN."index.php");
}
if (!isset($_SESSION["modulo_evaluacion_panel"])) {
    header("Location: "._ADMIN."panel.php");
}
?>
<style>
    .borde{
        border:1px solid black;
    }
    .borde2{
        border:3px solid #D7DBDD;
    }
    table, tr, td{
    border:none;
    vertical-align: middle !important;
    }    
    .bg-yellow, .callout.callout-warning, .alert-warning, .label-warning, .modal-warning .modal-body {
        color: #8a6d3b !important; 
        background-color: #fcf8e3 !important; 
        border: none !important; 
    }
    .inputStyle{
        color:
    }
    .redNumbers{
        text-align:center;
        color:#dd131a;
        font-weight: 900;
        font-size:1.6em;
    }
    .blackNumbers{
        text-align:center;
        color:black;
        font-weight: 900;
        font-size:2em;
    }
    .blackNumbers2{
        text-align:center;
        color:black;
        font-weight: 900;
        font-size:1.6em;
    }
    input,select,textarea{
        border:none !important;
    }  
    .fondo{
        background:#FDEBDF;
    } 
    .negritas{
        font-weight: bold;
    } 
   
</style>

</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
        include _INCLUDE."class/conexion.php";
        $conexion = new conexion();
        require_once _INCLUDE."menu_modulo.php";
        $year = date('Y');
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                CARTA DE MÉRITO O DEMÉRITO
                    <small>Detalle</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Evaluación</a></li>
                    <li class="active">CARTA DE MÉRITO O DEMÉRITO</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->
                      
                        <div class="box box-primary" >   
                            <div class="container">
                                                      
                            </div>                        
                            <div class="container" >
                                
                                <div class="row">

                                    <div class="col-md-8 col-md-offset-2" style="margin-top:3%; "> 
                                        <div class="row" style="margin-bottom:3%;">
                                            <div class="col-md-3 text-left" >
                                                <img src="<?php echo _ASSETS?>img/logo-icp.jpg" alt="">
                                            </div> 
                                            <div class="col-md-6 text-center">
                                                <h3>ANEXO 03: CARTA DE MÉRITO O DEMÉRITO</h3>
                                            </div> 
                                            <div class="col-md-3 text-center" >
                                                <h3><small>PR-A- 02- INFORME <br>DESEMPEÑO / VERSIÓN <br>07/2022</small></h3>
                                            </div>                                                                       
                                        </div>    

                                    
                                         <!-- Primer formulario -->
                                        <table class="table"  style="margin-bottom:30px;">                                                                                      
                                            <tbody>
                                                <tr style="border:1px solid black">
                                                    <td class="borde fondo negritas" style="border:1px solid black">TRABAJADOR/A</td>
                                                    <td class="borde" style="border:1px solid black"> <select name="persona" id="persona" class="form-control clean" autocomplete="off"></td>                                               
                                                    <td class="borde fondo negritas" style="border:1px solid black">FECHA DE ANOTACIÓN : </td>
                                                    <td class="borde negritas" style="border:1px solid black"> <?php echo Date('d-m-Y');?> </td>
                                                </tr>                                                                                               
                                            </tbody>
                                        </table>  

                                        <p style="text-align:justify; font-size:1.2em; font-family:Arial; padding-bottom:7%;">
                                         Conforme al punto 4.4 del PROCEDIMIENTO DE CICLO DE EVALUACIÓN DEL DESEMPEÑO PARA EQUIPO DE OPERACIONES, 
                                         PR-EVALUACION DESEMPEÑO-OPERACIONES-V.1 y todos los instrumentos disponibles en la empresa, tales como: Contratos laborales y/o Reglamentos internos u otros, 
                                         se considera una <b>anotación de mérito o de demérito</b>, conforme a las definiciones del procedimiento citado. 
                                         La anotación será realizada por el superior jerárquico cuando haya evidenciado una acción consistente a los propósitos de dicho mérito o demérito,
                                         dependiendo del caso y se materializará mediante este anexo, el cual deberá responder al estándar de los principios del procedimiento, 
                                         entre ellos: Fundamentación Objetividad, Imparcialidad y Confidencialidad.<br><br>
                                         Cabe hacer presente que, el mecanismo de mérito y de demérito tiene una incidencia directa 
                                         en el proceso de evaluación de desempeño conforme a lo descrito en el procedimiento.
                                        </p>
                                        
                                        <table class="table"  style="margin-bottom:50px; text-align:center;">
                                            <thead>
                                                <tr>
                                                    <td style="border-bottom:1px solid black; font-size:1.2em; font-family:Arial"><strong style="margin-right:10px;">ANOTACIÓN DE : </strong> 
                                                        <select name="carta" id="carta" style="border:1px solid grey !important; padding:8px;" class="text-center">
                                                            <option value="Merito">Merito</option>
                                                            <option value="Demerito">Demerito</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td class="borde fondo negritas" >DESCRIPCIÓN DEL COMPORTAMIENTO EVIDENCIADO OBSERVADO</td>                                                                                              
                                                </tr>

                                                <tr>
                                                    <td class="borde"><textarea name="descripcion" class="form-control clean" id="descripcion" cols="30" rows="3"></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td class="borde fondo negritas" >
                                                        REFERENCIA REGLAMENTARIA (PROCEDIMIENTO, REGLAMENTO DE ORDEN, 
                                                        HIGIENE Y SEGURIDAD, CONTRATO DE TRABAJO O DESCRITOR DE CARGO).
                                                    </td>                                                                                              
                                                </tr>

                                                <tr>
                                                    <td class="borde"><textarea name="referencia" class="form-control clean" id="referencia" cols="30" rows="3"></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td class="borde fondo negritas" >FUNDAMENTACIÓN DE LA DECISIÓN</td>                                                                                              
                                                </tr>
                                                <tr>
                                                    <td class="borde"><textarea name="fundamentacion" class="form-control clean" id="fundamentacion" cols="30" rows="3"></textarea></td>
                                                </tr> 
                                                <tr>
                                                    <td class="borde fondo negritas">RESOLUCIÓN (MÉRITO / DEMÉRITO)</td>                                                                                              
                                                </tr>
                                                <tr>
                                                    <td class="borde"><textarea name="resolucion" class="form-control clean" id="resolucion" cols="30" rows="3"></textarea></td>
                                                </tr>                                            
                                      
                                            </tbody>                                           
                                        </table>                                     
                                     

                                    </div> 
                                </div> <!--.row-->
                              

                                <div class="row" style="padding-bottom:3%;">
                                    <div class="container">                                        
                                        <div class="col-md-12 text-center">
                                            <input type="button" class="btn btn-primary btn-lg" id="guardar" value="Guardar carta" />                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>      
                                       
                    </div>                    
                </div>
            </section>            
        </div>        
<?php include_once _INCLUDE."footer_comun.php";?>
<?php include_once _INCLUDE."js_comun.php";?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', (event) => {   
    
    const _v = (propiedad) => {
        return ($("#"+propiedad).val() != '')?$("#"+propiedad).val():true;
    }
    const limpiar = () => $(".clean").val('');
    const req = (url,id) => {
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'html',
            success:function(data){
            $("#"+id+"").html(data);
            }
        })
    }
    
    req('getAllPersonas.php','persona');
    $("#guardar").on('click',function(e){

       let infoCarta = {
            trabajador : _v('persona'),
            carta :  _v('carta'),
            descripcion : _v('descripcion'),
            referencia :  _v('referencia'),
            fundamentacion : _v('fundamentacion'),
            resolucion : _v('resolucion')
           }
        let errors = [];        
        if(infoCarta.trabajador == true) errors.push("Nombre trabajador")
        if(infoCarta.carta  == true) errors.push("Anotacion");
        if(infoCarta.descripcion  == true) errors.push("Descripción del comportamiento");
        if(infoCarta.referencia == true) errors.push("Referencia reglamentaria");
        if(infoCarta.fundamentacion == true) errors.push("Fundamentación de la decisión");
        if(infoCarta.resolucion == true) errors.push("Resolución");
        
        if(errors.length>0){
            let validaciones ="\n";
            for (let index = 0; index < errors.length; index++) {                
                      validaciones += "\n * "+errors[index]+"";
                }
            swal("Error!","Porfavor complete las siguientes áreas : "+ validaciones +"","error");
        }else{
            $.ajax({
            url : 'insert_carta.php',
            type : 'POST',
            dataType : 'json',
            data : infoCarta,
            success : function(info){
                limpiar();                
                swal(info.title,info.message,info.icon)

            }
        });
        }        
    })

});
</script>
</body>
</html>
