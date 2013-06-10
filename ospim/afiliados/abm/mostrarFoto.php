<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];

if ($estafiliado == 1)
	$sqlTitular = "select * from titulares where nroafiliado = $nroafiliado";

if ($estafiliado == 0)
	$sqlTitular = "select * from titularesdebaja where nroafiliado = $nroafiliado";

$resTitular = mysql_query($sqlTitular,$db);
$rowTitular = mysql_fetch_array($resTitular);


$tipofotoafi="image/pjpeg";
$contfotoafi=$rowTitular['foto'];

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