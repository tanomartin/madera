<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Intranet OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuIntranet.php'"/></p>
  <h3>Men� Intranet OSPIM</h3>
  <table width="400" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>GENERAR ARCHIVOS</p>
        <p><a href="moduloGeneracion.php"><img src="img/archivos.png" width="90" height="90" border="0" /></a></p>
      </td>
	  <td width="200">
	  	<p>ACTUALIZAR DATOS</p>
        <p><a href="moduloActualizacion.php"><img src="img/actualizacion.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
