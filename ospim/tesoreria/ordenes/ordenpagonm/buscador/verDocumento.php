<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$doc = $_GET['documento'];
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaDoc = "../OrdenesPagoPDF/";
else
	$carpetaDoc = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";

$mi_pdf = $carpetaDoc.$doc;
header('Content-type: application/pdf');
readfile($mi_pdf);
exit();  ?>