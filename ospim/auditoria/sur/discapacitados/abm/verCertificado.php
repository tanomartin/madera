<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$sqlCertificado = "SELECT * FROM discapacitados WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
$resCertificado = mysql_query($sqlCertificado,$db);
$rowCertificado = mysql_fetch_assoc($resCertificado);
$tipoheader = "image/pjpeg";
header("Content-type: $tipoheader");
$imagen = $rowCertificado['documentocertificado'];
echo $imagen;
?>
