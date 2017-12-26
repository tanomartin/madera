<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Tesoreria OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h2>Men&uacute; Tesorer&iacute;a </h2>
  <table width="400" border="3" style="text-align: center">
    <tr>
	  <td width="200">
	  		<p>&Oacute;rdenes de Pago </p>
          	<p><a class="enlace" href="../moduloNoDisponible.php"><img src="img/ordenespago.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      		<p>Control C&aacute;pitas</p>
          	<p><a class="enlace" href="capitas/controlCapitas.php"><img src="img/padrones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
	   <td>
	   		<p>Facturacion Prestadores</p>
          	<p><a class="enlace" href="facturas/moduloFacturas.php"><img src="img/factura.png" width="119" height="92" border="0" alt="enviar"/></a></p>
       </td>
       <td>
	   		<p>Datos Aux. Prestadores</p>
          	<p><a class="enlace" href="prestadores/moduloPrestadores.php"><img src="img/prestador.png" width="119" height="92" border="0" alt="enviar"/></a></p>
       </td>		
    </tr>
  </table>
</div>
</body>
</html>
