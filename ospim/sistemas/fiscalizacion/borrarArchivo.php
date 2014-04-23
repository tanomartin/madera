<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$archivoUsimra = "SegUsimra.txt";
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0) {
	$direArcUsimra = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/fiscalizacion/liqui/".$archivoUsimra;
} else {
	$direArcUsimra = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/PruebasLiq/".$archivoUsimra;
}

$borrado = 0;
if (file_exists($direArcUsimra)) {
	unlink($direArcUsimra);
	$borrado = 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#CCCCCC">
<div align="center">
  <?php 
  	if ($borrado == 1) {
		print("<p><div align='center' style='color:#0033FF'><b> SE BORRO EL ARCHIVO SegUsimra.txt </b></div></p>");
	} else {
		print("<p><div align='center' style='color:#000000'><b> NO SE ENCONTRÓ EL ARCHIVO SegUsimra.txt </b></div></p>");
	}
  
  ?>
</div>
</body>
</html>