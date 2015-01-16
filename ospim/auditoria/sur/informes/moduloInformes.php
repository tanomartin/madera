<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes SUR OSPIM :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#CCCCCC">
<div align="center">
	<input type="reset" name="volver" value="Volver" onClick="location.href = '../menuSur.php'" align="center"/> 
</div>
<div align="center">
	<h2>Men&uacute; Consultas e Informes</h2>
</div>
<div align="center">
  <table width="420" border="3">
    <tr>
      <td width="200"><p align="center">Certificado por Fecha Vto. </p>
          <p align="center"><a class="enlace" href="certificadosPorVto.php"><img src="img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
		 <td width="200"><p align="center">Discapacitados por Delegación</p>
          <p align="center"><a class="enlace" href="dicapacitadosPorDelegacion.php"><img src="img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
