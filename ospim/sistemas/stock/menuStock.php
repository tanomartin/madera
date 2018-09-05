<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" /></p>
  	<h3>Menú Stock</h3>
  	<table width="600px" border="1" style="text-align: center">
	    <tr>
	    	<td width="200px">
	      		<p>STOCK</p>
	       		<p><a href="abm/stock.php"><img src="img/stock.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	      	<td width="200px">
	      		<p>CONSUMOS</p>
	        	<p><a href="consumos/consumos.php"><img src="img/consumos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
		   	<td width="200px">
		   		<p>PEIDIDOS</p>
	        		<p><a href="pedidos/pedidos.php"><img src="img/pedidos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	    </tr>
	    <tr>
	    	<td>
	       		<p>PRODUCTOS</p>
	       		<p><a href="productos/productos.php"><img src="img/productos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	       	</td>
	      	<td>
	      		<p>INSUMOS</p>
	      		<p><a href="insumos/insumos.php"><img src="img/insumos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	      	 <td>
		   		<p>SEGURO</p>
	       		<p><a href="seguro/moduloSeguro.php"><img src="img/seguro.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	    </tr>
  	</table>
</div>
</body>
</html>
