<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizacion :.</title>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  <h3>Menú Fiscalizacion </h3>
	  <table width="600" border="1" style="text-align: center">
	    <tr>
	      <td width="200">
	      	<p>CONFIGURACIÓN</p>
	        <p><a href="configuracion/menuConfiguracionFiscalizacion.php"><img src="img/configuracion.png" width="90" height="90" border="0" /></a></p>
	      </td>
	      <td width="200">
	      	<p>BOLETAS DE ACUERDOS</p>
	        <p><a href="acuerdos/fiscalizacionImpresion.php"><img src="img/impresora.png" width="90" height="90" border="0" /></a></p>
	      </td>
		  <td width="200">
		  	<p>FISCALIZACION </p>
			<p><a href="fiscalizacion/menuFiscalizaciones.php"><img src="img/fiscalizador.png" width="90" height="78" border="0" /></a></p>
	      </td>
	    </tr>
	  </table>
	</div>
</body>
</html>
