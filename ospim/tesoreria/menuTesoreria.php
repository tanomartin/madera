<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Tesoreria OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo2">Men&uacute; Tesorer&iacute;a </span></p>
  <table width="600" border="3">
    <tr>
	  <td width="200"><p align="center">&Oacute;rdenes de Pago </p>
          <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/ordenespago.png" width="90" height="90" border="0" alt="enviar"/></a></p>
          <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Control C&aacute;pitas</p>
          <p align="center"><a class="enlace" href="capitas/controlCapitas.php"><img src="img/padrones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	   <td width="200"><p align="center">Liquidaci&oacute;n </p>
          <p align="center"><a class="enlace" href="../moduloNoDisponible.php"><img src="img/factura.png" width="119" height="92" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>		
    </tr>
  </table>
</div>
</body>
</html>
