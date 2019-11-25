<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Auditoria Medica :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h2>Menú Liquidaciones</h2>
  <table width="600" border="1" style="text-align: center;vertical-align: middle;">
    <tr>
	  <td width="200">
	   	<p>FACTURAS LIQUIDADAS </p>
      	<p><a href="facturasLiquidadas.php"><img src="img/liquidadas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
	  <td width="200">
	   	<p>LIQUIDACION PRESTADORES</p>
	   	<p><a href="moduloLiquidaciones.php"><img src="img/liquidaciones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
	  <td width="178">
	  	<p>LISTADO DE FACTURAS </p>
	  	<p><a href="../../moduloNoDisponible.php"><img src="img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  </td>
    </tr>
  </table>
</div>
</body>
</html>
