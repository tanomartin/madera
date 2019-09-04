<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Tesoreria :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h2>Menú Tesorería </h2>
  	<table width="600" border="1" style="text-align: center">
    	<tr>
	  		<td width="200">
	  			<p>ORDENES DE PAGO </p>
          		<p><a href="ordenes/menuOrdenes.php"><img src="img/ordenespago.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td width="200">
      			<p>CONTROL CAPITAS</p>
          		<p><a href="capitas/controlCapitas.php"><img src="img/padrones.png" width="90" height="90" border="0" /></a></p>
      		</td>
	   		<td width="200">
	   			<p>FACTURACION PRESTADORES</p>
          		<p><a href="../moduloNoDisponible.php"><img src="img/factura.png" width="119" height="92" border="0" /></a></p>
       		</td>	
    	</tr>
  	</table>
</div>
</body>
</html>
