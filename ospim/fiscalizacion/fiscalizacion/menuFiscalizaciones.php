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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuFiscalizacion.php'" /></p>
  <h3>Menú Fiscalizador </h3>
  <table width="600" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>REQUERIMIENTOS</p>
        <p><a href="requerimientos/requerimientos.php"><img src="img/requerimientos.png" width="90" height="90" border="0" /></a></p>
      </td>
	  <td width="200">
	  	<p>INSPECCIONES </p>
      	<p><a href="requerimientos/listarInspecciones.php"><img src="img/inspeccion.png" width="90" height="90" border="0" /></a></p>
	  </td>
	  <td width="200">
	  	<p>FISCALIZADOR </p>
        <p><a href="fiscalizador/fiscalizador.php"><img src="img/fiscalizador.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>&nbsp;</p>
      </td>
      <td>
      	<p>INFORMES</p>
      	<p><a class="enlace" href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" /></a></p>
      </td>
      <td>
      	<p>&nbsp;</p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>