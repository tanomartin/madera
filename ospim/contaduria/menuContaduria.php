<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Contaduria OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<h2>Menú Contaduría</h2>
  	<table width="600" border="1" style="text-align: center">
		<tr>
		  	<td width="200">
			  	<p>ESTADOS CONTABLES </p>
		        <p><a class="enlace" href="estadocontable/moduloEstadoContable.php"><img src="img/estadocontable.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	  		<td width="200">
	      		<p>DIF. DDJJ vs PAGOS</p>
          		<p><a class="enlace" href="ddjjvspagos/moduloDiferencia.php"><img src="img/diferencia.png" width="90" height="90" border="0" alt="enviar"/></a></p>
     		</td>
     		<td width="200">
    	   		<p>DATOS AUX. PRESTA</p>
          		<p><a class="enlace" href="prestadores/moduloPrestadores.php"><img src="img/prestador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
  			</td>
  		</tr>
  	</table>
</div>
</body>
</html>