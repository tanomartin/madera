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
	<h3>Menú Ordenes de Pago Médicas No Médicas </h3>
  	<table width="600" border="1" style="text-align: center">
    	<tr>
	  		<td width="200">
	  			<p>CARGAR</p>
          		<p><a href="abm/nuevaOrdenPagoNM.php"><img src="../img/ordenespagonm.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td width="200">
	  			<p>IMPUTAR / GENERAR</p>
          		<p><a href="abm/listadoImputaOrdenPagoNM.php"><img src="../img/imputarpagonm.png" width="90" height="90" border="0"/></a></p>
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
