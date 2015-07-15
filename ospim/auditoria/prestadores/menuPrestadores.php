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
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'"/>
  </p>
  <p><span class="Estilo2">Men&uacute; Prestadores </span></p>
  <table width="400" border="3">
    <tr>
	  <td width="200"><p align="center">A.B.M.C.</p>
          <p align="center"><a class="enlace" href="abm/moduloAbmPrestadores.php"><img src="img/prestadores.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Auditoria Factura </p>
          <p align="center"><a class="enlace" href="#"><img src="img/auditoria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
          <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
