<?php 
require "../../config.php"; 
include _INCLUDE."class/conexion.php";
$conexion = new conexion();
$id = (isset($_GET['id']))?$_GET['id']:0;
$metas = (isset($_GET['metas']))?json_decode($_GET['metas']):0;
$desarrollo = "";
$informe = conexion::select("SELECT * FROM matriz_informe WHERE id =".$id);
$criterios = conexion::select("SELECT * FROM matriz_desarrollo WHERE id_vendedor =34 ORDER BY id desc LIMIT 1"); 
$anotacion = conexion::select("SELECT anotacion FROM matriz_carta WHERE trabajador_id=34 AND estado = 1 ORDER BY id desc LIMIT 1");
// $total_criterios = conexion::select("SELECT (((`rpregunta1` * 100) DIV 4) + ((`rpregunta2` * 100) DIV 4) + ((`rpregunta3` * 100) DIV 4) + ((`rpregunta4` * 100) DIV 4)) DIV 4 as total FROM `matriz_desarrollo` WHERE `id_vendedor`=34");
$nombreVendedor = "Margot Andrea Moya Olivares";
// totales criterio
$total1 = ($criterios[0]['rpregunta1'] * 100) / 4;
$total2 = ($criterios[0]['rpregunta2'] * 100) / 4;
$total3 = ($criterios[0]['rpregunta3'] * 100) / 4;
$total4 = ($criterios[0]['rpregunta4'] * 100) / 4;
$resultadoCompetencia = ($total1 + $total2 + $total3 + $total4) / 4;
$totalCompetencia = round($resultadoCompetencia * 0.3);
$resultadoMeta = $metas->porcentajeTotal;
$totalMeta = round($metas->porcentajeTotal * 0.7);
$total = round($totalCompetencia + $totalMeta);
$merito = "NO";  
$demerito = "NO"; 
if(isset($anotacion[0])){
    if($anotacion[0]['anotacion'] == "Merito" ){
        $merito = "SI";
        $total += 20;
    }else if($anotacion[0]['anotacion'] == "Demerito"){
        $demerito = "SI";
        $total -= 20;
    }
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
                                            <div class="col-md-3 text-left" style="width:100px; ">
                                                <img src="'._ASSETS.'img/logo-icp.jpg" alt="">
                                            </div> 
                                            <div class="text-center" style="width:300px; margin-left:170px; margin-top: -90px;">
                                                <h3>ANEXO 02: INFORME DE DESEMPEÑO</h3>
                                            </div> 
                                            <div class="col-md-3 text-center" style="width:200px;  margin-left:500px; margin-top:-105px; margin-bottom:50px;" >
                                                <h3><small>PR-A- 02- INFORME <br>DESEMPEÑO / VERSIÓN <br>07/2022</small></h3>
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
                                                  
                        </div>                        
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2" style="margin-top:3%;">                                      
                                    <table class="table"  style="margin-bottom:70px;">
                                        <thead>
                                            <tr>
                                                <td colspan="5" class="titulos">1. IDENTIFICACIÓN PERSONA EVALUADA.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="borde" style="background:#FDEBDF;">NOMBRE PERSONA EVALUADA</td>
                                                <td colspan="4" class="borde"> '.$nombreVendedor.' </td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background:#FDEBDF;">CARGO / FUNCIÓN</td>
                                                <td colspan="4" class="borde"> Colaboradora de Operaciones. </tr>
                                            <tr>
                                                <td class="borde" style="background:#FDEBDF;">NOMBRE EVALUADOR/A</td>
                                                <td colspan="4" class="borde"> Sara Noemí Araya Bugueño. </td>
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background:#FDEBDF;">CARGO / FUNCIÓN</td>
                                                <td colspan="4" class="borde">'.$cargo.'</td>                                                   
                                            </tr>
                                            <tr>
                                                <td class="borde" style="background:#FDEBDF;"> OBSERVACIONES DE DESEMPEÑO:</td>
                                                <td class="borde" style="background:#FDEBDF;">APLICA AUSENTISMO HASTA POR 60 DÍAS HÁBILES</td>
                                                <td class="borde text-center" width="12%">'.$informe[0]['ausentismo'].'</td>
                                                <td class="borde" style="background:#FDEBDF;">APLICA CRITERIO DE COMPENSACIÓN POR 15 DÍAS DE AUSENTISMO CONTINUO</td>
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
                                                <td class="borde" style="background:#FDEBDF;">2.1 META ASIGNADA</td>
                                                <td class="borde" style="background:#FDEBDF;"> AÑO ANTERIOR</td>
                                                <td class="borde" style="background:#FDEBDF;"> AÑO ACTUAL</td>
                                                <td class="borde" style="background:#FDEBDF;">% LOGRO</td>
                                            </tr>                                         
                                            <tr>
                                                <td class="borde blackNumbers2">'.$metas->totalAsignado.' </td>
                                                <td class="borde blackNumbers2">'.$metas->totalAnterior.'</td>
                                                <td class="borde blackNumbers2 ">'.$metas->totalLogrado.'</td>
                                                <td class="borde blackNumbers2">'.$metas->porcentajeTotal.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background:#FDEBDF;">FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda1'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;"><strong>2.2 COMPETENCIA 1</strong> </td>
                                                <td class="borde" style="background:#FDEBDF;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background:#FDEBDF;">AÑO ACTUAL</td>
                                                <td class="borde" style="background:#FDEBDF;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;">ORIENTACIÓN AL CLIENTE</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta1'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total1">'.$total1.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background:#FDEBDF;">FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda2'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;"><strong>2.3 COMPETENCIA 2</strong></td>
                                                <td class="borde" style="background:#FDEBDF;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background:#FDEBDF;">AÑO ACTUAL</td>
                                                <td class="borde" style="background:#FDEBDF;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;">HABILIDADES INTERPERSONALES</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta2'].'</td>
                                                <td class="borde blackNumbers" id="total2">'.$total2.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background:#FDEBDF;">FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda3'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;"><strong>2.4 COMPETENCIA 3</strong></td>
                                                <td class="borde" style="background:#FDEBDF;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background:#FDEBDF;">AÑO ACTUAL</td>
                                                <td class="borde" style="background:#FDEBDF;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;">ORIENTACIÓN AL LOGRO</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta3'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total3">'.$total3.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background:#FDEBDF;">FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda4'].'</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left " style="background:#FDEBDF;"><strong>2.5 COMPETENCIA 4</strong> </td>
                                                <td class="borde" style="background:#FDEBDF;">DESARROLLO PROPUESTO</td>
                                                <td class="borde" style="background:#FDEBDF;">AÑO ACTUAL</td>
                                                <td class="borde" style="background:#FDEBDF;">% LOGRO</td>
                                            </tr>
                                            <tr>
                                                <td class="borde text-left" style="background:#FDEBDF;">NEGOCIACIÓN</td>
                                                <td class="borde redNumbers">4</td>
                                                <td class="borde redNumbers">'.$criterios[0]['rpregunta4'].'</td>
                                                <td class="borde blackNumbers limpiaText" id="total4">'.$total4.'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="1" class="borde text-left" style="background:#FDEBDF;" >FUNDAMENTACIÓN</td>                                                    
                                                <td colspan="3" class="borde">'.$informe[0]['funda5'].'</td>
                                            </tr>         

                                        </tbody>
                                    </table>
                                    
                                    <table class="table table-bordered text-center " style="width:100%; margin-bottom:50px; margin-top:50px;">
                                        <tbody>
                                            <tr class="borde2">
                                                <td colspan="3" class="borde2" style="background:#FDEBDF;">RESULTADOS DE METAS</td>
                                                <td colspan="3" class="borde2" style="background:#FDEBDF;">RESULTADOS DE COMPETENCIAS</td>
                                                <td rowspan="2" class="borde2" style="vertical-align:middle; background:#FDEBDF;">TOTAL LOGRADO %</td>
                                            </tr>
                                       
                                        
                                            <tr>
                                                <td class="borde2" style="background:#FDEBDF;">RESULTADOS</td>
                                                <td class="borde2" style="background:#FDEBDF;">PONDERACION</td>
                                                <td class="borde2" style="background:#FDEBDF;">TOTAL</td>
                                                <td class="borde2" style="background:#FDEBDF;">RESULTADOS</td>
                                                <td class="borde2" style="background:#FDEBDF;">PONDERACION</td>
                                                <td class="borde2" style="background:#FDEBDF;">TOTAL</td>                                                                                                   
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
                                                <td colspan="3" class="borde2" style="background:#FDEBDF;">ANOTACIONES DEMÉRITO (20%)</td>
                                                <td class="borde2 text-center">'.$demerito.'</td>
                                                </td>
                                                <td colspan="2" class="borde2" style="background:#FDEBDF;">APLICA REDUCCIÓN DEL 20% AL TOTAL LOGRADO</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="borde2" style="background:#FDEBDF;">ANOTACIONES DE MÉRITO (20%)</td>
                                                <td class="borde2 text-center">'.$merito.'</td>
                                                </td>
                                                <td colspan="2" class="borde2" style="background-color:#FDEBDF !important;">APLICA AUMENTO DEL 20% AL TOTAL LOGRADO</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="borde2" style="background:#FDEBDF;"> NIVEL DE DESARROLLO ALCANZADO</td>
                                                <td colspan="4" class="borde2"> <b id="desarrollo" style="font-weight: bold;">'.$informe[0]['criterio'].'</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <table>
                                        <tbody>
                                        <tr>
                                                <td colspan="2" class="text-left titulos">3.- FEEDBACK PARA EL DESARROLLO.</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="borde text-center" style="background:#FDEBDF;">OBSERVACIONES GENERALES PARA LA MEJORA</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="borde" ><p>'.$informe[0]['feedback1'].'</p></td>
                                        </tr> 
                                        <tr>
                                            <td class="borde" style="background:#FDEBDF;"  width="45%">EVIDENCIAS PARA PREPARAR LA MEJORA (HECHOS ACONTECIDOS EN EL AÑO)</td>
                                            <td class="borde" width="70%" >'.$informe[0]['feedback2'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background:#FDEBDF;">OBJETIVOS PARA LA MEJORA CONTINUA (OBJETIVOS SMART)</td>
                                            <td class="borde">'.$informe[0]['feedback3'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background:#FDEBDF;">PLAN PARA LA MEJORA CONTINUA</td>
                                            <td class="borde" >'.$informe[0]['feedback4'].'</td>
                                        </tr>
                                        <tr>
                                            <td class="borde" style="background:#FDEBDF;">KPI´s de Control (Indicadores de Gestión Personal)</td>
                                            <td class="borde" >'.$informe[0]['feedback5'].'</td>
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
$url2 = _ASSETS."bootstrap/css/bootstrap.min.css";
$url1 = _ASSETS."dist/css/informePDF.css";

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

// $mpdf->charset_in='UTF-8';
// $mpdf->allow_charset_conversion=true;
// $url1 = _ASSETS."bootstrap/css/bootstrap.min.css";
// $url = _ASSETS."dist/css/informePDF.css";
// $stylesheet = file_get_contents($url);
// $stylesheet = file_get_contents($url);
// $mpdf->writeHTML($stylesheet,1);
// $mpdf->writeHTML($html,2);
// $mpdf->AddPage();
// $mpdf->WriteHTML($html);
$nombre = 'Informe_de_desempeño_operaciones_'.date('dmYHi').'.pdf';
ob_end_clean();//End Output Buffering
// $fecha = date('Y-m-d H:i:s');
$pdf = $mpdf->output($nombre ,'I');

?>
