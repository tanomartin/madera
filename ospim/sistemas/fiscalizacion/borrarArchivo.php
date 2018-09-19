<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$archivoUsimra = "SegUSIMRA.txt";
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0) {
	$direArcUsimra = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/fiscalizacion/liqui/".$archivoUsimra;
} else {
	$direArcUsimra = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$archivoUsimra;
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
<body bgcolor="#CCCCCC">
	<div align="center">
	<?php if ($borrado == 1) { ?>
			<p style='color:#0033FF'><b> SE BORRO EL ARCHIVO SegUSIMRA.txt </b></p>
	<?php } else { ?>
			<p style='color:#000000'><b> NO SE ENCONTRÓ EL ARCHIVO SegUSIMRA.txt </b></p>
	<?php } ?>
	</div>
</body>
</html>