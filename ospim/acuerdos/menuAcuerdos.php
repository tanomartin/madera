<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Men&uacute; Acuerdos </h3>
  	<table width="600" border="1" style="text-align: center;">
    	<tr>
      		<td width="200">
      			<p>A.B.M.C.</p>
        		<p><a href="abm/moduloABM.php"><img src="img/abmacuerdo.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td width="200">
      			<p>BOLESTAS DE PAGO</p>
        		<p><a href="impresion/menuBoletas.php"><img src="img/impresora.png" width="90" height="90" border="0"/></a></p>
      		</td>
      		<td width="200">
      			<p>BANCO</p>
        		<p><a href="banco/moduloBanco.php"><img src="img/banco.png" width="90" height="90" border="0"/></a></p>
        	</td>
    	</tr>
    	<tr>
      		<td>
      			<p>CANELACION <br/>MANUAL DE CUOTAS </p>
      			<p><a href="cancelacion/moduloCancelacion.php"><img src="img/cancelado.png" width="90" height="90" border="0" /></a></p>
      		</td>
      		<td>
      			<p>INFORMES</p>
        		<p><a href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
     		</td>
      		<td>
      			<p>VALORES AL COBRO</p>
      			<p><a href="valores/menuValores.php"><img src="img/valores.png" width="90" height="90" border="0" /></a></p> 
      		</td>
    	</tr>
  	</table>
</div>
</body>
</html>
