<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Juicios :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuLegales.php'" /> </p>
  	<h3>Informes Juicios</h3>
  	<table width="200" border="2" style="text-align: center">
    	<tr>
     		<td>
     			<p>JUICIOS POR FECHA EXPEDICION</p>
        		<p><a href="juciosFecExpedicion.php"><img src="img/excellogo.png" border="0" width="90" height="90"/></a></p>
     		</td>
    	</tr>
  	</table>
</div>
</body>
</html>
