<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$datos = array_values($_POST);

//echo $datos[0]; echo "<br>";
//echo $datos[1]; echo "<br>";

if($datos[0] == "nroafil") {
	$errorbusqueda = 1;
	$ordenbusqueda = "nroafiliado";
}

if($datos[0] == "nrodocu") {
	$errorbusqueda = 2;
	$ordenbusqueda = "nrodocumento";
}

if($datos[0] == "nrocuil") {
	$errorbusqueda = 3;
	$ordenbusqueda = "cuil";
}

$valorbusqueda = $datos[1];

//echo $ordenbusqueda; echo "<br>";
//echo $valorbusqueda; echo "<br>";

$sqltituacti = "select * from titulares where $ordenbusqueda = $valorbusqueda";
$restituacti = mysql_query($sqltituacti,$db);
$cantituacti = mysql_num_rows($restituacti);
if ($cantituacti != 1) {
	$sqlfamiacti = "select * from familiares where $ordenbusqueda = $valorbusqueda";
	$resfamiacti = mysql_query($sqlfamiacti,$db);
	$canfamiacti = mysql_num_rows($resfamiacti);
	if ($canfamiacti != 1) {
		$sqltitubaja = "select * from titularesdebaja where $ordenbusqueda = $valorbusqueda";
		$restitubaja = mysql_query($sqltitubaja,$db);
		$cantitubaja = mysql_num_rows($restitubaja);
		if ($cantitubaja != 1) {
			$sqlfamibaja = "select * from familiaresdebaja where $ordenbusqueda = $valorbusqueda";
			$resfamibaja = mysql_query($sqlfamibaja,$db);
			$canfamibaja = mysql_num_rows($resfamibaja);
			if ($canfamibaja != 1) {
				header ("Location: moduloABM.php?err=$errorbusqueda");
			}
			else {
				$leeafiliado = 2;
				$rowfamibaja = mysql_fetch_array($resfamibaja);
				$nroafiliado = $rowfamibaja['nroafiliado'];
			}
		}
		else {
			$leeafiliado = 2;
			$rowtitubaja = mysql_fetch_array($restitubaja);
			$nroafiliado = $rowtitubaja['nroafiliado'];
		}
	}
	else {
		$leeafiliado = 1;
		$rowfamiacti = mysql_fetch_array($resfamiacti);
		$nroafiliado = $rowfamiacti['nroafiliado'];
	}
}
else {
	$leeafiliado = 1;
	$rowtituacti = mysql_fetch_array($restituacti);
	$nroafiliado = $rowtituacti['nroafiliado'];
}

//echo $sqltituacti; echo "<br>";
//echo $sqlfamiacti; echo "<br>";
//echo $sqltitubaja; echo "<br>";
//echo $sqlfamibaja; echo "<br>";

if($leeafiliado == 1) {
//	$sqlleeafili = "select * from titulares where nroafiliado = $nroafiliado";
	header ("Location: afiliado.php?nroAfi=$nroafiliado&estAfi=1");
}

if($leeafiliado == 2) {
//	$sqlleeafili = "select * from titularesdebaja where nroafiliado = $nroafiliado";
	header ("Location: afiliado.php?nroAfi=$nroafiliado&estAfi=0");
}

//echo $sqlleeafili; echo "<br>";

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: ABM Afiliados :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
