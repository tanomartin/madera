<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
$nroafiliado=$_GET['nroAfi'];
$estfamilia=$_GET['estFam'];
$ordafiliado=$_GET['nroOrd'];

if ($estfamilia == 1)
	$sqlFamilia = "SELECT foto FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";

if ($estfamilia == 0)
	$sqlFamilia = "SELECT foto FROM familiaresdebaja WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";

$resFamilia = mysql_query($sqlFamilia,$db);
$rowFamilia = mysql_fetch_array($resFamilia);


$tipofotoafi="image/pjpeg";
$contfotoafi=$rowFamilia['foto'];

header("Content-type: $tipofotoafi");
echo $contfotoafi; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Foto :.</title>
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