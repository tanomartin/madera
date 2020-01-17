<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Facturas :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuFacturas.php'" /></p>	
	<h3>Modulo Informes Facturas </h3>
  	<table width="200" border="1" style="text-align: center">
    	<tr>
	  		<td width="200">
	  			<p>FAC. PENDIENTES X PRESTADOR</p>
          		<p><a href="facturasPendientesPrestador.php"><img src="img/informes.png" width="90" height="90" border="0"/></a></p>
      		</td>
      
      	</tr>
  	</table>
</div>
</body>
</html>
