<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
//var_dump($_POST);
$ordenbusqueda = $_POST['seleccion'];
$valorbusqueda = $_POST['valor'];

if(strcmp($ordenbusqueda, "nroafiliado")==0) {
	$errorbusqueda = 1;
}

if(strcmp($ordenbusqueda, "nrodocumento")==0) {
	$errorbusqueda = 2;
}

if(strcmp($ordenbusqueda, "cuil")==0) {
	$errorbusqueda = 3;
}

//echo $ordenbusqueda; echo "<br>";
//echo $valorbusqueda; echo "<br>";
//echo $errorbusqueda; echo "<br>";

$sqltituacti = "SELECT * FROM titulares WHERE $ordenbusqueda = '$valorbusqueda'";
$restituacti = mysql_query($sqltituacti,$db);
if (mysql_num_rows($restituacti)==0) {
	$sqlfamiacti = "SELECT * FROM familiares WHERE $ordenbusqueda = '$valorbusqueda'";
	$resfamiacti = mysql_query($sqlfamiacti,$db);
	if (mysql_num_rows($resfamiacti)==0) {
		$sqltitubaja = "SELECT * FROM titularesdebaja WHERE $ordenbusqueda = '$valorbusqueda'";
		$restitubaja = mysql_query($sqltitubaja,$db);
		if (mysql_num_rows($restitubaja)==0) {
			$sqlfamibaja = "SELECT * FROM familiaresdebaja WHERE $ordenbusqueda = '$valorbusqueda'";
			$resfamibaja = mysql_query($sqlfamibaja,$db);
			if (mysql_num_rows($resfamibaja)==0) {
				$noexiste = 1;
			}
			else {
				$rowfamibaja = mysql_fetch_array($resfamibaja);
				$nroafiliado = $rowfamibaja['nroafiliado'];

				$sqlLeeAfiliado = "SELECT * FROM titulares WHERE nroafiliado = '$nroafiliado'";
				$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
				if (mysql_num_rows($resLeeAfiliado)==0)
					$estafiliado = 0;
				else
					$estafiliado = 1;
			}
		}
		else {
			$estafiliado = 0;
			$rowtitubaja = mysql_fetch_array($restitubaja);
			$nroafiliado = $rowtitubaja['nroafiliado'];
		}
	}
	else {
		$estafiliado = 1;
		$rowfamiacti = mysql_fetch_array($resfamiacti);
		$nroafiliado = $rowfamiacti['nroafiliado'];
	}
}
else {
	$estafiliado = 1;
	$rowtituacti = mysql_fetch_array($restituacti);
	$nroafiliado = $rowtituacti['nroafiliado'];
}

//echo $sqltituacti; echo "<br>";
//echo $sqlfamiacti; echo "<br>";
//echo $sqltitubaja; echo "<br>";
//echo $sqlfamibaja; echo "<br>";

if ($noexiste == 1)
	header ("Location: moduloABM.php?err=$errorbusqueda");
else
	header ("Location: afiliado.php?nroAfi=$nroafiliado&estAfi=$estafiliado");

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
<body bgcolor="#CCCCCC">
</body>
</html>
