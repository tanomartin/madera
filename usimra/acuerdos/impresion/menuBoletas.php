<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo USIMRA :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#B2A274">
<div align="center">
  <p><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="../menuAcuerdos.php">VOLVER</a></strong></font></p>
  <p><span class="Estilo2">Men&uacute; Boletas </span></p>
  <table width="614" border="3">
    <tr>
      <td width="196"><p align="center">Anulacion de Boletas </p>
	  	  <!-- href="cargaAnulacion.php" -->
		  <!-- href="../../moduloNoDisponible.php" -->
          <p align="center"><a href="cargaAnulacion.php" ><img src="../img/anulacion.jpg" width="98" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196" valign="top"><p align="center">Impresi&oacute;n de Boletas </p>
      <p align="center"><a class="enlace" href="moduloImpresion.php"><img src="../img/impresora.jpg" width="98" height="84" border="0" alt="enviar"/></a></p></td>
      <td width="196"><p align="center">Buscador de Boletas </p>
        <p align="center"><a class="enlace" href="buscadorBoleta.php"><img src="../img/lupa.jpg" width="98" height="84" border="0" /> </a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
