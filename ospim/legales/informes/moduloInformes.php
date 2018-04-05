<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Juicios :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuLegales.php'" /> </p>
  	<h3>M&oacute;dulo De Informes</h3>
  	<table style="border: double; text-align: center;">
    	<tr>
     		<td>
     			<p>JUICIOS POR  <BR> FECHA EXPEDICION</p>
        		<p><a href="juciosFecExpedicion.php"><img src="img/excellogo.png" border="0" width="90" height="90"/></a></p>
     		</td>
    	</tr>
  	</table>
</div>
</body>
</html>
