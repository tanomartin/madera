<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'"/></p>
  <h3>Menú Intranet </h3>
  <table width="400" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>O.S.P.I.M. </p>
        <p><a class="enlace" href="intraospim/menuActualizacionOspim.php"><img src="img/intraospim.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td  width="200">
      	<p>U.S.I.M.R.A. </p>
        <p><a href="intrausimra/menuActualizacionUsimra.php"><img src="img/intrausimra.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>