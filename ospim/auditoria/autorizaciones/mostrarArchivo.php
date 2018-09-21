<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nrosolicitud=$_GET['nroSolicitud'];
$archivo=$_GET['archivo'];

$sqlLeeSolicitud="SELECT * FROM autorizacionesdocoriginales WHERE nrosolicitud = $nrosolicitud";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

if($archivo == 10) {
	$sqlLeeDocumento = "SELECT * FROM autorizaciondocumento WHERE nrosolicitud = $nrosolicitud";
	$resultLeeDocumento = mysql_query($sqlLeeDocumento,$db); 
	$rowLeeDocumento = mysql_fetch_array($resultLeeDocumento);
}

$tipo = "application/pdf";

switch ($archivo) {
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
    case 9:
		$contenidoarchivo = $rowLeeSolicitud['consultasssverificacion'];
   		break;
    case 10:
		$contenidoarchivo = $rowLeeDocumento['documentofinal'];
   		break;
}

Header("Content-type: $tipo");
echo $contenidoarchivo;  ?>