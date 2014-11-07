<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados OSPIM :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<body bgcolor="#CCCCCC">
<div align="center">
	<h2>Men&uacute; Afiliados</h2>
</div>
<div align="center">
  <table width="600" border="3">
    <tr>
      <td width="196"><p align="center">Alta, Modificaci&oacute;n y Consulta</p>
        <p align="center"><a class="enlace" href="#"><img src="img/abmafil.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Carnets</p>
          <p align="center"><a class="enlace" href="#"><img src="img/carnet.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Solicitudes de Autorizaci&oacute;n</p>
          <p align="center"><a class="enlace" href="verificaciones/buscaSolicitudes.php"><img src="img/autorizaciones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
