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
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
require_once _INCLUDE."menu_modulo.php";
$year = date('Y');
$totalMetasOps = conexion::select("SELECT count(*) as total FROM ppsavcl_ssoopp_digital.venta_venta where id_est_ven = 7 and  id_viv in(SELECT id_viv FROM vivienda_vivienda WHERE id_tor=6)");
$totalMetasEscrituracion = 112;
$total_metas = ($totalMetasOps[0]['total'] * 100) / $totalMetasEscrituracion;
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
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Informe de Desempeño de Operaciones
                    <small>Detalle</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo _ADMIN?>panel.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Evaluación</a></li>
                    <li class="active">Detalle Operaciones</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="col-sm-12">
                      <!-- general form elements -->                      
                        <div class="box box-primary" >                                                   
                            <div class="container" >
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2" style="margin-top:3%;"> 
                                        <div class="row" style="margin-bottom:3%;">
                                            <div class="col-md-3 text-left" >
                                                <img src="<?php echo _ASSETS?>img/logo-icp.jpg" alt="">
                                            </div> 
                                            <div class="col-md-6 text-center">
                                                <h3>ANEXO 02: INFORME DE DESEMPEÑO</h3>
                                            </div> 
                                            <div class="col-md-3 text-center" >
                                                <h3><small>PR-A- 02- INFORME <br>DESEMPEÑO / VERSIÓN <br>07/2022</small></h3>
                                            </div>                                                                       
                                        </div>
                                        <table class="table table-bordered" >                                                
                                                <tbody>
                                                    <tr>
                                                        <td width="25%" class="fondo"><b>CICLO DE EVALUACIÓN : </b></td>
                                                        <td class="text-center"><input size="3" type="number" id="year" name="year" value="<?php echo $year?>" style="border:1px solid #D7DBDD !important; width:80px; padding:2%; text-align:center;"></td>
                                                        <td width="20%" class="fondo"><b>FECHA EVALUACIÓN : </b></td>
                                                        <td><input type="date" id="fecha_eva" name="fecha_eva" class="form-control" value="<?php echo Date('d-n-Y');?>" style="border:1px solid #D7DBDD !important; padding:2%; text-align:center;"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <div class="row" style="margin-top:3%; margin-bottom:3%;">
                                        <!-- <div class="col-md-8 col-md-offset-2" style=margin-top:3%;> -->
                                                <div class="alert alert-warning" role="alert" style="padding:30px;">
                                                <i class="fa fa-warning" aria-hidden="true" style="font-size:2.4rem; padding:0 1% 1% 0;"></i>
                                                En consideración de este ciclo de evaluación de desempeño, ambas partes concuerdan que, al momento de la evaluación de desempeño, se tuvo presente, la asignación de metas de ciclo de evaluación, el Descriptor de Cargo y los lineamientos estratégicos de la empresa (Misión, Visión, Objetivos estratégicos)
                                                </div>
                                            <!-- </div> -->
                                        </div>
                                         <!-- Primer formulario -->
                                        <table class="table"  style="margin-bottom:70px;">
                                            <thead >
                                                <tr >
                                                    <td colspan="5" style="border-bottom:1px solid black"><strong >1. IDENTIFICACIÓN PERSONA EVALUADA.</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody class="borde">
                                                <tr >
                                                    <td class="borde fondo negritas">NOMBRE PERSONA EVALUADA</td>
                                                    <td colspan="4" class="borde"> Margot Andrea Moya Olivares.</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde fondo negritas">CARGO / FUNCIÓN</td>
                                                    <td colspan="4" class="borde"> Colaboradora de Operaciones. </tr>
                                                <tr>
                                                    <td class="borde fondo negritas">NOMBRE EVALUADOR/A</td>
                                                    <td colspan="4" class="borde"> Sara Noemí Araya Bugueño.</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde fondo negritas">CARGO / FUNCIÓN</td>
                                                    <td colspan="4" class="borde">
                                                    Jefa de operaciones.
                                                    </td>                                         
                                                </tr>                                                
                                            </tbody>
                                        </table>  
                                        
                                        <table class="table"  style="margin-bottom:50px; text-align:center;">
                                            <thead>
                                                <tr>
                                                    <td colspan="4" class="text-left" style="border-bottom:1px solid black"><strong>2.- CRITERIOS DE EVALUACIÓN.</strong><small>*(metas validas solo para modelo PAC 3100, etapa 1)</small></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="borde fondo negritas" width="30%"> 2.1 META ASIGNADA </td>
                                                    <td class="borde fondo"> AÑO ANTERIOR </td>
                                                    <td class="borde fondo"> AÑO ACTUAL </td>
                                                    <td class="borde fondo"> % LOGRO </td>
                                                </tr>                                               
                                                <tr>
                                                    <td class="borde"> <input type="number" id="totalAsignado" name="totalAsignado" value="<?php echo $totalMetasEscrituracion?>" class="form-control limpia blackNumbers" readonly></td>
                                                    <td class="borde"><input type="number" id="totalAnterior" name="totalAsignado" value="0" class="form-control limpia blackNumbers" readonly></td>
                                                    <td class="borde"><input type="number" id="totalLogrado" name="totalLogrado" value="<?php echo $totalMetasOps[0]['total']?>" class="form-control limpia blackNumbers"  readonly></td>
                                                    <td class="borde"><input type="number" id="porcentajeTotal" name="porcentajeTotal" value="<?php echo round($total_metas)?>" class="form-control limpia blackNumbers"  readonly></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" class="borde text-left fondo">FUNDAMENTACIÓN</td>                                                    
                                                    <td colspan="3" class="borde"><textarea name="fundamentacion1" class="form-control" id="fundamentacion1" cols="20" rows="3" autocomplete="off"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo negritas"><strong>2.2 COMPETENCIA 1</strong> </td>
                                                    <td class="borde fondo">DESARROLLO PROPUESTO</td>
                                                    <td class="borde fondo">AÑO ACTUAL</td>
                                                    <td class="borde fondo">% LOGRO</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo negritas">X</td>
                                                    <td class="borde redNumbers">4</td>
                                                    <td class="borde"><input type="number" id="orientacionAlCLiente" name="orientacionAlCLiente" class="form-control redNumbers limpia" autocomplete="off"></td>
                                                    <td class="borde blackNumbers limpiaText" id="total1">0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" class="borde text-left fondo" >FUNDAMENTACIÓN</td>                                                    
                                                    <td colspan="3" class="borde"><textarea name="fundamentacion2" class="form-control limpia" id="fundamentacion2" cols="20" rows="3" autocomplete="off"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo"><strong>2.3 COMPETENCIA 2</strong></td>
                                                    <td class="borde fondo">DESARROLLO PROPUESTO</td>
                                                    <td class="borde fondo">AÑO ACTUAL</td>
                                                    <td class="borde fondo">% LOGRO</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo negritas">X</td>
                                                    <td class="borde redNumbers">4</td>
                                                    <td class="borde"><input type="number" id="habilidades" name="habilidades" class="form-control redNumbers limpia" autocomplete="off"></td>
                                                    <td class="borde blackNumbers limpiaText" id="total2">0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" class="borde text-left fondo" >FUNDAMENTACIÓN</td>                                                    
                                                    <td colspan="3" class="borde"><textarea name="fundamentacion3" class="form-control limpia" id="fundamentacion3" cols="20" rows="3" autocomplete="off"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo"><strong>2.4 COMPETENCIA 3</strong></td>
                                                    <td class="borde fondo">DESARROLLO PROPUESTO</td>
                                                    <td class="borde fondo">AÑO ACTUAL</td>
                                                    <td class="borde fondo">% LOGRO</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo negritas">X</td>
                                                    <td class="borde redNumbers">4</td>
                                                    <td class="borde"><input type="number" id="orientacionAlLogro" name="oreintacionAlLogro" class="form-control redNumbers limpia" autocomplete="off"></td>
                                                    <td class="borde blackNumbers limpiaText" id="total3">0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" class="borde text-left fondo" >FUNDAMENTACIÓN</td>                                                    
                                                    <td colspan="3" class="borde"><textarea name="fundamentacion4" class="form-control" id="fundamentacion4" cols="20" rows="3" autocomplete="off"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo"><strong>2.5 COMPETENCIA 4</strong> </td>
                                                    <td class="borde fondo">DESARROLLO PROPUESTO</td>
                                                    <td class="borde fondo">AÑO ACTUAL</td>
                                                    <td class="borde fondo">% LOGRO</td>
                                                </tr>
                                                <tr>
                                                    <td class="borde text-left fondo negritas">X</td>
                                                    <td class="borde redNumbers">4</td>
                                                    <td class="borde"><input type="number" id="negociacion" name="negociacion" class="form-control redNumbers limpia" autocomplete="off"></td>
                                                    <td class="borde blackNumbers limpiaText" id="total4">0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="1" class="borde text-left fondo" >FUNDAMENTACIÓN</td>                                                    
                                                    <td colspan="3" class="borde"><textarea name="fundamentacion5" class="form-control" id="fundamentacion5" cols="20" rows="3" autocomplete="off"></textarea></td>
                                                </tr>         

                                            </tbody>
                                        </table>                                                                                
                                        
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <td colspan="2" style="border-bottom:1px solid black"><strong>3.- FEEDBACK PARA EL DESARROLLO.</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody >
                                            <tr >
                                                <td colspan="2" class="borde text-center fondo">OBSERVACIONES GENERALES PARA LA MEJORA</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="borde"><textarea name="obsmejora" class="form-control" id="obsmejora" cols="30" rows="3"></textarea></td>
                                            </tr> 
                                            <tr>
                                                <td class="borde fondo">EVIDENCIAS PARA PREPARAR LA MEJORA (HECHOS ACONTECIDOS EN EL AÑO)</td>
                                                <td class="borde" width="70%"> <textarea name="hecho" class="form-control" id="hecho" cols="30" rows="3"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="borde fondo">OBJETIVOS PARA LA MEJORA CONTINUA (OBJETIVOS SMART)</td>
                                                <td class="borde"><textarea name="objetivo" class="form-control" id="objetivo" cols="30" rows="3"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="borde fondo">PLAN PARA LA MEJORA CONTINUA</td>
                                                <td class="borde"><textarea name="mejora" class="form-control" id="mejora" cols="30" rows="3"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="borde fondo">KPI´s de Control (Indicadores de Gestión Personal)</td>
                                                <td class="borde"><textarea name="kpi" class="form-control" id="kpi" cols="30" rows="3"></textarea></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div> 
                                </div> <!--.row-->
                                <div class="row" style="margin:50px 0 50px 0;">
                                    <div class="container">
                                        <div class="col-md-4 col-md-offset-2 text-left"><hr style="height:1px;border:none;color:#333;background-color:#333;">
                                         <p class="text-center">Margot Andrea Moya Olivares</p>
                                         <p class="text-center">Colaboradora de Operaciones</p>
                                       </div>
                                        <div class="col-md-4 text-right"><hr style="height:1px;border:none;color:#333;background-color:#333;">
                                        <p class="text-center">Sara Noemí Araya Bugueño</p>
                                         <p class="text-center">Jefa de operaciones.</p>
                                      </div>
                                    </div>        
                                </div>  

                                <div class="row" style="padding-bottom:3%;">
                                    <div class="container">
                                        <div class="col-md-12 text-center"><small>DOCUMENÉNTESE ELECTRÓNICAMENTE Y EN CARPETA FÍSICA DEL/LA TRABAJADOR/A</small></div>
                                        <div class="col-md-12 text-center">
                                            <input type="button" class="btn btn-primary" id="pdf" value="Pasar a pdf" />
                                            <!-- <a href="javascript:window.print()" class="btn btn-primary btn-md">IMPRIMIR DOCUMENTO</a> -->
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
      
    const metas = () => {
        $.ajax({
            type: 'POST',
            data : 'vendedor=34',
            url: 'getEvaluacion.php',
            dataType: 'json',
            success:function(resp){
                if(resp.state){
                    $("#orientacionAlCLiente").val(resp.data[0].rpregunta1);
                    let total1 = (resp.data[0].rpregunta1 * 100)/4;
                    $("#total1").text(total1);
                    $("#habilidades").val(resp.data[0].rpregunta2);
                    let total2 = (resp.data[0].rpregunta2 * 100)/4;
                    $("#total2").text(total2);
                    $("#orientacionAlLogro").val(resp.data[0].rpregunta3);
                    let total3 = (resp.data[0].rpregunta3 * 100)/4;
                    $("#total3").text(total3);
                    $("#negociacion").val(resp.data[0].rpregunta4);
                    let total4 = (resp.data[0].rpregunta4 * 100)/4;
                    $("#total4").text(total4);                    
                }
                
            }
        })
        return false;
    }
    metas()
    
    

$("#pdf").on('click',function(e){    

    let totalCom = ( parseInt($("#total1").text()) + parseInt($("#total2").text()) + parseInt($("#total3").text()) + parseInt($("#total4").text()) ) / 4;    
    let totalmetas = parseInt(v_("porcentajeTotal","val"));
    let total_ = Math.round((parseInt(totalCom) * 0.3) + (parseInt(totalmetas) * 0.7));
    let desarrollo = "";
   
    if(total_ <= 69){
        desarrollo = "INSUFICIENTE";
    }else if(total_ >= 70 && total_ <= 99){
        desarrollo = "EN DESARROLLO";
    }else if(total_ >= 100){
        desarrollo = "SOBRESALIENTE";
    }     
    let ciclo = $("#year").val();
    let fecha_eva = $("#fecha_eva").val();

    let pdfData = {  
        ciclo_evaluacion : ciclo,
        fecha_evaluacion : fecha_eva,      
        desarrollo : desarrollo,
        fundamentacion1 : v_("fundamentacion1","val"),
        fundamentacion2 : v_("fundamentacion2","val"),
        fundamentacion3 : v_("fundamentacion3","val"),
        fundamentacion4 : v_("fundamentacion4","val"),
        fundamentacion5 : v_("fundamentacion5","val"),        
        obsmejora : v_("obsmejora","val"),
        hecho : v_("hecho","val"),
        objetivo : v_("objetivo","val"),
        kpi : v_("kpi","val"),
        mejora : v_("mejora","val")
    }    
    // $.ajax({        
    //     url : "insert_informe_operaciones.php",        
    //     type : "POST",
    //     data : pdfData,
    //     dataType : 'json',
    //     success:function(result){
    //         if(result.title == "data"){
    //             let metas = {
    //                 totalAsignado : v_("totalAsignado","val"),
    //                 totalAnterior : v_("totalAnterior","val"),
    //                 totalLogrado : v_("totalLogrado","val"),
    //                 porcentajeTotal : v_("porcentajeTotal","val")
    //             };
    //             location.href='<?php echo _MODULO?>evaluacion/evaluacion_operacionesPdf.php?id='+result.message+'&metas='+JSON.stringify(metas);                
    //         }else{
    //             swal(result.title, result.message, result.icon);
    //         }
                
    //     }
    // });
    e.preventDefault();
});

const v_ = (campo,val) =>{
    let valor ="";
    if(val == "val"){
        valor = $("#"+campo).val()
    }else if(val=="text"){
        valor = $("#"+campo).text()
    }else if(val == "select"){        
        valor = $('select[name="'+campo+'"] option:selected').text();

    }    
    return valor;
}
})
</script>
</body>
</html>
