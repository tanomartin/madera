<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
require($libPath."fpdf.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaPlanilla = "../../tesoreria/ordenes/ordenpago/OrdenesPagoPDF/";
else
	$carpetaPlanilla = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";


$idFactura = $_GET['id'];
$doc = $_GET['doc'];
$sqlOrden = "SELECT c.nroordenpago FROM ordendebito d, ordencabecera c, ordendetalle od
				WHERE od.idFactura = $idFactura AND od.nroordenpago = d.nroordenpago and
					  d.nroordenpago = c.nroordenpago AND 
					  c.fechacancelacion is null
				ORDER BY c.nroordenpago ASC LIMIT 1";
$resOrden = mysql_query($sqlOrden,$db);
$numOrden = mysql_num_rows($resOrden);
if ($numOrden != 0) {
	$rowOrden = mysql_fetch_assoc($resOrden);
	$nroorden = str_pad($rowOrden['nroordenpago'], 8, '0', STR_PAD_LEFT);
	$mi_pdf = $carpetaPlanilla."OP".$nroorden.$doc.".pdf";
	header('Content-type: application/pdf');
	readfile($mi_pdf);
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h2>NO SE ENCUENTRA EL DOCUMENTO DE DEBITO</h2>
	<h2 style="color: red">ORDEN DE PAGO CANCELADA PARA ESTA FACTURA</h2>
</div>
</body>
</html>
