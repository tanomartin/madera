<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$maquina = $_SERVER['SERVER_NAME'];
if(isset($_GET['rutaarchivo'])) {
	$archivo=$_GET['rutaarchivo'];
	$tamanio = filesize($archivo);
	header("Content-type: application/pdf");
	header("Content-Length: $tamanio");
	header("Content-Disposition: inline; filename=$archivo");
	$respuesta=readfile($archivo);
	echo $respuesta;
}
?>