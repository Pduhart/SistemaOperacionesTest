<?php 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = (isset($_GET['id']))?$_GET['id']:0;
$criterio = (isset($_GET['criterio']))?json_decode($_GET['criterio']):0;

$informe = conexion::select("SELECT * FROM matriz_informe WHERE id =".$id); 
$vendedor = conexion::select("SELECT * FROM vendedor_vendedor WHERE id_vend =".$informe[0]['id_vendedor']); 
$criterios = conexion::select("SELECT * FROM matriz_desarrollo WHERE id =".$informe[0]['id_desarrollo']); 
$nombreVendedor = utf8_encode($vendedor[0]['nombre_vend']).' '.utf8_encode($vendedor[0]['apellido_paterno_vend']).' '.utf8_encode($vendedor[0]['apellido_materno_vend']);
// totales criterio
$total1 = ($criterios[0]['rpregunta1'] * 100) / 4;
$total2 = ($criterios[0]['rpregunta2'] * 100) / 4;
$total3 = ($criterios[0]['rpregunta3'] * 100) / 4;
$total4 = ($criterios[0]['rpregunta4'] * 100) / 4;
$resultadoCompetencia = ($total1 + $total2 + $total3 + $total4) / 4;
$totalCompetencia = round($resultadoCompetencia * 0.3);
$resultadoMeta = $criterio->totalLogro;
$totalMeta = round($criterio->totalLogro * 0.7);
$total = round($totalCompetencia + $totalMeta);
if($informe[0]['merito'] == "SI"){
    $total += 20;
}else if($informe[0]['demerito'] == "SI"){
    $total -= 20;
}
// 
$cargo = '';
if($informe[0]['id_cargo']==1)  {
    $cargo = "Gerente de ventas y operaciones";
}else{
    $cargo = "Jefa de operaciones";
}
// echo '<pre>';
// var_dump($criterio);
// echo '</pre>';
ob_start();//Enables Output Buffering
session_start(); 
date_default_timezone_set('Chile/Continental');
include 'mpdf/mpdf.php';

$year = date('Y');
// var_dump($_POST);



$html = '';

$html .='
<!DOCTYPE html>
<html>
<head>   
    <meta charset="utf-8">    
</head>
<body>
<div class="wrapper">    
    <div class="content-wrapper">                      
        <section class="content">
            <div class="row">                
                <div class="col-sm-12">                  
                    <div class="box box-primary">                                                
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2" style=margin-top:3%;>  
                                    
                                            <div class="col-md-3 text-left" style="width:100px; ">
                                                <img src="'._ASSETS.'img/logo-icp.jpg" alt="">
                                            </div> 
                                            <div class="text-center" style="width:300px; margin-left:170px; margin-top: -90px;">
                                                <h3>ANEXO 02: INFORME DE DESEMPEÑO</h3>
                                            </div> 
                                            <div class="col-md-3 text-center" style="width:200px;  margin-left:500px; margin-top:-105px; margin-bottom:50px;" >
                                                <h3><small>PR-AX- 02- INFORME <br>DESEMPEÑO / VERSIÓN <br>01/2021</small></h3>
                                            </div> 

                                   <table class="table table-bordered">                                                
                                    <tbody>
                                        <tr>
                                            <td width="30%" class="fondo text-center borde" style="background:#FDEBDF;"><b>CICLO DE EVALUACIÓN : </b></td>
                                            <td class="text-center">'.$informe[0]['ciclo_evaluacion'].'</td>
                                            <td width="30%" class="fondo text-center" style="background:#FDEBDF;"><b>FECHA EVALUACIÓN : </b></td>
                                            <td class="text-center">'.$informe[0]['fecha_evaluacion'].'</td>
                                        </tr>
                                    </tbody>
                                    </table>                                   
                                    <table class="table"  style="margin-bottom:70px;">
                                        <thead>
                                            <tr>
                                                <td colspan="5" class="titulos">1. IDENTIFICACIÓN PERSONA EVALUADA.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">NOMBRE PERSONA EVALUADA</td>
                                                <td colspan="4" class="borde">'.$nombreVendedor.'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">CARGO / FUNCIÓN</td>
                                                <td colspan="4" class="borde">'.$informe[0]['cargo_persona'].'</tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >NOMBRE EVALUADOR/A</td>
                                                <td colspan="4" class="borde">'.$informe[0]['encargado'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >CARGO / FUNCIÓN</td>
                                                <td colspan="4" class="borde">'.$cargo.'</td>                                                   
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;"> OBSERVACIONES DE DESEMPEÑO:</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">APLICA AUSENTISMO HASTA POR 60 DÍAS HÁBILES</td>
                                                <td class="borde text-center" width="12%">'.$informe[0]['ausentismo'].'</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">APLICA CRITERIO DE COMPENSACIÓN POR 15 DÍAS DE AUSENTISMO CONTINUO</td>
                                                <td class="borde text-center" width="12%">'.$informe[0]['compensacion'].'</td>
                                            </tr>
                                        </tbody>
                                    </table>  
                                    
                                    <table class="table"  style="margin-bottom:50px; text-align:center;">
                                        <thead>
                                            <tr>
                                                <td colspan="4" class="titulos text-left">2.- CRITERIOS DE EVALUACIÓN.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">2.1 META ASIGNADA</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">META ASIGNADA</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">META LOGRADA</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 1 ENERO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[0].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[0].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[0].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 2 FEBRERO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[1].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[1].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[1].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 3 MARZO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[2].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[2].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[2].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 4 ABRIL</td>
                                                <td class="borde redNumbers">'.$criterio->asign[3].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[3].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[3].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 5 MAYO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[4].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[4].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[4].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 6 JUNIO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[5].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[5].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[5].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 7 JULIO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[6].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[6].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[6].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 8 AGOSTO</td>
                                                <td class="borde redNumbers">'.$criterio->asign[7].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[7].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[7].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 9 SEPTIEMBRE</td>
                                                <td class="borde redNumbers">'.$criterio->asign[8].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[8].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[8].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 10 OCTUBRE</td>
                                                <td class="borde redNumbers">'.$criterio->asign[9].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[9].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[9].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 11 NOVIEMBRE</td>
                                                <td class="borde redNumbers">'.$criterio->asign[10].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[10].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[10].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">MES 12 DICIEMBRE</td>
                                                <td class="borde redNumbers">'.$criterio->asign[11].'</td>
                                                <td class="borde redNumbers">'.$criterio->lograda[11].'</td>
                                                <td class="borde blackNumbers2">'.$criterio->logro[11].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background-color:#FDEBDF !important;">TOTALES</td>
                                                <td class="borde blackNumbers2">'.$criterio->totalAsign.'</td>
                                                <td class="borde blackNumbers2 ">'.$criterio->totalLograda.'</td>
                                                <td class="borde blackNumbers2">'.$criterio->totalLogro.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background-color:#FDEBDF !important;" >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda1'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;" ><strong>2.2 COMPETENCIA 1</strong> </td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >AÑO ACTUAL</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;" >ORIENTACIÓN AL CLIENTE</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta1'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total1">'.$total1.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background-color:#FDEBDF !important;"  >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda2'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;" ><strong>2.3 COMPETENCIA 2</strong></td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >AÑO ACTUAL</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;" >% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;" >HABILIDADES INTERPERSONALES</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta2'].'</td>
                                                <td class="borde blackNumbers" id="total2">'.$total2.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background-color:#FDEBDF !important;"  >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda3'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;"><strong>2.4 COMPETENCIA 3</strong></td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">AÑO ACTUAL</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;">ORIENTACIÓN AL LOGRO</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta3'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total3">'.$total3.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background-color:#FDEBDF !important;" >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda4'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;"><strong>2.5 COMPETENCIA 4</strong> </td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">AÑO ACTUAL</td>
                                                <td class="borde" style="background-color:#FDEBDF !important;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background-color:#FDEBDF !important;">NEGOCIACIÓN</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta4'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total4">'.$total4.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background-color:#FDEBDF !important;" >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda5'].'</td>
                                            </tr>         

                                        </tbody>
                                    </table>
                                    
                                    <table class="table table-bordered text-center " style="width:100%; margin-bottom:50px;">
                                        <tbody>
                                            <tr class="borde2">
                                                <td colspan="3" class="borde2" style="background-color:#FDEBDF !important;">RESULTADOS DE METAS</td>
                                                <td colspan="3" class="borde2" style="background-color:#FDEBDF !important;">RESULTADOS DE COMPETENCIAS</td>
                                                <td rowspan="2" class="borde2" style="background-color:#FDEBDF !important; vertical-align:middle">TOTAL LOGRADO %</td>
                                            </tr>
                                       
                                        
                                            <tr>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">RESULTADOS</td>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">PONDERACION</td>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">TOTAL</td>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">RESULTADOS</td>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">PONDERACION</td>
                                                <td class="borde2" style="background-color:#FDEBDF !important;">TOTAL</td>                                                                                                   
                                            </tr>
                                            <tr>
                                                <td class="borde2" id="resultadoMeta">'.$resultadoMeta.'</td>
                                                <td class="borde2">0,7</td>
                                                <td class="borde2 blackNumbers2" id="totalMeta">'.$totalMeta.'</td>
                                                <td class="borde2" id="resultadoCompetencia">'.$resultadoCompetencia.'</td>
                                                <td class="borde2">0,3</td>
                                                <td class="borde2 blackNumbers2" id="totalCompetencia">'.$totalCompetencia.'</td>
                                                <td class="borde2 blackNumbers" rowspan="3" style="font-size:3em;" id="total">'.$total.'</td>                                               
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="borde2" style="background-color:#FDEBDF !important;">ANOTACIONES DEMÉRITO (20%)</td>
                                                <td class="borde2 text-center">'.$informe[0]['demerito'].'</td>
                                                </td>
                                                <td colspan="2" class="borde2" style="background-color:#FDEBDF !important;" >APLICA REDUCCIÓN DEL 20% AL TOTAL LOGRADO</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="borde2" style="background-color:#FDEBDF !important;">ANOTACIONES DE MÉRITO (20%)</td>
                                                <td class="borde2 text-center">'.$informe[0]['merito'].'</td>
                                                </td>
                                                <td colspan="2" class="borde2" style="background-color:#FDEBDF !important;">APLICA AUMENTO DEL 20% AL TOTAL LOGRADO</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="borde2" style="background-color:#FDEBDF !important;"> NIVEL DE DESARROLLO ALCANZADO</td>
                                                <td colspan="4" class="borde2"> <b id="desarrollo" style="font-weight: bold;">'.$informe[0]['criterio'].'</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td colspan="2" class="titulos text-left">3.- FEEDBACK PARA EL DESARROLLO.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" class="borde text-center" style="background-color:#FDEBDF !important;">OBSERVACIONES GENERALES PARA LA MEJORA</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="borde">'.$informe[0]['feedback1'].'</td>
                                        </tr> 
                                        <tr>
                                            <td class="borde" style="background-color:#FDEBDF !important;">EVIDENCIAS PARA PREPARAR LA MEJORA (HECHOS ACONTECIDOS EN EL AÑO)</td>
                                            <td class="borde" width="70%">'.$informe[0]['feedback2'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background-color:#FDEBDF !important;">OBJETIVOS PARA LA MEJORA CONTINUA (OBJETIVOS SMART)</td>
                                            <td class="borde">'.$informe[0]['feedback3'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background-color:#FDEBDF !important;">PLAN PARA LA MEJORA CONTINUA</td>
                                            <td class="borde">'.$informe[0]['feedback4'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background-color:#FDEBDF !important;">KPI´s de Control (Indicadores de Gestión Personal)</td>
                                            <td class="borde">'.$informe[0]['feedback5'].'</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div> 
                            </div> 
                            <div class="row" style="margin:80px 0 50px 0;">
                                
                                        <div style="width:280px; float:left"><hr style="height:1px;border:none;color:#333;background-color:#333;">
                                            <p class="text-center" id="firmaEvaluado">'.$nombreVendedor.'</p>
                                            <p class="text-center">'.$informe[0]['cargo_persona'].'</p>
                                        </div>
                             
                                        <div style="width:280px; float:left; margin-left:80px"><hr style="height:1px;border:none;color:#333;background-color:#333;">
                                            <p class="text-center" id="firmaEvaluador">'.$informe[0]['encargado'].'</p>
                                            <p class="text-center" id="evaluadorCargo">'.$cargo.'</p>
                                        </div>
                                      
                            </div>                       
                        </div>
                    </div>                      
                </div>                    
            </div>
        </section>            
    </div>
</body>
</html>';

$mpdf = new mPDF('c','A4'); 
$url1 = _ASSETS."bootstrap/css/bootstrap.min.css";
$url2 = _ASSETS."dist/css/informePDF.css";

$bootstrap = curl_init($url1);
curl_setopt($bootstrap, CURLOPT_RETURNTRANSFER, true);
$stylesheet = curl_exec($bootstrap);
curl_close($bootstrap); 

$customcss = curl_init($url2);
curl_setopt($customcss, CURLOPT_RETURNTRANSFER, true);
$stylesheet .= curl_exec($customcss);
curl_close($customcss); 
// $stylesheet = file_get_contents($url);
$mpdf->writeHTML($stylesheet,1);
$mpdf->writeHTML($html,2);
// $mpdf->AddPage();
// $mpdf->WriteHTML($html2);
$nombre = 'evaluacion/evaluaciones/eva_'.date('dmYHi').'.pdf';
ob_end_clean();//End Output Buffering
// $fecha = date('Y-m-d H:i:s');
$pdf = $mpdf->output($nombre ,'I');

?>
