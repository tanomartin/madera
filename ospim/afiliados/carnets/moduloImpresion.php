<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Afiliados OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /> 
</div>
<div align="center">
	<h2>Men&uacute; Carnets</h2>
</div>
<div align="center">
  <table width="392" border="3">
    <tr>
      <td width="196"><p align="center">Emisi&oacute;n de Carnets </p>
        <p align="center"><a class="enlace" href="carnetsPorDelegacion.php"><img src="../img/carnet.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196"><p align="center">Impresi&oacute;n de Carnets </p>
          <p align="center"><a class="enlace" href="listadoLotes.php"><img src="../img/impafil.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
