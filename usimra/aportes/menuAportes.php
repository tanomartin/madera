<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Aportes USIMRA:.</title>
</head>
<body bgcolor="#B2A274">
<div align="center">
  <h3>MENU APORTES </h3>
  <table width="600" border="3" style="text-align: center">
    <tr>
      <td width="200">
      	  <p>DESCARGA</br>APLICATIVO DDJJ </p>
          <p><a class="enlace" href="descarga/moduloDescarga.php"><img src="img/download.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	  <p>INFORMES</p>
          <p><a class="enlace" href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	  <p>EMPRESAS PAGO MINIMO</p>
          <p><a class="enlace" href="../moduloNoDisponible.php"><img src="img/minimo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>CANCELACION </br> MANUAL APORTES</p>
	  	<p><a class="enlace" href="cancelacion/moduloCancelacion.php"><img src="img/cancelado.png" width="90" height="90" border="0" /></a></p>
	  </td>
      <td>
      	<p>CANCELACION </br> MANUAL CUOTA EXTRA.</p>
	  	<p><a class="enlace" href="cancelacionExtraordinaria/moduloCancelacion.php"><img src="img/cancelado.png" width="90" height="90" border="0" /></a></p>
	  </td>
	  <td></td>
    </tr>
  </table>
</div>
</body>
</html>
