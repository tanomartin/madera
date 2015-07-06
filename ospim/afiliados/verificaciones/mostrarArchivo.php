<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nrosolicitud=$_GET['nroSolicitud'];
$archivo=$_GET['archivo'];

$sqlLeeSolicitud="SELECT * FROM autorizaciones where nrosolicitud = $nrosolicitud";
//echo $sqlLeeSolicitud;
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);
//echo $archivo;

if($archivo <= 8)
{
	$tipo = "application/pdf";

	switch ($archivo){
	    case 1:
			$contenidoarchivo = $rowLeeSolicitud['pedidomedico'];
    		break;
	    case 2:
			$contenidoarchivo = $rowLeeSolicitud['resumenhc'];
    		break;
	    case 3:
			$contenidoarchivo = $rowLeeSolicitud['avalsolicitud'];
    		break;
	    case 4:
			$contenidoarchivo = $rowLeeSolicitud['presupuesto1'];
    		break;
	    case 5:
			$contenidoarchivo = $rowLeeSolicitud['presupuesto2'];
    		break;
	    case 6:
			$contenidoarchivo = $rowLeeSolicitud['presupuesto3'];
    		break;
	    case 7:
			$contenidoarchivo = $rowLeeSolicitud['presupuesto4'];
    		break;
	    case 8:
			$contenidoarchivo = $rowLeeSolicitud['presupuesto5'];
    		break;
	}
}
else {
	$tipo = "application/pdf";
	$contenidoarchivo = $rowLeeSolicitud['consultasssverificacion'];
}

//echo $tipo;
//Header("Location: $pagina");
Header("Content-type: $tipo");
//header("Content-type: $tipo");
echo $contenidoarchivo; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documentos</title>
<style type="text/css">
<!--
.Estilo3 {
	font-family: Papyrus;
	font-weight: bold;
	color: #999999;
	font-size: 24px;
}
body {
	background-color: #CCCCCC;
}
.Estilo4 {
	color: #990000;
	font-weight: bold;
}
-->
</style>
</head>
<body bgcolor="#CCCCCC">
</body>
</html>