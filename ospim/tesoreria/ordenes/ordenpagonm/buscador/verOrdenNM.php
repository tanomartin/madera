<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMPDF/";
$ordenNombreArchivo = str_pad($nroorden, 8, '0', STR_PAD_LEFT);
$nombreArchivo = "OP-NM".$ordenNombreArchivo.".pdf";
$archivo = 	$carpetaOrden.$nombreArchivo;
$tamanio = filesize($archivo);
header("Content-type: application/pdf");
header("Content-Length: $tamanio");
header("Content-Disposition: inline; filename=$archivo");
$respuesta=readfile($archivo);
echo $respuesta;
?>