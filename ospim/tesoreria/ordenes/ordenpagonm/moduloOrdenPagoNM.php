<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Ordenes de Pago Medicas No Medicas :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuOrdenes.php'" /></p>	
	<h3>Men� Ordenes de Pago M�dicas No M�dicas </h3>
  	<table width="400" border="1" style="text-align: center">
    	<tr>
	  		<td width="200">
	  			<p>GENERAR ORDEN</p>
          		<p><a href="abm/nuevaOrdenPagoNM.php"><img src="../img/ordenespagonm.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td width="200">
      			<p>CONSULTAR</p>
          		<p><a href="buscador/buscarOrdenNM.php"><img src="../img/buscar.png" width="90" height="90" border="0" /></a></p>
      		</td>	
      	</tr>
  	</table>
</div>
</body>
</html>
