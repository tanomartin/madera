<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nrosolicitud=$_GET['nroSolicitud'];
$archivo=$_GET['archivo'];

$sqlLeeSolicitud="SELECT * FROM autorizacionesdocoriginales where nrosolicitud = $nrosolicitud";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

if($archivo <= 8) {
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
} else {
	$tipo = "application/pdf";
	$contenidoarchivo = $rowLeeSolicitud['consultasssverificacion'];
}
Header("Content-type: $tipo");
echo $contenidoarchivo; ?>