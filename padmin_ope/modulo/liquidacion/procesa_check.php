<?php 
session_start();
$jsondata['envio'] = 1;
echo json_encode($jsondata);
exit();
?> 
