<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizacion :.</title>

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

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuFiscalizacion.php'" />
  </span></p>
  <p><span class="Estilo2">Men&uacute; Fiscalizador </span></p>
  <table width="626" border="3">
    <tr>
      <td width="200"><p align="center">Requerimientos</p>
        <p align="center"><a class="enlace" href="requerimientos/requerimientos.php"><img src="img/requerimientos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	  <td width="200"><p align="center">Inspecciones </p>
      <p align="center"><a class="enlace" href="requerimientos/listarInspecciones.php"><img src="img/inspeccion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	  <p align="center">&nbsp;</p></td>
	  <td width="200"><p align="center">Fiscalizador </p>
        <p align="center"><a class="enlace" href="fiscalizador/fiscalizador.php"><img src="img/fiscalizador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">&nbsp;</p>
      <p align="center">&nbsp;</p></td>
      <td><p align="center">Informes</p>
      <p align="center"><a class="enlace" href="informes/moduloInformes.php"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>