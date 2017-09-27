<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Seguimiento :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'"/></p>
  <h2>Men&uacute; Seguimiento </h2>
  <table width="400" border="3" style="text-align: center">
    <tr>
	  <td width="200">
	  	<p>A.B.M.C.</p>
        <p><a class="enlace" href="moduloABM.php"><img src="img/seguimiento.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p>
      </td>
      <td width="200">
      	<p>Informes </p>
        <p><a class="enlace" href="informes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>